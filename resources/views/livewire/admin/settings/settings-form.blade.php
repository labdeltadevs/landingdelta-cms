<div class="scroll-smooth bg-zinc-100 dark:bg-zinc-950 min-h-screen" x-data="{ activeSection: 'seg-general' }">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <flux:heading>Configuración del sitio</flux:heading>
        <flux:subheading>Administra los textos y contenido general del sitio web.</flux:subheading>

        {{-- Sub-nav con anclas inteligente --}}
        <div
            class="sticky top-4 z-30 mb-8 mt-6 flex w-full flex-wrap items-center gap-1 rounded-2xl border border-zinc-200 bg-white/90 p-2 shadow-sm backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/90">
            <a href="#seg-general" @click="activeSection = 'seg-general'"
                :class="activeSection === 'seg-general' ? 'bg-[#ff671f] text-white shadow-sm' :
                    'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'"
                class="rounded-full px-4 py-1.5 text-xs font-semibold transition">
                General
            </a>
            <a href="#seg-nosotros" @click="activeSection = 'seg-nosotros'"
                :class="activeSection === 'seg-nosotros' ? 'bg-[#ff671f] text-white shadow-sm' :
                    'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'"
                class="rounded-full px-4 py-1.5 text-xs font-semibold transition">
                Nosotros
            </a>
            <a href="#seg-trabaja" @click="activeSection = 'seg-trabaja'"
                :class="activeSection === 'seg-trabaja' ? 'bg-[#ff671f] text-white shadow-sm' :
                    'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'"
                class="rounded-full px-4 py-1.5 text-xs font-semibold transition">
                Trabajá con Nosotros
            </a>
            <a href="#seg-redes" @click="activeSection = 'seg-redes'"
                :class="activeSection === 'seg-redes' ? 'bg-[#ff671f] text-white shadow-sm' :
                    'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'"
                class="rounded-full px-4 py-1.5 text-xs font-semibold transition">
                Redes Sociales
            </a>
        </div>

        <form wire:submit.prevent="save" class="space-y-8 pb-24">

            {{-- ============================================================ --}}
            {{-- INFORMACIÓN GENERAL                                          --}}
            {{-- ============================================================ --}}
            <section id="seg-general" class="scroll-mt-28">
                <flux:card
                    class="overflow-hidden border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 transition-all duration-300"
                    :class="activeSection === 'seg-general' ?
                        'ring-2 ring-[#ff671f]/50 border-transparent border-l-4 border-l-[#ff671f]' : ''">
                    <div class="border-b border-zinc-100 bg-zinc-50 px-6 py-4 dark:border-zinc-800 dark:bg-zinc-800/50">
                        <flux:heading size="sm">Información general</flux:heading>
                        <flux:subheading size="sm">Datos base de la empresa y del sitio.</flux:subheading>
                    </div>

                    <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <flux:field class="sm:col-span-2">
                            <flux:label for="company_name">Nombre de la empresa</flux:label>
                            <flux:input id="company_name" wire:model="settings.company_name" />
                            <flux:description>Se muestra en el footer y en los metadatos del sitio.</flux:description>
                        </flux:field>

                        <flux:field class="sm:col-span-2">
                            <flux:label for="company_slogan">Slogan</flux:label>
                            <flux:input id="company_slogan" wire:model="settings.company_slogan" />
                            <flux:description>Frase corta que identifica a la empresa.</flux:description>
                        </flux:field>

                        <flux:field class="sm:col-span-2">
                            <flux:label for="legal_notice">Nota legal (footer)</flux:label>
                            <flux:textarea id="legal_notice" wire:model="settings.legal_notice" rows="3" />
                            <flux:description>Texto legal que aparece en el pie de página.</flux:description>
                        </flux:field>
                    </div>
                </flux:card>
            </section>

            {{-- ============================================================ --}}
            {{-- PÁGINA NOSOTROS                                              --}}
            {{-- ============================================================ --}}
            <section id="seg-nosotros" class="scroll-mt-28">
                <flux:card
                    class="overflow-hidden border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 transition-all duration-300"
                    :class="activeSection === 'seg-nosotros' ?
                        'ring-2 ring-[#ff671f]/50 border-transparent border-l-4 border-l-[#ff671f]' : ''">
                    <div class="border-b border-zinc-100 bg-zinc-50 px-6 py-4 dark:border-zinc-800 dark:bg-zinc-800/50">
                        <flux:heading size="sm">Página Nosotros</flux:heading>
                        <flux:subheading size="sm">Contenido de la página /nosotros.</flux:subheading>
                    </div>

                    <div class="space-y-6 p-6">
                        <flux:field>
                            <flux:label for="about_history">Historia</flux:label>
                            <flux:textarea id="about_history" wire:model="settings.about_history" rows="6" />
                            <flux:description>Texto de la sección historia de la empresa.</flux:description>
                        </flux:field>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <flux:field>
                                <flux:label for="about_mission">Misión</flux:label>
                                <flux:textarea id="about_mission" wire:model="settings.about_mission" rows="4" />
                            </flux:field>

                            <flux:field>
                                <flux:label for="about_vision">Visión</flux:label>
                                <flux:textarea id="about_vision" wire:model="settings.about_vision" rows="4" />
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label for="about_values">Valores</flux:label>
                            <flux:textarea id="about_values" wire:model="settings.about_values" rows="4" />
                        </flux:field>

                        <flux:field>
                            <flux:label for="about_quality_policy">Política de Calidad</flux:label>
                            <flux:textarea id="about_quality_policy" wire:model="settings.about_quality_policy"
                                rows="6" />
                        </flux:field>

                        <flux:field>
                            <flux:label for="about_milestones">Hitos / Línea de Tiempo (JSON)</flux:label>
                            <flux:textarea id="about_milestones" wire:model="settings.about_milestones" rows="8"
                                class="font-mono text-sm" />
                            <flux:description class="mt-2">
                                Arreglo JSON con los hitos de la línea de tiempo. Estructura:
                                <code
                                    class="block mt-2 text-[11px] leading-relaxed bg-zinc-800 text-green-400 p-3 rounded-lg border border-zinc-700 overflow-x-auto">{&quot;year&quot;:
                                    1987, &quot;title&quot;: &quot;Fundación&quot;, &quot;desc&quot;: &quot;Breve
                                    descripción&quot;}</code>
                            </flux:description>
                        </flux:field>
                    </div>
                </flux:card>
            </section>

            {{-- ============================================================ --}}
            {{-- PÁGINA TRABAJÁ CON NOSOTROS                                  --}}
            {{-- ============================================================ --}}
            <section id="seg-trabaja" class="scroll-mt-28">
                <flux:card
                    class="overflow-hidden border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 transition-all duration-300"
                    :class="activeSection === 'seg-trabaja' ?
                        'ring-2 ring-[#ff671f]/50 border-transparent border-l-4 border-l-[#ff671f]' : ''">
                    <div class="border-b border-zinc-100 bg-zinc-50 px-6 py-4 dark:border-zinc-800 dark:bg-zinc-800/50">
                        <flux:heading size="sm">Página Trabajá con Nosotros</flux:heading>
                        <flux:subheading size="sm">Contenido de la página /trabaja-con-nosotros.</flux:subheading>
                    </div>

                    <div class="space-y-6 p-6">
                        <flux:callout icon="information-circle" color="orange">
                            <flux:callout.heading>No es el hero del home</flux:callout.heading>
                            <flux:callout.text>Estos textos alimentan la cabecera de la página Trabajá con Nosotros. El
                                hero del home se administra en <strong>Hero Slides</strong> (menú Contenido).
                            </flux:callout.text>
                        </flux:callout>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <flux:field>
                                <flux:label for="work_title">Título de la página</flux:label>
                                <flux:input id="work_title" wire:model="settings.work_with_us_title"
                                    placeholder="Ej: Únete al equipo" />
                                <flux:description>Título principal de la cabecera de esta página.</flux:description>
                            </flux:field>

                            <flux:field>
                                <flux:label for="work_desc">Descripción de la página</flux:label>
                                <flux:textarea id="work_desc" wire:model="settings.work_with_us_description"
                                    rows="3" placeholder="Describe brevemente qué buscan en los candidatos..." />
                                <flux:description>Texto de apoyo que acompaña al título.</flux:description>
                            </flux:field>
                        </div>

                        <flux:field>
                            <flux:label for="work_body">Cuerpo adicional (opcional)</flux:label>
                            <flux:textarea id="work_body" wire:model="settings.work_with_us_body" rows="6"
                                placeholder="Información adicional sobre el proceso de postulación..." />
                            <flux:description>Información adicional sobre el proceso de postulación.</flux:description>
                        </flux:field>
                    </div>
                </flux:card>
            </section>

            {{-- ============================================================ --}}
            {{-- REDES SOCIALES                                               --}}
            {{-- ============================================================ --}}
            <section id="seg-redes" class="scroll-mt-28">
                <flux:card
                    class="overflow-hidden border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 transition-all duration-300"
                    :class="activeSection === 'seg-redes' ?
                        'ring-2 ring-[#ff671f]/50 border-transparent border-l-4 border-l-[#ff671f]' : ''">
                    <div
                        class="border-b border-zinc-100 bg-zinc-50 px-6 py-4 dark:border-zinc-800 dark:bg-zinc-800/50">
                        <flux:heading size="sm">Redes Sociales</flux:heading>
                        <flux:subheading size="sm">Enlaces públicos de la empresa.</flux:subheading>
                    </div>

                    <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <flux:field>
                            <flux:label for="social_linkedin">LinkedIn</flux:label>
                            <flux:input id="social_linkedin" wire:model="settings.social_linkedin"
                                placeholder="https://linkedin.com/company/tu-perfil" />
                        </flux:field>

                        <flux:field>
                            <flux:label for="social_facebook">Facebook</flux:label>
                            <flux:input id="social_facebook" wire:model="settings.social_facebook"
                                placeholder="https://facebook.com/tu-perfil" />
                        </flux:field>

                        <flux:field>
                            <flux:label for="social_instagram">Instagram</flux:label>
                            <flux:input id="social_instagram" wire:model="settings.social_instagram"
                                placeholder="https://instagram.com/tu-perfil" />
                        </flux:field>

                        <flux:field>
                            <flux:label for="social_tiktok">TikTok</flux:label>
                            <flux:input id="social_tiktok" wire:model="settings.social_tiktok"
                                placeholder="https://tiktok.com/@tu-perfil" />
                        </flux:field>
                    </div>
                </flux:card>
            </section>

            {{-- ============================================================ --}}
            {{-- STICKY ACTION BAR (Botón de guardar flotante)               --}}
            {{-- ============================================================ --}}
            <div class="sticky bottom-4 z-20 mt-8 flex justify-end">
                <div
                    class="flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white/90 p-3 pl-5 shadow-xl backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/90">
                    <span class="hidden text-xs text-zinc-500 dark:text-zinc-400 sm:block">
                        Asegúrate de guardar los cambios antes de salir.
                    </span>
                    <flux:button type="submit" variant="primary" class="cursor-pointer">
                        Guardar configuración
                    </flux:button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        // Lógica para el Intersection Observer (Resaltado de pestañas al hacer scroll)
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.querySelector('[x-data^="activeSection"]');
            if (!root) return;

            // Obtener la instancia de Alpine.js para poder modificar 'activeSection'
            const alpineRoot = root._x_dataStack[0];

            const sections = document.querySelectorAll('section[id^="seg-"]');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        alpineRoot.activeSection = entry.target.id;
                    }
                });
            }, {
                rootMargin: '-30% 0px -60% 0px'
            }); // Activa la sección cuando está en el tercio superior

            sections.forEach(sec => observer.observe(sec));
        });
    </script>
@endpush
