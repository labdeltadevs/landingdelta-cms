<div>
<flux:heading>Noticias</flux:heading>
<flux:subheading>Gestión de noticias y comunicados.</flux:subheading>

<div class="mt-6 flex items-center justify-between gap-4">
    <flux:input wire:model.live.debounce.300ms="search" placeholder="Buscar noticias..." class="max-w-sm" />
    @can('manage news', App\Models\News::class)
        <flux:button :href="route('admin.news.create')" wire:navigate icon="plus">Nueva noticia</flux:button>
    @endcan
</div>

<div class="mt-4 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
        <thead class="bg-zinc-50 dark:bg-zinc-800">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Título</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-zinc-500">Publicado</th>
                <th class="px-4 py-3 text-center text-sm font-medium text-zinc-500">Activo</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
            @forelse ($news as $item)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-sm font-medium">{{ $item->title }}</td>
                    <td class="px-4 py-3 text-sm text-zinc-500">{{ $item->published_at?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center gap-1 rounded-full bg-{{ $item->is_active ? 'green' : 'zinc' }}-100 px-2 py-0.5 text-xs font-medium text-{{ $item->is_active ? 'green' : 'zinc' }}-700">{{ $item->is_active ? 'Sí' : 'No' }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <flux:dropdown align="end">
                            <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />
                            <flux:menu>
                                <flux:menu.item :href="route('admin.news.edit', $item)" wire:navigate icon="pencil">Editar</flux:menu.item>
                                <flux:menu.item wire:click="delete({{ $item->id }})" icon="trash" variant="danger">Eliminar</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-sm text-zinc-400">No hay noticias registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $news->links() }}</div>
</div>