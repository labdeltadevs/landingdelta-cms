<div>
<flux:heading>Slides del Hero</flux:heading>
<flux:subheading>Gestión de los avisos que se muestran a los visitantes.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    @can('create', App\Models\HeroSlide::class)
        <flux:button :href="route('admin.hero.reorder')" wire:navigate icon="arrows-up-down" variant="ghost">Reordenar</flux:button>
        <flux:button :href="route('admin.hero.create')" wire:navigate icon="plus">Nuevo slide</flux:button>
    @endcan
</div>

<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($slides as $slide)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="aspect-video bg-zinc-100 dark:bg-zinc-800">
                <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="h-full w-full object-cover" />
            </div>
            <div class="p-4">
                <h3 class="font-medium text-sm">{{ $slide->title }}</h3>
                @if ($slide->subtitle)
                    <p class="text-xs text-zinc-500 mt-1">{{ $slide->subtitle }}</p>
                @endif
                @if ($slide->valid_from || $slide->valid_until)
                    <p class="text-xs text-zinc-400 mt-1">
                        {{ $slide->valid_from?->format('d/m/Y') }} → {{ $slide->valid_until?->format('d/m/Y') }}
                    </p>
                @endif
                <div class="mt-3 flex items-center justify-between">
                    <span class="inline-flex items-center gap-1 rounded-full bg-{{ $slide->is_active ? 'green' : 'zinc' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $slide->is_active ? 'green' : 'zinc' }}-700">
                        {{ $slide->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    @if ($slide->valid_from || $slide->valid_until)
                        <span @class([
                            'inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium',
                            'bg-emerald-100 text-emerald-700' => $slide->isValid(),
                            'bg-amber-100 text-amber-700' => ! $slide->isValid(),
                        ])>
                            {{ $slide->isValid() ? 'Vigente' : 'Fuera de vigencia' }}
                        </span>
                    @endif
                    @canany(['update', 'delete'], $slide)
                        <flux:dropdown align="end">
                            <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                            <flux:menu>
                                @can('update', $slide)
                                    <flux:menu.item :href="route('admin.hero.edit', $slide)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                @endcan
                                @can('delete', $slide)
                                    <flux:menu.item wire:click="delete({{ $slide->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                @endcan
                            </flux:menu>
                        </flux:dropdown>
                    @endcanany
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <p class="py-12 text-center text-sm text-zinc-400">No hay slides registrados.</p>
        </div>
    @endforelse
</div>
</div>