<div>
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
            <flux:field>
                <flux:label>Hitos / Línea de Tiempo (JSON)</flux:label>
                <flux:textarea wire:model="settings.about_milestones" rows="10" />
                <flux:description class="mt-1">
                    Formato JSON. Ejemplo:
                    <code class="block mt-1 text-[11px] leading-relaxed bg-zinc-100 p-2 rounded">[
  {"year": "1995", "title": "Fundación", "desc": "Descripción...", "icon": "sparkles"},
  {"year": "2001", "title": "Expansión", "desc": "Descripción...", "icon": "trending-up"}
]</code>
                    Íconos disponibles: <strong>sparkles</strong>, <strong>trending-up</strong>, <strong>globe-alt</strong>, <strong>user-group</strong>, <strong>rocket-launch</strong>, <strong>forward</strong>
                </flux:description>
            </flux:field>
        </div>
    </div>

    <div>
        <flux:heading size="sm" class="mb-4">Página Trabaja con Nosotros</flux:heading>
        <div class="space-y-4">
            <flux:field>
                <flux:label>Título del hero</flux:label>
                <flux:input wire:model="settings.work_with_us_title" placeholder="Ej: Únete al equipo" />
            </flux:field>
            <flux:field>
                <flux:label>Descripción del hero</flux:label>
                <flux:textarea wire:model="settings.work_with_us_description" rows="3" placeholder="Describe brevemente qué buscan en los candidatos..." />
            </flux:field>
            <flux:field>
                <flux:label>Cuerpo adicional (opcional)</flux:label>
                <flux:textarea wire:model="settings.work_with_us_body" rows="6" placeholder="Información adicional sobre el proceso de postulación..." />
            </flux:field>
        </div>
    </div>

    <div>
        <flux:heading size="sm" class="mb-4">Redes Sociales</flux:heading>
        <div class="space-y-4">
            <flux:field>
                <flux:label>LinkedIn</flux:label>
                <flux:input wire:model="settings.social_linkedin" placeholder="https://linkedin.com/company/tu-perfil" />
            </flux:field>
            <flux:field>
                <flux:label>Facebook</flux:label>
                <flux:input wire:model="settings.social_facebook" placeholder="https://facebook.com/tu-perfil" />
            </flux:field>
            <flux:field>
                <flux:label>Instagram</flux:label>
                <flux:input wire:model="settings.social_instagram" placeholder="https://instagram.com/tu-perfil" />
            </flux:field>
            <flux:field>
                <flux:label>TikTok</flux:label>
                <flux:input wire:model="settings.social_tiktok" placeholder="https://tiktok.com/@tu-perfil" />
            </flux:field>
        </div>
    </div>

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">Guardar configuración</flux:button>
        </div>
    </form>
</div>
