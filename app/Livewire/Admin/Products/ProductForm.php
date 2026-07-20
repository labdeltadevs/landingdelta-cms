<?php

namespace App\Livewire\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
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

        if ($this->product?->exists) {
            $this->name = $this->product->name;
            $this->active_ingredient = $this->product->active_ingredient ?? '';
            $this->description = $this->product->description ?? '';
            $this->approx_price = $this->product->approx_price;
            $this->brand_id = $this->product->brand_id;
            $this->category_id = $this->product->category_id;
            $this->is_active = $this->product->is_active;
            $this->is_featured = $this->product->is_featured;
        }
    }

    public function save(): void
    {
        $this->authorize($this->product?->exists ? 'update' : 'create', $this->product ?? Product::class);

        $data = $this->validate([
            'name' => 'required|string|max:255',
            'active_ingredient' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'approx_price' => 'nullable|numeric|min:0|max:999999.99',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data['slug'] = $this->product?->exists
            ? Str::slug($this->name).'-'.$this->product->id
            : Str::slug($this->name).'-'.Str::lower(Str::random(5));

        if ($this->upload && is_string($this->upload)) {
            $data['main_image_path'] = $this->upload;

            if ($this->product?->exists && $this->product->main_image_path) {
                Storage::disk('public')->delete($this->product->main_image_path);
            }
        }

        if ($this->product?->exists) {
            $this->product->update($data);
        } else {
            $this->product = Product::query()->create($data);
        }

        $this->dispatch('notify', message: 'Producto guardado correctamente.');
        $this->redirect(route('admin.products.index'), navigate: true);
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
        $newPath = 'products/'.$newFilename;

        Storage::disk('public')->put($newPath, Storage::disk($disk)->get($storagePath));

        Storage::disk($disk)->delete($storagePath);
        Storage::disk($disk)->delete($storagePath.'.json');

        $this->upload = $newPath;

        $this->dispatch('upload:finished', name: $name, tmpFilenames: [$filename])->self();
        app('livewire')->updateProperty($this, $name, $newPath);
    }

    public function render(): View
    {
        return view('livewire.admin.products.product-form', [
            'brands' => Brand::query()->ordered()->get(),
            'categories' => Category::query()->ordered()->get(),
        ]);
    }
}
