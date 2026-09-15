{{-- Modal compartido: QR de la URL pública (productos y marcas). Requiere el trait GeneratesQrCodes. --}}
<div @qr-ready.window="document.dispatchEvent(new CustomEvent('modal-show', { detail: { name: 'qr-modal' } }))"
    x-data="{ copied: false, downloadQr(box) {
        const svg = box.querySelector('svg');
        if (!svg) return;
        const xml = new XMLSerializer().serializeToString(svg);
        const img = new Image();
        img.onload = () => {
            const size = 1024;
            const canvas = document.createElement('canvas');
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, size, size);
            ctx.drawImage(img, 0, 0, size, size);
            const a = document.createElement('a');
            a.download = box.dataset.filename || 'qr.jpg';
            a.href = canvas.toDataURL('image/jpeg', 0.92);
            document.body.appendChild(a);
            a.click();
            a.remove();
        };
        img.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(xml);
    } }">
    <flux:modal name="qr-modal" class="max-w-md" wire:close="closeQr">
        <flux:heading>Código QR</flux:heading>
        <flux:subheading>{{ $qrName ?? 'Enlace público' }}</flux:subheading>

        @if ($qrSvg && $qrUrl)
            <div class="mt-4 rounded-2xl bg-white p-4 [&_svg]:h-auto [&_svg]:w-full" x-ref="qrBox"
                data-filename="{{ $qrFilename }}">
                {!! $qrSvg !!}
            </div>

            <p class="mt-3 break-all font-mono text-xs text-zinc-500" x-ref="qrUrlText">{{ $qrUrl }}</p>

            <div class="mt-4 flex flex-wrap items-center gap-2">
                <flux:button variant="primary" icon="arrow-down-tray" @click="downloadQr($refs.qrBox)">
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
