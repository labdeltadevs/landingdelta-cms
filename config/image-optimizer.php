<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Valores por defecto
    |--------------------------------------------------------------------------
    |
    | Se usan cuando una entidad no define preset propio.
    |
    */

    'defaults' => [
        'max_width' => 1920,
        'quality' => 80,
        'format' => 'webp',
    ],

    /*
    |--------------------------------------------------------------------------
    | Presets por entidad
    |--------------------------------------------------------------------------
    |
    | dir: subcarpeta dentro del disco "public".
    | maxWidth: ancho máximo en px (mantiene aspect ratio, solo reduce).
    | quality: 0-100 para jpeg/webp/avif (png ignora).
    | format: webp | avif | jpg | png | original (respeta extensión).
    |
    */

    'entities' => [
        'product' => ['dir' => 'products', 'maxWidth' => 1200, 'quality' => 80, 'format' => 'webp'],
        'brand' => ['dir' => 'brands', 'maxWidth' => 800, 'quality' => 85, 'format' => 'webp'],
        'hero' => ['dir' => 'hero', 'maxWidth' => 1920, 'quality' => 80, 'format' => 'webp'],
        'hero_slide' => ['dir' => 'hero', 'maxWidth' => 1920, 'quality' => 80, 'format' => 'webp'],
        'news' => ['dir' => 'news/covers', 'maxWidth' => 1200, 'quality' => 80, 'format' => 'webp'],
        'job' => ['dir' => 'job-openings', 'maxWidth' => 800, 'quality' => 80, 'format' => 'webp'],
    ],

];
