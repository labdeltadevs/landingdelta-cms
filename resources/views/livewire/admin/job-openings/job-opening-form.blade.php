<div>
    <flux:heading>{{ $jobOpening?->exists ? 'Editar oferta laboral' : 'Nueva oferta laboral' }}</flux:heading>
    <flux:subheading>Completa los datos de la convocatoria.</flux:subheading>

    <form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
        <flux:field>
            <flux:label>Título de la oferta</flux:label>
            <flux:input wire:model.live="title" placeholder="Ej: Farmacéutico/a para sucursal Santa Cruz" />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>Slug (URL pública)</flux:label>
            <flux:input wire:model="slug" readonly placeholder="Se genera automáticamente desde el título" />
            <flux:description>Identificador único en la URL. Se genera solo; no es editable.</flux:description>
            <flux:error name="slug" />
        </flux:field>

        <flux:field>
            <flux:label>Descripción</flux:label>
            <flux:textarea wire:model="description" rows="8" placeholder="Describe los requisitos, funciones y beneficios del puesto..." />
            <flux:error name="description" />
        </flux:field>

        <flux:field>
            <flux:label>Correo de postulación</flux:label>
            <flux:input wire:model="application_email" type="email" placeholder="rrhh@laboratoriosdelta.net" />
            <flux:description>Los interesados enviarán su CV a esta dirección.</flux:description>
            <flux:error name="application_email" />
        </flux:field>

        <div class="grid grid-cols-2 gap-4">
            <flux:field>
                <flux:label>Vigente desde</flux:label>
                <flux:input wire:model="valid_from" type="date" />
                <flux:error name="valid_from" />
            </flux:field>
            <flux:field>
                <flux:label>Vigente hasta</flux:label>
                <flux:input wire:model="valid_until" type="date" />
                <flux:error name="valid_until" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>Imagen de acompañamiento</flux:label>
            <input type="file" wire:model="image" accept="image/*"
                class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#ff671f] file:text-white hover:file:bg-[#e55a1a] transition-colors cursor-pointer border border-zinc-300 rounded-lg p-1.5" />
            <flux:error name="image" />
            @if ($image && is_string($image))
                <img src="{{ Storage::disk('public')->url($image) }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
            @elseif ($jobOpening?->image_path)
                <img src="{{ $jobOpening->image_url }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
            @endif
        </flux:field>

        <flux:switch wire:model="is_active" :checked="$is_active" label="Activa" />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">{{ $jobOpening?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
            <flux:button :href="route('admin.job-openings.index')" wire:navigate variant="ghost">Cancelar</flux:button>
        </div>
    </form>
</div>
