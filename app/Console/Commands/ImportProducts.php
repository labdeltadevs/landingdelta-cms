<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportProducts extends Command
{
    protected $signature = 'products:import';

    protected $description = 'Import products from productos_vigentes.csv and wc-product-export CSV';

    private const XLSX_PATH = 'app/public/products/productos_vigentes.csv';

    private const WP_PATH = 'app/public/products/wc-product-export-29-7-2026-1785333046194.csv';

    public function handle(): int
    {
        $this->info('=== Importación de productos ===');

        $xlsxPath = storage_path(self::XLSX_PATH);
        $wpPath = storage_path(self::WP_PATH);

        // ── 1. Read WP export ─────────────────────────────────
        $this->line('Leyendo export de WordPress...');
        $wpRows = $this->readWpCsv($wpPath);

        // ── 2. Read xlsx export ────────────────────────────────
        $this->line('Leyendo productos vigentes...');
        $xlsxRows = $this->readXlsxCsv($xlsxPath);

        // ── 3. Build normalized index from xlsx ────────────────
        $this->line('Construyendo índice de matching...');
        $xlsxIndex = [];
        foreach ($xlsxRows as $xlsx) {
            $norm = $this->normalizeName($xlsx['full_name']);
            $xlsxIndex[$norm] = $xlsx;
        }

        // ── 4. Track matched xlsx products ─────────────────────
        $matchedXlsx = [];
        $created = 0;
        $updated = 0;
        $withImage = 0;
        $noMatchWp = 0;
        $matchedCount = 0;

        // ── 5. Process each WP product ─────────────────────────
        $this->line('Procesando productos...');
        $bar = $this->output->createProgressBar(count($wpRows));
        $bar->start();

        foreach ($wpRows as $wp) {
            $normWp = $this->normalizeName($wp['nombre']);
            $match = $xlsxIndex[$normWp] ?? null;

            if ($match !== null) {
                $matchedCount++;
                $matchedXlsx[$match['internal_code']] = true;
            }

            // Parse brand and category
            [$brandId, $categoryId] = $this->resolveBrandAndCategory($wp['categorias'] ?? '');

            // Build slug
            $slug = $match['slug'] ?? Str::slug($wp['nombre']);

            // Ensure unique slug
            $slug = $this->ensureUniqueSlug($slug);

            // Build internal_code
            $internalCode = $match['internal_code'] ?? 'WP-'.Str::random(10);

            // Extract active ingredient
            $activeIngredient = $this->extractActiveIngredient(
                ($wp['descripcion'] ?? '')."\n".($wp['descripcion_corta'] ?? ''),
                $wp['nombre']
            );

            // Description: short + long
            $description = trim(
                ($wp['descripcion_corta'] ?? '')
                ."\n\n"
                .($wp['descripcion'] ?? '')
            );

            // Create or update product
            $product = Product::updateOrCreate(
                ['internal_code' => $internalCode],
                [
                    'name' => $wp['nombre'],
                    'slug' => $slug,
                    'active_ingredient' => $activeIngredient,
                    'description' => $description ?: null,
                    'brand_id' => $brandId,
                    'category_id' => $categoryId,
                    'is_active' => $match ? (strtolower($match['is_active'] ?? 'si') === 'si') : true,
                    'sort' => $match ? (int) ($match['id'] ?? 0) : 0,
                ]
            );

            if ($product->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }

            // Download image
            if (! empty($wp['imagenes'])) {
                $imagePath = $this->downloadImage($wp['imagenes'], $product);
                if ($imagePath !== null) {
                    $product->updateQuietly(['main_image_path' => $imagePath]);
                    $withImage++;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newline();

        // ── 6. Process xlsx products without WP match ──────────
        $xlsxWithoutMatch = 0;
        foreach ($xlsxRows as $xlsx) {
            if (isset($matchedXlsx[$xlsx['internal_code']])) {
                continue;
            }

            $slug = $this->ensureUniqueSlug($xlsx['slug']);

            Product::updateOrCreate(
                ['internal_code' => $xlsx['internal_code']],
                [
                    'name' => $xlsx['full_name'],
                    'slug' => $slug,
                    'active_ingredient' => null,
                    'description' => null,
                    'is_active' => strtolower($xlsx['is_active'] ?? 'si') === 'si',
                    'sort' => (int) ($xlsx['id'] ?? 0),
                ]
            );

            $xlsxWithoutMatch++;
            $created++;
        }

        // ── 7. Report ──────────────────────────────────────────
        $this->newline();
        $this->info('=== Importación completada ===');
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Productos creados', $created],
                ['Productos actualizados', $updated],
                ['Con imagen asignada', $withImage],
                ['Match WP→XLSX exitoso', $matchedCount],
                ['XLSX sin match en WP', $xlsxWithoutMatch],
                ['Total procesados', $created + $updated],
            ]
        );

        return self::SUCCESS;
    }

    private function readWpCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->fail("No se pudo abrir: {$path}");
        }

        $headers = fgetcsv($handle, 0, ',', '"', '');
        if ($headers === false) {
            fclose($handle);
            $this->fail('CSV de WP sin cabeceras');
        }

        $rows = [];
        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            $entry = [
                'nombre' => $row[1] ?? '',
                'descripcion_corta' => $row[2] ?? '',
                'descripcion' => $row[3] ?? '',
                'categorias' => $row[4] ?? '',
                'etiquetas' => $row[5] ?? '',
                'imagenes' => $row[6] ?? '',
                'marcas' => $row[7] ?? '',
            ];
            $rows[] = $entry;
        }

        fclose($handle);

        return $rows;
    }

    private function readXlsxCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->fail("No se pudo abrir: {$path}");
        }

        $rows = [];
        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            if (count($row) < 5) {
                continue;
            }
            $rows[] = [
                'id' => trim($row[0]),
                'internal_code' => trim($row[1]),
                'full_name' => trim($row[2]),
                'is_active' => trim($row[3]),
                'slug' => trim($row[4]),
            ];
        }

        fclose($handle);

        return $rows;
    }

    private function normalizeName(string $name): string
    {
        $name = preg_replace('/\s*\.\s*x\s+\d+\s+\w+.*$/u', '', $name);
        $name = mb_strtolower($name, 'UTF-8');

        // Transliterate accents
        $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name) ?: $name;

        $name = preg_replace('/[^a-z0-9\s%\/]/u', '', $name);
        $name = preg_replace('/(\d)([a-z])/i', '$1 $2', $name);
        $name = preg_replace('/\s+/', ' ', $name);

        return trim($name);
    }

    private function resolveBrandAndCategory(string $categoryStr): array
    {
        if (blank($categoryStr)) {
            return [null, null];
        }

        // Unescape commas
        $categoryStr = str_replace('\\,', ',', $categoryStr);

        $parts = explode(' > ', $categoryStr);
        $brandName = trim($parts[0] ?? '');
        $categoryName = trim($parts[1] ?? '');

        $brandId = null;
        if ($brandName !== '') {
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($brandName)],
                ['name' => $brandName, 'is_active' => true]
            );
            $brandId = $brand->id;
        }

        $categoryId = null;
        if ($categoryName !== '') {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName, 'is_active' => true]
            );
            $categoryId = $category->id;
        }

        return [$brandId, $categoryId];
    }

    private function extractActiveIngredient(string $html, string $fallbackName): ?string
    {
        if (blank($html)) {
            return null;
        }

        // 1) Look for a table row with an active ingredient cell
        if (preg_match('/<tr[^>]*>\s*<td[^>]*>\s*<p[^>]*>([^<]+)<\/p>\s*<\/td>/i', $html, $m)) {
            $ingredient = trim(strip_tags($m[1]));
            if ($ingredient !== '') {
                return mb_substr($ingredient, 0, 250);
            }
        }

        // 2) Look for "Principio Activo / PRINCIPIO ACTIVO" keyword in the text
        $stripped = strip_tags($html);
        if (preg_match('/PRINCIPIO\s+ACTIVO[:\s]*\n?\s*(.+?)(?:\n|$)/iu', $stripped, $m)) {
            $ingredient = trim($m[1]);
            if ($ingredient !== '') {
                return mb_substr($ingredient, 0, 250);
            }
        }

        // 3) Fallback: first meaningful line
        foreach (explode("\n", $stripped) as $line) {
            $line = trim($line);
            if ($line !== ''
                && ! str_starts_with($line, 'FORMA')
                && ! str_starts_with($line, 'Cada')
                && ! str_starts_with($line, '&nbsp;')
                && mb_strlen($line) < 200) {
                return $line;
            }
        }

        return mb_substr($fallbackName, 0, 250);
    }

    private function downloadImage(string $url, Product $product): ?string
    {
        $urls = explode(',', $url);
        $firstUrl = trim($urls[0]);

        if (blank($firstUrl)) {
            return null;
        }

        $context = stream_context_create([
            'http' => [
                'timeout' => 15,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $contents = @file_get_contents($firstUrl, false, $context);
        if ($contents === false) {
            return null;
        }

        $ext = pathinfo(parse_url($firstUrl, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION) ?: 'jpg';
        $ext = strtolower($ext);
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'])) {
            $ext = 'jpg';
        }

        $filename = "products/{$product->slug}-{$product->id}.{$ext}";

        Storage::disk('public')->put($filename, $contents);

        return $filename;
    }

    private function ensureUniqueSlug(string $slug): string
    {
        $base = $slug;
        $counter = 1;

        while (Product::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
