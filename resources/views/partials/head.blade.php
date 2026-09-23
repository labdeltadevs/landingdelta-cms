<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php
    $siteName = config('app.name', 'Laboratorios Delta S.A.');
    $pageTitle = filled($metaTitle ?? $title ?? null) ? ($metaTitle ?? $title).' - '.$siteName : $siteName;
    $pageDescription = $metaDescription ?? 'Laboratorios Delta S.A. es líder en la industria farmacéutica boliviana.';
    $pageImage = $ogImage ?? Storage::disk('public')->url('logo_delta.png');
    $canonicalUrl = $canonical ?? url()->current();
@endphp

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<meta name="google-site-verification" content="yXTZNPRLXiXWlLXPiGvs8D6Li_btH3rbB3otZL1WhqI" />

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:image" content="{{ $pageImage }}">
<meta property="og:url" content="{{ $canonicalUrl }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
<meta name="twitter:image" content="{{ $pageImage }}">

<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">

@if (request()->routeIs('public.home'))
    <link rel="preload" as="image" type="image/webp" href="{{ Storage::disk('public')->url('fondo-a-1920.webp') }}" fetchpriority="high">
@endif

@fonts

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Organization",
    "name": "{{ $siteName }}",
    "url": "{{ config('app.url') }}",
    "logo": "{{ Storage::disk('public')->url('logo_delta.png') }}",
    "description": "{{ $pageDescription }}",
    "foundingDate": "1987",
    "contactPoint": {
        "@@type": "ContactPoint",
        "telephone": "+591-2-2411516",
        "contactType": "customer service",
        "areaServed": "BO"
    },
    "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Calle Presbítero Medina, Pasaje Tal Tal Nro. 2, Zona Sopocachi",
        "addressLocality": "La Paz",
        "addressCountry": "BO"
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "{{ $siteName }}",
    "url": "{{ config('app.url') }}",
    "description": "{{ $pageDescription }}",
    "potentialAction": {
        "@@type": "SearchAction",
        "target": {
            "@@type": "EntryPoint",
            "urlTemplate": "{{ config('app.url') }}/productos?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

@if (isset($jsonLd) && $jsonLd)
<script type="application/ld+json">{!! $jsonLd !!}</script>
@endif

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@fluxAppearance
