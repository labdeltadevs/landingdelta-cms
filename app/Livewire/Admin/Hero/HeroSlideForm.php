<?php

namespace App\Livewire\Admin\Hero;

use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\FileUploadConfiguration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Slide del Hero')]
class HeroSlideForm extends Component
{
    use WithFileUploads;

    public ?HeroSlide $heroSlide = null;

    public string $title = '';

    public string $subtitle = '';

    public string $cta_label = '';

    public string $cta_url = '';

    public ?int $slideable_id = null;

    public string $slideable_type = '';

    public bool $is_active = true;

    public $image;

    public function mount(?HeroSlide $heroSlide = null): void
    {
        $this->heroSlide = $heroSlide;

        if ($this->heroSlide?->exists) {
            $this->title = $this->heroSlide->title;
            $this->subtitle = $this->heroSlide->subtitle ?? '';
            $this->cta_label = $this->heroSlide->cta_label ?? '';
            $this->cta_url = $this->heroSlide->cta_url ?? '';
            $this->slideable_id = $this->heroSlide->slideable_id;
            $this->slideable_type = $this->heroSlide->slideable_type ?? '';
            $this->is_active = $this->heroSlide->is_active;
        }
    }

    public function save(): void
    {
        $this->authorize($this->heroSlide?->exists ? 'update' : 'create', $this->heroSlide ?? HeroSlide::class);

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'slideable_id' => 'nullable|integer',
            'slideable_type' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($this->image && is_string($this->image)) {
            $data['image_path'] = $this->image;

            if ($this->heroSlide?->exists && $this->heroSlide->image_path) {
                Storage::disk('public')->delete($this->heroSlide->image_path);
            }
        }

        if ($this->heroSlide?->exists) {
            $this->heroSlide->update($data);
        } else {
            $this->heroSlide = HeroSlide::query()->create($data);
        }

        $this->dispatch('notify', message: 'Slide guardado correctamente.');
        $this->redirect(route('admin.hero.index'), navigate: true);
    }

    public function _finishUpload($name, $tmpPath, $isMultiple, $append = true): void
    {
        if (FileUploadConfiguration::shouldCleanupOldUploads()) {
            $this->cleanupOldUploads();
        }

        $tmpPath = collect($tmpPath)->map(function ($signedPath) {
            $path = TemporaryUploadedFile::extractPathFromSignedPath($signedPath);

            if ($path === false) {
                abort(403, 'Invalid upload reference.');
            }

            return $path;
        })->toArray();

        $filename = $tmpPath[0];
        $disk = FileUploadConfiguration::disk();
        $storagePath = FileUploadConfiguration::path($filename, false);

        if (! Storage::disk($disk)->exists($storagePath)) {
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = Str::uuid().'.'.$extension;
        $newPath = 'hero/'.$newFilename;

        Storage::disk('public')->put($newPath, Storage::disk($disk)->get($storagePath));

        Storage::disk($disk)->delete($storagePath);
        Storage::disk($disk)->delete($storagePath.'.json');

        $this->image = $newPath;

        $this->dispatch('upload:finished', name: $name, tmpFilenames: [$filename])->self();
        app('livewire')->updateProperty($this, $name, $newPath);
    }

    public function render(): View
    {
        return view('livewire.admin.hero.hero-slide-form', [
            'products' => Product::query()->active()->orderBy('name')->get(),
            'brands' => Brand::query()->active()->ordered()->get(),
        ]);
    }
}
