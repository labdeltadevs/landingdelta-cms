<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Legacy QR Redirects — QR de productos impresos en cajas (PARCHE)
|--------------------------------------------------------------------------
| Diccionario plano: substring legado => slug canónico (generado desde
| slugylegacy.csv, ordenado por longitud descendente para que el match
| más específico gane). Sin heurísticas ni matching difuso.
|
| Miss (sin match o slug inexistente) => 301 a /productos + log warning.
|
| BORRADO FUTURO: eliminar este archivo + la línea
|   require __DIR__.'/legacy-products.php';
| en routes/web.php. Nada más depende de este parche.
|--------------------------------------------------------------------------
*/

$legacyMap = [
    'nistatina-suspension-500-000-ui-5ml' => 'nistatina-suspension-500000-ui5ml',
    'olmstar-olmesartan-medoxomilo-40-mg' => 'olmstar-olmesartan-medoxomilo-40-mg',
    'cloranfenicol-suspension-250mg-5ml' => 'cloranfenicol-suspension-250mg5ml',
    'amoxicilina-suspension-250mg-5ml' => 'amoxicilina-suspension-250mg5ml',
    'amoxicilina-suspension-500mg-5ml' => 'amoxicilina-suspension-500mg5ml',
    'azitromicina-suspension-200mg-ml' => 'azitromicina-suspension-200mgml',
    'dextrometorfano-jarabe-10mg-5ml' => 'dextrometorfano-jarabe-10mg5ml',
    'ibuprofeno-suspension-100mg-5ml' => 'ibuprofeno-suspension-100mg5ml',
    'ibuprofeno-suspension-200mg-5ml' => 'ibuprofeno-suspension-200mg5ml',
    'supositorio-bronquial-lactante' => 'supositorio-bronquial-lactante',
    'supositorio-largo-de-glicerina' => 'supositorio-largo-de-glicerina',
    'nistatina-gotas-100-000-ui-ml' => 'nistatina-gotas-100000-uiml',
    'paracetamol-masticable-125-mg' => 'paracetamol-masticable-125-mg',
    'cotrimoxazol-forte-800-160mg' => 'cotrimoxazol-forte-800160mg',
    'paracetamol-jarabe-125g-5ml' => 'paracetamol-jarabe-125mg5ml',
    'paracetamol-jarabe-250mg-ml' => 'paracetamol-jarabe-250mg5ml',
    'dolomil-paracetamol-250-mg' => 'dolomil-paracetamol-250-mg',
    'espasmodol-compuesto-gotas' => 'espasmodol-compuesto-gotas',
    'paracetamol-gotas-100mg-ml' => 'paracetamol-gotas-100mgml',
    'ambroxol-jarabe-15mg-5ml' => 'ambroxol-jarabe-15mg5ml',
    'ambroxol-jarabe-30mg-5ml' => 'ambroxol-jarabe-30mg5ml',
    'cotrimoxazol-forte-100ml' => 'cotrimoxazol-forte-100ml',
    'yodopovidona-solucion-10' => 'yodopovidona-solucion-1000ml-10',
    'amoxicilina-125-mg-5-ml' => 'amoxicilina-125-mg-5-ml',
    '43-carbamazepina-200-mg' => 'carbamazepina-200-mg',
    'difenhidramina-pomada-2' => 'difenhidramina-pomada-2',
    'dolomil-1-gr-comprimido' => 'dolomil-paracetamol-1-g-comprimido',
    'gas-x-simeticona-300-mg' => 'gas-x-simeticona-300-mg',
    'mixol-fluconazol-200-mg' => 'mixol-fluconazol-200-mg',
    'fludelta-nf-suspension' => 'fludelta-nf-suspension',
    'metronidazol-250mg-5ml' => 'metronidazol-250mg5ml',
    '43-eritromicina-500-mg' => 'eritromicina-500mg',
    'ciprofloxacina-500mg' => 'ciprofloxacina-500mg',
    'dextrometorfano-15mg' => 'dextrometorfano-15mg',
    'espasmodol-compuesto' => 'espasmodol-compuesto',
    'nistatina-500-000-ui' => 'nistatina-500000-ui',
    'prednisona-20-mg-5ml' => 'prednisona-20-mg5ml',
    '72-paracetamol-1-gr' => 'paracetamol-1g',
    '23-cetirizina-10-mg' => 'cetirizina-10mg',
    'clorfeniramina-4mg' => 'clorfeniramina-4mg',
    'indometacina-100mg' => 'indometacina-100mg',
    'ketoprofeno-100-mg' => 'ketoprofeno-100-mg',
    'norfloxacina-400mg' => 'norfloxacina-400mg',
    'aciclovir-crema-5' => 'aciclovir-crema-5',
    'dexametasona-05mg' => 'dexametasona-05mg',
    'diclofenaco-100mg' => 'diclofenaco-100mg',
    'fludelta-nf-gotas' => 'fludelta-nf-gotas',
    'ketoconazol-200mg' => 'ketoconazol-200mg',
    'ranitidina-300-mg' => 'ranitidina-300-mg',
    'dextrofen-jarabe' => 'dextrofen-jarabe',
    'diclofenaco-50mg' => 'diclofenaco-50mg',
    'diclofenaco-75mg' => 'diclofenaco-75mg',
    'espasmodol-gotas' => 'espasmodol-gotas',
    'ibuprofeno-600mg' => 'ibuprofeno-600mg',
    'ibuprofeno-800mg' => 'ibuprofeno-800mg',
    'mebendazol-100mg' => 'mebendazol-100mg',
    'metformina-850mg' => 'metformina-850mg',
    'ranitidina-150mg' => 'ranitidina-150mg',
    'aciclovir-400mg' => 'aciclovir-400mg',
    'aciclovir-800mg' => 'aciclovir-800mg',
    '20-calcio-500-d' => 'calcio-500-d',
    'clobetazol-0-05' => 'clobetasol-005',
    'espasmodol-10mg' => 'espasmodol-10mg',
    'fluidmax-600-mg' => 'fluidmax-600-mg',
    'ketorolaco-20mg' => 'ketorolaco-20mg',
    'meloxicam-15-mg' => 'meloxicam-15-mg',
    'meloxicam-75-mg' => 'meloxicam-75-mg',
    'prednisona-20mg' => 'prednisona-20mg',
    'tadalafil-20-mg' => 'tadalafil-20-mg',
    'acetilcisteina' => 'acetilcisteina',
    'claritromicina' => 'claritromicina',
    'nexclav-500-mg' => 'nexclav-500-mg',
    'prednisona-5mg' => 'prednisona-5mg',
    'glibenclamida' => 'glibenclamida',
    'glimeform-duo' => 'glimeform-duo',
    'levofloxacina' => 'levofloxacina',
    '27-complejo-b' => 'complejo-b',
    'calcio-500mg' => 'calcio-500mg',
    'deltagel-ms' => 'deltagel-ms',
    'deltagel-nf' => 'deltagel-nf',
    'deltagel-ss' => 'deltagel-ss',
    'fludelta-nf' => 'fludelta-nf',
    'nexclav-duo' => 'nexclav-duo',
    'ampimil-1g' => 'ampimil-1g',
    'clear-face' => 'clear-face',
    'glimeform' => 'glimeform',
    'cortirel' => 'cortirel-prednisona-20-mg5ml',
    'glucomed' => 'glucomed',
    'migradol' => 'migradol',
    'levodel' => 'levodel',
    'parafen' => 'parafen',
    'punacap' => 'punacap',
    'flexco' => 'flexco',
    'neoxim' => 'neoxim',
    'tamdu' => 'tamdu', ];

