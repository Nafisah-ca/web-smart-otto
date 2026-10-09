@extends('layouts.admin')
@section('page-title', 'Buat Booking')
@section('content')
<div class="max-w-2xl space-y-4">
    <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-6">
        <form method="POST" action="{{ route('admin.bookings.store') }}" class="space-y-4">
            @csrf

            {{-- Customer --}}
            <div class="sm:col-span-2">
                <label class="form-label">Customer</label>
                <select name="user_id" class="form-input" required>
                    <option value="">-- Pilih Customer --</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ old('user_id')==$c->id?'selected':'' }}>
                        {{ $c->name }} ({{ $c->email }})
                    </option>
                    @endforeach
                </select>
                @error('user_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Data kendaraan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nomor Polisi</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number') }}"
                           class="form-input uppercase" placeholder="B 1234 ABC">
                </div>
                <div>
                    <label class="form-label">Merk Kendaraan</label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                           class="form-input" placeholder="Toyota">
                </div>
                <div>
                    <label class="form-label">Model</label>
                    {{-- Saat model diketik, JS akan AJAX ke /booking/detect-category --}}
                    <input type="text" name="model" id="vehicle_model"
                           value="{{ old('model') }}"
                           class="form-input" placeholder="Avanza, Fortuner, Brio..."
                           autocomplete="off">
                    <p class="text-xs text-gray-400 mt-1">Kategori harga terisi otomatis setelah model diisi</p>
                </div>
                <div>
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" value="{{ old('year') }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Jenis</label>
                    <select name="type" class="form-input">
                        <option value="mobil">Mobil</option>
                        <option value="motor">Motor</option>
                        <option value="truk">Truk</option>
                        <option value="bus">Bus</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Warna</label>
                    <input type="text" name="color" value="{{ old('color') }}"
                           class="form-input" placeholder="Putih, Hitam...">
                </div>
            </div>

            {{-- Kategori + Harga --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-3">
                <h3 class="text-sm font-semibold text-gray-700">Kategori & Harga Inspeksi</h3>

                {{-- Kategori (auto-detect, bisa override manual) --}}
                <div>
                    <label class="form-label">Kategori Kendaraan</label>
                    <select name="category_id" id="category_id" class="form-input">
                        <option value="">-- Tidak Terdeteksi / Pilih Manual --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                                data-min="{{ $cat->price_min }}"
                                data-max="{{ $cat->price_max }}"
                                data-range="{{ $cat->formatted_range }}"
                                {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} — {{ $cat->formatted_range }}
                        </option>
                        @endforeach
                    </select>
                    <div id="category-status" class="text-xs mt-1 text-gray-400"></div>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                {{-- Kisaran harga (readonly, info saja) --}}
                <div id="price-range-row" class="hidden">
                    <p class="text-xs text-gray-500">
                        Kisaran harga kategori ini:
                        <span id="price-range-label" class="font-semibold text-gray-700"></span>
                    </p>
                </div>

                {{-- Harga final --}}
                <div>
                    <label class="form-label">
                        Harga Final
                        <span class="ml-1 text-xs text-gray-400 font-normal">(boleh diubah dalam kisaran)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">Rp</span>
                        <input type="number" name="final_price" id="final_price"
                               value="{{ old('final_price') }}"
                               min="0" step="1000"
                               class="form-input pl-9"
                               placeholder="Isi harga final...">
                    </div>
                    @error('final_price')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Jadwal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="booking_date" value="{{ old('booking_date') }}"
                           class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Jam</label>
                    <select name="booking_time" class="form-input" required>
                        @foreach(['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'] as $slot)
                        <option value="{{ $slot }}">{{ $slot }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Inspektor (opsional)</label>
                    <select name="inspector_id" class="form-input">
                        <option value="">-- Assign Nanti --</option>
                        @foreach($inspectors as $i)
                        <option value="{{ $i->id }}">{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" rows="2" class="form-input">{{ old('notes') }}</textarea>
                </div>
            </div>

            <button class="btn-primary">Buat Booking</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var modelInput    = document.getElementById('vehicle_model');
    var categorySelect= document.getElementById('category_id');
    var statusEl      = document.getElementById('category-status');
    var rangeRow      = document.getElementById('price-range-row');
    var rangeLabel    = document.getElementById('price-range-label');
    var finalPriceEl  = document.getElementById('final_price');

    // Saat kategori dipilih manual, update range & default harga
    categorySelect.addEventListener('change', function () {
        var opt = categorySelect.options[categorySelect.selectedIndex];
        if (opt.value) {
            var min = parseFloat(opt.dataset.min);
            rangeLabel.textContent = opt.dataset.range;
            rangeRow.classList.remove('hidden');
            if (!finalPriceEl.value) finalPriceEl.value = min;
            statusEl.textContent = '';
        } else {
            rangeRow.classList.add('hidden');
        }
    });

    // Saat halaman load, tampilkan range jika ada old value
    if (categorySelect.value) categorySelect.dispatchEvent(new Event('change'));

    // Auto-detect saat model diisi (debounce 600ms)
    var detectTimer = null;
    modelInput.addEventListener('input', function () {
        clearTimeout(detectTimer);
        var model = modelInput.value.trim();
        if (model.length < 3) {
            statusEl.textContent = '';
            return;
        }
        detectTimer = setTimeout(function () {
            statusEl.textContent = 'Mendeteksi kategori...';
            fetch('/booking/detect-category?model=' + encodeURIComponent(model))
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.ambiguous) {
                        statusEl.textContent = '⚠ Model cocok ke beberapa kategori. Pilih manual.';
                        statusEl.className   = 'text-xs mt-1 text-amber-600';
                    } else if (data.category) {
                        // Set dropdown
                        for (var i = 0; i < categorySelect.options.length; i++) {
                            if (categorySelect.options[i].value == data.category.id) {
                                categorySelect.selectedIndex = i;
                                categorySelect.dispatchEvent(new Event('change'));
                                break;
                            }
                        }
                        statusEl.textContent = '✓ Kategori terdeteksi otomatis.';
                        statusEl.className   = 'text-xs mt-1 text-green-600';
                    } else {
                        statusEl.textContent = 'Model tidak dikenali. Pilih kategori manual.';
                        statusEl.className   = 'text-xs mt-1 text-gray-400';
                    }
                })
                .catch(function () {
                    statusEl.textContent = '';
                });
        }, 600);
    });
})();
</script>
@endpush
@endsection
