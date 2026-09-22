<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Product;

class LegacyQrRedirector
{
    /**
     * Normaliza path entrante (sin host, sin query string, lowercase, sin slash final)
     */
    public static function normalizePath(string $path): string
    {
        $path = urldecode($path);
        $path = parse_url($path, PHP_URL_PATH) ?? $path;
        $path = Str::lower(trim($path));
        $path = rtrim($path, '/');
        return '/'.ltrim($path, '/');
    }

    /**
     * Extrae candidate slug de patrón A: /productos/{categoria}/{id}-{slug}.html
     */
    public static function extractSlugFromPatternA(string $path): ?string
    {
        if (preg_match('#^/productos/[^/]+/\d+-(.+)\.html$#', $path, $m)) {
            return $m[1];
        }
        return null;
    }

    /**
     * Extrae candidate slug de patrón B: /producto/{slug}/
     */
    public static function extractSlugFromPatternB(string $path): ?string
    {
        if (preg_match('#^/producto/([^/]+)/?$#', $path, $m)) {
            return $m[1];
        }
        return null;
    }

    /**
     * Normaliza path entrante (sin host, sin query string, lowercase, sin slash final)
     */
    public static function normalize(string $path): string
    {
        $path = urldecode($path);
        $path = parse_url($path, PHP_URL_PATH) ?? $path;
        $path = Str::lower(trim($path));
        $path = rtrim($path, '/');
        return '/'.ltrim($path, '/');
    }
}