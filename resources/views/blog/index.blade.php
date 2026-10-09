@extends('layouts.app')

@section('title', 'Blog — Tips & Info Inspeksi Kendaraan')

@push('styles')
<meta name="description" content="Artikel, tips, dan panduan seputar inspeksi kendaraan bekas dari tim Smart Otto.">
<style>
    /* ── Blog index page ── */
    .blog-page-bg { background-color: #f1f5f9; }

    /* Card hover lift */
    .blog-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px 0 rgb(0 0 0 / .07), 0 1px 2px -1px rgb(0 0 0 / .07);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .blog-card:hover {
        box-shadow: 0 8px 24px 0 rgb(0 0 0 / .12), 0 2px 6px -1px rgb(0 0 0 / .08);
        transform: translateY(-3px);
    }

    /* Featured card */
    .featured-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 1px 4px 0 rgb(0 0 0 / .08), 0 2px 6px -2px rgb(0 0 0 / .06);
        transition: box-shadow 0.2s ease;
        overflow: hidden;
    }
    .featured-card:hover {
        box-shadow: 0 10px 28px 0 rgb(0 0 0 / .13), 0 4px 10px -2px rgb(0 0 0 / .08);
    }

    /* Thumbnail placeholder */
    .thumb-placeholder {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    }

    /* Category badge — inside body, not over image */
    .badge-category {
        display: inline-flex;
        align-items: center;
        background: #fff1f2;
        color: #e11d48;
        font-size: 0.6875rem;
        font-weight: 700;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        letter-spacing: 0.01em;
        white-space: nowrap;
    }

    /* "Unggulan" badge — in featured content area */
    .badge-featured {
        display: inline-flex;
        align-items: center;
        background: #e11d48;
        color: #ffffff;
        font-size: 0.6875rem;
        font-weight: 700;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    /* Uniform card grid: equal-height rows */
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
    }
    @media (min-width: 640px) {
        .blog-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 1024px) {
        .blog-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
@endpush

@section('content')

<div class="blog-page-bg min-h-screen">

{{-- PAGE HEADER --}}
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
        <div class="max-w-2xl">
            <p class="text-xs font-semibold text-primary-600 uppercase tracking-widest mb-2">Tips & Panduan</p>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                Semua yang Perlu Kamu Tahu Soal Inspeksi Kendaraan
            </h1>
            <p class="mt-3 text-gray-500 text-base leading-relaxed">
                Panduan praktis dari tim inspektor Smart Otto: cara memilih mobil bekas, memahami laporan inspeksi, dan melindungi diri dari pembelian yang merugikan.
            </p>
        </div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('blog.index') }}" class="flex flex-wrap items-center gap-3 py-3">
            {{-- Search --}}
            <div class="relative flex-1 min-w-[180px] max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari artikel…"
                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400 bg-gray-50">
            </div>

            {{-- Category pills --}}
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('blog.index', array_filter(['search' => request('search')])) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                          {{ ! request('category') ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('blog.index', array_filter(['search' => request('search'), 'category' => $cat])) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                          {{ request('category') === $cat ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                    {{ $cat }}
                </a>
                @endforeach
            </div>

            <button type="submit" class="btn-primary btn-sm hidden">Cari</button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if($posts->isEmpty())
    {{-- EMPTY STATE --}}
    <div class="text-center py-20">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-400 font-medium">Belum ada artikel ditemukan.</p>
        @if(request('search') || request('category'))
        <a href="{{ route('blog.index') }}" class="mt-4 inline-block text-sm text-primary-600 hover:underline">Lihat semua artikel</a>
        @endif
    </div>
    @else

    @php
        $allPosts = $posts->items();
        $featured  = null;
        $gridPosts = $allPosts;

        // Show featured block only on page 1 with no active filter
        if ($posts->currentPage() === 1 && ! request('search') && ! request('category')) {
            $featured  = $allPosts[0] ?? null;
            $gridPosts = array_slice($allPosts, 1);
        }
    @endphp

    {{-- FEATURED ARTICLE --}}
    @if($featured)
    <article class="mb-10">
        <a href="{{ route('blog.show', $featured->slug) }}" class="featured-card grid md:grid-cols-2 gap-0 block group">
            {{-- Thumbnail --}}
            <div class="relative overflow-hidden thumb-placeholder" style="min-height:280px;">
                @if($featured->thumbnail_url)
                    <img src="{{ asset($featured->thumbnail_url) }}"
                         alt="{{ $featured->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         style="min-height:280px;">
                @else
                    <div class="w-full h-full flex items-center justify-center" style="min-height:280px;">
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="p-8 flex flex-col justify-center">
                {{-- Badges row — category + Unggulan, side by side in the text area --}}
                <div class="flex items-center gap-2 mb-4">
                    <span class="badge-featured">★ Unggulan</span>
                    <span class="badge-category">{{ $featured->category ?? 'Inspeksi' }}</span>
                </div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 leading-snug group-hover:text-primary-700 transition-colors mb-3">
                    {{ $featured->title }}
                </h2>
                @if($featured->excerpt)
                <p class="text-gray-500 text-sm leading-relaxed mb-5 line-clamp-3">{{ $featured->excerpt }}</p>
                @endif
                <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($featured->author_name, 0, 1)) }}
                        </div>
                        <div class="text-xs">
                            <p class="font-medium text-gray-700">{{ $featured->author_name }}</p>
                            <p class="text-gray-400">{{ $featured->formatted_date }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-primary-600 flex items-center gap-1 group-hover:gap-2 transition-all">
                        Baca
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </div>
        </a>
    </article>
    @endif

    {{-- SECTION DIVIDER when showing grid below featured --}}
    @if($featured && count($gridPosts) > 0)
    <div class="flex items-center gap-3 mb-7">
        <span class="text-sm font-semibold text-gray-700">Artikel Lainnya</span>
        <div class="flex-1 h-px bg-gray-200"></div>
    </div>
    @endif

    {{-- ARTICLE GRID --}}
    @if(count($gridPosts) > 0)
    <div class="blog-grid mb-10">
        @foreach($gridPosts as $post)
        <article class="blog-card group">
            {{-- Thumbnail — clean, no badge overlay --}}
            <a href="{{ route('blog.show', $post->slug) }}"
               class="block relative overflow-hidden thumb-placeholder flex-shrink-0"
               style="aspect-ratio:16/9;">
                @if($post->thumbnail_url)
                    <img src="{{ asset($post->thumbnail_url) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </a>

            {{-- Body --}}
            <div class="p-5 flex flex-col flex-1">
                {{-- Category badge --}}
                <div class="flex items-center gap-2 mb-3">
                    <span class="badge-category">{{ $post->category ?? 'Inspeksi' }}</span>
                </div>

                <a href="{{ route('blog.show', $post->slug) }}">
                    <h3 class="font-bold text-gray-900 text-base leading-snug group-hover:text-primary-700 transition-colors line-clamp-3 mb-2">
                        {{ $post->title }}
                    </h3>
                </a>

                @if($post->excerpt)
                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-4">{{ $post->excerpt }}</p>
                @endif

                {{-- Author + date footer --}}
                <div class="flex items-center gap-2 mt-auto pt-3 border-t border-gray-100">
                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($post->author_name, 0, 1)) }}
                    </div>
                    <span class="text-xs text-gray-500 truncate">{{ $post->author_name }}</span>
                    <span class="text-xs text-gray-300 mx-0.5">·</span>
                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ $post->formatted_date }}</span>
                    <a href="{{ route('blog.show', $post->slug) }}"
                       class="ml-auto inline-flex items-center gap-1 text-xs font-semibold text-primary-600 hover:text-primary-800 transition-colors flex-shrink-0">
                        Baca
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    @endif

    {{-- PAGINATION --}}
    @if($posts->hasPages())
    <div class="flex justify-center">
        {{ $posts->links() }}
    </div>
    @endif

    @endif {{-- end if posts not empty --}}

</div>

</div>{{-- end blog-page-bg --}}
@endsection
