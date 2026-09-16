<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
use App\Services\ImageOptimizer;
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
#[Title('División')]
class BrandForm extends Component
{
    use WithFileUploads;

    public ?Brand $brand = null;

    public string $name = '';

    public string $description = '';

    public bool $is_active = true;

    public $logo;

    public function mount(?Brand $brand = null): void
    {
        $this->brand = $brand;

        if ($this->brand?->exists) {
            $this->name = $this->brand->name;
            $this->description = $this->brand->description ?? '';
            $this->is_active = $this->brand->is_active;
        }
    }

    public function save(): void
    {
        $this->authorize($this->brand?->exists ? 'update' : 'create', $this->brand ?? Brand::class);

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $this->brand?->exists
            ? Str::slug($this->name).'-'.$this->brand->id
            : Str::slug($this->name).'-'.Str::lower(Str::random(5));

        if ($this->logo && is_string($this->logo)) {
            $data['logo_path'] = $this->logo;

            if ($this->brand?->exists && $this->brand->logo_path) {
                Storage::disk('public')->delete($this->brand->logo_path);
            }
        }

        if ($this->brand?->exists) {
            $this->brand->update($data);
        } else {
            $this->brand = Brand::query()->create($data);
        }

        $this->dispatch('notify', message: 'División guardada correctamente.');
        $this->redirect(route('admin.brands.index'), navigate: true);
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

        if (! app(ImageOptimizer::class)->allowsExtension($extension)
            || Storage::disk($disk)->size($storagePath) > 5 * 1024 * 1024) {
            Storage::disk($disk)->delete($storagePath);
            Storage::disk($disk)->delete($storagePath.'.json');
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        try {
            $newPath = app(ImageOptimizer::class)->optimizeLivewireTempFile($disk, $storagePath, 'brand');
        } catch (\Throwable) {
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        $this->logo = $newPath;

        $this->dispatch('upload:finished', name: $name, tmpFilenames: [$filename])->self();
        app('livewire')->updateProperty($this, $name, $newPath);
    }

    public function render(): View
    {
        return view('livewire.admin.brands.brand-form');
    }
}
