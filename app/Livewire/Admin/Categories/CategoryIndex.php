<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Categorías')]
class CategoryIndex extends Component
{
    use WithPagination;

    public string $search = '';
    protected $queryString = ['search'];
    public function updatingSearch(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $category = Category::query()->findOrFail($id);
        $this->authorize('delete', $category);
        $category->delete();
    }

    public function render(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orderBy('sort')
            ->paginate(15);

        return view('livewire.admin.categories.category-index', compact('categories'));
    }
}
