<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-white text-zinc-900 font-sans antialiased">
        <header class="sticky top-0 z-50 w-full border-b border-zinc-200/50 bg-white/80 backdrop-blur-lg">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ route('public.home') }}" class="text-lg font-bold tracking-tight">{{ config('app.name', 'Laboratorios Delta S.A.') }}</a>
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-zinc-600">
                    <a href="{{ route('public.home') }}" class="hover:text-zinc-900 transition">Inicio</a>
                    <a href="{{ route('public.products.index') }}" class="hover:text-zinc-900 transition">Productos</a>
                    <a href="{{ route('public.brands.index') }}" class="hover:text-zinc-900 transition">Marcas</a>
                    <a href="{{ route('public.about') }}" class="hover:text-zinc-900 transition">Nosotros</a>
                    <a href="{{ route('public.brochures.index') }}" class="hover:text-zinc-900 transition">Rotafolios</a>
                    <a href="{{ route('public.news.index') }}" class="hover:text-zinc-900 transition">Noticias</a>
                    <a href="{{ route('public.contact') }}" class="hover:text-zinc-900 transition">Contacto</a>
                </nav>
                <livewire:public.search.product-search />
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="border-t border-zinc-200 bg-zinc-50 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900">{{ App\Models\SiteSetting::get('company_name', 'Laboratorios Delta S.A.') }}</h3>
                        <p class="mt-2 text-xs text-zinc-500">{{ App\Models\SiteSetting::get('legal_notice.body') ?? 'Todos los derechos reservados.' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900">Enlaces</h3>
                        <ul class="mt-2 space-y-1 text-xs text-zinc-500">
                            <li><a href="{{ route('public.home') }}" class="hover:text-zinc-900">Inicio</a></li>
                            <li><a href="{{ route('public.products.index') }}" class="hover:text-zinc-900">Productos</a></li>
                            <li><a href="{{ route('public.about') }}" class="hover:text-zinc-900">Nosotros</a></li>
                            <li><a href="{{ route('public.contact') }}" class="hover:text-zinc-900">Contacto</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900">Contacto</h3>
                        @php $branches = \App\Models\Branch::query()->active()->get(); @endphp
                        @foreach ($branches->take(2) as $branch)
                            <p class="mt-1 text-xs text-zinc-500">{{ $branch->name }} - {{ $branch->phone }}</p>
                        @endforeach
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900">Redes sociales</h3>
                        <p class="mt-2 text-xs text-zinc-500">Síguenos en nuestras redes sociales para estar al día.</p>
                    </div>
                </div>
                <div class="mt-8 border-t border-zinc-200 pt-6 text-center text-xs text-zinc-400">
                    &copy; {{ date('Y') }} {{ App\Models\SiteSetting::get('company_name', 'Laboratorios Delta S.A.') }}. Todos los derechos reservados.
                </div>
            </div>
        </footer>
    </body>
</html>
