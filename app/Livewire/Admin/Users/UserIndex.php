<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Usuarios')]
class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';
    protected $queryString = ['search'];
    public function updatingSearch(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        $user = User::query()->findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
    }

    public function render(): View
    {
        $users = User::query()
            ->with('roles')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.admin.users.user-index', compact('users'));
    }
}
