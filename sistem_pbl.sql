-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 16, 2026 at 03:53 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_pbl`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_terms`
--

CREATE TABLE `academic_terms` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun_akademik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('ganjil','genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ahp_comparisons`
--

CREATE TABLE `ahp_comparisons` (
  `id` bigint UNSIGNED NOT NULL,
  `segment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `criterion_a_id` bigint UNSIGNED NOT NULL,
  `criterion_b_id` bigint UNSIGNED NOT NULL,
  `value` decimal(8,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ahp_comparisons`
--

INSERT INTO `ahp_comparisons` (`id`, `segment`, `criterion_a_id`, `criterion_b_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 'group', 1, 2, '0.2000', '2025-12-18 08:51:50', '2025-12-18 08:51:54'),
(2, 'group', 1, 3, '9.0000', '2025-12-18 08:51:58', '2025-12-18 08:51:58'),
(3, 'group', 1, 4, '0.1110', '2025-12-18 08:52:01', '2025-12-18 08:52:01'),
(4, 'group', 2, 3, '9.0000', '2025-12-18 08:52:07', '2025-12-18 08:52:07'),
(5, 'group', 2, 4, '0.1110', '2025-12-18 08:52:09', '2025-12-18 08:52:09'),
(6, 'group', 3, 4, '0.1430', '2025-12-18 08:52:11', '2025-12-18 08:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `anggota_kelompok`
--

CREATE TABLE `anggota_kelompok` (
  `id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `is_leader` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anggota_kelompok`
--

INSERT INTO `anggota_kelompok` (`id`, `group_id`, `user_id`, `is_leader`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 45, 0, 'active', '2025-12-15 06:04:44', '2026-01-16 12:20:17'),
(2, 1, 32, 0, 'active', '2025-12-15 06:04:44', '2026-01-16 12:20:17'),
(3, 1, 30, 0, 'active', '2025-12-15 06:04:44', '2026-01-16 12:20:17'),
(4, 1, 33, 0, 'active', '2025-12-15 06:04:44', '2026-01-16 12:20:17'),
(5, 1, 52, 0, 'active', '2025-12-15 06:04:44', '2026-01-16 12:20:17'),
(7, 2, 42, 1, 'active', '2025-12-15 06:57:27', '2025-12-15 06:57:27'),
(8, 2, 49, 0, 'active', '2025-12-15 06:57:27', '2025-12-15 06:57:27'),
(9, 2, 29, 0, 'active', '2025-12-15 06:57:27', '2025-12-15 06:57:27'),
(10, 2, 48, 0, 'active', '2025-12-15 06:57:27', '2025-12-15 06:57:27'),
(11, 2, 43, 0, 'active', '2025-12-15 06:57:27', '2025-12-15 06:57:27'),
(12, 3, 47, 1, 'active', '2025-12-15 06:57:51', '2025-12-15 06:58:03'),
(13, 3, 34, 0, 'active', '2025-12-15 06:57:51', '2025-12-15 06:58:03'),
(14, 3, 35, 0, 'active', '2025-12-15 06:57:51', '2025-12-15 06:58:03'),
(15, 3, 37, 0, 'active', '2025-12-15 06:57:51', '2025-12-15 06:58:03'),
(16, 3, 38, 0, 'active', '2025-12-15 06:57:51', '2025-12-15 06:58:03'),
(17, 4, 36, 0, 'active', '2025-12-15 06:58:43', '2025-12-15 06:58:43'),
(18, 4, 40, 1, 'active', '2025-12-15 06:58:43', '2025-12-15 06:58:43'),
(19, 4, 51, 0, 'active', '2025-12-15 06:58:43', '2025-12-15 06:58:43'),
(20, 4, 39, 0, 'active', '2025-12-15 06:58:43', '2025-12-15 06:58:43'),
(21, 4, 28, 0, 'active', '2025-12-15 06:58:43', '2025-12-15 06:58:43'),
(22, 5, 31, 1, 'active', '2025-12-15 06:59:07', '2025-12-15 06:59:07'),
(23, 5, 46, 0, 'active', '2025-12-15 06:59:07', '2025-12-15 06:59:07'),
(24, 5, 25, 0, 'active', '2025-12-15 06:59:07', '2025-12-15 06:59:07'),
(25, 5, 41, 0, 'active', '2025-12-15 06:59:07', '2025-12-15 06:59:07'),
(26, 5, 50, 0, 'active', '2025-12-15 06:59:07', '2025-12-15 06:59:07'),
(27, 6, 27, 1, 'active', '2025-12-15 07:05:39', '2025-12-15 07:05:39'),
(28, 6, 44, 0, 'active', '2025-12-15 07:05:39', '2025-12-15 07:05:39'),
(29, 6, 26, 0, 'active', '2025-12-15 07:05:39', '2025-12-15 07:05:39'),
(30, 7, 85, 1, 'active', '2025-12-17 05:47:24', '2025-12-17 05:47:24'),
(31, 7, 86, 0, 'active', '2025-12-17 05:47:24', '2025-12-17 05:47:24'),
(32, 7, 87, 0, 'active', '2025-12-17 05:47:24', '2025-12-17 05:47:24'),
(33, 7, 88, 0, 'active', '2025-12-17 05:47:24', '2025-12-17 05:47:24'),
(34, 7, 89, 0, 'active', '2025-12-17 05:47:24', '2025-12-17 05:47:24'),
(35, 8, 82, 1, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(36, 8, 83, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(37, 8, 84, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(38, 8, 90, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(39, 8, 91, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(40, 9, 92, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(41, 9, 93, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(42, 9, 94, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(43, 9, 95, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(44, 9, 96, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(45, 10, 97, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(46, 10, 98, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(47, 10, 99, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(48, 10, 100, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(49, 10, 101, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(50, 11, 102, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(51, 11, 103, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(52, 11, 104, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(53, 11, 105, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(54, 11, 106, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(55, 12, 107, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(56, 12, 108, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(57, 13, 109, 1, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(58, 13, 110, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(59, 13, 111, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(60, 13, 112, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(61, 13, 113, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(62, 14, 114, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(63, 14, 115, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(64, 14, 116, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(65, 14, 117, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(66, 14, 118, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(67, 15, 119, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(68, 15, 120, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(69, 15, 121, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(70, 15, 122, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(71, 15, 123, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(72, 16, 124, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(73, 16, 125, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(74, 16, 126, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(75, 16, 127, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(76, 16, 128, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(77, 17, 129, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(78, 17, 130, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(79, 17, 131, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(80, 17, 132, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(81, 18, 55, 1, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(82, 18, 56, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(83, 18, 57, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(84, 18, 58, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(85, 18, 59, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(86, 19, 60, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(87, 19, 61, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(88, 19, 62, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(89, 19, 63, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(90, 19, 64, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(91, 20, 65, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(92, 20, 66, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(93, 20, 67, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(94, 20, 68, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(95, 20, 69, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(96, 21, 70, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(97, 21, 71, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(98, 21, 72, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(99, 21, 73, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(100, 21, 74, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(101, 22, 75, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(102, 22, 76, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(103, 22, 77, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(104, 22, 78, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(105, 22, 79, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(106, 23, 80, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(107, 23, 81, 0, 'active', '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(109, 1, 143, 1, 'active', '2026-01-16 12:20:17', '2026-01-16 12:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','late','excused') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen_mata_kuliah`
--

CREATE TABLE `dosen_mata_kuliah` (
  `id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `periode` enum('sebelum_uts','sesudah_uts') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sebelum_uts',
  `academic_period_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen_pbl_kelas`
--

CREATE TABLE `dosen_pbl_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `dosen_id` bigint UNSIGNED NOT NULL,
  `class_room_id` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `academic_period_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen_pbl_kelas`
--

INSERT INTO `dosen_pbl_kelas` (`id`, `dosen_id`, `class_room_id`, `is_active`, `academic_period_id`, `created_at`, `updated_at`) VALUES
(23, 23, 1, 1, 1, '2026-01-16 10:32:56', '2026-01-16 10:32:56');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas_mata_kuliah`
--

CREATE TABLE `kelas_mata_kuliah` (
  `id` bigint UNSIGNED NOT NULL,
  `class_room_id` bigint UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `rubrik_penilaian_id` bigint UNSIGNED DEFAULT NULL,
  `dosen_sebelum_uts_id` bigint UNSIGNED DEFAULT NULL,
  `dosen_sesudah_uts_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas_mata_kuliah`
--

INSERT INTO `kelas_mata_kuliah` (`id`, `class_room_id`, `mata_kuliah_id`, `rubrik_penilaian_id`, `dosen_sebelum_uts_id`, `dosen_sesudah_uts_id`, `created_at`, `updated_at`) VALUES
(5, 2, 2, 16, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(6, 2, 3, 17, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(7, 2, 4, 18, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(8, 3, 2, 16, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(9, 3, 3, 17, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(10, 3, 4, 18, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(11, 4, 2, 16, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(12, 4, 3, 17, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(13, 4, 4, 18, NULL, NULL, '2025-12-17 20:55:03', '2025-12-18 05:06:39'),
(17, 1, 4, 18, 23, 23, '2026-01-16 10:34:43', '2026-01-16 12:02:25'),
(18, 1, 3, 17, 23, 23, '2026-01-16 10:35:07', '2026-01-16 11:53:21'),
(19, 1, 2, 16, 23, 23, '2026-01-16 10:35:15', '2026-01-16 11:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `kelompok`
--

CREATE TABLE `kelompok` (
  `id` bigint UNSIGNED NOT NULL,
  `class_room_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` bigint UNSIGNED DEFAULT NULL,
  `leader_id` bigint UNSIGNED DEFAULT NULL,
  `google_drive_folder_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `ranking` int DEFAULT NULL,
  `max_members` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelompok`
--

INSERT INTO `kelompok` (`id`, `class_room_id`, `name`, `project_id`, `leader_id`, `google_drive_folder_id`, `total_score`, `ranking`, `max_members`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kelompok 1', NULL, 143, '1WOOiyIU4eI5_lrgpa5WqgVoyDV5TveZB', '0.00', NULL, 6, '2025-12-15 06:04:44', '2026-01-16 12:20:17'),
(2, 1, 'Kelompok 2', NULL, 42, NULL, '0.00', NULL, 5, '2025-12-15 06:57:26', '2025-12-15 06:57:26'),
(3, 1, 'Kelompok 3', NULL, 47, NULL, '0.00', NULL, 5, '2025-12-15 06:57:51', '2025-12-15 06:58:03'),
(4, 1, 'Kelompok 4', NULL, 40, NULL, '0.00', NULL, 5, '2025-12-15 06:58:43', '2025-12-15 06:58:43'),
(5, 1, 'Kelompok 5', NULL, 31, NULL, '0.00', NULL, 5, '2025-12-15 06:59:07', '2025-12-15 06:59:07'),
(6, 1, 'Kelompok 6', NULL, 27, NULL, '0.00', NULL, 5, '2025-12-15 07:05:39', '2025-12-15 07:05:39'),
(7, 2, 'Kelompok 1', NULL, 85, NULL, '0.00', NULL, 5, '2025-12-17 05:47:24', '2025-12-17 05:47:24'),
(8, 2, 'Kelompok 2', NULL, 82, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(9, 2, 'Kelompok 3', NULL, 92, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(10, 2, 'Kelompok 4', NULL, 97, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(11, 2, 'Kelompok 5', NULL, 102, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(12, 2, 'Kelompok 6', NULL, 107, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(13, 3, 'Kelompok 1', NULL, 109, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(14, 3, 'Kelompok 2', NULL, 114, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(15, 3, 'Kelompok 3', NULL, 119, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(16, 3, 'Kelompok 4', NULL, 124, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(17, 3, 'Kelompok 5', NULL, 129, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(18, 4, 'Kelompok 1', NULL, 55, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(19, 4, 'Kelompok 2', NULL, 60, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(20, 4, 'Kelompok 3', NULL, 65, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(21, 4, 'Kelompok 4', NULL, 70, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(22, 4, 'Kelompok 5', NULL, 75, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39'),
(23, 4, 'Kelompok 6', NULL, 80, NULL, '0.00', NULL, 5, '2025-12-17 05:57:39', '2025-12-17 05:57:39');

-- --------------------------------------------------------

--
-- Table structure for table `kemajuan_mingguan`
--

CREATE TABLE `kemajuan_mingguan` (
  `id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `week_number` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `activities` text COLLATE utf8mb4_unicode_ci,
  `achievements` text COLLATE utf8mb4_unicode_ci,
  `challenges` text COLLATE utf8mb4_unicode_ci,
  `next_week_plan` text COLLATE utf8mb4_unicode_ci,
  `documents` json DEFAULT NULL,
  `is_checked_only` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('draft','submitted','reviewed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `deadline` timestamp NULL DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` decimal(10,9) NOT NULL DEFAULT '0.000000000',
  `tipe` enum('benefit','cost') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'benefit',
  `segment` enum('group','student') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'group',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama`, `bobot`, `tipe`, `segment`, `created_at`, `updated_at`) VALUES
(1, 'Kecepatan Progres', '0.244000000', 'benefit', 'group', '2025-12-15 06:23:33', '2025-12-18 04:33:05'),
(2, 'Nilai Akhir PBL', '0.531000000', 'benefit', 'group', '2025-12-15 06:23:33', '2025-12-18 04:33:05'),
(3, 'Ketepatan Waktu', '0.153000000', 'benefit', 'group', '2025-12-15 06:23:33', '2025-12-18 04:33:05'),
(4, 'Penilaian Teman (Group)', '0.072000000', 'benefit', 'group', '2025-12-15 06:23:33', '2025-12-18 04:33:05'),
(5, 'Nilai Akhir PBL', '0.634000000', 'benefit', 'student', '2025-12-15 06:23:33', '2025-12-18 04:34:01'),
(6, 'Penilaian Teman', '0.260000000', 'benefit', 'student', '2025-12-15 06:23:33', '2025-12-18 04:33:05'),
(7, 'Kehadiran', '0.106000000', 'benefit', 'student', '2025-12-15 06:23:33', '2025-12-18 04:33:05');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `sks` int NOT NULL DEFAULT '3',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode`, `nama`, `deskripsi`, `sks`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'TI301', 'Teknik Pengambilan Keputusan', 'Mata kuliah yang mempelajari metode-metode pengambilan keputusan seperti AHP, SAW, TOPSIS, dan Weighted Product.', 3, 1, '2025-12-17 12:05:53', '2025-12-18 04:48:02'),
(3, 'TI302', 'Pemrograman Web Lanjut', 'Pengembangan aplikasi web tingkat lanjut dengan menggunakan framework modern seperti Laravel, Vue.js, dan React.', 4, 1, '2025-12-17 12:05:53', '2025-12-18 04:48:02'),
(4, 'TI303', 'Integrasi Sistem', 'Mata kuliah yang mempelajari teknik integrasi antar sistem, API development, microservices, dan SOA.', 3, 1, '2025-12-17 12:05:53', '2025-12-18 04:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_buat_tabel_cache', 1),
(2, '0001_01_01_000002_buat_tabel_pekerjaan', 1),
(3, '2025_09_23_034452_buat_tabel_pengguna', 1),
(4, '2025_09_23_034500_buat_tabel_term_akademik', 1),
(5, '2025_09_23_040159_buat_tabel_proyek', 1),
(6, '2025_09_23_040217_buat_tabel_kelompok', 1),
(7, '2025_09_23_040224_buat_tabel_anggota_kelompok', 1),
(8, '2025_09_23_040240_buat_tabel_kemajuan_mingguan', 1),
(9, '2025_09_23_040253_buat_tabel_ulasan_kemajuan', 1),
(10, '2025_09_23_040302_buat_tabel_kehadiran', 1),
(11, '2025_09_23_040310_buat_tabel_kriteria', 1),
(12, '2025_09_23_040320_buat_tabel_nilai_kelompok', 1),
(13, '2025_09_30_084956_buat_tabel_ruang_kelas', 1),
(14, '2025_09_30_085017_perbarui_tabel_kelompok_tambah_ruang_kelas_dan_ketua', 1),
(15, '2025_09_30_085037_perbarui_tabel_anggota_kelompok_tambah_ketua', 1),
(16, '2025_09_30_151623_buat_tabel_sesi', 1),
(17, '2025_10_01_071615_buat_tabel_mata_kuliah', 1),
(18, '2025_10_01_071621_buat_tabel_target_mingguan', 1),
(19, '2025_10_01_071627_tambah_subject_id_ke_tabel_ruang_kelas', 1),
(20, '2025_10_01_071632_perbarui_tabel_kemajuan_mingguan', 1),
(21, '2025_10_01_080445_tambah_subject_id_ke_tabel_kriteria', 1),
(22, '2025_10_01_143557_buat_tabel_tahun_akademik', 1),
(23, '2025_10_01_143608_buat_tabel_semester', 1),
(24, '2025_10_01_143719_tambah_tahun_akademik_dan_semester_ke_tabel_ruang_kelas', 1),
(25, '2025_10_01_162747_buat_tabel_periode_akademik', 1),
(26, '2025_10_01_162822_tambah_academic_period_id_ke_tabel_mata_kuliah', 1),
(27, '2025_10_01_162857_perbarui_ruang_kelas_untuk_periode_akademik', 1),
(28, '2025_10_01_184355_tambah_bukti_ke_tabel_target_mingguan', 1),
(29, '2025_10_02_051819_tambah_kolom_ulasan_ke_tabel_target_mingguan', 1),
(30, '2025_10_02_052303_buat_tabel_ulasan_target_mingguan', 1),
(31, '2025_10_05_buat_tambah_class_room_id_ke_tabel_pengguna', 1),
(32, '2025_10_06_084216_tambah_kolom_dosen_ke_tabel_target_mingguan', 1),
(33, '2025_10_06_174416_buat_tabel_nilai_mahasiswa', 1),
(34, '2025_10_07_022114_buat_tabel_perbandingan_ahp', 1),
(35, '2025_10_08_115257_tambah_is_open_ke_tabel_target_mingguan', 1),
(36, '2025_10_09_052207_tambah_nim_ke_tabel_pengguna', 1),
(37, '2025_10_09_162848_tambah_foto_profil_ke_tabel_pengguna', 1),
(38, '2025_10_13_034626_tambah_dosen_id_ke_tabel_ruang_kelas', 1),
(39, '2025_10_13_152800_buat_aktivitas_nullable_di_kemajuan_mingguan', 1),
(40, '2025_10_13_191339_buat_tabel_mata_kuliah_pengguna', 1),
(41, '2025_10_14_031550_buat_password_nullable_di_tabel_pengguna', 1),
(42, '2025_10_29_030000_drop_unused_rubric_tables', 1),
(43, '2025_10_30_031937_ubah_presisi_bobot_kriteria', 1),
(44, '2025_11_17_081129_remove_subject_id_from_ruang_kelas_table', 1),
(45, '2025_11_17_085353_drop_mata_kuliah_tables', 1),
(46, '2025_11_19_060352_add_grace_period_to_target_mingguan_table', 1),
(47, '2025_12_05_004102_create_settings_table', 1),
(48, '2025_12_09_181847_create_mata_kuliah_table', 1),
(49, '2025_12_09_200000_create_target_todo_items_table', 1),
(50, '2025_12_09_200001_add_score_columns_to_target_mingguan', 1),
(51, '2025_12_09_200002_create_sync_kriteria_logs_table', 1),
(52, '2025_12_09_222427_make_class_room_id_nullable_in_sync_kriteria_logs', 1),
(53, '2025_12_10_134551_add_periode_to_dosen_mata_kuliah_table', 1),
(54, '2025_12_15_132036_hapus_dosen_id_dari_ruang_kelas', 2),
(55, '2025_12_15_132839_hapus_semester_dari_ruang_kelas', 3),
(56, '2025_12_17_125600_create_kelas_mata_kuliah_table', 4),
(57, '2025_12_17_125601_create_nilai_rubrik_table', 4),
(58, '2025_12_17_131800_fix_dosen_mata_kuliah_unique_constraint', 5),
(59, '2025_12_17_141200_create_dosen_pbl_kelas_table', 6),
(60, '2025_12_17_144000_add_dosen_to_kelas_mata_kuliah_table', 7),
(61, '2025_12_17_161800_add_academic_period_to_dosen_tables', 8),
(62, '2025_12_18_033044_add_bobot_uts_uas_to_rubrik_penilaian', 9),
(63, '2025_12_18_051738_add_exam_period_status_to_periode_akademik', 10),
(64, '2026_01_01_140630_add_reminder_fields_to_targets_table', 11),
(65, '2026_01_01_144401_create_notifications_table', 11),
(66, '2026_01_01_205150_buat_tabel_ranking_mahasiswa', 12),
(68, '2026_01_04_000132_buat_tabel_password_reset_otp', 13),
(69, '2026_01_04_210007_remove_periode_from_dosen_pbl_kelas_table', 14),
(70, '2026_01_05_060000_add_parent_id_to_rubrik_item', 14),
(71, '2026_01_05_070000_create_flexible_rubrik_system', 14),
(72, '2026_01_06_091931_create_password_reset_otps_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_kelompok`
--

CREATE TABLE `nilai_kelompok` (
  `id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `criterion_id` bigint UNSIGNED NOT NULL,
  `skor` decimal(6,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai_kelompok`
--

INSERT INTO `nilai_kelompok` (`id`, `group_id`, `criterion_id`, `skor`, `created_at`, `updated_at`) VALUES
(73, 1, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(74, 1, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(75, 1, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(76, 2, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(77, 2, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(78, 2, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(79, 3, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(80, 3, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(81, 3, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(82, 4, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(83, 4, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(84, 4, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(85, 5, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(86, 5, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(87, 5, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(88, 6, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(89, 6, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(90, 6, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(91, 7, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(92, 7, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(93, 7, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(94, 8, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(95, 8, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(96, 8, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(97, 9, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(98, 9, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(99, 9, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(100, 10, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(101, 10, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(102, 10, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(103, 11, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(104, 11, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(105, 11, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(106, 12, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(107, 12, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(108, 12, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(109, 13, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(110, 13, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(111, 13, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(112, 14, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(113, 14, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(114, 14, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(115, 15, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(116, 15, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(117, 15, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(118, 16, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(119, 16, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(120, 16, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(121, 17, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(122, 17, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(123, 17, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(124, 18, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(125, 18, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(126, 18, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(127, 19, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(128, 19, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(129, 19, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(130, 20, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(131, 20, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(132, 20, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(133, 21, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(134, 21, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(135, 21, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(136, 22, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(137, 22, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(138, 22, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(139, 23, 1, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(140, 23, 2, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51'),
(141, 23, 3, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_mahasiswa`
--

CREATE TABLE `nilai_mahasiswa` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `criterion_id` bigint UNSIGNED NOT NULL,
  `skor` decimal(6,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nilai_mahasiswa`
--

INSERT INTO `nilai_mahasiswa` (`id`, `user_id`, `criterion_id`, `skor`, `created_at`, `updated_at`) VALUES
(4, 25, 5, '100.00', '2025-12-18 04:35:32', '2025-12-18 04:35:32'),
(5, 25, 6, '100.00', '2025-12-18 04:35:32', '2025-12-18 04:35:32'),
(6, 25, 7, '100.00', '2025-12-18 04:35:32', '2025-12-18 04:35:32'),
(7, 69, 5, '90.00', '2025-12-18 04:42:08', '2025-12-18 04:42:08'),
(8, 69, 6, '90.00', '2025-12-18 04:42:08', '2025-12-18 04:42:08'),
(9, 69, 7, '100.00', '2025-12-18 04:42:08', '2025-12-18 04:42:08'),
(10, 52, 5, '80.00', '2025-12-18 08:54:15', '2025-12-18 08:54:15'),
(11, 52, 6, '90.00', '2025-12-18 08:54:15', '2025-12-18 08:54:15'),
(12, 52, 7, '95.00', '2025-12-18 08:54:15', '2025-12-18 08:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_rubrik`
--

CREATE TABLE `nilai_rubrik` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `kelas_mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `rubrik_item_id` bigint UNSIGNED NOT NULL,
  `nilai` decimal(5,2) NOT NULL DEFAULT '0.00',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `dinilai_oleh` bigint UNSIGNED NOT NULL,
  `dinilai_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_otp`
--

CREATE TABLE `password_reset_otp` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_otps`
--

CREATE TABLE `password_reset_otps` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` bigint UNSIGNED NOT NULL,
  `politala_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nim` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('mahasiswa','dosen','admin','koordinator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mahasiswa',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_room_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `politala_id`, `nim`, `name`, `email`, `profile_photo_path`, `email_verified_at`, `password`, `role`, `phone`, `program_studi`, `class_room_id`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(23, 'DSN_DOSEN_799', NULL, 'Dosen', 'dosen@politala.ac.id', NULL, NULL, '$2y$12$I9S/ziwuAiuU86gPAnWae.oqyWEnXSjMka9z.caGzcj1A7o2xg61C', 'dosen', NULL, 'Sistem Informasi', NULL, 1, NULL, '2025-12-15 04:57:20', '2025-12-15 04:57:20'),
(24, 'ADMIN_ADMIN_591', NULL, 'Admin', 'admin@politala.ac.id', 'profile-photos/9pZu1vUVWgH7gzd2fLo21dDgiiHRdMcNoRuOCkcg.jpg', NULL, '$2y$12$Hp1bGWmZ/VWY0aVzKcgQRuaVNYLrZYek/EWN8bLQ2HnCziiuHyLDa', 'admin', NULL, 'Sistem Informasi', NULL, 1, NULL, '2025-12-15 05:07:34', '2026-01-16 15:12:01'),
(25, 'RF4908', '2301301001', 'RIZQIA FEBRIANOOR', 'rizqiafebriannoor23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(26, 'ZNJ5055', '2301301006', 'ZUBAIDAH NUR JUMIAH', 'zubaidahnurjumiah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(27, 'SF1751', '2301301007', 'SITI FATIMAH', 'sitifatimah231@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(28, 'NI5005', '2301301017', 'NADELLA IRSASYARIFA', 'nadellairsasyarifa23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(29, 'FAA9394', '2301301025', 'FITRI AYU ANGGRAINI', 'fitriayuanggaraini23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(30, 'AMZ6844', '2301301026', 'ANNISA MAULIDA ZUHRIAH', 'annisamaulidazuhriah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(31, 'RR1538', '2301301027', 'RAHMA RAIHANI', 'rahmaraihani23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(32, 'AAP2066', '2301301033', 'AKHMAD ADI PURNOMO', 'akhmadadipurnomo23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(33, 'AN4804', '2301301037', 'ANNISA NURHIDAYAH', 'annisanurhidayah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(34, 'MFR2740', '2301301049', 'MUHAMMAD FAJAR RAMADHAN', 'muhammadfajarramadhan23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(35, 'MHA3979', '2301301055', 'MUHAMMAD HAFIZ ANSORI', 'muhammadhafizansori23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(36, 'MR7166', '2301301062', 'MUHAMMAD RIFANI', 'muhammadrifani23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(37, 'MNK8477', '2301301063', 'MUHAMMAD NOOR KHALIS', 'muhammadnoorkhalis23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(38, 'MRA4756', '2301301064', 'MUHAMMAD RADHI AKBAR', 'muhammadradhiakbar23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(39, 'MWP8266', '2301301073', 'MUHAMMAD WIDIGDA PRATAMA', 'muhammadwidigdapratama23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(40, 'MSR6255', '2301301076', 'MUHAMMAD SAHLIL RIZKI', 'muhammadsahlilrizki23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(41, 'SSA8865', '2301301085', 'SARIDA SOFIANI ARASIDAH', 'saridasofianiarasidah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(42, 'DHP5580', '2301301086', 'DINA HARIYANI PUTRI', 'dinahariyaniputri23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(43, 'MIR5847', '2301301089', 'M. INDRA RAHMAN', 'mindrarahman23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(44, 'ZNHF5620', '2301301090', 'ZIDAN NIBRAS HAFIZH FADHILAH', 'zidannibrashafizhfadhilah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(45, 'ASN3053', '2301301093', 'AIDA SEKAR NINGRUM', 'aidasekarningrum23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(46, 'R2542', '2301301095', 'RAHMAWATI', 'rahmawati231@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(47, 'MRW8379', '2301301101', 'M. RISZQI WARDANA', 'mriszqiwardana23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(48, 'KA6922', '2301301113', 'KHOTIMATUL ALIMAH', 'khotimatulalimah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(49, 'EA2068', '2301301119', 'EVY ARIANI', 'evyariani23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(50, 'SS2782', '2301301121', 'SIMA SABRINA', 'simasabrina23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(51, 'MS6892', '2301301124', 'MUHAMMAD SUPIANOOR', 'muhammadsupianoor23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(52, 'BW1159', '2301301125', 'BIMO WICAKSONO', 'bimowicaksono23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi informasi', 1, 1, NULL, '2025-12-15 05:37:00', '2025-12-15 05:37:00'),
(54, 'KOORD_KOORDINATOR_537', NULL, 'Koordinator', 'koordinator@politala.ac.id', NULL, NULL, '$2y$12$p2tTQ6gNN7dkI3Oq22kp5eWwJmF3Ejhm8iThk12okVrDcaRxWckSy', 'koordinator', NULL, 'Sistem Informasi', NULL, 1, NULL, '2025-12-16 23:55:05', '2025-12-16 23:55:05'),
(55, 'ADK4727', '2301301002', 'AHMAD DWI KURNIA', 'ahmaddwikurnia23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(56, 'AN4303', '2301301004', 'ALMA NABILLA', 'almanabilla23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(57, 'MKB8014', '2301301011', 'M.GALIH KATON BAGASKARA', 'mgalihkatonbagaskara23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(58, 'M7598', '2301301013', 'MAHYUDIANOOR', 'mahyudianoor23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(59, 'GNH5272', '2301301019', 'GHINA NUR HALIDAH', 'ghinanurhalidah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(60, 'ADA5284', '2301301030', 'ABEL DEVIA AGUSTIN', 'abeldeviaagustin23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(61, 'K6014', '2301301031', 'KHOLISYAH', 'kholisyah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(62, 'RKA3734', '2301301032', 'RICKY KESUMA ADISTANA', 'rickykesumaadistana23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(63, 'HBB1276', '2301301036', 'HARIEL BREMA BARUS', 'harielbremabarus23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(64, 'MR8874', '2301301038', 'MUHAMMAD RIZAL', 'muhammadrizal23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(65, 'ZAA4904', '2301301042', 'ZAINI AFLAHA AZIZAH', 'zainiaflahaazizah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(66, 'M7196', '2301301043', 'MAIMUNAH', 'maimunah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(67, 'STS1582', '2301301045', 'SINTIA TIARA SHIFA', 'sintiatiarashifa23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(68, 'IDAP5783', '2301301051', 'INTAN DEWI AYU PRAWESTI', 'intandewiayuprawesti23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(69, 'AN2979', '2301301057', 'AHMAD NAZRI', 'ahmadnazri23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(70, 'MF1674', '2301301066', 'MUHAMMAD FAZRIANNOR', 'muhammadfazriannor23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(71, 'NJ8073', '2301301069', 'NOR JANNAH', 'norjannah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(72, 'MRP9170', '2301301070', 'MUHAMMAD RIZKY PRATAMA', 'muhammadrizkypratama23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(73, 'AK1935', '2301301075', 'ANI KHAIRIYAH', 'anikhairiyah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(74, 'SF1884', '2301301081', 'SILVYA FEBIYANTI', 'silvyafebiyanti23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(75, 'YPK6969', '2301301088', 'YOGA PUTRA KARMANDA', 'yogaputrakarmanda23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(76, 'SN6061', '2301301092', 'SAYYIDAH NAFISAH', 'sayyidahnafisah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(77, 'MA3770', '2301301094', 'MUHAMMAD ADITYA', 'muhammadaditya23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(78, 'AR7076', '2301301097', 'ACHMAD RAFIQI', 'achmadrafiqi23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(79, 'AN2105', '2301301098', 'AKHMAD NUR', 'akhmadnur23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(80, 'ANH7715', '2301301102', 'ALYA NUR HAFIZAH', 'alyanurhafizah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(81, 'OES6219', '2301301117', 'OKY EGI SYAPUTRA', 'okyegisyaputra23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 4, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(82, 'NM3127', '2301301016', 'NURDIAH MUFARRIHAH', 'nurdiahmufarrihah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknik Informatika', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(83, 'MSH6234', '2301301020', 'MUHAMMAD SULAIMAN HAFI', 'muhammadsulaimanhafi23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknik Informatika', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(84, 'RO4602', '2301301021', 'RIEKE OKTAVIANNE', 'riekeoktavianne23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(85, 'S4753', '2301301022', 'SAUDAH', 'saudah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(86, 'ANF5166', '2301301023', 'AN NISA FITRI', 'annisafitri23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(87, 'NA3628', '2301301024', 'NURAISYAH ASTIWIDARI', 'nuraisyahastiwidari23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(88, 'AFA6870', '2301301029', 'AHMAD FAISAL ADITYA', 'ahmadfaisaladitya23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(89, 'AM3628', '2301301039', 'AZKIYA MUFIDA', 'azkiyamufida23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(90, 'NL1661', '2301301040', 'NURUL LATHIFAH', 'nurullathifah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(91, 'ALS2842', '2301301041', 'ANISA LILI SAFITRI', 'anisalilisafitri23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(92, 'MF3126', '2301301046', 'M. FAJRIANSYAH', 'mfajriansyah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(93, 'REP2600', '2301301048', 'RAHMAD ERWIN PRAYOGA', 'rahmaderwinprayoga23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(94, 'MAW3863', '2301301052', 'MITCHELL ARDIZA WIJAYANTO', 'mitchellardizawijayanto23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(95, 'MWR2744', '2301301061', 'M.RAYHAN WAHYU RIDUAN', 'mrayhanwahyuriduan23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(96, 'INPA1544', '2301301065', 'INTAN NIKITA PUTRI AZZAHRA', 'intannikitaputriazzahra23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(97, 'AHP5965', '2301301067', 'ALFITRIA HANIFA PRIYADI', 'alfitriahanifapriyadi23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(98, 'BT7287', '2301301071', 'BANGKIT TRIANDOYO', 'bangkittriandoyo23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(99, 'MMNR8587', '2301301072', 'M. MUJAYHID NOOR RIZQE', 'mmujayhidnoorrizqe23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(100, 'N7283', '2301301084', 'NADIA', 'nadia23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(101, 'Z1170', '2301301100', 'ZAINAL', 'zainal23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(102, 'AF4184', '2301301106', 'ABDI FAZAR', 'abdifazar23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(103, 'ADW4431', '2301301107', 'ANDRIAN DIVA WARDANA', 'andriandivawardana23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(104, 'K2820', '2301301108', 'KHAIRINA', 'khairina23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(105, 'MRN9283', '2301301110', 'MARDIANA RAFIQHI NUUR', 'mardianarafiqhinuur23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(106, 'MA3996', '2301301112', 'MUHAMMAD ARIFIN', 'muhammadarifin23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(107, 'CDJ4245', '2301301115', 'CAHYANI DWI JAYANTI', 'cahyanidwijayanti23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(108, 'TAA3732', '2301301116', 'TIARA AMALIA ANGGRAINI', 'tiaraamaliaanggraini23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 2, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(109, 'SS2066', '2301301003', 'SHERLY SANDRINA', 'sherlysandrina23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(110, 'MY3064', '2301301005', 'MAULIDA YANTI', 'maulidayanti23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(111, 'SAT8778', '2301301009', 'SALSABELLA AMANDA TAUPIK', 'salsabellaamandataupik23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(112, 'MT1560', '2301301012', 'MYRNA THIARA', 'myrnathiara23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(113, 'MHB5490', '2301301014', 'MUHAMMAD HAFIDL BADALI', 'muhammadhafidlbadali23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(114, 'NIQN6347', '2301301028', 'NUR INTAN QOLBI NURI', 'nurintanqolbinuri23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(115, 'IAS5262', '2301301053', 'INDAH ANNISA SUSANTI', 'indahannisasusanti23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(116, 'MFR8974', '2301301059', 'MUHAMMAD FAJAR RAFIQI', 'muhammadfajarrafiqi23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(117, 'ASAP4774', '2301301060', 'ANGGITA SILVIANA AWALIA PUTRI', 'anggitasilvianaawaliaputri23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(118, 'MR1975', '2301301068', 'MUHAMMAD RIZALDI', 'muhammadrizaldi23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(119, 'SR5771', '2301301077', 'SITI RUKMANA', 'sitirukmana23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(120, 'RAA3703', '2301301079', 'RAIHAN ADITYA AKBAR', 'raihanadityaakbar23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(121, 'SA5601', '2301301082', 'SITI AISYAH', 'sitiaisyah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(122, 'AA7533', '2301301083', 'AULIYA AZAH', 'auliyaazah23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(123, 'D2488', '2301301087', 'DICKY', 'dicky23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(124, 'IY3055', '2301301091', 'IMELDA YANI', 'imeldayani23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(125, 'A8559', '2301301096', 'ALFIN', 'alfin23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(126, 'GMT6309', '2301301099', 'GUSTI MUHAMMAD TAUFIKURRAHMAN', 'gustimuhammadtaufikurrahman23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(127, 'MA2550', '2301301103', 'MAULANA AKBAR', 'maulanaakbar23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(128, 'RDPI6218', '2301301105', 'RENO DWI PUTRA IHSAN', 'renodwiputraihsan23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(129, 'SA2546', '2301301111', 'SUCHI APRILIA', 'suchiaprilia23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(130, 'MZA4978', '2301301114', 'M. ZAINAL AKLI', 'mzainalakli23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(131, 'TAP9557', '2301301123', 'TIARA ANANDA PUTRI', 'tiaraanandaputri23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(132, 'EF3614', '2301301126', 'ERLIA FITRIANI', 'erliafitriani23@mhs.politala.ac.id', NULL, NULL, NULL, 'mahasiswa', NULL, 'Teknologi Informasi', 3, 1, NULL, '2025-12-17 05:35:35', '2025-12-17 05:35:35'),
(138, NULL, NULL, 'Billy Sabella, S.Kom., M.Kom.', 'billy.sabella@politala.ac.id', NULL, NULL, '$2y$12$5C8/Lx2KU.tvl.prlTFp4e2mUbtISz7v8OOY.w4xVmEcqRTcgxmJ.', 'dosen', NULL, NULL, NULL, 1, NULL, '2025-12-18 05:13:40', '2025-12-18 05:13:40'),
(139, NULL, NULL, 'Nindy Permatasari, S.Kom., M.Kom', 'nindy.permatasari@politala.ac.id', NULL, NULL, '$2y$12$gP15jAXXBeqmVpETOSITe.xPMdxEdKo7OS0OfPn5D8lgiv/iWpcJ2', 'dosen', NULL, NULL, NULL, 1, NULL, '2025-12-18 05:13:40', '2025-12-18 08:41:12'),
(140, NULL, NULL, 'Jaka Permadi, S.Si., M.Cs', 'jaka.permadi@politala.ac.id', NULL, NULL, '$2y$12$iq8zjcVpeqY3oRqpBr0h6.L4vu3UoUAIyurcGTgKjkXNj33ev2rqy', 'dosen', NULL, NULL, NULL, 1, NULL, '2025-12-18 05:13:40', '2025-12-18 05:13:40'),
(141, NULL, NULL, 'Ir. Agustian Noor, M.Kom', 'agustian.noor@politala.ac.id', NULL, NULL, '$2y$12$Ersv.XEcs0F2Z.f5v3.AYeyM1o7m78NCN6lJq6W6aHCIODbmFqimW', 'dosen', NULL, NULL, NULL, 1, NULL, '2025-12-18 05:13:41', '2025-12-18 05:13:41'),
(143, 'MHS_MUHAMMADEKA_4085', '2401301053', 'MUHAMMAD EKA YUSDA PUTRA', 'muhammad.eka@mhs.politala.ac.id', NULL, NULL, '$2y$12$odfOffyx6c8n3kj/TJKuJOEvPeUkMNl1uhGRSSaKthmK6D.6xHhPG', 'mahasiswa', NULL, 'Teknologi Informasi', 1, 1, 'jhX6PQPmrqcFW1YUXG6SSJMnOuPauApCN0qkWy9kZ8acvd7fHO4xM23bMfaJ', '2026-01-16 12:17:26', '2026-01-16 12:19:51');

-- --------------------------------------------------------

--
-- Table structure for table `periode_akademik`
--

CREATE TABLE `periode_akademik` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester_number` int NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `current_exam_period` enum('none','uts','uas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none' COMMENT 'Status periode ujian yang sedang aktif',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `periode_akademik`
--

INSERT INTO `periode_akademik` (`id`, `name`, `code`, `academic_year`, `semester_number`, `start_date`, `end_date`, `is_active`, `current_exam_period`, `description`, `created_at`, `updated_at`) VALUES
(1, 'TA 2025 - Semester 4', '2025-4', '2025', 4, '2025-12-15', '2026-03-15', 1, 'uts', NULL, '2025-12-15 05:11:04', '2025-12-18 09:10:47');

-- --------------------------------------------------------

--
-- Table structure for table `progress_reviews`
--

CREATE TABLE `progress_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `weekly_progress_id` bigint UNSIGNED NOT NULL,
  `reviewer_id` bigint UNSIGNED NOT NULL,
  `score_progress_speed` decimal(3,1) DEFAULT NULL,
  `score_quality` decimal(3,1) DEFAULT NULL,
  `score_timeliness` decimal(3,1) DEFAULT NULL,
  `score_collaboration` decimal(3,1) DEFAULT NULL,
  `total_score` decimal(4,1) DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `suggestions` text COLLATE utf8mb4_unicode_ci,
  `status` enum('approved','needs_revision','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proyek`
--

CREATE TABLE `proyek` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `dosen_id` bigint UNSIGNED DEFAULT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `max_members` int NOT NULL DEFAULT '5',
  `status` enum('draft','active','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `rubrik_penilaian` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ranking_mahasiswa`
--

CREATE TABLE `ranking_mahasiswa` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `periode_akademik_id` bigint UNSIGNED NOT NULL,
  `class_room_id` bigint UNSIGNED NOT NULL,
  `skor_total` decimal(8,4) NOT NULL DEFAULT '0.0000' COMMENT 'Skor total hasil perhitungan SAW',
  `peringkat` int DEFAULT NULL COMMENT 'Posisi peringkat dalam kelas',
  `detail_skor` json DEFAULT NULL COMMENT 'Breakdown skor per kriteria',
  `dihitung_pada` timestamp NOT NULL COMMENT 'Waktu terakhir ranking dihitung',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ruang_kelas`
--

CREATE TABLE `ruang_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_period_id` bigint UNSIGNED DEFAULT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Teknik Informatika',
  `max_groups` int NOT NULL DEFAULT '5',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruang_kelas`
--

INSERT INTO `ruang_kelas` (`id`, `name`, `code`, `academic_period_id`, `program_studi`, `max_groups`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'TI-4A', 'TI-4A', 1, 'Teknologi Informasi', 6, 1, '2025-12-15 05:22:53', '2025-12-15 07:00:28'),
(2, 'TI-4B', 'TI-4B', 1, 'Teknologi Informasi', 5, 1, '2025-12-17 05:32:45', '2025-12-17 05:32:45'),
(3, 'TI-4C', 'TI-4C', 1, 'Teknologi Informasi', 5, 1, '2025-12-17 05:33:07', '2025-12-17 05:33:07'),
(4, 'TI-4D', 'TI-4D', 1, 'Teknologi Informasi', 5, 1, '2025-12-17 05:35:01', '2025-12-17 05:35:01');

-- --------------------------------------------------------

--
-- Table structure for table `rubrik_item`
--

CREATE TABLE `rubrik_item` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `level` int NOT NULL DEFAULT '0' COMMENT 'Kedalaman level: 0=root, 1=sub-item',
  `rubrik_penilaian_id` bigint UNSIGNED NOT NULL,
  `rubrik_kategori_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `persentase` decimal(5,2) NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `urutan` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rubrik_item`
--

INSERT INTO `rubrik_item` (`id`, `parent_id`, `level`, `rubrik_penilaian_id`, `rubrik_kategori_id`, `nama`, `persentase`, `deskripsi`, `urutan`, `created_at`, `updated_at`) VALUES
(73, NULL, 0, 16, 1, 'Pemahaman Teori SPK', '40.00', 'Pemahaman konsep dasar Sistem Pendukung Keputusan', 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(74, NULL, 0, 16, 1, 'Penerapan Metode AHP', '60.00', 'Kemampuan menerapkan metode Analytical Hierarchy Process', 2, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(75, NULL, 0, 16, 2, 'Penerapan Metode SAW/TOPSIS', '40.00', 'Kemampuan menerapkan metode SAW atau TOPSIS', 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(76, NULL, 0, 16, 2, 'Implementasi Sistem', '30.00', 'Implementasi SPK dalam bentuk aplikasi', 2, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(77, NULL, 0, 16, 2, 'Analisis Hasil', '30.00', 'Kemampuan menganalisis dan mempresentasikan hasil SPK', 3, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(78, NULL, 0, 17, 3, 'Penguasaan Framework', '50.00', 'Pemahaman arsitektur dan fitur framework Laravel', 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(79, NULL, 0, 17, 3, 'Implementasi CRUD', '50.00', 'Kemampuan membuat fitur CRUD dengan Eloquent ORM', 2, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(80, NULL, 0, 17, 4, 'Fitur Lanjutan', '40.00', 'Implementasi authentication, authorization, dan API', 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(81, NULL, 0, 17, 4, 'Frontend Integration', '30.00', 'Integrasi dengan Vue.js atau React', 2, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(82, NULL, 0, 17, 4, 'Deployment & Testing', '30.00', 'Kualitas testing dan deployment aplikasi', 3, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(83, NULL, 0, 18, 5, 'Konsep Integrasi', '45.00', 'Pemahaman konsep API, SOA, dan Microservices', 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(84, NULL, 0, 18, 5, 'RESTful API Design', '55.00', 'Kemampuan merancang dan membuat RESTful API', 2, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(85, NULL, 0, 18, 6, 'Integrasi Multi-Sistem', '40.00', 'Implementasi integrasi antar sistem berbeda', 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(86, NULL, 0, 18, 6, 'Keamanan API', '30.00', 'Implementasi OAuth, JWT, dan API security', 2, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(87, NULL, 0, 18, 6, 'Dokumentasi & Testing', '30.00', 'Dokumentasi API (Swagger) dan pengujian integrasi', 3, '2025-12-18 05:06:39', '2025-12-18 05:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `rubrik_kategori`
--

CREATE TABLE `rubrik_kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `rubrik_penilaian_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` decimal(5,2) NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rubrik_kategori`
--

INSERT INTO `rubrik_kategori` (`id`, `rubrik_penilaian_id`, `nama`, `bobot`, `urutan`, `deskripsi`, `kode`, `created_at`, `updated_at`) VALUES
(1, 16, 'UTS', '40.00', 1, 'Ujian Tengah Semester (Minggu 1-8)', 'uts', '2026-01-16 09:24:43', '2026-01-16 09:24:43'),
(2, 16, 'UAS', '60.00', 2, 'Ujian Akhir Semester (Minggu 9-16)', 'uas', '2026-01-16 09:24:43', '2026-01-16 09:24:43'),
(3, 17, 'UTS', '40.00', 1, 'Ujian Tengah Semester (Minggu 1-8)', 'uts', '2026-01-16 09:24:43', '2026-01-16 09:24:43'),
(4, 17, 'UAS', '60.00', 2, 'Ujian Akhir Semester (Minggu 9-16)', 'uas', '2026-01-16 09:24:43', '2026-01-16 09:24:43'),
(5, 18, 'UTS', '40.00', 1, 'Ujian Tengah Semester (Minggu 1-8)', 'uts', '2026-01-16 09:24:43', '2026-01-16 09:24:43'),
(6, 18, 'UAS', '60.00', 2, 'Ujian Akhir Semester (Minggu 9-16)', 'uas', '2026-01-16 09:24:43', '2026-01-16 09:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `rubrik_penilaian`
--

CREATE TABLE `rubrik_penilaian` (
  `id` bigint UNSIGNED NOT NULL,
  `mata_kuliah_id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `periode_akademik_id` bigint UNSIGNED NOT NULL,
  `semester` int NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rubrik_penilaian`
--

INSERT INTO `rubrik_penilaian` (`id`, `mata_kuliah_id`, `nama`, `deskripsi`, `periode_akademik_id`, `semester`, `created_by`, `is_active`, `created_at`, `updated_at`) VALUES
(16, 2, 'Rubrik Teknik Pengambilan Keputusan', 'Rubrik penilaian untuk mata kuliah Teknik Pengambilan Keputusan', 1, 4, 24, 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(17, 3, 'Rubrik Pemrograman Web Lanjut', 'Rubrik penilaian untuk mata kuliah Pemrograman Web Lanjut', 1, 4, 24, 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39'),
(18, 4, 'Rubrik Integrasi Sistem', 'Rubrik penilaian untuk mata kuliah Integrasi Sistem', 1, 4, 24, 1, '2025-12-18 05:06:39', '2025-12-18 05:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `number` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('LLYZgdRsIPzBT6N1q9aefv5fN16ZePhSxZoY5oBb', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiY1JTZ05WSjdsYjlURHpQZnBQbEN6MFA3TmZiVUNNWGZTOXYxWXczWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyNDt9', 1768577939),
('MG3ce9rQvUldy3fDuVK58LcSOZkOCa5637FyzpCM', 143, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSEIxN25TWUZMNGNmY0xqbG1FeGp0eDFvN0hMTzdJSWJzakQzWUVsMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYWhhc2lzd2EvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE5OiJtYWhhc2lzd2EuZGFzaGJvYXJkIjt9czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE0Mzt9', 1768576955),
('MnbHwiKgVKVj2Ht3AFqEeNllgTFwSrzl0sVCGLbm', 23, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZFFUREI5bVBkWXBVMVJPNDYybUdxMTRoRUh6RmgzVHM1VnlyYkk1VSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb3Nlbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImRvc2VuLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMzt9', 1768576952),
('twRHivnGyJ8S0lsx3fLynWfjI04h6DhnsE5ZZiNy', 54, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNUNXV3BZOVMwWlRhOHJ0aFVLcTBjS0FnV09EaXY2bnhqTFdwYVY3cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90YXJnZXRzP2xvY2tfc3RhdHVzPXRlcmt1bmNpIjtzOjU6InJvdXRlIjtzOjEzOiJ0YXJnZXRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU0O30=', 1768574132);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `description`, `created_at`, `updated_at`) VALUES
(1, 'google_drive_folder_id', '17CxeM9vNWDLuxEZyg3k7qDJu-nJRVVxR', 'string', 'google_drive', 'ID Folder Utama', '2025-12-15 04:39:55', '2026-01-06 03:04:41'),
(2, 'google_drive_enabled', 'true', 'boolean', 'google_drive', 'Google Drive Aktif', '2025-12-15 04:39:55', '2025-12-15 06:40:29'),
(6, 'google_drive_auth_type', 'oauth', 'string', 'google_drive', 'Tipe Autentikasi', '2025-12-15 06:40:29', '2026-01-06 03:04:41'),
(13, 'email_smtp_enabled', 'true', 'boolean', 'email', 'Aktifkan SMTP', '2026-01-06 03:00:53', '2026-01-06 03:00:53'),
(14, 'email_smtp_host', 'smtp.gmail.com', 'string', 'email', 'SMTP Host', '2026-01-06 03:00:53', '2026-01-06 03:00:53'),
(15, 'email_smtp_port', '587', 'string', 'email', 'SMTP Port', '2026-01-06 03:00:53', '2026-01-06 03:00:53'),
(16, 'email_smtp_encryption', 'tls', 'string', 'email', 'Enkripsi', '2026-01-06 03:00:53', '2026-01-06 03:00:53'),
(17, 'email_smtp_username', 'udinbecco123@gmail.com', 'string', 'email', 'Akun Gmail', '2026-01-06 03:00:53', '2026-01-16 12:15:50'),
(18, 'email_from_address', 'udinbecco123@gmail.com', 'string', 'email', 'Email From', '2026-01-06 03:00:53', '2026-01-16 12:15:50'),
(19, 'email_from_name', 'Sistem Informasi PBL', 'string', 'email', 'Nama Pengirim', '2026-01-06 03:00:53', '2026-01-06 03:00:53'),
(20, 'email_smtp_password', 'eyJpdiI6IittWk1PUnRTMktXNnIxb3VxOE9JaXc9PSIsInZhbHVlIjoibTlkeUVSZjRwYjBOazEzOVRkZmZ4SFhWSE9OdzJCelQ4WG82YndEc1BxWT0iLCJtYWMiOiJlZjNlMGYxNDYzNjBhZDhhYzdhOGFjNTJmYmEwNGQyNTYxYzM5MmNkYzk0ZTRjMmRiOGNlMmU0NGI5NTAyZTYwIiwidGFnIjoiIn0=', 'encrypted', 'email', 'Password SMTP', '2026-01-06 03:00:53', '2026-01-16 12:15:50'),
(21, 'google_drive_oauth_token', '{\"access_token\":\"ya29.a0AUMWg_KXf6M893p6v0_sEFnuy7z8oHFgOoTtmMx57t1aeJ7KLW4eb2xrEpZ_rmKXzwAuWCujrbz8Q6FPSRAIMtgebEPampfnDN-lG9pSDOFEC8MFvdivmaslVbHnBpti-CNGpjVzoaCTNWJZpncWeH38FY-e9E-Gq64HLZajMZrck1S6m_NN1s1-FfTfFPcJSDk6vC_haCgYKAfUSARYSFQHGX2Mi7H1nkll89ecB4gxVF4jUdw0207\",\"expires_in\":3599,\"scope\":\"https:\\/\\/www.googleapis.com\\/auth\\/userinfo.profile openid https:\\/\\/www.googleapis.com\\/auth\\/drive.file https:\\/\\/www.googleapis.com\\/auth\\/userinfo.email\",\"token_type\":\"Bearer\",\"id_token\":\"eyJhbGciOiJSUzI1NiIsImtpZCI6IjdiZjU5NTQ4OWEwYmIxNThiMDg1ZTIzZTdiNTJiZjk4OTFlMDQ1MzgiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIyMTAxMjk4OTgwNTctamhrM25sc2dpOGI0NnVvYTNyN25jNGUzbzM5NHNtOTkuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiIyMTAxMjk4OTgwNTctamhrM25sc2dpOGI0NnVvYTNyN25jNGUzbzM5NHNtOTkuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDA5Mjc0Mzg2MjU0NTA1MTk1OTkiLCJlbWFpbCI6InVkaW5iZWNjbzEyM0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXRfaGFzaCI6IkQtME85cVZwWFpObC1RQVcteVMxTVEiLCJuYW1lIjoiRWthIFB1dHJhIiwicGljdHVyZSI6Imh0dHBzOi8vbGgzLmdvb2dsZXVzZXJjb250ZW50LmNvbS9hL0FDZzhvY0lPSDBRQkdEU0FuLVFWdUlZQjc3ZzliWUU5c3U5VXJPNHRwSGM4eUhyb1ZJNGh4dkhIPXM5Ni1jIiwiZ2l2ZW5fbmFtZSI6IkVrYSIsImZhbWlseV9uYW1lIjoiUHV0cmEiLCJpYXQiOjE3Njg1NTgwMTksImV4cCI6MTc2ODU2MTYxOX0.nXHZy16l1w5mhsBqBxp5ZONTQ8ewCNnANcr66LQKfrSXY_HSJ4NUeXdZtBbh2ktDdnoQ7bIF4EIl8Px1r1Pz_h3ukNwSrbWG7a5cGQO24ElZnILgvCUuLDfgNBXS7L-EvBCtTbd-qGL7W0KqdRM25-B6BFT_pTYHVBHuevzuODigBH2iDTJ69CMLyIZMDkyW5D7-Y4j2Ybk_pEl7bMkw8ZU6edSrb4afj-gI4X2MqEcm3ppvvvmNS2GW47-CcNFczxNPqx9BBaiEm_0zl0doBEMlSJ_01VSzGumSf3HZKHBmSpfmIRwWEr0wL_AznKO6Zfe6mNGPbjXjViKiNxTeOA\",\"created\":1768558025,\"refresh_token\":\"1\\/\\/0gTOZ9SoECAsKCgYIARAAGBASNwF-L9IrOQvmvClpCTxGDKundSIk_pQYnHx1zzV0B-BKXEKJ3ZGvC2AcTLSjRcDpBStVYXWMg_E\"}', 'json', 'google_drive', NULL, '2026-01-06 03:04:40', '2026-01-16 10:07:05'),
(22, 'google_drive_oauth_email', 'udinbecco123@gmail.com', 'string', 'google_drive', 'Email Akun Google Terhubung', '2026-01-06 03:04:40', '2026-01-06 03:04:40'),
(23, 'google_drive_oauth_name', 'Eka Putra', 'string', 'google_drive', 'Nama Akun Google Terhubung', '2026-01-06 03:04:41', '2026-01-06 03:04:41');

-- --------------------------------------------------------

--
-- Table structure for table `sync_kriteria_logs`
--

CREATE TABLE `sync_kriteria_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `class_room_id` bigint UNSIGNED DEFAULT NULL,
  `synced_by` bigint UNSIGNED NOT NULL,
  `criteria_synced` json NOT NULL,
  `previous_values` json DEFAULT NULL,
  `new_values` json NOT NULL,
  `synced_at` timestamp NOT NULL,
  `is_reverted` tinyint(1) NOT NULL DEFAULT '0',
  `reverted_at` timestamp NULL DEFAULT NULL,
  `reverted_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sync_kriteria_logs`
--

INSERT INTO `sync_kriteria_logs` (`id`, `class_room_id`, `synced_by`, `criteria_synced`, `previous_values`, `new_values`, `synced_at`, `is_reverted`, `reverted_at`, `reverted_by`, `created_at`, `updated_at`) VALUES
(3, NULL, 24, '[\"1\", \"2\", \"3\"]', '{\"1\": {\"1\": null, \"2\": null, \"3\": null}, \"2\": {\"1\": null, \"2\": null, \"3\": null}, \"3\": {\"1\": null, \"2\": null, \"3\": null}, \"4\": {\"1\": null, \"2\": null, \"3\": null}, \"5\": {\"1\": null, \"2\": null, \"3\": null}, \"6\": {\"1\": null, \"2\": null, \"3\": null}, \"7\": {\"1\": null, \"2\": null, \"3\": null}, \"8\": {\"1\": null, \"2\": null, \"3\": null}, \"9\": {\"1\": null, \"2\": null, \"3\": null}, \"10\": {\"1\": null, \"2\": null, \"3\": null}, \"11\": {\"1\": null, \"2\": null, \"3\": null}, \"12\": {\"1\": null, \"2\": null, \"3\": null}, \"13\": {\"1\": null, \"2\": null, \"3\": null}, \"14\": {\"1\": null, \"2\": null, \"3\": null}, \"15\": {\"1\": null, \"2\": null, \"3\": null}, \"16\": {\"1\": null, \"2\": null, \"3\": null}, \"17\": {\"1\": null, \"2\": null, \"3\": null}, \"18\": {\"1\": null, \"2\": null, \"3\": null}, \"19\": {\"1\": null, \"2\": null, \"3\": null}, \"20\": {\"1\": null, \"2\": null, \"3\": null}, \"21\": {\"1\": null, \"2\": null, \"3\": null}, \"22\": {\"1\": null, \"2\": null, \"3\": null}, \"23\": {\"1\": null, \"2\": null, \"3\": null}}', '{\"1\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"2\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"3\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"4\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"5\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"6\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"7\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"8\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"9\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"10\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"11\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"12\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"13\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"14\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"15\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"16\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"17\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"18\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"19\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"20\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"21\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"22\": {\"1\": 0, \"2\": 0, \"3\": 0}, \"23\": {\"1\": 0, \"2\": 0, \"3\": 0}}', '2025-12-18 09:29:51', 0, NULL, NULL, '2025-12-18 09:29:51', '2025-12-18 09:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `target_mingguan`
--

CREATE TABLE `target_mingguan` (
  `id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `week_number` int NOT NULL,
  `deadline` timestamp NULL DEFAULT NULL COMMENT 'Batas waktu submit target',
  `last_reminder_sent_at` timestamp NULL DEFAULT NULL,
  `deadline_reminder_sent` tinyint(1) NOT NULL DEFAULT '0',
  `grace_period_minutes` int UNSIGNED NOT NULL DEFAULT '0',
  `auto_close` tinyint(1) NOT NULL DEFAULT '1',
  `auto_closed_at` timestamp NULL DEFAULT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Apakah target masih bisa disubmit (false jika sudah lewat deadline atau ditutup manual)',
  `reopened_by` bigint UNSIGNED DEFAULT NULL,
  `reopened_at` timestamp NULL DEFAULT NULL COMMENT 'Waktu target dibuka kembali oleh dosen',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `submission_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan dari mahasiswa saat submit',
  `evidence_files` json DEFAULT NULL,
  `is_checked_only` tinyint(1) NOT NULL DEFAULT '0',
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `submission_status` enum('pending','submitted','late','approved','revision') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Status submission: pending, submitted, late, approved, revision',
  `is_reviewed` tinyint(1) NOT NULL DEFAULT '0',
  `quality_score` decimal(5,2) DEFAULT NULL,
  `final_score` decimal(5,2) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewer_id` bigint UNSIGNED DEFAULT NULL,
  `evidence_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `target_todo_items`
--

CREATE TABLE `target_todo_items` (
  `id` bigint UNSIGNED NOT NULL,
  `target_mingguan_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order` int NOT NULL DEFAULT '0',
  `is_completed_by_student` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `is_verified_by_reviewer` tinyint(1) NOT NULL DEFAULT '0',
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ulasan_target_mingguan`
--

CREATE TABLE `ulasan_target_mingguan` (
  `id` bigint UNSIGNED NOT NULL,
  `weekly_target_id` bigint UNSIGNED NOT NULL,
  `reviewer_id` bigint UNSIGNED NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `suggestions` text COLLATE utf8mb4_unicode_ci,
  `status` enum('approved','needs_revision','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_terms`
--
ALTER TABLE `academic_terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `academic_years_code_unique` (`code`);

--
-- Indexes for table `ahp_comparisons`
--
ALTER TABLE `ahp_comparisons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ahp_unique_comparison` (`segment`,`criterion_a_id`,`criterion_b_id`),
  ADD KEY `ahp_comparisons_criterion_a_id_foreign` (`criterion_a_id`),
  ADD KEY `ahp_comparisons_criterion_b_id_foreign` (`criterion_b_id`);

--
-- Indexes for table `anggota_kelompok`
--
ALTER TABLE `anggota_kelompok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anggota_kelompok_group_id_user_id_unique` (`group_id`,`user_id`),
  ADD KEY `anggota_kelompok_user_id_foreign` (`user_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendances_project_id_user_id_date_unique` (`project_id`,`user_id`,`date`),
  ADD KEY `attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_mata_kuliah_dosen_id_mata_kuliah_id_unique` (`dosen_id`,`mata_kuliah_id`),
  ADD UNIQUE KEY `dosen_matkul_periode_unique` (`dosen_id`,`mata_kuliah_id`,`periode`),
  ADD KEY `dosen_mata_kuliah_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `dosen_mata_kuliah_academic_period_id_foreign` (`academic_period_id`);

--
-- Indexes for table `dosen_pbl_kelas`
--
ALTER TABLE `dosen_pbl_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dosen_pbl_kelas_unique` (`dosen_id`,`class_room_id`),
  ADD UNIQUE KEY `unique_dosen_class` (`dosen_id`,`class_room_id`),
  ADD KEY `dosen_pbl_kelas_class_room_id_foreign` (`class_room_id`),
  ADD KEY `dosen_pbl_kelas_academic_period_id_foreign` (`academic_period_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas_mata_kuliah`
--
ALTER TABLE `kelas_mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_mata_kuliah_class_room_id_mata_kuliah_id_unique` (`class_room_id`,`mata_kuliah_id`),
  ADD KEY `kelas_mata_kuliah_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `kelas_mata_kuliah_rubrik_penilaian_id_foreign` (`rubrik_penilaian_id`),
  ADD KEY `kelas_mata_kuliah_dosen_sebelum_uts_id_foreign` (`dosen_sebelum_uts_id`),
  ADD KEY `kelas_mata_kuliah_dosen_sesudah_uts_id_foreign` (`dosen_sesudah_uts_id`);

--
-- Indexes for table `kelompok`
--
ALTER TABLE `kelompok`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelompok_project_id_foreign` (`project_id`),
  ADD KEY `kelompok_leader_id_foreign` (`leader_id`),
  ADD KEY `kelompok_class_room_id_foreign` (`class_room_id`);

--
-- Indexes for table `kemajuan_mingguan`
--
ALTER TABLE `kemajuan_mingguan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kemajuan_mingguan_group_id_week_number_unique` (`group_id`,`week_number`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mata_kuliah_kode_unique` (`kode`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai_kelompok`
--
ALTER TABLE `nilai_kelompok`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nilai_kelompok_group_id_criterion_id_unique` (`group_id`,`criterion_id`),
  ADD KEY `nilai_kelompok_criterion_id_foreign` (`criterion_id`);

--
-- Indexes for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nilai_mahasiswa_user_id_criterion_id_unique` (`user_id`,`criterion_id`),
  ADD KEY `nilai_mahasiswa_criterion_id_foreign` (`criterion_id`);

--
-- Indexes for table `nilai_rubrik`
--
ALTER TABLE `nilai_rubrik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nilai_rubrik_unique` (`user_id`,`kelas_mata_kuliah_id`,`rubrik_item_id`),
  ADD KEY `nilai_rubrik_kelas_mata_kuliah_id_foreign` (`kelas_mata_kuliah_id`),
  ADD KEY `nilai_rubrik_rubrik_item_id_foreign` (`rubrik_item_id`),
  ADD KEY `nilai_rubrik_dinilai_oleh_foreign` (`dinilai_oleh`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_otp`
--
ALTER TABLE `password_reset_otp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_reset_otp_email_index` (`email`);

--
-- Indexes for table `password_reset_otps`
--
ALTER TABLE `password_reset_otps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_reset_otps_email_index` (`email`),
  ADD KEY `password_reset_otps_otp_code_index` (`otp_code`),
  ADD KEY `password_reset_otps_expires_at_index` (`expires_at`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengguna_email_unique` (`email`),
  ADD UNIQUE KEY `pengguna_politala_id_unique` (`politala_id`),
  ADD UNIQUE KEY `pengguna_nim_unique` (`nim`),
  ADD KEY `pengguna_class_room_id_foreign` (`class_room_id`);

--
-- Indexes for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `periode_akademik_academic_year_semester_number_unique` (`academic_year`,`semester_number`),
  ADD UNIQUE KEY `periode_akademik_code_unique` (`code`);

--
-- Indexes for table `progress_reviews`
--
ALTER TABLE `progress_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progress_reviews_weekly_progress_id_foreign` (`weekly_progress_id`),
  ADD KEY `progress_reviews_reviewer_id_foreign` (`reviewer_id`);

--
-- Indexes for table `proyek`
--
ALTER TABLE `proyek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proyek_dosen_id_foreign` (`dosen_id`);

--
-- Indexes for table `ranking_mahasiswa`
--
ALTER TABLE `ranking_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ranking_mahasiswa_unik` (`user_id`,`periode_akademik_id`,`class_room_id`),
  ADD KEY `ranking_mahasiswa_periode_akademik_id_foreign` (`periode_akademik_id`),
  ADD KEY `ranking_mahasiswa_class_room_id_foreign` (`class_room_id`);

--
-- Indexes for table `ruang_kelas`
--
ALTER TABLE `ruang_kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruang_kelas_code_unique` (`code`),
  ADD KEY `ruang_kelas_academic_period_id_foreign` (`academic_period_id`);

--
-- Indexes for table `rubrik_item`
--
ALTER TABLE `rubrik_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubrik_item_rubrik_penilaian_id_foreign` (`rubrik_penilaian_id`),
  ADD KEY `rubrik_item_parent_id_foreign` (`parent_id`),
  ADD KEY `rubrik_item_rubrik_kategori_id_foreign` (`rubrik_kategori_id`);

--
-- Indexes for table `rubrik_kategori`
--
ALTER TABLE `rubrik_kategori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubrik_kategori_rubrik_penilaian_id_foreign` (`rubrik_penilaian_id`);

--
-- Indexes for table `rubrik_penilaian`
--
ALTER TABLE `rubrik_penilaian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubrik_penilaian_mata_kuliah_id_foreign` (`mata_kuliah_id`),
  ADD KEY `rubrik_penilaian_periode_akademik_id_foreign` (`periode_akademik_id`),
  ADD KEY `rubrik_penilaian_created_by_foreign` (`created_by`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semesters_academic_year_id_number_unique` (`academic_year_id`,`number`),
  ADD UNIQUE KEY `semesters_code_unique` (`code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `sync_kriteria_logs`
--
ALTER TABLE `sync_kriteria_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sync_kriteria_logs_synced_by_foreign` (`synced_by`),
  ADD KEY `sync_kriteria_logs_reverted_by_foreign` (`reverted_by`),
  ADD KEY `sync_kriteria_logs_class_room_id_synced_at_index` (`class_room_id`,`synced_at`);

--
-- Indexes for table `target_mingguan`
--
ALTER TABLE `target_mingguan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_mingguan_group_id_foreign` (`group_id`),
  ADD KEY `target_mingguan_completed_by_foreign` (`completed_by`),
  ADD KEY `target_mingguan_reviewer_id_foreign` (`reviewer_id`),
  ADD KEY `target_mingguan_created_by_foreign` (`created_by`),
  ADD KEY `target_mingguan_reopened_by_foreign` (`reopened_by`);

--
-- Indexes for table `target_todo_items`
--
ALTER TABLE `target_todo_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_todo_items_verified_by_foreign` (`verified_by`),
  ADD KEY `target_todo_items_target_mingguan_id_order_index` (`target_mingguan_id`,`order`);

--
-- Indexes for table `ulasan_target_mingguan`
--
ALTER TABLE `ulasan_target_mingguan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ulasan_target_mingguan_weekly_target_id_unique` (`weekly_target_id`),
  ADD KEY `ulasan_target_mingguan_reviewer_id_foreign` (`reviewer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_terms`
--
ALTER TABLE `academic_terms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ahp_comparisons`
--
ALTER TABLE `ahp_comparisons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `anggota_kelompok`
--
ALTER TABLE `anggota_kelompok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dosen_pbl_kelas`
--
ALTER TABLE `dosen_pbl_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas_mata_kuliah`
--
ALTER TABLE `kelas_mata_kuliah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `kelompok`
--
ALTER TABLE `kelompok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `kemajuan_mingguan`
--
ALTER TABLE `kemajuan_mingguan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `nilai_kelompok`
--
ALTER TABLE `nilai_kelompok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `nilai_rubrik`
--
ALTER TABLE `nilai_rubrik`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_otp`
--
ALTER TABLE `password_reset_otp`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_reset_otps`
--
ALTER TABLE `password_reset_otps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `progress_reviews`
--
ALTER TABLE `progress_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proyek`
--
ALTER TABLE `proyek`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ranking_mahasiswa`
--
ALTER TABLE `ranking_mahasiswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ruang_kelas`
--
ALTER TABLE `ruang_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rubrik_item`
--
ALTER TABLE `rubrik_item`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `rubrik_kategori`
--
ALTER TABLE `rubrik_kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rubrik_penilaian`
--
ALTER TABLE `rubrik_penilaian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `sync_kriteria_logs`
--
ALTER TABLE `sync_kriteria_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `target_mingguan`
--
ALTER TABLE `target_mingguan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `target_todo_items`
--
ALTER TABLE `target_todo_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `ulasan_target_mingguan`
--
ALTER TABLE `ulasan_target_mingguan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ahp_comparisons`
--
ALTER TABLE `ahp_comparisons`
  ADD CONSTRAINT `ahp_comparisons_criterion_a_id_foreign` FOREIGN KEY (`criterion_a_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ahp_comparisons_criterion_b_id_foreign` FOREIGN KEY (`criterion_b_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `anggota_kelompok`
--
ALTER TABLE `anggota_kelompok`
  ADD CONSTRAINT `anggota_kelompok_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anggota_kelompok_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `proyek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_mata_kuliah`
--
ALTER TABLE `dosen_mata_kuliah`
  ADD CONSTRAINT `dosen_mata_kuliah_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `periode_akademik` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dosen_mata_kuliah_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_mata_kuliah_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dosen_pbl_kelas`
--
ALTER TABLE `dosen_pbl_kelas`
  ADD CONSTRAINT `dosen_pbl_kelas_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `periode_akademik` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dosen_pbl_kelas_class_room_id_foreign` FOREIGN KEY (`class_room_id`) REFERENCES `ruang_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dosen_pbl_kelas_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_mata_kuliah`
--
ALTER TABLE `kelas_mata_kuliah`
  ADD CONSTRAINT `kelas_mata_kuliah_class_room_id_foreign` FOREIGN KEY (`class_room_id`) REFERENCES `ruang_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_kuliah_dosen_sebelum_uts_id_foreign` FOREIGN KEY (`dosen_sebelum_uts_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_mata_kuliah_dosen_sesudah_uts_id_foreign` FOREIGN KEY (`dosen_sesudah_uts_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kelas_mata_kuliah_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_mata_kuliah_rubrik_penilaian_id_foreign` FOREIGN KEY (`rubrik_penilaian_id`) REFERENCES `rubrik_penilaian` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kelompok`
--
ALTER TABLE `kelompok`
  ADD CONSTRAINT `kelompok_class_room_id_foreign` FOREIGN KEY (`class_room_id`) REFERENCES `ruang_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelompok_leader_id_foreign` FOREIGN KEY (`leader_id`) REFERENCES `pengguna` (`id`),
  ADD CONSTRAINT `kelompok_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `proyek` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kemajuan_mingguan`
--
ALTER TABLE `kemajuan_mingguan`
  ADD CONSTRAINT `kemajuan_mingguan_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_kelompok`
--
ALTER TABLE `nilai_kelompok`
  ADD CONSTRAINT `nilai_kelompok_criterion_id_foreign` FOREIGN KEY (`criterion_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_kelompok_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  ADD CONSTRAINT `nilai_mahasiswa_criterion_id_foreign` FOREIGN KEY (`criterion_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_mahasiswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_rubrik`
--
ALTER TABLE `nilai_rubrik`
  ADD CONSTRAINT `nilai_rubrik_dinilai_oleh_foreign` FOREIGN KEY (`dinilai_oleh`) REFERENCES `pengguna` (`id`),
  ADD CONSTRAINT `nilai_rubrik_kelas_mata_kuliah_id_foreign` FOREIGN KEY (`kelas_mata_kuliah_id`) REFERENCES `kelas_mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_rubrik_rubrik_item_id_foreign` FOREIGN KEY (`rubrik_item_id`) REFERENCES `rubrik_item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_rubrik_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_class_room_id_foreign` FOREIGN KEY (`class_room_id`) REFERENCES `ruang_kelas` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `progress_reviews`
--
ALTER TABLE `progress_reviews`
  ADD CONSTRAINT `progress_reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `pengguna` (`id`),
  ADD CONSTRAINT `progress_reviews_weekly_progress_id_foreign` FOREIGN KEY (`weekly_progress_id`) REFERENCES `kemajuan_mingguan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proyek`
--
ALTER TABLE `proyek`
  ADD CONSTRAINT `proyek_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `pengguna` (`id`);

--
-- Constraints for table `ranking_mahasiswa`
--
ALTER TABLE `ranking_mahasiswa`
  ADD CONSTRAINT `ranking_mahasiswa_class_room_id_foreign` FOREIGN KEY (`class_room_id`) REFERENCES `ruang_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ranking_mahasiswa_periode_akademik_id_foreign` FOREIGN KEY (`periode_akademik_id`) REFERENCES `periode_akademik` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ranking_mahasiswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ruang_kelas`
--
ALTER TABLE `ruang_kelas`
  ADD CONSTRAINT `ruang_kelas_academic_period_id_foreign` FOREIGN KEY (`academic_period_id`) REFERENCES `periode_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rubrik_item`
--
ALTER TABLE `rubrik_item`
  ADD CONSTRAINT `rubrik_item_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `rubrik_item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rubrik_item_rubrik_kategori_id_foreign` FOREIGN KEY (`rubrik_kategori_id`) REFERENCES `rubrik_kategori` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rubrik_item_rubrik_penilaian_id_foreign` FOREIGN KEY (`rubrik_penilaian_id`) REFERENCES `rubrik_penilaian` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rubrik_kategori`
--
ALTER TABLE `rubrik_kategori`
  ADD CONSTRAINT `rubrik_kategori_rubrik_penilaian_id_foreign` FOREIGN KEY (`rubrik_penilaian_id`) REFERENCES `rubrik_penilaian` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rubrik_penilaian`
--
ALTER TABLE `rubrik_penilaian`
  ADD CONSTRAINT `rubrik_penilaian_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rubrik_penilaian_mata_kuliah_id_foreign` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rubrik_penilaian_periode_akademik_id_foreign` FOREIGN KEY (`periode_akademik_id`) REFERENCES `periode_akademik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `semesters_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sync_kriteria_logs`
--
ALTER TABLE `sync_kriteria_logs`
  ADD CONSTRAINT `sync_kriteria_logs_class_room_id_foreign` FOREIGN KEY (`class_room_id`) REFERENCES `ruang_kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sync_kriteria_logs_reverted_by_foreign` FOREIGN KEY (`reverted_by`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sync_kriteria_logs_synced_by_foreign` FOREIGN KEY (`synced_by`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `target_mingguan`
--
ALTER TABLE `target_mingguan`
  ADD CONSTRAINT `target_mingguan_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `pengguna` (`id`),
  ADD CONSTRAINT `target_mingguan_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `target_mingguan_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `kelompok` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `target_mingguan_reopened_by_foreign` FOREIGN KEY (`reopened_by`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `target_mingguan_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `target_todo_items`
--
ALTER TABLE `target_todo_items`
  ADD CONSTRAINT `target_todo_items_target_mingguan_id_foreign` FOREIGN KEY (`target_mingguan_id`) REFERENCES `target_mingguan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `target_todo_items_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ulasan_target_mingguan`
--
ALTER TABLE `ulasan_target_mingguan`
  ADD CONSTRAINT `ulasan_target_mingguan_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ulasan_target_mingguan_weekly_target_id_foreign` FOREIGN KEY (`weekly_target_id`) REFERENCES `target_mingguan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
