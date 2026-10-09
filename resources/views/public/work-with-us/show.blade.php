@php
    $jobSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => $jobOpening->title,
        'description' => strip_tags($jobOpening->description),
        'datePosted' => $jobOpening->created_at->toIso8601String(),
        'validThrough' => $jobOpening->valid_until->toIso8601String(),
        'employmentType' => 'FULL_TIME',
        'hiringOrganization' => [
            '@type' => 'Organization',
            'name' => 'Laboratorios Delta S.A.',
            'url' => config('app.url'),
        ],
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'La Paz',
                'addressCountry' => 'BO',
            ],
        ],
    ];

    if ($jobOpening->application_email) {
        $jobSchema['applicationContact'] = [
            '@type' => 'ContactPoint',
            'email' => $jobOpening->application_email,
            'contactType' => 'Recursos Humanos',
        ];
    }

    $jobJsonLd = json_encode($jobSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    // Validar si la oferta sigue vigente
    $isVigente = now()->between($jobOpening->valid_from, $jobOpening->valid_until);
@endphp

<x-layouts::public metaTitle="{{ $jobOpening->title }} — Trabaja con Nosotros"
    metaDescription="{{ Str::of(strip_tags($jobOpening->description))->limit(160)->trim() }}" :jsonLd="$jobJsonLd">

    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-white pt-12 pb-4 sm:pt-12 sm:pb-4 border-b border-zinc-200/60">
        {{-- Patrón de fondo --}}
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: radial-gradient(circle, #000000 1px, transparent 1px); background-size: 24px 24px;">
        </div>
        {{-- Resplandor naranja --}}
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] rounded-full bg-[#ff671f]/[0.04] blur-3xl pointer-events-none">
        </div>

        <div class="mx-auto max-w-6xl px-4 relative z-10 sm:px-2 lg:px-4">
            {{-- Breadcrumbs --}}
            <nav class="mb-6 flex items-center gap-2 text-sm text-zinc-500 font-medium" data-aos="fade-down">
                <a href="{{ route('public.home') }}" class="hover:text-[#ff671f] transition-colors">Inicio</a>
                <svg class="h-4 w-4 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('public.work-with-us') }}" class="hover:text-[#ff671f] transition-colors">Empleos</a>
                <svg class="h-4 w-4 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-zinc-900 truncate max-w-[200px] sm:max-w-md">{{ $jobOpening->title }}</span>
            </nav>

            {{-- Título y Badges --}}
            <div class="max-w-3xl" data-aos="fade-up">
                <h1 class="text-2xl font-black text-zinc-900 sm:text-2xl lg:text-2xl tracking-tight leading-[1.1] mb-6">
                    {{ $jobOpening->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-3">
                    @if ($isVigente)
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 border border-emerald-200/60">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Convocatoria Abierta
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 border border-red-200/60">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                            Convocatoria Cerrada
                        </span>
                    @endif

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-3 py-1.5 text-xs font-medium text-zinc-600 border border-zinc-200/80">
                        <svg class="h-3.5 w-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Publicado {{ $jobOpening->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content - Grid Layout --}}
    <section class="bg-zinc-50/50 pb-20 pt-6 sm:py-6">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- Columna Izquierda: Descripción (toma 8 columnas) --}}
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-sm overflow-hidden"
                        data-aos="fade-up">
                        @if ($jobOpening->image_url)
                            <img src="{{ $jobOpening->image_url }}"
                                alt="Imagen descriptiva para {{ $jobOpening->title }}" loading="lazy"
                                class="w-full h-auto max-h-[400px] object-cover" />
                        @endif

                        <div class="p-6 sm:p-10">
                            <h2 class="text-xl font-bold text-zinc-900 mb-6 border-b border-zinc-100 pb-4">Detalles del
                                cargo</h2>

                            {{-- NOTA: Se usa 'prose' de Tailwind Typography para darle formato automático a párrafos y listas.
                                 Si guardas HTML en la DB, cambia nl2br(e()) por {!! $jobOpening->description !!} --}}
                            <div
                                class="prose prose-zinc prose-a:text-[#ff671f] max-w-none text-zinc-700 leading-relaxed text-sm sm:text-base">
                                {!! nl2br(e($jobOpening->description)) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Sidebar Sticky (toma 4 columnas) --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-white rounded-3xl border border-zinc-200/80 shadow-sm p-6 sm:p-8">

                        <h3 class="text-lg font-bold text-zinc-900 mb-6">Resumen de la oferta</h3>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start gap-3">
                                <div class="p-2 bg-[#ff671f]/10 rounded-lg text-[#ff671f]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Apertura</p>
                                    <p class="text-sm font-medium text-zinc-900">
                                        {{ $jobOpening->valid_from->format('d/m/Y') }}</p>
                                </div>
                            </li>

                            <li class="flex items-start gap-3">
                                <div class="p-2 bg-[#ff671f]/10 rounded-lg text-[#ff671f]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Cierre</p>
                                    <p class="text-sm font-medium text-zinc-900">
                                        {{ $jobOpening->valid_until->format('d/m/Y') }}</p>
                                </div>
                            </li>

                            <li class="flex items-start gap-3">
                                <div class="p-2 bg-[#ff671f]/10 rounded-lg text-[#ff671f]">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Modalidad
                                    </p>
                                    <p class="text-sm font-medium text-zinc-900">Tiempo Completo</p>
                                </div>
                            </li>
                        </ul>

                        @if ($jobOpening->application_email && $isVigente)
                            <a href="mailto:{{ $jobOpening->application_email }}?subject=Postulación: {{ $jobOpening->title }}"
                                class="flex items-center justify-center gap-2 w-full rounded-2xl bg-[#ff671f] px-6 py-4 text-sm font-bold text-white shadow-lg shadow-[#ff671f]/25 transition-all duration-300 hover:bg-[#e55a1a] hover:-translate-y-0.5 hover:shadow-xl focus:ring-4 focus:ring-[#ff671f]/20">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                Postularme ahora
                            </a>
                        @elseif(!$isVigente)
                            <div
                                class="w-full rounded-2xl bg-zinc-100 px-6 py-4 text-center text-sm font-bold text-zinc-500 cursor-not-allowed">
                                Convocatoria Finalizada
                            </div>
                        @endif

                        <a href="{{ route('public.work-with-us') }}"
                            class="mt-3 flex w-full items-center justify-center gap-2 rounded-2xl border border-zinc-200 bg-white px-6 py-4 text-sm font-semibold text-zinc-700 transition-all duration-300 hover:bg-zinc-50 hover:border-zinc-300">
                            Volver a convocatorias
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- CTA Flotante para Móviles (Solo visible en pantallas pequeñas) --}}
    @if ($jobOpening->application_email && $isVigente)
        <div
            class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-zinc-200/80 p-4 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] lg:hidden">
            <a href="mailto:{{ $jobOpening->application_email }}?subject=Postulación: {{ $jobOpening->title }}"
                class="flex items-center justify-center gap-2 w-full rounded-xl bg-[#ff671f] px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-[#ff671f]/20">
                Postularme por Correo
            </a>
        </div>
    @endif
</x-layouts::public>
