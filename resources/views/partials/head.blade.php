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

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://unpkg.com">

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
