<div>
<flux:heading>Slides del Hero</flux:heading>
<flux:subheading>Gestión de los slides del carrusel principal.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    @can('manage hero', App\Models\HeroSlide::class)
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
                <div class="mt-3 flex items-center justify-between">
                    <span class="inline-flex items-center gap-1 rounded-full bg-{{ $slide->is_active ? 'green' : 'zinc' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $slide->is_active ? 'green' : 'zinc' }}-700">
                        {{ $slide->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                    <flux:dropdown align="end">
                        <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                        <flux:menu>
                            @can('manage hero')
                                <flux:menu.item :href="route('admin.hero.edit', $slide)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                <flux:menu.item wire:click="delete({{ $slide->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                            @endcan
                        </flux:menu>
                    </flux:dropdown>
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