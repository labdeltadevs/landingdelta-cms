<x-layouts::public>
    @php
        $rawTitle = \App\Models\SiteSetting::get('work_with_us_title');
        $title = is_array($rawTitle) ? ($rawTitle['body'] ?? '') : $rawTitle;

        $rawDesc = \App\Models\SiteSetting::get('work_with_us_description');
        $description = is_array($rawDesc) ? ($rawDesc['body'] ?? '') : $rawDesc;

        $openings = \App\Models\JobOpening::query()->published()->ordered()->get();
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-900 py-20">
        <div class="absolute inset-0 opacity-[0.04]"
             style="background-image: radial-gradient(circle, #ff671f 1px, transparent 1px); background-size: 32px 32px;" />
        <div class="mx-auto max-w-4xl px-4 text-center relative z-10 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-white sm:text-4xl" data-aos="fade-up">{{ $title ?: 'Trabaja con nosotros' }}</h1>
            @if ($description)
                <p class="mt-3 text-sm text-zinc-300 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">{{ $description }}</p>
            @else
                <p class="mt-3 text-sm text-zinc-300 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">Únete al equipo de Laboratorios Delta S.A.</p>
            @endif
        </div>
    </section>

    {{-- Listado de ofertas --}}
    <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
        @if ($openings->isNotEmpty())
            <div class="grid gap-6">
                @foreach ($openings as $job)
                    <div class="rounded-xl border border-zinc-200 bg-white p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 hover:border-[#ff671f]/20"
                         data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                            @if ($job->image_url)
                                <div class="flex-shrink-0">
                                    <img src="{{ $job->image_url }}" alt="{{ $job->title }}" class="h-24 w-24 rounded-xl object-cover sm:h-28 sm:w-28" />
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h2 class="text-lg font-semibold text-zinc-900">{{ $job->title }}</h2>
                                <div class="mt-2 text-xs text-zinc-500 leading-relaxed whitespace-pre-line">{{ Str::limit($job->description, 300) }}</div>
                                <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-zinc-400">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                        {{ $job->valid_from->format('d/m/Y') }} — {{ $job->valid_until->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-8 text-center" data-aos="fade-up">
                <svg class="mx-auto h-10 w-10 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z"/></svg>
                <p class="mt-3 text-sm font-medium text-zinc-600">No hay convocatorias abiertas actualmente</p>
                <p class="mt-1 text-xs text-zinc-400">Síguenos en nuestras redes sociales para estar al tanto de nuevas oportunidades.</p>
            </div>
        @endif
    </section>
</x-layouts::public>
