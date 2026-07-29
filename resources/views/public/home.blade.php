<x-layouts::public>
    @php
        $slides = \App\Models\HeroSlide::query()->active()->ordered()->get();
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
    <section class="relative min-h-[90vh] flex items-center overflow-hidden">

        {{-- ===== BACKGROUND SLIDESHOW WITH CROSSFADE ===== --}}
        <div class="absolute inset-0" x-data="{
            slides: [
                '{{ Storage::disk('public')->url('fondo-a.jpeg') }}',
                '{{ Storage::disk('public')->url('fondo-b.jpeg') }}',
                '{{ Storage::disk('public')->url('fondo-c.jpeg') }}',
                '{{ Storage::disk('public')->url('fondo-d.jpeg') }}',
                '{{ Storage::disk('public')->url('fondo-e.jpeg') }}',
                '{{ Storage::disk('public')->url('fondo-f.jpeg') }}',
            ],
            current: 0
        }" x-init="setInterval(() => { current = (current + 1) % slides.length }, 7500)">
            {{-- Image layers — all in DOM, only one visible via opacity --}}
            <template x-for="(src, i) in slides" :key="i">
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-all duration-1500 ease-out"
                    :style="`background-image: url('${src}'); opacity: ${current === i ? 1 : 0};`">
                </div>
            </template>

            {{-- Gradient overlays (static, on top of all images) --}}
            <div class="absolute inset-0 pointer-events-none"
                style="background:
                     linear-gradient(to bottom, rgba(50,50,50,0.30), rgba(50,50,50,0.30), rgba(50,50,50,0.30)),
                     linear-gradient(to right, rgba(50,50,50,0.25), transparent, rgba(50,50,50,0.25)),
                     radial-gradient(circle at 50% 50%, rgba(255,103,31,0.08) 0%, transparent 50%),
                     radial-gradient(circle, rgba(255,103,31,0.06) 1.5px, transparent 1.5px);
                 background-size: cover, cover, cover, 28px 28px;">
            </div>

            {{-- Progress bar — resets on each slide change --}}
            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-white/5">
                <div class="h-full bg-gradient-to-r from-[#ff671f]/60 to-[#ff671f]/30" x-init="function animate() {
                    $el.style.transition = 'none';
                    $el.style.width = '0%';
                    void $el.offsetHeight; // force reflow
                    $el.style.transition = 'width 6000ms linear';
                    requestAnimationFrame(() => $el.style.width = '100%');
                }
                animate();
                $watch('current', animate);">
                </div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- MAIN CONTENT CONTAINER   --}}
        {{-- ======================== --}}
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-20">

            {{-- Glass panel container --}}
            <div
                class="rounded-2xl bg-black/30 backdrop-blur-lg border border-white/[0.08] p-6 sm:p-8 lg:p-12 shadow-2xl shadow-black/40">

                <div class="flex flex-col lg:flex-row lg:items-center gap-8 lg:gap-16">

                    {{-- === LEFT ZONE: Main content === --}}
                    <div class="flex-1 flex flex-col justify-center">

                        {{-- Slides --}}
                        @if ($slides->isNotEmpty())
                            <div x-data="{ current: 0, total: {{ $slides->count() }} }" x-init="setInterval(() => { current = (current + 1) % total }, 6000)" class="mb-8">
                                <template
                                    x-for="(slide, i) in {{ json_encode($slides->map(fn($s) => ['title' => $s->title, 'subtitle' => $s->subtitle, 'cta_label' => $s->cta_label, 'cta_url' => $s->cta_url])) }}"
                                    :key="i">
                                    <div x-show="current === i" x-transition:enter="transition ease-out duration-700"
                                        x-transition:enter-start="opacity-0 translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-500"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-4">
                                        <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-tight tracking-tight drop-shadow-lg"
                                            x-text="slide.title"></h1>
                                        <p class="mt-4 text-base sm:text-lg lg:text-xl text-zinc-200/80 leading-relaxed"
                                            x-text="slide.subtitle"></p>
                                        <a x-show="slide.cta_label" :href="slide.cta_url"
                                            class="mt-5 inline-flex items-center gap-2 rounded-full bg-[#ff671f] px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-orange-500/30 transition-all duration-300 hover:bg-[#e55a1a] hover:shadow-orange-500/50 hover:-translate-y-0.5 hover:scale-105">
                                            <span x-text="slide.cta_label"></span>
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </template>
                                <div class="flex gap-2 mt-6">
                                    <template x-for="(_, i) in total" :key="i">
                                        <button @click="current = i"
                                            class="h-1.5 rounded-full transition-all duration-500 ease-out"
                                            :class="current === i ? 'w-8 bg-[#ff671f] shadow-[0_0_10px_rgba(255,103,31,0.5)]' :
                                                'w-1.5 bg-white/30 hover:bg-white/50'"></button>
                                    </template>
                                </div>
                            </div>
                        @endif

                        {{-- Logo stamp --}}
                        <div data-aos="fade-up" data-aos-delay="100" class="mb-10">
                            <img src="{{ Storage::disk('public')->url('logo_delta.png') }}"
                                alt="Laboratorios Delta S.A."
                                class="h-16 sm:h-24 lg:h-28 object-contain drop-shadow-lg" />
                        </div>

                        {{-- Tagline with editorial accent --}}
                        <div data-aos="fade-up" data-aos-delay="200" class="mb-10">
                            <div class="flex items-center gap-4 mb-5">
                                <span class="h-px w-12 bg-[#ff671f]/60"></span>
                                <span
                                    class="text-[14px] font-semibold uppercase tracking-[0.25em] text-[#ff671f] drop-shadow-sm">
                                    Lider en la industria farmacéutica Boliviana
                                </span>
                            </div>
                            <p
                                class="text-md sm:text-md lg:text-md text-white/95 leading-relaxed max-w-2xl font-light drop-shadow-sm">
                                Comprometidos con la salud y el bienestar de los bolivianos,
                                ofreciendo productos farmacéuticos de la más alta calidad.
                            </p>
                        </div>

                        {{-- CTAs --}}
                        <div class="flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="300">
                            <a href="{{ route('public.products.index') }}"
                                class="group inline-flex items-center gap-2.5 rounded-full bg-[#ff671f] px-8 py-4 text-sm font-semibold text-white shadow-lg shadow-orange-500/25 transition-all duration-300 hover:bg-[#e55a1a] hover:shadow-orange-500/50 hover:-translate-y-1 hover:scale-105">
                                Explorar productos
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                            <a href="{{ route('public.about') }}"
                                class="group inline-flex items-center gap-2.5 rounded-full border border-white/25 bg-white/[0.12] backdrop-blur-md px-8 py-4 text-sm font-semibold text-white transition-all duration-300 hover:bg-white/[0.20] hover:border-white/50 hover:-translate-y-1 hover:scale-105">
                                Nuestra historia
                                <svg class="h-4 w-4 opacity-60 transition-transform duration-300 group-hover:translate-x-1"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- === RIGHT ZONE: Achievement timeline === --}}
                    <div class="flex-shrink-0 flex flex-col justify-center" data-aos="zoom-in" data-aos-delay="400">
                        <div class="relative pl-8 border-l-2 border-white/10">
                            @php
                                $galardones = [
                                    [
                                        'icon' =>
                                            '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
                                        'label' => '30+ años',
                                        'sub' => 'de experiencia',
                                    ],
                                    [
                                        'icon' =>
                                            '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>',
                                        'label' => 'Orgullo',
                                        'sub' => 'boliviano',
                                    ],
                                    [
                                        'icon' =>
                                            '<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>',
                                        'label' => 'Eje',
                                        'sub' => 'troncal',
                                    ],
                                ];
                            @endphp
                            @foreach ($galardones as $g)
                                <div class="relative pb-10 last:pb-0 group" data-aos="fade-left"
                                    data-aos-delay="{{ 500 + $loop->index * 100 }}">
                                    {{-- Timeline connector dot --}}
                                    <div
                                        class="absolute -left-[5px] top-6 w-2.5 h-2.5 rounded-full bg-[#ff671f] border-2 border-white/20 shadow-[0_0_12px_rgba(255,103,31,0.4)] transition-all duration-500 group-hover:shadow-[0_0_20px_rgba(255,103,31,0.6)] group-hover:scale-125">
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#ff671f]/40 to-[#ff671f]/15 border border-[#ff671f]/30 text-[#ff671f] flex-shrink-0 transition-all duration-500 group-hover:scale-110 group-hover:shadow-[0_0_24px_rgba(255,103,31,0.35)]"
                                            style="animation: pulse-glow 3s ease-in-out infinite; animation-delay: {{ $loop->index * 0.5 }}s">
                                            {!! $g['icon'] !!}
                                        </div>
                                        <div class="pt-1">
                                            <span
                                                class="block text-sm font-bold text-white leading-tight drop-shadow-sm">{{ $g['label'] }}</span>
                                            <span
                                                class="block text-xs text-white/80 mt-0.5 drop-shadow-sm">{{ $g['sub'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- === STATS BAR: Integrated floating bar === --}}
            <div class="mt-10 lg:mt-12 grid grid-cols-3 divide-x divide-white/10 rounded-2xl bg-black/50 backdrop-blur-lg border border-white/[0.08] overflow-hidden"
                data-aos="fade-up" data-aos-delay="500">
                <div class="py-5 text-center group">
                    <p class="text-xs sm:text-sm text-white/80 mt-1 font-medium tracking-wide drop-shadow-sm">Cobertura
                        en</p>
                    <p
                        class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#ff671f] transition-all duration-300 group-hover:scale-110">
                        + {{ $productsCount }}</p>
                    <p class="text-xs sm:text-sm text-white/80 mt-1 font-medium tracking-wide drop-shadow-sm">Productos
                    </p>
                </div>
                <div class="py-5 text-center group">
                    <p class="text-xs sm:text-sm text-white/80 mt-1 font-medium tracking-wide drop-shadow-sm">Cobertura
                        en</p>
                    <p
                        class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#ff671f] transition-all duration-300 group-hover:scale-110">
                        + {{ $brands->count() }}</p>
                    <p class="text-xs sm:text-sm text-white/80 mt-1 font-medium tracking-wide drop-shadow-sm">Marcas
                    </p>
                </div>
                <div class="py-5 text-center group">
                    <p class="text-xs sm:text-sm text-white/80 mt-1 font-medium tracking-wide drop-shadow-sm">Cobertura
                        en los</p>
                    <p
                        class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#ff671f] transition-all duration-300 group-hover:scale-110">
                        9</p>
                    <p class="text-xs sm:text-sm text-white/80 mt-1 font-medium tracking-wide drop-shadow-sm">
                        Departamentos</p>
                </div>
            </div>
            {{-- Scroll indicator --}}
            <div class="flex mt-6 justify-center" data-aos="fade-up" data-aos-delay="300">
                <a href="#travesia"
                    class="flex flex-col items-center gap-2 text-white hover:text-white transition-colors group">
                    <span class="text-[10px] font-medium uppercase tracking-[0.25em]">Descubre más</span>
                    <span
                        class="flex h-9 w-6 items-start justify-center rounded-full border border-white group-hover:border-zinc-300 transition-colors">
                        <span class="mt-1.5 h-2 w-1 rounded-full bg-white group-hover:bg-white animate-bounce"></span>
                    </span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- SECTION 2: BRANDS CAROUSEL · 3-SLOT PEEK                     --}}
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

        <section class="relative overflow-hidden bg-gradient-to-b from-zinc-50 to-white py-24" x-data="{
            current: 1,
            total: {{ $brands->count() }},
            autoplay: null,
            scroller: null,
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
            startAutoplay() {
                this.autoplay = setInterval(() => this.next(), 5000);
            },
            stopAutoplay() {
                clearInterval(this.autoplay);
            },
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

            <div class="text-center mb-16 max-w-2xl mx-auto px-4">
                <span
                    class="inline-block text-[11px] font-semibold uppercase tracking-[0.25em] text-[#ff671f] mb-4 px-3 py-1 rounded-full bg-[#ff671f]/10">
                    Distribución exclusiva
                </span>
                <h2 class="text-3xl sm:text-5xl font-bold tracking-tight text-zinc-900">
                    Nuestras <span class="text-[#ff671f]">Marcas</span>
                </h2>
                <p class="mt-4 text-base text-zinc-500 leading-relaxed">
                    Conoce las marcas de alta calidad que representamos y distribuimos en todo Bolivia.
                </p>
            </div>

            {{-- Carousel Container --}}
            <div class="relative">
                {{-- Edge Fades --}}
                <div
                    class="absolute inset-y-0 left-0 z-10 w-1/4 sm:w-32 bg-gradient-to-r from-zinc-50 to-transparent pointer-events-none">
                </div>
                <div
                    class="absolute inset-y-0 right-0 z-10 w-1/4 sm:w-32 bg-gradient-to-l from-zinc-50 to-transparent pointer-events-none">
                </div>

                {{-- Navigation Buttons --}}
                <button @click="prev()"
                    class="absolute left-2 sm:left-6 top-1/2 -translate-y-1/2 z-30 flex h-11 w-11 items-center justify-center rounded-full bg-white shadow-xl border border-zinc-100 text-zinc-700 transition-all hover:bg-[#ff671f] hover:text-white hover:scale-110">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button @click="next()"
                    class="absolute right-2 sm:right-6 top-1/2 -translate-y-1/2 z-30 flex h-11 w-11 items-center justify-center rounded-full bg-white shadow-xl border border-zinc-100 text-zinc-700 transition-all hover:bg-[#ff671f] hover:text-white hover:scale-110">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                {{-- Scroller --}}
                <div x-ref="scroller"
                    class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth scrollbar-hide pb-12 pt-4">

                    @foreach ($brands as $idx => $brand)
                        <div class="snap-center shrink-0 w-full sm:w-[calc(100%-8rem)] sm:mx-auto px-4 sm:px-8">
                            {{-- Card Container: Split Layout --}}
                            <div
                                class="relative max-w-4xl mx-auto bg-white rounded-[2rem] border border-zinc-100 shadow-2xl shadow-zinc-200/60 overflow-hidden">

                                {{-- Grid Layout: 1 col on mobile, 2 cols on desktop --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 items-stretch">

                                    {{-- Left Side: Full Image Area --}}
                                    <div
                                        class="relative bg-zinc-50 flex items-center justify-center p-8 sm:p-12 min-h-[280px] md:min-h-[420px]">
                                        {{-- Decorative Pattern --}}
                                        <div class="absolute inset-0 opacity-50"
                                            style="background-image: radial-gradient(#e4e4e7 1px, transparent 1px); background-size: 16px 16px;">
                                        </div>

                                        <div class="relative w-full h-full flex items-center justify-center">
                                            @if ($brand->logo_path)
                                                {{-- Full Image --}}
                                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                                                    class="w-full h-full object-contain drop-shadow-md" />
                                            @else
                                                {{-- Fallback --}}
                                                <div
                                                    class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-gradient-to-br from-[#ff671f]/20 to-[#ff671f]/5 flex items-center justify-center text-6xl sm:text-7xl font-bold text-[#ff671f] shadow-inner">
                                                    {{ substr($brand->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Right Side: Content Centered --}}
                                    <div
                                        class="relative p-8 sm:p-12 md:p-16 flex flex-col justify-center text-left border-t md:border-t-0 md:border-l border-zinc-100">

                                        <h3 class="text-3xl sm:text-4xl font-bold text-zinc-900 tracking-tight">
                                            {{ $brand->name }}</h3>

                                        @if ($brand->description)
                                            <p class="mt-4 text-base sm:text-lg text-zinc-500 leading-relaxed">
                                                {{ $brand->description }}
                                            </p>
                                        @endif

                                        <div class="mt-8">
                                            <a href="{{ route('public.brands.show', $brand) }}"
                                                class="inline-flex items-center gap-2 rounded-full bg-[#ff671f] px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/30 transition-all hover:bg-[#e55a1a] hover:-translate-y-1 hover:shadow-orange-500/40">
                                                Ver productos
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                {{-- Decorative Blurred Orb for color pop --}}
                                <div
                                    class="absolute top-0 right-0 w-40 h-40 bg-[#ff671f]/5 rounded-full blur-3xl pointer-events-none">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Progress Bar Indicator --}}
            <div class="flex justify-center items-center gap-3 mt-8">
                @foreach ($brands as $i => $brand)
                    <button @click="goTo({{ $i + 1 }})"
                        class="h-2 rounded-full transition-all duration-500 ease-out"
                        :class="current === {{ $i + 1 }} ? 'w-10 bg-[#ff671f]' : 'w-2 bg-zinc-200 hover:bg-zinc-300'">
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
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
                style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 24px 24px;">
            </div>
            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up">
                    <span
                        class="inline-block text-[11px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-3">Destacados</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900">Productos <span
                            class="text-[#ff671f]">destacados</span></h2>
                    <p class="mt-3 text-sm text-zinc-500 max-w-lg mx-auto">Los productos más relevantes de nuestro
                        catálogo farmacéutico.</p>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featured as $product)
                        <a href="{{ route('public.products.show', $product) }}"
                            class="group relative bg-white rounded-2xl border border-zinc-100 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-[#ff671f]/5 hover:-translate-y-1"
                            data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <div class="relative aspect-[4/3] overflow-hidden bg-zinc-100">
                                @if ($product->main_image_path)
                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                                @else
                                    <div class="flex h-full items-center justify-center text-zinc-300 text-sm">Sin
                                        imagen</div>
                                @endif
                                <div
                                    class="absolute top-0 right-0 h-16 w-16 bg-gradient-to-bl from-[#ff671f]/20 to-transparent">
                                </div>
                                @if ($product->brand)
                                    <span
                                        class="absolute top-3 left-3 inline-flex items-center rounded-full bg-white/90 backdrop-blur-sm px-3 py-1 text-[10px] font-semibold text-[#ff671f] shadow-sm">{{ $product->brand->name }}</span>
                                @endif
                            </div>
                            <div class="p-5 sm:p-6">
                                <h3 class="font-semibold text-zinc-900 group-hover:text-[#ff671f] transition-colors">
                                    {{ $product->name }}</h3>
                                @if ($product->active_ingredient)
                                    <p class="mt-1 text-xs text-zinc-400">{{ $product->active_ingredient }}</p>
                                @endif
                                <div class="mt-4 flex items-center justify-between">
                                    {{-- @if ($product->approx_price)
                                        <span
                                            class="text-sm font-bold text-zinc-800">{{ $product->formatted_price }}</span>
                                    @endif --}}
                                    <span
                                        class="text-xs font-medium text-[#ff671f] opacity-0 translate-x-2 transition-all duration-300 group-hover:opacity-100 group-hover:translate-x-0">
                                        Ver más →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="text-center mt-12" data-aos="fade-up">
                    <a href="{{ route('public.products.index') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-zinc-300 bg-white px-7 py-3 text-sm font-medium text-zinc-700 transition-all hover:border-[#ff671f]/30 hover:text-[#ff671f] hover:shadow-lg hover:shadow-[#ff671f]/5">
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
        <section class="py-24 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up">
                    <span
                        class="inline-block text-[11px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-3">Categorías</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-zinc-900">Explora por <span
                            class="text-[#ff671f]">categoría</span></h2>
                    <p class="mt-3 text-sm text-zinc-500 max-w-lg mx-auto">Encuentra los productos que necesitas de
                        forma rápida.</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach ($categories as $category)
                        <a href="{{ route('public.products.index') }}?category={{ $category->slug }}"
                            class="group relative overflow-hidden rounded-2xl border border-zinc-100 bg-white p-6 sm:p-8 transition-all duration-300 hover:border-[#ff671f]/20 hover:shadow-lg hover:shadow-[#ff671f]/5 hover:-translate-y-1"
                            data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            <div
                                class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#ff671f]/0 via-[#ff671f]/40 to-[#ff671f]/0 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-[#ff671f]/10 to-[#ff671f]/5 text-[#ff671f] transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-[#ff671f]/10">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 6h.008v.008H6V6Z" />
                                    </svg>
                                </div>
                                <h3
                                    class="mt-4 font-semibold text-zinc-900 group-hover:text-[#ff671f] transition-colors">
                                    {{ $category->name }}</h3>
                                {{-- <p class="mt-1 text-xs text-zinc-400">{{ $category->products_count ?? 0 }} productos
                                </p> --}}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- SECTION 5: BRANCHES / OFFICES                                --}}
    {{-- ============================================================ --}}
    @if ($branches->isNotEmpty())
        <section class="relative overflow-hidden bg-zinc-900 py-24">
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
                style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 24px 24px;">
            </div>
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-[#ff671f]/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-aos="fade-up">
                    <span
                        class="inline-block text-[11px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-3">Presencia
                        nacional</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white">Nuestras <span
                            class="text-[#ff671f]">oficinas</span></h2>
                    <p class="mt-3 text-sm text-zinc-400 max-w-lg mx-auto">Estamos ubicados en las principales ciudades
                        del país para servirte mejor.</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($branches as $branch)
                        <div class="group relative rounded-2xl border border-zinc-700/50 bg-zinc-800/30 p-6 transition-all duration-300 hover:bg-zinc-800/60 hover:border-[#ff671f]/20 hover:-translate-y-1"
                            data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#ff671f]/10 text-[#ff671f] mb-4">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-white group-hover:text-[#ff671f] transition-colors">
                                {{ $branch->name }}</h3>
                            <p class="mt-1 text-sm text-zinc-400">{{ $branch->city }}</p>
                            <p class="mt-0.5 text-xs text-zinc-500">{{ $branch->address }}</p>
                            <div class="mt-4 pt-4 border-t border-zinc-700/30 space-y-1">
                                @if ($branch->phone)
                                    <a href="tel:{{ $branch->phone }}"
                                        class="flex items-center gap-2 text-sm text-zinc-400 hover:text-[#ff671f] transition-colors">
                                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor"
                                            stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                        </svg>
                                        {{ $branch->phone }}
                                    </a>
                                @endif
                                <hr class="border-zinc-600 my-4" />
                                @if ($branch->email)
                                    <a href="mailto:{{ $branch->email }}"
                                        class="flex items-center gap-2 text-sm text-zinc-400 hover:text-[#ff671f] transition-colors">
                                        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor"
                                            stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                        </svg>
                                        {{ $branch->email }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-layouts::public>