$normalizeLegacyPath = function (string $path): string {
    $path = urldecode($path);
    $path = parse_url($path, PHP_URL_PATH) ?? $path;
    $path = Str::lower(trim($path));
    $path = rtrim($path, '/');

    return '/'.ltrim($path, '/');
};

$resolveLegacySlug = function (string $path) use ($legacyMap): ?string {
    foreach ($legacyMap as $needle => $slug) {
        if ($needle !== '' && str_contains($path, $needle)) {
            return $slug;
        }
    }

    return null;
};

$redirectLegacyProduct = function (?string $slug, string $legacyLabel) {
    if ($slug !== null && Product::where('slug', $slug)->exists()) {
        Log::channel('daily')->info('legacy-qr hit', ['legacy' => $legacyLabel, 'slug' => $slug]);

        return redirect()->route('public.products.show', $slug, 301);
    }

    Log::channel('daily')->warning('legacy-qr miss', ['legacy' => $legacyLabel]);

    return redirect()->route('public.products.index', [], 301);
};

/*
|--------------------------------------------------------------------------
| PATRÓN A: /productos/{categoria}/{id}-{slug}.html (http, www opcional)
|--------------------------------------------------------------------------
*/
Route::get('/productos/{category}/{legacy}.html', function (Request $request, string $category, string $legacy) use ($normalizeLegacyPath, $resolveLegacySlug, $redirectLegacyProduct) {
    $path = $normalizeLegacyPath('/productos/'.$category.'/'.$legacy.'.html');

    return $redirectLegacyProduct($resolveLegacySlug($path), $path);
})->where(['category' => '[^/]+', 'legacy' => '.+']);

/*
|--------------------------------------------------------------------------
| PATRÓN B: /producto/{slug}/ (singular, con o sin slash final)
|--------------------------------------------------------------------------
*/
Route::get('/producto/{slug}', function (string $slug) use ($normalizeLegacyPath, $resolveLegacySlug, $redirectLegacyProduct) {
    $path = $normalizeLegacyPath('/producto/'.$slug);

    return $redirectLegacyProduct($resolveLegacySlug($path), $path);
})->where('slug', '[^/]+');
