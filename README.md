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

### Penjelasan Alur Scheduler

Bagian ini dibuat agar alur notifikasi mudah dijelaskan saat demo, sidang, atau saat project dilanjutkan oleh programmer lain.

Catatan:

- nomor baris di bawah ini mengacu pada kondisi kode saat README ini ditulis
- jika nanti ada refactor, nomor baris bisa bergeser
- flow utamanya tetap sama: `scheduler -> command -> automation service -> schedule service -> dispatch service`

### 1. Scheduler utama ada di `bootstrap/app.php`

File: [bootstrap/app.php](./bootstrap/app.php)

Baris penting:

- baris `16-20`

Potongan kode:

```php
->withSchedule(function (Schedule $schedule): void {
    $schedule->command('wa:send-notification')
        ->monthlyOn(01, '00:05');
    $schedule->command('wa:send-overdue-followup')
        ->dailyAt('08:00');
})
```

Arti logic:

- `wa:send-notification` adalah scheduler notifikasi bulanan
- `wa:send-overdue-followup` adalah scheduler pengingat kedua
- notifikasi pertama dikirim setiap awal bulan
- notifikasi kedua dicek setiap hari untuk mencari mitra yang sudah jatuh tempo tetapi belum membayar

### 2. Command notifikasi bulanan

File: [app/Console/Commands/SendWaNotification.php](./app/Console/Commands/SendWaNotification.php)

Baris penting:

- baris `19-31`

Potongan kode:

```php
public function handle()
{
    $result = $this->notificationAutomationService->dispatchMonthlyBatch(now());

    if ($result['processed_count'] === 0) {
        $this->info('Tidak ada notifikasi bulanan yang perlu dikirim.');
        return self::SUCCESS;
    }

    $this->info("Semua notifikasi berhasil diproses. Queue bulan ini disiapkan: {$result['prepared_count']}.");
    $this->line('Attempt ID: #' . implode(', #', $result['attempt_ids']));

    return self::SUCCESS;
}
```

Arti logic:

- command ini dipanggil oleh scheduler
- sistem memulai proses batch bulanan dengan `dispatchMonthlyBatch(now())`
- jika tidak ada data yang layak dikirim, command selesai tanpa error
- jika ada data, sistem menampilkan jumlah data yang disiapkan dan `attempt id` hasil pengiriman

### 3. Command notifikasi kedua

File: [app/Console/Commands/SendWaOverdueFollowup.php](./app/Console/Commands/SendWaOverdueFollowup.php)

Baris penting:

- baris `19-31`

Potongan kode:

```php
public function handle()
{
    $result = $this->notificationAutomationService->dispatchOverdueFollowUpBatch(now());

    if ($result['processed_count'] === 0) {
        $this->info('Tidak ada notifikasi kedua yang perlu dikirim.');
        return self::SUCCESS;
    }

    $this->info('Semua notifikasi kedua berhasil diproses.');
    $this->line('Attempt ID: #' . implode(', #', $result['attempt_ids']));

    return self::SUCCESS;
}
```

Arti logic:

- command ini khusus untuk pengingat kedua
- hanya data yang sudah jatuh tempo dan belum membayar yang akan diproses
- hasil pengiriman juga ditandai dengan `attempt id`

### 4. Automation service sebagai penghubung proses

File: [app/Services/NotificationAutomationService.php](./app/Services/NotificationAutomationService.php)

Baris penting:

- baris `16-34` untuk notifikasi bulanan
- baris `38-53` untuk notifikasi kedua

Potongan kode notifikasi bulanan:

```php
public function dispatchMonthlyBatch(?Carbon $referenceDate = null): array
{
    $referenceDate ??= now();

    $preparedNotifications = $this->notificationScheduleService->prepareMonthlyNotifications($referenceDate);
    $notifications = $this->notificationScheduleService->firstRemindersReadyForDispatch($referenceDate);

    $attemptIds = [];

    foreach ($notifications as $notification) {
        $attempt = $this->notificationDispatchService->dispatch($notification, 'first_notice_system');
        $attemptIds[] = $attempt->id;
    }

    return [
        'prepared_count' => $preparedNotifications->count(),
        'processed_count' => count($attemptIds),
        'attempt_ids' => $attemptIds,
    ];
}
```

