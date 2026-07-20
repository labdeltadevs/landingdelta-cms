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
                    <a href="#story"
                       class="group inline-flex items-center gap-2.5 rounded-full bg-[#ff671f] px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#ff671f]/25 transition-all duration-300 hover:bg-[#e55a1a] hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[#ff671f]/30">
                        <span>Descubre nuestra historia</span>
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
                <a href="#story" class="flex flex-col items-center gap-2 text-zinc-500 hover:text-zinc-300 transition-colors group">
                    <span class="text-[10px] font-medium uppercase tracking-[0.25em]">Descubre más</span>
                    <span class="flex h-9 w-6 items-start justify-center rounded-full border border-zinc-500 group-hover:border-zinc-300 transition-colors">
                        <span class="mt-1.5 h-2 w-1 rounded-full bg-zinc-500 group-hover:bg-zinc-300 animate-bounce"></span>
                    </span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- STORY / HISTORY — STORYTIME NARRATIVE                       --}}
    {{-- ============================================================ --}}
    @if ($hasHistory)
        <section id="story" class="relative py-24 sm:py-32 bg-white overflow-hidden">
            {{-- Decorative background --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-zinc-50 to-transparent"></div>
                <div class="absolute top-40 left-10 w-72 h-72 rounded-full bg-[#ff671f]/[0.02] blur-3xl"></div>
                <div class="absolute bottom-40 right-10 w-96 h-96 rounded-full bg-[#ff671f]/[0.02] blur-3xl"></div>
            </div>

            <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                {{-- Chapter header --}}
                <div class="text-center mb-16" data-aos="fade-up">
                    <span class="inline-flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#ff671f]">
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                        Capítulo I
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                    </span>
                    <h2 class="mt-6 text-4xl sm:text-5xl font-bold text-zinc-900 leading-tight">
                        Nuestra <span class="text-[#ff671f]">historia</span>
                    </h2>
                    <p class="mt-3 text-sm text-zinc-400 max-w-md mx-auto">
                        {{ $yearsActive }} años de compromiso con la salud y el bienestar de Bolivia
                    </p>
                </div>

                {{-- Story content — flowing narrative --}}
                <div class="space-y-8 sm:space-y-10 text-base sm:text-lg text-zinc-700 leading-[1.8] tracking-wide"
                     data-aos="fade-up" data-aos-delay="100">

                    {{-- Opening quote --}}
                    <div class="relative pl-8 sm:pl-12 border-l-2 border-[#ff671f]/30">
                        <svg class="absolute -left-3 -top-2 h-8 w-8 text-[#ff671f]/20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151C7.563 6.068 6 8.789 6 11h4v10H0z"/>
                        </svg>
                        <p class="text-lg sm:text-xl text-zinc-900 font-medium italic leading-relaxed">
                            {{ $historyParagraphs[0] ?? $historyBody }}
                        </p>
                    </div>

                    {{-- Rest of paragraphs with drop caps --}}
                    @foreach ($historyParagraphs as $idx => $paragraph)
                        @if ($idx === 0) @continue @endif
                        <div class="relative">
                            @php
                                $firstChar = mb_substr(trim($paragraph), 0, 1);
                                $restText = mb_substr(trim($paragraph), 1);
                                $isLetter = $firstChar && preg_match('/\pL/u', $firstChar);
                            @endphp
                            @if ($isLetter)
                                <span aria-hidden="true" class="float-left text-5xl sm:text-6xl font-bold text-[#ff671f]/20 leading-none mr-3 mt-1 select-none pointer-events-none font-serif">{{ $firstChar }}</span>
                            @endif
                            <p class="text-zinc-600 leading-relaxed">{{ $restText ? $firstChar . $restText : $paragraph }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Milestone highlight --}}
                @if ($hasMilestones)
                    <div class="mt-16 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3" data-aos="fade-up" data-aos-delay="200">
                        @foreach (array_slice($milestones, 0, 6) as $m)
                            <div class="group relative rounded-xl border border-zinc-200/60 bg-zinc-50/50 p-4 text-center transition-all duration-300 hover:bg-white hover:shadow-md hover:border-[#ff671f]/20 hover:-translate-y-0.5">
                                <div class="text-lg font-bold text-[#ff671f] tabular-nums">{{ $m['year'] }}</div>
                                <div class="mt-0.5 text-[10px] font-medium text-zinc-500 uppercase tracking-wider leading-tight">{{ $m['title'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Years counter --}}
                <div class="mt-16 text-center" data-aos="fade-up">
                    <div class="inline-flex items-center gap-3 rounded-full bg-zinc-100 px-6 py-3">
                        <span class="text-2xl font-bold text-[#ff671f]">{{ $yearsActive }}</span>
                        <span class="text-xs font-medium text-zinc-500 uppercase tracking-wider">Años de experiencia</span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- DYNAMIC TIMELINE                                            --}}
    {{-- ============================================================ --}}
    @if ($hasMilestones)
        <section class="relative py-24 sm:py-32 bg-gradient-to-b from-zinc-50 to-white overflow-hidden">
            <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
                 style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 24px 24px;">
            </div>
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="text-center" data-aos="fade-up">
                    <span class="inline-flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#ff671f]">
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                        Capítulo II
                        <span class="h-px w-8 bg-[#ff671f]/30"></span>
                    </span>
                    <h2 class="mt-6 text-4xl sm:text-5xl font-bold text-zinc-900">Nuestra travesía</h2>
                    <p class="mt-3 text-sm text-zinc-400 max-w-lg mx-auto">Hitos que marcaron nuestro camino y nos impulsan hacia el futuro.</p>
                </div>

                <div class="relative mt-16">
                    {{-- Vertical line --}}
                    <div class="absolute left-8 md:left-1/2 top-0 h-full w-0.5 bg-gradient-to-b from-[#ff671f] via-[#ff671f]/30 to-transparent md:-translate-x-px"></div>

                    @foreach ($milestones as $i => $m)
                        @php
                            $icons = [
                                'sparkles' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456Z"/></svg>',
                                'trending-up' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg>',
                                'globe-alt' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>',
                                'user-group' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>',
                                'rocket-launch' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/></svg>',
                                'forward' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z"/></svg>',
                                'calendar' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>',
                                'shield-check' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>',
                            ];
                            $iconSvg = $icons[$m['icon'] ?? ''] ?? $icons['calendar'];
                        @endphp
                        <div class="relative flex items-start gap-5 md:gap-10 pb-14 md:pb-18 last:pb-0 group"
                             data-aos="fade-up" data-aos-delay="{{ min($i * 60, 500) }}">
                            {{-- Dot / Icon --}}
                            <div class="relative z-10 flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#ff671f] to-[#e55a1a] text-white shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-[#ff671f]/30 md:h-14 md:w-14">
                                {!! $iconSvg !!}
                            </div>
                            {{-- Card --}}
                            <div class="flex-1 rounded-2xl border border-zinc-200/60 bg-white p-6 md:p-7 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-[#ff671f]/20 relative overflow-hidden">
                                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#ff671f] via-[#ff671f]/40 to-transparent"></div>
                                <div class="flex flex-wrap items-center gap-3 mb-2">
                                    <span class="inline-flex items-center rounded-full bg-[#ff671f]/10 px-3 py-0.5 text-xs font-bold text-[#ff671f]">
                                        {{ $m['year'] }}
                                    </span>
                                    <h3 class="text-base font-semibold text-zinc-900">{{ $m['title'] }}</h3>
                                </div>
                                @if ($m['desc'] ?? false)
                                    <p class="text-sm text-zinc-500 leading-relaxed">{{ $m['desc'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
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
