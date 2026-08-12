@props([
    'slides' => collect(),
])

@if ($slides instanceof \Illuminate\Support\Collection ? $slides->isNotEmpty() : !empty($slides))
    @php
        $modalSlides = collect($slides)
            ->map(function ($slide) {
                // Resolver enlace: prioriza cta_url, luego el morph slideable
                $ctaUrl = $slide->ctaLink();

                if (! $ctaUrl && $slide->slideable) {
                    $ctaUrl = match (true) {
                        $slide->slideable instanceof \App\Models\Product => route('products.show', $slide->slideable),
                        $slide->slideable instanceof \App\Models\Brand   => route('brands.show', $slide->slideable),
                        default => null,
                    };
                }

                return [
                    'title'      => $slide->title,
                    'subtitle'   => $slide->subtitle,
                    'image_url'  => $slide->image_url,
                    'tag'        => $slide->tagLabel(),
                    'cta_label'  => $slide->cta_label,
                    'cta_url'    => $ctaUrl,
                    'has_cta'    => filled($slide->cta_label) && filled($ctaUrl),
                ];
            })
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
        function heroSlidesModal({ slides, startOpen }) {
            return {
                open: false,
                current: 0,
                progress: 0,
                slides,
                timer: null,
                duration: 6000,

                init() {
                    if (startOpen && !sessionStorage.getItem('hero-slides-modal-dismissed')) {
                        this.open = true;
                        this.$nextTick(() => this.start());
                    }
                },

                start() {
                    if (this.slides.length <= 1) return;

                    this.progress = 0;
                    const step = 50;
                    let elapsed = 0;

                    this.timer = setInterval(() => {
                        elapsed += step;
                        this.progress = (elapsed / this.duration) * 100;

                        if (elapsed >= this.duration) {
                            this.next();
                        }
                    }, step);
                },

                next() {
                    this.current = (this.current + 1) % this.slides.length;
                    this.restart();
                },

                goTo(i) {
                    this.current = i;
                    this.restart();
                },

                restart() {
                    if (this.timer) clearInterval(this.timer);
                    this.start();
                },

                close() {
                    this.open = false;
                    if (this.timer) clearInterval(this.timer);
                    sessionStorage.setItem('hero-slides-modal-dismissed', '1');
                },
            };
        }
    </script>

    <div data-hero-slides-modal x-cloak
        x-data="heroSlidesModal({
            slides: {{ $modalSlides->toJson($jsonFlags) }},
            startOpen: true,
        })"
        x-show="open"
        @keydown.escape.window="close()"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="hero-slides-modal-title">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-600/20 backdrop-blur-lg saturate-150 transition-opacity duration-300"
            @click="close()"></div>

        {{-- Modal card --}}
        <div x-show="open"
            x-transition:enter="transition ease-out duration-500 delay-100"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            class="relative w-full max-w-6xl overflow-hidden rounded-[2rem] bg-slate-200 shadow-[0_30px_90px_-15px_rgba(0,0,0,0.7)] ring-1 ring-white/10">

            {{-- Close button --}}
            <button type="button" @click="close()" aria-label="Cerrar aviso"
                class="group absolute right-4 top-4 z-30 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md ring-1 ring-white/20 transition-all duration-300 hover:scale-110 hover:bg-[#ff671f] hover:ring-[#ff671f] hover:shadow-lg hover:shadow-[#ff671f]/40 active:scale-95">
                <svg class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Slides container --}}
            <div class="relative h-[75vh] sm:h-[70vh] lg:h-[80vh]">

                <template x-for="(slide, i) in slides" :key="i">
                    <div x-show="current === i" x-cloak
                        x-transition:enter="transition duration-[800ms] ease-[cubic-bezier(0.16,1,0.3,1)]"
                        x-transition:enter-start="opacity-0 scale-[1.04]"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition duration-[600ms] ease-[cubic-bezier(0.7,0,0.84,0)]"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-[0.97]"
                        class="absolute inset-0 will-change-transform">

                        {{-- Background image --}}
                        <template x-if="slide.image_url">
                            <img :src="slide.image_url" :alt="slide.title"
                                class="absolute inset-0 h-full w-full object-scale-down" />
                        </template>

                        {{-- Gradient fallback --}}
                        <template x-if="!slide.image_url">
                            <div class="absolute inset-0 bg-gradient-to-br from-[#ff671f] via-[#ff8a4c] to-[#ffb37e]"></div>
                        </template>

                        {{-- Ambient glow --}}
                        <div class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-[#ff671f]/25 blur-3xl"></div>
                        <div class="pointer-events-none absolute -top-24 -right-24 h-56 w-56 rounded-full bg-[#ff8a4c]/20 blur-3xl"></div>

                        {{-- Multi-layer gradient overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-400 via-slate-600/10 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-400/30 via-transparent to-transparent"></div>

                        {{-- Content --}}
                        <div class="absolute inset-x-0 bottom-0 z-10 p-6 sm:p-6 lg:p-8">

                            {{-- Title --}}
                            <h2 id="hero-slides-modal-title"
                                x-text="slide.title"
                                class="max-w-4xl text-xl font-black leading-[1.1] tracking-tight text-white drop-shadow-lg sm:text-4xl lg:text-5xl"></h2>

                            {{-- Subtitle --}}
                            <template x-if="slide.subtitle">
                                <p x-text="slide.subtitle"
                                    class="mt-4 max-w-4xl text-sm leading-relaxed text-white/85 sm:text-base lg:text-lg"></p>
                            </template>

                            {{-- CTA Button --}}
                            <template x-if="slide.has_cta">
                                <a :href="slide.cta_url"
                                    class="group mt-6 inline-flex items-center gap-2.5 rounded-full bg-[#ff671f] px-8 py-4 text-sm font-bold text-white shadow-lg shadow-[#ff671f]/40 transition-all duration-300 hover:-translate-y-0.5 hover:bg-[#e55a1a] hover:shadow-xl hover:shadow-[#ff671f]/50 active:translate-y-0 active:shadow-md">
                                    <span x-text="slide.cta_label"></span>
                                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                        fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Progress bar (sincronizada con el timer) --}}
                <template x-if="slides.length > 1">
                    <div class="absolute top-0 left-0 right-0 z-20 h-[3px] bg-white/10">
                        <div class="h-full rounded-r-full bg-[#ff671f] shadow-[0_0_8px_rgba(255,103,31,0.6)]"
                            :style="'width:' + progress + '%'"></div>
                    </div>
                </template>

                {{-- Navigation dots --}}
                <template x-if="slides.length > 1">
                    <div class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-2.5">
                        <template x-for="(_, i) in slides" :key="i">
                            <button type="button" @click="goTo(i)" :aria-label="'Ver aviso ' + (i + 1)"
                                class="relative h-2.5 rounded-full transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#ff671f]/60 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-900"
                                :class="current === i
                                    ? 'w-10 bg-[#ff671f] shadow-[0_0_12px_rgba(255,103,31,0.6)]'
                                    : 'w-2.5 bg-white/40 hover:bg-white/70'">
                            </button>
                        </template>
                    </div>
                </template>

                {{-- Slide counter --}}
                <template x-if="slides.length > 1">
                    <div class="absolute right-5 bottom-5 z-20 rounded-full bg-black/40 px-3 py-1 text-xs font-semibold text-white/90 backdrop-blur-md ring-1 ring-white/10">
                        <span x-text="current + 1"></span>
                        <span class="text-white/50">/</span>
                        <span x-text="slides.length"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
@endif
