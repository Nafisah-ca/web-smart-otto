@extends('layouts.admin')
@section('title', 'Edit: ' . $post->title)
@section('page-title', 'Edit Artikel')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke daftar artikel
    </a>
    <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
       class="text-sm text-primary-600 hover:text-primary-800 flex items-center gap-1">
        Lihat di website
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
    </a>
</div>

<form method="POST"
      action="{{ route('admin.posts.update', $post) }}"
      enctype="multipart/form-data"
      novalidate>
    @csrf
    @method('PUT')
    @include('admin.posts._form')
</form>
@endsection
