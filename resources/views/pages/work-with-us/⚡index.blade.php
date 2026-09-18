<?php

use App\Models\JobOpening;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.public-page')] #[Title('Trabaja con Nosotros')] class extends Component {
    use WithPagination;

    #[Url]
    public string $search = '';

    public function mount(): void
    {
        view()->share('metaTitle', 'Trabaja con Nosotros');
        view()->share('metaDescription', 'Únete al equipo de Laboratorios Delta S.A. — Ve nuestras convocatorias laborales vigentes y forma parte de la empresa farmacéutica líder en Bolivia.');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}; ?>

<div>
    @php
        $rawTitle = SiteSetting::get('work_with_us_title');
        $title = is_array($rawTitle) ? $rawTitle['body'] ?? '' : $rawTitle;

        $rawDesc = SiteSetting::get('work_with_us_description');
        $description = is_array($rawDesc) ? $rawDesc['body'] ?? '' : $rawDesc;

        $openings = JobOpening::query()
            ->published()
            ->when($this->search !== '', fn ($query) => $query->where('title', 'like', '%'.$this->search.'%'))
            ->orderByDesc('created_at')
            ->paginate(12);
    @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-white py-12 sm:py-12">
        {{-- Patrón de puntos sutil --}}
        <div class="absolute inset-0 opacity-[0.035]"
            style="background-image: radial-gradient(circle, #000000 1px, transparent 1px); background-size: 28px 28px;">
        </div>

        {{-- Blob decorativo naranja sutil --}}
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-[#ff671f]/[0.03] blur-3xl pointer-events-none">
        </div>

        <div class="mx-auto max-w-4xl px-4 text-center relative z-10 sm:px-6 lg:px-8">
            {{-- Línea decorativa --}}
            <div class="flex items-center justify-center gap-3 mb-6" data-aos="fade-up">
                <div class="h-px w-8 bg-black/10"></div>
                <div class="w-2 h-2 bg-[#ff671f] rounded-full"></div>
                <div class="h-px w-8 bg-black/10"></div>
            </div>

            <h1 class="text-xl font-black text-black sm:text-5xl tracking-tight leading-tight" data-aos="fade-up"
                data-aos-delay="50">
                {{ $title ?: 'Trabaja con nosotros' }}
            </h1>

            @if ($description)
                <p class="mt-5 text-base text-black/50 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up"
                    data-aos-delay="100">
                    {{ $description }}
                </p>
            @else
                <p class="mt-5 text-base text-black/50 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up"
                    data-aos-delay="100">
                    Únete al equipo de LABORATORIOS DELTA S.A. y sé parte de un proyecto que impacta la salud de miles
                    de personas.
                </p>
            @endif

            {{-- Stats decorativas --}}
            <div class="mt-10 flex items-center justify-center gap-8 sm:gap-12" data-aos="fade-up" data-aos-delay="150">
                <div class="w-px h-8 bg-black/10"></div>
                <div class="text-center">
                    <p class="text-2xl sm:text-3xl font-black text-black">35+</p>
                    <p class="text-xs text-black/40 mt-1">Años de trayectoria</p>
                </div>
                <div class="w-px h-8 bg-black/10"></div>
                <div class="text-center">
                    <p class="text-2xl sm:text-3xl font-black text-[#ff671f]">100%</p>
                    <p class="text-xs text-black/40 mt-1">Compromiso</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Listado de ofertas --}}
    <section class="bg-zinc-50/50 border-t border-black/5">
        <div class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">

            {{-- Header de sección --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-10" data-aos="fade-up">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-6 bg-[#ff671f] rounded-full"></div>
                    <h2 class="text-xl font-bold text-black tracking-tight">Convocatorias abiertas</h2>
                    @if ($openings->total() > 0)
                        <span
                            class="inline-flex items-center rounded-full bg-black px-2.5 py-0.5 text-xs font-medium text-white">
                            {{ $openings->total() }}
                        </span>
                    @endif
                </div>
                <input type="search" wire:model.live.debounce.300ms="search" placeholder="Buscar convocatoria…"
                    class="w-full sm:max-w-xs rounded-full border border-zinc-200 bg-white px-4 py-2.5 text-sm text-zinc-800 placeholder-zinc-400 shadow-sm focus:border-[#ff671f]/50 focus:outline-none focus:ring-4 focus:ring-[#ff671f]/10" />
            </div>

            @if ($openings->isNotEmpty())
                <div class="grid gap-5">
                    @foreach ($openings as $job)
                        <div class="group relative bg-white rounded-xl border border-black/8 overflow-hidden
                                    transition-all duration-500 ease-out
                                    hover:border-black/20 hover:shadow-[0_8px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5"
                            data-aos="fade-up" data-aos-delay="{{ min($loop->index * 80, 400) }}">

                            <div class="flex flex-col sm:flex-row">
                                {{-- Imagen lateral (desktop) / superior (mobile) --}}
                                @if ($job->image_url)
                                    <div class="sm:w-56 sm:flex-shrink-0 relative overflow-hidden">
                                        <img src="{{ $job->image_url }}" alt="{{ $job->title }}" loading="lazy"
                                            class="h-48 sm:h-full w-full sm:w-56 object-cover transition-transform duration-700 ease-out group-hover:scale-105" />
                                        <div
                                            class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-500">
                                        </div>
                                    </div>
                                @endif

                                {{-- Contenido --}}
                                <div class="flex-1 p-5 sm:p-6 relative">
                                    {{-- Línea decorativa naranja --}}
                                    <div class="absolute top-0 left-0 right-0 h-px bg-black/5">
                                        <div
                                            class="h-full bg-[#ff671f] w-0 group-hover:w-full transition-all duration-500 ease-out">
                                        </div>
                                    </div>

                                    {{-- Badges superiores --}}
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        <span
                                            class="inline-flex items-center gap-1 rounded-lg bg-black/5 px-2.5 py-1 text-[10px] font-medium text-black/60 border border-black/5">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                            </svg>
                                            {{ $job->valid_from->format('d/m/Y') }} —
                                            {{ $job->valid_until->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    {{-- Título --}}
                                    <h3 class="text-lg font-bold text-black leading-snug">
                                        <a href="{{ route('public.work-with-us.show', $job) }}"
                                            wire:navigate
                                            class="transition-colors duration-300 group-hover:text-[#ff671f]">
                                            {{ $job->title }}
                                        </a>
                                    </h3>

                                    {{-- Descripción --}}
                                    <div class="mt-2 text-sm text-black/50 leading-relaxed line-clamp-3">
                                        {{ Str::limit($job->description, 280) }}
                                    </div>

                                    {{-- Footer con CTA --}}
                                    <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                                        <div class="flex items-center gap-2 text-xs text-black/40">
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                Publicado {{ $job->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('public.work-with-us.show', $job) }}"
                                                wire:navigate
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 px-4 py-2.5 text-xs font-semibold text-zinc-700 transition-all duration-300 hover:border-[#ff671f]/40 hover:text-[#ff671f]">
                                                Ver convocatoria
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                </svg>
                                            </a>
                                            @if ($job->application_email)
                                                <a href="mailto:{{ $job->application_email }}?subject=Postulación: {{ $job->title }}"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-black px-5 py-2.5 text-xs font-semibold text-white transition-all duration-300 hover:bg-[#ff671f] active:scale-[0.98]">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                                    </svg>
                                                    Enviar CV
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Borde inferior naranja --}}
                            <div
                                class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#ff671f] scale-x-0 group-hover:scale-x-100 transition-transform duration-500 ease-out origin-left">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $openings->links() }}
                </div>
            @else
                {{-- Estado vacío mejorado --}}
                <div class="bg-white rounded-2xl border border-black/8 p-12 text-center" data-aos="fade-up">
                    <div
                        class="w-16 h-16 mx-auto rounded-2xl bg-zinc-50 border border-black/5 flex items-center justify-center mb-5">
                        <svg class="h-7 w-7 text-black/20" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-black mb-2">No hay convocatorias abiertas</h3>
                    <p class="text-sm text-black/40 max-w-md mx-auto mb-6">
                        {{ $this->search !== '' ? 'Ninguna convocatoria coincide con tu búsqueda.' : 'En este momento no tenemos ofertas activas.' }}
                    </p>
                </div>
            @endif
        </div>
    </section>
</div>
