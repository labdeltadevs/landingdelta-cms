<flux:heading>{{ $product?->exists ? 'Editar producto' : 'Nuevo producto' }}</flux:heading>
<flux:subheading>Completa los campos para {{ $product?->exists ? 'actualizar' : 'crear' }} el producto.</flux:subheading>

<form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
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

        <flux:field>
            <flux:label>Precio aproximado (Bs.)</flux:label>
            <flux:input wire:model="approx_price" type="number" step="0.01" placeholder="0.00" />
            <flux:error name="approx_price" />
        </flux:field>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <flux:field>
            <flux:label>Marca</flux:label>
            <flux:select wire:model="brand_id">
                <option value="">Sin marca</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </flux:select>
            <flux:error name="brand_id" />
        </flux:field>

        <flux:field>
            <flux:label>Categoría</flux:label>
            <flux:select wire:model="category_id">
                <option value="">Sin categoría</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </flux:select>
            <flux:error name="category_id" />
        </flux:field>
    </div>

    <flux:field>
        <flux:label>Descripción</flux:label>
        <flux:textarea wire:model="description" rows="4" placeholder="Descripción del producto..." />
        <flux:error name="description" />
    </flux:field>

    <flux:field>
        <flux:label>Imagen principal</flux:label>
        <flux:input type="file" wire:model="upload" accept="image/*" />
        <flux:error name="upload" />
        @if ($upload)
            <img src="{{ $upload->temporaryUrl() }}" class="mt-2 h-32 w-32 rounded-lg object-cover" />
        @elseif ($product?->main_image_path)
            <img src="{{ $product->main_image_url }}" class="mt-2 h-32 w-32 rounded-lg object-cover" />
        @endif
    </flux:field>

    <div class="flex items-center gap-6">
        <flux:switch wire:model="is_active" :checked="$is_active" label="Activo" />
        <flux:switch wire:model="is_featured" :checked="$is_featured" label="Destacado en homepage" />
    </div>

    <div class="flex items-center gap-4">
        <flux:button type="submit" variant="primary">{{ $product?->exists ? 'Actualizar' : 'Crear' }} producto</flux:button>
        <flux:button :href="route('admin.products.index')" wire:navigate variant="ghost">Cancelar</flux:button>
    </div>
</form>
