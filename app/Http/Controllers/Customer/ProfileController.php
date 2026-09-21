<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('customer.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address'      => 'nullable|string|max:500',
            'password'     => 'nullable|string|min:8|confirmed',
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'password.min'   => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $data = [
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
