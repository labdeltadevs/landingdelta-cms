<?php

namespace App\Livewire\Admin\Brands;

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
#[Title('Marcas')]
class BrandIndex extends Component
{
    use GeneratesQrCodes;
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $brand = Brand::query()->findOrFail($id);
        $this->authorize('delete', $brand);
        $brand->delete();
    }

    protected function qrTarget(int $id): Brand
    {
        return Brand::query()->findOrFail($id);
    }

    protected function qrRoute(Product|Brand $target): string
    {
        return route('public.brands.show', $target);
    }

    protected function qrFilename(Model $target): string
    {
        return 'qr-marca-'.($target->slug ?? $target->getRouteKey()).'.jpg';
    }

    public function render(): View
    {
        $brands = Brand::query()
            ->withCount('products')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orderBy('sort')
            ->paginate(15);

        return view('livewire.admin.brands.brand-index', compact('brands'));
    }
}
