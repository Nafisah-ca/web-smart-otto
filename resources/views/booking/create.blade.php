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

            {{-- Step 1: Pilih Paket --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm">1</span>
                    Pilih Paket Inspeksi
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($packages as $pkg)
                    <label class="cursor-pointer">
                        <input type="radio" name="package_id" value="{{ $pkg->id }}"
                               class="sr-only peer" {{ old('package_id', $selected) == $pkg->id ? 'checked' : '' }} required>
                        <div class="border-2 rounded-xl p-4 peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:border-primary-300 transition-colors">
                            <div class="text-2xl mb-1">{{ $pkg->icon ?? '🔧' }}</div>
                            <p class="font-semibold text-sm text-gray-800">{{ $pkg->name }}</p>
                            <p class="text-primary-600 font-bold mt-1">{{ $pkg->formatted_price }}</p>
                            <p class="text-xs text-gray-400">± {{ $pkg->duration_estimate }} menit</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('package_id')<p class="form-error mt-2">{{ $message }}</p>@enderror
            </div>

            {{-- Step 2: Data Kendaraan --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm">2</span>
                    Data Kendaraan
                </h2>

                @auth
                @if($vehicles->count())
                <div class="flex gap-4 mb-4">
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="radio" name="vehicle_type" value="existing" checked class="text-primary-600"> Pilih kendaraan tersimpan
                    </label>
                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                        <input type="radio" name="vehicle_type" value="new" class="text-primary-600"> Tambah kendaraan baru
                    </label>
                </div>

                <div id="existing-vehicle" class="space-y-3">
                    <select name="vehicle_id" class="form-input">
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach($vehicles as $v)
                        <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
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
                        <input type="text" name="brand" value="{{ old('brand') }}" class="form-input" placeholder="Toyota, Honda...">
                        @error('brand')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Model</label>
                        <input type="text" name="model" value="{{ old('model') }}" class="form-input" placeholder="Avanza, Jazz...">
                        @error('model')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Nomor Polisi</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="form-input uppercase" placeholder="B 1234 ABC">
                        @error('plate_number')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" value="{{ old('year') }}" class="form-input" placeholder="{{ date('Y') }}">
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
                        <input type="text" name="color" value="{{ old('color') }}" class="form-input" placeholder="Putih, Hitam...">
                    </div>
                </div>
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
                        <input type="hidden" id="booking_time" name="booking_time" value="{{ old('booking_time') }}" required>
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
                <textarea name="notes" rows="3" class="form-input" placeholder="Ceritakan keluhan atau hal yang ingin dicek secara khusus...">{{ old('notes') }}</textarea>
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
@endsection
