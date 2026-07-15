<x-layouts::public>
    @php
        $slides = \App\Models\HeroSlide::query()->active()->ordered()->get();
        $categories = \App\Models\Category::query()->active()->ordered()->get();
        $featured = \App\Models\Product::query()->active()->featured()->with('brand', 'category')->ordered()->take(6)->get();
        $brands = \App\Models\Brand::query()->active()->ordered()->get();
        $branches = \App\Models\Branch::query()->active()->ordered()->get();
    @endphp

    @if ($slides->isNotEmpty())
        <section class="relative overflow-hidden bg-zinc-900" x-data="{ current: 0, total: {{ $slides->count() }} }" x-init="setInterval(() => { current = (current + 1) % total }, 6000)">
            <template x-for="(slide, i) in {{ json_encode($slides->map(fn($s) => ['image' => $s->image_url, 'title' => $s->title, 'subtitle' => $s->subtitle, 'cta_label' => $s->cta_label, 'cta_url' => $s->cta_url])) }}" :key="i">
                <div x-show="current === i" x-transition:enter="transition-opacity duration-700" class="relative h-[70vh] min-h-[500px] w-full">
                    <img :src="slide.image" :alt="slide.title" class="absolute inset-0 h-full w-full object-cover opacity-60" />
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent" />
                    <div class="relative z-10 flex h-full items-center px-6 sm:px-12 lg:px-20">
                        <div class="max-w-xl">
                            <h1 class="text-4xl font-bold text-white sm:text-5xl lg:text-6xl" x-text="slide.title"></h1>
                            <p class="mt-4 text-lg text-zinc-200" x-text="slide.subtitle"></p>
                            <a x-show="slide.cta_label" :href="slide.cta_url" class="mt-6 inline-flex items-center gap-2 rounded-lg bg-orange-600 px-6 py-3 text-sm font-medium text-white transition hover:bg-orange-500" x-text="slide.cta_label"></a>
                        </div>
                    </div>
                </div>
            </template>
            <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 gap-2">
                <template x-for="(_, i) in total" :key="i">
                    <button @click="current = i" class="h-2 w-2 rounded-full transition" :class="current === i ? 'bg-orange-500 w-6' : 'bg-white/50'"></button>
                </template>
            </div>
        </section>
    @endif

    @if ($categories->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-zinc-900">Categorías</h2>
            <p class="mt-2 text-sm text-zinc-500">Explora nuestros productos por categoría.</p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                @foreach ($categories as $category)
                    <a href="{{ route('public.products.index') }}?category={{ $category->slug }}" class="group rounded-xl border border-zinc-200 p-6 transition hover:border-orange-200 hover:shadow-sm">
                        <h3 class="font-medium text-zinc-900 group-hover:text-orange-600">{{ $category->name }}</h3>
                        <p class="mt-1 text-xs text-zinc-400">{{ $category->products_count ?? 0 }} productos</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($featured->isNotEmpty())
        <section class="bg-zinc-50 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-zinc-900">Productos destacados</h2>
                <p class="mt-2 text-sm text-zinc-500">Los productos más relevantes de nuestro catálogo.</p>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featured as $product)
                        <a href="{{ route('public.products.show', $product) }}" class="group rounded-xl border border-zinc-200 bg-white p-4 transition hover:shadow-md">
                            <div class="aspect-square overflow-hidden rounded-lg bg-zinc-100">
                                @if ($product->main_image_path)
                                    <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition group-hover:scale-105" />
                                @else
                                    <div class="flex h-full items-center justify-center text-zinc-300 text-sm">Sin imagen</div>
                                @endif
                            </div>
                            <div class="mt-4">
                                <p class="text-xs text-orange-600 font-medium">{{ $product->brand?->name }}</p>
                                <h3 class="mt-1 font-semibold text-zinc-900 group-hover:text-orange-600">{{ $product->name }}</h3>
                                <p class="mt-1 text-xs text-zinc-500">{{ $product->active_ingredient }}</p>
                                @if ($product->approx_price)
                                    <p class="mt-2 text-sm font-bold">{{ $product->formatted_price }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($brands->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-zinc-900">Nuestras marcas</h2>
            <p class="mt-2 text-sm text-zinc-500">Conoce las marcas que distribuimos.</p>
            <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($brands as $brand)
                    <a href="{{ route('public.brands.show', $brand) }}" class="group flex flex-col items-center rounded-xl border border-zinc-200 p-8 transition hover:border-orange-200 hover:shadow-sm">
                        @if ($brand->logo_path)
                            <div class="mb-4 h-20 grayscale transition group-hover:grayscale-0">
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="h-full object-contain" />
                            </div>
                        @else
                            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-zinc-100 text-2xl font-bold text-zinc-400">{{ substr($brand->name, 0, 1) }}</div>
                        @endif
                        <h3 class="font-medium text-zinc-900 group-hover:text-orange-600">{{ $brand->name }}</h3>
                        <p class="mt-1 text-xs text-zinc-400">{{ $brand->products_count ?? 0 }} productos</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($branches->isNotEmpty())
        <section class="bg-zinc-900 py-20 text-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold">Nuestras oficinas</h2>
                <p class="mt-2 text-sm text-zinc-400">Estamos ubicados en las principales ciudades del país.</p>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($branches as $branch)
                        <div class="rounded-xl border border-zinc-700 p-6">
                            <h3 class="font-semibold text-orange-500">{{ $branch->name }}</h3>
                            <p class="mt-2 text-sm text-zinc-300">{{ $branch->city }}</p>
                            <p class="mt-1 text-xs text-zinc-400">{{ $branch->address }}</p>
                            @if ($branch->phone)
                                <p class="mt-2 text-xs text-zinc-400">Tel: {{ $branch->phone }}</p>
                            @endif
                            @if ($branch->email)
                                <p class="text-xs text-zinc-400">{{ $branch->email }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts::public>
