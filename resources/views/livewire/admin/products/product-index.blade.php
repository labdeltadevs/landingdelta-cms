<div>
<flux:heading>Productos</flux:heading>
<flux:subheading>Gestión de productos del catálogo.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar productos..." class="max-w-sm" />
    @can('create', App\Models\Product::class)
        <flux:button :href="route('admin.products.create')" wire:navigate icon="plus">Nuevo producto</flux:button>
    @endcan
</div>

<div class="mt-4 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead class="bg-zinc-50 dark:bg-zinc-800">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Nombre</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Marca</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Categoría</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Precio aprox.</th>
                <th class="px-4 py-3 text-center text-sm font-medium text-zinc-500">Destacado</th>
                <th class="px-4 py-3 text-center text-sm font-medium text-zinc-500">Activo</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($products as $product)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-sm font-medium">{{ $product->name }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $product->brand?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $product->formatted_price }}</td>
                    <td class="px-4 py-3 text-center">
                        <flux:switch wire:click="toggleFeatured({{ $product->id }})" :checked="$product->is_featured" />
                    </td>
                    <td class="px-4 py-3 text-center">
                        <flux:switch wire:click="toggleActive({{ $product->id }})" :checked="$product->is_active" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <flux:dropdown align="end">
                            <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                            <flux:menu>
                                @can('update', $product)
                                    <flux:menu.item :href="route('admin.products.edit', $product)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                @endcan
                                @can('delete', $product)
                                    <flux:menu.item wire:click="delete({{ $product->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                @endcan
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-sm text-zinc-400">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $products->links(data: ['layout' => 'pagination']) }}
</div>
</div>
