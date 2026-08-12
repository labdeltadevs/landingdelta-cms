<x-layouts::public metaTitle="Contacto"
    metaDescription="Encuentra las oficinas, sucursales y datos de contacto de Laboratorios Delta S.A. en La Paz, Santa Cruz, Cochabamba y Chuquisaca.">
    @php
        $companyName = App\Models\SiteSetting::get('company_name', 'Laboratorios Delta S.A.');
        $contactEmail = App\Models\SiteSetting::get('contact_email', 'info@delta.lab');
        $contactPhone = App\Models\SiteSetting::get('contact_phone', '');

        $branches = \App\Models\Branch::query()->active()->ordered()->get();

        $socialIcons = [
            'linkedin' =>
                '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
            'facebook' =>
                '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            'instagram' =>
                '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913a5.885 5.885 0 0 0 1.384 2.126A5.868 5.868 0 0 0 4.14 23.37c.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558a5.898 5.898 0 0 0 2.126-1.384 5.86 5.86 0 0 0 1.384-2.126c.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913a5.89 5.89 0 0 0-1.384-2.126A5.847 5.847 0 0 0 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227a3.81 3.81 0 0 1-.899 1.382 3.744 3.744 0 0 1-1.38.896c-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421a3.716 3.716 0 0 1-1.379-.899 3.644 3.644 0 0 1-.9-1.38c-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 1 0 0-12.324zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405a1.441 1.441 0 1 1-2.882 0 1.441 1.441 0 0 1 2.882 0z"/></svg>',
            'tiktok' =>
                '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
        ];
    @endphp

    {{-- ============================================================ --}}
    {{-- CONTACT PAGE (LIGHT)                                          --}}
    {{-- ============================================================ --}}
    <div class="relative min-h-screen bg-zinc-50 overflow-hidden">

        {{-- Background Pattern --}}
        <div class="pointer-events-none absolute inset-0 opacity-60"
            style="background-image: radial-gradient(circle, #e4e4e7 1px, transparent 1px); background-size: 22px 22px;">
        </div>
        <div class="pointer-events-none absolute -top-24 right-0 h-96 w-96 rounded-full bg-[#ff671f]/5 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 h-72 w-72 rounded-full bg-orange-200/20 blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-12" data-aos="fade-up">
                <span
                    class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-4">
                    <span class="h-px w-8 bg-[#ff671f]/50"></span>
                    Contáctanos
                    <span class="h-px w-8 bg-[#ff671f]/50"></span>
                </span>
                <h1 class="text-3xl sm:text-5xl font-bold text-zinc-900">Nuestras <span
                        class="text-[#ff671f]">oficinas</span></h1>
                <p class="mt-4 text-base text-zinc-500 max-w-xl mx-auto">
                    Estamos ubicados en las principales ciudades del país para servirte mejor. Contáctanos o visítanos.
                </p>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid gap-8 lg:grid-cols-12">

                {{-- Contact Info Cards --}}
                <div class="lg:col-span-8 space-y-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="grid gap-6 sm:grid-cols-2">
                        @forelse ($branches as $branch)
                            <div class="group relative rounded-2xl border border-zinc-200 bg-white p-6 shadow-[0_2px_12px_-2px_rgba(0,0,0,0.05)] transition-all duration-300 hover:border-[#ff671f]/30 hover:shadow-[0_8px_30px_-6px_rgba(255,103,31,0.12)] hover:-translate-y-1"
                                data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">

                                {{-- Header Badge --}}
                                <div class="absolute -top-3 left-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 text-[10px] font-semibold uppercase tracking-wider text-[#ff671f] shadow-sm ring-1 ring-[#ff671f]/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-[#ff671f] animate-pulse"></span>
                                        {{ $branch->city }}
                                    </span>
                                </div>

                                {{-- Branch Name --}}
                                <div class="flex items-start gap-3 mb-4">
                                    <div
                                        class="flex h-10 w-10 flex-none items-center justify-center rounded-xl bg-orange-50 text-[#ff671f] ring-1 ring-orange-100 transition-colors duration-300 group-hover:bg-[#ff671f] group-hover:text-white group-hover:ring-[#ff671f] group-hover:shadow-lg group-hover:shadow-[#ff671f]/30">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2
                                            class="font-semibold text-zinc-900 transition-colors group-hover:text-[#e55a1a]">
                                            {{ $branch->name }}</h2>
                                    </div>
                                </div>

                                {{-- Address --}}
                                @if (!empty($branch->address))
                                    <div class="flex items-start gap-2.5 text-sm text-zinc-500 mb-4">
                                        <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-zinc-400" fill="none"
                                            stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                        </svg>
                                        <span>{{ $branch->address }}</span>
                                    </div>
                                @endif

                                {{-- Divider --}}
                                <div class="border-t border-zinc-100 mb-4"></div>

                                {{-- Contact Details --}}
                                <div class="space-y-2.5">
                                    @if ($branch->phone)
                                        <a href="tel:{{ preg_replace('/\s+/', '', $branch->phone) }}"
                                            class="flex items-center gap-2.5 text-sm text-zinc-600 transition-colors hover:text-[#ff671f]">
                                            <span
                                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-md bg-zinc-100 text-zinc-500 transition-colors group-hover:bg-orange-50 group-hover:text-[#ff671f]">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                                </svg>
                                            </span>
                                            {{ $branch->phone }}
                                            <span class="text-[10px] font-medium text-zinc-400 ml-auto">Fijo</span>
                                        </a>
                                    @endif

                                    @if ($branch->phone_2)
                                        <a href="tel:{{ preg_replace('/\s+/', '', $branch->phone_2) }}"
                                            class="flex items-center gap-2.5 text-sm text-zinc-600 transition-colors hover:text-[#ff671f]">
                                            <span
                                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-md bg-zinc-100 text-zinc-500 transition-colors group-hover:bg-orange-50 group-hover:text-[#ff671f]">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                                </svg>
                                            </span>
                                            {{ $branch->phone_2 }}
                                            <span class="text-[10px] font-medium text-zinc-400 ml-auto">Celular</span>
                                        </a>
                                    @endif

                                    @if ($branch->email)
                                        <a href="mailto:{{ $branch->email }}"
                                            class="flex items-center gap-2.5 text-sm text-zinc-600 transition-colors hover:text-[#ff671f]">
                                            <span
                                                class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-md bg-zinc-100 text-zinc-500 transition-colors group-hover:bg-orange-50 group-hover:text-[#ff671f]">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                                </svg>
                                            </span>
                                            <span class="truncate">{{ $branch->email }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div
                                class="col-span-2 flex flex-col items-center justify-center rounded-2xl border border-dashed border-zinc-300 bg-zinc-100/50 py-20 text-center">
                                <svg class="h-12 w-12 text-zinc-300" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <p class="mt-3 text-sm font-medium text-zinc-500">No hay sucursales registradas</p>
                                <p class="mt-1 text-xs text-zinc-400">Próximamente estaremos en más ciudades.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar: Contact CTA + Social --}}
                <div class="lg:col-span-4 space-y-6" data-aos="fade-up" data-aos-delay="200">
                    {{-- Contact CTA Card --}}
                    <div
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-[0_2px_12px_-2px_rgba(0,0,0,0.05)]">
                        <h3 class="text-base font-semibold text-zinc-900">¿Necesitas ayuda?</h3>
                        <p class="mt-2 text-sm text-zinc-500">Contáctanos directamente y nuestro equipo te atenderá lo
                            antes posible.</p>

                        <div class="mt-5 space-y-3">
                            @if ($contactPhone)
                                <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#ff671f] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/30 transition-all hover:bg-[#e55a1a] hover:-translate-y-0.5 hover:shadow-orange-500/40 active:translate-y-0">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                    </svg>
                                    Llamar ahora
                                </a>
                            @endif

                            <a href="mailto:{{ $contactEmail }}"
                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-5 py-3 text-sm font-medium text-zinc-700 transition-all hover:border-[#ff671f]/30 hover:bg-[#ff671f]/5 hover:text-[#ff671f]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                Enviar correo
                            </a>
                        </div>
                    </div>

                    {{-- Social Media Card --}}
                    <div
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-[0_2px_12px_-2px_rgba(0,0,0,0.05)]">
                        <h3 class="text-base font-semibold text-zinc-900">Síguenos</h3>
                        <p class="mt-2 text-sm text-zinc-500">Mantente al día con nuestras novedades.</p>

                        <div class="mt-5 flex flex-wrap gap-3">
                            @php $hasSocial = false; @endphp
                            @foreach ($socialIcons as $key => $svg)
                                @php
                                    $socialUrl = App\Models\SiteSetting::get('social_' . $key);
                                @endphp
                                @if ($socialUrl)
                                    @php $hasSocial = true; @endphp
                                    <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer"
                                        aria-label="{{ ucfirst($key) }}"
                                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600 ring-1 ring-zinc-200 transition-all duration-300 hover:scale-105 hover:text-white
                                        {{ $key === 'linkedin' ? 'hover:bg-[#0A66C2] hover:ring-[#0A66C2]' : '' }}
                                        {{ $key === 'facebook' ? 'hover:bg-[#1877F2] hover:ring-[#1877F2]' : '' }}
                                        {{ $key === 'instagram' ? 'hover:bg-gradient-to-br hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:ring-[#dc2743]' : '' }}
                                        {{ $key === 'tiktok' ? 'hover:bg-black hover:ring-zinc-700' : '' }}">
                                        {!! $svg !!}
                                    </a>
                                @endif
                            @endforeach

                            @unless ($hasSocial)
                                <p class="text-sm text-zinc-400">Próximamente en redes.</p>
                            @endunless
                        </div>
                    </div>

                    {{-- Working Hours Card --}}
                    <div
                        class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-[0_2px_12px_-2px_rgba(0,0,0,0.05)]">
                        <h3 class="text-base font-semibold text-zinc-900">Horario de atención</h3>

                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-zinc-500">Lunes - Viernes</span>
                                <span class="font-medium text-zinc-700">08:00 - 16:30</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-zinc-500">Sábados</span>
                                <span class="font-medium text-zinc-700">09:00 - 13:00</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-zinc-500">Domingos</span>
                                <span class="font-medium text-zinc-400">Cerrado</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::public>
