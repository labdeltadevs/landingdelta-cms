@php
    $previewImageUrl = null;
    if ($upload && $upload instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
        $previewImageUrl = $upload->temporaryUrl();
    } elseif ($upload && is_string($upload)) {
        $previewImageUrl = Storage::disk('public')->url($upload);
    } elseif ($product?->main_image_path) {
        $previewImageUrl = $product->main_image_url;
    }

    $previewName = $name ?: ($product?->name ?? 'Nombre del producto');
    $previewIngredient = $active_ingredient ?: ($product?->active_ingredient ?? '');
    $previewDesc = \App\Support\VademecumHtml::build($vademecumRows) ?? '';
    $previewBrand = $brand_id ? $brands->firstWhere('id', $brand_id)?->name : null;
    $previewCategory = $category_id ? $categories->firstWhere('id', $category_id)?->name : null;
    $previewActive = $is_active;
    $previewFeatured = $is_featured;
    $previewInitial = $previewName ? strtoupper(substr($previewName, 0, 1)) : 'P';
@endphp

<div>
    <flux:heading>{{ $product?->exists ? 'Editar producto' : 'Nuevo producto' }}</flux:heading>
    <flux:subheading>Completa los campos para {{ $product?->exists ? 'actualizar' : 'crear' }} el producto.</flux:subheading>

    <div class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-5">
        {{-- FORMULARIO --}}
        <div class="lg:col-span-3">
            <form wire:submit.prevent="save" class="space-y-6">
                <flux:field>
                    <flux:label>Nombre del producto</flux:label>
                    <flux:input wire:model="name" placeholder="Ej: ACETAZOLAMIDA 250 mg" />
                    <flux:error name="name" />
                </flux:field>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Principio activo</flux:label>
                        <flux:input wire:model="active_ingredient" placeholder="Ej: Acetazolamida" />
                        <flux:error name="active_ingredient" />
                    </flux:field>

                </div>

                <div class="grid grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>División</flux:label>
                        <flux:select wire:model="brand_id">
                            <option value="">Sin división</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="brand_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Especialidad</flux:label>
                        <flux:select wire:model="category_id">
                            <option value="">Sin especialidad</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="category_id" />
                    </flux:field>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-4">
                    <div class="mb-3 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-zinc-900">Vademécum</p>
                            <p class="mt-0.5 text-xs text-zinc-500">
                                Secciones descriptivas del producto (Presentación, Composición, Posología...). Cada fila se guarda como un <code>&lt;tr&gt;</code> de la tabla HTML en la descripción.
                            </p>
                        </div>
                        <flux:button size="sm" variant="primary" wire:click="addVademecumRow" icon="plus" class="shrink-0">Agregar fila</flux:button>
                    </div>

                    <div class="space-y-3">
                        @forelse ($vademecumRows as $index => $row)
                            <div wire:key="vademecum-row-{{ $row['id'] }}" class="grid gap-2 rounded-lg border border-zinc-200 bg-zinc-50/50 p-3 sm:grid-cols-[200px_1fr_auto] sm:items-start">
                                <flux:input wire:model.live="vademecumRows.{{ $index }}.label" placeholder="Título (ej: Presentación)" />
                                <flux:textarea wire:model.live="vademecumRows.{{ $index }}.text" rows="3" placeholder="Contenido de la sección..." />
                                <flux:button size="sm" variant="danger" wire:click="removeVademecumRow({{ $index }})" icon="trash" title="Eliminar fila" class="justify-self-start sm:justify-self-auto" />
                            </div>
                        @empty
                            <p class="rounded-lg border border-dashed border-zinc-200 px-4 py-6 text-center text-sm text-zinc-400">
                                Sin secciones todavía. Usa «Agregar fila» para crear la primera.
                            </p>
                        @endforelse
                    </div>
                </div>

                <flux:field>
                    <flux:label>Imagen principal</flux:label>
                    <flux:input type="file" wire:model="upload" accept="image/*" />
                    <flux:error name="upload" />
                    @if ($previewImageUrl)
                        <img src="{{ $previewImageUrl }}" class="mt-2 h-32 w-32 rounded-lg object-cover" />
                    @endif
                </flux:field>

                <div class="flex items-center gap-6">
                    <flux:switch wire:model="is_active" :checked="$is_active" label="Activo" />
                    <flux:switch wire:model="is_featured" :checked="$is_featured" label="Destacado" />
                </div>

                <div class="flex items-center gap-4">
                    <flux:button type="submit" variant="primary">{{ $product?->exists ? 'Actualizar' : 'Crear' }} producto</flux:button>
                    <flux:button :href="route('admin.products.index')" wire:navigate variant="ghost">Cancelar</flux:button>
                </div>
            </form>
        </div>

        {{-- PREVIEW EN VIVO --}}
        <div class="lg:col-span-2 self-start">
            <div class="sticky top-24">
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-zinc-400">
                    👁 Vista previa
                </p>

                <div class="overflow-hidden rounded-2xl border bg-white/90 shadow-lg backdrop-blur-sm transition-all duration-300 {{ $previewActive ? 'border-[#ff671f]/20 shadow-[#ff671f]/5' : 'border-zinc-200 opacity-60' }}">
                    {{-- IMAGEN --}}
                    <div class="relative aspect-[16/9] w-full overflow-hidden bg-zinc-100">
                        @if ($previewImageUrl)
                            <img src="{{ $previewImageUrl }}" alt="{{ $previewName }}" class="h-full w-full object-cover" />
                        @else
                            <div class="flex h-full w-full items-center justify-center">
                                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#ff671f]/10 text-3xl font-bold text-[#ff671f]">
                                    {{ $previewInitial }}
                                </div>
                            </div>
                        @endif

                        {{-- BADGES --}}
                        <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                            @if (!$previewActive)
                                <span class="rounded-full bg-zinc-800/70 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
                                    Inactivo
                                </span>
                            @endif
                            @if ($previewFeatured)
                                <span class="rounded-full bg-[#ff671f]/80 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
                                    ★ Destacado
                                </span>
                            @endif
                        </div>

                    </div>

                    {{-- CONTENIDO --}}
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-zinc-900">{{ $previewName }}</h3>

                        @if ($previewIngredient)
                            <p class="mt-1 text-sm text-zinc-500">
                                <span class="font-medium text-zinc-700">Principio activo:</span> {{ $previewIngredient }}
                            </p>
                        @endif

                        @if ($previewBrand || $previewCategory)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @if ($previewBrand)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-[#ff671f]/10 px-3 py-1 text-xs font-medium text-[#ff671f]">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                        </svg>
                                        {{ $previewBrand }}
                                    </span>
                                @endif
                                @if ($previewCategory)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-600">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                        </svg>
                                        {{ $previewCategory }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if ($previewDesc)
                            <div class="mt-3 text-sm leading-relaxed text-zinc-500">
                                {!! $previewDesc !!}
                            </div>
                        @endif

                        <div class="mt-5">
                            <a href="#" class="inline-flex items-center gap-2 rounded-xl bg-[#ff671f] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#ff671f]/25 transition-all duration-300 hover:bg-[#e55a1a] hover:shadow-[#ff671f]/40 active:scale-95">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                Ver producto
                            </a>
                        </div>
                    </div>
                </div>

                <p class="mt-3 text-xs leading-relaxed text-zinc-400">
                    Así se verá el producto en la sección de productos del sitio web.
                </p>
            </div>
        </div>
    </div>
</div>