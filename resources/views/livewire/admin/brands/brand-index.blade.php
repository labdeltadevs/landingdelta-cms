<div>
<flux:heading>Marcas</flux:heading>
<flux:subheading>Gestión de marcas del laboratorio.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar marcas..." class="max-w-sm" />
    @can('create', App\Models\Brand::class)
        <flux:button :href="route('admin.brands.create')" wire:navigate icon="plus">Nueva marca</flux:button>
    @endcan
</div>

<div class="mt-4 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead class="bg-zinc-50 dark:bg-zinc-800">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Nombre</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Productos</th>
                <th class="px-4 py-3 text-center text-sm font-medium text-zinc-500">Activo</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($brands as $brand)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-sm font-medium">{{ $brand->name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $brand->products_count }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">{{ $brand->is_active ? 'Sí' : 'No' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <flux:dropdown align="end">
                            <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                            <flux:menu>
                                @can('update', $brand)
                                    <flux:menu.item :href="route('admin.brands.edit', $brand)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                @endcan
                                @can('delete', $brand)
                                    <flux:menu.item wire:click="delete({{ $brand->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                @endcan
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-sm text-zinc-400">No hay marcas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $brands->links() }}</div>
</div>
