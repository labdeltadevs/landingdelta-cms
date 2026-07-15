<flux:heading>Reordenar slides</flux:heading>
<flux:subheading>Arrastra los slides para cambiar su orden en el carrusel.</flux:subheading>

<div class="mt-6 max-w-xl space-y-2" x-data="{ order: @entangle('order') }">
    <template x-for="(id, index) in order" :key="id">
        <div class="flex items-center gap-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-3 cursor-grab"
             x-sortable-item="id"
             :style="'order: ' + index">
            <flux:icon name="grip-vertical" class="size-4 text-zinc-400" />
            <span class="text-sm font-medium" x-text="'Slide #' + (index + 1)"></span>
        </div>
    </template>

    <div class="mt-4">
        <flux:button wire:click="updateOrder(order)" variant="primary">Guardar orden</flux:button>
        <flux:button :href="route('admin.hero.index')" wire:navigate variant="ghost">Volver</flux:button>
    </div>
</div>