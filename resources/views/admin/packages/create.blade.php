@extends('layouts.admin')
@section('page-title', 'Tambah Paket')
@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.packages.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('admin.packages.store') }}" class="space-y-4">
            @csrf
            @include('admin.packages._form')
            <button class="btn-primary">Simpan Paket</button>
        </form>
    </div>
</div>
@endsection
