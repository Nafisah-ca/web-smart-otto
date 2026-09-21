<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InspectionPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = InspectionPackage::withCount('bookings')->orderBy('sort_order')->paginate(15);
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'duration_estimate' => 'required|integer|min:30',
            'icon'              => 'nullable|string|max:10',
            'sort_order'        => 'nullable|integer',
        ]);

        InspectionPackage::create($request->only('name','description','price','duration_estimate','icon','sort_order') + ['is_active' => true]);
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function show(InspectionPackage $package)
    {
        $package->load('checklistItems');
        return view('admin.packages.show', compact('package'));
    }

    public function edit(InspectionPackage $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, InspectionPackage $package)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'duration_estimate' => 'required|integer|min:30',
            'icon'              => 'nullable|string|max:10',
            'sort_order'        => 'nullable|integer',
        ]);

        $package->update($request->only('name','description','price','duration_estimate','icon','sort_order'));
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(InspectionPackage $package)
    {
        $package->delete();
        return back()->with('success', 'Paket berhasil dihapus.');
    }

    public function toggle(InspectionPackage $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        return back()->with('success', 'Status paket berhasil diubah.');
    }
}
