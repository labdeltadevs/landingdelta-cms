<x-layouts::public>
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <nav class="mb-8 text-sm text-zinc-500">
            <a href="{{ route('public.home') }}" class="hover:text-zinc-900">Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('public.news.index') }}" class="hover:text-zinc-900">Noticias</a>
            <span class="mx-2">/</span>
            <span class="text-zinc-900">{{ $news->title }}</span>
        </nav>

        <article>
            <p class="text-sm text-zinc-400">{{ $news->published_at?->format('d/m/Y') }}</p>
            <h1 class="mt-2 text-3xl font-bold text-zinc-900">{{ $news->title }}</h1>

            @if ($news->cover_image_path)
                <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}" class="mt-6 w-full rounded-xl object-cover aspect-video" />
            @endif

            @if ($news->excerpt)
                <p class="mt-6 text-lg text-zinc-600 leading-relaxed">{{ $news->excerpt }}</p>
            @endif

            @if ($news->body)
                <div class="mt-6 prose prose-sm max-w-none text-zinc-600 leading-relaxed whitespace-pre-line">
                    {{ $news->body }}
                </div>
            @endif
        </article>
    </div>
</x-layouts::public>
