<x-layouts::public
    metaTitle="Productos"
    metaDescription="Explorá el catálogo completo de productos farmacéuticos de Laboratorios Delta S.A. Medicamentos, suplementos y más con presencia en todo Bolivia.">
    @php
        $totalProducts = \App\Models\Product::query()->active()->count();
    @endphp

    {{-- ============================================================ --}}
    {{-- HERO SECTION                                                --}}
    {{-- ============================================================ --}}
    <section class="relative overflow-hidden py-10 sm:py-10">
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none"
            style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 24px 24px;">
        </div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-[#ff671f]/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-1/4 w-64 h-64 bg-[#ff671f]/5 rounded-full blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center" data-aos="fade-up">
                <span class="inline-block text-[14px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-4">
                    Catálogo farmacéutico
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight">
                    Nuestros <span class="text-[#ff671f]">Productos</span>
                </h1>
                <p class="mt-4 text-base sm:text-lg text-zinc-800 max-w-2xl mx-auto">
                    Explora nuestro catálogo completo con más de <strong
                        class="text-[#ff671f]">{{ $totalProducts }}</strong> productos farmacéuticos de la más alta
                    calidad.
                </p>
            </div>

            {{-- Search Bar --}}
            <div class="mt-10 max-w-xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                <form action="{{ route('public.products.index') }}" method="GET" class="relative">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-zinc-400" fill="none"
                            stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <input type="text" name="q" value="{{ request('q') }}"
                            placeholder="Buscar por nombre, principio activo..."
                            class="w-full rounded-full border border-zinc-300 bg-white backdrop-blur-md py-4 pl-12 pr-20 text-sm text-zinc-800 placeholder:text-zinc-400
                                      focus:outline-none focus:ring-2 focus:ring-[#ff671f]/40 focus:border-[#ff671f]/30
                                      transition-all duration-300" />
                        <button type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-[#ff671f] px-5 py-2 text-xs font-semibold text-white
                                       transition-all duration-300 hover:bg-[#e55a1a] hover:shadow-lg hover:shadow-[#ff671f]/30">
                            Buscar
                        </button>
                    </div>
                    @if (request('q') || request('category'))
                        <a href="{{ route('public.products.index') }}"
                            class="mt-2 inline-flex items-center gap-1 text-xs text-zinc-400 hover:text-white transition-colors">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar filtros
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- CATEGORY FILTER PILLS                                       --}}
    {{-- ============================================================ --}}
    <section class="py-8 border-b border-zinc-200/30">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center gap-2" data-aos="fade-up" data-aos-delay="50">
                {{-- "All" pill --}}
                <a href="{{ route('public.products.index') }}"
                    class="group relative inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-xs font-medium transition-all duration-300
                          {{ !request('category') ? 'bg-[#ff671f] text-white border-[#ff671f] shadow-md shadow-[#ff671f]/20' : 'bg-white text-zinc-600 border-zinc-200 hover:border-[#ff671f]/30 hover:text-[#ff671f] hover:shadow-sm' }}">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                    Todos
                </a>

                @foreach ($categories as $category)
                    @php
                        $isSelected = request('category') === $category->slug;
                        $hasProducts = $category->products_count > 0;
                        $canClick = $hasProducts || $isSelected;
                    @endphp

                    @if ($canClick)
                        <a href="{{ $isSelected ? route('public.products.index') : route('public.products.index', ['category' => $category->slug] + request()->except('category', 'page')) }}"
                            class="group relative inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-xs font-medium transition-all duration-300
                                  {{ $isSelected ? 'bg-[#ff671f] text-white border-[#ff671f] shadow-md shadow-[#ff671f]/20' : 'bg-white text-zinc-600 border-zinc-200 hover:border-[#ff671f]/30 hover:text-[#ff671f] hover:shadow-sm' }}">
                            {{ $category->name }}
                            <span
                                class="inline-flex items-center justify-center rounded-full px-1.5 py-0.5 text-[9px] font-bold tabular-nums
                                         {{ $isSelected ? 'bg-white/20 text-white' : 'bg-zinc-100 text-zinc-400 group-hover:bg-[#ff671f]/10 group-hover:text-[#ff671f]' }}">
                                {{ $category->products_count }}
                            </span>
                            @if ($isSelected)
                                <svg class="h-3 w-3 ml-0.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </a>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 rounded-full border border-zinc-100 bg-zinc-50 px-4 py-2 text-xs font-medium text-zinc-300 opacity-50 cursor-not-allowed"
                            title="No hay productos en esta categoría">
                            {{ $category->name }}
                            <span
                                class="inline-flex items-center justify-center rounded-full px-1.5 py-0.5 text-[9px] font-bold tabular-nums bg-zinc-100 text-zinc-300">
                                0
                            </span>
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- PRODUCTS GRID                                               --}}
    {{-- ============================================================ --}}
    <section class="py-16 sm:py-20 bg-gradient-to-b from-zinc-50 to-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($products->count() > 0)
                <div class="flex items-center justify-between mb-8" data-aos="fade-up">
                    <p class="text-sm text-zinc-500">
                        Mostrando <strong
                            class="text-zinc-700">{{ $products->firstItem() }}-{{ $products->lastItem() }}</strong> de
                        <strong class="text-zinc-700">{{ $products->total() }}</strong> productos
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <a href="{{ route('public.products.show', $product) }}"
                            class="group relative bg-white rounded-xl border border-black/8 overflow-hidden
           transition-all duration-500 ease-out
           hover:border-black/20 hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] hover:-translate-y-1"
                            data-aos="fade-up" data-aos-delay="{{ min($loop->index * 80, 400) }}">

                            {{-- Image Container --}}
                            <div class="relative aspect-square overflow-hidden bg-zinc-50">
                                @if ($product->main_image_path)
                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" loading="lazy"
                                        class="h-full w-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" />

                                    {{-- Overlay sutil en hover --}}
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-500">
                                    </div>
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <div
                                            class="flex h-16 w-16 items-center justify-center rounded-full bg-[#ff671f]/8 text-2xl font-bold text-[#ff671f]/40">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Brand badge — siempre visible --}}
                                @if ($product->brand)
                                    <span
                                        class="absolute top-1 left-1 inline-flex items-center rounded-lg bg-white px-2.5 py-1 text-[10px] font-semibold text-[#ff671f] border border-black/5 shadow-sm transition-all duration-500 ease-out group-hover:shadow-md group-hover:border-black/10">
                                        {{ $product->brand->name }}
                                    </span>
                                @endif

                                {{-- Category badge — siempre visible, reposicionado arriba-derecha --}}
                                @if ($product->category)
                                    <span
                                        class="absolute bottom-1 left-1 rounded-lg bg-black px-2.5 py-1 text-[10px] font-medium text-white shadow-sm transition-all duration-500 ease-out group-hover:bg-[#ff671f] group-hover:shadow-md">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-4 sm:p-5 relative">
                                {{-- Línea decorativa naranja que se expande en hover --}}
                                <div class="absolute top-0 left-4 right-4 h-px bg-black/5">
                                    <div
                                        class="h-full bg-[#ff671f] w-0 group-hover:w-full transition-all duration-500 ease-out">
                                    </div>
                                </div>

                                <h3
                                    class="font-semibold text-zinc-900 group-hover:text-[#ff671f] transition-colors duration-300 text-sm leading-snug">
                                    {{ $product->name }}
                                </h3>

                                @if ($product->active_ingredient)
                                    <p class="mt-1.5 text-xs text-zinc-400">{{ $product->active_ingredient }}</p>
                                @endif

                                {{-- CTA Row --}}
                                <div class="mt-4 flex items-center justify-between">
                                    <span
                                        class="text-xs font-medium text-[#ff671f] flex items-center gap-1.5 transition-all duration-500 ease-out group-hover:gap-2">
                                        Ver detalle
                                        <svg class="h-3.5 w-3.5 transition-transform duration-500 ease-out group-hover:translate-x-1"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                    </span>

                                    {{-- Indicador visual de interacción — siempre visible con sutil cambio en hover --}}
                                    <div
                                        class="w-6 h-6 rounded-full border border-black/8 flex items-center justify-center transition-all duration-500 ease-out group-hover:border-[#ff671f]/40 group-hover:bg-[#ff671f]/5">
                                        <svg class="h-3 w-3 text-black/30 transition-all duration-500 ease-out group-hover:text-[#ff671f] group-hover:translate-x-0.5"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Borde inferior naranja que aparece en hover --}}
                            <div
                                class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#ff671f] scale-x-0 group-hover:scale-x-100 transition-transform duration-500 ease-out origin-left">
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12" data-aos="fade-up">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 text-center" data-aos="fade-up">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-zinc-100 mb-6">
                        <svg class="h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-700">
                        {{ $selectedCategory ? 'Sin resultados en ' . $selectedCategory->name : 'Sin resultados' }}
                    </h3>
                    <p class="mt-2 text-sm text-zinc-400 max-w-sm">
                        @if ($selectedCategory)
                            No encontramos productos en la categoría "<strong>{{ $selectedCategory->name }}</strong>"
                            que coincidan con tu búsqueda.
                        @else
                            No encontramos productos que coincidan con tu búsqueda. Intenta con otros términos.
                        @endif
                    </p>
                    <a href="{{ route('public.products.index') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#ff671f] px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#ff671f]/20 transition-all hover:bg-[#e55a1a] hover:-translate-y-0.5">
                        Ver todos los productos
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-layouts::public>
