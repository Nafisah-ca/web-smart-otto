@extends('layouts.admin')
@section('title', 'Artikel Baru')
@section('page-title', 'Artikel Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke daftar artikel
    </a>
</div>

<form method="POST"
      action="{{ route('admin.posts.store') }}"
      enctype="multipart/form-data"
      novalidate>
    @csrf
    @include('admin.posts._form')
</form>
@endsection
