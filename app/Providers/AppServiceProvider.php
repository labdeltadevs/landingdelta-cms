<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Branch;
use App\Models\Brochure;
use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\News;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use App\Policies\BrandPolicy;
use App\Policies\BranchPolicy;
use App\Policies\BrochurePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\HeroSlidePolicy;
use App\Policies\NewsPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SiteSettingPolicy;
use App\Policies\UserPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Brand::class, BrandPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Branch::class, BranchPolicy::class);
        Gate::policy(HeroSlide::class, HeroSlidePolicy::class);
        Gate::policy(Brochure::class, BrochurePolicy::class);
        Gate::policy(News::class, NewsPolicy::class);
        Gate::policy(SiteSetting::class, SiteSettingPolicy::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
