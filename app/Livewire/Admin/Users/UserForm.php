<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class UserForm extends Component
{
    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public ?int $role_id = null;

    public array $permissions = [];

    public function mount(?User $user = null): void
    {
        $this->user = $user;
        if ($user->exists) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role_id = $user->roles()->first()?->id;
            $this->permissions = $user->getPermissionNames()->toArray();
        }
    }

    public function save(): void
    {
        $this->authorize($this->user?->exists ? 'update' : 'create', $this->user ?? User::class);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email'.($this->user?->exists ? ','.$this->user->id : ''),
            'role_id' => 'required|exists:roles,id',
        ];

        if (! $this->user?->exists) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $data = $this->validate($rules);

        if ($this->user?->exists) {
            unset($data['password']);
            $this->user->update($data);
        } else {
            $data['password'] = bcrypt($this->password);
            $this->user = User::query()->create($data);
        }

        $role = Role::findById($this->role_id, 'web');
        $this->user->syncRoles([$role->name]);

        $this->user->syncPermissions($this->permissions);

        $this->dispatch('notify', message: 'Usuario guardado correctamente.');
        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.users.user-form', [
            'roles' => Role::query()->where('guard_name', 'web')->get(),
            'allPermissions' => Permission::query()->where('guard_name', 'web')->orderBy('name')->get(),
        ]);
    }
}
