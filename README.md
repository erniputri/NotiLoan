# NotiLoan

Aplikasi Notifikasi Online untuk Para Nasabah Peminjam berbasis Laravel.

Project ini dipakai untuk:
- mengelola data pinjaman mitra
- mencatat pembayaran
- mengirim notifikasi pengingat pinjaman
- memantau status pinjaman melalui dashboard admin
- mengelola user dengan role `super_admin` dan `admin`

## Fitur Utama

- Login berbasis SAP
- Role user:
  - `super_admin`
  - `admin`
- CRUD data pinjaman
- CRUD pembayaran
- Monitoring notifikasi
- Pengingat notifikasi otomatis:
  - notifikasi awal bulan
  - pengingat kedua untuk pinjaman jatuh tempo yang belum dibayar
- Import dan export Excel
- Dashboard monitoring pinjaman

## Tech Stack

- PHP 8.4
- Laravel 12
- MySQL 8.0.30
- Blade
- TailwindCSS
- Laravel Excel (`maatwebsite/excel`)

## Kebutuhan Sistem

Pastikan environment Anda minimal memiliki:

- PHP `8.4+`
- Composer
- MySQL / MariaDB `8.0+`
- Node.js dan npm
- Web server lokal seperti Laragon, XAMPP, atau `php artisan serve`

## Instalasi Project

1. Clone repository

```bash
git clone <url-repository>
cd NotiLoan
```

2. Install dependency PHP

```bash
composer install
```

3. Copy file environment

```bash
copy .env.example .env
```

Jika menggunakan Linux / macOS:

```bash
cp .env.example .env
```

4. Generate app key

```bash
php artisan key:generate
```

5. Atur koneksi database di file `.env`

Contoh untuk MySQL:

```env
APP_NAME=NotiLoan
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=notiloan
DB_USERNAME=root
DB_PASSWORD=
```

## Konfigurasi Super Admin

Seeder super admin membaca nilai berikut dari `.env`:

```env
SUPER_ADMIN_NAME="Super Admin"
SUPER_ADMIN_SAP=10001
SUPER_ADMIN_PASSWORD=admin123
```

Jika tidak diisi, sistem akan memakai default di atas.

## Migrasi dan Seeder

Jalankan migrasi:

```bash
php artisan migrate
```

Lalu seed akun super admin:

```bash
php artisan db:seed --class=SuperAdminSeeder
```

Atau jalankan semua seeder:

```bash
php artisan db:seed
```

## Opsi Restore Menggunakan File SQL

Project ini juga menyediakan file database siap pakai di root project:

- `notiloan.sql`

File ini disediakan untuk kebutuhan restore manual ke server, terutama jika deployment tidak menggunakan `php artisan migrate`.

Catatan:

- `notiloan.sql` adalah dump database terbaru dari MySQL lokal
- file tersebut berisi struktur tabel dan data yang sedang digunakan aplikasi
- file SQL ini berguna untuk menjaga data tetap aman ketika server tidak memakai migration Laravel
- backup sebelum update terakhir tersedia pada file `notiloan-before-update-20260423-161344.sql`

### Cara Import SQL ke MySQL

1. Buat database tujuan terlebih dahulu jika belum ada:

