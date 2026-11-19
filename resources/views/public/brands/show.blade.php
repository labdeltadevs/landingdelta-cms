<x-layouts::public
    metaTitle="{{ $brand->name }}"
    metaDescription="Conocé todos los productos de la división {{ $brand->name }} distribuida por Laboratorios Delta S.A. en Bolivia."
    ogImage="{{ $brand->logo_url }}"
    jsonLd='{
        "@context": "https://schema.org",
        "@type": "Brand",
        "name": "{{ $brand->name }}",
        "description": "División distribuida por Laboratorios Delta S.A. en Bolivia.",
        "url": "{{ url()->current() }}",
        "logo": "{{ $brand->logo_path ? $brand->logo_url : Storage::disk('public')->url('logo_delta.png') }}"
    }'>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <nav class="mb-8 text-sm text-zinc-500">
            <a href="{{ route('public.home') }}" class="hover:text-zinc-900">Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('public.brands.index') }}" class="hover:text-zinc-900">Divisiones</a>
            <span class="mx-2">/</span>
            <span class="text-zinc-900">{{ $brand->name }}</span>
        </nav>

        <div class="flex items-center gap-6 mb-12">
            @if ($brand->logo_path)
                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" loading="lazy" class="h-20 object-contain" />
            @endif
            <div>
                <h1 class="text-3xl font-bold text-zinc-900">{{ $brand->name }}</h1>
                @if ($brand->description)
                    <p class="mt-2 text-sm text-zinc-500">{{ $brand->description }}</p>
                @endif
            </div>
        </div>

        @if ($products->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <a href="{{ route('public.products.show', $product) }}" class="group rounded-xl border border-zinc-200 bg-white p-4 transition hover:shadow-md">
                        <div class="aspect-square overflow-hidden rounded-lg bg-zinc-100">
                            @if ($product->is_incoming)
                                <img src="{{ $product->display_image_url }}" alt="Próximamente: {{ $product->name }}" loading="lazy" class="h-full w-full object-contain p-8 transition" />
                                {{-- Listón "Próximamente" al pie de la imagen --}}
                                <div class="absolute left-0 right-0 bottom-0 px-4 py-2.5 flex items-center gap-1.5 bg-white/80 backdrop-blur-sm border-t border-[#fc671a]/15">
                                    <svg class="h-3 w-3" fill="#c2410c" viewBox="0 0 24 24">
                                        <path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                    </svg>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-[#c2410c] leading-none">Próximamente</span>
                                </div>
                                </span>
@elseif ($product->main_image_path)
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition group-hover:scale-105" />
                            @else
                                <div class="flex h-full items-center justify-center text-zinc-300 text-sm">Sin imagen</div>
                            @endif
                        </div>
                        <div class="mt-4">
                            <h3 class="font-semibold text-zinc-900 group-hover:text-orange-600">{{ $product->name }}</h3>
                            <p class="mt-1 text-xs text-zinc-500">{{ $product->active_ingredient }}</p>
                            {{-- @if ($product->approx_price)
                                <p class="mt-2 text-sm font-bold">{{ $product->formatted_price }}</p>
                            @endif --}}
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="py-20 text-center text-zinc-400">No hay productos de esta división.</p>
        @endif
    </div>
</x-layouts::public>
