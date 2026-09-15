<?php

use App\Livewire\Admin\Branches\BranchForm;
use App\Livewire\Admin\Branches\BranchIndex;
use App\Livewire\Admin\Brands\BrandForm;
use App\Livewire\Admin\Brands\BrandIndex;
use App\Livewire\Admin\Brochures\BrochureForm;
use App\Livewire\Admin\Brochures\BrochureIndex;
use App\Livewire\Admin\Categories\CategoryForm;
use App\Livewire\Admin\Categories\CategoryIndex;
use App\Livewire\Admin\Hero\HeroSlideForm;
use App\Livewire\Admin\Hero\HeroSlideIndex;
use App\Livewire\Admin\Hero\HeroSlideReorder;
use App\Livewire\Admin\JobOpenings\JobOpeningForm;
use App\Livewire\Admin\JobOpenings\JobOpeningIndex;
use App\Livewire\Admin\News\NewsForm;
use App\Livewire\Admin\News\NewsIndex;
use App\Livewire\Admin\Products\ProductForm;
use App\Livewire\Admin\Products\ProductIndex;
use App\Livewire\Admin\Settings\SettingsForm;
use App\Livewire\Admin\Users\UserForm;
use App\Livewire\Admin\Users\UserIndex;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Brochure;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

// === SEO: Robots & Sitemap ===

Route::get('/robots.txt', function () {
    $content = 'User-agent: *'.PHP_EOL;
    $content .= 'Allow: /'.PHP_EOL;
    $content .= PHP_EOL;
    $content .= 'Sitemap: '.config('app.url').'/sitemap.xml'.PHP_EOL;

    return response($content, 200, ['Content-Type' => 'text/plain']);
});

