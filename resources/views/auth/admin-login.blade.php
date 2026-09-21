<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Smart Otto</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-bold text-xl">SO</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Admin Panel</h1>
            <p class="text-gray-400 text-sm mt-1">Smart Otto Management System</p>
        </div>

        <div class="bg-gray-800 rounded-2xl p-8 border border-gray-700">
            <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Email Admin</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="block w-full rounded-lg bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('email') border-red-500 @enderror"
                           placeholder="admin@smartotto.test">
                    @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="block w-full rounded-lg bg-gray-700 border-gray-600 text-white placeholder-gray-400 focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                           placeholder="••••••••">
                </div>
                <button type="submit" class="w-full btn-primary justify-center py-3">
                    Masuk ke Admin Panel
                </button>
            </form>
        </div>
        <p class="text-center text-xs text-gray-500 mt-4">
            <a href="{{ route('home') }}" class="hover:text-gray-300">← Kembali ke Website</a>
        </p>
    </div>
</body>
</html>
