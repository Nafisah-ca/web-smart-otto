@extends('layouts.admin')
@section('page-title', 'Tambah Tarif')
@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.tariffs.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('admin.tariffs.store') }}" class="space-y-4">
            @csrf
            @include('admin.tariffs._form')
            <button class="btn-primary">Simpan Tarif</button>
        </form>
    </div>
</div>
@endsection