```sql
CREATE DATABASE notiloan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import file SQL ke database tersebut:

```bash
mysql -u root -p notiloan < notiloan.sql
```

Jika menggunakan Laragon/XAMPP dan password MySQL kosong, command biasanya menjadi:

```bash
mysql -u root notiloan < notiloan.sql
```

Jika database sudah ada dan Anda ingin refresh isi databasenya, pastikan data lama memang boleh ditimpa karena file SQL akan melakukan `DROP TABLE` sebelum membuat tabel kembali.

### Cara Update File SQL dari Database Lokal

Jika data di aplikasi sudah berubah dan ingin menyimpan kondisi database terbaru ke file SQL root, jalankan:

```bash
mysqldump -u root -p notiloan > notiloan.sql
```

Jika password MySQL kosong:

```bash
mysqldump -u root notiloan > notiloan.sql
```

### Kapan Menggunakan File SQL

Gunakan `notiloan.sql` jika:

- server tujuan tidak menjalankan migration Laravel
- Anda ingin restore database secara cepat
- Anda membutuhkan paket project yang sudah menyertakan struktur dan data awal

### Catatan Penting

- jika data lokal berubah, file `notiloan.sql` perlu diperbarui ulang agar tetap sesuai kondisi database terbaru
- file SQL bisa berisi data aplikasi, jadi jangan upload ke repository publik jika data di dalamnya bersifat sensitif
- untuk development normal, migration Laravel tetap menjadi cara yang lebih terstruktur
- untuk server yang tidak menjalankan migration, pastikan import SQL dilakukan sebelum aplikasi dipakai

## Menjalankan Aplikasi

### Opsi 1: Menggunakan Laravel built-in server

```bash
php artisan serve
```

Aplikasi akan berjalan di:

```txt
http://127.0.0.1:8000
```

### Opsi 2: Menggunakan Laragon

Jika project diletakkan di folder `www` Laragon, jalankan Apache dan MySQL dari Laragon, lalu akses domain lokal sesuai konfigurasi Anda.

Contoh:

```txt
http://notiloan.test
```

## Build Frontend

Install dependency frontend:

```bash
npm install
```

Untuk development:

```bash
npm run dev
```

Untuk build production asset:

```bash
npm run build
```

## Akun Login Awal

Setelah seeder dijalankan, login menggunakan:

- SAP: `10001`
- Password: `admin123`

Jika Anda mengubah nilai `SUPER_ADMIN_*` di `.env`, gunakan nilai tersebut saat login.

## Struktur Role

### Super Admin

Memiliki akses penuh ke seluruh modul, termasuk:

- dashboard
- data pinjaman
- pembayaran
- notifikasi
- halaman user

### Admin

Memiliki akses ke modul operasional, tetapi tidak bisa membuka halaman manajemen user.

## Scheduler Notifikasi

Project ini memiliki scheduler otomatis di [bootstrap/app.php](./bootstrap/app.php):

- `wa:send-notification`
  - jalan setiap tanggal 1 pukul `00:05`
- `wa:send-overdue-followup`
  - jalan setiap hari pukul `08:00`

Untuk development lokal, jalankan:

```bash
php artisan schedule:work
```

Untuk server production, gunakan cron:

```bash
* * * * * php /path-ke-project/artisan schedule:run >> /dev/null 2>&1
```

## Command Notifikasi

Jalankan manual jika ingin test:

```bash
php artisan wa:send-notification
php artisan wa:send-overdue-followup
```

Catatan:
- saat ini dispatch WhatsApp masih bersifat simulasi di `NotificationDispatchService`
- semua attempt pengiriman tetap dicatat ke tabel `notification_attempts`
- jika ingin memakai API WhatsApp sungguhan, titik integrasinya paling tepat ada di `NotificationDispatchService`

## Import Excel

Fitur import data pinjaman memakai template resmi dari sistem.

Aturan import saat ini:

- wajib memakai template hasil download dari aplikasi
- semua header kolom harus lengkap
- semua kolom pada setiap baris wajib diisi
- nama header harus sesuai template

Kolom import yang digunakan sistem:

- `nomor_mitra`
- `virtual_account_bank`
- `virtual_account`
- `nama_mitra`
- `kontak`
- `alamat`
- `kabupaten`
- `sektor`
- `tgl_peminjaman`
- `tgl_jatuh_tempo`
- `tgl_akhir_pinjaman`
- `lama_angsuran_bulan`
- `bunga_persen`
- `pokok_pinjaman_awal`
- `administrasi_awal`
- `no_surat_perjanjian`
- `jaminan`
- `pokok_cicilan_sd`
- `jasa_cicilan_sd`
- `pokok_sisa`
- `jasa_sisa`
- `kualitas_kredit`

Jika ada header yang kurang atau nilai kosong, sistem akan menolak file import dan menampilkan pesan error.

## Export Excel

Fitur export mendukung:

- pemilihan kolom secara dinamis
- mengikuti filter pencarian yang aktif
- styling header
- format angka dan tanggal

## Testing

Jalankan test dengan:

```bash
php artisan test
```

## Troubleshooting

### 1. Gagal koneksi database

Periksa kembali:

- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### 2. Seeder super admin tidak terbaca

Pastikan Anda sudah menambahkan variabel ini di `.env` atau gunakan nilai default:

```env
SUPER_ADMIN_NAME="Super Admin"
SUPER_ADMIN_SAP=10001
SUPER_ADMIN_PASSWORD=admin123
```

### 3. Import Excel gagal

Pastikan:

- file memakai template resmi
- seluruh kolom terisi
- format tanggal valid
- format angka tidak rusak

### 4. Notifikasi otomatis tidak jalan

Pastikan scheduler aktif:

```bash
php artisan schedule:work
```

atau cron server sudah dikonfigurasi.

## Catatan Pengembangan

Jika ingin melanjutkan pengembangan project ini, area yang biasanya paling sering disentuh adalah:

- `app/Services`
- `app/Http/Controllers`
- `app/Imports`
- `app/Exports`
- `resources/views/pages`
- `routes/web.php`
