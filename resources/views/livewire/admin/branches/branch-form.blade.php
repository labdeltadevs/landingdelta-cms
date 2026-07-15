<flux:heading>{{ $branch?->exists ? 'Editar sucursal' : 'Nueva sucursal' }}</flux:heading>
<flux:subheading>Completa los datos de la sucursal.</flux:subheading>

<form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
    <div class="grid grid-cols-2 gap-4">
        <flux:field>
            <flux:label>Nombre</flux:label>
            <flux:input wire:model="name" placeholder="Ej: Casa Matriz" />
            <flux:error name="name" />
        </flux:field>
        <flux:field>
            <flux:label>Ciudad</flux:label>
            <flux:input wire:model="city" placeholder="Ej: La Paz" />
            <flux:error name="city" />
        </flux:field>
    </div>

    <flux:field>
        <flux:label>Dirección</flux:label>
        <flux:input wire:model="address" placeholder="Dirección completa" />
        <flux:error name="address" />
    </flux:field>

    <div class="grid grid-cols-2 gap-4">
        <flux:field>
            <flux:label>Teléfono</flux:label>
            <flux:input wire:model="phone" placeholder="2 2411516" />
            <flux:error name="phone" />
        </flux:field>
        <flux:field>
            <flux:label>Teléfono 2</flux:label>
            <flux:input wire:model="phone_2" placeholder="69829357" />
            <flux:error name="phone_2" />
        </flux:field>
    </div>

    <flux:field>
        <flux:label>Email</flux:label>
        <flux:input wire:model="email" type="email" placeholder="ventas@laboratoriosdelta.net" />
        <flux:error name="email" />
    </flux:field>

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ $branch?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
        <flux:button :href="route('admin.branches.index')" wire:navigate variant="ghost">Cancelar</flux:button>
    </div>
</form>
