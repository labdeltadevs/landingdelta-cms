<x-layouts::public metaTitle="Marcas"
    metaDescription="Conocé las marcas exclusivas que Laboratorios Delta S.A. representa y distribuye en Bolivia: Delta, Hidrófilo, Maver, Rossetti, Synthera y más.">
    {{-- ============================================================ --}}
    {{-- BRANDS INDEX (LIGHT)                                         --}}
    {{-- ============================================================ --}}
    <div class="relative overflow-hidden bg-zinc-50" x-data="{
        search: '',
        visible: {{ $brands->count() }},
        filter() {
            const q = this.search.toLowerCase().trim();
            let count = 0;
            this.$refs.brandGrid.querySelectorAll('[data-name]').forEach((el) => {
                const show = !q || el.dataset.name.includes(q);
                el.classList.toggle('hidden', !show);
                if (show) count++;
            });
            this.visible = count;
        }
    }">

        {{-- Patrón de puntos + glows ambientales --}}
        <div class="pointer-events-none absolute inset-0 opacity-60"
            style="background-image: radial-gradient(circle, #e4e4e7 1px, transparent 1px); background-size: 22px 22px;">
        </div>
        <div class="pointer-events-none absolute -top-24 left-1/4 h-96 w-96 rounded-full bg-[#ff671f]/5 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-72 w-72 rounded-full bg-orange-200/20 blur-3xl">
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">

            {{-- Encabezado --}}
            <header class="mb-10 text-center" data-aos="fade-up">
                <span
                    class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-3">
                    <span class="h-px w-8 bg-[#ff671f]/50"></span>
                    Distribución exclusiva
                    <span class="h-px w-8 bg-[#ff671f]/50"></span>
                </span>
                <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900">
                    Nuestras <span class="text-[#ff671f]">marcas</span>
                </h1>
                <p class="mt-3 text-sm text-zinc-500 max-w-lg mx-auto">
                    Trabajamos con marcas de alta calidad para llevar los mejores productos a todo el país.
                </p>

                @if ($brands->isNotEmpty())
                    <div
                        class="mt-5 inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-4 py-1.5 text-xs font-medium text-zinc-500 shadow-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-[#ff671f] animate-pulse"></span>
                        {{ $brands->count() }} {{ $brands->count() === 1 ? 'marca aliada' : 'marcas aliadas' }}
                    </div>
                @endif
            </header>

            {{-- Buscador en vivo --}}
            @if ($brands->count() > 4)
                <div class="relative mx-auto mb-12 max-w-md" data-aos="fade-up" data-aos-delay="80">
                    <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center">
                        <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input type="search" x-model="search" @input="filter()" placeholder="Buscar marca…"
                        class="w-full rounded-full border border-zinc-200 bg-white py-3 pl-11 pr-11 text-sm text-zinc-800 placeholder-zinc-400 shadow-sm transition-all focus:border-[#ff671f]/50 focus:outline-none focus:ring-4 focus:ring-[#ff671f]/10" />
                    <button type="button" x-show="search.length" x-cloak @click="search = ''; filter()"
                        aria-label="Limpiar búsqueda"
                        class="absolute inset-y-0 right-3 my-auto flex h-7 w-7 items-center justify-center rounded-full text-zinc-400 transition-colors hover:bg-zinc-100 hover:text-zinc-600">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Grid de marcas --}}
            <div x-ref="brandGrid" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($brands as $brand)
                    <a href="{{ route('public.brands.show', $brand) }}"
                        data-name="{{ \Illuminate\Support\Str::lower($brand->name) }}"
                        class="group relative flex flex-col items-center overflow-hidden rounded-2xl border border-zinc-200 bg-white p-8 text-center shadow-[0_2px_12px_-2px_rgba(0,0,0,0.05)] transition-all duration-300 hover:-translate-y-1 hover:border-[#ff671f]/30 hover:shadow-[0_8px_30px_-6px_rgba(255,103,31,0.15)]"
                        data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 60 }}">

                        {{-- Línea de acento superior --}}
                        <div
                            class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-[#ff671f] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-60">
                        </div>

                        {{-- Glow interno al hover --}}
                        <div
                            class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-[#ff671f]/10 blur-2xl opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                        </div>

                        {{-- Logo / Fallback --}}
                        @if ($brand->logo_path)
                            <div class="relative flex h-24 w-full items-center justify-center">
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" loading="lazy"
                                    class="max-h-full max-w-[160px] object-contain grayscale-[55%] opacity-90 transition-all duration-500 group-hover:scale-110 group-hover:grayscale-0 group-hover:opacity-100" />
                            </div>
                        @else
                            <div
                                class="flex h-24 w-24 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-50 to-[#ff671f]/10 text-3xl font-black text-[#ff671f] ring-1 ring-orange-100 transition-all duration-500 group-hover:scale-110 group-hover:from-[#ff671f] group-hover:to-orange-400 group-hover:text-white group-hover:shadow-lg group-hover:shadow-orange-500/30">
                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($brand->name, 0, 1)) }}
                            </div>
                        @endif

                        {{-- Nombre --}}
                        <h3
                            class="mt-5 font-semibold text-zinc-900 transition-colors duration-300 group-hover:text-[#e55a1a]">
                            {{ $brand->name }}
                        </h3>

                        @if ($brand->description)
                            <p class="mt-1.5 text-xs leading-relaxed text-zinc-400 line-clamp-2">
                                {{ $brand->description }}</p>
                        @endif

                        {{-- Conteo de productos --}}
                        @if (($brand->products_count ?? 0) > 0)
                            <span
                                class="mt-2 inline-flex items-center gap-1 rounded-full bg-zinc-50 px-2.5 py-0.5 text-[11px] font-medium text-zinc-500 ring-1 ring-zinc-200 transition-colors group-hover:bg-orange-50 group-hover:text-[#e55a1a] group-hover:ring-orange-200">
                                {{ $brand->products_count }} productos
                            </span>
                        @endif

                        {{-- CTA revelado al hover --}}
                        <span
                            class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#ff671f] opacity-0 translate-y-1 transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                            Ver productos
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </a>
                @empty
                    <div
                        class="col-span-full flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-zinc-300 bg-white/60 py-20 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-400">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-zinc-700">Sin marcas registradas</h3>
                        <p class="mt-1 text-sm text-zinc-400">Pronto anunciaremos nuevas alianzas comerciales.</p>
                    </div>
                @endforelse
            </div>

            {{-- Sin resultados de búsqueda --}}
            <div x-show="search.length && visible === 0" x-cloak
                class="mt-4 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-zinc-300 bg-white/60 py-16 text-center">
                <svg class="h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <p class="mt-3 text-sm font-medium text-zinc-600">
                    No encontramos marcas para «<span class="font-semibold text-[#ff671f]" x-text="search"></span>»
                </p>
                <button type="button" @click="search = ''; filter()"
                    class="mt-4 inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white px-4 py-2 text-xs font-semibold text-zinc-600 transition-all hover:border-[#ff671f]/40 hover:text-[#ff671f]">
                    Limpiar búsqueda
                </button>
            </div>

        </div>
    </div>
</x-layouts::public>
