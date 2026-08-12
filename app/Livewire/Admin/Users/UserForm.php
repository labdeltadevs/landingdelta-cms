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
        if ($this->user?->exists) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role_id = $user->roles()->first()?->id;
            // getPermissionNames() solo devuelve permisos directos; usamos
            // getAllPermissions() para reflejar también los del rol.
            $this->permissions = $user->getAllPermissions()->pluck('name')->toArray();
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

        // Solo se guardan como directos los permisos que el rol no otorga ya,
        // para no duplicarlos ni dejarlos huérfanos si el rol cambia.
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $this->user->syncPermissions(array_values(array_diff($this->permissions, $rolePermissions)));

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
