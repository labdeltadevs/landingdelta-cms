@props([
    'metaTitle' => null,
    'metaDescription' => null,
    'ogImage' => null,
    'canonical' => null,
    'jsonLd' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    {{-- AOS local, non-blocking: solo para animaciones below-fold --}}
    <link rel="preload" href="{{ asset('aos.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('aos.css') }}"></noscript>
</head>

<body class="bg-white text-zinc-900 font-sans antialiased">
    {{-- Floating Badge "Hecho en Bolivia" --}}
    {{-- <div class="group fixed top-20 right-5 z-40 hidden sm:block bg-white rounded-full" data-aos="flip-down" data-aos-delay="800">
        <img src="{{ Storage::disk('public')->url('hecho_en_bolivia.png') }}" alt="Hecho en Bolivia"
            class="h-16 w-16 rounded-full object-cover ring-2 ring-white/50 shadow-lg
                        transition-all duration-300 group-hover:scale-110
                        group-hover:ring-[#ff671f] group-hover:drop-shadow-lg
                        group-hover:shadow-[#ff671f]/20 cursor-pointer" />
    </div> --}}

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <header x-cloak class="sticky top-0 z-50 w-full border-b border-white/20 bg-white/70 backdrop-blur-xl"
        x-data="{ mobileOpen: false }" @click.away="mobileOpen = false">
        <div class="mx-auto flex h-16 max-w-7xl items-center px-4 sm:px-6 lg:px-8">
            {{-- Spacer for floating logo --}}
            <div class="hidden md:block w-16 sm:w-20 flex-shrink-0"></div>

            {{-- Desktop Nav — Dropdown + pills --}}
            <nav class="relative hidden md:flex flex-1 items-center justify-center gap-1 text-sm font-medium">
                {{-- Floating Logo Button --}}
                <a href="{{ route('public.home') }}"
                    class="absolute -bottom-7 left-0 z-20 flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-lg shadow-gray-400 ring-1 ring-white transition-all duration-500 hover:scale-125 hover:shadow-[#ff671f]/10 hover:ring-[#ff671f]/30 group">
                    <img src="{{ Storage::disk('public')->url('matraz_naranja_no_bg.png') }}" alt="Laboratorios Delta"
                        class="h-auto w-auto object-contain transition-transform duration-300" />
                </a>

                {{-- Standalone pills: Productos, Marcas, Catálogos --}}
                <a href="{{ route('public.products.index') }}"
                    class="group relative inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition-all duration-300
                           {{ request()->routeIs('public.products.*') ? 'bg-gradient-to-r from-[#ff671f] to-[#e85c1a] text-white shadow-lg shadow-[#ff671f]/10 scale-105 ring-1 ring-white/20' : 'bg-white/20 backdrop-blur-lg border border-white/30 text-zinc-600 shadow-sm hover:bg-white/40 hover:border-white/50 hover:text-zinc-900 hover:shadow-md' }}">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6"
                        fill="none" stroke="#ff671f" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                    <span>Productos</span>
                </a>
                <a href="{{ route('public.brands.index') }}"
                    class="group relative inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition-all duration-300
                           {{ request()->routeIs('public.brands.*') ? 'bg-gradient-to-r from-[#ff671f] to-[#e85c1a] text-white shadow-lg shadow-[#ff671f]/25 scale-105 ring-1 ring-white/20' : 'bg-white/20 backdrop-blur-lg border border-white/30 text-zinc-600 shadow-sm hover:bg-white/40 hover:border-white/50 hover:text-zinc-900 hover:shadow-md' }}">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6"
                        fill="none" stroke="#ff671f" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72L4.318 3.44A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72m-13.5 8.65h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .415.336.75.75.75Z" />
                    </svg>
                    <span>Divisiones</span>
                </a>
                <a href="{{ route('public.brochures.index') }}"
                    class="group relative inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition-all duration-300
                           {{ request()->routeIs('public.brochures.*') ? 'bg-gradient-to-r from-[#ff671f] to-[#e85c1a] text-white shadow-lg shadow-[#ff671f]/25 scale-105 ring-1 ring-white/20' : 'bg-white/20 backdrop-blur-lg border border-white/30 text-zinc-600 shadow-sm hover:bg-white/40 hover:border-white/50 hover:text-zinc-900 hover:shadow-md' }}">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6"
                        fill="none" stroke="#ff671f" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span>Rotafolios</span>
                </a>

                {{-- Nosotros Dropdown (Historia, Trabaja con Nosotros, Noticias, Contacto) --}}
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                    <button @click="open = !open"
                        class="group relative inline-flex items-center gap-1.5 rounded-full px-5 py-2 text-sm font-medium transition-all duration-300
                                   {{ request()->routeIs('public.about') || request()->routeIs('public.work-with-us') || request()->routeIs('public.news.*') || request()->routeIs('public.contact') ? 'bg-gradient-to-r from-[#ff671f] to-[#e85c1a] text-white shadow-lg shadow-[#ff671f]/25 scale-105 ring-1 ring-white/20' : 'bg-white/20 backdrop-blur-lg border border-white/30 text-zinc-600 shadow-sm hover:bg-white/40 hover:border-white/50 hover:text-zinc-900 hover:shadow-md' }}">
                        <svg class="h-4 w-4 transition-transform duration-300 group-hover:scale-110" fill="none"
                            stroke="#ff671f" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                        </svg>
                        <span>Nosotros</span>
                        <svg class="h-3 w-3 transition-all duration-300"
                            :class="{ 'rotate-180': open, 'group-hover:translate-y-0.5': !open }" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    {{-- Dropdown menu --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95" @click.away="open = false"
                        class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-56 rounded-2xl border border-white/40 bg-white p-2 shadow-2xl shadow-zinc-900/10 backdrop-blur-2xl ring-1 ring-white/30"
                        style="display: none;">
                        {{-- Arrow --}}
                        <div
                            class="absolute -top-2 left-1/2 -translate-x-1/2 h-3 w-3 rotate-45 border-l border-t border-white/40 bg-white/95 backdrop-blur-sm">
                        </div>

                        {{-- Historia --}}
                        <a href="{{ route('public.about') }}" @click="open = false"
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('public.about') ? 'bg-gradient-to-r from-[#ff671f]/15 to-transparent text-[#ff671f] font-semibold ring-1 ring-[#ff671f]/20' : 'text-zinc-600 hover:bg-white/50 hover:text-zinc-900 hover:pl-5 hover:backdrop-blur-sm' }}">
                            <svg class="h-4 w-4 flex-shrink-0 transition-transform duration-200 group-hover:scale-110"
                                fill="none" stroke="#ff671f" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span>Historia</span>
                        </a>

                        {{-- Trabaja con Nosotros --}}
                        <a href="{{ route('public.work-with-us') }}" @click="open = false"
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('public.work-with-us') ? 'bg-gradient-to-r from-[#ff671f]/15 to-transparent text-[#ff671f] font-semibold ring-1 ring-[#ff671f]/20' : 'text-zinc-600 hover:bg-white/50 hover:text-zinc-900 hover:pl-5 hover:backdrop-blur-sm' }}">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="#ff671f" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                            </svg>
                            <span>Trabaja con Nosotros</span>
                        </a>

                        {{-- Noticias --}}
                        <a href="{{ route('public.news.index') }}" @click="open = false"
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('public.news.*') ? 'bg-gradient-to-r from-[#ff671f]/15 to-transparent text-[#ff671f] font-semibold ring-1 ring-[#ff671f]/20' : 'text-zinc-600 hover:bg-white/50 hover:text-zinc-900 hover:pl-5 hover:backdrop-blur-sm' }}">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="#ff671f" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                            </svg>
                            <span>Noticias</span>
                        </a>

                        {{-- Contacto --}}
                        <a href="{{ route('public.contact') }}" @click="open = false"
                            class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('public.contact') ? 'bg-gradient-to-r from-[#ff671f]/15 to-transparent text-[#ff671f] font-semibold ring-1 ring-[#ff671f]/20' : 'text-zinc-600 hover:bg-white/50 hover:text-zinc-900 hover:pl-5 hover:backdrop-blur-sm' }}">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="#ff671f" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            <span>Contacto</span>
                        </a>
                    </div>
                </div>
            </nav>

            {{-- Right: Search + Mobile Hamburger --}}
            <div class="flex items-center gap-2">
                <livewire:public.search.product-search />

                {{-- Mobile Hamburger --}}
                <button @click="mobileOpen = !mobileOpen" aria-label="Menú de navegación"
                    class="md:hidden flex h-9 w-9 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 transition-all hover:border-[#ff671f]/30 hover:text-[#ff671f]">
                    <svg x-show="!mobileOpen" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="mobileOpen" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-zinc-200/30 bg-white/95 backdrop-blur-xl"
            @keydown.escape.window="mobileOpen = false">
            <nav class="mx-auto max-w-7xl px-4 py-4 space-y-1 text-sm" x-data="{ nosotrosOpen: false }">
                {{-- Productos --}}
                <a href="{{ route('public.products.index') }}" @click="mobileOpen = false"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition hover:bg-zinc-100 {{ request()->routeIs('public.products.*') ? 'text-[#ff671f] font-semibold bg-orange-50' : 'text-zinc-600' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                    Productos
                </a>

                {{-- Marcas --}}
                <a href="{{ route('public.brands.index') }}" @click="mobileOpen = false"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition hover:bg-zinc-100 {{ request()->routeIs('public.brands.*') ? 'text-[#ff671f] font-semibold bg-orange-50' : 'text-zinc-600' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72L4.318 3.44A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72m-13.5 8.65h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .415.336.75.75.75Z" />
                    </svg>
                    Divisiones
                </a>

                {{-- Catálogos --}}
                <a href="{{ route('public.brochures.index') }}" @click="mobileOpen = false"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition hover:bg-zinc-100 {{ request()->routeIs('public.brochures.*') ? 'text-[#ff671f] font-semibold bg-orange-50' : 'text-zinc-600' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Catálogos
                </a>

                <hr class="my-2 border-zinc-100" />

                {{-- Nosotros group header (accordion) --}}
                <button @click="nosotrosOpen = !nosotrosOpen"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 transition hover:bg-zinc-100 {{ request()->routeIs('public.about') || request()->routeIs('public.work-with-us') || request()->routeIs('public.news.*') || request()->routeIs('public.contact') ? 'text-[#ff671f] font-semibold bg-orange-50' : 'text-zinc-600' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                    </svg>
                    <span class="flex-1 text-left">Nosotros</span>
                    <svg class="h-3 w-3 transition-transform duration-200" :class="{ 'rotate-180': nosotrosOpen }"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div x-show="nosotrosOpen" class="ml-4 space-y-0.5">
                    <a href="{{ route('public.about') }}" @click="mobileOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-zinc-100 {{ request()->routeIs('public.about') ? 'text-[#ff671f] font-semibold bg-orange-50' : 'text-zinc-600' }}">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        Historia
                    </a>
                    <a href="{{ route('public.work-with-us') }}" @click="mobileOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-zinc-100 {{ request()->routeIs('public.work-with-us') ? 'text-[#ff671f] font-semibold bg-orange-50' : 'text-zinc-600' }}">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                        </svg>
                        Trabaja con Nosotros
                    </a>
                    <a href="{{ route('public.news.index') }}" @click="mobileOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-zinc-100 {{ request()->routeIs('public.news.*') ? 'text-[#ff671f] font-semibold bg-orange-50' : 'text-zinc-600' }}">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                        </svg>
                        Noticias
                    </a>
                    <a href="{{ route('public.contact') }}" @click="mobileOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-zinc-100 {{ request()->routeIs('public.contact') ? 'text-[#ff671f] font-semibold bg-orange-50' : 'text-zinc-600' }}">
                        <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                        Contacto
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>

    @php
        $companyName = App\Models\SiteSetting::get('company_name', 'Laboratorios Delta S.A.');
        $companySlogan = App\Models\SiteSetting::get(
            'company_slogan',
            'Soluciones de calidad para la industria farmacéutica y veterinaria.',
        );

        $legalNoticeRaw = App\Models\SiteSetting::get('legal_notice');
        if (is_array($legalNoticeRaw)) {
            $legalNotice = $legalNoticeRaw['body'] ?? 'Todos los derechos reservados.';
        } else {
            $legalNotice = $legalNoticeRaw ?: 'Todos los derechos reservados.';
        }

        $branches = \App\Models\Branch::query()->active()->get();

        $socialIcons = [
            'linkedin' => [
                'label' => 'LinkedIn',
                'svg' =>
                    '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
            ],
            'facebook' => [
                'label' => 'Facebook',
                'svg' =>
                    '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            ],
            'instagram' => [
                'label' => 'Instagram',
                'svg' =>
                    '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913a5.885 5.885 0 0 0 1.384 2.126A5.868 5.868 0 0 0 4.14 23.37c.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558a5.898 5.898 0 0 0 2.126-1.384 5.86 5.86 0 0 0 1.384-2.126c.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913a5.89 5.89 0 0 0-1.384-2.126A5.847 5.847 0 0 0 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227a3.81 3.81 0 0 1-.899 1.382 3.744 3.744 0 0 1-1.38.896c-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421a3.716 3.716 0 0 1-1.379-.899 3.644 3.644 0 0 1-.9-1.38c-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 1 0 0-12.324zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405a1.441 1.441 0 1 1-2.882 0 1.441 1.441 0 0 1 2.882 0z"/></svg>',
            ],
            'tiktok' => [
                'label' => 'TikTok',
                'svg' =>
                    '<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
            ],
        ];
    @endphp

    <footer class="relative overflow-hidden border-t border-zinc-200 bg-white text-zinc-600">
        {{-- Línea de acento superior --}}
        <div class="absolute inset-x-0 top-0 h-[3px] bg-gradient-to-r from-[#ff671f] via-orange-400 to-amber-500">
        </div>

        {{-- Resplandor ambiental sutil --}}
        <div class="pointer-events-none absolute -top-24 left-1/4 h-72 w-72 rounded-full bg-[#ff671f]/5 blur-3xl">
        </div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-56 w-56 rounded-full bg-[#ff671f]/5 blur-3xl">
        </div>

        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-12">
                {{-- Marca --}}
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="text-base font-bold tracking-tight text-zinc-900">{{ $companyName }}</p>
                            <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-[#ff671f]">
                                {{ $companySlogan ?? 'Calidad y Confianza' }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-5 max-w-sm text-justify text-sm leading-relaxed text-zinc-500">
                        {{ $legalNotice }}
                    </p>

                    @if ($branches->isNotEmpty())
                        <a href="{{ route('public.contact') }}"
                            class="mt-6 inline-flex items-center gap-2 rounded-full border border-zinc-200 bg-zinc-50 px-5 py-2.5 text-xs font-semibold text-zinc-700 transition-all duration-300 hover:bg-white hover:text-zinc-900 hover:border-[#ff671f]/60 hover:shadow-md">
                            Contactar con un asesor
                            <svg class="h-3.5 w-3.5 text-[#ff671f]" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                </div>

                {{-- Enlaces --}}
                <div class="lg:col-span-2">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-900">Enlaces</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li>
                            <a href="{{ route('public.home') }}"
                                class="group inline-flex items-center gap-2 text-zinc-500 transition-colors hover:text-[#e55a1a]">
                                <span
                                    class="h-px w-3 bg-zinc-300 transition-all duration-300 group-hover:w-5 group-hover:bg-[#ff671f]"></span>
                                Inicio
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('public.products.index') }}"
                                class="group inline-flex items-center gap-2 text-zinc-500 transition-colors hover:text-[#e55a1a]">
                                <span
                                    class="h-px w-3 bg-zinc-300 transition-all duration-300 group-hover:w-5 group-hover:bg-[#ff671f]"></span>
                                Productos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('public.about') }}"
                                class="group inline-flex items-center gap-2 text-zinc-500 transition-colors hover:text-[#e55a1a]">
                                <span
                                    class="h-px w-3 bg-zinc-300 transition-all duration-300 group-hover:w-5 group-hover:bg-[#ff671f]"></span>
                                Nosotros
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('public.contact') }}"
                                class="group inline-flex items-center gap-2 text-zinc-500 transition-colors hover:text-[#e55a1a]">
                                <span
                                    class="h-px w-3 bg-zinc-300 transition-all duration-300 group-hover:w-5 group-hover:bg-[#ff671f]"></span>
                                Contacto
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Sucursales --}}
                <div class="lg:col-span-3">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-900">Sucursales</h3>
                    <ul class="mt-5 space-y-4">
                        @forelse ($branches as $branch)
                            <li class="flex items-start gap-3">
                                <div
                                    class="mt-0.5 flex h-7 w-7 flex-none items-center justify-center rounded-lg bg-orange-50 text-[#ff671f] ring-1 ring-orange-100">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-zinc-800">{{ $branch->name }}</p>
                                    @if ($branch->phone)
                                        <a href="tel:{{ $branch->phone }}"
                                            class="mt-0.5 block text-xs text-zinc-500 transition-colors hover:text-[#ff671f]">
                                            {{ $branch->phone }}
                                        </a>
                                    @endif
                                    @if (!empty($branch->address))
                                        <p class="mt-0.5 text-xs text-zinc-400">{{ $branch->address }}</p>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-zinc-500">Consultar sucursales en contacto.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Redes sociales --}}
                <div class="lg:col-span-3">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-900">Síguenos</h3>
                    <p class="mt-5 text-sm leading-relaxed text-zinc-500">
                        Conoce nuestras novedades, promociones y contenido exclusivo.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        @php $socialActive = true; @endphp
                        @foreach ($socialIcons as $key => $social)
                            @php
                                $socialUrl = App\Models\SiteSetting::get('social_' . $key);
                            @endphp
                            @if ($socialUrl)
                                @php $socialActive = true; @endphp
                                <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer"
                                    aria-label="{{ $social['label'] }}"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600 ring-1 ring-zinc-200 transition-all duration-300 hover:scale-105 hover:text-white
                                    {{ $key === 'linkedin' ? 'hover:bg-[#0A66C2] hover:ring-[#0A66C2]' : '' }}
                                    {{ $key === 'facebook' ? 'hover:bg-[#1877F2] hover:ring-[#1877F2]' : '' }}
                                    {{ $key === 'instagram' ? 'hover:bg-gradient-to-br hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:ring-[#dc2743]' : '' }}
                                    {{ $key === 'tiktok' ? 'hover:bg-black hover:ring-zinc-700' : '' }}">
                                    {!! $social['svg'] !!}
                                </a>
                            @endif
                        @endforeach
                        @if (!$socialActive)
                            <p class="text-sm text-zinc-500">Próximamente en redes sociales.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Línea inferior --}}
            <div
                class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-zinc-200 pt-6 sm:flex-row">
                <p class="text-xs text-zinc-500">
                    &copy; {{ date('Y') }} - <span class="font-semibold text-zinc-800">{{ $companyName }}</span>. Todos los derechos reservados.
                </p>
                <p class="text-xs text-zinc-400">
                    Desarrollado por el departamento de <span class="font-semibold text-zinc-600">T.I.C.</span>
                </p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('aos.js') }}" defer></script>
    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: false,
                offset: 80,
            });
        });
    </script>
</body>

</html>
