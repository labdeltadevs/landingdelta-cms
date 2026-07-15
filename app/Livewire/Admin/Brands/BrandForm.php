<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
use App\Services\FileUploader;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
#[Title('Marca')]
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
        if ($brand->exists) {
            $this->name = $brand->name;
            $this->description = $brand->description ?? '';
            $this->is_active = $brand->is_active;
        }
    }

    public function save(FileUploader $uploader): void
    {
        $this->authorize($this->brand?->exists ? 'update' : 'create', Brand::class);

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:4096',
        ]);

        $data['slug'] = $this->brand?->exists
            ? Str::slug($this->name).'-'.$this->brand->id
            : Str::slug($this->name).'-'.Str::lower(Str::random(5));

        if ($this->logo) {
            $data['logo_path'] = $uploader->upload($this->logo, 'brands');
            if ($this->brand?->exists) {
                $uploader->delete($this->brand->logo_path);
            }
        }

        if ($this->brand?->exists) {
            $this->brand->update($data);
        } else {
            $this->brand = Brand::query()->create($data);
        }

        $this->dispatch('notify', message: 'Marca guardada correctamente.');
        $this->redirect(route('admin.brands.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.brands.brand-form');
    }
}