Potongan kode notifikasi kedua:

```php
public function dispatchOverdueFollowUpBatch(?Carbon $referenceDate = null): array
{
    $referenceDate ??= now();

    $notifications = $this->notificationScheduleService->secondRemindersReadyForDispatch($referenceDate);
    $attemptIds = [];

    foreach ($notifications as $notification) {
        $attempt = $this->notificationDispatchService->dispatchSecondReminder($notification, 'second_notice_system');
        $attemptIds[] = $attempt->id;
    }

    return [
        'processed_count' => count($attemptIds),
        'attempt_ids' => $attemptIds,
    ];
}
```

Arti logic:

- service ini menjadi penghubung antara scheduler dan pengiriman
- tahap pertama: siapkan data notifikasi
- tahap kedua: pilih data yang memang sudah boleh dikirim
- tahap ketiga: kirim satu per satu dan simpan `attempt id`

### 5. Logic seleksi data notifikasi bulanan

File: [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php)

Baris penting:

- baris `13-45` menjaga satu pinjaman selalu punya jadwal notifikasi aktif
- baris `49-64` menyiapkan queue bulanan
- baris `67-85` memilih reminder pertama yang siap dikirim

Potongan kode penyiapan queue bulanan:

```php
public function prepareMonthlyNotifications(?Carbon $referenceDate = null): Collection
{
    $referenceDate = ($referenceDate ?: now())->copy()->startOfMonth();
    $monthStart = $referenceDate->copy()->startOfMonth();
    $monthEnd = $referenceDate->copy()->endOfMonth();

    return Peminjaman::query()
        ->where('pokok_sisa', '>', 0)
        ->with(['latestPembayaran', 'notifikasi'])
        ->get()
        ->filter(fn (Peminjaman $loan) => $loan->next_due_date->between($monthStart, $monthEnd))
        ->map(function (Peminjaman $loan) use ($referenceDate) {
            return $this->syncForLoan($loan);
        })
        ->filter()
        ->values();
}
```

Potongan kode reminder pertama siap kirim:

```php
public function firstRemindersReadyForDispatch(?Carbon $referenceDate = null): Collection
{
    $referenceDate = ($referenceDate ?: now())->copy();
    $monthStart = $referenceDate->copy()->startOfMonth();
    $monthEnd = $referenceDate->copy()->endOfMonth();

    return Notification::query()
        ->with(['peminjaman.latestPembayaran'])
        ->where('status', false)
        ->where('send_at', '<=', $referenceDate)
        ->whereBetween('due_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
        ->whereHas('peminjaman', function ($query) {
            $query->where('pokok_sisa', '>', 0);
        })
        ->get()
        ->filter(fn (Notification $notification) => $notification->peminjaman
            && $notification->due_date
            && $notification->due_date->isSameDay($notification->peminjaman->next_due_date))
        ->values();
}
```

Arti logic:

- hanya pinjaman dengan `pokok_sisa > 0` yang masuk antrian
- hanya pinjaman yang jatuh tempo pada bulan berjalan yang disiapkan
- notifikasi pertama hanya boleh dikirim jika:
  - `status = false`
  - `send_at` sudah lewat atau sama dengan waktu sekarang
  - `due_date` masih sama dengan siklus jatuh tempo aktif

### 6. Logic seleksi notifikasi kedua

File: [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php)

Baris penting:

- baris `89-105` memilih reminder kedua
- baris `108-115` memastikan pinjaman benar-benar jatuh tempo dan belum lunas

Potongan kode:

```php
public function secondRemindersReadyForDispatch(?Carbon $referenceDate = null): Collection
{
    $referenceDate = ($referenceDate ?: now())->copy()->startOfDay();

    return Notification::query()
        ->with(['peminjaman.latestPembayaran'])
        ->whereDate('due_date', '<=', $referenceDate->toDateString())
        ->whereNull('follow_up_sent_at')
        ->whereHas('peminjaman', function ($query) {
            $query->where('pokok_sisa', '>', 0);
        })
        ->get()
        ->filter(fn (Notification $notification) => $notification->peminjaman
            && $notification->due_date
            && $notification->due_date->isSameDay($notification->peminjaman->next_due_date)
            && $this->isLoanDueAndUnpaid($notification->peminjaman, $referenceDate))
        ->values();
}
```

