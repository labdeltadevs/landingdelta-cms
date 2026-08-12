<x-layouts::public metaTitle="Noticias"
    metaDescription="Últimas noticias, comunicados y novedades de Laboratorios Delta S.A. — Mantenete informado sobre la industria farmacéutica boliviana.">
    @php
        $featuredNews = $news->first(); // La primera como destacada
    @endphp

    <div class="relative overflow-hidden bg-zinc-50">
        {{-- Patrón puntos + glows --}}
        <div class="pointer-events-none absolute inset-0 opacity-60"
            style="background-image: radial-gradient(circle, #e4e4e7 1px, transparent 1px); background-size: 22px 22px;">
        </div>
        <div class="pointer-events-none absolute -top-24 left-1/4 h-96 w-96 rounded-full bg-[#ff671f]/5 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-72 w-72 rounded-full bg-orange-200/20 blur-3xl">
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">

            {{-- Header editorial --}}
            <header class="mb-14 text-center" data-aos="fade-up">
                <span
                    class="inline-flex items-center gap-3 text-[11px] font-bold uppercase tracking-[0.25em] text-[#ff671f] mb-4">
                    <span class="h-[2px] w-10 rounded-full bg-[#ff671f]/60"></span>
                    Archivo de informes
                    <span class="h-[2px] w-10 rounded-full bg-[#ff671f]/60"></span>
                </span>
                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-zinc-900 leading-[1.1]">
                    Noticias, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#ff671f] to-orange-400">Notas y Comunicados</span>
                </h1>
                <p class="mt-4 text-base sm:text-lg text-zinc-500 max-w-2xl mx-auto">
                    Siempre manteniendo informada a nuestra comunidad.
                </p>
            </header>

            {{-- Hero / Noticia destacada tipo "informe principal" --}}
            @if ($featuredNews)
                <section class="mb-12" data-aos="fade-up" data-aos-delay="50">
                    <a href="{{ route('public.news.show', $featuredNews) }}"
                        class="group relative block rounded-3xl overflow-hidden bg-white border border-zinc-200 shadow-[0_4px_30px_-10px_rgba(0,0,0,0.08)] transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_20px_50px_-12px_rgba(255,103,31,0.12)] hover:border-[#ff671f]/20">
                        <div class="grid md:grid-cols-2 items-stretch">
                            {{-- Imagen grande con efecto de "documento escaneado" --}}
                            <div class="relative h-72 md:h-auto min-h-[340px] overflow-hidden bg-zinc-100">
                                @if ($featuredNews->cover_image_path)
                                    <img src="{{ $featuredNews->cover_image_url }}" alt="{{ $featuredNews->title }}"
                                        loading="lazy"
                                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.05]" />
                                @else
                                    <div
                                        class="flex h-full w-full items-center justify-center bg-gradient-to-br from-zinc-200 to-zinc-300">
                                        <svg class="h-16 w-16 text-zinc-400" fill="none" stroke="currentColor"
                                            stroke-width="1" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0h5.25m-9 0H3.375c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h3.75c.621 0 1.125-.504 1.125-1.125V3.375m-4.5 8.25l-2.25 2.25-2.25-2.25m2.25 2.25v7.5" />
                                        </svg>
                                    </div>
                                @endif
                                {{-- Etiqueta de informe destacado --}}
                                <div class="absolute top-5 left-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-[#ff671f] px-3.5 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white shadow-xl shadow-orange-600/30">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                        </svg>
                                        Informe principal
                                    </span>
                                </div>
                            </div>

                            {{-- Contenido tipo "hoja" --}}
                            <div
                                class="relative p-8 sm:p-10 lg:p-14 flex flex-col justify-center bg-gradient-to-br from-white to-zinc-50/50">
                                {{-- Línea decorativa izquierda como carpeta/archivo --}}
                                <div
                                    class="absolute left-0 top-8 bottom-8 w-1.5 rounded-full bg-gradient-to-b from-[#ff671f] to-orange-300 opacity-60">
                                </div>

                                <div class="pl-6">
                                    <time
                                        class="inline-block text-xs font-bold uppercase tracking-[0.15em] text-[#ff671f]"
                                        datetime="{{ $featuredNews->published_at?->format('Y-m-d') }}">
                                        {{ $featuredNews->published_at?->format('d/m/Y') ?? 'Sin fecha' }}
                                    </time>
                                    <h2
                                        class="mt-3 text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight tracking-tight text-zinc-900">
                                        {{ $featuredNews->title }}
                                    </h2>
                                    @if ($featuredNews->excerpt)
                                        <p class="mt-4 text-base sm:text-lg text-zinc-500 leading-relaxed line-clamp-3">
                                            {{ $featuredNews->excerpt }}
                                        </p>
                                    @endif
                                    <div
                                        class="mt-8 flex items-center gap-2 text-sm font-semibold text-[#ff671f] tracking-wide transition-colors group-hover:text-[#e55a1a]">
                                        Leer informe completo
                                        <span
                                            class="inline-block transition-transform group-hover:translate-x-1">→</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </section>
            @endif

            {{-- Grid resto: "notas" tipo ficha/documento --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($news->skip(1)->take(6) as $item)
                    <a href="{{ route('public.news.show', $item) }}"
                        class="group relative flex flex-col rounded-2xl border border-zinc-200 bg-white overflow-hidden shadow-[0_2px_12px_-2px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#ff671f]/5 hover:border-[#ff671f]/20">
                        {{-- "Clip" decorativo de nota en la esquina superior derecha --}}
                        <div class="absolute top-0 right-0 z-10">
                            <div class="h-12 w-12 overflow-hidden">
                                <div
                                    class="flex h-full w-full items-end justify-end bg-[#ff671f] text-white translate-x-3 translate-y-[-3px] rotate-45 shadow-lg">
                                    <svg class="h-4 w-4 mb-2 mr-2" fill="none" stroke="currentColor"
                                        stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        @if ($item->cover_image_path)
                            <div class="aspect-[16/10] overflow-hidden bg-zinc-100 relative">
                                <img src="{{ $item->cover_image_url }}" alt="{{ $item->title }}" loading="lazy"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                                {{-- Gradiente inferior para legibilidad si hay texto superpuesto, pero aquí usamos abajo --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                            </div>
                        @else
                            <div
                                class="aspect-[16/10] overflow-hidden bg-gradient-to-br from-zinc-100 to-zinc-200 flex items-center justify-center">
                                <svg class="h-10 w-10 text-zinc-300" fill="none" stroke="currentColor"
                                    stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0h5.25m-9 0H3.375c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h3.75c.621 0 1.125-.504 1.125-1.125V3.375m-4.5 8.25l-2.25 2.25-2.25-2.25m2.25 2.25v7.5" />
                                </svg>
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col p-6">
                            {{-- Línea estilizada de "informe" a la izquierda --}}
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-1 h-full flex-none rounded-full bg-gradient-to-b from-[#ff671f]/60 to-transparent self-stretch min-h-[40px]">
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <time class="text-[11px] font-bold uppercase tracking-wider text-zinc-400"
                                            datetime="{{ $item->published_at?->format('Y-m-d') }}">
                                            {{ $item->published_at?->format('d M Y') ?? 'Sin fecha' }}
                                        </time>
                                        <span class="h-0.5 w-0.5 rounded-full bg-zinc-300"></span>
                                        <span
                                            class="text-[10px] font-bold uppercase text-[#ff671f]/80 tracking-wider">Nota</span>
                                    </div>

                                    <h3
                                        class="font-bold text-zinc-900 leading-snug transition-colors duration-300 group-hover:text-[#e55a1a] line-clamp-2">
                                        {{ $item->title }}
                                    </h3>

                                    @if ($item->excerpt)
                                        <p class="mt-2 text-sm text-zinc-500 leading-relaxed line-clamp-3">
                                            {{ $item->excerpt }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="mt-auto pt-5 flex items-center justify-between text-xs font-semibold uppercase tracking-widest text-zinc-400 group-hover:text-[#ff671f] transition-colors">
                                <span>Leer documento</span>
                                <span
                                    class="h-7 w-7 flex items-center justify-center rounded-full bg-zinc-100 text-zinc-500 transition-all group-hover:bg-[#ff671f] group-hover:text-white">
                                    <svg class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5"
                                        fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div
                        class="col-span-full flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-zinc-300 bg-white/50 py-24 text-center">
                        <svg class="h-14 w-14 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0h5.25m-9 0H3.375c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h3.75c.621 0 1.125-.504 1.125-1.125V3.375m-4.5 8.25l-2.25 2.25-2.25-2.25m2.25 2.25v7.5" />
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-zinc-700">Archivo vacío</h3>
                        <p class="mt-1 text-sm text-zinc-400">No hay notas ni comunicados publicados aún.</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginación estilizada tipo informe --}}
            @if ($news->hasPages())
                <div class="mt-14 flex justify-center" data-aos="fade-up">
                    <nav class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white p-1.5 shadow-sm"
                        aria-label="Paginación">
                        {{-- Previous --}}
                        @if ($news->onFirstPage())
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-full text-zinc-300 cursor-default"
                                aria-disabled="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                            </span>
                        @else
                            <a href="{{ $news->previousPageUrl() }}"
                                class="flex h-9 w-9 items-center justify-center rounded-full text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900"
                                aria-label="Anterior">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                            </a>
                        @endif

                        {{-- Numbers --}}
                        @foreach ($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                            @if ($page == $news->currentPage())
                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-[#ff671f] text-sm font-bold text-white shadow-md shadow-orange-500/30"
                                    aria-current="page">{{ $page }}</span>
                            @elseif (
                                $page == 1 ||
                                    $page == $news->lastPage() ||
                                    ($page >= $news->currentPage() - 1 && $page <= $news->currentPage() + 1))
                                <a href="{{ $url }}"
                                    class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-medium text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900"
                                    aria-label="Página {{ $page }}">{{ $page }}</a>
                            @elseif ($page == $news->currentPage() - 2 || $page == $news->currentPage() + 2)
                                <span class="flex h-9 w-9 items-center justify-center text-zinc-300">…</span>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($news->hasMorePages())
                            <a href="{{ $news->nextPageUrl() }}"
                                class="flex h-9 w-9 items-center justify-center rounded-full text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900"
                                aria-label="Siguiente">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        @else
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-full text-zinc-300 cursor-default"
                                aria-disabled="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </span>
                        @endif
                    </nav>
                </div>
            @endif

        </div>
    </div>
</x-layouts::public>
