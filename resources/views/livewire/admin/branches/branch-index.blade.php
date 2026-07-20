<div>
<flux:heading>Sucursales</flux:heading>
<flux:subheading>Gestión de sucursales y oficinas.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar sucursales..." class="max-w-sm" />
    @can('create', App\Models\Branch::class)
        <flux:button :href="route('admin.branches.create')" wire:navigate icon="plus">Nueva sucursal</flux:button>
    @endcan
</div>

<div class="mt-4 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead class="bg-zinc-50 dark:bg-zinc-800">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Nombre</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Ciudad</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Teléfono</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($branches as $branch)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-sm font-medium">{{ $branch->name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $branch->city }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $branch->phone }}</td>
                    <td class="px-4 py-3 text-right">
                        <flux:dropdown align="end">
                            <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                            <flux:menu>
                                @can('update', $branch)
                                    <flux:menu.item :href="route('admin.branches.edit', $branch)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                @endcan
                                @can('delete', $branch)
                                    <flux:menu.item wire:click="delete({{ $branch->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                @endcan
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-sm text-zinc-400">No hay sucursales registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $branches->links() }}</div>
</div>
