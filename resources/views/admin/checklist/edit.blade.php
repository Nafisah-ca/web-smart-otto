@extends('layouts.admin')
@section('page-title', 'Edit Checklist Item')
@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.checklist-items.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('admin.checklist-items.update', $checklistItem) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="form-label">Paket</label>
                <select name="package_id" class="form-input" required>
                    @foreach($packages as $p)<option value="{{ $p->id }}" {{ old('package_id',$checklistItem->package_id)==$p->id?'selected':'' }}>{{ $p->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Kategori</label>
                <input type="text" name="category" value="{{ old('category', $checklistItem->category) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Nama Item</label>
                <input type="text" name="item_name" value="{{ old('item_name', $checklistItem->item_name) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $checklistItem->sort_order) }}" class="form-input" min="0">
            </div>
            <button class="btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
