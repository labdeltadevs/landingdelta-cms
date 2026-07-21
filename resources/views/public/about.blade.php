<x-layouts::public>
    @php
        $historyBody = is_array($history) ? ($history['body'] ?? '') : ($history ?? '');
        $missionBody = is_array($mission) ? ($mission['body'] ?? '') : ($mission ?? '');
        $visionBody = is_array($vision) ? ($vision['body'] ?? '') : ($vision ?? '');
        $valuesBody = is_array($values) ? ($values['body'] ?? '') : ($values ?? '');
        $qualityBody = is_array($quality) ? ($quality['body'] ?? '') : ($quality ?? '');

        $hasHistory = ! blank($historyBody);
        $hasMission = ! blank($missionBody);
        $hasVision = ! blank($visionBody);
        $hasValues = ! blank($valuesBody);
        $hasQuality = ! blank($qualityBody);
        $hasBranches = $branches->count() > 0;
        $hasMilestones = count($milestones) > 0;
        $firstYearGlobal = $hasMilestones ? (int) ($milestones[0]['year'] ?? 1987) : 1987;

        $historyParagraphs = $hasHistory ? array_filter(array_map('trim', explode("\n\n", $historyBody))) : [];

        $valueIcons = [
            ['name' => 'Honradez', 'icon' => 'shield-check', 'desc' => 'Actuamos con transparencia y ética en cada decisión.'],
            ['name' => 'Consideración', 'icon' => 'heart', 'desc' => 'Respetamos y valoramos a cada persona que nos rodea.'],
            ['name' => 'Trabajo en equipo', 'icon' => 'users', 'desc' => 'Juntos logramos más, sumando talentos y esfuerzos.'],
            ['name' => 'Responsabilidad', 'icon' => 'clipboard-check', 'desc' => 'Cumplimos nuestros compromisos con excelencia.'],
            ['name' => 'Mejora continua', 'icon' => 'arrow-trending-up', 'desc' => 'Buscamos ser mejores cada día, innovando sin cesar.'],
            ['name' => 'Compromiso social', 'icon' => 'globe-alt', 'desc' => 'Devolvemos a la sociedad el apoyo que nos brinda.'],
        ];
    @endphp

    {{-- ============================================================ --}}
    {{-- HERO — "CONOZCAN NUESTRA HISTORIA"                         --}}
    {{-- ============================================================ --}}
    <section class="relative min-h-[80vh] flex items-center overflow-hidden bg-gradient-to-br from-zinc-900 via-zinc-800 to-[#1a1a1a]">
        {{-- Animated grid --}}
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 28px 28px;">
        </div>
        {{-- Orbs --}}
        <div class="absolute -top-48 -right-48 h-[35rem] w-[35rem] rounded-full bg-[#ff671f]/8 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-48 -left-48 h-[30rem] w-[30rem] rounded-full bg-[#ff671f]/5 blur-3xl"></div>
        <div class="absolute top-1/4 left-1/3 h-1 w-1 rounded-full bg-white/10 shadow-[0_0_80px_40px_rgba(255,103,31,0.08)]"></div>

        <div class="relative z-10 mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center" data-aos="fade-up">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 rounded-full border border-[#ff671f]/20 bg-[#ff671f]/10 px-5 py-1.5 mb-8">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#ff671f] animate-pulse"></span>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#ff671f]">
                        Desde 1987
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-white leading-[1.05] tracking-tight">
                    Conozcan nuestra
                    <br>
                    <span class="text-[#ff671f] relative inline-block">
                        historia
                        <svg class="absolute -bottom-3 left-0 w-full h-4 text-[#ff671f]/20" viewBox="0 0 200 14" fill="currentColor" preserveAspectRatio="none">
                            <path d="M0 12 Q 50 0, 100 10 T 200 12 L 200 14 L 0 14 Z"/>
                        </svg>
                    </span>
                </h1>

                {{-- Subtitle --}}
                <p class="mt-6 text-lg sm:text-xl text-zinc-300 max-w-2xl mx-auto leading-relaxed font-light">
                    Una historia de compromiso, calidad y dedicación que comenzó hace más de
                    <strong class="text-white font-semibold">{{ $yearsActive }} años</strong>
                    y sigue transformando la salud en Bolivia.
                </p>

                {{-- CTAs --}}
                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a href="#travesia"
                       class="group inline-flex items-center gap-2.5 rounded-full bg-[#ff671f] px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#ff671f]/25 transition-all duration-300 hover:bg-[#e55a1a] hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#ff671f]/30">
                        <span>Explora nuestra travesía</span>
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </a>
                    <a href="{{ route('public.contact') }}"
                       class="group inline-flex items-center gap-2.5 rounded-full border border-white/20 bg-white/5 backdrop-blur-sm px-7 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-white/10 hover:border-white/30">
                        Contáctanos
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    </a>
                </div>
            </div>

            {{-- Scroll indicator --}}
            <div class="mt-20 flex justify-center" data-aos="fade-up" data-aos-delay="300">
                <a href="#travesia" class="flex flex-col items-center gap-2 text-zinc-500 hover:text-zinc-300 transition-colors group">
                    <span class="text-[10px] font-medium uppercase tracking-[0.25em]">Descubre más</span>
                    <span class="flex h-9 w-6 items-start justify-center rounded-full border border-zinc-500 group-hover:border-zinc-300 transition-colors">
                        <span class="mt-1.5 h-2 w-1 rounded-full bg-zinc-500 group-hover:bg-zinc-300 animate-bounce"></span>
                    </span>
                </a>
            </div>
        </div>
    </section>


    {{-- ============================================================ --}}
    {{-- LIFESPAN TIMELINE — "NUESTRA TRAVESÍA"                     --}}
    {{-- ============================================================ --}}
    @if ($hasMilestones)
        <section id="travesia" class="relative py-24 sm:py-32 bg-gradient-to-b from-zinc-50 to-white overflow-hidden">
            {{-- Dot grid bg --}}
            <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
                 style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 24px 24px;">
            </div>
            {{-- Soft radial orbs --}}
            <div class="absolute -top-48 -right-48 h-[35rem] w-[35rem] rounded-full bg-[#ff671f]/5 blur-3xl"></div>
            <div class="absolute -bottom-48 -left-48 h-[30rem] w-[30rem] rounded-full bg-[#ff671f]/3 blur-3xl"></div>

            @php
                $firstYear = (int) ($milestones[0]['year'] ?? 1987);
                $lastYear = (int) (now()->year);
                $totalSpan = max($lastYear - $firstYear, 1);
                $decades = range(ceil($firstYear / 10) * 10, floor($lastYear / 10) * 10, 10);
                if (empty($decades) || $decades[0] > $firstYear) {
                    array_unshift($decades, $firstYear);
                }
                if (end($decades) !== $lastYear) {
                    $decades[] = $lastYear;
                }
            @endphp

            <div class="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                {{-- Header --}}
                <div class="text-center" data-aos="fade-up">
                    <span class="inline-flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#ff671f]">
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                        Capítulo I
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                    </span>
                    <h2 class="mt-6 text-4xl sm:text-5xl font-bold text-zinc-900">Nuestra travesía</h2>
                    <p class="mt-3 text-sm text-zinc-400 max-w-lg mx-auto">Hitos que marcaron nuestro camino y nos impulsan hacia el futuro.</p>
                </div>

                {{-- Lifespan Bar --}}
                <div class="relative mt-16 mb-20" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative h-24 sm:h-28">
                        {{-- Full-span track --}}
                        <div class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-gradient-to-r from-transparent via-[#ff671f]/30 to-transparent"></div>
                        {{-- Gradient fill line --}}
                        <div class="absolute left-0 top-1/2 h-0.5 -translate-y-1/2 bg-gradient-to-r from-[#ff671f] via-[#e55a1a] to-[#ff671f]"
                             style="width: 100%; mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%); -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);">
                        </div>
                        {{-- Decade markers --}}
                        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex items-center justify-between px-2">
                            @foreach ($decades as $decade)
                                @php
                                    $pos = ($decade - $firstYear) / $totalSpan * 100;
                                    $isEdge = $decade === $firstYear || $decade === $lastYear;
                                @endphp
                                <div class="absolute flex flex-col items-center" style="left: {{ min(max($pos, 2), 98) }}%;">
                                    <span class="h-2.5 w-2.5 rounded-full {{ $isEdge ? 'bg-[#ff671f] ring-2 ring-[#ff671f]/20' : 'bg-zinc-300' }} transition-all duration-300 hover:bg-[#ff671f] hover:ring-2 hover:ring-[#ff671f]/20"></span>
                                    <span class="absolute top-5 text-[10px] font-semibold {{ $isEdge ? 'text-[#ff671f]' : 'text-zinc-400' }} tracking-wider whitespace-nowrap">
                                        {{ $decade }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        {{-- Endcaps --}}
                        <div class="absolute -top-1 left-0 text-xs font-bold text-[#ff671f] bg-white/80 px-2 py-0.5 rounded-r-full shadow-sm border border-[#ff671f]/10">
                            {{ $firstYear }}
                        </div>
                        <div class="absolute -top-1 right-0 text-xs font-bold text-[#ff671f] bg-white/80 px-2 py-0.5 rounded-l-full shadow-sm border border-[#ff671f]/10">
                            {{ $lastYear }}
                        </div>
                    </div>
                </div>

                {{-- Vertical Timeline --}}
                <div class="relative"
                     x-data="{
                         visible: [],
                         scrollY: 0.5,
                         init() {
                             // Parallax tracking — runs before first paint
                             const trackScroll = () => {
                                 const rect = this.$el.getBoundingClientRect();
                                 const wh = window.innerHeight;
                                 this.scrollY = Math.max(0, Math.min(1, (wh - rect.top) / (wh + rect.height)));
                             };
                             trackScroll();
                             window.addEventListener('scroll', trackScroll, { passive: true });

                             // Node reveal — needs DOM ready
                             this.$nextTick(() => {
                                 this.$el.querySelectorAll('.timeline-node').forEach((node, i) => {
                                     const obs = new IntersectionObserver((entries) => {
                                         entries.forEach(entry => {
                                             if (entry.isIntersecting) {
                                                 setTimeout(() => {
                                                     this.visible = [...this.visible, i];
                                                 }, i * 120);
                                                 obs.unobserve(entry.target);
                                             }
                                         });
                                     }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
                                     obs.observe(node);
                                 });
                             });
                         }
                     }">
                    {{-- Central spine (parallax) --}}
                    <div class="absolute left-8 md:left-1/2 top-0 h-full w-0.5 bg-gradient-to-b from-[#ff671f] via-[#ff671f]/30 to-transparent transition-transform duration-100 ease-linear"
                         :style="'transform: translateX(-0.125rem) translateY(' + ((scrollY - 0.5) * 100) + 'px)'"
                         style="transform: translateX(-0.125rem)">
                    </div>

                    @foreach ($milestones as $i => $m)
                        @php
                            $isLeft = $i % 2 === 0;
                            $hasImage = ! empty($m['image']);

                            $icons = [
                                'sparkles' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z"/></svg>',
                                'trending-up' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg>',
                                'globe-alt' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>',
                                'user-group' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>',
                                'rocket-launch' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/></svg>',
                                'forward' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z"/></svg>',
                                'calendar' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>',
                                'shield-check' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>',
                                'building' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>',
                                'beaker' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>',
                                'trophy' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0 1 16.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.023 6.023 0 0 1-2.77.896m0 0a6.04 6.04 0 0 1-3-.011m0 0a6.023 6.023 0 0 1-2.77-.885"/></svg>',
                                'scale' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z"/></svg>',
                                'heart' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
                            ];
                            $iconSvg = $icons[$m['icon'] ?? ''] ?? $icons['calendar'];
                        @endphp

                        {{-- Mobile: always left-aligned. Desktop: alternating. --}}
                        <div class="timeline-node relative flex items-start gap-5 pb-16 last:pb-0 group md:gap-0 transition-all duration-700 ease-out"
                             :class="visible.includes({{ $i }}) ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-12 pointer-events-none'">

                            {{-- Desktop left side: card only for left-aligned items --}}
                            <div class="hidden md:flex md:w-1/2 {{ $isLeft ? 'md:justify-end md:pr-12' : 'md:justify-start md:pl-12' }}">
                                @if ($isLeft)
                                    <div class="w-full max-w-lg">
                                        @include('public.partials.milestone-card', [
                                            'm' => $m,
                                            'hasImage' => $hasImage,
                                            'iconSvg' => $iconSvg,
                                        ])
                                    </div>
                                @endif
                            </div>

                            {{-- Central dot --}}
                            <div class="absolute left-8 md:left-1/2 z-10 flex h-14 w-14 -translate-x-7 md:-translate-x-7 items-center justify-center rounded-2xl bg-gradient-to-br from-[#ff671f] to-[#e55a1a] text-white shadow-lg transition-all duration-500 group-hover:scale-110 group-hover:shadow-[#ff671f]/30 group-hover:rotate-6 md:h-16 md:w-16">
                                {!! $iconSvg !!}
                            </div>

                            {{-- Mobile card (always right) --}}
                            <div class="flex-1 pl-16 md:hidden">
                                @include('public.partials.milestone-card', [
                                    'm' => $m,
                                    'hasImage' => $hasImage,
                                    'iconSvg' => $iconSvg,
                                ])
                            </div>

                            {{-- Desktop right side: card only for right-aligned items --}}
                            <div class="hidden md:block md:w-1/2 {{ $isLeft ? 'md:pl-12' : 'md:pr-12' }}">
                                @if (! $isLeft)
                                    <div class="w-full max-w-lg">
                                        @include('public.partials.milestone-card', [
                                            'm' => $m,
                                            'hasImage' => $hasImage,
                                            'iconSvg' => $iconSvg,
                                        ])
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- STORY / HISTORY — AGED NEWSPAPER DESIGN                      --}}
    {{-- ============================================================ --}}
    @if ($hasHistory)
        {{-- =
             AGED NEWSPAPER PAGE
             Un diseño que evoca un periódico antiguo, desgastado por el tiempo,
             con textura de papel amarillento, bordes irregulares, pliegues
             y tipografía serif clásica.
        = --}}
        <section id="historia" class="relative overflow-hidden">
            {{-- Paper background with grain/noise texture --}}
            <div class="absolute inset-0 bg-[#f5f0e8]"
                 style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 400 400%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%274%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27 opacity=%270.07%27/%3E%3C/svg%3E');">
            </div>
            {{-- Sepia/aged overlay gradient --}}
            <div class="absolute inset-0 bg-gradient-to-b from-[#d4c5a9]/20 via-transparent to-[#c4b494]/30 pointer-events-none"></div>

            {{-- Torn edge top --}}
            <div class="absolute -top-1 left-0 right-0 h-8 bg-[#f5f0e8]"
                 style="clip-path: polygon(0% 100%, 3% 40%, 7% 70%, 12% 30%, 18% 80%, 22% 20%, 28% 60%, 33% 50%, 38% 85%, 44% 35%, 50% 70%, 55% 25%, 61% 55%, 67% 40%, 72% 75%, 78% 30%, 83% 65%, 88% 45%, 93% 80%, 97% 35%, 100% 60%, 100% 100%, 0% 100%);"></div>

            {{-- Stain/age marks --}}
            <div class="absolute top-32 right-16 w-40 h-40 rounded-full bg-[#d4c5a9]/30 blur-2xl pointer-events-none"></div>
            <div class="absolute bottom-48 left-12 w-56 h-56 rounded-full bg-[#c4b494]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/3 left-1/4 w-3 h-3 rounded-full bg-[#8b7355]/15 blur-sm pointer-events-none"></div>
            <div class="absolute bottom-1/4 right-1/3 w-4 h-4 rounded-full bg-[#8b7355]/10 blur-sm pointer-events-none"></div>

            {{-- Fold/crease effect across the page --}}
            <div class="absolute top-1/2 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#c4b494]/40 to-transparent pointer-events-none"></div>
            <div class="absolute top-[calc(50%-8px)] left-0 right-0 h-4 bg-gradient-to-b from-transparent via-[#d4c5a9]/10 to-transparent pointer-events-none" style="clip-path: polygon(0% 0%, 100% 0%, 97% 100%, 3% 100%);"></div>

            <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
                {{-- ========== NEWSPAPER MASTHEAD ========== --}}
                <div class="text-center mb-12" data-aos="fade-up">
                    {{-- Chapter badge --}}
                    <div class="mb-4">
                        <span class="inline-flex items-center gap-2 px-3 py-1 text-[9px] font-serif uppercase tracking-[0.25em] text-[#8b7355] border border-[#8b7355]/20 rounded-sm bg-[#efe5d5]/30">
                            <span class="w-2 h-px bg-[#8b7355]/30"></span>
                            Capítulo II
                            <span class="w-2 h-px bg-[#8b7355]/30"></span>
                        </span>
                    </div>
                    {{-- Top rule --}}
                    <div class="flex items-center gap-3 justify-center mb-3">
                        <span class="block h-[1px] flex-1 max-w-24 bg-[#8b7355]/30"></span>
                        <span class="text-[10px] font-serif italic text-[#8b7355] tracking-[0.3em]">EDICIÓN HISTÓRICA</span>
                        <span class="block h-[1px] flex-1 max-w-24 bg-[#8b7355]/30"></span>
                    </div>

                    {{-- Newspaper nameplate --}}
                    <h2 class="font-serif text-5xl sm:text-7xl font-bold tracking-tight text-[#3a3226] leading-[1.05]">
                        <span class="block">La Historia</span>
                        <span class="block text-2xl sm:text-3xl font-normal italic text-[#8b7355] tracking-[0.15em]">de Laboratorios Delta</span>
                    </h2>

                    {{-- Date line --}}
                    <div class="mt-4 flex items-center gap-4 justify-center text-[10px] sm:text-xs text-[#8b7355] font-serif uppercase tracking-[0.25em]">
                        <span class="h-px w-6 bg-[#8b7355]/20"></span>
                        <span>{{ now()->format('d \d\e F \d\e Y') }}</span>
                        <span class="h-px w-6 bg-[#8b7355]/20"></span>
                    </div>

                    {{-- Thick-thin rule under masthead --}}
                    <div class="mt-5 space-y-[2px]">
                        <span class="block h-[3px] bg-gradient-to-r from-transparent via-[#8b7355]/40 to-transparent"></span>
                        <span class="block h-px bg-gradient-to-r from-transparent via-[#8b7355]/20 to-transparent"></span>
                    </div>
                </div>

                {{-- ========== NEWSPAPER CONTENT ========== --}}
                <div class="relative" data-aos="fade-up" data-aos-delay="100">
                    {{-- Opening quote / lead paragraph --}}
                    <div class="relative px-2 sm:px-4">
                        {{-- Large drop cap for first paragraph --}}
                        <div class="text-center sm:text-left">
                            @php $_leadTrimmed = trim($historyParagraphs[0] ?? $historyBody); @endphp
                            <span class="font-serif text-6xl sm:text-7xl font-bold text-[#3a3226] leading-none float-none sm:float-left mr-0 sm:mr-4 mb-2 sm:mb-0">{{ mb_substr($_leadTrimmed, 0, 1) }}</span>
                            <p class="font-serif text-base sm:text-lg text-[#4a4236] leading-[1.9] italic first-line:font-bold">
                                {{ mb_substr($_leadTrimmed, 1) }}
                            </p>
                        </div>
                    </div>

                    {{-- Column divider ornament --}}
                    <div class="my-10 flex items-center gap-4 justify-center">
                        <span class="block h-px flex-1 bg-[#8b7355]/20"></span>
                        <svg class="w-6 h-6 text-[#8b7355]/30" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" d="M12 3v18M3 12h18M7.5 7.5l9 9M16.5 7.5l-9 9"/>
                        </svg>
                        <span class="block h-px flex-1 bg-[#8b7355]/20"></span>
                    </div>

                    {{-- Two-column layout for the rest of paragraphs --}}
                    <div class="sm:columns-2 sm:gap-8 sm:space-y-0 space-y-6">
                        @foreach ($historyParagraphs as $idx => $paragraph)
                            @if ($idx === 0) @continue @endif
                            <div class="break-inside-avoid mb-6 sm:mb-0 sm:pb-6">
                                @php
                                    $firstChar = mb_substr(trim($paragraph), 0, 1);
                                    $restText = mb_substr(trim($paragraph), 1);
                                @endphp
                                <p class="font-serif text-sm sm:text-base text-[#4a4236] leading-[1.85] sm:text-justify text-left hyphens-auto">
                                    @if ($firstChar && preg_match('/\pL/u', $firstChar))
                                        <span class="font-serif font-bold text-2xl text-[#3a3226]/70 float-left mr-2 leading-none">{{ $firstChar }}</span>
                                    @endif
                                    {{ $restText ? $firstChar . $restText : $paragraph }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Bottom rule --}}
                    <div class="mt-12 space-y-[2px]">
                        <span class="block h-px bg-gradient-to-r from-transparent via-[#8b7355]/20 to-transparent"></span>
                        <span class="block h-[3px] bg-gradient-to-r from-transparent via-[#8b7355]/40 to-transparent"></span>
                    </div>

                    {{-- Footer slogan --}}
                    <div class="mt-6 text-center">
                    <p class="font-serif text-[11px] text-[#8b7355] italic tracking-[0.15em]">
                        "Compromiso con la salud y el bienestar de Bolivia desde {{ $firstYearGlobal ?? 1987 }}"
                    </p>
                    </div>
                </div>

                {{-- ========== MILESTONE STAMP ROW ========== --}}
                @if ($hasMilestones)
                    <div class="mt-16" data-aos="fade-up" data-aos-delay="200">
                        {{-- Ornamental rule --}}
                        <div class="flex items-center gap-3 mb-8">
                            <span class="h-px flex-1 bg-[#8b7355]/20"></span>
                            <span class="font-serif text-[10px] text-[#8b7355] uppercase tracking-[0.3em]">Hitos</span>
                            <span class="h-px flex-1 bg-[#8b7355]/20"></span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach (array_slice($milestones, 0, 8) as $m)
                                <div class="group relative border border-[#d4c5a9]/40 bg-[#f5f0e8]/60 p-4 text-center transition-all duration-300 hover:bg-[#efe5d5] hover:shadow-sm hover:-translate-y-0.5">
                                    {{-- Decorative corner dots --}}
                                    <span class="absolute top-1 left-1 w-1 h-1 rounded-full bg-[#8b7355]/20 group-hover:bg-[#8b7355]/40 transition-colors"></span>
                                    <span class="absolute bottom-1 right-1 w-1 h-1 rounded-full bg-[#8b7355]/20 group-hover:bg-[#8b7355]/40 transition-colors"></span>
                                    {{-- Year --}}
                                    <div class="font-serif text-xl font-bold text-[#3a3226] tabular-nums">{{ $m['year'] }}</div>
                                    {{-- Separator --}}
                                    <div class="mx-auto my-1.5 w-6 h-px bg-[#8b7355]/20"></div>
                                    {{-- Title --}}
                                    <div class="font-serif text-[10px] text-[#8b7355] uppercase tracking-wider leading-tight">{{ $m['title'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ========== YEARS COUNTER WITH STAMP EFFECT ========== --}}
                <div class="mt-16 text-center" data-aos="fade-up">
                    {{-- Vintage stamp circle --}}
                    <div class="relative inline-flex items-center justify-center">
                        {{-- Stamp ring --}}
                        <div class="absolute inset-0 rounded-full border-2 border-dashed border-[#8b7355]/25"></div>
                        <div class="relative flex items-center gap-4 rounded-full bg-[#f5f0e8]/80 px-8 py-4">
                            <span class="font-serif text-3xl sm:text-4xl font-bold text-[#3a3226] tabular-nums">{{ $yearsActive }}</span>
                            <div class="text-left">
                                <span class="block font-serif text-[10px] text-[#8b7355] uppercase tracking-[0.2em] leading-tight">Años de</span>
                                <span class="block font-serif text-[10px] text-[#8b7355] uppercase tracking-[0.2em] leading-tight">Trayectoria</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Torn edge bottom --}}
            <div class="absolute -bottom-1 left-0 right-0 h-8 bg-[#f5f0e8]"
                 style="clip-path: polygon(0% 0%, 3% 60%, 7% 30%, 12% 70%, 18% 20%, 22% 80%, 28% 40%, 33% 60%, 38% 15%, 44% 65%, 50% 35%, 55% 75%, 61% 25%, 67% 55%, 72% 20%, 78% 70%, 83% 40%, 88% 60%, 93% 30%, 97% 65%, 100% 40%, 100% 0%, 0% 0%);"></div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- MISSION & VISION                                            --}}
    {{-- ============================================================ --}}
    @if ($hasMission || $hasVision)
        <section class="relative py-24 sm:py-32 bg-white overflow-hidden">
            <div class="absolute top-0 left-0 w-1/3 h-1/3 bg-gradient-to-br from-[#ff671f]/5 to-transparent rounded-br-[120px]"></div>
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <span class="inline-flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#ff671f]">
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                        Capítulo III
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                    </span>
                    <h2 class="mt-6 text-4xl sm:text-5xl font-bold text-zinc-900">Lo que nos impulsa</h2>
                </div>

                <div class="mt-14 grid gap-8 sm:grid-cols-2" data-aos="fade-up" data-aos-delay="100">
                    @if ($hasMission)
                        <div class="group relative overflow-hidden rounded-2xl border border-zinc-200/60 bg-gradient-to-br from-white to-zinc-50 p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-[#ff671f]/20">
                            <div class="absolute -top-10 -right-10 h-28 w-28 rounded-full bg-[#ff671f]/5 transition-all duration-500 group-hover:scale-[3] group-hover:bg-[#ff671f]/10"></div>
                            <div class="relative">
                                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-[#ff671f] to-[#e55a1a] text-white shadow-lg shadow-[#ff671f]/20 mb-5">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672Zm-7.518-.267A8.25 8.25 0 1 1 20.25 10.5M8.288 14.212A5.25 5.25 0 1 1 17.25 10.5"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-zinc-900">Nuestra Misión</h3>
                                <p class="mt-3 text-sm sm:text-base text-zinc-600 leading-relaxed">{{ $missionBody }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($hasVision)
                        <div class="group relative overflow-hidden rounded-2xl border border-zinc-200/60 bg-gradient-to-br from-white to-zinc-50 p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-[#ff671f]/20">
                            <div class="absolute -top-10 -right-10 h-28 w-28 rounded-full bg-[#ff671f]/5 transition-all duration-500 group-hover:scale-[3] group-hover:bg-[#ff671f]/10"></div>
                            <div class="relative">
                                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-[#ff671f] to-[#e55a1a] text-white shadow-lg shadow-[#ff671f]/20 mb-5">
                                    <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-zinc-900">Nuestra Visión</h3>
                                <p class="mt-3 text-sm sm:text-base text-zinc-600 leading-relaxed">{{ $visionBody }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- VALUES                                                      --}}
    {{-- ============================================================ --}}
    @if ($hasValues)
        <section class="relative py-24 sm:py-32 bg-gradient-to-b from-zinc-50 to-white overflow-hidden">
            <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
                 style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 32px 32px;">
            </div>
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <span class="inline-flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#ff671f]">
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                        Capítulo IV
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                    </span>
                    <h2 class="mt-6 text-4xl sm:text-5xl font-bold text-zinc-900">Nuestros valores</h2>
                    <p class="mt-3 text-sm text-zinc-400 max-w-xl mx-auto">{{ $valuesBody }}</p>
                </div>

                <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-aos="fade-up" data-aos-delay="100">
                    @foreach ($valueIcons as $v)
                        @php
                            $iconSvgs = [
                                'shield-check' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>',
                                'heart' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',
                                'users' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>',
                                'clipboard-check' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z"/></svg>',
                                'arrow-trending-up' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg>',
                                'globe-alt' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>',
                            ];
                        @endphp
                        <div class="group relative overflow-hidden rounded-2xl border border-zinc-200/60 bg-white p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-[#ff671f]/15">
                            <div class="absolute inset-0 bg-gradient-to-br from-[#ff671f]/5 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                            <div class="relative flex items-start gap-4">
                                <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-[#ff671f]/10 text-[#ff671f] transition-all duration-300 group-hover:bg-[#ff671f] group-hover:text-white group-hover:shadow-lg group-hover:shadow-[#ff671f]/20">
                                    {!! $iconSvgs[$v['icon']] ?? '' !!}
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-zinc-900 group-hover:text-[#ff671f] transition-colors">{{ $v['name'] }}</h3>
                                    <p class="mt-1 text-xs text-zinc-500 leading-relaxed">{{ $v['desc'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- QUALITY POLICY                                              --}}
    {{-- ============================================================ --}}
    @if ($hasQuality)
        <section class="relative py-20 sm:py-24 bg-white overflow-hidden">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-bl from-[#ff671f]/5 to-transparent"></div>
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl border border-zinc-200/60 bg-gradient-to-br from-zinc-50 to-white p-8 sm:p-12 text-center shadow-lg" data-aos="fade-up">
                    <div class="absolute -top-16 -right-16 h-40 w-40 rounded-full bg-[#ff671f]/5"></div>
                    <div class="absolute -bottom-8 -left-8 h-24 w-24 rounded-full bg-[#ff671f]/5"></div>
                    <div class="relative">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#ff671f] to-[#e55a1a] text-white shadow-xl shadow-[#ff671f]/20 mb-6">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-4">
                            Compromiso
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-zinc-900">Política de Calidad</h2>
                        <p class="mt-4 text-sm sm:text-base text-zinc-600 leading-relaxed max-w-2xl mx-auto">{{ $qualityBody }}</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- BRANCHES / LOCATIONS                                        --}}
    {{-- ============================================================ --}}
    @if ($hasBranches)
        <section class="relative py-24 sm:py-32 bg-gradient-to-b from-white to-zinc-50 overflow-hidden">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#ff671f]">
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                        Presencia nacional
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                    </span>
                    <h2 class="mt-6 text-4xl sm:text-5xl font-bold text-zinc-900">Nuestras sucursales</h2>
                    <p class="mt-3 text-sm text-zinc-400 max-w-lg mx-auto">Estamos presentes en las principales ciudades de Bolivia para servirte mejor.</p>
                </div>

                <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-aos="fade-up" data-aos-delay="100">
                    @foreach ($branches as $branch)
                        <div class="group relative overflow-hidden rounded-2xl border border-zinc-200/60 bg-white p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-[#ff671f]/20">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#ff671f] via-[#ff671f]/40 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                            <div class="flex items-start gap-4">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-[#ff671f]/10 text-[#ff671f]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-zinc-900">{{ $branch->name }}</h3>
                                    <p class="mt-0.5 text-xs font-medium text-[#ff671f]">{{ $branch->city }}</p>
                                    <p class="mt-1 text-xs text-zinc-500 leading-relaxed">{{ $branch->address }}</p>
                                    @if ($branch->phone)
                                        <p class="mt-1.5 text-xs text-zinc-400 flex items-center gap-1.5">
                                            <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                                            {{ $branch->phone }}
                                            @if ($branch->phone_2)
                                                <span class="text-zinc-300">·</span> {{ $branch->phone_2 }}
                                            @endif
                                        </p>
                                    @endif
                                    @if ($branch->email)
                                        <p class="mt-1 text-xs text-zinc-400 flex items-center gap-1.5">
                                            <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                            {{ $branch->email }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- FINAL CTA                                                    --}}
    {{-- ============================================================ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-zinc-900 via-zinc-800 to-[#1a1a1a] py-24 sm:py-32">
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 32px 32px;">
        </div>
        <div class="absolute top-0 left-1/3 h-80 w-80 rounded-full bg-[#ff671f]/8 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/3 h-60 w-60 rounded-full bg-[#ff671f]/5 blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h2 class="text-3xl sm:text-4xl font-bold text-white leading-tight">
                ¿Quieres ser parte de <span class="text-[#ff671f]">nuestra historia</span>?
            </h2>
            <p class="mt-4 text-base sm:text-lg text-zinc-300 max-w-xl mx-auto leading-relaxed">
                Únete al equipo de Laboratorios Delta y contribuye a la salud y bienestar de Bolivia.
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('public.work-with-us') }}"
                   class="group inline-flex items-center gap-2.5 rounded-full bg-[#ff671f] px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#ff671f]/25 transition-all duration-300 hover:bg-[#e55a1a] hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#ff671f]/30">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/></svg>
                    Únete al equipo
                </a>
                <a href="{{ route('public.contact') }}"
                   class="group inline-flex items-center gap-2.5 rounded-full border border-white/20 bg-white/5 backdrop-blur-sm px-7 py-3.5 text-sm font-semibold text-white transition-all duration-300 hover:bg-white/10 hover:border-white/30">
                    Contáctanos
                </a>
            </div>
        </div>
    </section>
</x-layouts::public>
