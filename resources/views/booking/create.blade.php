@extends('layouts.app')
@section('title', 'Booking Inspeksi')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Booking Inspeksi Kendaraan</h1>
        <p class="text-gray-500 mt-2">Isi form di bawah untuk membuat jadwal inspeksi</p>
    </div>

    <div class="card p-8">
        <form method="POST" action="{{ route('booking.store') }}" class="space-y-6">
            @csrf

            {{-- Step 1: Data Kendaraan --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm">1</span>
                    Data Kendaraan
                </h2>

                @auth
                @if($vehicles->count())
                <div class="flex gap-4 mb-4">
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="radio" name="vehicle_type" value="existing" checked class="text-primary-600">
                        Pilih kendaraan tersimpan
                    </label>
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="radio" name="vehicle_type" value="new" class="text-primary-600">
                        Tambah kendaraan baru
                    </label>
                </div>

                <div id="existing-vehicle" class="space-y-3">
                    <select name="vehicle_id" id="existing_vehicle_select" class="form-input">
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}"
                                data-model="{{ $v->model }}"
                                {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->brand }} {{ $v->model }} — {{ $v->plate_number }} ({{ $v->year }})
                        </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                @else
                <input type="hidden" name="vehicle_type" value="new">
                @endif
                @endauth

                <div id="new-vehicle" class="{{ $vehicles->count() ? 'hidden' : '' }} grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                    <div>
                        <label class="form-label">Merk</label>
                        <input type="text" name="brand" value="{{ old('brand') }}"
                               class="form-input" placeholder="Toyota, Honda...">
                        @error('brand')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Model</label>
                        <input type="text" name="model" id="new_vehicle_model"
                               value="{{ old('model') }}"
                               class="form-input" placeholder="Avanza, Jazz...">
                        @error('model')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Nomor Polisi</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}"
                               class="form-input uppercase" placeholder="B 1234 ABC">
                        @error('plate_number')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" value="{{ old('year') }}"
                               class="form-input" placeholder="{{ date('Y') }}">
                        @error('year')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Jenis Kendaraan</label>
                        <select name="type" class="form-input">
                            <option value="mobil" {{ old('type')=='mobil'?'selected':'' }}>Mobil</option>
                            <option value="motor" {{ old('type')=='motor'?'selected':'' }}>Motor</option>
                            <option value="truk"  {{ old('type')=='truk'?'selected':'' }}>Truk</option>
                            <option value="bus"   {{ old('type')=='bus'?'selected':'' }}>Bus</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Warna</label>
                        <input type="text" name="color" value="{{ old('color') }}"
                               class="form-input" placeholder="Putih, Hitam...">
                    </div>
                </div>
            </div>

            {{-- Step 2: Kategori & Estimasi Harga (readonly, info saja) --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm">2</span>
                    Estimasi Harga
                </h2>

                <div id="category-info-box" class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <div id="category-loading" class="text-sm text-gray-400 hidden">Mendeteksi kategori...</div>

                    <div id="category-found" class="hidden">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Kategori Kendaraan</p>
                        <p id="cat-name" class="font-semibold text-gray-800 text-base"></p>
                        <p class="text-xs text-gray-500 mt-2 uppercase tracking-wide mb-1">Kisaran Harga Inspeksi</p>
                        <p id="cat-range" class="text-primary-600 font-bold text-lg"></p>
                        <p class="text-xs text-gray-400 mt-2">
                            Harga final dikonfirmasi oleh admin setelah booking diterima.
                        </p>
                    </div>

                    <div id="category-not-found" class="text-sm text-gray-500">
                        Isi data kendaraan untuk melihat estimasi harga.
                    </div>

                    <div id="category-ambiguous" class="hidden text-sm text-amber-600">
                        ⚠ Model cocok ke beberapa kategori. Admin akan mengonfirmasi kategori dan harga.
                    </div>
                </div>

                {{-- Hidden input — dikirim ke controller untuk disimpan --}}
                <input type="hidden" name="category_id" id="hidden_category_id" value="{{ old('category_id') }}">
            </div>

            {{-- Step 3: Jadwal --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm">3</span>
                    Pilih Jadwal
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tanggal Inspeksi</label>
                        <input type="date" id="booking_date" name="booking_date"
                               value="{{ old('booking_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="form-input @error('booking_date') border-red-500 @enderror" required>
                        @error('booking_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Jam Inspeksi</label>
                        <input type="hidden" id="booking_time" name="booking_time"
                               value="{{ old('booking_time') }}" required>
                        <div id="slot-container" class="flex flex-wrap gap-2 mt-1">
                            <p class="text-sm text-gray-400">Pilih tanggal terlebih dahulu</p>
                        </div>
                        @error('booking_time')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Step 4: Catatan --}}
            <div>
                <label class="form-label">Catatan / Keluhan (opsional)</label>
                <textarea name="notes" rows="3" class="form-input"
                          placeholder="Ceritakan keluhan atau hal yang ingin dicek secara khusus...">{{ old('notes') }}</textarea>
            </div>

            <div class="pt-2">
                @guest
                <p class="text-sm text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 mb-4">
                    ⚠️ Anda harus <a href="{{ route('login') }}" class="font-semibold underline">login</a> atau
                    <a href="{{ route('register') }}" class="font-semibold underline">daftar</a> sebelum melakukan booking.
                </p>
                @endguest
                <button type="submit" class="btn-primary w-full justify-center text-base py-3" @guest disabled @endguest>
                    🔧 Buat Booking Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    // ── Elemen UI ──────────────────────────────────────────────
    var foundBox      = document.getElementById('category-found');
    var notFoundBox   = document.getElementById('category-not-found');
    var ambiguousBox  = document.getElementById('category-ambiguous');
    var loadingBox    = document.getElementById('category-loading');
    var catName       = document.getElementById('cat-name');
    var catRange      = document.getElementById('cat-range');
    var hiddenCatId   = document.getElementById('hidden_category_id');
    var existingSelect= document.getElementById('existing_vehicle_select');
    var newModelInput = document.getElementById('new_vehicle_model');
    var detectTimer   = null;

    function showState(state) {
        [foundBox, notFoundBox, ambiguousBox, loadingBox].forEach(function(el) {
            if (el) el.classList.add('hidden');
        });
        if (state === 'found')     { foundBox.classList.remove('hidden'); }
        if (state === 'notfound')  { notFoundBox.classList.remove('hidden'); }
        if (state === 'ambiguous') { ambiguousBox.classList.remove('hidden'); }
        if (state === 'loading')   { loadingBox.classList.remove('hidden'); }
    }

    function detectCategory(model) {
        if (!model || model.length < 2) { showState('notfound'); return; }
        showState('loading');
        fetch('/booking/detect-category?model=' + encodeURIComponent(model))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.ambiguous) {
                    hiddenCatId.value = '';
                    showState('ambiguous');
                } else if (data.category) {
                    hiddenCatId.value  = data.category.id;
                    catName.textContent = data.category.name;
                    catRange.textContent= data.category.formatted_range;
                    showState('found');
                } else {
                    hiddenCatId.value = '';
                    showState('notfound');
                }
            })
            .catch(function() { showState('notfound'); });
    }

    // Kendaraan tersimpan dipilih → detect dari model kendaraan
    if (existingSelect) {
        existingSelect.addEventListener('change', function () {
            var opt = existingSelect.options[existingSelect.selectedIndex];
            detectCategory(opt.dataset.model || '');
        });
        // Trigger on load jika ada old value
        if (existingSelect.value) existingSelect.dispatchEvent(new Event('change'));
    }

    // Kendaraan baru diketik
    if (newModelInput) {
        newModelInput.addEventListener('input', function () {
            clearTimeout(detectTimer);
            detectTimer = setTimeout(function () {
                detectCategory(newModelInput.value.trim());
            }, 600);
        });
        if (newModelInput.value) detectCategory(newModelInput.value.trim());
    }

    // Toggle existing/new vehicle
    var radioButtons = document.querySelectorAll('input[name="vehicle_type"]');
    radioButtons.forEach(function (r) {
        r.addEventListener('change', function () {
            var isNew = r.value === 'new';
            var existingDiv = document.getElementById('existing-vehicle');
            var newDiv      = document.getElementById('new-vehicle');
            if (existingDiv) existingDiv.classList.toggle('hidden', isNew);
            if (newDiv)      newDiv.classList.toggle('hidden', !isNew);
            if (isNew) {
                // Reset deteksi
                hiddenCatId.value = '';
                showState('notfound');
            } else if (existingSelect && existingSelect.value) {
                existingSelect.dispatchEvent(new Event('change'));
            }
        });
    });

    // ── Slot jam (sudah ada sebelumnya) ───────────────────────
    var dateInput = document.getElementById('booking_date');
    if (dateInput) {
        dateInput.addEventListener('change', function () {
            var slotContainer = document.getElementById('slot-container');
            var timeInput     = document.getElementById('booking_time');
            if (!this.value) return;
            slotContainer.innerHTML = '<p class="text-sm text-gray-400">Memuat slot...</p>';
            fetch('/booking/slots?date=' + this.value)
                .then(function (r) { return r.json(); })
                .then(function (slots) {
                    slotContainer.innerHTML = '';
                    slots.forEach(function (slot) {
                        var btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = slot.time;
                        btn.disabled = !slot.available;
                        btn.className = slot.available
                            ? 'px-4 py-2 rounded-lg border-2 text-sm font-medium transition-colors border-gray-200 hover:border-primary-500 hover:bg-primary-50'
                            : 'px-4 py-2 rounded-lg border-2 text-sm font-medium border-gray-100 text-gray-300 cursor-not-allowed';
                        if (slot.time === timeInput.value) {
                            btn.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-700');
                        }
                        btn.addEventListener('click', function () {
                            timeInput.value = slot.time;
                            slotContainer.querySelectorAll('button').forEach(function (b) {
                                b.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-700');
                                b.classList.add('border-gray-200');
                            });
                            btn.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-700');
                        });
                        slotContainer.appendChild(btn);
                    });
                });
        });
        if (dateInput.value) dateInput.dispatchEvent(new Event('change'));
    }
})();
</script>
@endpush
@endsection
