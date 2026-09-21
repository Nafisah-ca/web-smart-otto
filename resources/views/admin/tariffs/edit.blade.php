@extends('layouts.admin')
@section('page-title', 'Edit Tarif')
@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.tariffs.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('admin.tariffs.update', $tariff) }}" class="space-y-4">
            @csrf @method('PUT')
            @include('admin.tariffs._form', ['tariff' => $tariff])
            <button class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
