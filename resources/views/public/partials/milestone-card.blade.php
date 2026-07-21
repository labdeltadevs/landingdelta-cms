@props([
    'm' => [],
    'hasImage' => false,
    'iconSvg' => '',
])

<div class="relative overflow-hidden rounded-2xl border border-zinc-200/60 bg-white shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-1 hover:border-[#ff671f]/20 group/card">
    {{-- Top gradient bar --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#ff671f] via-[#ff671f]/40 to-transparent"></div>

    {{-- Content --}}
    <div class="p-5 sm:p-6">
        {{-- Year badge & title --}}
        <div class="flex flex-wrap items-center gap-3 mb-2">
            <span class="inline-flex items-center rounded-full bg-[#ff671f]/10 px-3 py-0.5 text-xs font-bold text-[#ff671f] tabular-nums ring-1 ring-[#ff671f]/10">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                </svg>
                {{ $m['year'] }}
            </span>
            <h3 class="text-base font-semibold text-zinc-900 group-hover/card:text-[#ff671f] transition-colors">{{ $m['title'] }}</h3>
        </div>

        {{-- Description --}}
        @if ($m['desc'] ?? false)
            <p class="text-sm text-zinc-500 leading-relaxed mt-2">
                {{ $m['desc'] }}
            </p>
        @endif

        {{-- Image --}}
        @if ($hasImage)
            <div class="mt-4 -mx-1 overflow-hidden rounded-xl border border-zinc-100 bg-zinc-50 transition-all duration-500 group-hover/card:shadow-md group-hover/card:border-[#ff671f]/10"
                 x-data="{ loaded: true }"
                 x-show="loaded">
                <img src="{{ $m['image'] }}"
                     alt="Hito {{ $m['year'] }} — {{ $m['title'] }}"
                     loading="lazy"
                     class="w-full h-40 sm:h-48 object-cover transition-all duration-700 group-hover/card:scale-105"
                     @@error="loaded = false">
            </div>
        @endif
    </div>

    {{-- Decorative corner accent --}}
    <div class="absolute -bottom-6 -right-6 h-12 w-12 rounded-full bg-[#ff671f]/5 transition-all duration-500 group-hover/card:scale-[3] group-hover/card:bg-[#ff671f]/10"></div>
</div>
