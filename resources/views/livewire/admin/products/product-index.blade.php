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
                <th class="px-4 py-3 text-center text-sm font-medium text-zinc-500">Destacado</th>
                <th class="px-4 py-3 text-center text-sm font-medium text-zinc-500">Activo</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($products as $product)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-sm font-medium">
                        <span class="inline-flex items-center gap-1.5">
                            @if (filled($product->main_image_path))
                                <svg class="h-4 w-4 shrink-0 text-emerald-500" fill="none" stroke="currentColor"
                                    stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <title>Con imagen</title>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                <span class="sr-only">Con imagen:</span>
                            @else
                                <svg class="h-4 w-4 shrink-0 text-zinc-300 dark:text-zinc-600" fill="none"
                                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <title>Sin imagen</title>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                <span class="sr-only">Sin imagen:</span>
                            @endif
                            {{ $product->name }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $product->brand?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        @can('update', $product)
                            <flux:switch wire:click="toggleFeatured({{ $product->id }})" :checked="$product->is_featured" />
                        @else
                            <span class="text-xs text-zinc-400">{{ $product->is_featured ? 'Sí' : 'No' }}</span>
                        @endcan
                    </td>
                    <td class="px-4 py-3 text-center">
                        @can('update', $product)
                            <flux:switch wire:click="toggleActive({{ $product->id }})" :checked="$product->is_active" />
                        @else
                            <span class="text-xs text-zinc-400">{{ $product->is_active ? 'Sí' : 'No' }}</span>
                        @endcan
                    </td>
                    <td class="px-4 py-3 text-right">
                        @canany(['update', 'delete'], $product)
                            <flux:dropdown align="end">
                                <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                                <flux:menu>
                                    <flux:menu.item wire:click="showQr({{ $product->id }})" icon="qr-code">Generar QR</flux:menu.item>
                                    @can('update', $product)
                                        <flux:menu.item :href="route('admin.products.edit', $product)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                    @endcan
                                    @can('delete', $product)
                                        <flux:menu.item wire:click="delete({{ $product->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                    @endcan
                                </flux:menu>
                            </flux:dropdown>
                        @endcanany
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-sm text-zinc-400">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $products->links(data: ['layout' => 'pagination']) }}
</div>

@include('livewire.admin.partials.qr-modal')
</div>
