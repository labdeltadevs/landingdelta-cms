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
            <flux:label>Vincular destino</flux:label>
            <flux:select wire:model.live="slideable_type">
                <option value="">Sin vínculo</option>
                <option value="{{ \App\Models\Product::class }}">Producto</option>
                <option value="{{ \App\Models\Brand::class }}">Marca</option>
            </flux:select>
            <flux:error name="slideable_type" />
            <flux:description>Si eliges un producto o marca, el botón enlazará automáticamente a su página (a menos que escribas una URL manual).</flux:description>
        </flux:field>

        @if ($slideable_type === \App\Models\Product::class)
            <flux:field>
                <flux:label>Producto</flux:label>
                <flux:select wire:model="slideable_id">
                    <option value="">Seleccionar producto</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="slideable_id" />
            </flux:field>
        @elseif ($slideable_type === \App\Models\Brand::class)
            <flux:field>
                <flux:label>Marca</flux:label>
                <flux:select wire:model="slideable_id">
                    <option value="">Seleccionar marca</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="slideable_id" />
            </flux:field>
        @endif

        <div class="grid grid-cols-2 gap-4">
            <flux:field>
                <flux:label>Texto del botón (CTA)</flux:label>
                <flux:input wire:model="cta_label" placeholder="Ej: Ver producto" />
                <flux:error name="cta_label" />
            </flux:field>
            <flux:field>
                <flux:label>URL del CTA</flux:label>
                <flux:input wire:model="cta_url" placeholder="Ej: /productos" />
                <flux:error name="cta_url" />
            </flux:field>
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