<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tariff;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    public function index(Request $request)
    {
        $query = Tariff::orderBy('category')->orderBy('name');
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $tariffs    = $query->paginate(20)->withQueryString();
        $categories = Tariff::distinct()->pluck('category');
        return view('admin.tariffs.index', compact('tariffs', 'categories'));
    }

    public function create()
    {
        $categories = Tariff::distinct()->pluck('category');
        return view('admin.tariffs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'price'        => 'required|numeric|min:0',
            'unit'         => 'required|string|max:20',
            'description'  => 'nullable|string',
            'active_from'  => 'required|date',
            'active_until' => 'nullable|date|after_or_equal:active_from',
        ]);

        Tariff::create(array_merge($request->only('name','category','price','unit','description','active_from','active_until'), ['is_active' => true]));
        return redirect()->route('admin.tariffs.index')->with('success', 'Tarif berhasil ditambahkan.');
    }

    public function edit(Tariff $tariff)
    {
        $categories = Tariff::distinct()->pluck('category');
        return view('admin.tariffs.edit', compact('tariff', 'categories'));
    }

    public function update(Request $request, Tariff $tariff)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'price'        => 'required|numeric|min:0',
            'unit'         => 'required|string|max:20',
            'active_from'  => 'required|date',
            'active_until' => 'nullable|date|after_or_equal:active_from',
        ]);

        $tariff->update($request->only('name','category','price','unit','description','active_from','active_until'));
        return redirect()->route('admin.tariffs.index')->with('success', 'Tarif berhasil diperbarui.');
    }

    public function destroy(Tariff $tariff)
    {
        $tariff->delete();
        return back()->with('success', 'Tarif berhasil dihapus.');
    }

    public function toggle(Tariff $tariff)
    {
        $tariff->update(['is_active' => !$tariff->is_active]);
        return back()->with('success', 'Status tarif berhasil diubah.');
    }
}
