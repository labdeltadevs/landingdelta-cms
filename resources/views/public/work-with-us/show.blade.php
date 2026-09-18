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
@endphp

<x-layouts::public
    metaTitle="{{ $jobOpening->title }} — Trabaja con Nosotros"
    metaDescription="{{ Str::of(strip_tags($jobOpening->description))->limit(160)->trim() }}"
    :jsonLd="$jobJsonLd">

    {{-- Hero compacto --}}
    <section class="relative overflow-hidden bg-white py-12 sm:py-16">
        <div class="absolute inset-0 opacity-[0.035]"
            style="background-image: radial-gradient(circle, #000000 1px, transparent 1px); background-size: 28px 28px;">
        </div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-[#ff671f]/[0.03] blur-3xl pointer-events-none">
        </div>

        <div class="mx-auto max-w-4xl px-4 relative z-10 sm:px-6 lg:px-8">
            <nav class="mb-8 flex items-center gap-2 text-xs sm:text-sm text-zinc-400" data-aos="fade-up">
                <a href="{{ route('public.home') }}" class="hover:text-zinc-900 transition-colors">Inicio</a>
                <span>/</span>
                <a href="{{ route('public.work-with-us') }}" class="hover:text-zinc-900 transition-colors">Trabaja con Nosotros</a>
                <span>/</span>
                <span class="text-zinc-900 font-medium truncate max-w-[220px] sm:max-w-md">{{ $jobOpening->title }}</span>
            </nav>

            <div class="flex flex-wrap items-center gap-2 mb-5" data-aos="fade-up" data-aos-delay="50">
                <span
                    class="inline-flex items-center gap-1 rounded-lg bg-black/5 px-2.5 py-1 text-[11px] font-medium text-black/60 border border-black/5">
                    Vigente: {{ $jobOpening->valid_from->format('d/m/Y') }} — {{ $jobOpening->valid_until->format('d/m/Y') }}
                </span>
                <span
                    class="inline-flex items-center gap-1 rounded-lg bg-[#ff671f]/8 px-2.5 py-1 text-[11px] font-semibold text-[#ff671f] border border-[#ff671f]/10">
                    Publicado {{ $jobOpening->created_at->diffForHumans() }}
                </span>
            </div>

            <h1 class="text-2xl font-black text-black sm:text-4xl lg:text-5xl tracking-tight leading-tight"
                data-aos="fade-up" data-aos-delay="100">
                {{ $jobOpening->title }}
            </h1>
        </div>
    </section>

    {{-- Detalle --}}
    <section class="bg-zinc-50/50 border-t border-black/5">
        <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-black/8 overflow-hidden" data-aos="fade-up">
                @if ($jobOpening->image_url)
                    <img src="{{ $jobOpening->image_url }}" alt="{{ $jobOpening->title }}" loading="lazy"
                        class="w-full max-h-[420px] object-cover" />
                @endif

                <div class="p-6 sm:p-10">
                    <div class="text-sm sm:text-base text-zinc-700 leading-relaxed whitespace-pre-line">
                        {{ $jobOpening->description }}
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 border-t border-zinc-100 pt-6">
                        @if ($jobOpening->application_email)
                            <a href="mailto:{{ $jobOpening->application_email }}?subject=Postulación: {{ $jobOpening->title }}"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-[#ff671f] px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#ff671f]/25 transition-all duration-300 hover:bg-[#e55a1a] hover:shadow-xl">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                Enviar CV a {{ $jobOpening->application_email }}
                            </a>
                        @endif
                        <a href="{{ route('public.work-with-us') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-full border border-zinc-200 bg-white px-7 py-3.5 text-sm font-medium text-zinc-700 transition-all duration-300 hover:border-[#ff671f]/30 hover:text-[#ff671f]">
                            Ver convocatorias
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts::public>
