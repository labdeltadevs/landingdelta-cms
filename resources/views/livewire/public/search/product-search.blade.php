<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <div class="relative">
        <flux:input
            wire:model.live.debounce.300ms="query"
            placeholder="Buscar productos..."
            class="w-full lg:w-96"
            @focus="open = true"
        />
    </div>

    @if (strlen($query) >= 2 && count($results) > 0)
        <div x-show="open" class="absolute z-50 mt-2 w-full rounded-xl border border-zinc-200 bg-white p-2 shadow-lg dark:border-zinc-700 dark:bg-zinc-800">
            <div class="space-y-1">
                @foreach ($results as $product)
                    <a href="{{ route('public.products.show', $product) }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700">
                        <div class="h-10 w-10 flex-shrink-0 rounded-md bg-zinc-100 dark:bg-zinc-700">
                            @if ($product->main_image_path)
                                <img src="{{ $product->main_image_url }}" class="h-full w-full rounded-md object-cover" />
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-medium">{{ $product->name }}</p>
                            <p class="truncate text-xs text-zinc-500">{{ $product->active_ingredient }}</p>
                        </div>
                        @if ($product->approx_price)
                            <span class="text-xs font-medium text-zinc-500">{{ $product->formatted_price }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
