<div>
<flux:heading>Usuarios</flux:heading>
<flux:subheading>Gestión de usuarios del sistema.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar usuarios..." class="max-w-sm" />
    @can('create', App\Models\User::class)
        <flux:button :href="route('admin.users.create')" wire:navigate icon="plus">Nuevo usuario</flux:button>
    @endcan
</div>

<div class="mt-4 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead class="bg-zinc-50 dark:bg-zinc-800">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Nombre</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Email</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Rol</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($users as $user)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-sm font-medium">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-sm">
                        @foreach ($user->roles as $role)
                            <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-700">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-right">
                        @can('update', $user)
                            <flux:button :href="route('admin.users.edit', $user)" wire:navigate icon="pencil" variant="ghost" size="sm">Editar</flux:button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-sm text-zinc-400">No hay usuarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $users->links() }}</div>
</div>
