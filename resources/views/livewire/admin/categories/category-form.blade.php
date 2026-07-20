<div>
    <flux:heading>{{ $category?->exists ? 'Editar categoría' : 'Nueva categoría' }}</flux:heading>
    <flux:subheading>Completa los campos para {{ $category?->exists ? 'actualizar' : 'crear' }} la categoría.</flux:subheading>

    <form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
        <flux:field>
            <flux:label>Nombre de la categoría</flux:label>
            <flux:input wire:model="name" placeholder="Ej: Comprimidos, Cápsulas, Sobres, Tabletas" />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label>Descripción</flux:label>
            <flux:textarea wire:model="description" rows="3" />
            <flux:error name="description" />
        </flux:field>

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">{{ $category?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
            <flux:button :href="route('admin.categories.index')" wire:navigate variant="ghost">Cancelar</flux:button>
        </div>
    </form>
</div>