Potongan kode validasi jatuh tempo:

```php
public function isLoanDueAndUnpaid(Peminjaman $peminjaman, ?Carbon $referenceDate = null): bool
{
    $referenceDate = ($referenceDate ?: now())->copy()->startOfDay();
    $loan = $peminjaman->loadMissing('latestPembayaran');

    return (int) $loan->pokok_sisa > 0
        && $loan->next_due_date->lte($referenceDate);
}
```

Arti logic:

- reminder kedua hanya dipilih jika `due_date` sudah masuk hari ini atau sudah lewat
- `follow_up_sent_at` harus masih kosong
- pinjaman masih aktif dan belum lunas
- sistem mengecek ulang apakah pinjaman benar-benar overdue pada siklus saat ini

### 7. Logic anti duplikasi agar tidak spam

File: [app/Services/NotificationDispatchService.php](./app/Services/NotificationDispatchService.php)

Baris penting:

- baris `40-56` mencegah reminder kedua terkirim dua kali
- baris `59-71` mencatat attempt proses kirim
- baris `82-93` menandai status kirim berhasil

Potongan kode:

```php
if ($isFollowUp && $notification->follow_up_sent_at) {
    return $notification->attempts()->create([
        'peminjaman_id' => $notification->peminjaman_id,
        'kontak' => $notification->kontak,
        'message' => $message,
        'channel' => 'whatsapp',
        'trigger_type' => $triggerType,
        'send_status' => 'skipped',
        'payload' => [
            'kontak' => $notification->kontak,
            'message' => $message,
        ],
        'response_code' => 'FOLLOW_UP_ALREADY_SENT',
        'response_body' => 'Notifikasi kedua untuk siklus jatuh tempo ini sudah pernah dikirim.',
        'is_success' => false,
        'attempted_at' => now(),
    ]);
}
```

Potongan kode update status:

```php
$notification->update([
    'status' => true,
    'sent_at' => $isFollowUp ? $notification->sent_at : now(),
    'follow_up_sent_at' => $isFollowUp ? now() : $notification->follow_up_sent_at,
]);
```

Arti logic:

- notifikasi pertama tidak akan terkirim dua kali karena query pengambilannya hanya membaca `status = false`
- notifikasi kedua tidak akan terkirim dua kali karena query pengambilannya hanya membaca `follow_up_sent_at is null`
- jika ada trigger ulang untuk reminder kedua, sistem mencatatnya sebagai `skipped`, bukan `success`
- semua proses tetap dicatat ke tabel `notification_attempts` untuk audit

### 8. Cara sistem mendeteksi kirim ulang notifikasi

Bagian ini penting karena sering menjadi pertanyaan saat review atau sidang.

Prinsip utamanya:

- sistem tidak mendeteksi kirim ulang hanya dari ada atau tidaknya row di tabel `notifications`
- sistem membedakan antara `siklus jatuh tempo yang sama` dan `siklus jatuh tempo baru`
- selama masih satu siklus, notifikasi tidak boleh dikirim ulang
- jika sudah masuk siklus baru, row yang sama boleh dipakai lagi setelah di-reset

File utama:

- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `13-45`
- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `67-105`
- [app/Services/NotificationDispatchService.php](./app/Services/NotificationDispatchService.php) baris `40-56`

Potongan kode pembanding siklus:

```php
$sameCycle = $notification->due_date
    && $notification->due_date->isSameDay($nextDueDate);

if (! $sameCycle) {
    $payload['status'] = 0;
    $payload['sent_at'] = null;
    $payload['follow_up_sent_at'] = null;
}
```

Arti logic:

- jika `due_date` notifikasi masih sama dengan `next_due_date` pinjaman, maka sistem menganggap itu masih satu siklus
- jika berbeda, maka sistem menganggap sudah masuk siklus baru
- pada siklus baru, row notifikasi lama tidak dibuang, tetapi di-reset agar bisa dipakai lagi

