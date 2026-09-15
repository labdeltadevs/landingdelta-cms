<?php

namespace App\Support;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Genera códigos QR en SVG para URLs públicas (productos, marcas).
 */
class QrCodeGenerator
{
    public function __construct(
        private int $size = 300,
        private int $margin = 2,
    ) {}

    public function svg(string $url): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($this->size, $this->margin),
            new SvgImageBackEnd
        );

        return (new Writer($renderer))->writeString($url);
    }
}
