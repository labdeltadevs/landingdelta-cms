<x-layouts::public>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <nav class="mb-8 text-sm text-zinc-500">
            <a href="{{ route('public.home') }}" class="hover:text-zinc-900">Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('public.brands.index') }}" class="hover:text-zinc-900">Marcas</a>
            <span class="mx-2">/</span>
            <span class="text-zinc-900">{{ $brand->name }}</span>
        </nav>

        <div class="flex items-center gap-6 mb-12">
            @if ($brand->logo_path)
                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="h-20 object-contain" />
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
                            @if ($product->main_image_path)
                                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition group-hover:scale-105" />
                            @else
                                <div class="flex h-full items-center justify-center text-zinc-300 text-sm">Sin imagen</div>
                            @endif
                        </div>
                        <div class="mt-4">
                            <h3 class="font-semibold text-zinc-900 group-hover:text-orange-600">{{ $product->name }}</h3>
                            <p class="mt-1 text-xs text-zinc-500">{{ $product->active_ingredient }}</p>
                            @if ($product->approx_price)
                                <p class="mt-2 text-sm font-bold">{{ $product->formatted_price }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="py-20 text-center text-zinc-400">No hay productos de esta marca.</p>
        @endif
    </div>
</x-layouts::public>
