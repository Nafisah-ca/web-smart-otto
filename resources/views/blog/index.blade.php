@extends('layouts.app')

@section('title', 'Blog — Tips & Info Inspeksi Kendaraan')

@push('styles')
<meta name="description" content="Artikel, tips, dan panduan seputar inspeksi kendaraan bekas dari tim Smart Otto.">
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="bg-white border-b border-gray-100">
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
<div class="sticky top-16 z-30 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
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
                          {{ ! request('category') ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('blog.index', array_filter(['search' => request('search'), 'category' => $cat])) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                          {{ request('category') === $cat ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
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
        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v8a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-400 font-medium">Belum ada artikel ditemukan.</p>
        @if(request('search') || request('category'))
        <a href="{{ route('blog.index') }}" class="mt-4 inline-block text-sm text-primary-600 hover:underline">Lihat semua artikel</a>
        @endif
    </div>
    @else

    @php $allPosts = $posts->items(); $featured = $allPosts[0] ?? null; $rest = array_slice($allPosts, 1); @endphp

    {{-- FEATURED ARTICLE (first on page 1 only, no active filter) --}}
    @if($featured && $posts->currentPage() === 1 && ! request('search') && ! request('category'))
    <article class="group mb-12">
        <a href="{{ route('blog.show', $featured->slug) }}" class="grid md:grid-cols-2 gap-0 bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            {{-- Thumbnail --}}
            <div class="relative overflow-hidden bg-gray-100" style="min-height:280px;">
                @if($featured->thumbnail_url)
                    <img src="{{ asset($featured->thumbnail_url) }}"
                         alt="{{ $featured->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         style="min-height:280px;">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200" style="min-height:280px;">
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                <span class="absolute top-4 left-4 bg-primary-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                    Unggulan
                </span>
            </div>

            {{-- Content --}}
            <div class="p-8 flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-full">
                        {{ $featured->category ?? 'Inspeksi' }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $featured->reading_time }} menit baca</span>
                </div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 leading-snug group-hover:text-primary-700 transition-colors mb-3">
                    {{ $featured->title }}
                </h2>
                @if($featured->excerpt)
                <p class="text-gray-500 text-sm leading-relaxed mb-5 line-clamp-3">{{ $featured->excerpt }}</p>
                @endif
                <div class="flex items-center justify-between mt-auto">
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
    @php $gridPosts = $rest; @endphp
    @else
    @php $gridPosts = $allPosts; @endphp
    @endif

    {{-- ARTICLE GRID --}}
    @if(count($gridPosts) > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @foreach($gridPosts as $post)
        <article class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5 flex flex-col overflow-hidden">
            {{-- Thumbnail --}}
            <a href="{{ route('blog.show', $post->slug) }}" class="block relative overflow-hidden bg-gray-100 aspect-video flex-shrink-0">
                @if($post->thumbnail_url)
                    <img src="{{ asset($post->thumbnail_url) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                {{-- Category badge --}}
                <span class="absolute top-3 left-3 bg-white/90 text-gray-700 text-xs font-semibold px-2.5 py-1 rounded-full backdrop-blur-sm">
                    {{ $post->category ?? 'Inspeksi' }}
                </span>
            </a>

            {{-- Body --}}
            <div class="p-5 flex flex-col flex-1">
                {{-- Meta --}}
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($post->author_name, 0, 1)) }}
                    </div>
                    <span class="text-xs text-gray-500 truncate">{{ $post->author_name }}</span>
                    <span class="text-xs text-gray-300">·</span>
                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ $post->reading_time }} mnt</span>
                </div>

                <a href="{{ route('blog.show', $post->slug) }}">
                    <h3 class="font-bold text-gray-900 text-base leading-snug group-hover:text-primary-700 transition-colors line-clamp-3 mb-2">
                        {{ $post->title }}
                    </h3>
                </a>

                @if($post->excerpt)
                <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-4">{{ $post->excerpt }}</p>
                @endif

                <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50">
                    <span class="text-xs text-gray-400">{{ $post->formatted_date }}</span>
                    <a href="{{ route('blog.show', $post->slug) }}"
                       class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 hover:text-primary-800 transition-colors">
                        Lebih lanjut
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
@endsection