Deteksi kirim ulang reminder pertama:

- scheduler hanya mengambil data dengan `status = false`
- jika `status = true`, berarti reminder pertama pada siklus itu sudah pernah dikirim

Deteksi kirim ulang reminder kedua:

- scheduler hanya mengambil data dengan `follow_up_sent_at is null`
- jika `follow_up_sent_at` sudah terisi, berarti reminder kedua sudah pernah dikirim
- jika ada trigger ulang, dispatch service akan menandainya sebagai `skipped`

Kesimpulan:

- notifikasi pertama dikendalikan oleh kombinasi `status` dan `sameCycle`
- notifikasi kedua dikendalikan oleh kombinasi `follow_up_sent_at` dan `sameCycle`
- histori pengiriman detail tetap ada di tabel `notification_attempts`

### 9. Cara sistem menentukan jatuh tempo berikutnya

File: [app/Models/Peminjaman.php](./app/Models/Peminjaman.php)

Baris penting:

- baris `107-120` mengambil pembayaran terakhir lalu menghitung jatuh tempo berikutnya
- baris `179-181` menyediakan accessor `next_due_date`

Potongan kode:

```php
public function latestPembayaran()
{
    return $this->hasOne(Pembayaran::class)->ofMany('tanggal_pembayaran', 'max');
}

public function resolveNextDueDate(): Carbon
{
    $referenceDate = $this->relationLoaded('latestPembayaran')
        ? $this->latestPembayaran?->tanggal_pembayaran
        : $this->pembayaran()->latest('tanggal_pembayaran')->value('tanggal_pembayaran');

    $referenceDate ??= $this->tgl_peminjaman;

    return Carbon::parse($referenceDate)->addMonth()->startOfDay();
}
```

Arti logic:

- jika ada pembayaran terakhir, jatuh tempo berikutnya dihitung dari tanggal pembayaran terakhir + 1 bulan
- jika belum ada pembayaran, jatuh tempo dihitung dari `tgl_peminjaman + 1 bulan`
- hasil inilah yang dipakai oleh scheduler saat memilih siapa yang harus dikirimi notifikasi

### 10. Penjelasan saat penguji mempertanyakan row notifikasi tidak bertambah setiap bulan

Ini adalah bagian yang sering menimbulkan salah paham.

Yang benar:

- tabel `notifications` pada desain ini berfungsi sebagai `status aktif notifikasi per pinjaman`
- tabel `notifications` bukan tabel histori bulanan
- karena itu, untuk `peminjaman_id` yang sama, sistem memang tidak menambah row notifikasi baru setiap bulan
- row notifikasi yang sama akan diupdate dan di-reset jika pinjaman masuk siklus jatuh tempo baru

Kalimat yang bisa dipakai:

`Pada desain ini, tabel notifications menyimpan status aktif notifikasi per pinjaman, bukan histori notifikasi bulanan. Jadi row untuk peminjaman_id yang sama memang di-update, bukan diinsert ulang. Histori detail pengiriman disimpan di tabel notification_attempts.`

Implikasi bisnisnya:

- jika mitra sudah membayar, `next_due_date` maju ke bulan berikutnya, sehingga row notifikasi lama di-reset dan bisa dipakai lagi
- jika mitra belum membayar, `next_due_date` tidak maju, sehingga sistem tidak menganggap ada siklus bulanan baru
- dalam kondisi belum bayar, yang bekerja adalah flow `overdue follow-up`, bukan `first reminder` bulanan baru

### 11. Ringkasan flow sederhana

Urutan proses lengkapnya:

1. Scheduler menjalankan `wa:send-notification` atau `wa:send-overdue-followup`.
2. Command memanggil `NotificationAutomationService`.
3. `NotificationAutomationService` meminta `NotificationScheduleService` untuk memilih data yang layak dikirim.
4. `NotificationDispatchService` memproses pengiriman.
5. Status notifikasi diupdate.
6. Semua hit pengiriman dicatat ke tabel `notification_attempts`.

### 12. Peta perubahan kondisi bisnis dan bagian kode yang harus diubah

