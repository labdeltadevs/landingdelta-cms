<x-layouts::public metaTitle="Inicio"
    metaDescription="Laboratorios Delta S.A. — Líder en la industria farmacéutica boliviana. Productos de alta calidad, divisiones exclusivas y presencia nacional en los 9 departamentos.">
    @php
        $slides = \App\Models\HeroSlide::query()->active()->valid()->ordered()->with('slideable')->get();
        $categories = \App\Models\Category::query()->active()->ordered()->get();
        $featured = \App\Models\Product::query()
            ->active()
            ->featured()
            ->with('brand', 'category')
            ->ordered()
            ->take(6)
            ->get();
        $brands = \App\Models\Brand::query()->active()->ordered()->get();
        $branches = \App\Models\Branch::query()->active()->ordered()->get();
        $productsCount = \App\Models\Product::query()->active()->count();
    @endphp


    {{-- ============================================================ --}}
    {{-- SECTION 1: HERO · EDITORIAL PREMIUM                          --}}
    {{-- ============================================================ --}}
    @php
        // WebP optimizados a 1920px (~60-125KB vs 1MB+ originales).
        // fondo-c/d se mantienen en JPEG (ya optimizados, WebP no mejora).
        $backgroundSlides = [
            Storage::disk('public')->url('fondo-a-1920.webp'),
            Storage::disk('public')->url('fondo-b-1920.webp'),
            Storage::disk('public')->url('fondo-c.jpeg'),
            Storage::disk('public')->url('fondo-d.jpeg'),
            Storage::disk('public')->url('fondo-e-1920.webp'),
            Storage::disk('public')->url('fondo-f-1920.webp'),
        ];

        $jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;

        $galardones = [
            [
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>',
                'label' => '30+ años',
                'sub' => 'de experiencia',
            ],
            [
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 2.25 2.25L15 9.75"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2.714A11.96 11.96 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                </svg>',
                'label' => 'Orgullo',
                'sub' => 'boliviano',
            ],
            [
                'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                </svg>',
                'label' => 'Presencia',
                'sub' => 'en el eje troncal',
            ],
        ];
    @endphp

    <section id="inicio"
        class="relative isolate flex min-h-[100svh] items-center overflow-hidden bg-zinc-100 lg:min-h-[90vh]"
        x-data="{
            slideCount: {{ count($backgroundSlides) }},
            current: 0,
            cycle: 0,
            initHero() {
                setInterval(() => {
                    this.current = (this.current + 1) % this.slideCount;
                    this.cycle++;
                }, 7500);
            }
        }" x-init="initHero()">

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        {{-- ================================================= --}}
        {{-- BACKGROUND SLIDESHOW                              --}}
        {{-- ================================================= --}}
        <div class="absolute inset-0" aria-hidden="true" x-data='{ slides: {!! json_encode($backgroundSlides, $jsonFlags) !!} }'>

            {{-- Imágenes: <img> en lugar de CSS background para LCP optimizable.
                 Primera con fetchpriority alta + eager; resto eager (slideshow activo). --}}
            <template x-for="(src, i) in slides" :key="i">
                <img :src="src" alt="" aria-hidden="true" draggable="false"
                    :fetchpriority="i === 0 ? 'high' : 'auto'" :loading="i === 0 ? 'eager' : 'eager'"
                    :decoding="i === 0 ? 'sync' : 'async'" width="1920" height="1080"
                    class="hero-bg-layer absolute inset-0 h-full w-full object-cover"
                    :class="current === i ? (i === 0 && cycle === 0 ? 'hero-bg-first' : 'hero-bg-active') : ''"
                    :style="{ opacity: current === i ? 1 : 0 }">
            </template>

            {{-- Destellos decorativos --}}
            <div class="absolute -left-40 top-1/4 h-64 w-64 rounded-full bg-[#ff671f]/20 blur-3xl sm:h-80 sm:w-80">
            </div>

            <div class="absolute -right-40 bottom-0 h-72 w-72 rounded-full bg-[#ff671f]/10 blur-3xl sm:h-96 sm:w-96">
            </div>

            {{-- Overlay claro --}}
            <div class="pointer-events-none absolute inset-0"
                style="background:
                linear-gradient(
                    90deg,
                    rgba(150,150,150,0.74) 0%,
                    rgba(150,150,150,0.56) 52%,
                    rgba(150,150,150,0.64) 100%
                ),
                linear-gradient(
                    180deg,
                    rgba(150,150,150,0.32) 0%,
                    rgba(150,150,150,0.18) 45%,
                    rgba(150,150,150,0.70) 100%
                );">
            </div>

            {{-- Patrón sutil --}}
            <div class="pointer-events-none absolute inset-0 opacity-30 sm:opacity-40"
                style="
                background-image: radial-gradient(
                    circle,
                    rgba(255,103,31,0.16) 1px,
                    transparent 1.5px
                );
                background-size: 32px 32px;
            ">
            </div>

            {{-- Barra de progreso --}}
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-zinc-900/10">
                <div class="h-full bg-gradient-to-r from-[#ff671f] to-[#ff671f]/30" x-init="const resetProgress = () => {
                    $el.style.transition = 'none';
                    $el.style.width = '0%';

                    void $el.offsetWidth;

                    $el.style.transition = 'width 7500ms linear';

                    requestAnimationFrame(() => {
                        $el.style.width = '100%';
                    });
                };

                resetProgress();
                $watch('current', resetProgress);">
                </div>
            </div>
        </div>

        {{-- ================================================= --}}
        {{-- CONTENIDO PRINCIPAL                               --}}
        {{-- ================================================= --}}
        <div class="relative z-10 mx-auto min-w-[75vw] lg:max-w-[75vw] px-4 pt-8 pb-2 sm:px-8 lg:px-8">

            {{-- Panel principal glassmorphism --}}
            <div
                class="hero-reveal relative overflow-hidden rounded-[1.5rem] border border-white/[0.80] bg-white/[0.60] backdrop-blur-2xl sm:rounded-[2rem]">

                {{-- Decoración interna --}}
                <div
                    class="pointer-events-none absolute -right-32 -top-32 h-64 w-64 rounded-full bg-[#ff671f]/10 blur-3xl sm:h-80 sm:w-80">
                </div>

                <div
                    class="pointer-events-none absolute -bottom-40 -left-32 h-64 w-64 rounded-full bg-white/50 blur-3xl sm:h-80 sm:w-80">
                </div>

                <div class="relative z-10 p-4 sm:p-6 lg:p-8 xl:p-10">

                    <div
                        class="grid gap-8 lg:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)] lg:items-stretch xl:gap-12">

                        {{-- ================================================= --}}
                        {{-- ZONA IZQUIERDA                                    --}}
                        {{-- ================================================= --}}
                        <div class="flex min-w-0 flex-col justify-between">

                            <div>
                                {{-- Logo --}}
                                <div class="hero-reveal-scale mb-7 flex justify-center lg:justify-start"
                                    style="animation-delay:150ms">
                                    <img src="{{ Storage::disk('public')->url('logo_delta.png') }}"
                                        alt="Laboratorios Delta S.A." fetchpriority="high" decoding="sync"
                                        width="728" height="174"
                                        class="h-16 w-auto mx-auto max-w-[320px] object-contain drop-shadow-sm sm:h-20 sm:max-w-[420px] lg:h-24 xl:h-28">
                                </div>

                                {{-- Badge institucional --}}
                                <div class="hero-reveal mb-4 inline-flex items-center gap-2 rounded-full border border-[#ff671f]/15 bg-[#ff671f]/8 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#d95417]"
                                    style="animation-delay:220ms">
                                    <span class="size-1.5 rounded-full bg-[#ff671f]"></span>
                                    Líder en la industria farmacéutica boliviana
                                </div>

                                {{-- Contenido principal --}}
                                <div class="hero-reveal max-w-4xl" style="animation-delay:280ms">
                                    <h1
                                        class="text-xl leading-[1.02] tracking-[-0.045em] text-zinc-950 sm:text-5xl lg:text-6xl xl:text-7xl">
                                        Cuidando la salud de nuestra gente
                                    </h1>

                                    <p class="mt-5 max-w-2xl text-base leading-7 text-zinc-600 sm:text-lg">
                                        Comprometidos con la salud y el bienestar de los bolivianos,
                                        ofreciendo productos farmacéuticos de alta calidad.
                                    </p>
                                </div>

                                {{-- Botones principales --}}
                                <div class="hero-reveal mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center"
                                    style="animation-delay:380ms">
                                    <a href="{{ route('public.products.index') }}"
                                        class="group inline-flex w-full items-center justify-center gap-2.5 rounded-full bg-[#ff671f] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-[#ff671f]/20 transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#e85d1c] hover:shadow-xl hover:shadow-[#ff671f]/25 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#ff671f] focus-visible:ring-offset-2 sm:w-auto">
                                        <span>Explorar productos</span>

                                        <svg class="size-4 transition-transform duration-300 group-hover:translate-x-0.5"
                                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('public.about') }}"
                                        class="group inline-flex w-full items-center justify-center gap-2.5 rounded-full border border-zinc-900/10 bg-white/55 px-6 py-3 text-sm font-semibold text-zinc-800 shadow-sm backdrop-blur-md transition-all duration-300 hover:-translate-y-0.5 hover:border-[#ff671f]/25 hover:bg-white/80 hover:text-zinc-950 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#ff671f] focus-visible:ring-offset-2 sm:w-auto">
                                        <span>Nuestra historia</span>

                                        <svg class="size-4 text-zinc-400 transition-all duration-300 group-hover:translate-x-0.5 group-hover:text-[#ff671f]"
                                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            {{-- ================================================= --}}
                            {{-- ESTADÍSTICAS                                      --}}
                            {{-- ================================================= --}}
                            <div class="hero-reveal-fade mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3 lg:mt-10"
                                style="animation-delay:650ms">
                                <div
                                    class="rounded-2xl border border-white/70 bg-white/45 p-4 text-center shadow-sm backdrop-blur-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/70 hover:shadow-md">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-zinc-500">
                                        Portafolio
                                    </p>

                                    <p class="mt-1 text-3xl font-bold tracking-tight text-[#ff671f] lg:text-4xl">
                                        +{{ $productsCount }}
                                    </p>

                                    <p class="mt-1 text-xs text-zinc-500">
                                        productos
                                    </p>
                                </div>

                                <div
                                    class="rounded-2xl border border-white/70 bg-white/45 p-4 text-center shadow-sm backdrop-blur-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/70 hover:shadow-md">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-zinc-500">
                                        Alcance
                                    </p>

                                    <p class="mt-1 text-3xl font-bold tracking-tight text-[#ff671f] lg:text-4xl">
                                        +{{ $brands->count() }}
                                    </p>

                                    <p class="mt-1 text-xs text-zinc-500">
                                        divisiones
                                    </p>
                                </div>

                                <div
                                    class="rounded-2xl border border-white/70 bg-white/45 p-4 text-center shadow-sm backdrop-blur-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/70 hover:shadow-md">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-zinc-500">
                                        Presencia
                                    </p>

                                    <p class="mt-1 text-3xl font-bold tracking-tight text-[#ff671f] lg:text-4xl">
                                        9
                                    </p>

                                    <p class="mt-1 text-xs text-zinc-500">
                                        departamentos
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- ================================================= --}}
                        {{-- ZONA DERECHA                                      --}}
                        {{-- ================================================= --}}
                        <div class="hero-reveal-right flex lg:min-h-full" style="animation-delay:500ms">
                            <div
                                class="relative flex w-full flex-col overflow-hidden rounded-[1.75rem] border border-white/70 bg-white/50 p-5 shadow-xl shadow-zinc-900/[0.05] backdrop-blur-xl sm:p-6 lg:p-7">

                                {{-- Decoración minimal --}}
                                <div
                                    class="pointer-events-none absolute -right-12 -top-12 size-40 rounded-full bg-[#ff671f]/10 blur-3xl">
                                </div>
                                <div
                                    class="pointer-events-none absolute -bottom-16 left-8 size-36 rounded-full bg-amber-300/10 blur-3xl">
                                </div>

                                {{-- Encabezado --}}
                                <div class="relative">
                                    <div class="mb-4 flex items-center justify-between gap-4">
                                        <div
                                            class="inline-flex items-center gap-2 rounded-full bg-white/60 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.18em] text-[#d95417] ring-1 ring-white/70">
                                            <span class="size-1.5 rounded-full bg-[#ff671f]"></span>
                                            Nuestra trayectoria
                                        </div>

                                        <div
                                            class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-[#ff671f]/10 text-[#ff671f] ring-1 ring-[#ff671f]/15">
                                            <svg class="size-4.5" fill="none" stroke="currentColor"
                                                stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 3v18m9-9H3" />
                                            </svg>
                                        </div>
                                    </div>

                                    <h2
                                        class="text-2xl font-bold leading-tight tracking-[-0.035em] text-zinc-950 sm:text-3xl">
                                        Hechos que nos mueven
                                    </h2>

                                    <p class="mt-3 max-w-sm text-sm leading-6 text-zinc-500">
                                        Una historia construida con calidad, cercanía y compromiso.
                                    </p>
                                </div>

                                {{-- Logros --}}
                                <div class="relative mt-6 flex-1 space-y-3">
                                    @foreach ($galardones as $g)
                                        <div style="animation-delay: {{ 650 + $loop->index * 90 }}ms"
                                            class="hero-reveal group relative flex items-center gap-3 overflow-hidden rounded-2xl border border-white/70 bg-white/55 p-3.5 shadow-sm backdrop-blur-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-[#ff671f]/20 hover:bg-white/80 hover:shadow-md">
                                            {{-- Glow hover --}}
                                            <div
                                                class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                                <div
                                                    class="absolute -left-6 -top-6 size-24 rounded-full bg-[#ff671f]/10 blur-2xl">
                                                </div>
                                            </div>

                                            {{-- Icono --}}
                                            <div
                                                class="relative flex size-11 shrink-0 items-center justify-center rounded-xl bg-[#ff671f]/10 text-[#ff671f] ring-1 ring-[#ff671f]/15 transition-all duration-300 group-hover:scale-105 group-hover:bg-[#ff671f]/15 group-hover:ring-[#ff671f]/25">
                                                <span
                                                    class="absolute -right-1 -top-1 flex size-4 items-center justify-center rounded-full bg-[#ff671f] text-[9px] font-bold text-white ring-2 ring-white">
                                                    ★
                                                </span>

                                                <span class="size-5">
                                                    {!! $g['icon'] !!}
                                                </span>
                                            </div>

                                            {{-- Contenido --}}
                                            <div class="relative min-w-0 flex-1">
                                                <span
                                                    class="block truncate text-sm font-semibold leading-tight text-zinc-850">
                                                    {{ $g['label'] }}
                                                </span>

                                                <span class="mt-0.5 block truncate text-xs leading-snug text-zinc-500">
                                                    {{ $g['sub'] }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Footer de la card --}}
                                <div class="relative mt-6 border-t border-zinc-900/10 pt-4">
                                    <p class="text-xs leading-5 text-zinc-500">
                                        Innovación, responsabilidad y confianza al servicio de la salud boliviana.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Indicador de scroll --}}
            <div class="hero-reveal-fade mt-2 flex justify-center sm:mt-2" style="animation-delay:950ms">

                <a href="#travesia"
                    class="group inline-flex flex-col items-center gap-2 rounded-full px-4 py-2 text-zinc-600 transition-colors hover:text-[#d95417] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#ff671f] focus-visible:ring-offset-2">

                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em] sm:tracking-[0.25em]">
                        Descubre más
                    </span>

                    <span
                        class="flex h-9 w-6 items-start justify-center rounded-full border border-zinc-900/20 bg-white/[0.38] backdrop-blur-sm transition-colors group-hover:border-[#ff671f]/50">

                        <span class="mt-1.5 h-2 w-1 rounded-full bg-[#ff671f] animate-bounce"></span>
                    </span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- SECTION 2: BRANDS CAROUSEL · SINGLE-SLIDE (LIGHT)               --}}
    {{-- ============================================================ --}}
    @if ($brands->isNotEmpty())
        <style>
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>

        <section class="relative overflow-hidden bg-zinc-50 py-24" x-data="{
            current: 1,
            total: {{ $brands->count() }},
            autoplay: null,
            scroller: null,
            scrollTimeout: null,
            init() {
                this.scroller = this.$refs.scroller;
                this.startAutoplay();
                this.scroller.addEventListener('scroll', () => {
                    clearTimeout(this.scrollTimeout);
                    this.scrollTimeout = setTimeout(() => {
                        this.current = Math.round(this.scroller.scrollLeft / this.scroller.offsetWidth) + 1;
                    }, 100);
                });
            },
            startAutoplay() { this.autoplay = setInterval(() => this.next(), 6000); },
            stopAutoplay() { clearInterval(this.autoplay); },
            next() {
                this.current = this.current >= this.total ? 1 : this.current + 1;
                this.scrollToCard();
            },
            prev() {
                this.current = this.current <= 1 ? this.total : this.current - 1;
                this.scrollToCard();
            },
            goTo(i) {
                this.current = i;
                this.scrollToCard();
            },
            scrollToCard() {
                const scrollAmount = (this.current - 1) * this.scroller.offsetWidth;
                this.scroller.scrollTo({ left: scrollAmount, behavior: 'smooth' });
            }
        }"
            @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()" data-aos="fade-up">

            {{-- Ambient Elements --}}
            <div class="pointer-events-none absolute inset-0 opacity-60"
                style="background-image: radial-gradient(circle, #e4e4e7 1px, transparent 1px); background-size: 22px 22px;">
            </div>
            <div class="pointer-events-none absolute -top-24 left-1/4 h-96 w-96 rounded-full bg-[#ff671f]/5 blur-3xl">
            </div>
            <div
                class="pointer-events-none absolute -bottom-24 right-1/4 h-72 w-72 rounded-full bg-orange-200/20 blur-3xl">
            </div>

            {{-- Header --}}
            <div class="text-center mb-16 max-w-2xl mx-auto px-4">
                <span
                    class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-4">
                    <span class="h-px w-8 bg-[#ff671f]/50"></span>
                    Distribución exclusiva
                    <span class="h-px w-8 bg-[#ff671f]/50"></span>
                </span>
                <h2 class="text-3xl sm:text-5xl font-bold tracking-tight text-zinc-900">
                    Nuestras <span class="text-[#ff671f]">Divisiones</span>
                </h2>
                <p class="mt-4 text-base text-zinc-500 leading-relaxed">
                    Conoce las divisiones de alta calidad que representamos y distribuimos en todo el país.
                </p>
            </div>

            {{-- Carousel Wrapper --}}
            <div class="relative px-4 sm:px-0">
                {{-- Edge Fades --}}
                <div
                    class="absolute inset-y-0 left-0 z-10 w-24 sm:w-48 bg-gradient-to-r from-zinc-50 to-transparent pointer-events-none">
                </div>
                <div
                    class="absolute inset-y-0 right-0 z-10 w-24 sm:w-48 bg-gradient-to-l from-zinc-50 to-transparent pointer-events-none">
                </div>

                {{-- Navigation --}}
                <button @click="prev()" aria-label="Anterior"
                    class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-30 flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-xl border border-zinc-200 text-zinc-600 transition-all hover:bg-[#ff671f] hover:text-white hover:scale-110 hover:shadow-lg hover:shadow-[#ff671f]/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button @click="next()" aria-label="Siguiente"
                    class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-30 flex h-12 w-12 items-center justify-center rounded-full bg-white shadow-xl border border-zinc-200 text-zinc-600 transition-all hover:bg-[#ff671f] hover:text-white hover:scale-110 hover:shadow-lg hover:shadow-[#ff671f]/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                {{-- Scroller --}}
                <div x-ref="scroller"
                    class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide pb-8">
                    @foreach ($brands as $idx => $brand)
                        <div class="snap-center shrink-0 w-full sm:w-[calc(100%-4rem)] px-4">
                            <div
                                class="relative max-w-4xl mx-auto bg-white rounded-[2rem] border border-zinc-100 shadow-lg shadow-zinc-200/50 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-[#ff671f]/5 hover:-translate-y-1">
                                {{-- Decorative Orb --}}
                                <div
                                    class="pointer-events-none absolute top-0 right-0 w-48 h-48 bg-[#ff671f]/5 rounded-full blur-3xl">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2">
                                    {{-- Image Side --}}
                                    <div
                                        class="relative bg-gradient-to-br from-zinc-50 to-zinc-100 p-8 sm:p-12 flex items-center justify-center min-h-[280px] md:min-h-[420px] overflow-hidden">
                                        <div class="absolute inset-0 opacity-50"
                                            style="background-image: radial-gradient(#e4e4e7 1px, transparent 1px); background-size: 16px 16px;">
                                        </div>

                                        @if ($brand->logo_path)
                                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                                                loading="lazy"
                                                class="relative w-full h-full object-contain drop-shadow-md transition-transform duration-500 group-hover:scale-105" />
                                        @else
                                            <div
                                                class="relative w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-gradient-to-br from-orange-50 to-[#ff671f]/10 flex items-center justify-center shadow-inner transition-all duration-500 hover:scale-105">
                                                <span
                                                    class="text-6xl sm:text-7xl font-bold text-[#ff671f]">{{ substr($brand->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Content Side --}}
                                    <div
                                        class="relative p-8 sm:p-12 md:p-16 flex flex-col justify-center border-t md:border-t-0 md:border-l border-zinc-100">
                                        <h3 class="text-3xl sm:text-4xl font-bold text-zinc-900 tracking-tight">
                                            {{ $brand->name }}
                                        </h3>

                                        @if ($brand->description)
                                            <p class="mt-4 text-base sm:text-lg text-zinc-500 leading-relaxed">
                                                {{ $brand->description }}
                                            </p>
                                        @endif

                                        <div class="mt-8">
                                            <a href="{{ route('public.brands.show', $brand) }}"
                                                class="inline-flex items-center gap-2 rounded-full bg-[#ff671f] px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/30 transition-all hover:bg-[#e55a1a] hover:-translate-y-0.5 hover:shadow-orange-500/40 active:translate-y-0">
                                                Ver productos
                                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5"
                                                    fill="none" stroke="currentColor" stroke-width="2.5"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Progress Dots --}}
            <div class="flex justify-center items-center gap-2.5 mt-8">
                @foreach ($brands as $i => $brand)
                    <button @click="goTo({{ $i + 1 }})" aria-label="Ir a {{ $brand->name }}"
                        class="relative h-2 rounded-full transition-all duration-500 ease-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[#ff671f]/60"
                        :class="current === {{ $i + 1 }} ?
                            'w-10 bg-[#ff671f] shadow-[0_0_10px_rgba(255,103,31,0.4)]' :
                            'w-2 bg-zinc-300 hover:bg-zinc-400'">
                        <span x-show="current === {{ $i + 1 }}" x-transition:enter="transition duration-700"
                            x-transition:enter-start="opacity-0 scale-75"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute inset-0 animate-ping rounded-full bg-[#ff671f]/30"></span>
                    </button>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- SECTION 3: FEATURED PRODUCTS                                 --}}
    {{-- ============================================================ --}}
    @if ($featured->isNotEmpty())
        <section class="relative overflow-hidden bg-zinc-50 py-24">
            {{-- Patrón de puntos --}}
            <div class="pointer-events-none absolute inset-0 opacity-[0.35]"
                style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 24px 24px;">
            </div>

            {{-- Degradado de desvanecido --}}
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-white via-transparent to-white">
            </div>

            {{-- Resplandores --}}
            <div class="pointer-events-none absolute -top-24 left-1/3 h-96 w-96 rounded-full bg-[#ff671f]/8 blur-3xl">
            </div>
            <div
                class="pointer-events-none absolute bottom-0 right-1/4 h-72 w-72 rounded-full bg-amber-200/25 blur-3xl">
            </div>

            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- Encabezado --}}
                <div class="mb-14 text-center" data-aos="fade-up">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-orange-50 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-[#ff671f]">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                        Destacados
                    </span>

                    <h2 class="mt-5 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl lg:text-[2.75rem]">
                        Productos
                        <span class="relative inline-block text-[#ff671f]">
                            destacados
                            <svg class="absolute -bottom-1 left-0 w-full text-[#ff671f]/30" height="8"
                                viewBox="0 0 100 8" preserveAspectRatio="none" fill="none">
                                <path d="M1 5.5C20 2 40 1.5 60 3.5C75 5 88 6 99 4" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" />
                            </svg>
                        </span>
                    </h2>

                    <p class="mx-auto mt-4 max-w-lg text-sm leading-relaxed text-zinc-500 sm:text-base">
                        Los productos más relevantes de nuestro catálogo farmacéutico.
                    </p>
                </div>

                {{-- Grid --}}
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featured as $product)
                        <a href="{{ route('public.products.show', $product) }}"
                            class="group relative flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white transition-all duration-500 hover:-translate-y-1.5 hover:border-[#ff671f]/40 hover:shadow-xl hover:shadow-orange-100/70"
                            data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">

                            {{-- Acento superior --}}
                            <span
                                class="absolute inset-x-0 top-0 z-20 h-[3px] origin-left scale-x-0 bg-gradient-to-r from-[#ff671f] to-amber-400 transition-transform duration-500 ease-out group-hover:scale-x-100"></span>

                            {{-- Imagen --}}
                            <div
                                class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-zinc-100 to-zinc-50">
                                @if ($product->main_image_path)
                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                                        loading="lazy"
                                        class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-110" />
                                @else
                                    <div class="flex h-full flex-col items-center justify-center gap-2 text-zinc-300">
                                        <svg class="h-10 w-10" fill="none" stroke="currentColor"
                                            stroke-width="1.25" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" />
                                        </svg>
                                        <span class="text-xs font-medium">Sin imagen</span>
                                    </div>
                                @endif

                                {{-- Overlay al hover --}}
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-zinc-900/40 via-transparent to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                                </div>

                                {{-- Marca --}}
                                @if ($product->brand)
                                    <span
                                        class="absolute left-3 top-3 inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-[#ff671f] shadow-sm ring-1 ring-black/5 backdrop-blur-sm">
                                        {{ $product->brand->name }}
                                    </span>
                                @endif

                                {{-- Badge destacado --}}
                                <span
                                    class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-[#ff671f] px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white shadow-lg shadow-[#ff671f]/30">
                                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                    </svg>
                                    Destacado
                                </span>
                            </div>

                            {{-- Contenido --}}
                            <div class="relative flex flex-1 flex-col p-5 sm:p-6">
                                <h3
                                    class="text-base font-bold leading-snug text-zinc-900 transition-colors duration-300 group-hover:text-[#ff671f]">
                                    {{ $product->name }}
                                </h3>

                                @if ($product->active_ingredient)
                                    <p class="mt-1.5 text-xs font-medium uppercase tracking-wide text-zinc-400">
                                        {{ $product->active_ingredient }}
                                    </p>
                                @endif

                                <div class="mt-auto flex items-center justify-between pt-5">
                                    {{-- @if ($product->approx_price)
                                    <span class="text-sm font-bold text-zinc-800">{{ $product->formatted_price }}</span>
                                @endif --}}

                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#ff671f] transition-all duration-300 group-hover:gap-2.5">
                                        Ver ficha
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- CTA catálogo --}}
                <div class="mt-14 text-center" data-aos="fade-up">
                    <a href="{{ route('public.products.index') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-7 py-3.5 text-sm font-semibold text-zinc-700 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-[#ff671f]/50 hover:text-[#ff671f] hover:shadow-md">
                        Ver catálogo completo
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- SECTION 4: CATEGORIES                                        --}}
    {{-- ============================================================ --}}
    @if ($categories->isNotEmpty())
        <section class="relative overflow-hidden bg-white py-24">
            {{-- Fondo decorativo --}}
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-32 right-0 h-80 w-80 rounded-full bg-[#ff671f]/5 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-amber-100/40 blur-3xl"></div>
            </div>

            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- Encabezado --}}
                <div class="mb-14 text-center" data-aos="fade-up">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-orange-50 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-[#ff671f]">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        Especialidades
                    </span>

                    <h2 class="mt-5 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl lg:text-[2.75rem]">
                        Explora por
                        <span class="relative inline-block text-[#ff671f]">
                            especialidad
                            <svg class="absolute -bottom-1 left-0 w-full text-[#ff671f]/30" height="8"
                                viewBox="0 0 100 8" preserveAspectRatio="none" fill="none">
                                <path d="M1 5.5C20 2 40 1.5 60 3.5C75 5 88 6 99 4" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" />
                            </svg>
                        </span>
                    </h2>

                    <p class="mx-auto mt-4 max-w-lg text-sm leading-relaxed text-zinc-500 sm:text-base">
                        Encuentra los productos que necesitas de forma rápida y sencilla.
                    </p>
                </div>

                {{-- Grid --}}
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach ($categories as $category)
                        <a href="{{ route('public.products.index') }}?category={{ $category->slug }}"
                            class="group relative flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 transition-all duration-300 hover:-translate-y-1.5 hover:border-[#ff671f]/40 hover:shadow-xl hover:shadow-orange-100/70"
                            data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">

                            {{-- Acento superior --}}
                            <span
                                class="absolute inset-x-0 top-0 h-[3px] origin-left scale-x-0 bg-gradient-to-r from-[#ff671f] to-amber-400 transition-transform duration-500 ease-out group-hover:scale-x-100"></span>

                            {{-- Halo --}}
                            <div
                                class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-orange-50 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100">
                            </div>

                            {{-- Número de índice --}}
                            <span
                                class="absolute right-4 top-4 text-[11px] font-bold tabular-nums text-zinc-200 transition-colors duration-300 group-hover:text-orange-200">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <div class="relative flex flex-1 flex-col items-center text-center">
                                {{-- Icono --}}
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-50 to-orange-100 text-[#ff671f] ring-1 ring-orange-100 transition-all duration-300 group-hover:scale-110 group-hover:from-[#ff671f] group-hover:to-orange-400 group-hover:text-white group-hover:ring-[#ff671f]/20 group-hover:shadow-lg group-hover:shadow-orange-200">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.75"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 6h.008v.008H6V6Z" />
                                    </svg>
                                </div>

                                {{-- Nombre --}}
                                <h3
                                    class="mt-5 text-[15px] font-bold leading-snug text-zinc-900 transition-colors duration-300 group-hover:text-[#ff671f]">
                                    {{ $category->name }}
                                </h3>

                                {{-- Contador (opcional, se muestra solo si existe) --}}
                                @if (isset($category->products_count))
                                    <p class="mt-1.5 text-xs font-medium text-zinc-400">
                                        {{ $category->products_count }}
                                        {{ $category->products_count === 1 ? 'producto' : 'productos' }}
                                    </p>
                                @endif

                                {{-- CTA implícito --}}
                                <span
                                    class="mt-4 inline-flex items-center gap-1 text-[11px] font-semibold uppercase tracking-wider text-zinc-400 transition-all duration-300 group-hover:gap-2 group-hover:text-[#ff671f]">
                                    Ver productos
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Enlace a catálogo completo --}}
                <div class="mt-12 text-center" data-aos="fade-up">
                    <a href="{{ route('public.products.index') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-6 py-3 text-sm font-semibold text-zinc-700 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-[#ff671f]/50 hover:text-[#ff671f] hover:shadow-md">
                        Ver todo el catálogo
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- SECTION 5: BRANCHES / OFFICES                                --}}
    {{-- ============================================================ --}}
    @if ($branches->isNotEmpty())
        <section class="relative overflow-hidden bg-zinc-50 py-24">
            {{-- Patrón de puntos sutil --}}
            <div class="pointer-events-none absolute inset-0 opacity-[0.4]"
                style="background-image: radial-gradient(circle, #d4d4d8 1px, transparent 1px); background-size: 24px 24px;">
            </div>

            {{-- Degradado de desvanecido en bordes --}}
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-white via-transparent to-white">
            </div>

            {{-- Resplandores ambientales --}}
            <div class="pointer-events-none absolute top-0 left-1/4 h-96 w-96 rounded-full bg-[#ff671f]/10 blur-3xl">
            </div>
            <div
                class="pointer-events-none absolute bottom-0 right-1/4 h-72 w-72 rounded-full bg-amber-200/30 blur-3xl">
            </div>

            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- Encabezado --}}
                <div class="mb-14 text-center" data-aos="fade-up">
                    <span
                        class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-orange-50 px-4 py-1.5 text-[11px] font-bold uppercase tracking-[0.2em] text-[#ff671f]">
                        <span class="relative flex h-1.5 w-1.5">
                            <span
                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#ff671f] opacity-75"></span>
                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-[#ff671f]"></span>
                        </span>
                        Presencia nacional
                    </span>

                    <h2 class="mt-5 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl lg:text-[2.75rem]">
                        Nuestras <span class="relative inline-block text-[#ff671f]">
                            oficinas
                            <svg class="absolute -bottom-1 left-0 w-full text-[#ff671f]/30" height="8"
                                viewBox="0 0 100 8" preserveAspectRatio="none" fill="none">
                                <path d="M1 5.5C20 2 40 1.5 60 3.5C75 5 88 6 99 4" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" />
                            </svg>
                        </span>
                    </h2>

                    <p class="mx-auto mt-4 max-w-lg text-sm leading-relaxed text-zinc-500 sm:text-base">
                        Estamos ubicados en las principales ciudades del país para servirte mejor.
                    </p>
                </div>

                {{-- Tarjetas --}}
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($branches as $branch)
                        <div class="group relative overflow-hidden rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:border-[#ff671f]/40 hover:shadow-xl hover:shadow-orange-100/60"
                            data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">

                            {{-- Acento superior animado --}}
                            <span
                                class="absolute inset-x-0 top-0 h-[3px] scale-x-0 bg-gradient-to-r from-[#ff671f] to-amber-400 transition-transform duration-500 ease-out group-hover:scale-x-100"></span>

                            {{-- Halo decorativo --}}
                            <div
                                class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-orange-50 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100">
                            </div>

                            <div class="relative">
                                {{-- Icono --}}
                                <div
                                    class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 text-[#ff671f] ring-1 ring-orange-100 transition-all duration-300 group-hover:from-[#ff671f] group-hover:to-orange-400 group-hover:text-white group-hover:ring-[#ff671f]/30 group-hover:shadow-lg group-hover:shadow-orange-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.75"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                </div>

                                {{-- Nombre y ubicación --}}
                                <h3
                                    class="text-base font-bold text-zinc-900 transition-colors duration-300 group-hover:text-[#ff671f]">
                                    {{ $branch->name }}
                                </h3>

                                @if ($branch->city)
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-zinc-400">
                                        {{ $branch->city }}
                                    </p>
                                @endif

                                @if ($branch->address)
                                    <p class="mt-2.5 text-sm leading-relaxed text-zinc-500">
                                        {{ $branch->address }}
                                    </p>
                                @endif

                                {{-- Datos de contacto --}}
                                @if ($branch->phone || $branch->email)
                                    <div class="mt-5 space-y-2 border-t border-dashed border-zinc-200 pt-5">
                                        @if ($branch->phone)
                                            <a href="tel:{{ $branch->phone }}"
                                                class="flex items-center gap-2.5 rounded-lg px-2 py-1.5 -mx-2 text-sm text-zinc-600 transition-all duration-200 hover:bg-orange-50 hover:text-[#ff671f]">
                                                <span
                                                    class="flex h-7 w-7 flex-none items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                                    </svg>
                                                </span>
                                                <span class="truncate font-medium">{{ $branch->phone }}</span>
                                            </a>
                                        @endif

                                        @if ($branch->email)
                                            <a href="mailto:{{ $branch->email }}"
                                                class="flex items-center gap-2.5 rounded-lg px-2 py-1.5 -mx-2 text-sm text-zinc-600 transition-all duration-200 hover:bg-orange-50 hover:text-[#ff671f]">
                                                <span
                                                    class="flex h-7 w-7 flex-none items-center justify-center rounded-lg bg-sky-50 text-sky-600 ring-1 ring-sky-100">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                                    </svg>
                                                </span>
                                                <span class="truncate font-medium">{{ $branch->email }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-public.hero-slides-modal :slides="$slides" />

</x-layouts::public>
