<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

@if ($siteSettings->logo_url)
    <link rel="icon" href="{{ $siteSettings->logo_url }}?v={{ $siteSettings->updated_at?->timestamp ?? 1 }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ $siteSettings->logo_url }}?v={{ $siteSettings->updated_at?->timestamp ?? 1 }}">
@else
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
@endif

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
