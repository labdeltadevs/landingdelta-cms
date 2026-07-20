<div class="relative" x-data="{ open: false }" @click.away="open = false">
    {{-- Compact search icon button --}}
    <button @click="open = !open"
            class="flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 transition-all hover:border-[#ff671f]/30 hover:text-[#ff671f] hover:shadow-sm"
            :class="open ? 'border-[#ff671f]/30 text-[#ff671f]' : ''">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 top-full mt-2 w-80 origin-top-right rounded-xl border border-zinc-200 bg-white shadow-lg z-50 overflow-hidden"
         @keydown.escape.window="open = false">
        {{-- Input --}}
        <div class="relative border-b border-zinc-100">
            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
            </svg>
            <input wire:model.live.debounce.300ms="query"
                   type="text"
                   placeholder="Buscar productos..."
                   class="w-full border-0 bg-transparent py-3 pl-10 pr-4 text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-0" />
        </div>

        {{-- Results --}}
        <div class="max-h-80 overflow-y-auto">
            @if (strlen($query ?? '') > 0 && strlen($query ?? '') < 2)
                <div class="px-4 py-6 text-center text-xs text-zinc-400">
                    Escribe al menos 2 caracteres para buscar...
                </div>
            @elseif (strlen($query ?? '') >= 2 && count($results ?? []) === 0)
                <div class="px-4 py-6 text-center text-xs text-zinc-400">
                    No se encontraron productos para "{{ $query }}"
                </div>
            @elseif (count($results ?? []) > 0)
                <div class="divide-y divide-zinc-100">
                    @foreach ($results as $product)
                        <a href="{{ route('public.products.show', $product) }}"
                           class="flex items-center gap-3 px-4 py-3 transition hover:bg-zinc-50"
                           @click="open = false">
                            <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-lg bg-zinc-100">
                                @if ($product->main_image_path)
                                    <img src="{{ $product->main_image_url }}" class="h-full w-full object-cover" />
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-zinc-900">{{ $product->name }}</p>
                                <p class="truncate text-xs text-zinc-500">{{ $product->active_ingredient }}</p>
                            </div>
                            @if ($product->approx_price)
                                <span class="flex-shrink-0 text-xs font-medium text-zinc-500">{{ $product->formatted_price }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <div class="px-4 py-6 text-center text-xs text-zinc-400">
                    <svg class="mx-auto h-6 w-6 text-zinc-300 mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    Busca por nombre o ingrediente activo
                </div>
            @endif
        </div>
    </div>
</div>
