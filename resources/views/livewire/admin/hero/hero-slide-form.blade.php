<div>
    <flux:heading>{{ $heroSlide?->exists ? 'Editar slide' : 'Nuevo slide' }}</flux:heading>
    <flux:subheading>Configuración del aviso que se muestra al entrar al sitio.</flux:subheading>

    <form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
        <flux:field>
            <flux:label>Título</flux:label>
            <flux:input wire:model="title" placeholder="Título del slide" />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>Subtítulo</flux:label>
            <flux:input wire:model="subtitle" placeholder="Subtítulo opcional" />
            <flux:error name="subtitle" />
        </flux:field>

        <flux:field>
            <flux:label>Etiqueta</flux:label>
            <flux:input wire:model="tag" placeholder="Ej: Nuevo, Oferta, Alerta" />
            <flux:error name="tag" />
            <flux:description>Texto corto sobre el título. Vacío mostrará "Aviso".</flux:description>
        </flux:field>

        <flux:field>
            <flux:label>Imagen del aviso</flux:label>
            <flux:input type="file" wire:model="image" accept="image/*" />
            <flux:error name="image" />
            @if ($image && $image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
            @elseif ($image && is_string($image))
                <img src="{{ Storage::disk('public')->url($image) }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
            @elseif ($heroSlide?->image_path)
                <img src="{{ $heroSlide->image_url }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
            @endif
        </flux:field>

        <flux:field>
            <flux:label>Destino del botón</flux:label>
            <flux:radio.group wire:model.live="slideable_type">
                <flux:radio value="" label="Sin vínculo" />
                <flux:radio value="{{ \App\Models\Product::class }}" label="Producto" />
                <flux:radio value="{{ \App\Models\Brand::class }}" label="División" />
            </flux:radio.group>
            <flux:error name="slideable_type" />
            <flux:description>Elegí a qué página llevará el botón del aviso, sin necesidad de copiar URLs.</flux:description>
        </flux:field>

        @if ($slideable_type === \App\Models\Product::class || $slideable_type === \App\Models\Brand::class)
            <flux:field>
                <flux:label>{{ $slideable_type === \App\Models\Product::class ? 'Producto' : 'División' }}</flux:label>

                @if ($selectedDestination)
                    <div class="flex items-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-3 py-2.5">
                        <span class="min-w-0 flex-1 truncate text-sm font-medium text-zinc-800">
                            {{ $selectedDestination->name }}
                            @if ($selectedDestination instanceof \App\Models\Product && $selectedDestination->brand)
                                <span class="font-normal text-zinc-500">({{ $selectedDestination->brand->name }})</span>
                            @endif
                        </span>
                        <flux:button type="button" size="xs" variant="ghost" wire:click="clearDestination">
                            Cambiar
                        </flux:button>
                    </div>
                @else
                    <div x-data="{ open: false }" class="relative" @click.outside="open = false"
                        @keydown.escape.window="open = false">
                        <flux:input wire:model.live.debounce.300ms="destinationSearch" @focus="open = true"
                            placeholder="Escribí para buscar…" autocomplete="off" />
                        @if ($destinationResults->isNotEmpty())
                            <ul x-show="open" x-cloak
                                class="absolute z-20 mt-1 max-h-56 w-full overflow-auto rounded-xl border border-zinc-200 bg-white py-1 shadow-lg">
                                @foreach ($destinationResults as $result)
                                    <li>
                                        <button type="button"
                                            @click="$wire.selectDestination({{ $result->id }}); open = false"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-zinc-700 transition-colors hover:bg-zinc-100">
                                            <span class="min-w-0 flex-1 truncate font-medium">{{ $result->name }}</span>
                                            @if ($result instanceof \App\Models\Product && $result->brand)
                                                <span
                                                    class="shrink-0 text-xs text-zinc-400">{{ $result->brand->name }}</span>
                                            @endif
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <flux:description>
                        {{ $slideable_type === \App\Models\Product::class ? 'Mostrando hasta 8 productos. Escribí al menos 2 letras para filtrar.' : 'Elegí la división de la lista o escribí para filtrar.' }}
                    </flux:description>
                @endif
                <flux:error name="slideable_id" />
            </flux:field>
        @endif

        <flux:field>
            <flux:label>Texto del botón (CTA)</flux:label>
            <flux:input wire:model="cta_label" placeholder="Ej: Ver producto" />
            <flux:error name="cta_label" />
            <flux:description>Sin texto, el botón no aparecerá en el aviso aunque haya destino.</flux:description>
        </flux:field>

        <flux:field>
            <flux:label>Vista previa del enlace</flux:label>
            @if ($resolvedCtaUrl)
                <div class="flex items-center gap-2 rounded-xl border border-zinc-200 bg-zinc-50 px-3 py-2.5">
                    <span class="min-w-0 flex-1 truncate font-mono text-xs text-zinc-600">{{ $resolvedCtaUrl }}</span>
                    <a href="{{ $resolvedCtaUrl }}" target="_blank" rel="noopener"
                        class="shrink-0 text-xs font-semibold text-[#d95417] hover:underline">Abrir para probar</a>
                </div>
                @if (filled($cta_url))
                    <flux:description>Se usará tu URL personalizada en lugar del destino elegido.</flux:description>
                @endif
            @else
                <flux:description>Sin destino: el aviso se mostrará sin botón.</flux:description>
            @endif
        </flux:field>

        <div x-data="{ showCustom: @js(filled($cta_url)) }">
            <button type="button" @click="showCustom = !showCustom"
                class="text-sm font-medium text-zinc-500 underline decoration-dotted underline-offset-4 hover:text-zinc-800">
                URL personalizada o externa (opcional)
            </button>
            <div x-show="showCustom" x-cloak class="mt-3">
                <flux:field>
                    <flux:label>URL del CTA</flux:label>
                    <flux:input wire:model="cta_url" placeholder="Ej: https://wa.me/59171234567" />
                    <flux:error name="cta_url" />
                    <flux:description>Tiene prioridad sobre el destino elegido. Úsala para WhatsApp, redes u otras páginas.</flux:description>
                </flux:field>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <flux:field>
                <flux:label>Vigente desde</flux:label>
                <flux:input type="date" wire:model="valid_from" />
                <flux:error name="valid_from" />
            </flux:field>
            <flux:field>
                <flux:label>Vigente hasta</flux:label>
                <flux:input type="date" wire:model="valid_until" />
                <flux:error name="valid_until" />
            </flux:field>
        </div>
        <flux:description>Deja ambos campos vacíos para que el aviso esté siempre vigente.</flux:description>

        <flux:switch wire:model="is_active" :checked="$is_active" label="Activo" />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">{{ $heroSlide?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
            <flux:button :href="route('admin.hero.index')" wire:navigate variant="ghost">Cancelar</flux:button>
        </div>
    </form>
</div>