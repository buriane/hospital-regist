# 🏥 SIAP-RSE

> **Sistem Informasi Antrian & Pendaftaran — RS Elisabeth**
> Pendaftaran Online Pasien Umum & Asuransi

SIAP-RSE adalah aplikasi web pendaftaran pasien rumah sakit secara online yang memungkinkan pasien umum maupun peserta asuransi melakukan registrasi kunjungan dari mana saja, memilih poliklinik, dokter, dan jadwal praktik yang tersedia — tanpa perlu antre di loket sejak awal.

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Prasyarat](#-prasyarat)
- [Instalasi & Konfigurasi](#-instalasi--konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Alur Pendaftaran Pasien](#-alur-pendaftaran-pasien)
- [Struktur Database](#-struktur-database)
- [Admin Panel (Filament)](#-admin-panel-filament)
- [Rute Utama](#-rute-utama)
- [Lisensi](#-lisensi)

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🆕 **Pendaftaran Pasien Baru** | Mengisi form lengkap sebagai pasien baru |
| 🔁 **Cek Pasien Lama** | Verifikasi via No. Rekam Medis + tanggal lahir |
| 🏨 **Pilih Poliklinik & Dokter** | Memilih poliklinik, dokter, tanggal, dan jam praktik yang tersedia |
| 📅 **Jadwal Reguler & Khusus** | Mendukung jadwal dokter reguler maupun jadwal khusus |
| 🚫 **Manajemen Cuti Dokter** | Slot jadwal yang terkena cuti otomatis diblokir |
| 🎟️ **Kode Booking & QR Code** | Setiap pendaftaran menghasilkan kode booking unik beserta QR code |
| 📄 **Bukti Pendaftaran PDF** | Pasien dapat mengunduh bukti pendaftaran untuk daftar ulang di loket |
| 📊 **Kuota Praktik** | Sistem membatasi pendaftaran sesuai kuota jam praktik dokter |
| 🔐 **Admin Panel** | Manajemen data dokter, poliklinik, jadwal, dan registrasi via Filament |

---

## 🛠 Teknologi yang Digunakan

### Backend
- **PHP** `8.2`
- **Laravel Framework** `^11.9`
- **Filament v3** `^3.2` — admin panel
- **Livewire v3** `^3.5` — komponen UI reaktif
- **barryvdh/laravel-dompdf** — generate PDF bukti pendaftaran
- **mpdf/mpdf** — alternatif generate PDF
- **milon/barcode** — generate QR code kode booking

### Frontend
- **Vite 5** + `laravel-vite-plugin` — bundler aset
- **Tailwind CSS 3** — utility-first styling
- **Alpine.js 3** — interaksi UI ringan di Blade (`x-data`, `x-show`, dll.)
- **Axios** — HTTP client JavaScript

### Template & Rendering
- **Blade Templates** (`resources/views/**/*.blade.php`)

### Infrastruktur
- **MySQL** — basis data utama
- **Laravel Queue & Jobs** — antrian proses background
- **Laravel Cache** — caching data jadwal & kuota

---

## ✅ Prasyarat

Pastikan perangkat Anda telah menginstal:

- PHP `>= 8.2` (dengan ekstensi: `mbstring`, `gd`, `zip`, `pdo_mysql`)
- Composer
- Node.js & npm
- MySQL

---

## 🚀 Instalasi & Konfigurasi

### 1. Clone Repositori

```bash
git clone https://github.com/username/siap-rse.git
cd siap-rse
```

### 2. Instal Dependency PHP

```bash
composer install
```

### 3. Instal Dependency Frontend

```bash
npm install
```

### 4. Salin File Konfigurasi Environment

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi koneksi MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siap_rse
DB_USERNAME=root
DB_PASSWORD=your_password
```

Buat database terlebih dahulu:

```sql
CREATE DATABASE siap_rse;
```

### 7. Jalankan Migrasi

```bash
php artisan migrate
```

### 8. (Opsional) Jalankan Seeder

```bash
php artisan db:seed
```

### 9. Buat Symbolic Link Storage

```bash
php artisan storage:link
```

---

## ▶️ Menjalankan Aplikasi

```bash
# Terminal 1 — Laravel development server
php artisan serve

# Terminal 2 — Vite dev server (hot reload)
npm run dev

# Terminal 3 — Laravel queue worker (untuk job background)
php artisan queue:work
```

Aplikasi dapat diakses di:
- **Form Pendaftaran:** [http://localhost:8000](http://localhost:8000)
- **Admin Panel:** [http://localhost:8000/admin](http://localhost:8000/admin)

### Build untuk Produksi

```bash
npm run build
```

---

## 🔄 Alur Pendaftaran Pasien

```
Buka Halaman Utama (/)
        │
        ▼
Pilih Jenis Pasien
   ┌────┴────┐
   │         │
Pasien    Pasien
 Baru      Lama
   │         │
   │    Verifikasi No. RM
   │    + Tanggal Lahir
   │         │
   └────┬────┘
        │
        ▼
Isi / Konfirmasi Data Diri
(nama, kontak, jenis penjamin)
        │
        ▼
Pilih Poliklinik → Dokter → Tanggal → Jam Praktik
(kuota ditampilkan; slot cuti & habis kuota diblokir)
        │
        ▼
Submit Pendaftaran
        │
        ▼
Sistem Generate:
  - Kode Booking unik
  - QR Code
        │
        ▼
Tampilkan Konfirmasi Pendaftaran
        │
        ▼
Download Bukti Pendaftaran (PDF)
        │
        ▼
Daftar Ulang di Loket RS dengan Bukti PDF
```

---

## 🗄️ Struktur Database

Tabel utama yang dikelola melalui migrasi Laravel:

| Tabel | Deskripsi |
|---|---|
| `pasiens` | Data pasien (baru & lama) |
| `dokters` | Data dokter beserta spesialisasi |
| `polikliniks` | Daftar poliklinik |
| `jadwals` | Jadwal praktik reguler dokter |
| `jadwal_khususs` | Jadwal praktik khusus (pengganti/tambahan) |
| `cutis` | Data cuti dokter per tanggal |
| `registrasis` | Data pendaftaran/booking pasien |
| `jobs` | Antrian job background (Laravel Queue) |
| `cache` | Cache data aplikasi |

---

## 🔐 Admin Panel (Filament)

Panel admin dibangun menggunakan **Filament v3** dan dapat diakses di `/admin`.

Fitur yang tersedia di admin panel:

- Manajemen data **pasien**
- Manajemen **dokter** dan **poliklinik**
- Pengaturan **jadwal reguler** dan **jadwal khusus**
- Input **cuti dokter**
- Pantau dan kelola **data registrasi/booking**
- Pengaturan **kuota** per sesi praktik

Untuk membuat akun admin pertama kali:

```bash
php artisan make:filament-user
```

---

## 🗺️ Rute Utama

| Method | URI | Deskripsi |
|---|---|---|
| `GET` | `/` | Halaman form pendaftaran online |
| `POST` | `/` | Submit data pendaftaran |
| `POST` | `/cek-pasien` | Verifikasi pasien lama (No. RM + tgl lahir) |
| `POST` | `/cek-email` | Validasi ketersediaan email |
| `POST` | `/cek-no-kartu` | Validasi nomor kartu asuransi |
| `GET` | `/download-pdf/{kode}` | Unduh bukti pendaftaran PDF |
| `GET\|POST` | `/admin/*` | Admin panel (Filament) |

---

## 📁 Struktur Direktori (Ringkas)

```
siap-rse/
├── app/
│   ├── Filament/           # Resource & halaman admin panel
│   ├── Http/
│   │   ├── Controllers/    # Controller form & PDF
│   │   └── Livewire/       # Komponen Livewire reaktif
│   ├── Models/             # Eloquent models
│   └── Jobs/               # Background jobs (queue)
├── database/
│   ├── migrations/         # Skema tabel database
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── livewire/       # Blade views komponen Livewire
│   │   ├── pdf/            # Template bukti pendaftaran PDF
│   │   └── *.blade.php
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
├── .env.example
├── composer.json
├── package.json
└── vite.config.js
```

---

## 📄 Lisensi

Proyek ini dikembangkan dalam konteks kerja sama dengan **RS Elisabeth** dan bersifat proprietary. Seluruh hak cipta dilindungi.

---

> ⚠️ **Catatan:** Bukti pendaftaran yang diunduh hanya sebagai tanda pendaftaran awal. Pasien tetap wajib melakukan **daftar ulang di loket** RS Elisabeth pada hari kunjungan dengan membawa bukti PDF dan identitas diri.
