<?php

namespace App\Livewire\Admin\Hero;

use App\Models\Brand;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Services\ImageOptimizer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
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

    public string $tag = '';

    public string $cta_label = '';

    public string $cta_url = '';

    public ?int $slideable_id = null;

    public string $slideable_type = '';

    public string $destinationSearch = '';

    public ?string $valid_from = null;

    public ?string $valid_until = null;

    public bool $is_active = true;

    public $image;

    public function mount(?HeroSlide $heroSlide = null): void
    {
        $this->heroSlide = $heroSlide;

        if ($this->heroSlide?->exists) {
            $this->title = $this->heroSlide->title;
            $this->subtitle = $this->heroSlide->subtitle ?? '';
            $this->tag = $this->heroSlide->tag ?? '';
            $this->cta_label = $this->heroSlide->cta_label ?? '';
            $this->cta_url = $this->heroSlide->cta_url ?? '';
            $this->slideable_id = $this->heroSlide->slideable_id;
            $this->slideable_type = $this->heroSlide->slideable_type ?? '';
            $this->destinationSearch = $this->heroSlide->slideable?->name ?? '';
            $this->valid_from = $this->heroSlide->valid_from?->format('Y-m-d');
            $this->valid_until = $this->heroSlide->valid_until?->format('Y-m-d');
            $this->is_active = $this->heroSlide->is_active;
        }
    }

    public function updatedSlideableType(): void
    {
        $this->slideable_id = null;
        $this->destinationSearch = '';
    }

    public function selectDestination(int $id): void
    {
        $this->slideable_id = $id;
        $this->destinationSearch = $this->selectedDestination()?->name ?? '';
    }

    public function clearDestination(): void
    {
        $this->slideable_id = null;
        $this->destinationSearch = '';
    }

    /**
     * @return Collection<int, Product|Brand>
     */
    protected function destinationResults()
    {
        if ($this->slideable_type === Product::class) {
            $query = Product::query()->active()->with('brand');

            if (strlen(trim($this->destinationSearch)) >= 2) {
                $query->search($this->destinationSearch);
            } else {
                $query->orderBy('name');
            }

            return $query->limit(8)->get();
        }

        if ($this->slideable_type === Brand::class) {
            $query = Brand::query()->active();

            if (strlen(trim($this->destinationSearch)) >= 2) {
                $query->search($this->destinationSearch);
            } else {
                $query->ordered();
            }

            return $query->limit(8)->get();
        }

        return collect();
    }

    protected function selectedDestination(): Product|Brand|null
    {
        if (blank($this->slideable_type) || blank($this->slideable_id)) {
            return null;
        }

        return match ($this->slideable_type) {
            Product::class => Product::query()->with('brand')->find($this->slideable_id),
            Brand::class => Brand::query()->find($this->slideable_id),
            default => null,
        };
    }

    protected function resolvedCtaUrl(Product|Brand|null $destination): ?string
    {
        if (filled(trim($this->cta_url))) {
            return trim($this->cta_url);
        }

        return match (true) {
            $destination instanceof Product => route('public.products.show', $destination),
            $destination instanceof Brand => route('public.brands.show', $destination),
            default => null,
        };
    }

    public function save(): void
    {
        $this->authorize($this->heroSlide?->exists ? 'update' : 'create', $this->heroSlide ?? HeroSlide::class);

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'tag' => 'nullable|string|max:60',
            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'slideable_type' => 'nullable|string|in:'.Product::class.','.Brand::class,
            'slideable_id' => [
                'nullable',
                'integer',
                match ($this->slideable_type) {
                    Product::class => 'exists:products,id',
                    Brand::class => 'exists:brands,id',
                    default => 'prohibited',
                },
            ],
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $data['valid_from'] = filled($data['valid_from']) ? $data['valid_from'] : null;
        $data['valid_until'] = filled($data['valid_until']) ? $data['valid_until'] : null;

        if (blank($data['slideable_type'])) {
            $data['slideable_type'] = null;
            $data['slideable_id'] = null;
        }

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

        if (! app(ImageOptimizer::class)->allowsExtension($extension)
            || Storage::disk($disk)->size($storagePath) > 5 * 1024 * 1024) {
            Storage::disk($disk)->delete($storagePath);
            Storage::disk($disk)->delete($storagePath.'.json');
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        try {
            $newPath = app(ImageOptimizer::class)->optimizeLivewireTempFile($disk, $storagePath, 'hero_slide');
        } catch (\Throwable) {
            $this->dispatch('upload:errored', name: $name)->self();

            return;
        }

        $this->image = $newPath;

        $this->dispatch('upload:finished', name: $name, tmpFilenames: [$filename])->self();
        app('livewire')->updateProperty($this, $name, $newPath);
    }

    public function render(): View
    {
        $selectedDestination = $this->selectedDestination();

        return view('livewire.admin.hero.hero-slide-form', [
            'destinationResults' => $selectedDestination ? collect() : $this->destinationResults(),
            'selectedDestination' => $selectedDestination,
            'resolvedCtaUrl' => $this->resolvedCtaUrl($selectedDestination),
        ]);
    }
}
