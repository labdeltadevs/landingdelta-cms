<flux:heading>{{ $user?->exists ? 'Editar usuario' : 'Nuevo usuario' }}</flux:heading>
<flux:subheading>{{ $user?->exists ? 'Actualiza los datos del usuario.' : 'Crea un nuevo usuario en el sistema.' }}</flux:subheading>

<form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
    <flux:field>
        <flux:label>Nombre</flux:label>
        <flux:input wire:model="name" placeholder="Nombre completo" />
        <flux:error name="name" />
    </flux:field>

    <flux:field>
        <flux:label>Email</flux:label>
        <flux:input wire:model="email" type="email" placeholder="usuario@laboratoriosdelta.net" />
        <flux:error name="email" />
    </flux:field>

    @unless ($user?->exists)
        <div class="grid grid-cols-2 gap-4">
            <flux:field>
                <flux:label>Contraseña</flux:label>
                <flux:input wire:model="password" type="password" placeholder="Mín. 8 caracteres" />
                <flux:error name="password" />
            </flux:field>
            <flux:field>
                <flux:label>Confirmar contraseña</flux:label>
                <flux:input wire:model="password_confirmation" type="password" placeholder="Repite la contraseña" />
                <flux:error name="password_confirmation" />
            </flux:field>
        </div>
    @endunless

    <flux:field>
        <flux:label>Rol</flux:label>
        <flux:select wire:model="role_id">
            <option value="">Selecciona un rol</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
            @endforeach
        </flux:select>
        <flux:error name="role_id" />
    </flux:field>

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ $user?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
        <flux:button :href="route('admin.users.index')" wire:navigate variant="ghost">Cancelar</flux:button>
    </div>
</form>
