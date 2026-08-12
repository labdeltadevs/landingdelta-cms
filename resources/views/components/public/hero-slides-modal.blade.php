@props([
    'slides' => collect(),
])

@if ($slides instanceof \Illuminate\Support\Collection ? $slides->isNotEmpty() : !empty($slides))
    @php
        $modalSlides = collect($slides)
            ->map(
                fn($slide) => [
                    'title' => $slide->title,
                    'subtitle' => $slide->subtitle,
                    'image_url' => $slide->image_url,
                    'cta_label' => $slide->cta_label,
                    'cta_url' => $slide->cta_url,
                ],
            )
            ->values();

        $jsonFlags =
            JSON_HEX_TAG |
            JSON_HEX_APOS |
            JSON_HEX_AMP |
            JSON_HEX_QUOT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES;
    @endphp

    <script>
        function heroSlidesModal({
            slides,
            startOpen
        }) {
            return {
                open: false,
                current: 0,
                slides,
                timer: null,

                init() {
                    if (startOpen && !sessionStorage.getItem('hero-slides-modal-dismissed')) {
                        this.open = true;
                        this.start();
                    }
                },

                start() {
                    if (this.slides.length > 1) {
                        this.timer = setInterval(() => {
                            this.current = (this.current + 1) % this.slides.length;
                        }, 5000);
                    }
                },

                restart() {
                    if (this.timer) {
                        clearInterval(this.timer);
                    }

                    this.start();
                },

                close() {
                    this.open = false;

                    if (this.timer) {
                        clearInterval(this.timer);
                    }

                    sessionStorage.setItem('hero-slides-modal-dismissed', '1');
                },
            };
        }
    </script>

    <div data-hero-slides-modal x-cloak x-data="heroSlidesModal({
        slides: {{ $modalSlides->toJson($jsonFlags) }},
        startOpen: true,
    })" x-show="open" @keydown.escape.window="close()"
        x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-8" role="dialog" aria-modal="true"
        aria-labelledby="hero-slides-modal-title">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-600/20 backdrop-blur-md saturate-150 transition-opacity duration-300"
            @click="close()"></div>

        {{-- Modal card --}}
        <div
            class="relative w-full max-w-7xl overflow-hidden rounded-[2rem] bg-white shadow-[0_25px_80px_-12px_rgba(0,0,0,0.5)] ring-1 ring-black/5">

            {{-- Close button --}}
            <button type="button" @click="close()" aria-label="Cerrar aviso"
                class="absolute right-4 top-4 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-amber-500 backdrop-blur-md ring-1 ring-amber-500 transition-all duration-300 hover:scale-110 hover:bg-white/25 hover:ring-white/40 hover:shadow-lg active:scale-95">
                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Slides container --}}
            <div class="relative h-[75vh] sm:h-[65vh] lg:h-[70vh]">

                <template x-for="(slide, i) in slides" :key="i">
                    <div x-show="current === i" x-cloak
                        x-transition:enter="transition duration-[800ms] ease-[cubic-bezier(0.16,1,0.3,1)]"
                        x-transition:enter-start="opacity-0 scale-[1.03]" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition duration-[600ms] ease-[cubic-bezier(0.7,0,0.84,0)]"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-[0.97]"
                        class="absolute inset-0 will-change-transform">

                        {{-- Background image --}}
                        <img x-show="slide.image_url" :src="slide.image_url" :alt="slide.title"
                            class="absolute inset-0 h-full w-full object-contain" />

                        {{-- Gradient fallback --}}
                        <div x-show="!slide.image_url"
                            class="absolute inset-0 bg-gradient-to-b from-[#ff671f] via-[#ff8a4c] to-[#ffb37e]"></div>
                        </div>

                        {{-- Ambient glow effect --}}
                        <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-[#ff671f]/20 blur-3xl"></div>
                        <div class="absolute -top-20 -right-20 h-48 w-48 rounded-full bg-[#ff8a4c]/15 blur-3xl"></div>

                        {{-- Multi-layer gradient overlay --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent">
                        </div>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-slate-950/40 via-transparent to-slate-950/20">
                        </div>
                        <div class="absolute bottom-0 h-1/2 w-full bg-gradient-to-t from-slate-950/60 to-transparent">
                        </div>

                        {{-- Content --}}
                        <div class="absolute inset-x-0 bottom-0 z-10 p-6 sm:p-8 lg:p-10">

                            {{-- Decorative accent line --}}
                            <div class="mb-4 flex items-center gap-3">
                                <div
                                    class="h-[3px] w-10 rounded-full bg-[#ff671f] shadow-[0_0_12px_rgba(255,103,31,0.6)]">
                                </div>
                                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-[#ff8a4c]"
                                    x-text="slide.tag || 'Aviso'"></span>
                            </div>

                            {{-- Title --}}
                            <h2 id="hero-slides-modal-title" x-text="slide.title"
                                class="text-2xl font-extrabold leading-[1.15] tracking-tight text-white sm:text-3xl lg:text-4xl drop-shadow-lg">
                            </h2>

                            {{-- Subtitle --}}
                            <p x-show="slide.subtitle" x-text="slide.subtitle"
                                x-transition:enter="transition duration-500 delay-150"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mt-3 max-w-lg text-sm leading-relaxed text-white/80 sm:text-base lg:text-lg">
                            </p>

                            {{-- CTA Button --}}
                            <a x-show="slide.cta_label" :href="slide.cta_url || '#'" x-text="slide.cta_label"
                                x-transition:enter="transition duration-500 delay-300"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mt-5 inline-flex items-center gap-2.5 rounded-full bg-[#ff671f] px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#ff671f]/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#e55a1a] hover:shadow-xl hover:shadow-[#ff671f]/40 active:translate-y-0 active:shadow-md">
                                <span x-text="slide.cta_label"></span>
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5"
                                    fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </template>

                {{-- Progress bar --}}
                <template x-if="slides.length > 1">
                    <div class="absolute top-0 left-0 right-0 z-10 h-[3px] bg-white/10">
                        <div class="h-full rounded-r-full bg-[#ff671f] transition-all duration-300 ease-out"
                            :style="'width:' + ((current + 1) / slides.length * 100) + '%'"></div>
                    </div>
                </template>

                {{-- Navigation dots --}}
                <template x-if="slides.length > 1">
                    <div class="absolute bottom-4 left-1/2 z-10 flex -translate-x-1/2 gap-2.5 sm:bottom-5">
                        <template x-for="(_, i) in slides" :key="i">
                            <button type="button" @click="current = i; restart()" :aria-label="'Ver aviso ' + (i + 1)"
                                class="relative h-2.5 rounded-full transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#ff671f]/60 focus-visible:ring-offset-2 focus-visible:ring-offset-transparent"
                                :class="current === i ?
                                    'w-10 bg-[#ff671f] shadow-[0_0_10px_rgba(255,103,31,0.5)]' :
                                    'w-2.5 bg-white/40 hover:bg-white/70'">
                                {{-- Active pulse ring --}}
                                <span x-show="current === i" x-transition:enter="transition duration-700"
                                    x-transition:enter-start="opacity-0 scale-75"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute inset-0 animate-ping rounded-full bg-[#ff671f]/30">
                                </span>
                            </button>
                        </template>
                    </div>
                </template>

                {{-- Slide counter badge --}}
                <template x-if="slides.length > 1">
                    <div
                        class="absolute right-4 bottom-4 z-10 rounded-full bg-black/30 px-3 py-1 text-xs font-medium text-white/80 backdrop-blur-sm ring-1 ring-white/10 sm:right-5 sm:bottom-5">
                        <span x-text="current + 1"></span>
                        <span class="text-white/50">/</span>
                        <span x-text="slides.length"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
@endif
