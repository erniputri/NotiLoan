-- =========================================================
-- NotiLoan
-- Schema dan data awal database aplikasi
-- Versi bersih untuk restore manual ke server
-- Backup dump mentah tetap tersedia pada file timestamped
-- File ini disusun ulang agar lebih mudah dibaca.
-- Tujuannya supaya struktur database, relasi tabel, dan data awal
-- dapat dipahami tanpa harus membaca dump MySQL mentah.
-- =========================================================

CREATE DATABASE IF NOT EXISTS `notiloan`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `notiloan`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;

-- =========================================================
-- Reset tabel
-- =========================================================
-- Bagian ini memastikan proses import SQL dimulai dari kondisi bersih.
-- Urutan drop disusun dari tabel turunan ke tabel induk agar foreign key
-- tidak mengganggu saat database direstore ulang.

DROP TABLE IF EXISTS `notification_attempts`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `pembayarans`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `peminjaman`;

-- =========================================================
-- Tabel user dan autentikasi
-- =========================================================
-- Kelompok tabel ini mendukung proses login, session, dan reset password.
-- Role user dipakai untuk membedakan hak akses super admin dan admin biasa.

CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `role` VARCHAR(30) NOT NULL DEFAULT 'admin',
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabel sistem Laravel
-- =========================================================
-- Tabel di bagian ini dipakai framework untuk cache, queue, failed job,
-- batch queue, dan riwayat migrasi. Secara bisnis bukan tabel utama,
-- tetapi tetap penting agar aplikasi dapat berjalan normal di server.

CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT DEFAULT NULL,
    `cancelled_at` INT DEFAULT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Tabel utama bisnis
-- =========================================================
-- Ini adalah inti dari aplikasi NotiLoan.
-- Tabel peminjaman menyimpan data pinjaman mitra,
-- tabel pembayaran menyimpan cicilan yang masuk,
-- tabel notifications menyimpan jadwal dan status notifikasi,
-- sedangkan notification_attempts dipakai untuk audit pengiriman.

