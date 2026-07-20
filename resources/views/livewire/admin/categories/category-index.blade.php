<div>
<flux:heading>Categorías</flux:heading>
<flux:subheading>Gestión de categorías de productos.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar categorías..." class="max-w-sm" />
    @can('create', App\Models\Category::class)
        <flux:button :href="route('admin.categories.create')" wire:navigate icon="plus">Nueva categoría</flux:button>
    @endcan
</div>

<div class="mt-4 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead class="bg-zinc-50 dark:bg-zinc-800">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Nombre</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Productos</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($categories as $category)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-sm font-medium">{{ $category->name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $category->products_count }}</td>
                    <td class="px-4 py-3 text-right">
                        <flux:dropdown align="end">
                            <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                            <flux:menu>
                                @can('update', $category)
                                    <flux:menu.item :href="route('admin.categories.edit', $category)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                @endcan
                                @can('delete', $category)
                                    <flux:menu.item wire:click="delete({{ $category->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                @endcan
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-12 text-center text-sm text-zinc-400">No hay categorías registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $categories->links() }}</div>
</div>
