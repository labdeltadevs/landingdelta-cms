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
                        <flux:table.cell variant="strong">
                            <span class="inline-flex items-center gap-1.5">
                                @if (filled($job->image_path))
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
                                {{ $job->title }}
                            </span>
                        </flux:table.cell>
                        <flux:table.cell>
                            <span class="text-xs">
                                {{ $job->valid_from->format('d/m/Y') }} — {{ $job->valid_until->format('d/m/Y') }}
                            </span>
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge :color="$job->is_active ? 'green' : 'zinc'" size="sm">{{ $job->is_active ? 'Sí' : 'No' }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            @canany(['update', 'delete'], $job)
                                <flux:dropdown align="end">
                                    <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                                    <flux:menu>
                                        <flux:menu.item wire:click="showQr({{ $job->id }})" icon="qr-code">Generar QR</flux:menu.item>
                                        <flux:menu.item wire:click="showBarcode({{ $job->id }})" icon="barcode">Generar Código de Barras</flux:menu.item>
                                        @can('update', $job)
                                            <flux:menu.item :href="route('admin.job-openings.edit', $job)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                        @endcan
                                        @can('delete', $job)
                                            <flux:menu.item wire:click="delete({{ $job->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                                        @endcan
                                    </flux:menu>
                                </flux:dropdown>
                            @endcanany
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

    @include('livewire.admin.partials.qr-modal')
</div>
