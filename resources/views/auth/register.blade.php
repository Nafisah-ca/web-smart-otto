@extends('layouts.app')
@section('title', 'Daftar Akun')
@section('content')
<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-bold text-lg">SO</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
            <p class="text-gray-500 mt-1 text-sm">Daftar untuk mulai booking inspeksi</p>
        </div>

        <div class="card p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="form-input @error('name') border-red-500 @enderror" placeholder="Nama lengkap Anda">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="form-input @error('email') border-red-500 @enderror" placeholder="email@contoh.com">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           class="form-input @error('phone') border-red-500 @enderror" placeholder="08xx xxxx xxxx">
                    @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" required
                           class="form-input @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="form-input" placeholder="Ulangi password">
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Daftar Sekarang</button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary-600 font-medium hover:underline">Masuk</a>
            </p>
        </div>
    </div>
</div>
@endsection
