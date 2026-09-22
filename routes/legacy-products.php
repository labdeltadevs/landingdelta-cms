<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\LegacyQrRedirector;

/*
|--------------------------------------------------------------------------
| Legacy QR Redirects — QR de productos impresos en cajas
|--------------------------------------------------------------------------
| Intercepta URLs legadas (patrón A y B) ANTES de la ruta canónica
| de producto y redirige 301 al slug real si existe, o a /productos si no.
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| PATRÓN A: /productos/{categoria}/{id}-{slug}.html (http, www opcional)
|--------------------------------------------------------------------------
*/
Route::get('/productos/{category}/{legacy}.html', function (Request $r, string $category, string $legacy) {
    $normalized = LegacyQrRedirector::normalize("/productos/{$category}/{$legacy}.html");

    // 1) Busca slug real en BD (sin el id- ni .html)
    $candidateSlug = LegacyQrRedirector::extractSlugFromPatternA("/productos/dummy/{$legacy}.html");
    
    if ($candidateSlug && Product::where('slug', $candidateSlug)->exists()) {
        Log::channel('daily')->info('legacy-qr pattern A hit', [
            'legacy' => "/productos/{$category}/{$legacy}.html",
            'slug' => $candidateSlug,
        ]);
        return redirect()->route('public.products.show', $candidateSlug, 301);
    }

    // Fallback: intentar slug crudo sin id- ni .html
    $candidate = Str::lower(preg_replace('/^\d+-/', '', $legacy));
    $candidate = Str::before($candidate, '.html');
    
    if ($candidate && Product::where('slug', $candidate)->exists()) {
        Log::channel('daily')->info('legacy-qr pattern A heuristic hit', [
            'legacy' => "/productos/{$category}/{$legacy}.html",
            'candidate' => $candidate,
        ]);
        return redirect()->route('public.products.show', $candidate, 301);
    }

    Log::channel('daily')->warning('legacy-qr pattern A miss', ['legacy' => "/productos/{$category}/{$legacy}.html"]);
    return redirect()->route('public.products.index', [], 301);
})->where(['category' => '[^/]+', 'legacy' => '.+']);

/*
|--------------------------------------------------------------------------
| PATRÓN B: /producto/{slug}/ (singular, con slash final, sin .html)
|--------------------------------------------------------------------------
*/
Route::get('/producto/{slug}', function (string $slug) {
    $normalized = Str::lower(trim(urldecode($slug)));
    $normalized = rtrim($normalized, '/');

    // 1) Busca slug exacto
    if (Product::where('slug', $normalized)->exists()) {
        return redirect()->route('public.products.show', $normalized, 301);
    }

    // 2) Busca sin slash final (ya normalizado) - ya está
    // Intenta con slug tal cual (ya normalizado)
    if (Product::where('slug', $normalized)->exists()) {
        return redirect()->route('public.products.show', $normalized, 301);
    }

    // Fallback: buscar por internal_code si coincide
    if (Product::where('internal_code', $normalized)->exists()) {
        $product = Product::where('internal_code', $normalized)->first();
        if ($product) {
            Log::channel('daily')->info('legacy-qr pattern B internal_code hit', [
                'legacy' => "/producto/{$normalized}/",
                'slug' => $product->slug,
            ]);
            return redirect()->route('public.products.show', $product->slug, 301);
        }
    }

    Log::channel('daily')->warning('legacy-qr pattern B miss', ['legacy' => "/producto/{$slug}/"]);
    return redirect()->route('public.products.index', 301);
})->where('slug', '[^/]+');