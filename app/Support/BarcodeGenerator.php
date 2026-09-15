<?php

namespace App\Support;

use Picqer\Barcode\BarcodeGenerator as PicqerGenerator;
use Picqer\Barcode\BarcodeGeneratorSVG;

/**
 * Genera códigos de barras Code 128 en SVG para códigos internos y slugs.
 */
class BarcodeGenerator
{
    public function __construct(
        private float $widthFactor = 2,
        private float $height = 60,
    ) {}

    public function svg(string $code): string
    {
        return (new BarcodeGeneratorSVG)->getBarcode(
            $code,
            PicqerGenerator::TYPE_CODE_128,
            $this->widthFactor,
            $this->height,
        );
    }
}
