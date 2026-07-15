<?php

namespace App\Livewire\Admin\Branches;

use App\Models\Branch;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Sucursales')]
class BranchIndex extends Component
{
    use WithPagination;

    public string $search = '';
    protected $queryString = ['search'];
    public function updatingSearch(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $branch = Branch::query()->findOrFail($id);
        $this->authorize('delete', $branch);
        $branch->delete();
    }

    public function render(): View
    {
        $branches = Branch::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('city', 'like', '%'.$this->search.'%')
            ->orderBy('sort')
            ->paginate(15);

        return view('livewire.admin.branches.branch-index', compact('branches'));
    }
}
