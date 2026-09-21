<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InspectionChecklistItem;
use App\Models\InspectionPackage;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function index(Request $request)
    {
        $query = InspectionChecklistItem::with('package')->orderBy('package_id')->orderBy('sort_order');
        if ($request->package_id) {
            $query->where('package_id', $request->package_id);
        }
        $items    = $query->paginate(20)->withQueryString();
        $packages = InspectionPackage::active()->get();
        return view('admin.checklist.index', compact('items', 'packages'));
    }

    public function create()
    {
        $packages = InspectionPackage::active()->get();
        return view('admin.checklist.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:inspection_packages,id',
            'item_name'  => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'description'=> 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        InspectionChecklistItem::create($request->only('package_id','item_name','category','description','sort_order'));
        return redirect()->route('admin.checklist-items.index')->with('success', 'Item checklist berhasil ditambahkan.');
    }

    public function edit(InspectionChecklistItem $checklistItem)
    {
        $packages = InspectionPackage::active()->get();
        return view('admin.checklist.edit', compact('checklistItem', 'packages'));
    }

    public function update(Request $request, InspectionChecklistItem $checklistItem)
    {
        $request->validate([
            'package_id' => 'required|exists:inspection_packages,id',
            'item_name'  => 'required|string|max:255',
            'category'   => 'required|string|max:100',
        ]);

        $checklistItem->update($request->only('package_id','item_name','category','description','sort_order'));
        return redirect()->route('admin.checklist-items.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(InspectionChecklistItem $checklistItem)
    {
        $checklistItem->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }
}