CREATE TABLE `peminjaman` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nomor_mitra` VARCHAR(50) DEFAULT NULL,
    `virtual_account_bank` VARCHAR(100) DEFAULT NULL,
    `virtual_account` VARCHAR(50) DEFAULT NULL,
    `nama_mitra` VARCHAR(255) NOT NULL,
    `kontak` VARCHAR(20) DEFAULT NULL,
    `alamat` TEXT DEFAULT NULL,
    `kabupaten` VARCHAR(100) DEFAULT NULL,
    `tgl_peminjaman` DATE NOT NULL,
    `tgl_jatuh_tempo` DATE DEFAULT NULL,
    `tgl_akhir_pinjaman` DATE DEFAULT NULL,
    `lama_angsuran_bulan` INT NOT NULL DEFAULT 0,
    `no_surat_perjanjian` VARCHAR(100) DEFAULT NULL,
    `bunga_persen` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `pokok_pinjaman_awal` BIGINT NOT NULL,
    `administrasi_awal` BIGINT NOT NULL DEFAULT 0,
    `jaminan` VARCHAR(255) DEFAULT NULL,
    `pokok_cicilan_sd` BIGINT NOT NULL DEFAULT 0,
    `jasa_cicilan_sd` BIGINT NOT NULL DEFAULT 0,
    `pokok_sisa` BIGINT NOT NULL DEFAULT 0,
    `jasa_sisa` BIGINT NOT NULL DEFAULT 0,
    `sektor` VARCHAR(50) DEFAULT NULL,
    `kualitas_kredit` VARCHAR(30) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pembayarans` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `peminjaman_id` BIGINT UNSIGNED NOT NULL,
    `tanggal_pembayaran` DATE NOT NULL,
    `jumlah_bayar` DECIMAL(15,2) NOT NULL,
    `bukti_pembayaran` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `pembayarans_peminjaman_id_foreign` (`peminjaman_id`),
    CONSTRAINT `pembayarans_peminjaman_id_foreign`
        FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `peminjaman_id` BIGINT UNSIGNED NOT NULL,
    `kontak` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `due_date` DATE DEFAULT NULL,
    `send_at` DATETIME NOT NULL,
    `status` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `sent_at` TIMESTAMP NULL DEFAULT NULL,
    `follow_up_sent_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `notifications_peminjaman_id_foreign` (`peminjaman_id`),
    CONSTRAINT `notifications_peminjaman_id_foreign`
        FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notification_attempts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `notification_id` BIGINT UNSIGNED DEFAULT NULL,
    `peminjaman_id` BIGINT UNSIGNED DEFAULT NULL,
    `kontak` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `channel` VARCHAR(255) NOT NULL DEFAULT 'whatsapp',
    `trigger_type` VARCHAR(255) NOT NULL DEFAULT 'system',
    `send_status` VARCHAR(255) NOT NULL DEFAULT 'processing',
    `payload` JSON DEFAULT NULL,
    `response_code` VARCHAR(255) DEFAULT NULL,
    `response_body` TEXT DEFAULT NULL,
    `is_success` TINYINT(1) NOT NULL DEFAULT 0,
    `attempted_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `notification_attempts_notification_id_foreign` (`notification_id`),
    KEY `notification_attempts_peminjaman_id_foreign` (`peminjaman_id`),
    CONSTRAINT `notification_attempts_notification_id_foreign`
        FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`)
        ON DELETE SET NULL,
    CONSTRAINT `notification_attempts_peminjaman_id_foreign`
        FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Data aplikasi
-- =========================================================
-- Data di bawah ini adalah data awal yang ikut dibawa saat file SQL direstore.
-- Isinya mencakup data migrasi yang pernah berjalan, user, data pinjaman,
-- serta notifikasi yang sudah tersimpan pada saat backup dibuat.

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
    (1, '0001_01_01_000000_create_users_table', 1),
    (2, '0001_01_01_000001_create_cache_table', 1),
    (3, '0001_01_01_000002_create_jobs_table', 1),
    (4, '0_create_peminjaman_table', 1),
    (5, '2025_12_17_065548_create_notifications_table', 1),
    (6, '2025_12_23_044841_add_peminjaman_id_to_notifications_table', 1),
    (7, '2026_02_05_152028_add_extra_columns_to_peminjaman', 1),
    (8, '2026_02_10_104236_create_pembayaran_table', 1),
    (9, '2026_02_13_135027_add_sent_at_to_notifications', 1),
    (10, '2026_04_12_000001_create_notification_attempts_table', 1),
    (11, '2026_04_15_000002_add_due_date_and_follow_up_sent_at_to_notifications_table', 1),
    (12, '2026_04_15_211000_normalize_kontak_format', 1),
    (13, '2026_04_15_220000_add_virtual_account_bank_to_peminjaman', 1),
    (14, '2026_04_19_000001_add_role_to_users_table', 2);

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
    (1, 'Erni', '12345', 'admin', NULL, '$2y$12$gZXyP5XseLrFqSipz8DPeu5RG2lLhAidBYsCWubwZCPfabiBe0KSG', NULL, '2026-04-15 08:47:20', '2026-04-15 08:47:20'),
    (2, 'Super Admin', '10001', 'super_admin', '2026-04-19 06:59:23', '$2y$12$GIx7Jxyu8Ucv4hTx4WOZuuOdx1GmbbrijMrkY1ivrCWNhPhVwSXlS', NULL, '2026-04-19 06:59:23', '2026-04-19 06:59:23'),
    (3, 'Hidayatul', '12345678', 'admin', NULL, '$2y$12$w/cRc5PB1FT50lEkK5/qnekkc0JEE1puKoq0gBst4fUjl8/LEx28i', NULL, '2026-04-19 07:05:46', '2026-04-19 07:05:46');
-- Catatan:
-- Password pada tabel users sudah dalam bentuk hash, bukan password asli.
-- Jadi file ini aman untuk restore sistem, tetapi tetap perlu dijaga
-- karena memuat akun yang dapat dipakai untuk login aplikasi.

INSERT INTO `peminjaman` (
    `id`, `nomor_mitra`, `virtual_account_bank`, `virtual_account`, `nama_mitra`, `kontak`, `alamat`, `kabupaten`,
    `tgl_peminjaman`, `tgl_jatuh_tempo`, `tgl_akhir_pinjaman`, `lama_angsuran_bulan`, `no_surat_perjanjian`,
    `bunga_persen`, `pokok_pinjaman_awal`, `administrasi_awal`, `jaminan`, `pokok_cicilan_sd`, `jasa_cicilan_sd`,
    `pokok_sisa`, `jasa_sisa`, `sektor`, `kualitas_kredit`, `created_at`, `updated_at`
) VALUES
    (3, '001', NULL, 'Bank BRK - 123456768', 'Erni', '(+62) 82287654321', 'jl. damai sejah tera', 'Dumai', '2026-04-15', '2027-04-15', '2027-04-15', 12, NULL, 6.00, 1000000, 60000, NULL, 0, 0, 1000000, 0, 'Industri', 'Lancar', '2026-04-19 08:01:48', '2026-04-19 08:01:48'),
    (4, '02', NULL, 'Bank Mandiri - 92813023121', 'Erni sianturi', '(+62) 82287654321', 'jl. damai sejah tera', 'dumai', '2026-02-04', '2026-08-04', '2026-08-04', 6, NULL, 6.00, 21000000, 1260000, NULL, 0, 0, 21000000, 0, 'Industri', 'Kurang Lancar', '2026-04-19 08:01:48', '2026-04-19 08:01:48'),
    (5, '001', NULL, 'Bank BRK - 123456768', 'test 1', '(+62) 82287654321', 'jl. damai sejah tera', 'Dumai', '2026-04-15', '2027-04-15', '2027-04-15', 12, NULL, 6.00, 1000000, 60000, NULL, 0, 0, 1000000, 0, 'Industri', 'Lancar', '2026-04-19 08:03:32', '2026-04-19 08:03:32');
-- Data peminjaman di atas menunjukkan contoh pinjaman aktif yang masih
-- dipakai sistem untuk monitoring jatuh tempo, kualitas kredit, dan notifikasi.

INSERT INTO `notifications` (
    `id`, `peminjaman_id`, `kontak`, `message`, `due_date`, `send_at`, `status`, `created_at`, `updated_at`, `sent_at`, `follow_up_sent_at`
) VALUES
    (3, 3, '(+62) 82287654321', 'Yth Erni, pembayaran pinjaman Anda dijadwalkan jatuh tempo pada 2026-05-15. Silakan siapkan pembayaran melalui Virtual Account Bank BRK - 123456768.', '2026-05-15', '2026-05-01 00:05:00', 0, '2026-04-19 08:01:48', '2026-04-19 08:01:48', NULL, NULL),
    (4, 4, '(+62) 82287654321', 'Yth Erni sianturi, pembayaran pinjaman Anda dijadwalkan jatuh tempo pada 2026-03-04. Silakan siapkan pembayaran melalui Virtual Account Bank Mandiri - 92813023121.', '2026-03-04', '2026-03-01 00:05:00', 0, '2026-04-19 08:01:48', '2026-04-19 08:01:48', NULL, NULL),
    (5, 5, '(+62) 82287654321', 'Yth test 1, pembayaran pinjaman Anda dijadwalkan jatuh tempo pada 2026-05-15. Silakan siapkan pembayaran melalui Virtual Account Bank BRK - 123456768.', '2026-05-15', '2026-05-01 00:05:00', 0, '2026-04-19 08:03:32', '2026-04-19 08:03:32', NULL, NULL);
-- Data notifikasi ini menjadi bukti bahwa aplikasi menyimpan jadwal kirim,
-- isi pesan, dan status pengiriman untuk tiap pinjaman yang aktif.

SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;

-- =========================================================
-- Selesai
-- =========================================================
-- Setelah file ini dijalankan, struktur tabel dan data awal utama
-- akan terbentuk kembali di database target.
