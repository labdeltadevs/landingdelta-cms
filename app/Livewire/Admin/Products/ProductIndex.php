<?php

namespace App\Livewire\Admin\Products;

use App\Livewire\Admin\Concerns\GeneratesQrCodes;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Productos')]
class ProductIndex extends Component
{
    use GeneratesQrCodes;
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleFeatured(int $id): void
    {
        $product = Product::query()->findOrFail($id);
        $this->authorize('update', $product);
        $product->update(['is_featured' => ! $product->is_featured]);
    }

    public function toggleActive(int $id): void
    {
        $product = Product::query()->findOrFail($id);
        $this->authorize('update', $product);
        $product->update(['is_active' => ! $product->is_active]);
    }

    public function delete(int $id): void
    {
        $product = Product::query()->findOrFail($id);
        $this->authorize('delete', $product);
        $product->delete();
    }

    protected function qrTarget(int $id): Product
    {
        return Product::query()->findOrFail($id);
    }

    protected function qrRoute(Product|Brand $target): string
    {
        return route('public.products.show', $target);
    }

    protected function qrFilename(Model $target): string
    {
        return 'qr-producto-'.($target->slug ?? $target->getRouteKey()).'.jpg';
    }

    public function render(): View
    {
        $products = Product::query()
            ->with('brand', 'category')
            ->search($this->search)
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.admin.products.product-index', compact('products'));
    }
}
