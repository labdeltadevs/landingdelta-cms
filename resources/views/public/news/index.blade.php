<x-layouts::public
    metaTitle="Noticias"
    metaDescription="Últimas noticias, comunicados y novedades de Laboratorios Delta S.A. — Mantenete informado sobre la industria farmacéutica boliviana.">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-zinc-900">Noticias</h1>
        <p class="mt-2 text-sm text-zinc-500">Últimas noticias y comunicados de Laboratorios Delta S.A.</p>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($news as $item)
                <a href="{{ route('public.news.show', $item) }}" class="group rounded-xl border border-zinc-200 overflow-hidden transition hover:shadow-md">
                    @if ($item->cover_image_path)
                        <div class="aspect-video overflow-hidden bg-zinc-100">
                            <img src="{{ $item->cover_image_url }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition group-hover:scale-105" />
                        </div>
                    @endif
                    <div class="p-5">
                        <p class="text-xs text-zinc-400">{{ $item->published_at?->format('d/m/Y') }}</p>
                        <h3 class="mt-1 font-semibold text-zinc-900 group-hover:text-orange-600">{{ $item->title }}</h3>
                        @if ($item->excerpt)
                            <p class="mt-2 text-sm text-zinc-500 line-clamp-2">{{ $item->excerpt }}</p>
                        @endif
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center text-zinc-400">No hay noticias publicadas.</div>
            @endforelse
        </div>

        <div class="mt-8">{{ $news->links() }}</div>
    </div>
</x-layouts::public>
