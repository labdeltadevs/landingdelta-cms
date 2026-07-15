<x-layouts::public>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-zinc-900">Productos</h1>
        <p class="mt-2 text-sm text-zinc-500">Explora nuestro catálogo completo de productos farmacéuticos.</p>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($products as $product)
                <a href="{{ route('public.products.show', $product) }}" class="group rounded-xl border border-zinc-200 bg-white p-4 transition hover:shadow-md">
                    <div class="aspect-square overflow-hidden rounded-lg bg-zinc-100">
                        @if ($product->main_image_path)
                            <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition group-hover:scale-105" />
                        @else
                            <div class="flex h-full items-center justify-center text-zinc-300 text-sm">Sin imagen</div>
                        @endif
                    </div>
                    <div class="mt-4">
                        @if ($product->brand)
                            <p class="text-xs text-orange-600 font-medium">{{ $product->brand->name }}</p>
                        @endif
                        <h3 class="mt-1 font-semibold text-zinc-900 group-hover:text-orange-600">{{ $product->name }}</h3>
                        <p class="mt-1 text-xs text-zinc-500">{{ $product->active_ingredient }}</p>
                        @if ($product->approx_price)
                            <p class="mt-2 text-sm font-bold">{{ $product->formatted_price }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center text-zinc-400">No hay productos disponibles.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->links() }}</div>
    </div>
</x-layouts::public>
