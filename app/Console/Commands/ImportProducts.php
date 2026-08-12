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
    protected $signature = 'products:import {--path= : Ruta del CSV de WooCommerce (por defecto usa el export en storage)}';

    protected $description = 'Import products from the WooCommerce CSV export';

    private const DEFAULT_PATH = 'app/public/products/wc-product-export-31-7-2026-1785512522302.csv';

    public function handle(): int
    {
        $path = $this->option('path') ?: storage_path(self::DEFAULT_PATH);

        $this->info('=== Importación de productos ===');

        if (! is_file($path)) {
            $this->error("No se encontró el archivo: {$path}");

            return self::FAILURE;
        }

        $rows = $this->readWcCsv($path);

        if ($rows === []) {
            $this->error('No se pudieron leer filas del CSV.');

            return self::FAILURE;
        }

        $this->line('Vaciando la tabla products...');
        Product::query()->truncate();

        $created = 0;
        $withImage = 0;

        $this->line('Procesando '.count($rows).' productos...');
        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $row) {
            $slug = $this->ensureUniqueSlug(Str::slug($row['name']));

            $description = $this->buildDescription($row['short_description'], $row['description']);

            $activeIngredient = $description !== null
                ? $this->extractActiveIngredient($description, $row['name'])
                : null;

            [$brandId, $categoryId] = $this->resolveBrandAndCategory($row['categories']);

            $product = Product::create([
                'internal_code' => 'PPROD_'.$row['id'],
                'name' => $row['name'],
                'slug' => $slug,
                'active_ingredient' => $activeIngredient,
                'description' => $description,
                'brand_id' => $brandId,
                'category_id' => $categoryId,
                'is_active' => true,
                'is_featured' => false,
                'sort' => (int) $row['id'],
            ]);

            $created++;

            if (! empty($row['images'])) {
                $imagePath = $this->downloadImage($row['images'], $product);
                if ($imagePath !== null) {
                    $product->updateQuietly(['main_image_path' => $imagePath]);
                    $withImage++;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newline();

        $this->info('=== Importación completada ===');
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Productos creados', $created],
                ['Con imagen descargada', $withImage],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function readWcCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error("No se pudo abrir: {$path}");

            return [];
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            $this->error('CSV sin cabeceras');

            return [];
        }

        $headers = array_map(
            fn (string $header): string => str_replace("\xEF\xBB\xBF", '', trim($header)),
            $headers
        );

        $columns = array_flip($headers);

        $required = ['ID', 'Nombre', 'Categorías', 'Imágenes'];
        $missing = array_diff($required, $headers);

        if ($missing !== []) {
            fclose($handle);
            $this->error('Faltan columnas: '.implode(', ', $missing));

            return [];
        }

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = [
                'id' => trim($data[$columns['ID']] ?? ''),
                'name' => trim($data[$columns['Nombre']] ?? ''),
                'short_description' => $this->cell($data, $columns, 'Descripción corta'),
                'description' => $this->cell($data, $columns, 'Descripción'),
                'categories' => trim($this->cell($data, $columns, 'Categorías')),
                'images' => trim($this->cell($data, $columns, 'Imágenes')),
            ];
        }

        fclose($handle);

        return $rows;
    }

    /**
     * @param  array<int, mixed>  $data
     * @param  array<string, int>  $columns
     */
    private function cell(array $data, array $columns, string $name): string
    {
        if (! isset($columns[$name])) {
            return '';
        }

        return (string) ($data[$columns[$name]] ?? '');
    }

    private function buildDescription(string $short, string $long): ?string
    {
        $short = $this->normalizeHtml($short);
        $long = $this->normalizeHtml($long);

        $description = trim($short."\n\n".$long);

        return $description !== '' ? $description : null;
    }

    private function normalizeHtml(string $html): string
    {
        return str_replace(
            ['\r\n', '\n', '\r', '\t'],
            ["\r\n", "\n", "\r", "\t"],
            $html
        );
    }

    private function resolveBrandAndCategory(string $categoryStr): array
    {
        if (blank($categoryStr)) {
            return [null, null];
        }

        // Unescape commas escaped with a backslash by the WooCommerce export
        $categoryStr = str_replace('\\,', ',', $categoryStr);

        $parts = explode(' > ', $categoryStr);
        $brandName = trim($parts[0] ?? '');
        $categoryName = trim($parts[1] ?? '');

        // Brand: first token before a comma (handles "Delta, Delta > ...")
        if (str_contains($brandName, ',')) {
            $brandName = trim(explode(',', $brandName)[0]);
        }

        // Strip trailing brand labels from the category segment (", Delta", ", Synthera")
        foreach (['Delta', 'Synthera'] as $label) {
            $categoryName = trim(
                preg_replace('/,\s*'.preg_quote($label, '/').'\s*$/u', '', $categoryName) ?? $categoryName
            );
        }

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
