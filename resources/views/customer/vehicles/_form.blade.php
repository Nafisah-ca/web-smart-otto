<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="form-label">Merk</label>
        <input type="text" name="brand" value="{{ old('brand', $vehicle->brand ?? '') }}" class="form-input @error('brand') border-red-500 @enderror" placeholder="Toyota, Honda..." required>
        @error('brand')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Model</label>
        <input type="text" name="model" value="{{ old('model', $vehicle->model ?? '') }}" class="form-input @error('model') border-red-500 @enderror" placeholder="Avanza, Jazz..." required>
        @error('model')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Nomor Polisi</label>
        <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number ?? '') }}" class="form-input uppercase @error('plate_number') border-red-500 @enderror" placeholder="B 1234 ABC" required>
        @error('plate_number')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Tahun</label>
        <input type="number" name="year" value="{{ old('year', $vehicle->year ?? '') }}" class="form-input @error('year') border-red-500 @enderror" placeholder="{{ date('Y') }}" min="1990" max="{{ date('Y') }}" required>
        @error('year')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="form-label">Jenis Kendaraan</label>
        <select name="type" class="form-input" required>
            @foreach(['mobil'=>'Mobil','motor'=>'Motor','truk'=>'Truk','bus'=>'Bus'] as $k=>$v)
            <option value="{{ $k }}" {{ old('type', $vehicle->type ?? '')  === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Warna</label>
        <input type="text" name="color" value="{{ old('color', $vehicle->color ?? '') }}" class="form-input" placeholder="Putih, Hitam...">
    </div>
    <div>
        <label class="form-label">No. Mesin (opsional)</label>
        <input type="text" name="engine_number" value="{{ old('engine_number', $vehicle->engine_number ?? '') }}" class="form-input">
    </div>
    <div>
        <label class="form-label">No. Rangka (opsional)</label>
        <input type="text" name="chassis_number" value="{{ old('chassis_number', $vehicle->chassis_number ?? '') }}" class="form-input">
    </div>
</div>
