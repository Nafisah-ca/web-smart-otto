<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    // ── Customer Login ──────────────────────────────────────────────
    public function create()
    {
        // Jika sudah login, redirect ke halaman yang sesuai
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();

        // Admin tidak boleh login dari halaman customer
        if ($user->role === 'admin') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Silakan login melalui halaman Admin.',
            ]);
        }

        $request->session()->regenerate();

        return match ($user->role) {
            'inspector' => redirect()->route('inspector.dashboard'),
            default     => redirect()->intended(route('customer.dashboard')),
        };
    }

    // ── Admin Login ──────────────────────────────────────────────────
    public function adminCreate()
    {
        // Jika sudah login sebagai admin, langsung ke dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Jika login sebagai role lain, logout dulu
        if (Auth::check()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return view('auth.admin-login');
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Pastikan tidak ada session lain yang aktif
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akses ditolak. Hanya Admin yang dapat masuk di sini.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    // ── Logout (shared) ──────────────────────────────────────────────
    public function destroy(Request $request)
    {
        $role = Auth::user()?->role;
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return match ($role) {
            'admin' => redirect()->route('admin.login'),
            default => redirect()->route('home'),
        };
    }

    // ── Helper ──────────────────────────────────────────────────────
    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'inspector' => redirect()->route('inspector.dashboard'),
            default     => redirect()->route('customer.dashboard'),
        };
    }
}
