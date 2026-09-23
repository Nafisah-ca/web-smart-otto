@extends('layouts.app')
@section('title', 'Masuk')
@section('content')
<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-bold text-lg">SO</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Masuk ke Smart Otto</h1>
            <p class="text-gray-500 mt-1 text-sm">Masuk dengan akun Anda</p>
        </div>

        <div class="card p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="form-input @error('email') border-red-500 @enderror"
                           placeholder="email@contoh.com">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" required
                           class="form-input @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600">
                        Ingat saya
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Masuk</button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-primary-600 font-medium hover:underline">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection
