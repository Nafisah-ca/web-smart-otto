@extends('layouts.admin')
@section('page-title', 'Edit Inspektor')
@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.inspectors.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('admin.inspectors.update', $inspector) }}" class="space-y-4">
            @csrf @method('PUT')
            <div><label class="form-label">Nama</label><input type="text" name="name" value="{{ old('name', $inspector->name) }}" class="form-input" required></div>
            <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email', $inspector->email) }}" class="form-input" required></div>
            <div><label class="form-label">No. Telepon</label><input type="text" name="phone" value="{{ old('phone', $inspector->phone) }}" class="form-input" required></div>
            <div><label class="form-label">Alamat</label><input type="text" name="address" value="{{ old('address', $inspector->address) }}" class="form-input"></div>
            <div><label class="form-label">Password Baru (kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-input"></div>
            <button class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
