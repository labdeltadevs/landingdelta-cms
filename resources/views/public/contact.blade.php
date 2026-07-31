<x-layouts::public
    metaTitle="Contacto"
    metaDescription="Encontrá las oficinas, sucursales y datos de contacto de Laboratorios Delta S.A. en La Paz, Santa Cruz, Cochabamba y Chuquisaca.">
    @php $branches = \App\Models\Branch::query()->active()->ordered()->get(); @endphp
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-zinc-900">Contacto</h1>
        <p class="mt-2 text-sm text-zinc-500">Encuentra nuestras oficinas y sucursales.</p>
        <div class="mt-8 grid gap-6 sm:grid-cols-2">
            @forelse ($branches as $branch)
                <div class="rounded-xl border border-zinc-200 p-6">
                    <h2 class="text-lg font-semibold text-zinc-900">{{ $branch->name }}</h2>
                    <p class="mt-1 text-sm text-zinc-500">{{ $branch->city }}</p>
                    <p class="mt-2 text-sm text-zinc-600">{{ $branch->address }}</p>
                    @if ($branch->phone)<p class="mt-1 text-sm text-zinc-600">Tel: {{ $branch->phone }}</p>@endif
                    @if ($branch->phone_2)<p class="text-sm text-zinc-600">Cel: {{ $branch->phone_2 }}</p>@endif
                    @if ($branch->email)<p class="mt-1 text-sm text-orange-600">{{ $branch->email }}</p>@endif
                </div>
            @empty
                <div class="col-span-full py-20 text-center text-zinc-400">No hay sucursales registradas.</div>
            @endforelse
        </div>
    </div>
</x-layouts::public>