Route::get('/sitemap.xml', function () {
    $staticPages = [
        ['loc' => route('public.home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
        ['loc' => route('public.products.index'), 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['loc' => route('public.brands.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
        ['loc' => route('public.brochures.index'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['loc' => route('public.about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => route('public.contact'), 'priority' => '0.6', 'changefreq' => 'yearly'],
        ['loc' => route('public.work-with-us'), 'priority' => '0.6', 'changefreq' => 'weekly'],
        ['loc' => route('public.news.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
    ];

    $products = Product::query()->active()->get()->map(fn ($p) => [
        'loc' => route('public.products.show', $p),
        'priority' => '0.7',
        'changefreq' => 'monthly',
        'lastmod' => $p->updated_at->toIso8601String(),
    ]);

    $brands = Brand::query()->active()->get()->map(fn ($b) => [
        'loc' => route('public.brands.show', $b),
        'priority' => '0.6',
        'changefreq' => 'monthly',
    ]);

    $news = News::query()->published()->get()->map(fn ($n) => [
        'loc' => route('public.news.show', $n),
        'priority' => '0.6',
        'changefreq' => 'monthly',
        'lastmod' => $n->updated_at->toIso8601String(),
    ]);

    $urls = collect($staticPages)
        ->concat($products)
        ->concat($brands)
        ->concat($news);

    $content = '<?xml version="1.0" encoding="UTF-8"?>';
    $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    foreach ($urls as $url) {
        $content .= '<url>';
        $content .= '<loc>'.e($url['loc']).'</loc>';
        $content .= '<priority>'.$url['priority'].'</priority>';
        $content .= '<changefreq>'.$url['changefreq'].'</changefreq>';
        if (isset($url['lastmod'])) {
            $content .= '<lastmod>'.$url['lastmod'].'</lastmod>';
        }
        $content .= '</url>';
    }

    $content .= '</urlset>';

    return response($content, 200, ['Content-Type' => 'application/xml']);
});

// === Public Routes ===

Route::view('/', 'public.home')->name('public.home');

Route::get('/productos', function () {
    $query = Product::query()->active()->with('brand', 'category')->orderBy('name');

    $selectedCategory = null;
    $categorySlug = request('category');
    if ($categorySlug) {
        $selectedCategory = Category::query()->where('slug', $categorySlug)->first();
        if ($selectedCategory) {
            $query->where('category_id', $selectedCategory->id);
        }
    }

    $searchTerm = request('q');
    if ($searchTerm) {
        $like = '%'.$searchTerm.'%';
        $query->where(function ($q) use ($like) {
            $q->where('name', 'like', $like)
                ->orWhere('active_ingredient', 'like', $like)
                ->orWhere('description', 'like', $like);
        });
    }

    return view('public.products.index', [
        'products' => $query->ordered()->paginate(12),
        'categories' => Category::query()->active()->ordered()->withCount(['products' => function ($q) {
            $q->active();
        }])->get(),
        'selectedCategory' => $selectedCategory,
    ]);
})->name('public.products.index');

Route::get('/productos/{product:slug}', function (Product $product) {
    return view('public.products.show', compact('product'));
})->name('public.products.show');

Route::get('/marcas', function () {
    return view('public.brands.index', [
        'brands' => Brand::query()->active()->ordered()->get(),
    ]);
})->name('public.brands.index');

Route::get('/marcas/{brand:slug}', function (Brand $brand) {
    return view('public.brands.show', [
        'brand' => $brand,
        'products' => $brand->products()->active()->get(),
    ]);
})->name('public.brands.show');

Route::get('/nosotros', function () {
    $history = SiteSetting::get('about_history');
    $mission = SiteSetting::get('about_mission');
    $vision = SiteSetting::get('about_vision');
    $values = SiteSetting::get('about_values');
    $quality = SiteSetting::get('about_quality_policy');
    $milestonesRaw = SiteSetting::get('about_milestones', '[]');
    $milestonesDecoded = is_string($milestonesRaw) ? json_decode($milestonesRaw, true) : $milestonesRaw;
    $milestones = is_array($milestonesDecoded) ? $milestonesDecoded : [];

    $yearsActive = now()->year - 1987;

    return view('public.about', [
        'history' => $history,
        'mission' => $mission,
        'vision' => $vision,
        'values' => $values,
        'quality' => $quality,
        'milestones' => $milestones,
        'branches' => Branch::query()->active()->ordered()->get(),
        'yearsActive' => $yearsActive,
    ]);
})->name('public.about');
Route::view('/contacto', 'public.contact')->name('public.contact');
Route::view('/trabaja-con-nosotros', 'public.work-with-us')->name('public.work-with-us');

Route::get('/rotafolios', function () {
    return view('public.brochures.index', [
        'brochures' => Brochure::query()->active()->ordered()->get(),
    ]);
})->name('public.brochures.index');

Route::get('/noticias', function () {
    return view('public.news.index', [
        'news' => News::query()->published()->paginate(9),
    ]);
})->name('public.news.index');

Route::get('/noticias/{news:slug}', function (News $news) {
    return view('public.news.show', compact('news'));
})->name('public.news.show');

// === Admin Routes ===

Route::middleware(['auth', 'verified'])
    ->prefix('admin')->name('admin.')->group(function () {

        Route::view('/', 'dashboard')->name('dashboard');

        Route::middleware(PermissionMiddleware::class.':view.products')->group(function () {
            Route::get('/products', ProductIndex::class)->name('products.index');
        });

        Route::middleware(PermissionMiddleware::class.':edit.products')->group(function () {
            Route::get('/products/create', ProductForm::class)->name('products.create');
            Route::get('/products/{product}/edit', ProductForm::class)->name('products.edit');
        });

        Route::middleware(PermissionMiddleware::class.':view.brands')->group(function () {
            Route::get('/brands', BrandIndex::class)->name('brands.index');
        });

        Route::middleware(PermissionMiddleware::class.':edit.brands')->group(function () {
            Route::get('/brands/create', BrandForm::class)->name('brands.create');
            Route::get('/brands/{brand}/edit', BrandForm::class)->name('brands.edit');
        });

        Route::middleware(PermissionMiddleware::class.':view.categories')->group(function () {
            Route::get('/categories', CategoryIndex::class)->name('categories.index');
        });

        Route::middleware(PermissionMiddleware::class.':edit.categories')->group(function () {
            Route::get('/categories/create', CategoryForm::class)->name('categories.create');
            Route::get('/categories/{category}/edit', CategoryForm::class)->name('categories.edit');
        });

        Route::middleware(PermissionMiddleware::class.':view.branches')->group(function () {
            Route::get('/branches', BranchIndex::class)->name('branches.index');
        });

        Route::middleware(PermissionMiddleware::class.':edit.branches')->group(function () {
            Route::get('/branches/create', BranchForm::class)->name('branches.create');
            Route::get('/branches/{branch}/edit', BranchForm::class)->name('branches.edit');
        });

        Route::middleware(PermissionMiddleware::class.':view.hero')->group(function () {
            Route::get('/hero', HeroSlideIndex::class)->name('hero.index');
        });

        Route::middleware(PermissionMiddleware::class.':edit.hero')->group(function () {
            Route::get('/hero/create', HeroSlideForm::class)->name('hero.create');
            Route::get('/hero/{heroSlide}/edit', HeroSlideForm::class)->name('hero.edit');
            Route::get('/hero/reorder', HeroSlideReorder::class)->name('hero.reorder');
        });

        Route::middleware(PermissionMiddleware::class.':view.brochures')->group(function () {
            Route::get('/brochures', BrochureIndex::class)->name('brochures.index');
        });

        Route::middleware(PermissionMiddleware::class.':edit.brochures')->group(function () {
            Route::get('/brochures/create', BrochureForm::class)->name('brochures.create');
            Route::get('/brochures/{brochure}/edit', BrochureForm::class)->name('brochures.edit');
        });

        Route::middleware(PermissionMiddleware::class.':view.news')->group(function () {
            Route::get('/news', NewsIndex::class)->name('news.index');
        });

        Route::middleware(PermissionMiddleware::class.':edit.news')->group(function () {
            Route::get('/news/create', NewsForm::class)->name('news.create');
            Route::get('/news/{news}/edit', NewsForm::class)->name('news.edit');
        });

        Route::middleware(PermissionMiddleware::class.':view.job-openings')->group(function () {
            Route::get('/job-openings', JobOpeningIndex::class)->name('job-openings.index');
        });

        Route::middleware(PermissionMiddleware::class.':edit.job-openings')->group(function () {
            Route::get('/job-openings/create', JobOpeningForm::class)->name('job-openings.create');
            Route::get('/job-openings/{jobOpening}/edit', JobOpeningForm::class)->name('job-openings.edit');
        });

        Route::middleware(PermissionMiddleware::class.':view.users')
            ->group(function () {
                Route::get('/users', UserIndex::class)->name('users.index');
            });

        Route::middleware(PermissionMiddleware::class.':edit.users')
            ->group(function () {
                Route::get('/users/create', UserForm::class)->name('users.create');
                Route::get('/users/{user}/edit', UserForm::class)->name('users.edit');
            });

        Route::middleware(PermissionMiddleware::class.':view.settings')
            ->group(function () {
                Route::get('/settings', SettingsForm::class)->name('settings.index');
            });
    });

require __DIR__.'/settings.php';
