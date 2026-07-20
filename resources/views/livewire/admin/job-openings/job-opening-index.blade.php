<div>
    <flux:heading>Ofertas Laborales</flux:heading>
    <flux:subheading>Gestión de convocatorias y ofertas de trabajo.</flux:subheading>

    <div class="mt-6 flex items-center justify-between gap-4">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar ofertas..." class="max-w-sm" />
        @can('create', App\Models\JobOpening::class)
            <flux:button :href="route('admin.job-openings.create')" wire:navigate icon="plus">Nueva oferta</flux:button>
        @endcan
    </div>

    @if ($jobOpenings->count())
        <flux:table :paginate="$jobOpenings" class="mt-4">
            <flux:table.columns>
                <flux:table.column>Título</flux:table.column>
                <flux:table.column>Vigencia</flux:table.column>
                <flux:table.column align="center">Activo</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($jobOpenings as $job)
                    <flux:table.row :key="$job->id">
                        <flux:table.cell variant="strong">{{ $job->title }}</flux:table.cell>
                        <flux:table.cell>
                            <span class="text-xs">
                                {{ $job->valid_from->format('d/m/Y') }} — {{ $job->valid_until->format('d/m/Y') }}
                            </span>
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge :color="$job->is_active ? 'green' : 'zinc'" size="sm">{{ $job->is_active ? 'Sí' : 'No' }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <flux:dropdown align="end">
                                <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                                <flux:menu>
                                    <flux:menu.item :href="route('admin.job-openings.edit', $job)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                    <flux:menu.item wire:click="delete({{ $job->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    @else
        <div class="mt-6 flex justify-center">
            <flux:callout icon="briefcase">
                <flux:callout.heading>No hay ofertas laborales registradas</flux:callout.heading>
                <flux:callout.text>Crea tu primera oferta para publicarla en el sitio web.</flux:callout.text>
            </flux:callout>
        </div>
    @endif
</div>
