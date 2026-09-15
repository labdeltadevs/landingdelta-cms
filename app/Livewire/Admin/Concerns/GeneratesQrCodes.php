<?php

namespace App\Livewire\Admin\Concerns;

use App\Models\Brand;
use App\Models\Product;
use App\Support\QrCodeGenerator;
use Illuminate\Database\Eloquent\Model;

/**
 * Agrega un modal con el QR de la URL pública de un producto o marca,
 * con descarga en JPEG y copiado del enlace.
 */
trait GeneratesQrCodes
{
    public ?string $qrSvg = null;

    public ?string $qrName = null;

    public ?string $qrUrl = null;

    public ?string $qrFilename = null;

    abstract protected function qrTarget(int $id): Product|Brand;

    abstract protected function qrRoute(Product|Brand $target): string;

    abstract protected function qrFilename(Model $target): string;

    public function showQr(int $id): void
    {
        $target = $this->qrTarget($id);
        $this->authorize('view', $target);

        $this->qrUrl = $this->qrRoute($target);
        $this->qrName = $target->name;
        $this->qrFilename = $this->qrFilename($target);
        $this->qrSvg = app(QrCodeGenerator::class)->svg($this->qrUrl);

        $this->dispatch('qr-ready');
    }

    public function closeQr(): void
    {
        $this->qrSvg = null;
        $this->qrName = null;
        $this->qrUrl = null;
        $this->qrFilename = null;
    }
}
