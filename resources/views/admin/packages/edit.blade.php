@extends('layouts.admin')
@section('page-title', 'Edit Paket')
@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.packages.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('admin.packages.update', $package) }}" class="space-y-4">
            @csrf @method('PUT')
            @include('admin.packages._form', ['package'=>$package])
            <button class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
