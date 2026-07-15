<flux:heading>{{ $brochure?->exists ? 'Editar rotafolio' : 'Nuevo rotafolio' }}</flux:heading>
<flux:subheading>Completa los datos del rotafolio.</flux:subheading>

<form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
    <flux:field>
        <flux:label>Título</flux:label>
        <flux:input wire:model="title" placeholder="Ej: Línea Gástricos" />
        <flux:error name="title" />
    </flux:field>

    <flux:field>
        <flux:label>Línea</flux:label>
        <flux:input wire:model="line" placeholder="Ej: Gástricos, Otológicos..." />
        <flux:error name="line" />
    </flux:field>

    <flux:field>
        <flux:label>Descripción</flux:label>
        <flux:textarea wire:model="description" rows="3" />
        <flux:error name="description" />
    </flux:field>

    <flux:field>
        <flux:label>Archivo PDF</flux:label>
        <flux:input type="file" wire:model="file" accept=".pdf" />
        <flux:error name="file" />
        @if ($file)
            <p class="mt-1 text-xs text-zinc-500">{{ $file->getClientOriginalName() }}</p>
        @elseif ($brochure?->file_path)
            <p class="mt-1 text-xs text-zinc-500">Archivo actual: <a href="{{ $brochure->file_url }}" target="_blank" class="text-orange-600 hover:underline">Ver PDF</a></p>
        @endif
    </flux:field>

    <flux:switch wire:model="is_active" :checked="$is_active" label="Activo" />

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ $brochure?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
        <flux:button :href="route('admin.brochures.index')" wire:navigate variant="ghost">Cancelar</flux:button>
    </div>
</form>