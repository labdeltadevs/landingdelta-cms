<?php

namespace App\Livewire\Admin\Brands;

use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Marcas')]
class BrandIndex extends Component
{
    use WithPagination;

    public string $search = '';
    protected $queryString = ['search'];
    public function updatingSearch(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $brand = Brand::query()->findOrFail($id);
        $this->authorize('delete', $brand);
        $brand->delete();
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
