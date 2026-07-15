<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\FileUploader;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
#[Title('Producto')]
class ProductForm extends Component
{
    use WithFileUploads;

    public ?Product $product = null;
    public string $name = '';
    public string $active_ingredient = '';
    public string $description = '';
    public ?float $approx_price = null;
    public ?int $brand_id = null;
    public ?int $category_id = null;
    public bool $is_active = true;
    public bool $is_featured = false;
    public $upload;

    public function mount(?Product $product = null): void
    {
        $this->product = $product;

        if ($product->exists) {
            $this->name = $product->name;
            $this->active_ingredient = $product->active_ingredient ?? '';
            $this->description = $product->description ?? '';
            $this->approx_price = $product->approx_price;
            $this->brand_id = $product->brand_id;
            $this->category_id = $product->category_id;
            $this->is_active = $product->is_active;
            $this->is_featured = $product->is_featured;
        }
    }

    public function save(FileUploader $uploader): void
    {
        $this->authorize($this->product?->exists ? 'update' : 'create', Product::class);

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'active_ingredient' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'approx_price' => 'nullable|numeric|min:0|max:999999.99',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'upload' => 'nullable|image|max:4096',
        ]);

        $data['slug'] = $this->product?->exists
            ? Str::slug($this->name).'-'.$this->product->id
            : Str::slug($this->name).'-'.Str::lower(Str::random(5));

        if ($this->upload) {
            $data['main_image_path'] = $uploader->upload($this->upload, 'products');
            if ($this->product?->exists) {
                $uploader->delete($this->product->main_image_path);
            }
        }

        unset($data['upload']);

        if ($this->product?->exists) {
            $this->product->update($data);
        } else {
            $this->product = Product::query()->create($data);
        }

        $this->dispatch('notify', message: 'Producto guardado correctamente.');
        $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.products.product-form', [
            'brands' => Brand::query()->ordered()->get(),
            'categories' => Category::query()->ordered()->get(),
        ]);
    }
}
