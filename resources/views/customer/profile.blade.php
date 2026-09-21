@extends('layouts.customer')
@section('title', 'Profil Saya')
@section('content')
<div class="space-y-5">
    <h2 class="text-xl font-bold text-gray-900">Profil Saya</h2>
    <div class="card p-6">
        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input @error('name') border-red-500 @enderror" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" value="{{ $user->email }}" class="form-input bg-gray-50 cursor-not-allowed" disabled>
                    <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah</p>
                </div>
                <div>
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input @error('phone') border-red-500 @enderror" required>
                    @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Alamat</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-input">
                </div>
            </div>
            <hr class="border-gray-100">
            <p class="text-sm font-medium text-gray-700">Ubah Password (kosongkan jika tidak ingin mengubah)</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input @error('password') border-red-500 @enderror" placeholder="Min. 8 karakter">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password">
                </div>
            </div>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
