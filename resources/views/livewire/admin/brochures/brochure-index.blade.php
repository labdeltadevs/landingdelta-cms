<div>
<flux:heading>Rotafolios</flux:heading>
<flux:subheading>Gestión de rotafolios o catálogos en PDF.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    @can('create', App\Models\Brochure::class)
        <flux:button :href="route('admin.brochures.create')" wire:navigate icon="plus">Nuevo rotafolio</flux:button>
    @endcan
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($brochures as $brochure)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4">
            <h3 class="font-medium">{{ $brochure->title }}</h3>
            @if ($brochure->line)
                <p class="text-xs text-zinc-500 mt-1">{{ $brochure->line }}</p>
            @endif
            <div class="mt-3 flex items-center justify-between">
                <a href="{{ $brochure->file_url }}" target="_blank" class="text-sm text-orange-600 hover:underline">Ver PDF</a>
                @canany(['update', 'delete'], $brochure)
                    <flux:dropdown align="end">
                        <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                        <flux:menu>
                            @can('update', $brochure)
                                <flux:menu.item :href="route('admin.brochures.edit', $brochure)" wire:navigate icon="pencil">Editar</flux:menu.item>
                            @endcan
                            @can('delete', $brochure)
                                <flux:menu.item wire:click="delete({{ $brochure->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                            @endcan
                        </flux:menu>
                    </flux:dropdown>
                @endcanany
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <p class="py-12 text-center text-sm text-zinc-400">No hay rotafolios registrados.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $brochures->links() }}</div>
</div>