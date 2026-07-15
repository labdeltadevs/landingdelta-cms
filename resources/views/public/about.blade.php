<x-layouts::public>
    @php
        $history = \App\Models\SiteSetting::get('about_history');
        $mission = \App\Models\SiteSetting::get('about_mission');
        $vision = \App\Models\SiteSetting::get('about_vision');
        $values = \App\Models\SiteSetting::get('about_values');
        $quality = \App\Models\SiteSetting::get('about_quality_policy');
    @endphp
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-zinc-900">Nosotros</h1>
        @if ($history)
            <section class="mt-8">
                <h2 class="text-xl font-semibold text-zinc-900">{{ $history['title'] ?? 'Nuestra historia' }}</h2>
                <div class="mt-4 text-sm text-zinc-600 leading-relaxed whitespace-pre-line">{{ $history['body'] ?? $history }}</div>
            </section>
        @endif
        @if ($mission)
            <section class="mt-8">
                <h2 class="text-xl font-semibold text-zinc-900">{{ $mission['title'] ?? 'Misión' }}</h2>
                <p class="mt-4 text-sm text-zinc-600 leading-relaxed">{{ $mission['body'] ?? $mission }}</p>
            </section>
        @endif
        @if ($vision)
            <section class="mt-8">
                <h2 class="text-xl font-semibold text-zinc-900">{{ $vision['title'] ?? 'Visión' }}</h2>
                <p class="mt-4 text-sm text-zinc-600 leading-relaxed">{{ $vision['body'] ?? $vision }}</p>
            </section>
        @endif
        @if ($values)
            <section class="mt-8">
                <h2 class="text-xl font-semibold text-zinc-900">{{ $values['title'] ?? 'Valores' }}</h2>
                <p class="mt-4 text-sm text-zinc-600 leading-relaxed">{{ $values['body'] ?? $values }}</p>
                @if ($values['quote'] ?? null)
                    <blockquote class="mt-4 border-l-4 border-orange-500 pl-4 italic text-zinc-500">{{ $values['quote'] }}</blockquote>
                @endif
            </section>
        @endif
        @if ($quality)
            <section class="mt-8">
                <h2 class="text-xl font-semibold text-zinc-900">{{ $quality['title'] ?? 'Política de Calidad' }}</h2>
                <p class="mt-4 text-sm text-zinc-600 leading-relaxed">{{ $quality['body'] ?? $quality }}</p>
            </section>
        @endif
    </div>
</x-layouts::public>
