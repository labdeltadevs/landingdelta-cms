<x-layouts::public>
    @php
        $related = \App\Models\Product::query()
            ->active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->with('brand', 'category')
            ->ordered()
            ->take(4)
            ->get();
    @endphp

    {{-- ============================================================ --}}
    {{-- BREADCRUMB                                                   --}}
    {{-- ============================================================ --}}
    <section class="relative overflow-hidden bg-gradient-to-b from-zinc-900 to-zinc-800 py-12 sm:py-16">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
             style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 24px 24px;">
        </div>
        <div class="absolute top-0 right-1/3 w-72 h-72 bg-[#ff671f]/8 rounded-full blur-3xl"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-zinc-400" data-aos="fade-up">
                <a href="{{ route('public.home') }}" class="hover:text-white transition-colors">Inicio</a>
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                <a href="{{ route('public.products.index') }}" class="hover:text-white transition-colors">Productos</a>
                @if ($product->category)
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                    <a href="{{ route('public.products.index') }}?category={{ $product->category->slug }}" class="hover:text-white transition-colors">{{ $product->category->name }}</a>
                @endif
                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                <span class="text-white font-medium truncate max-w-[200px]">{{ $product->name }}</span>
            </nav>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- PRODUCT DETAIL                                               --}}
    {{-- ============================================================ --}}
    <section class="py-12 sm:py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
                {{-- LEFT: Image Gallery --}}
                <div data-aos="fade-right" data-aos-delay="100">
                    <div class="relative aspect-square overflow-hidden rounded-2xl bg-zinc-50 border border-zinc-100 shadow-lg">
                        @if ($product->main_image_path)
                            <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}"
                                 class="h-full w-full object-cover transition duration-700 hover:scale-105" />
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-gradient-to-br from-[#ff671f]/10 to-[#ff671f]/5 text-5xl font-bold text-[#ff671f]/30">
                                    {{ strtoupper(substr($product->name, 0, 1)) }}
                                </div>
                            </div>
                        @endif

                        {{-- Image badges --}}
                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            @if ($product->is_featured)
                                <span class="inline-flex items-center gap-1 rounded-full bg-[#ff671f]/90 backdrop-blur-sm px-3 py-1.5 text-xs font-semibold text-white shadow-lg">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                                    Destacado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Product Info --}}
                <div class="flex flex-col justify-center" data-aos="fade-left" data-aos-delay="200">
                    {{-- Brand & Category --}}
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @if ($product->brand)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#ff671f]/10 px-4 py-1.5 text-xs font-semibold text-[#ff671f]">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                                {{ $product->brand->name }}
                            </span>
                        @endif
                        @if ($product->category)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-100 px-4 py-1.5 text-xs font-medium text-zinc-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                                </svg>
                                {{ $product->category->name }}
                            </span>
                        @endif
                    </div>

                    {{-- Name --}}
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-zinc-900 leading-tight">
                        {{ $product->name }}
                    </h1>

                    {{-- Active Ingredient --}}
                    @if ($product->active_ingredient)
                        <div class="mt-6 flex items-center gap-3 rounded-2xl bg-gradient-to-r from-[#ff671f]/5 to-transparent border border-[#ff671f]/10 px-5 py-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#ff671f]/10 text-[#ff671f] flex-shrink-0">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider">Principio activo</p>
                                <p class="text-sm font-semibold text-zinc-800">{{ $product->active_ingredient }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Price --}}
                    {{-- @if ($product->approx_price)
                        <div class="mt-6">
                            <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1">Precio aproximado</p>
                            <p class="text-3xl sm:text-4xl font-bold text-zinc-900">
                                {{ $product->formatted_price }}
                                <span class="text-sm font-normal text-zinc-400">+ IVA</span>
                            </p>
                        </div>
                    @endif --}}

                    {{-- Divider --}}
                    <div class="my-8 border-t border-zinc-100"></div>

                    {{-- Description --}}
                    @if ($product->description)
                        <div>
                            <h3 class="text-sm font-semibold text-zinc-900 mb-3">Descripción</h3>
                            <div class="prose prose-sm prose-zinc max-w-none text-zinc-600 leading-relaxed">
                                {!! $product->description !!}
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('public.products.index') }}"
                           class="inline-flex items-center gap-2 rounded-full bg-[#ff671f] px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#ff671f]/25 transition-all duration-300 hover:bg-[#e55a1a] hover:shadow-[#ff671f]/50 hover:-translate-y-0.5 active:scale-95">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                            Volver a productos
                        </a>
                        @if ($product->brand)
                            <a href="{{ route('public.brands.show', $product->brand) }}"
                               class="inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-white px-7 py-3.5 text-sm font-medium text-zinc-700 transition-all duration-300 hover:border-[#ff671f]/30 hover:text-[#ff671f] hover:shadow-lg hover:shadow-[#ff671f]/5">
                                Ver más de {{ $product->brand->name }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- RELATED PRODUCTS                                             --}}
    {{-- ============================================================ --}}
    @if ($related->isNotEmpty())
        <section class="py-16 sm:py-20 bg-zinc-50 border-t border-zinc-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10" data-aos="fade-up">
                    <span class="inline-block text-[11px] font-semibold uppercase tracking-[0.2em] text-[#ff671f] mb-3">
                        Relacionados
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-zinc-900">
                        Productos <span class="text-[#ff671f]">similares</span>
                    </h2>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($related as $rel)
                        <a href="{{ route('public.products.show', $rel) }}"
                           class="group relative bg-white rounded-2xl border border-zinc-100 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-[#ff671f]/5 hover:-translate-y-1"
                           data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <div class="relative aspect-square overflow-hidden bg-zinc-50">
                                @if ($rel->main_image_path)
                                    <img src="{{ $rel->main_image_url }}" alt="{{ $rel->name }}"
                                         class="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                                @else
                                    <div class="flex h-full items-center justify-center">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#ff671f]/5 text-xl font-bold text-[#ff671f]/20">
                                            {{ strtoupper(substr($rel->name, 0, 1)) }}
                                        </div>
                                    </div>
                                @endif
                                @if ($rel->brand)
                                    <span class="absolute top-3 left-3 rounded-full bg-white/90 backdrop-blur-sm px-2.5 py-1 text-[10px] font-semibold text-[#ff671f] shadow-sm">
                                        {{ $rel->brand->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-zinc-900 group-hover:text-[#ff671f] transition-colors leading-snug">
                                    {{ $rel->name }}
                                </h3>
                                @if ($rel->active_ingredient)
                                    <p class="mt-1 text-xs text-zinc-400">{{ $rel->active_ingredient }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts::public>
