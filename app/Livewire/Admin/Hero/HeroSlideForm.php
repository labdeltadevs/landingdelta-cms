<?php

namespace App\Livewire\Admin\Hero;

use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\Brand;
use App\Services\FileUploader;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
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
        if ($heroSlide->exists) {
            $this->title = $heroSlide->title;
            $this->subtitle = $heroSlide->subtitle ?? '';
            $this->cta_label = $heroSlide->cta_label ?? '';
            $this->cta_url = $heroSlide->cta_url ?? '';
            $this->slideable_id = $heroSlide->slideable_id;
            $this->slideable_type = $heroSlide->slideable_type ?? '';
            $this->is_active = $heroSlide->is_active;
        }
    }

    public function save(FileUploader $uploader): void
    {
        $this->authorize($this->heroSlide?->exists ? 'update' : 'create', HeroSlide::class);

        $data = $this->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'slideable_id' => 'nullable|integer',
            'slideable_type' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:4096',
        ]);

        if ($this->image) {
            $data['image_path'] = $uploader->upload($this->image, 'hero');
            if ($this->heroSlide?->exists) {
                $uploader->delete($this->heroSlide->image_path);
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

    public function render(): View
    {
        return view('livewire.admin.hero.hero-slide-form', [
            'products' => Product::query()->active()->orderBy('name')->get(),
            'brands' => Brand::query()->active()->ordered()->get(),
        ]);
    }
}
