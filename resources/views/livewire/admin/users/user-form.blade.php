<div>
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

    <flux:separator />

    <flux:heading level="3">Permisos adicionales</flux:heading>
    <flux:subheading>Asigna permisos específicos además del rol base.</flux:subheading>

    @php
        $groups = [
            'Contenido' => ['products', 'brands', 'categories', 'branches', 'hero', 'brochures', 'news', 'job-openings'],
            'Administración' => ['users', 'settings'],
        ];
    @endphp

    <div class="space-y-4">
        @foreach ($groups as $groupLabel => $entities)
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4">
                <h4 class="mb-3 text-sm font-medium text-zinc-700 dark:text-zinc-300">{{ $groupLabel }}</h4>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($entities as $entity)
                        @php
                            $viewPerm = 'view.'.$entity;
                            $editPerm = 'edit.'.$entity;
                        @endphp
                        <div class="flex flex-col gap-1">
                            <span class="text-xs font-medium text-zinc-500 capitalize">{{ str_replace('-', ' ', $entity) }}</span>
                            <label class="flex items-center gap-2 text-sm">
                                <flux:checkbox wire:model="permissions" value="{{ $viewPerm }}" />
                                Ver
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <flux:checkbox wire:model="permissions" value="{{ $editPerm }}" />
                                Editar
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <flux:error name="permissions" />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">{{ $user?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
            <flux:button :href="route('admin.users.index')" wire:navigate variant="ghost">Cancelar</flux:button>
        </div>
    </form>
</div>
