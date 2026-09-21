# 🚗 Smart Otto — Sistem Inspeksi Kendaraan

Aplikasi web manajemen inspeksi kendaraan berbasis Laravel 11 + MySQL + Blade + TailwindCSS.

---

## 📋 Fitur Utama

### 👤 Customer Portal
- Landing page dengan CMS dinamis
- Booking inspeksi online dengan slot management
- Progress tracker booking real-time
- Riwayat & detail inspeksi
- Laporan inspeksi digital lengkap
- **Tanda tangan digital** pada laporan
- Manajemen kendaraan tersimpan
- Edit profil

### 🛠️ Admin Panel (`/admin`)
- Dashboard dengan statistik & grafik booking
- Kelola booking: konfirmasi, assign inspektor, update status
- Manajemen customer & inspektor
- **CMS Konten**: edit semua konten website secara dinamis
- **Master Tarif**: tarif tindakan & sparepart dengan periode aktif
- CRUD paket inspeksi & checklist item
- Laporan bulanan + export CSV

### 🔧 Inspektor Portal
- Daftar tugas yang di-assign
- Form input hasil inspeksi dengan checklist interaktif
- Upload foto dokumentasi
- Verifikasi laporan inspeksi

### 💰 Transaksi & Billing
- Input item tindakan/sparepart dari master tarif
- Perhitungan otomatis subtotal + PPN 11%
- Update status pembayaran (Belum Bayar / Bayar Sebagian / Lunas)

---

## 🗂️ Struktur Database

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Customer, Admin, Inspektor |
| `vehicles` | Data kendaraan customer |
| `inspection_packages` | Paket inspeksi (Basic/Standar/Premium) |
| `inspection_checklist_items` | Item checklist per paket |
| `bookings` | Data booking dengan status tracking |
| `inspection_results` | Hasil inspeksi + tanda tangan digital |
| `tariffs` | Master tarif tindakan & sparepart |
| `transactions` | Transaksi per booking |
| `transaction_items` | Detail item transaksi |
| `cms_contents` | Konten dinamis website |

---

## 🚀 Cara Instalasi

### Prasyarat
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js + NPM
- Laragon (Windows) atau LAMP/LEMP

### Langkah Instalasi

```bash
# 1. Clone/copy project ke folder web
# 2. Copy & sesuaikan .env
cp .env.example .env

# 3. Buat database MySQL
# CREATE DATABASE smartotto;

# 4. Install dependencies
composer install
npm install

# 5. Generate key & migrate
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link

# 6. Build assets
npm run build
# atau untuk development:
npm run dev
```

Atau jalankan `install.bat` (Windows + Laragon)

---

## 🔐 Akun Default (Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@smartotto.test` | `password` |
| Inspektor | `budi@smartotto.test` | `password` |
| Inspektor | `agus@smartotto.test` | `password` |
| Customer | `andi@gmail.com` | `password` |
| Customer | `siti@gmail.com` | `password` |

---

## 🌐 URL Akses

| Halaman | URL |
|---------|-----|
| Landing Page | `http://smartotto.test/` |
| Login Customer | `http://smartotto.test/login` |
| Register | `http://smartotto.test/register` |
| Dashboard Customer | `http://smartotto.test/customer/dashboard` |
| Booking | `http://smartotto.test/booking` |
| **Login Admin** | `http://smartotto.test/admin/login` |
| Dashboard Admin | `http://smartotto.test/admin/dashboard` |
| Dashboard Inspektor | `http://smartotto.test/inspector/dashboard` |

---

## 📁 Struktur Modul

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/          — Login, Register
│   │   ├── Admin/         — Dashboard, Booking, Inspector, Package, Tariff, CMS, Report
│   │   ├── Customer/      — Dashboard, History, Profile, Vehicle
│   │   ├── Inspector/     — Dashboard, Inspection Form
│   │   ├── Transaction/   — Transaksi & Billing
│   │   ├── BookingController.php
│   │   └── HomeController.php
│   └── Middleware/
│       └── CheckRole.php
└── Models/
    ├── User, Vehicle, InspectionPackage
    ├── InspectionChecklistItem, Booking
    ├── InspectionResult, Tariff
    ├── Transaction, TransactionItem, CmsContent
```

---

## 👥 Pembagian Tugas Tim Smart Otto

| Nama | Modul | File Utama |
|------|-------|-----------|
| **Nakita** | Customer Portal | `customer/history/*`, `customer/profile.blade.php` |
| **Jasmin** | Booking | `BookingController.php`, `booking/*` |
| **Rahma** | Inspeksi | `Inspector/InspectionController.php`, `inspector/tasks/*` |
| **Nafisah** | Tarif & Billing | `Admin/TariffController.php`, `Transaction/TransactionController.php` |
| **Daffa** | Transaksi | `transaction/show.blade.php`, `TransactionController.php` |
| **Afdal** | Admin & CMS | `Admin/*Controller.php`, `admin/*` views |

---

## 🔄 Alur Status Booking

```
pending → confirmed → waiting → on_progress → completed
                                             ↘ cancelled (kapan saja)
```

---

## ⚡ Tech Stack

- **Backend**: Laravel 11, PHP 8.2
- **Database**: MySQL 8
- **Frontend**: Blade, TailwindCSS 3, Vanilla JS
- **Auth**: Laravel custom session auth + role middleware
- **Build**: Vite 5
