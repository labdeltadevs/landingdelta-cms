<flux:heading>{{ $brand?->exists ? 'Editar marca' : 'Nueva marca' }}</flux:heading>
<flux:subheading>Completa los campos para {{ $brand?->exists ? 'actualizar' : 'crear' }} la marca.</flux:subheading>

<form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
    <flux:field>
        <flux:label>Nombre de la marca</flux:label>
        <flux:input wire:model="name" placeholder="Ej: Delta Care" />
        <flux:error name="name" />
    </flux:field>

    <flux:field>
        <flux:label>Descripción</flux:label>
        <flux:textarea wire:model="description" rows="3" placeholder="Descripción de la marca..." />
        <flux:error name="description" />
    </flux:field>

    <flux:field>
        <flux:label>Logo</flux:label>
        <flux:input type="file" wire:model="logo" accept="image/*" />
        <flux:error name="logo" />
        @if ($logo)
            <img src="{{ $logo->temporaryUrl() }}" class="mt-2 h-20 object-contain" />
        @elseif ($brand?->logo_path)
            <img src="{{ $brand->logo_url }}" class="mt-2 h-20 object-contain" />
        @endif
    </flux:field>

    <flux:switch wire:model="is_active" :checked="$is_active" label="Activa" />

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ $brand?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
        <flux:button :href="route('admin.brands.index')" wire:navigate variant="ghost">Cancelar</flux:button>
    </div>
</form>
