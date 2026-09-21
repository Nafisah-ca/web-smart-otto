<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin') or middleware('role:admin,inspector')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;

        foreach ($roles as $role) {
            if ($userRole === $role) {
                return $next($request);
            }
        }

        // Redirect ke halaman yang sesuai dengan role
        return match ($userRole) {
            'admin'     => redirect()->route('admin.dashboard')->with('error', 'Akses tidak diizinkan.'),
            'inspector' => redirect()->route('inspector.dashboard')->with('error', 'Akses tidak diizinkan.'),
            default     => redirect()->route('customer.dashboard')->with('error', 'Akses tidak diizinkan.'),
        };
    }
}
