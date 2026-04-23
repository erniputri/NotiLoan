-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: notiloan
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-navbar_notifications:v1','a:2:{s:19:\"navbarNotifications\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:16:\"navbarNotifCount\";i:0;}',1776784245);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'0_create_peminjaman_table',1),(5,'2025_12_17_065548_create_notifications_table',1),(6,'2025_12_23_044841_add_peminjaman_id_to_notifications_table',1),(7,'2026_02_05_152028_add_extra_columns_to_peminjaman',1),(8,'2026_02_10_104236_create_pembayaran_table',1),(9,'2026_02_13_135027_add_sent_at_to_notifications',1),(10,'2026_04_12_000001_create_notification_attempts_table',1),(11,'2026_04_15_000002_add_due_date_and_follow_up_sent_at_to_notifications_table',1),(12,'2026_04_15_211000_normalize_kontak_format',1),(13,'2026_04_15_220000_add_virtual_account_bank_to_peminjaman',1),(14,'2026_04_19_000001_add_role_to_users_table',2),(15,'2026_04_21_000001_create_mitras_table',3),(16,'2026_04_21_160000_add_performance_indexes_to_notiloan_tables',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mitras`
--

DROP TABLE IF EXISTS `mitras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mitras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_mitra` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `virtual_account_bank` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `virtual_account` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_mitra` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kabupaten` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sektor` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mitras_nomor_mitra_index` (`nomor_mitra`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mitras`
--

LOCK TABLES `mitras` WRITE;
/*!40000 ALTER TABLE `mitras` DISABLE KEYS */;
INSERT INTO `mitras` VALUES (1,'001',NULL,'Bank BRK - 123456768','Erni','(+62) 82287654321','jl. damai sejah tera','Dumai','Industri','2026-04-19 08:01:48','2026-04-19 08:01:48'),(2,'02',NULL,'Bank Mandiri - 92813023121','Erni sianturi','(+62) 82287654321','jl. damai sejah tera','dumai','Industri','2026-04-19 08:01:48','2026-04-21 06:10:42'),(3,'003','Bank BRI','Bank BRK - 123456768','test 1','(+62) 82287654321','jl. damai sejah tera','Dumai','Industri','2026-04-21 06:53:57','2026-04-21 06:53:57'),(4,'022','Bank BRI','123213213','Dayat','(+62) 82287654321','jl. damai sejah tera','ROKAN HULU','Industri','2026-04-21 07:06:55','2026-04-21 07:06:55');
/*!40000 ALTER TABLE `mitras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification_attempts`
--

DROP TABLE IF EXISTS `notification_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification_attempts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `notification_id` bigint unsigned DEFAULT NULL,
  `peminjaman_id` bigint unsigned DEFAULT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'whatsapp',
  `trigger_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `send_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'processing',
  `payload` json DEFAULT NULL,
  `response_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_body` text COLLATE utf8mb4_unicode_ci,
  `is_success` tinyint(1) NOT NULL DEFAULT '0',
  `attempted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_attempts_notification_id_foreign` (`notification_id`),
  KEY `notification_attempts_peminjaman_id_foreign` (`peminjaman_id`),
  KEY `notification_attempts_attempted_at_index` (`attempted_at`),
  KEY `notification_attempts_send_status_index` (`send_status`),
  KEY `notification_attempts_trigger_type_index` (`trigger_type`),
  CONSTRAINT `notification_attempts_notification_id_foreign` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notification_attempts_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification_attempts`
--

LOCK TABLES `notification_attempts` WRITE;
/*!40000 ALTER TABLE `notification_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peminjaman_id` bigint unsigned NOT NULL,
  `kontak` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date DEFAULT NULL,
  `send_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `follow_up_sent_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_peminjaman_id_foreign` (`peminjaman_id`),
  KEY `notifications_status_index` (`status`),
  KEY `notifications_send_at_index` (`send_at`),
  KEY `notifications_due_date_index` (`due_date`),
  CONSTRAINT `notifications_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (3,3,'(+62) 82287654321','Yth Erni, pembayaran pinjaman Anda dijadwalkan jatuh tempo pada 2026-05-15. Silakan siapkan pembayaran melalui Virtual Account Bank BRK - 123456768.','2026-05-15','2026-05-01 00:05:00',0,'2026-04-19 08:01:48','2026-04-19 08:01:48',NULL,NULL),(4,4,'(+62) 82287654321','Yth Erni sianturi, pembayaran pinjaman Anda dijadwalkan jatuh tempo pada 2026-05-21. Silakan siapkan pembayaran melalui Virtual Account Bank Mandiri - 92813023121.','2026-05-21','2026-05-01 00:05:00',0,'2026-04-19 08:01:48','2026-04-21 05:29:10',NULL,NULL),(5,5,'(+62) 82287654321','Yth test 1, pembayaran pinjaman Anda dijadwalkan jatuh tempo pada 2026-05-21. Silakan siapkan pembayaran melalui Virtual Account Bank BRI - Bank BRK - 123456768.','2026-05-21','2026-05-01 00:05:00',0,'2026-04-19 08:03:32','2026-04-21 06:54:07',NULL,NULL);
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayarans`
--

DROP TABLE IF EXISTS `pembayarans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayarans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peminjaman_id` bigint unsigned NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `jumlah_bayar` decimal(15,2) NOT NULL,
  `bukti_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayarans_peminjaman_id_foreign` (`peminjaman_id`),
  KEY `pembayarans_tanggal_pembayaran_index` (`tanggal_pembayaran`),
  CONSTRAINT `pembayarans_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayarans`
--

LOCK TABLES `pembayarans` WRITE;
/*!40000 ALTER TABLE `pembayarans` DISABLE KEYS */;
INSERT INTO `pembayarans` VALUES (1,4,'2026-04-21',1000000.00,'bukti-pembayaran/FV6upVCwXzO3xeR34MfISS8pY9UxWHz5QU7Tt959.jpg','2026-04-21 05:29:10','2026-04-21 05:29:10'),(2,4,'2026-04-21',122121.00,NULL,'2026-04-21 06:10:42','2026-04-21 06:10:42'),(4,5,'2026-04-21',1000000.00,NULL,'2026-04-21 08:01:32','2026-04-21 08:01:32');
/*!40000 ALTER TABLE `pembayarans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peminjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mitra_id` bigint unsigned DEFAULT NULL,
  `nomor_mitra` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `virtual_account_bank` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `virtual_account` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_mitra` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `kabupaten` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_peminjaman` date NOT NULL,
  `tgl_jatuh_tempo` date DEFAULT NULL,
  `tgl_akhir_pinjaman` date DEFAULT NULL,
  `lama_angsuran_bulan` int NOT NULL DEFAULT '0',
  `no_surat_perjanjian` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bunga_persen` decimal(5,2) NOT NULL DEFAULT '0.00',
  `pokok_pinjaman_awal` bigint NOT NULL,
  `administrasi_awal` bigint NOT NULL DEFAULT '0',
  `jaminan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pokok_cicilan_sd` bigint NOT NULL DEFAULT '0',
  `jasa_cicilan_sd` bigint NOT NULL DEFAULT '0',
  `pokok_sisa` bigint NOT NULL DEFAULT '0',
  `jasa_sisa` bigint NOT NULL DEFAULT '0',
  `sektor` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kualitas_kredit` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peminjaman_mitra_id_foreign` (`mitra_id`),
  KEY `peminjaman_pokok_sisa_index` (`pokok_sisa`),
  KEY `peminjaman_tgl_peminjaman_index` (`tgl_peminjaman`),
  KEY `peminjaman_kualitas_kredit_index` (`kualitas_kredit`),
  CONSTRAINT `peminjaman_mitra_id_foreign` FOREIGN KEY (`mitra_id`) REFERENCES `mitras` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES (3,1,'001',NULL,'Bank BRK - 123456768','Erni','(+62) 82287654321','jl. damai sejah tera','Dumai','2026-04-15','2027-04-15','2027-04-15',12,NULL,6.00,1000000,60000,NULL,0,0,1000000,0,'Industri','Lancar','2026-04-19 08:01:48','2026-04-19 08:01:48'),(4,2,'02',NULL,'Bank Mandiri - 92813023121','Erni sianturi','(+62) 82287654321','jl. damai sejah tera','dumai','2026-02-04','2026-08-04','2026-08-04',4,NULL,6.00,21000000,1260000,NULL,0,0,19877879,0,'Industri','Lancar','2026-04-19 08:01:48','2026-04-21 06:10:42'),(5,3,'003','Bank BRI','Bank BRK - 123456768','test 1','(+62) 82287654321','jl. damai sejah tera','Dumai','2026-04-21','2027-04-21','2027-04-21',11,NULL,6.00,1000000,60000,'12312',0,0,0,0,'Industri','Lancar','2026-04-19 08:03:32','2026-04-21 08:01:32');
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('4j2eEhaBbq17MPkkVQP4SvzBonqi4tZGQbzPbJP0',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSzZNTGVGS2VjeFVKYmNGRTdTcU1mVXU0NVRBRFptTXh5VmxUMW1GbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXRhIjtzOjU6InJvdXRlIjtzOjEwOiJkYXRhLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1776784116),('6Un7cSwv00O2Z6zvdO3gNdpHDR3Tnqw0frzZnYn4',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWmM1TkZBSjFmTFpnNWdhenpPVk1zVGN4WnhBUlBiUmJTWHNhcVZlQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9ub3RpbG9hbi50ZXN0L2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1776784230);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Erni','12345','admin',NULL,'$2y$12$gZXyP5XseLrFqSipz8DPeu5RG2lLhAidBYsCWubwZCPfabiBe0KSG',NULL,'2026-04-15 08:47:20','2026-04-15 08:47:20'),(2,'Super Admin','10001','super_admin','2026-04-19 06:59:23','$2y$12$GIx7Jxyu8Ucv4hTx4WOZuuOdx1GmbbrijMrkY1ivrCWNhPhVwSXlS',NULL,'2026-04-19 06:59:23','2026-04-19 06:59:23'),(3,'Hidayatul','12345678','admin',NULL,'$2y$12$w/cRc5PB1FT50lEkK5/qnekkc0JEE1puKoq0gBst4fUjl8/LEx28i',NULL,'2026-04-19 07:05:46','2026-04-19 07:05:46');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'notiloan'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-23 16:14:54
