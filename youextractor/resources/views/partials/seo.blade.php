{{--
    Shared SEO meta block (same pattern as Gigora): every public page passes
    unique title, meta_description, and meta_keywords.
--}}
@props([
    'title',
    'description',
    'type' => 'website',
    'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
    'image' => null,
    'canonical' => null,
    'published' => null,
    'modified' => null,
    'keywords' => null,
])

@php
    $base = rtrim(config('app.url') ?: 'https://youextractor.me', '/');
    $path = trim(request()->path(), '/');
    $canonical = $canonical ?: ($base . ($path === '' ? '/' : '/' . $path));
    $image = $image ?: ($base . '/img/app-screenshot-2.png');
    $seoTitle = trim(strip_tags((string) $title));
    $seoDesc = \Illuminate\Support\Str::limit(trim(strip_tags((string) $description)), 160, '');
    $seoKeywords = trim(strip_tags((string) ($keywords ?: 'how to extract code from YouTube tutorial, what is YouExtractor, why extract code from video, who uses YouExtractor, when to use YouTube code extractor, where to download extracted repo, which frameworks supported, can I push to GitHub, could I convert video to project, should I use YouExtractor, would it work on long courses, does it extract dependencies, do I need extension, YouTube code extractor tool, AI code extractor, YouTube to GitHub, copy code from coding video, learn programming faster, YouExtractor, youextractor, youextractor.me')));
@endphp

<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDesc }}">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="robots" content="{{ $robots }}">
<meta name="googlebot" content="{{ $robots }}">
<meta name="application-name" content="YouExtractor">
<meta name="apple-mobile-web-app-title" content="YouExtractor">
<meta name="author" content="YouExtractor">
<meta name="language" content="English">
<link rel="canonical" href="{{ $canonical }}">
<link rel="alternate" type="application/xml" title="Sitemap" href="{{ $base }}/sitemap.xml">

<meta property="og:site_name" content="YouExtractor">
<meta property="og:locale" content="en_US">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ \Illuminate\Support\Str::limit($seoTitle, 65, '') }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit($seoDesc, 200, '') }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:secure_url" content="{{ $image }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="YouExtractor — AI that turns YouTube coding tutorials into runnable code projects">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ \Illuminate\Support\Str::limit($seoTitle, 65, '') }}">
<meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($seoDesc, 200, '') }}">
<meta name="twitter:image" content="{{ $image }}">
<meta name="twitter:image:alt" content="YouExtractor dashboard extracting a YouTube coding tutorial into a real project">

@if($published)
<meta property="article:published_time" content="{{ $published }}">
@endif
@if($modified)
<meta property="article:modified_time" content="{{ $modified }}">
@endif
