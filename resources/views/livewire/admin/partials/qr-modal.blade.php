{{-- Modal compartido: QR + código de barras de la URL pública y el código interno (productos y marcas). Requiere el trait GeneratesQrCodes. --}}
<div @qr-ready.window="document.dispatchEvent(new CustomEvent('modal-show', { detail: { name: 'qr-modal' } }))"
    x-data="{ copied: false, downloadCodes(box) {
        const loadImage = (svg) => new Promise((resolve, reject) => {
            const xml = new XMLSerializer().serializeToString(svg);
            const img = new Image();
            img.onload = () => resolve(img);
            img.onerror = reject;
            img.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(xml);
        });
        (async () => {
            const qrSvg = box.querySelector('[data-qr] svg');
            const barSvg = box.querySelector('[data-barcode] svg');
            if (!qrSvg || !barSvg) return;
            const width = 1024;
            const qrSize = 900;
            const barHeight = 220;
            const labelHeight = 90;
            const pad = 48;
            const height = pad + qrSize + pad + barHeight + labelHeight + pad;
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, width, height);
            const qrImg = await loadImage(qrSvg);
            ctx.drawImage(qrImg, (width - qrSize) / 2, pad, qrSize, qrSize);
            const barImg = await loadImage(barSvg);
            ctx.drawImage(barImg, pad, pad + qrSize + pad, width - pad * 2, barHeight);
            ctx.fillStyle = '#27272a';
            ctx.font = '600 52px ui-monospace, monospace';
            ctx.textAlign = 'center';
            ctx.fillText(box.dataset.barcodeValue || '', width / 2, pad + qrSize + pad + barHeight + 62);
            const a = document.createElement('a');
            a.download = box.dataset.filename || 'codigo.jpg';
            a.href = canvas.toDataURL('image/jpeg', 0.92);
            document.body.appendChild(a);
            a.click();
            a.remove();
        })();
    } }">
    <flux:modal name="qr-modal" class="max-w-md" wire:close="closeQr">
        <flux:heading>Código QR</flux:heading>
        <flux:subheading>{{ $qrName ?? 'Enlace público' }}</flux:subheading>

        @if ($qrSvg && $qrUrl)
            <div class="mt-4 rounded-2xl bg-white p-4" x-ref="qrBox" data-filename="{{ $qrFilename }}"
                data-barcode-value="{{ $barcodeValue }}">
                <div data-qr class="[&_svg]:h-auto [&_svg]:w-full">
                    {!! $qrSvg !!}
                </div>
                @if ($barcodeSvg)
                    <div data-barcode class="mt-2 [&_svg]:h-auto [&_svg]:w-full">
                        {!! $barcodeSvg !!}
                    </div>
                    <p class="mt-1 text-center font-mono text-sm tracking-widest text-zinc-700">{{ $barcodeValue }}</p>
                @endif
            </div>

            <p class="mt-3 break-all font-mono text-xs text-zinc-500" x-ref="qrUrlText">{{ $qrUrl }}</p>

            <div class="mt-4 flex flex-wrap items-center gap-2">
                <flux:button variant="primary" icon="arrow-down-tray" @click="downloadCodes($refs.qrBox)">
                    Descargar JPEG
                </flux:button>
                <flux:button
                    icon="link"
                    @click="navigator.clipboard.writeText($refs.qrUrlText.innerText); copied = true; setTimeout(() => copied = false, 2000)">
                    <span x-show="!copied">Copiar enlace</span>
                    <span x-show="copied" x-cloak>¡Copiado!</span>
                </flux:button>
            </div>
        @endif
    </flux:modal>
</div>
