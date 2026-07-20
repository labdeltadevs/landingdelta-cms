<div>
    <flux:heading>{{ $news?->exists ? 'Editar noticia' : 'Nueva noticia' }}</flux:heading>
    <flux:subheading>Completa los datos de la noticia.</flux:subheading>

    <form wire:submit.prevent="save" class="mt-6 space-y-6 max-w-2xl">
        <flux:field>
            <flux:label>Título</flux:label>
            <flux:input wire:model="title" placeholder="Título de la noticia" />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>Extracto</flux:label>
            <flux:textarea wire:model="excerpt" rows="2" placeholder="Breve extracto o resumen..." />
            <flux:error name="excerpt" />
        </flux:field>

        <flux:field>
            <flux:label>Cuerpo (Markdown)</flux:label>
            <flux:textarea wire:model="body" rows="10" placeholder="Contenido de la noticia en formato Markdown..." />
            <flux:error name="body" />
        </flux:field>

        <div class="grid grid-cols-2 gap-4">
            <flux:field>
                <flux:label>Fecha de publicación</flux:label>
                <flux:input wire:model="published_at" type="datetime-local" />
                <flux:error name="published_at" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>Imagen de portada</flux:label>
            <flux:input type="file" wire:model="cover" accept="image/*" />
            <flux:error name="cover" />
            @if ($cover && $cover instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                <img src="{{ $cover->temporaryUrl() }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
            @elseif ($cover && is_string($cover))
                <img src="{{ Storage::disk('public')->url($cover) }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
            @elseif ($news?->cover_image_path)
                <img src="{{ $news->cover_image_url }}" class="mt-2 h-40 w-full rounded-lg object-cover" />
            @endif
        </flux:field>

        <flux:switch wire:model="is_active" :checked="$is_active" label="Activa" />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">{{ $news?->exists ? 'Actualizar' : 'Crear' }}</flux:button>
            <flux:button :href="route('admin.news.index')" wire:navigate variant="ghost">Cancelar</flux:button>
        </div>
    </form>
</div>