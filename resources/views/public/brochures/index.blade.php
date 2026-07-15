<x-layouts::public>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-zinc-900">Rotafolios</h1>
        <p class="mt-2 text-sm text-zinc-500">Descarga los rotafolios de las diferentes líneas de productos.</p>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($brochures as $brochure)
                <div class="rounded-xl border border-zinc-200 p-6 transition hover:shadow-sm">
                    <h3 class="font-semibold text-zinc-900">{{ $brochure->title }}</h3>
                    @if ($brochure->line)
                        <p class="mt-1 text-xs text-zinc-500">{{ $brochure->line }}</p>
                    @endif
                    @if ($brochure->description)
                        <p class="mt-2 text-sm text-zinc-600">{{ $brochure->description }}</p>
                    @endif
                    <a href="{{ $brochure->file_url }}" target="_blank" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-orange-600 hover:text-orange-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Descargar PDF
                    </a>
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-zinc-400">No hay rotafolios disponibles.</div>
            @endforelse
        </div>
    </div>
</x-layouts::public>
