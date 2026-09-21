<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="form-label">Nama Tindakan / Barang</label>
        <input type="text" name="name" value="{{ old('name', $tariff->name ?? '') }}" class="form-input @error('name') border-red-500 @enderror" required>
        @error('name')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Kategori</label>
        <input type="text" name="category" value="{{ old('category', $tariff->category ?? '') }}" class="form-input" list="cat-list" required>
        <datalist id="cat-list">
            @foreach($categories as $cat)<option value="{{ $cat }}">@endforeach
        </datalist>
        @error('category')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Harga (Rp)</label>
        <input type="number" name="price" value="{{ old('price', $tariff->price ?? '') }}" class="form-input" min="0" step="500" required>
        @error('price')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Satuan</label>
        <input type="text" name="unit" value="{{ old('unit', $tariff->unit ?? 'pcs') }}" class="form-input" list="unit-list" required>
        <datalist id="unit-list">
            @foreach(['pcs','set','buah','liter','botol','jam','kali'] as $u)<option value="{{ $u }}">@endforeach
        </datalist>
    </div>
    <div>
        <label class="form-label">Berlaku Mulai</label>
        <input type="date" name="active_from" value="{{ old('active_from', isset($tariff) ? $tariff->active_from?->format('Y-m-d') : date('Y-m-d')) }}" class="form-input" required>
    </div>
    <div>
        <label class="form-label">Berlaku Sampai (opsional)</label>
        <input type="date" name="active_until" value="{{ old('active_until', $tariff->active_until?->format('Y-m-d') ?? '') }}" class="form-input">
    </div>
    <div class="sm:col-span-2">
        <label class="form-label">Deskripsi (opsional)</label>
        <textarea name="description" rows="2" class="form-input">{{ old('description', $tariff->description ?? '') }}</textarea>
    </div>
</div>
