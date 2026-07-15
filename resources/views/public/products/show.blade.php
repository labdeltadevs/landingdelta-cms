<x-layouts::public>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <nav class="mb-8 text-sm text-zinc-500">
            <a href="{{ route('public.home') }}" class="hover:text-zinc-900">Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('public.products.index') }}" class="hover:text-zinc-900">Productos</a>
            <span class="mx-2">/</span>
            <span class="text-zinc-900">{{ $product->name }}</span>
        </nav>

        <div class="grid gap-12 lg:grid-cols-2">
            <div class="aspect-square overflow-hidden rounded-2xl bg-zinc-100">
                @if ($product->main_image_path)
                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                @else
                    <div class="flex h-full items-center justify-center text-zinc-300 text-lg">Sin imagen</div>
                @endif
            </div>
            <div>
                @if ($product->brand)
                    <p class="text-sm font-medium text-orange-600">{{ $product->brand->name }}</p>
                @endif
                <h1 class="mt-2 text-3xl font-bold text-zinc-900">{{ $product->name }}</h1>
                @if ($product->active_ingredient)
                    <p class="mt-2 text-sm text-zinc-500">Principio activo: <span class="font-medium text-zinc-700">{{ $product->active_ingredient }}</span></p>
                @endif
                @if ($product->category)
                    <p class="mt-1 text-sm text-zinc-500">Categoría: <span class="font-medium text-zinc-700">{{ $product->category->name }}</span></p>
                @endif
                @if ($product->approx_price)
                    <p class="mt-4 text-2xl font-bold text-zinc-900">{{ $product->formatted_price }}</p>
                @endif
                @if ($product->description)
                    <div class="mt-6 prose prose-sm max-w-none text-zinc-600">
                        {{ $product->description }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts::public>
