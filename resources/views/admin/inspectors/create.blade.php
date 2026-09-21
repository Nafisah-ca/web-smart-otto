@extends('layouts.admin')
@section('page-title', 'Tambah Inspektor')
@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.inspectors.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('admin.inspectors.store') }}" class="space-y-4">
            @csrf
            <div><label class="form-label">Nama</label><input type="text" name="name" value="{{ old('name') }}" class="form-input" required></div>
            <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-input" required></div>
            <div><label class="form-label">No. Telepon</label><input type="text" name="phone" value="{{ old('phone') }}" class="form-input" required></div>
            <div><label class="form-label">Alamat</label><input type="text" name="address" value="{{ old('address') }}" class="form-input"></div>
            <div><label class="form-label">Password</label><input type="password" name="password" class="form-input" required></div>
            <button class="btn-primary">Tambah Inspektor</button>
        </form>
    </div>
</div>
@endsection
