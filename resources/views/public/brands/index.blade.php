<x-layouts::public>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-zinc-900">Marcas</h1>
        <p class="mt-2 text-sm text-zinc-500">Conoce las marcas que distribuimos.</p>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($brands as $brand)
                <a href="{{ route('public.brands.show', $brand) }}" class="group flex flex-col items-center rounded-xl border border-zinc-200 p-8 transition hover:border-orange-200 hover:shadow-sm">
                    @if ($brand->logo_path)
                        <div class="mb-4 h-24 grayscale transition group-hover:grayscale-0">
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="h-full object-contain" />
                        </div>
                    @else
                        <div class="mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-zinc-100 text-3xl font-bold text-zinc-400">{{ substr($brand->name, 0, 1) }}</div>
                    @endif
                    <h3 class="font-semibold text-zinc-900 group-hover:text-orange-600">{{ $brand->name }}</h3>
                </a>
            @empty
                <div class="col-span-full py-20 text-center text-zinc-400">No hay marcas registradas.</div>
            @endforelse
        </div>
    </div>
</x-layouts::public>
