<?php

namespace App\Support;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Exportación e importación masiva de productos en CSV.
 *
 * Formato: punto y coma + BOM (Excel en español); el parseo tolera comas.
 */
class ProductCsv
{
    public const HEADERS = [
        'id',
        'name',
        'internal_code',
        'slug',
        'active_ingredient',
        'brand_id',
        'category_id',
        'is_active',
        'is_featured',
    ];

    public const DELIMITER = ';';

    public static function detectDelimiter(string $firstLine): string
    {
        return substr_count($firstLine, ';') >= substr_count($firstLine, ',') ? ';' : ',';
    }

    /**
     * @return array{delimiter: string, headers: array<int, string>, rows: array<int, array<string, ?string>>, missing: array<int, string>, malformed: int}
     */
    public static function parse(string $contents): array
    {
        $contents = ltrim($contents, "\xEF\xBB\xBF");
        $lines = array_values(array_filter(
            preg_split('/\r\n|\r|\n/', $contents) ?: [],
            fn (string $line): bool => trim($line) !== ''
        ));

        if ($lines === []) {
            return ['delimiter' => self::DELIMITER, 'headers' => [], 'rows' => [], 'missing' => self::HEADERS, 'malformed' => 0];
        }

        $delimiter = self::detectDelimiter($lines[0]);
        $headers = array_map(
            fn (string $header): string => strtolower(trim($header)),
            str_getcsv($lines[0], $delimiter) ?: []
        );

        $rows = [];
        $malformed = 0;

        foreach (array_slice($lines, 1) as $line) {
            $values = str_getcsv($line, $delimiter);

            if ($values === false || count($values) !== count($headers)) {
                $malformed++;

                continue;
            }

            $row = [];

            foreach ($headers as $index => $header) {
                $row[$header] = $values[$index];
            }

            $rows[] = $row;
        }

        return [
            'delimiter' => $delimiter,
            'headers' => $headers,
            'rows' => $rows,
            'missing' => array_values(array_diff(self::HEADERS, $headers)),
            'malformed' => $malformed,
        ];
    }

    public static function normalizeBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower(Str::ascii(trim((string) $value))), ['1', 'true', 'yes', 'si', 'y', 'on'], true);
    }

    public static function normalizeId(mixed $value): ?int
    {
        $value = trim((string) $value);

        return ctype_digit($value) && (int) $value > 0 ? (int) $value : null;
    }

    /**
     * @return array<int, string|int|null>
     */
    public static function row(Product $product): array
    {
        return [
            $product->id,
            $product->name,
            $product->internal_code,
            $product->slug,
            $product->active_ingredient,
            $product->brand_id,
            $product->category_id,
            $product->is_active ? '1' : '0',
            $product->is_featured ? '1' : '0',
        ];
    }

    /**
     * Crea o actualiza un producto desde una fila del CSV.
     *
     * @param  array<string, ?string>  $row
     * @return array{status: 'created'|'updated'|'skipped', reason: ?string, id: ?int}
     */
    public static function syncRow(array $row): array
    {
        return DB::transaction(function () use ($row) {
            $id = self::normalizeId($row['id'] ?? null);
            $product = $id !== null ? Product::query()->find($id) : null;
            $name = trim((string) ($row['name'] ?? ''));

            if ($product === null && $name === '') {
                return ['status' => 'skipped', 'reason' => 'sin nombre para crear', 'id' => null];
            }

            $brandId = self::normalizeId($row['brand_id'] ?? null);
            if ($brandId !== null && ! Brand::query()->whereKey($brandId)->exists()) {
                $brandId = null;
            }

            $categoryId = self::normalizeId($row['category_id'] ?? null);
            if ($categoryId !== null && ! Category::query()->whereKey($categoryId)->exists()) {
                $categoryId = null;
            }

            $data = [
                'name' => $product !== null && $name === '' ? $product->name : $name,
                'active_ingredient' => trim((string) ($row['active_ingredient'] ?? '')) ?: null,
                'brand_id' => $brandId,
                'category_id' => $categoryId,
            ];

            foreach (['is_active' => true, 'is_featured' => false] as $field => $default) {
                $raw = trim((string) ($row[$field] ?? ''));

                if ($raw === '') {
                    $data[$field] = $product !== null ? $product->{$field} : $default;
                } else {
                    $data[$field] = self::normalizeBool($raw);
                }
            }

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'active_ingredient' => 'nullable|string|max:255',
                'brand_id' => 'nullable|integer',
                'category_id' => 'nullable|integer',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
            ]);

            if ($validator->fails()) {
                return ['status' => 'skipped', 'reason' => $validator->errors()->first(), 'id' => $product?->id];
            }

            if ($product !== null) {
                $product->update($validator->validated());

                return ['status' => 'updated', 'reason' => null, 'id' => $product->id];
            }

            $data = $validator->validated();
            $data['slug'] = self::resolveSlug($row['slug'] ?? null, $data['name']);
            $data['internal_code'] = self::resolveInternalCode($row['internal_code'] ?? null);

            $product = Product::query()->create($data);

            return ['status' => 'created', 'reason' => null, 'id' => $product->id];
        });
    }

    private static function resolveSlug(?string $candidate, string $name): string
    {
        $candidate = Str::slug(trim((string) $candidate));

        if ($candidate !== '' && ! Product::query()->where('slug', $candidate)->exists()) {
            return $candidate;
        }

        do {
            $slug = Str::slug($name).'-'.Str::lower(Str::random(5));
        } while (Product::query()->where('slug', $slug)->exists());

        return $slug;
    }

    private static function resolveInternalCode(?string $candidate): string
    {
        $candidate = trim((string) $candidate);

        if ($candidate !== '' && ! Product::query()->where('internal_code', $candidate)->exists()) {
            return $candidate;
        }

        do {
            $code = 'PROD-'.Str::upper(Str::random(6));
        } while (Product::query()->where('internal_code', $code)->exists());

        return $code;
    }
}
