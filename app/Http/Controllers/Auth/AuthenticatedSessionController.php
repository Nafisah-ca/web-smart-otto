<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    // ── Login ────────────────────────────────────────────────────────
    public function create()
    {
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

        $request->session()->regenerate();

        return match (Auth::user()->role) {
            'admin'     => redirect()->route('admin.cms.index'),
            'inspector' => redirect()->route('inspector.dashboard'),
            default     => redirect()->intended(route('customer.dashboard')),
        };
    }

    // ── Logout (shared) ──────────────────────────────────────────────
    public function destroy(Request $request)
    {
        $role = Auth::user()?->role;
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return match ($role) {
            'admin' => redirect()->route('login'),
            default => redirect()->route('home'),
        };
    }

    // ── Helper ──────────────────────────────────────────────────────
    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'     => redirect()->route('admin.cms.index'),
            'inspector' => redirect()->route('inspector.dashboard'),
            default     => redirect()->route('customer.dashboard'),
        };
    }
}
