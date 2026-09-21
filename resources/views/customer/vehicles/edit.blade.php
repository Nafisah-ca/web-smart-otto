@extends('layouts.customer')
@section('title', 'Edit Kendaraan')
@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('customer.vehicles.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-xl font-bold text-gray-900">Edit Kendaraan</h2>
    </div>
    <div class="card p-6">
        <form method="POST" action="{{ route('customer.vehicles.update', $vehicle) }}" class="space-y-4">
            @csrf @method('PUT')
            @include('customer.vehicles._form', ['vehicle' => $vehicle])
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
