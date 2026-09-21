<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InspectorController extends Controller
{
    public function index()
    {
        $inspectors = User::inspectors()->withCount('assignedBookings')->orderBy('name')->paginate(15);
        return view('admin.inspectors.index', compact('inspectors'));
    }

    public function create()
    {
        return view('admin.inspectors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'address'  => 'nullable|string|max:500',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'inspector',
            'address'  => $request->address,
        ]);

        return redirect()->route('admin.inspectors.index')
            ->with('success', 'Inspektor berhasil ditambahkan.');
    }

    public function show(User $inspector)
    {
        abort_if($inspector->role !== 'inspector', 404);
        $inspector->load(['assignedBookings' => fn($q) => $q->with('vehicle','package')->latest()->limit(10)]);
        return view('admin.inspectors.show', compact('inspector'));
    }

    public function edit(User $inspector)
    {
        abort_if($inspector->role !== 'inspector', 404);
        return view('admin.inspectors.edit', compact('inspector'));
    }

    public function update(Request $request, User $inspector)
    {
        abort_if($inspector->role !== 'inspector', 404);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $inspector->id,
            'phone'    => 'required|string|max:20',
            'address'  => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8',
        ]);

        $data = $request->only('name','email','phone','address');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $inspector->update($data);
        return redirect()->route('admin.inspectors.index')
            ->with('success', 'Data inspektor berhasil diperbarui.');
    }

    public function destroy(User $inspector)
    {
        abort_if($inspector->role !== 'inspector', 404);
        $inspector->delete();
        return back()->with('success', 'Inspektor berhasil dihapus.');
    }
}
