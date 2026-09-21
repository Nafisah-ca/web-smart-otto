@extends('layouts.admin')
@section('page-title', 'Tambah Checklist Item')
@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.checklist-items.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-6 mt-4">
        <form method="POST" action="{{ route('admin.checklist-items.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="form-label">Paket</label>
                <select name="package_id" class="form-input" required>
                    <option value="">-- Pilih Paket --</option>
                    @foreach($packages as $p)<option value="{{ $p->id }}" {{ old('package_id')==$p->id?'selected':'' }}>{{ $p->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" class="form-input" list="cat-list" required placeholder="Mesin, Rem, Ban...">
                <datalist id="cat-list">
                    @foreach(['Mesin','Rem','Ban & Roda','Kaki-kaki','Kelistrikan','AC','Lampu','Eksterior','Interior','Transmisi','Test Drive'] as $c)
                    <option value="{{ $c }}">
                    @endforeach
                </datalist>
            </div>
            <div>
                <label class="form-label">Nama Item</label>
                <input type="text" name="item_name" value="{{ old('item_name') }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-input" min="0">
            </div>
            <button class="btn-primary">Simpan Item</button>
        </form>
    </div>
</div>
@endsection
