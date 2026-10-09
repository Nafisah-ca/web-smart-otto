@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title)

@push('styles')
{{-- SEO Meta --}}
<meta name="description" content="{{ $post->meta_description ?: $post->excerpt }}">
<meta name="author"      content="{{ $post->author_name }}">
<link rel="canonical"    href="{{ route('blog.show', $post->slug) }}">

{{-- Open Graph --}}
<meta property="og:type"        content="article">
<meta property="og:title"       content="{{ $post->meta_title ?: $post->title }}">
<meta property="og:description" content="{{ $post->meta_description ?: $post->excerpt }}">
<meta property="og:url"         content="{{ route('blog.show', $post->slug) }}">
@if($post->thumbnail_url)
<meta property="og:image" content="{{ asset($post->thumbnail_url) }}">
@endif
<meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
<meta property="article:author"         content="{{ $post->author_name }}">

<style>
    /* ── Blog detail page ── */
    .blog-detail-bg { background-color: #f1f5f9; }

    /* ── Centred content wrapper ── */
    .blog-detail-wrap {
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    @media (min-width: 640px) {
        .blog-detail-wrap { padding-left: 2rem; padding-right: 2rem; }
    }

    /* ── 2-column layout (article + TOC) ── */
    .blog-detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0;
    }
    @media (min-width: 1024px) {
        .blog-detail-grid {
            grid-template-columns: minmax(0, 1fr) 300px;
            column-gap: 2.5rem;
            align-items: start;
        }
    }
    @media (min-width: 1280px) {
        .blog-detail-grid { column-gap: 3rem; }
    }

    .blog-article-col { min-width: 0; }
    /* spacer col tidak dipakai lagi */
    .blog-toc-gap { display: none; }

    /* ── Article body card ── */
    .article-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / .07), 0 1px 2px -1px rgb(0 0 0 / .06);
        padding: 2rem;
    }
    @media (min-width: 768px) { .article-card { padding: 2.5rem 3rem; } }

    /* ── Article body typography ── */
    .prose-article {
        line-height: 1.8;
        color: #374151;
        font-size: 1.0625rem;
    }
    .prose-article h2 {
        font-size: 1.375rem;
        font-weight: 700;
        color: #111827;
        margin-top: 2.25rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #fecdd3;
        scroll-margin-top: 88px;
    }
    .prose-article h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-top: 1.75rem;
        margin-bottom: 0.5rem;
        scroll-margin-top: 88px;
    }
    .prose-article p  { margin-bottom: 1.25rem; }
    .prose-article ul { list-style: disc;    padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .prose-article ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .prose-article li { margin-bottom: 0.4rem; }
    .prose-article a  { color: #e11d48; text-decoration: underline; text-underline-offset: 3px; }
    .prose-article a:hover { color: #be123c; }
    .prose-article blockquote {
        border-left: 3px solid #e11d48;
        background: #fff1f2;
        padding: 0.75rem 1.25rem;
        margin: 1.5rem 0;
        border-radius: 0 0.5rem 0.5rem 0;
        font-style: italic;
        color: #6b7280;
    }
    .prose-article pre {
        background: #f3f4f6;
        border-radius: 0.5rem;
        padding: 1rem 1.25rem;
        overflow-x: auto;
        font-size: 0.875rem;
        margin-bottom: 1.25rem;
    }
    .prose-article code {
        background: #f3f4f6;
        border-radius: 0.25rem;
        padding: 0.15em 0.4em;
        font-size: 0.875em;
    }
    .prose-article img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin: 1.5rem auto;
        display: block;
    }
    .prose-article table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    .prose-article th, .prose-article td {
        border: 1px solid #e5e7eb;
        padding: 0.6rem 0.9rem;
        text-align: left;
    }
    .prose-article th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
    }
    .prose-article figure { margin: 1.5rem 0; }
    .prose-article figcaption { text-align: center; font-size: 0.8rem; color: #9ca3af; margin-top: 0.5rem; }

    /* ── TOC sidebar sticky ── */
    /* aside sendiri yang sticky; div di dalamnya mengalir normal */
    .toc-aside-sticky {
        position: sticky;
        top: 96px;                        /* navbar 64px + sedikit ruang */
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        align-self: start;                /* wajib agar sticky bekerja */
        scrollbar-width: thin;
        scrollbar-color: #e2e8f0 transparent;
    }
    .toc-aside-sticky::-webkit-scrollbar { width: 4px; }
    .toc-aside-sticky::-webkit-scrollbar-track { background: transparent; }
    .toc-aside-sticky::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }

    /* ── TOC links ── */
    .toc-link {
        display: block;
        padding: 0.275rem 0.75rem;
        font-size: 0.8125rem;
        line-height: 1.5;
        color: #6b7280;
        transition: color 0.15s, border-color 0.15s;
        border-left: 2px solid transparent;
    }
    .toc-link:hover { color: #e11d48; }
    .toc-link.toc-active { color: #e11d48; border-left-color: #e11d48; font-weight: 600; }
    .toc-link.toc-h3 { padding-left: 1.5rem; font-size: 0.78rem; color: #9ca3af; }
    .toc-link.toc-h3.toc-active { color: #e11d48; }
</style>
@endpush

@section('content')

<div class="blog-detail-bg min-h-screen">

{{-- BREADCRUMB --}}
<div class="bg-white border-b border-gray-200">
    <div class="blog-detail-wrap py-3">
        <nav class="flex items-center gap-2 text-xs text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-gray-600 transition-colors">Beranda</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('blog.index') }}" class="hover:text-gray-600 transition-colors">Blog</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 truncate max-w-xs">{{ $post->title }}</span>
        </nav>
    </div>
</div>

{{-- MAIN LAYOUT --}}
<div class="blog-detail-wrap py-10">
    <div class="blog-detail-grid">

        {{-- ── ARTICLE COLUMN ── --}}
        <div class="blog-article-col">

            {{-- Article card --}}
            <div class="article-card">

                {{-- Header --}}
                <header class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="inline-flex items-center bg-primary-50 text-primary-600 text-xs font-semibold px-3 py-1 rounded-full">
                            {{ $post->category ?? 'Inspeksi Kendaraan' }}
                        </span>
                    </div>

                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight mb-5">
                        {{ $post->title }}
                    </h1>

                    {{-- Author + Date --}}
                    <div class="flex items-center gap-3 pb-5 border-b border-gray-100">
                        <div class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($post->author_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $post->author_name }}</p>
                            <p class="text-xs text-gray-400">Diterbitkan {{ $post->formatted_date }}</p>
                        </div>
                        <div class="ml-auto flex items-center gap-1.5 text-xs text-gray-400">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $post->formatted_date }}
                        </div>
                    </div>
                </header>

                {{-- Hero Image --}}
                @if($post->thumbnail_url)
                <figure class="mb-8 -mx-8 md:-mx-12 rounded-xl overflow-hidden">
                    <img src="{{ asset($post->thumbnail_url) }}"
                         alt="{{ $post->title }}"
                         class="w-full object-cover"
                         style="max-height:420px;">
                </figure>
                @endif

                {{-- Mobile TOC (injected by JS if headings ≥ 2) --}}
                <div id="toc-mobile-wrapper" class="lg:hidden mb-8 hidden">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                        <button id="toc-mobile-toggle"
                                class="w-full flex items-center justify-between px-5 py-3.5 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors"
                                aria-expanded="true">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h12M4 14h8M4 18h6"/></svg>
                                Daftar Isi
                            </div>
                            <svg id="toc-mobile-chevron" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="toc-mobile-body" class="px-5 pb-4">
                            <nav id="toc-mobile-list" aria-label="Daftar isi artikel"></nav>
                        </div>
                    </div>
                </div>

                {{-- Article Body --}}
                <div id="article-body" class="prose-article">
                    {!! $post->content !!}
                </div>

                {{-- CTA Banner --}}
                @if($post->cta_enabled)
                <div class="mt-10 rounded-2xl overflow-hidden border border-primary-100 shadow-sm">
                    @if($post->cta_image)
                    <div class="relative">
                        <img src="{{ asset($post->cta_image) }}" alt="{{ $post->cta_title }}"
                             class="w-full object-cover" style="max-height:180px;">
                        <div class="absolute inset-0 bg-gray-900/55 flex items-center">
                            <div class="px-6 py-4 flex-1">
                                @if($post->cta_title)
                                <h3 class="text-lg font-bold text-white leading-snug">{{ $post->cta_title }}</h3>
                                @endif
                                @if($post->cta_subtitle)
                                <p class="text-sm text-gray-200 mt-1">{{ $post->cta_subtitle }}</p>
                                @endif
                                @if($post->cta_button_text)
                                <a href="{{ $post->cta_button_url ?: route('booking.create') }}"
                                   class="mt-4 inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                                    {{ $post->cta_button_text }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-7 py-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="flex-1">
                            @if($post->cta_title)
                            <h3 class="text-lg font-bold text-white leading-snug">{{ $post->cta_title }}</h3>
                            @endif
                            @if($post->cta_subtitle)
                            <p class="text-sm text-primary-100 mt-1">{{ $post->cta_subtitle }}</p>
                            @endif
                        </div>
                        @if($post->cta_button_text)
                        <a href="{{ $post->cta_button_url ?: route('booking.create') }}"
                           class="flex-shrink-0 bg-white text-primary-700 hover:bg-primary-50 font-semibold text-sm px-6 py-2.5 rounded-lg transition-colors">
                            {{ $post->cta_button_text }}
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                @endif

                {{-- Tags / Share --}}
                <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                    <span class="inline-flex items-center gap-2 text-xs font-medium text-gray-500">
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full">{{ $post->category }}</span>
                    </span>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400">Bagikan:</span>
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}"
                           target="_blank" rel="noopener"
                           class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 flex items-center justify-center transition-colors"
                           aria-label="Bagikan ke WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('blog.show', $post->slug)) }}"
                           target="_blank" rel="noopener"
                           class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 flex items-center justify-center transition-colors"
                           aria-label="Bagikan ke Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </div>
                </div>

            </div>{{-- end article-card --}}
        </div>{{-- end article column --}}

        {{-- ── GAP COLUMN (tidak terpakai) ── --}}

        {{-- ── TOC SIDEBAR (desktop sticky) ── --}}
        <aside class="hidden lg:block toc-aside-sticky">
            <div id="toc-desktop-wrapper" class="hidden">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                        <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h12M4 14h8M4 18h6"/></svg>
                            Daftar Isi
                        </div>
                        <button id="toc-desktop-toggle" aria-label="Sembunyikan daftar isi"
                                class="text-gray-400 hover:text-gray-600 transition-colors text-xs px-2 py-0.5 rounded border border-gray-200 hover:border-gray-300">
                            ↑ Tutup
                        </button>
                    </div>
                    <nav id="toc-desktop-list" class="py-3 px-1" aria-label="Daftar isi artikel"></nav>
                </div>
            </div>
        </aside>

    </div>{{-- end blog-detail-grid --}}
