<div>
    <label class="form-label">Nama Paket</label>
    <input type="text" name="name" value="{{ old('name', $package->name ?? '') }}" class="form-input" required>
    @error('name')<p class="form-error">{{ $message }}</p>@enderror
</div>
<div>
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="3" class="form-input">{{ old('description', $package->description ?? '') }}</textarea>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="form-label">Harga (Rp)</label>
        <input type="number" name="price" value="{{ old('price', $package->price ?? '') }}" class="form-input" min="0" required>
        @error('price')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Durasi (menit)</label>
        <input type="number" name="duration_estimate" value="{{ old('duration_estimate', $package->duration_estimate ?? 60) }}" class="form-input" min="30" required>
    </div>
    <div>
        <label class="form-label">Icon (emoji)</label>
        <input type="text" name="icon" value="{{ old('icon', $package->icon ?? '') }}" class="form-input" placeholder="🔧">
    </div>
    <div>
        <label class="form-label">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order ?? 0) }}" class="form-input" min="0">
    </div>
</div>
