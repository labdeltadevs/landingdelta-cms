<x-layouts::app :title="__('Dashboard')">
    @php
        $stats = [
            ['label' => 'Productos', 'count' => \App\Models\Product::count(), 'icon' => 'shopping-cart'],
            ['label' => 'Marcas', 'count' => \App\Models\Brand::count(), 'icon' => 'building-storefront'],
            ['label' => 'Categorías', 'count' => \App\Models\Category::count(), 'icon' => 'folder'],
            ['label' => 'Sucursales', 'count' => \App\Models\Branch::count(), 'icon' => 'map-pin'],
            ['label' => 'Noticias', 'count' => \App\Models\News::count(), 'icon' => 'newspaper'],
            ['label' => 'Rotafolios', 'count' => \App\Models\Brochure::count(), 'icon' => 'document-text'],
            ['label' => 'Usuarios', 'count' => \App\Models\User::count(), 'icon' => 'users'],
            ['label' => 'Hero Slides', 'count' => \App\Models\HeroSlide::count(), 'icon' => 'photo'],
        ];
    @endphp

    <flux:heading>Dashboard</flux:heading>
    <flux:subheading>Resumen del contenido del CMS.</flux:subheading>

    <div class="mt-6 grid auto-rows-min gap-4 md:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-medium text-neutral-500">{{ $stat['label'] }}</h3>
                <p class="mt-2 text-3xl font-bold">{{ $stat['count'] }}</p>
            </div>
        @endforeach
    </div>

    @can('create content')
        <div class="mt-8 flex items-center gap-4">
            <flux:button :href="route('admin.products.create')" wire:navigate icon="plus">Nuevo producto</flux:button>
            <flux:button :href="route('admin.news.create')" wire:navigate icon="plus">Nueva noticia</flux:button>
        </div>
    @endcan
</x-layouts::app>
