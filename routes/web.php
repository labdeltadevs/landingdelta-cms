<?php

use App\Livewire\Admin\Brands\BrandForm;
use App\Livewire\Admin\Brands\BrandIndex;
use App\Livewire\Admin\Branches\BranchForm;
use App\Livewire\Admin\Branches\BranchIndex;
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
use App\Models\Brand;
use App\Models\News;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

// === Public Routes ===

Route::view('/', 'public.home')->name('public.home');

Route::get('/productos', function () {
    $query = Product::query()->active()->with('brand', 'category');

    $selectedCategory = null;
    $categorySlug = request('category');
    if ($categorySlug) {
        $selectedCategory = \App\Models\Category::query()->where('slug', $categorySlug)->first();
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
        'products' => $query->ordered()->paginate(10),
        'categories' => \App\Models\Category::query()->active()->ordered()->withCount(['products' => function ($q) {
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
    $history = \App\Models\SiteSetting::get('about_history');
    $mission = \App\Models\SiteSetting::get('about_mission');
    $vision = \App\Models\SiteSetting::get('about_vision');
    $values = \App\Models\SiteSetting::get('about_values');
    $quality = \App\Models\SiteSetting::get('about_quality_policy');
    $milestonesRaw = \App\Models\SiteSetting::get('about_milestones', '[]');
    $milestones = is_string($milestonesRaw) ? json_decode($milestonesRaw, true) : (is_array($milestonesRaw) ? $milestonesRaw : []);

    $yearsActive = now()->year - 1987;

    return view('public.about', [
        'history' => $history,
        'mission' => $mission,
        'vision' => $vision,
        'values' => $values,
        'quality' => $quality,
        'milestones' => $milestones,
        'branches' => \App\Models\Branch::query()->active()->ordered()->get(),
        'yearsActive' => $yearsActive,
    ]);
})->name('public.about');
Route::view('/contacto', 'public.contact')->name('public.contact');
Route::view('/trabaja-con-nosotros', 'public.work-with-us')->name('public.work-with-us');

Route::get('/rotafolios', function () {
    return view('public.brochures.index', [
        'brochures' => \App\Models\Brochure::query()->active()->ordered()->get(),
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

Route::middleware(['auth', 'verified', 'role:admin|editor|visor'])
    ->prefix('admin')->name('admin.')->group(function () {

        Route::view('/', 'dashboard')->name('dashboard');

        Route::get('/products', ProductIndex::class)->name('products.index');
        Route::get('/products/create', ProductForm::class)->name('products.create');
        Route::get('/products/{product}/edit', ProductForm::class)->name('products.edit');

        Route::get('/brands', BrandIndex::class)->name('brands.index');
        Route::get('/brands/create', BrandForm::class)->name('brands.create');
        Route::get('/brands/{brand}/edit', BrandForm::class)->name('brands.edit');

        Route::get('/categories', CategoryIndex::class)->name('categories.index');
        Route::get('/categories/create', CategoryForm::class)->name('categories.create');
        Route::get('/categories/{category}/edit', CategoryForm::class)->name('categories.edit');

        Route::get('/branches', BranchIndex::class)->name('branches.index');
        Route::get('/branches/create', BranchForm::class)->name('branches.create');
        Route::get('/branches/{branch}/edit', BranchForm::class)->name('branches.edit');

        Route::get('/hero', HeroSlideIndex::class)->name('hero.index');
        Route::get('/hero/create', HeroSlideForm::class)->name('hero.create');
        Route::get('/hero/{heroSlide}/edit', HeroSlideForm::class)->name('hero.edit');
        Route::get('/hero/reorder', HeroSlideReorder::class)->name('hero.reorder');

        Route::get('/brochures', BrochureIndex::class)->name('brochures.index');
        Route::get('/brochures/create', BrochureForm::class)->name('brochures.create');
        Route::get('/brochures/{brochure}/edit', BrochureForm::class)->name('brochures.edit');

        Route::get('/news', NewsIndex::class)->name('news.index');
        Route::get('/news/create', NewsForm::class)->name('news.create');
        Route::get('/news/{news}/edit', NewsForm::class)->name('news.edit');

        Route::get('/job-openings', JobOpeningIndex::class)->name('job-openings.index');
        Route::get('/job-openings/create', JobOpeningForm::class)->name('job-openings.create');
        Route::get('/job-openings/{jobOpening}/edit', JobOpeningForm::class)->name('job-openings.edit');

        // Admin-only routes
        Route::middleware(PermissionMiddleware::class . ':manage users')
            ->group(function () {
                Route::get('/users', UserIndex::class)->name('users.index');
                Route::get('/users/create', UserForm::class)->name('users.create');
                Route::get('/users/{user}/edit', UserForm::class)->name('users.edit');
            });

        Route::middleware(PermissionMiddleware::class . ':manage settings')
            ->group(function () {
                Route::get('/settings', SettingsForm::class)->name('settings.index');
            });
    });

require __DIR__.'/settings.php';