Bagian ini disusun agar programmer berikutnya tahu titik ubah mana yang paling relevan ketika kebijakan perusahaan berubah.

#### A. Jika perusahaan ingin mengubah tanggal atau jam scheduler utama

Bagian yang perlu diubah:

- [bootstrap/app.php](./bootstrap/app.php) baris `16-20`

Contoh:

```php
$schedule->command('wa:send-notification')
    ->monthlyOn(5, '07:30');
```

Dampak:

- hanya mengubah waktu eksekusi command
- tidak mengubah aturan seleksi data

#### B. Jika perusahaan ingin reminder kedua dikirim pada jam yang berbeda

Bagian yang perlu diubah:

- [bootstrap/app.php](./bootstrap/app.php) baris `19-20`

Contoh:

```php
$schedule->command('wa:send-overdue-followup')
    ->dailyAt('16:00');
```

Dampak:

- hanya mengubah jam follow-up
- tidak mengubah rule overdue

#### C. Jika perusahaan ingin notifikasi pertama dikirim 2 kali dalam sebulan

Bagian yang perlu dicek dan diubah:

- [bootstrap/app.php](./bootstrap/app.php) baris `16-20`
- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `163-166`
- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `67-85`

Contoh perubahan scheduler:

```php
$schedule->command('wa:send-notification')
    ->monthlyOn(1, '00:05');

$schedule->command('wa:send-notification')
    ->monthlyOn(15, '00:05');
```

Catatan:

- perubahan ini belum cukup jika status notifikasi masih hanya memakai `status = true/false`
- jika ingin 2 batch reguler dalam 1 bulan, sebaiknya tambah penanda baru seperti:
  - `first_batch_sent_at`
  - `second_batch_sent_at`

Alasan:

- saat ini scheduler pertama hanya mengenal satu status kirim utama
- bila ada dua batch reguler dalam satu bulan, sistem membutuhkan penanda yang lebih rinci agar batch pertama dan batch kedua tidak saling menimpa

#### D. Jika perusahaan ingin pinjaman yang belum bayar tetap menerima reminder pertama lagi pada bulan berikutnya

Ini adalah perubahan rule yang cukup besar.

Bagian yang perlu dicek dan diubah:

- [app/Models/Peminjaman.php](./app/Models/Peminjaman.php) baris `112-120`
- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `49-64`
- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `67-85`
- kemungkinan struktur tabel `notifications`

Alasan:

- saat ini `next_due_date` hanya maju jika ada pembayaran
- jadi bila belum ada pembayaran, sistem tidak menganggap ada due cycle baru
- kalau perusahaan ingin reminder bulanan berulang sampai lunas, desain sekarang belum cukup

Solusi yang biasanya dipertimbangkan:

- menambah histori notifikasi per periode
- atau menambah field khusus seperti `last_monthly_reminder_sent_at`
- atau mengubah `notifications` dari tabel status aktif menjadi tabel histori per siklus

#### E. Jika perusahaan ingin message diubah

Bagian yang perlu diubah:

- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `127-149`

Contoh:

- ubah isi `buildMonthlyMessage()`
- ubah isi `buildOverdueMessage()`

#### F. Jika perusahaan ingin mengganti logic siapa yang layak menerima notifikasi

Bagian yang perlu diubah:

- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `49-64`
- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `67-85`
- [app/Services/NotificationScheduleService.php](./app/Services/NotificationScheduleService.php) baris `89-115`

Contoh kasus:

- menambah filter wilayah
- hanya mengirim ke kategori kredit tertentu
- hanya mengirim ke mitra yang validasi kontaknya lolos

#### G. Jika perusahaan ingin pakai API WhatsApp sungguhan

Bagian yang perlu diubah:

- [app/Services/NotificationDispatchService.php](./app/Services/NotificationDispatchService.php) baris `73-87`

Saat ini masih simulator:

```php
Log::info('SIMULASI WA TERKIRIM', [
    'trigger' => $triggerType,
    'ke' => $notification->kontak,
    'message' => $message,
    'notification_id' => $notification->id,
    'attempt_id' => $attempt->id,
]);
```

Nanti bagian ini bisa diganti menjadi request HTTP ke provider WhatsApp.

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
