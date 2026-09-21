<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::customers()->withCount('bookings')->orderBy('name');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->paginate(15)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'customer', 404);
        $user->load(['vehicles', 'bookings' => fn($q) => $q->with('package','vehicle')->latest()->limit(10)]);
        return view('admin.customers.show', compact('user'));
    }
}
