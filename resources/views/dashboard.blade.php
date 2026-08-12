<x-layouts::app :title="__('Dashboard')">
    @php
        $sections = [
            ['label' => 'Productos', 'model' => \App\Models\Product::class, 'perm' => 'view.products', 'icon' => 'shopping-cart'],
            ['label' => 'Marcas', 'model' => \App\Models\Brand::class, 'perm' => 'view.brands', 'icon' => 'building-storefront'],
            ['label' => 'Categorías', 'model' => \App\Models\Category::class, 'perm' => 'view.categories', 'icon' => 'folder'],
            ['label' => 'Sucursales', 'model' => \App\Models\Branch::class, 'perm' => 'view.branches', 'icon' => 'map-pin'],
            ['label' => 'Noticias', 'model' => \App\Models\News::class, 'perm' => 'view.news', 'icon' => 'newspaper'],
            ['label' => 'Rotafolios', 'model' => \App\Models\Brochure::class, 'perm' => 'view.brochures', 'icon' => 'document-text'],
            ['label' => 'Usuarios', 'model' => \App\Models\User::class, 'perm' => 'view.users', 'icon' => 'users'],
            ['label' => 'Hero Slides', 'model' => \App\Models\HeroSlide::class, 'perm' => 'view.hero', 'icon' => 'photo'],
        ];

        $stats = [];
        foreach ($sections as $section) {
            if (auth()->user()->can($section['perm'])) {
                $stats[] = [
                    'label' => $section['label'],
                    'count' => $section['model']::count(),
                    'icon' => $section['icon'],
                ];
            }
        }
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

    @can('edit.products')
        <div class="mt-8 flex items-center gap-4">
            <flux:button :href="route('admin.products.create')" wire:navigate icon="plus">Nuevo producto</flux:button>
            @can('edit.news')
                <flux:button :href="route('admin.news.create')" wire:navigate icon="plus">Nueva noticia</flux:button>
            @endcan
        </div>
    @elseif (auth()->user()->hasAnyPermission(['view.products', 'view.news', 'view.brands', 'view.categories', 'view.branches', 'view.hero', 'view.brochures', 'view.job-openings', 'view.users', 'view.settings']))
        <div class="mt-8">
            <flux:text class="text-zinc-500">Tienes acceso de solo lectura a las secciones del panel.</flux:text>
        </div>
    @endcan
</x-layouts::app>
