@extends('layouts.admin')
@section('page-title', 'Buat Booking')
@section('content')
<div class="max-w-2xl space-y-4">
    <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-6">
        <form method="POST" action="{{ route('admin.bookings.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="form-label">Customer</label>
                    <select name="user_id" class="form-input" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $c)<option value="{{ $c->id }}" {{ old('user_id')==$c->id?'selected':'' }}>{{ $c->name }} ({{ $c->email }})</option>@endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label">Paket Inspeksi</label>
                    <select name="package_id" class="form-input" required>
                        @foreach($packages as $p)<option value="{{ $p->id }}" {{ old('package_id')==$p->id?'selected':'' }}>{{ $p->name }} — {{ $p->formatted_price }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Nomor Polisi</label>
                    <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="form-input uppercase" placeholder="B 1234 ABC">
                </div>
                <div>
                    <label class="form-label">Merk Kendaraan</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" class="form-input" placeholder="Toyota">
                </div>
                <div>
                    <label class="form-label">Model</label>
                    <input type="text" name="model" value="{{ old('model') }}" class="form-input" placeholder="Avanza">
                </div>
                <div>
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" value="{{ old('year') }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Jenis</label>
                    <select name="type" class="form-input">
                        <option value="mobil">Mobil</option><option value="motor">Motor</option>
                        <option value="truk">Truk</option><option value="bus">Bus</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="booking_date" value="{{ old('booking_date') }}" class="form-input" required>
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
                        @foreach($inspectors as $i)<option value="{{ $i->id }}">{{ $i->name }}</option>@endforeach
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
@endsection
