<x-layouts::public>
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
                    Explora nuestro catálogo completo con más de <strong class="text-[#ff671f]">{{ $totalProducts }}</strong> productos farmacéuticos de la más alta calidad.
                </p>
            </div>

            {{-- Search Bar --}}
            <div class="mt-10 max-w-xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                <form action="{{ route('public.products.index') }}" method="GET" class="relative">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
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
                        <a href="{{ route('public.products.index') }}" class="mt-2 inline-flex items-center gap-1 text-xs text-zinc-400 hover:text-white transition-colors">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
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
                          {{ ! request('category') ? 'bg-[#ff671f] text-white border-[#ff671f] shadow-md shadow-[#ff671f]/20' : 'bg-white text-zinc-600 border-zinc-200 hover:border-[#ff671f]/30 hover:text-[#ff671f] hover:shadow-sm' }}">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
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
                            <span class="inline-flex items-center justify-center rounded-full px-1.5 py-0.5 text-[9px] font-bold tabular-nums
                                         {{ $isSelected ? 'bg-white/20 text-white' : 'bg-zinc-100 text-zinc-400 group-hover:bg-[#ff671f]/10 group-hover:text-[#ff671f]' }}">
                                {{ $category->products_count }}
                            </span>
                            @if ($isSelected)
                                <svg class="h-3 w-3 ml-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-100 bg-zinc-50 px-4 py-2 text-xs font-medium text-zinc-300 opacity-50 cursor-not-allowed"
                              title="No hay productos en esta categoría">
                            {{ $category->name }}
                            <span class="inline-flex items-center justify-center rounded-full px-1.5 py-0.5 text-[9px] font-bold tabular-nums bg-zinc-100 text-zinc-300">
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
                        Mostrando <strong class="text-zinc-700">{{ $products->firstItem() }}-{{ $products->lastItem() }}</strong> de <strong class="text-zinc-700">{{ $products->total() }}</strong> productos
                    </p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <a href="{{ route('public.products.show', $product) }}"
                           class="group relative bg-white rounded-2xl border border-zinc-100 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-[#ff671f]/5 hover:-translate-y-1"
                           data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                            {{-- Image --}}
                            <div class="relative aspect-square overflow-hidden bg-zinc-50">
                                @if ($product->main_image_path)
                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                                         class="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#ff671f]/5 text-2xl font-bold text-[#ff671f]/30">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </div>
                                    </div>
                                @endif

                                {{-- Brand badge --}}
                                @if ($product->brand)
                                    <span class="absolute top-3 left-3 inline-flex items-center rounded-full bg-white/90 backdrop-blur-sm px-3 py-1 text-[10px] font-semibold text-[#ff671f] shadow-sm border border-white/50">
                                        {{ $product->brand->name }}
                                    </span>
                                @endif

                                {{-- Price pill --}}
                                {{-- @if ($product->approx_price)
                                    <span class="absolute bottom-3 right-3 rounded-full bg-white/90 backdrop-blur-sm px-3 py-1.5 text-xs font-bold text-zinc-800 shadow-sm border border-white/50">
                                        {{ $product->formatted_price }}
                                    </span>
                                @endif --}}

                                {{-- Category hint --}}
                                @if ($product->category)
                                    <span class="absolute bottom-3 left-3 rounded-full bg-black/50 backdrop-blur-sm px-3 py-1 text-[10px] font-medium text-white/90">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-4 sm:p-5">
                                <h3 class="font-semibold text-zinc-900 group-hover:text-[#ff671f] transition-colors text-sm leading-snug">
                                    {{ $product->name }}
                                </h3>
                                @if ($product->active_ingredient)
                                    <p class="mt-1 text-xs text-zinc-400">{{ $product->active_ingredient }}</p>
                                @endif
                                <div class="mt-3 flex items-center gap-2">
                                    <span class="text-xs font-medium text-[#ff671f] opacity-0 translate-x-2 transition-all duration-300 group-hover:opacity-100 group-hover:translate-x-0">
                                        Ver detalle
                                    </span>
                                    <svg class="h-3.5 w-3.5 text-[#ff671f] opacity-0 translate-x-2 transition-all duration-300 group-hover:opacity-100 group-hover:translate-x-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                    </svg>
                                </div>
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
                        <svg class="h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-700">
                        {{ $selectedCategory ? 'Sin resultados en ' . $selectedCategory->name : 'Sin resultados' }}
                    </h3>
                    <p class="mt-2 text-sm text-zinc-400 max-w-sm">
                        @if ($selectedCategory)
                            No encontramos productos en la categoría "<strong>{{ $selectedCategory->name }}</strong>" que coincidan con tu búsqueda.
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
