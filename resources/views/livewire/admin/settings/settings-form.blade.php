<flux:heading>Configuración del sitio</flux:heading>
<flux:subheading>Administra los textos y contenido general del sitio web.</flux:subheading>

<form wire:submit.prevent="save" class="mt-6 space-y-8 max-w-3xl">
    <div>
        <flux:heading size="sm" class="mb-4">Información general</flux:heading>
        <div class="space-y-4">
            <flux:field>
                <flux:label>Nombre de la empresa</flux:label>
                <flux:input wire:model="settings.company_name" />
            </flux:field>
            <flux:field>
                <flux:label>Slogan</flux:label>
                <flux:input wire:model="settings.company_slogan" />
            </flux:field>
            <flux:field>
                <flux:label>Nota legal (footer)</flux:label>
                <flux:textarea wire:model="settings.legal_notice" rows="3" />
            </flux:field>
        </div>
    </div>

    <div>
        <flux:heading size="sm" class="mb-4">Página Nosotros</flux:heading>
        <div class="space-y-4">
            <flux:field>
                <flux:label>Historia</flux:label>
                <flux:textarea wire:model="settings.about_history" rows="6" />
            </flux:field>
            <flux:field>
                <flux:label>Misión</flux:label>
                <flux:textarea wire:model="settings.about_mission" rows="4" />
            </flux:field>
            <flux:field>
                <flux:label>Visión</flux:label>
                <flux:textarea wire:model="settings.about_vision" rows="4" />
            </flux:field>
            <flux:field>
                <flux:label>Valores</flux:label>
                <flux:textarea wire:model="settings.about_values" rows="4" />
            </flux:field>
            <flux:field>
                <flux:label>Política de Calidad</flux:label>
                <flux:textarea wire:model="settings.about_quality_policy" rows="6" />
            </flux:field>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">Guardar configuración</flux:button>
    </div>
</form>