</div>{{-- end blog-detail-wrap --}}

{{-- ── RELATED ARTICLES ── --}}
@if($related->isNotEmpty())
<section class="bg-white border-t border-gray-200 py-12 mt-4">
    <div class="blog-detail-wrap">
        <div class="flex items-center justify-between mb-7">
            <h2 class="text-lg font-bold text-gray-900">Artikel Terkait</h2>
            <a href="{{ route('blog.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium flex items-center gap-1">
                Semua artikel
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($related as $rel)
            <article class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 flex flex-col overflow-hidden">
                <a href="{{ route('blog.show', $rel->slug) }}" class="block relative overflow-hidden bg-gray-100 flex-shrink-0" style="aspect-ratio:16/9;">
                    @if($rel->thumbnail_url)
                        <img src="{{ asset($rel->thumbnail_url) }}"
                             alt="{{ $rel->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <span class="inline-flex items-center bg-primary-50 text-primary-600 text-xs font-semibold px-2.5 py-1 rounded-full mb-2 self-start">
                        {{ $rel->category ?? 'Inspeksi' }}
                    </span>
                    <a href="{{ route('blog.show', $rel->slug) }}">
                        <h3 class="font-bold text-gray-900 text-sm leading-snug group-hover:text-primary-700 transition-colors line-clamp-3 mb-3">
                            {{ $rel->title }}
                        </h3>
                    </a>
                    <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100 text-xs text-gray-400">
                        <span>{{ $rel->formatted_date }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

</div>{{-- end blog-detail-bg --}}

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── 1. Gather headings from article body ── */
    var body     = document.getElementById('article-body');
    var headings = body ? Array.from(body.querySelectorAll('h2, h3')) : [];

    /* ── 2. Deduplicate: assign unique IDs, skip exact-duplicate text headings ── */
    var seenTexts = {};   // text → count (for generating unique IDs)
    var seenIds   = {};   // id   → true  (to catch any pre-existing duplicates)
    var uniqueHeadings = [];

    headings.forEach(function (h) {
        var rawText = h.textContent.trim();

        // If we have already processed a heading with this exact text, skip it.
        if (seenTexts[rawText]) {
            // Mark this heading so it is ignored in TOC and IntersectionObserver.
            h.dataset.tocIgnore = '1';
            return;
        }
        seenTexts[rawText] = 1;

        // Assign a stable ID if none exists.
        if (!h.id) {
            var baseId = rawText
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .slice(0, 60);
            var id = baseId;
            var n  = 1;
            while (seenIds[id]) { id = baseId + '-' + n++; }
            h.id = id;
        }
        seenIds[h.id] = true;
        uniqueHeadings.push(h);
    });

    if (uniqueHeadings.length < 2) return; // hide TOC when too few headings

    /* ── 3. Build TOC links into a given <nav> container (idempotent) ── */
    function buildLinks(container) {
        if (container.dataset.built) return;  // guard: jangan bangun dua kali
        container.dataset.built = 'true';
        container.replaceChildren();          // kosongkan dulu jika ada sisa

        var h2Count = 0;
        var h3Count = 0;

        uniqueHeadings.forEach(function (h) {
            var isH2 = h.tagName === 'H2';
            if (isH2) { h2Count++; h3Count = 0; }
            else       { h3Count++; }

            var num = isH2 ? h2Count + '.' : h2Count + '.' + h3Count + '.';

            var a       = document.createElement('a');
            a.href      = '#' + h.id;
            a.className = 'toc-link' + (isH2 ? '' : ' toc-h3');
            a.innerHTML = '<span class="mr-1 text-gray-400 tabular-nums">' + num + '</span>'
                        + h.textContent.trim();

            a.addEventListener('click', function (e) {
                e.preventDefault();
                h.scrollIntoView({ behavior: 'smooth', block: 'start' });
                history.pushState(null, '', '#' + h.id);
            });

            container.appendChild(a);
        });
    }

    /* ── 4. Desktop TOC ── */
    var desktopList    = document.getElementById('toc-desktop-list');
    var desktopWrapper = document.getElementById('toc-desktop-wrapper');
    var desktopToggle  = document.getElementById('toc-desktop-toggle');
    var desktopBody    = desktopList;

    if (desktopList && desktopWrapper) {
        buildLinks(desktopList);
        desktopWrapper.classList.remove('hidden');

        var tocOpen = true;
        desktopToggle.addEventListener('click', function () {
            tocOpen = !tocOpen;
            desktopBody.style.display = tocOpen ? '' : 'none';
            desktopToggle.textContent  = tocOpen ? '↑ Tutup' : '↓ Buka';
        });
    }

    /* ── 5. Mobile TOC ── */
    var mobileWrapper = document.getElementById('toc-mobile-wrapper');
    var mobileList    = document.getElementById('toc-mobile-list');
    var mobileToggle  = document.getElementById('toc-mobile-toggle');
    var mobileChevron = document.getElementById('toc-mobile-chevron');
    var mobileBody    = document.getElementById('toc-mobile-body');

    if (mobileList && mobileWrapper) {
        buildLinks(mobileList);
        mobileWrapper.classList.remove('hidden');

        var mobileOpen = true;
        mobileToggle.addEventListener('click', function () {
            mobileOpen = !mobileOpen;
            mobileBody.classList.toggle('hidden', !mobileOpen);
            mobileChevron.style.transform = mobileOpen ? '' : 'rotate(-90deg)';
            mobileToggle.setAttribute('aria-expanded', mobileOpen);
        });
    }

    /* ── 6. Active link highlight via IntersectionObserver ── */
    var allLinks = document.querySelectorAll('.toc-link');
    var tocAside = document.querySelector('.toc-aside-sticky');

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                var id = entry.target.id;
                allLinks.forEach(function (l) {
                    var active = l.getAttribute('href') === '#' + id;
                    l.classList.toggle('toc-active', active);
                    // scroll item aktif agar terlihat di dalam aside (desktop)
                    if (active && tocAside && l.closest('#toc-desktop-list')) {
                        l.scrollIntoView({ block: 'nearest' });
                    }
                });
            }
        });
    }, {
        rootMargin: '-80px 0px -65% 0px',
        threshold: 0,
    });

    uniqueHeadings.forEach(function (h) { observer.observe(h); });
})();
</script>
@endpush
@endsection
