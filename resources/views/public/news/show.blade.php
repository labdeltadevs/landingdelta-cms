@php
    $newsSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $news->title,
        'description' => (string) Str::of(strip_tags($news->body ?? ''))->limit(300)->trim(),
    ];
    if ($news->published_at) {
        $newsSchema['datePublished'] = $news->published_at->toIso8601String();
    }
    if ($news->updated_at) {
        $newsSchema['dateModified'] = $news->updated_at->toIso8601String();
    }
    $newsSchema['image'] = $news->cover_image_path
        ? Storage::disk('public')->url($news->cover_image_path)
        : Storage::disk('public')->url('logo_delta.png');
    $newsSchema['author'] = [
        '@type' => 'Organization',
        'name' => 'Laboratorios Delta S.A.',
        'url' => config('app.url'),
    ];
    $newsSchema['publisher'] = [
        '@type' => 'Organization',
        'name' => 'Laboratorios Delta S.A.',
        'url' => config('app.url'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => Storage::disk('public')->url('logo_delta.png'),
        ],
    ];
    $newsJsonLd = json_encode($newsSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp
<x-layouts::public
    metaTitle="{{ $news->title }}"
    metaDescription="{{ Str::of(strip_tags($news->body ?? ''))->limit(160)->trim() }}"
    :jsonLd="$newsJsonLd">
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
                <img src="{{ $news->cover_image_url }}" alt="{{ $news->title }}" loading="lazy" class="mt-6 w-full rounded-xl object-cover aspect-video" />
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
