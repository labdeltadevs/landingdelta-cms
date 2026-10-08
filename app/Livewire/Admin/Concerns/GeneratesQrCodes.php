<?php

namespace App\Livewire\Admin\Concerns;

use App\Models\Brand;
use App\Models\JobOpening;
use App\Models\Product;
use App\Support\BarcodeGenerator;
use App\Support\QrCodeGenerator;
use Illuminate\Database\Eloquent\Model;

/**
 * Agrega un modal dinámico con el QR de la URL pública o el código de
 * barras del código interno de un producto, marca o convocatoria, con
 * descarga en JPEG y copiado del enlace.
 */
trait GeneratesQrCodes
{
    public ?string $qrSvg = null;

    public ?string $qrName = null;

    public ?string $qrUrl = null;

    public ?string $codeFilename = null;

    public ?string $barcodeSvg = null;

    public ?string $barcodeValue = null;

    abstract protected function qrTarget(int $id): Product|Brand|JobOpening;

    abstract protected function qrRoute(Product|Brand|JobOpening $target): string;

    abstract protected function codeFilename(Model $target): string;

    abstract protected function barcodeValue(Product|Brand|JobOpening $target): string;

    public function showQr(int $id): void
    {
        $target = $this->qrTarget($id);
        $this->authorize('view', $target);

        $this->reset('barcodeSvg', 'barcodeValue');

        $this->qrUrl = $this->qrRoute($target);
        $this->qrName = $target->name;
        $this->qrSvg = app(QrCodeGenerator::class)->svg($this->qrUrl);
        $this->codeFilename = $this->codeFilename($target);

        $this->dispatch('qr-ready');
    }

    public function showBarcode(int $id): void
    {
        $target = $this->qrTarget($id);
        $this->authorize('view', $target);

        $this->reset('qrSvg', 'qrUrl');

        $this->barcodeValue = $this->barcodeValue($target);
        $this->barcodeSvg = app(BarcodeGenerator::class)->svg($this->barcodeValue);
        $this->qrName = $target->name;
        $this->codeFilename = $this->codeFilename($target);

        $this->dispatch('qr-ready');
    }

    public function closeCode(): void
    {
        $this->reset([
            'qrSvg',
            'qrName',
            'qrUrl',
            'codeFilename',
            'barcodeSvg',
            'barcodeValue',
        ]);
    }
}
