<flux:heading>{{ $heroSlide?->exists ? 'Editar slide' : 'Nuevo slide' }}</flux:heading>
<flux:subheading>Configuración del slide del carrusel principal.</flux:subheading>

<form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
    <flux:field>
        <flux:label>Título</flux:label>
        <flux:input wire:model="title" placeholder="Título del slide" />
        <flux:error name="title" />
    </flux:field>

    <flux:field>
        <flux:label>Subtítulo</flux:label>
        <flux:input wire:model="subtitle" placeholder="Subtítulo opcional" />
        <flux:error name="subtitle" />
    </flux:field>

    <flux:field>
        <flux:label>Imagen de fondo</flux:label>
        <flux:input type="file" wire:model="image" accept="image/*" />
        <flux:error name="image" />
        @if ($image)
            <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
        @elseif ($heroSlide?->image_path)
            <img src="{{ $heroSlide->image_url }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
        @endif
    </flux:field>

    <div class="grid grid-cols-2 gap-4">
        <flux:field>
            <flux:label>Texto del botón (CTA)</flux:label>
            <flux:input wire:model="cta_label" placeholder="Ej: Ver producto" />
            <flux:error name="cta_label" />
        </flux:field>
        <flux:field>
            <flux:label>URL del CTA</flux:label>
            <flux:input wire:model="cta_url" placeholder="Ej: /productos" />
            <flux:error name="cta_url" />
        </flux:field>
    </div>

    <flux:switch wire:model="is_active" :checked="$is_active" label="Activo" />

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ $heroSlide?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
        <flux:button :href="route('admin.hero.index')" wire:navigate variant="ghost">Cancelar</flux:button>
    </div>
</form>