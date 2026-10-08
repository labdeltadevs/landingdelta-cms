{{-- Modal compartido y dinámico: muestra el QR de la URL pública o el código de barras (CB) del código interno (productos, marcas y convocatorias). Requiere el trait GeneratesQrCodes. --}}
@php
    $showQr = filled($qrSvg);
    $showBarcode = filled($barcodeSvg);
    $isBarcodeOnly = $showBarcode && ! $showQr;
    $filenameSuffix = $isBarcodeOnly ? '-codigo-barras.jpg' : '-qr.jpg';
@endphp
<div @qr-ready.window="document.dispatchEvent(new CustomEvent('modal-show', { detail: { name: 'qr-modal' } }))"
    x-data="{ copied: false, downloadCode(box) {
        const loadImage = (svg) => new Promise((resolve, reject) => {
            const xml = new XMLSerializer().serializeToString(svg);
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(xml);
        });
        (async () => {
            const width = 1024;
            const pad = 48;
            const barHeight = 220;
            const labelHeight = 90;
            const qrSvg = box.querySelector('[data-qr] svg');
            const barSvg = box.querySelector('[data-barcode] svg');
            const qrSize = qrSvg ? Math.min(width - pad * 2, 900) : 0;
            const height = pad + qrSize + (barSvg ? pad + barHeight + labelHeight : 0) + pad;
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, width, height);
            if (qrSvg) {
                const img = await loadImage(qrSvg);
                ctx.drawImage(img, (width - qrSize) / 2, pad, qrSize, qrSize);
            }
            if (barSvg) {
                const barY = qrSvg ? pad + qrSize + pad : pad;
                const img = await loadImage(barSvg);
                ctx.drawImage(img, pad, barY, width - pad * 2, barHeight);
                ctx.fillStyle = '#27272a';
                ctx.font = '600 52px ui-monospace, monospace';
                ctx.textAlign = 'center';
                ctx.fillText(box.dataset.barcodeValue || '', width / 2, barY + barHeight + 62);
            }
            const a = document.createElement('a');
            a.download = box.dataset.filename || 'codigo.jpg';
            a.href = canvas.toDataURL('image/jpeg', 0.92);
            document.body.appendChild(a);
            a.click();
            a.remove();
        })();
    } }">
    <flux:modal name="qr-modal" class="max-w-md" wire:close="closeCode">
        <flux:heading>
            @if ($isBarcodeOnly)
                Código de barras
            @else
                Código QR
            @endif
        </flux:heading>
        <flux:subheading>{{ $qrName ?? 'Enlace público' }}</flux:subheading>

        @if ($showQr || $showBarcode)
            <div class="mt-4 rounded-2xl bg-white p-4" x-ref="qrBox"
                data-filename="{{ $codeFilename ?? 'codigo' }}{{ $filenameSuffix }}"
                data-barcode-value="{{ $barcodeValue }}">

                @if ($showQr)
                    <div data-qr class="[&_svg]:h-auto [&_svg]:w-full">
                        {!! $qrSvg !!}
                    </div>
                @endif

                @if ($showBarcode)
                    <div data-barcode class="{{ $showQr ? 'mt-2' : '' }} [&_svg]:h-auto [&_svg]:w-full">
                        {!! $barcodeSvg !!}
                    </div>
                    <p class="mt-1 text-center font-mono text-sm tracking-widest text-zinc-700">{{ $barcodeValue }}</p>
                @endif
            </div>

            @if ($showQr)
                <p class="mt-3 break-all font-mono text-xs text-zinc-500" x-ref="qrUrlText">{{ $qrUrl }}</p>
            @endif

            <div class="mt-4 flex flex-wrap items-center gap-2">
                <flux:button variant="primary" icon="arrow-down-tray" @click="downloadCode($refs.qrBox)">
                    Descargar JPEG
                </flux:button>
                @if ($showQr)
                    <flux:button
                        icon="link"
                        @click="navigator.clipboard.writeText($refs.qrUrlText.innerText); copied = true; setTimeout(() => copied = false, 2000)">
                        <span x-show="!copied">Copiar enlace</span>
                        <span x-show="copied" x-cloak>¡Copiado!</span>
                    </flux:button>
                @endif
            </div>
        @endif
    </flux:modal>
</div>