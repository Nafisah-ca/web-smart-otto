# Smart Otto — Sistem Inspeksi Kendaraan

Platform manajemen inspeksi kendaraan bekas dengan fitur Booking, CMS konten, dan Blog artikel.

---

## Persyaratan

| Kebutuhan | Versi minimal |
|-----------|--------------|
| PHP       | 8.2          |
| Composer  | 2.x          |
| Node.js   | 18.x         |
| MySQL     | 8.0          |

---

## Setup Setelah Clone / Pull

Jalankan perintah berikut dari direktori project (`web-smart-otto/`):

### 1. Install dependencies PHP

```bash
composer install
```

### 2. Salin file environment

```bash
cp .env.example .env
```

Lalu edit `.env` dan sesuaikan nilai berikut dengan environment lokal Anda:

```env
APP_URL=http://smartotto.test   # atau http://localhost/smart_otto/web-smart-otto/public
DB_DATABASE=smartotto
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Generate application key

```bash
php artisan key:generate
```

### 4. Install dependencies JavaScript & build assets

```bash
npm install
npm run build
```

> Untuk development dengan hot-reload, jalankan `npm run dev` di terminal terpisah.

### 5. Buat database

Buat database MySQL kosong dengan nama sesuai `DB_DATABASE` di `.env` Anda.

### 6. Jalankan migration

```bash
php artisan migrate
```

Ini akan membuat semua tabel yang dibutuhkan, termasuk tabel `posts` untuk fitur Blog.

### 7. Jalankan seeder

```bash
php artisan db:seed
```

Seeder akan membuat:
- Akun admin, inspektor, dan data operasional contoh
- **6 artikel blog** seputar inspeksi mobil bekas (sudah `published`)
- Konten CMS dasar (hero, statistik, footer, dll.)

> **Idempotent:** Aman dijalankan berkali-kali — tidak akan membuat duplikat.

### 8. Buat symbolic link storage (jika belum ada)

```bash
php artisan storage:link
```

### 9. Jalankan project

Dengan Laragon / XAMPP: akses via virtual host yang sudah dikonfigurasi (`http://smartotto.test`).

Atau via artisan:

```bash
php artisan serve
```

---

## Akun Default

Setelah `db:seed`, akun berikut sudah tersedia:

| Role      | Email                        | Password        |
|-----------|------------------------------|-----------------|
| Admin     | admin@smartotto.test         | Admin@2026      |
| Inspektor | inspektor1@smartotto.test    | Inspektor@2026  |
| Inspektor | inspektor2@smartotto.test    | Inspektor@2026  |

> **Penting:** Ganti password default di production. Akun customer dibuat sendiri melalui halaman `/register`.

---

## Fitur

### Website Publik
| Halaman | URL |
|---------|-----|
| Beranda | `/` |
| Layanan & Paket | `/layanan` |
| Blog | `/blog` |
| Detail Artikel | `/blog/{slug}` |
| Booking | `/booking` |
| Tentang | `/tentang` |
| Kontak | `/kontak` |

### Panel Admin (`/admin`) — Login diperlukan (role: admin)
| Modul | URL |
|-------|-----|
| Dashboard | `/admin/dashboard` |
| Manajemen Booking | `/admin/bookings` |
| CMS Konten Website | `/admin/cms` |
| **Blog Artikel** | `/admin/posts` |
| Inspektor | `/admin/inspectors` |
| Paket Inspeksi | `/admin/packages` |
| Tarif | `/admin/tariffs` |
| Laporan | `/admin/reports` |

### Fitur Blog
- **Daftar artikel** dengan grid 3 kolom, artikel unggulan (featured), filter kategori, dan pencarian
- **Detail artikel** dengan Table of Contents otomatis (dari H2/H3), sticky di desktop, collapse di mobile
- **Banner CTA** per artikel yang bisa dikonfigurasi dari CMS admin
- **Artikel terkait** otomatis berdasarkan kategori
- **SEO**: meta title, meta description, Open Graph, canonical URL

### CMS Blog (`/admin/posts`)
- **Buat** artikel dengan rich text editor, upload thumbnail, pengaturan slug otomatis
- **Edit** artikel yang sudah ada, termasuk penggantian thumbnail
- **Hapus** artikel dengan konfirmasi modal (thumbnail ikut dihapus)
- **Filter & search** berdasarkan status (Draft/Published) dan judul

---

## Struktur File Baru (Fitur Blog)

```
app/
  Http/Controllers/
    BlogController.php              # Public: index & show
    Admin/PostController.php        # CMS: CRUD artikel
  Models/
    Post.php                        # Model dengan scope published, reading_time

database/
  migrations/
    2026_10_01_000011_create_posts_table.php
  seeders/
    PostSeeder.php                  # 6 artikel contoh (idempotent)
    DatabaseSeeder.php              # PostSeeder sudah terdaftar

public/
  seed/blog/                        # Gambar seed (ter-commit di repo)
    inspeksi-mobil-bekas.svg
    mesin-mobil-bekas.svg
    dokumen-bpkb-stnk.svg
    harga-mobil-bekas.svg
    tips-beli-mobil-bekas.svg
    odometer-kilometer.svg
  uploads/                          # Upload runtime (di-.gitignore)

resources/views/
  blog/
    index.blade.php                 # Halaman daftar artikel
    show.blade.php                  # Halaman detail + TOC
  admin/posts/
    index.blade.php                 # Tabel artikel + delete modal
    create.blade.php
    edit.blade.php
    _form.blade.php                 # Form shared (create & edit)
```

---

## Git & Kolaborasi Tim

- **Jangan commit** file `.env`, database lokal, atau folder `public/uploads/`
- File gambar seed di `public/seed/blog/` **sudah ter-commit** dan akan tersedia setelah `git pull`
- Setelah pull yang mengandung migration baru, jalankan: `php artisan migrate`
- Setelah pull yang mengandung seeder baru, jalankan: `php artisan db:seed` (aman diulang)

---

## Troubleshooting

**Halaman blog 404?**
Pastikan route cache dibersihkan: `php artisan route:clear`

**Asset CSS/JS tidak ter-load?**
Jalankan ulang: `npm run build`

**Gambar thumbnail tidak muncul?**
Pastikan `php artisan storage:link` sudah dijalankan dan folder `public/uploads/blog/` dapat ditulis.

**Seeder error "Table not found"?**
Jalankan migration terlebih dahulu: `php artisan migrate`
