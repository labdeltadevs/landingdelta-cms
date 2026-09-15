<div>
<flux:heading>Divisiones</flux:heading>
<flux:subheading>Gestión de divisiones del laboratorio.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar divisiones..." class="max-w-sm" />
    @can('create', App\Models\Brand::class)
        <flux:button :href="route('admin.brands.create')" wire:navigate icon="plus">Nueva división</flux:button>
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
                    <td class="px-4 py-3 text-sm font-medium">
                        <span class="inline-flex items-center gap-1.5">
                            @if (filled($brand->logo_path))
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
                            {{ $brand->name }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $brand->products_count }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">{{ $brand->is_active ? 'Sí' : 'No' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        @canany(['update', 'delete'], $brand)
                            <flux:dropdown align="end">
                                <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                                <flux:menu>
                                    <flux:menu.item wire:click="showQr({{ $brand->id }})" icon="qr-code">Generar QR</flux:menu.item>
                                    @can('update', $brand)
                                        <flux:menu.item :href="route('admin.brands.edit', $brand)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                    @endcan
                                    @can('delete', $brand)
                                        <flux:menu.item wire:click="delete({{ $brand->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                    @endcan
                                </flux:menu>
                            </flux:dropdown>
                        @endcanany
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-sm text-zinc-400">No hay divisiones registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $brands->links() }}</div>

@include('livewire.admin.partials.qr-modal')
</div>
