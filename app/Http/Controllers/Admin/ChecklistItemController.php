<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InspectionChecklistItem;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function index(Request $request)
    {
        $query = InspectionChecklistItem::orderBy('sort_order');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $items      = $query->paginate(30)->withQueryString();
        $categories = InspectionChecklistItem::distinct()->orderBy('category')->pluck('category');

        return view('admin.checklist.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = InspectionChecklistItem::distinct()->orderBy('category')->pluck('category');
        return view('admin.checklist.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'  => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'description'=> 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        InspectionChecklistItem::create([
            'package_id'  => null,
            'item_name'   => $request->item_name,
            'category'    => $request->category,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.checklist-items.index')
            ->with('success', 'Item checklist berhasil ditambahkan.');
    }

    public function edit(InspectionChecklistItem $checklistItem)
    {
        $categories = InspectionChecklistItem::distinct()->orderBy('category')->pluck('category');
        return view('admin.checklist.edit', compact('checklistItem', 'categories'));
    }

    public function update(Request $request, InspectionChecklistItem $checklistItem)
    {
        $request->validate([
            'item_name'  => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'description'=> 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $checklistItem->update([
            'item_name'   => $request->item_name,
            'category'    => $request->category,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? $checklistItem->sort_order,
            'is_active'   => $request->boolean('is_active', $checklistItem->is_active),
        ]);

        return redirect()->route('admin.checklist-items.index')
            ->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(InspectionChecklistItem $checklistItem)
    {
        $checklistItem->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }
}
