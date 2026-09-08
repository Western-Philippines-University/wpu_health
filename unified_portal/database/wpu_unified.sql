-- phpMyAdmin SQL Dump
-- Unified WPU Medical System Database
-- Merges wpu, wpu_dental, and wpu_clinic into single database
-- Version: 3.0 - Unified Module
-- Generated: 2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wpu`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
-- Merged from all three databases
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Insert merged admin data (deduplicated)
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$YMfSNHk2hjH9Cr4tg0UYu..ShqiyzMBajbirin6.Mw61RaSTz0vqi', '2025-10-21 04:00:43'),
(2, 'joms_27', '$2y$10$.vB37GnsoYBoaTodJbzZueDy1MUkHnvfUhwuoiIYuBgPdj7M.avyi', '2025-10-20 13:53:43');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_codes`
-- From wpu database
--

CREATE TABLE IF NOT EXISTS `certificate_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `certificate_code` varchar(255) NOT NULL DEFAULT 'WPU-QSF-GASS-HSO-01 Rev.00 (09.20.24)',
  `referral_code` varchar(255) NOT NULL DEFAULT 'WPU-QSF-GASS-HSO-12 Rev.00 (09.20.24)',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `certificate_codes` (`id`, `certificate_code`, `referral_code`, `updated_at`) VALUES
(1, 'WPU-QSF-GASS-HSO-01 Rev.00 (09.20.24)', 'WPU-QSF-GASS-HSO-12 Rev.00 (09.20.25)', '2025-10-17 10:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `md_off`
-- From wpu database
--

CREATE TABLE IF NOT EXISTS `md_off` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_certificates`
-- From wpu database
--

CREATE TABLE IF NOT EXISTS `medical_certificates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `civil_status` enum('Single','Married','Divorced','Widowed') NOT NULL,
  `address` text NOT NULL,
  `examination_date` date NOT NULL,
  `reason` text NOT NULL,
  `findings_fit` tinyint(1) DEFAULT 0,
  `findings_impression` tinyint(1) DEFAULT 0,
  `impression_text` text DEFAULT NULL,
  `advice` text DEFAULT NULL,
  `receipt_no` varchar(100) DEFAULT NULL,
  `date_issued` date DEFAULT NULL,
  `mc_no` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `findings` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
-- From wpu database
--

CREATE TABLE IF NOT EXISTS `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hospital_clinic` varchar(255) NOT NULL,
  `referral_date` date NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `patient_age` int(11) NOT NULL,
  `patient_sex` enum('MALE','FEMALE') NOT NULL,
  `patient_type_occupation` tinyint(1) DEFAULT 0,
  `patient_type_faculty` tinyint(1) DEFAULT 0,
  `patient_type_staff` tinyint(1) DEFAULT 0,
  `patient_type_student` tinyint(1) DEFAULT 0,
  `patient_address` text NOT NULL,
  `case_summary` text NOT NULL,
  `reason_for_referral` text NOT NULL,
  `send_back_agency` varchar(255) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `return_patient_name` varchar(255) DEFAULT NULL,
  `return_patient_age` int(11) DEFAULT NULL,
  `return_patient_sex` enum('Male','Female') DEFAULT NULL,
  `services_findings` text DEFAULT NULL,
  `signature_name` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `send_back_patient_name` varchar(255) DEFAULT NULL,
  `send_back_patient_age` varchar(10) DEFAULT NULL,
  `send_back_patient_sex` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_signatures`
-- From wpu database
--

CREATE TABLE IF NOT EXISTS `staff_signatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'MICAELLA T. BAGALANON-LABUTOY, MD, OHP',
  `position` varchar(255) NOT NULL DEFAULT 'University Physician',
  `license_no` varchar(100) NOT NULL DEFAULT 'License. No. 0148115',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `staff_signatures` (`id`, `name`, `position`, `license_no`, `updated_at`) VALUES
(1, 'Jomari B. Recalde, Instructor 1', 'University Physician', 'License. No. 123456', '2025-10-18 01:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `case_types`
-- Merged from wpu_dental and wpu_clinic (same data)
--

CREATE TABLE IF NOT EXISTS `case_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `case_name` (`case_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `case_types` (`id`, `case_name`, `created_at`) VALUES
(1, 'Infectious', '2025-10-11 00:46:37'),
(2, 'Circulatory', '2025-10-11 00:46:37'),
(3, 'Respiratory', '2025-10-11 00:46:37'),
(4, 'Metabolic', '2025-10-11 00:46:37'),
(5, 'Dermatologic', '2025-10-11 00:46:37'),
(6, 'Obstetrical', '2025-10-11 00:46:37'),
(7, 'Gynecologic', '2025-10-11 00:46:37'),
(8, 'Urologic', '2025-10-11 00:46:37'),
(9, 'Digestive', '2025-10-11 00:46:37'),
(10, 'Musculoskeletal', '2025-10-11 00:46:37'),
(11, 'Neurologic', '2025-10-11 00:46:37'),
(12, 'Ophthalmologic', '2025-10-11 00:46:37'),
(13, 'Otolaryngologic', '2025-10-11 00:46:37'),
(14, 'Hematologic', '2025-10-11 00:46:37'),
(15, 'Oncologic', '2025-10-11 00:46:37'),
(16, 'Injury (work-related)', '2025-10-11 00:46:37'),
(17, 'Injury (non-work related)', '2025-10-11 00:46:37'),
(18, 'Foreign Body', '2025-10-11 00:46:37'),
(19, 'Psychological', '2025-10-11 00:46:37'),
(20, 'APE', '2025-10-11 00:46:37'),
(21, 'Medical Exam', '2025-10-11 00:46:37'),
(22, 'Dental', '2025-10-11 00:46:37'),
(23, 'Others', '2025-10-11 00:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
-- Merged from wpu_dental and wpu_clinic
--

CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `departments` (`id`, `name`, `created_at`) VALUES
(1, 'Computer Science', '2025-10-11 00:46:37'),
(2, 'Engineering', '2025-10-11 00:46:37'),
(3, 'Agriculture', '2025-10-11 00:46:37'),
(4, 'Fisheries', '2025-10-11 00:46:37'),
(5, 'CAS', '2025-10-11 05:11:59'),
(6, 'Education', '2025-10-11 05:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `patient_records`
-- Merged from wpu_dental and wpu_clinic
-- Added module_type field to distinguish between dental, health, and certificate records
--

CREATE TABLE IF NOT EXISTS `patient_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_type` enum('dental','health','certificate') DEFAULT 'health',
  `patient_type_id` varchar(100) NOT NULL,
  `student_id` varchar(100) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `age` int(3) NOT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `is_minor` enum('Yes','No') DEFAULT 'No',
  `guardian_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `department_id` varchar(150) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `case_type_id` varchar(150) DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `subjective` text DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `diagnostics` text DEFAULT NULL,
  `assessment` text DEFAULT NULL,
  `plan` text DEFAULT NULL,
  `doctor` varchar(150) DEFAULT NULL,
  `visit_quick_snapshot` mediumtext DEFAULT NULL COMMENT 'JSON: visit fields before last edit (Quick View)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_module_type` (`module_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `patient_record_edit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_record_id` int(11) NOT NULL,
  `edited_by` varchar(50) DEFAULT NULL,
  `snapshot` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_record_created` (`patient_record_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_types`
-- Merged from wpu_dental and wpu_clinic
--

CREATE TABLE IF NOT EXISTS `patient_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `color_code` varchar(7) DEFAULT '#1565C0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `patient_types` (`id`, `type_name`, `color_code`, `created_at`) VALUES
(1, 'Student', '#1565C0', '2025-10-11 00:46:37'),
(2, 'Faculty', '#FF8F00', '2025-10-11 00:46:37'),
(3, 'Staff', '#5C6BC0', '2025-10-11 00:46:37'),
(4, 'Outsider', '#14acd7', '2025-10-13 01:33:06');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
-- Merged from all three databases
--

CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `created_at`) VALUES
(1, 'auto_lock_enabled', '1', '2025-10-20 13:53:30'),
(2, 'auto_lock_timeout', '300000', '2025-10-20 13:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
-- Merged from all three databases
--

CREATE TABLE IF NOT EXISTS `user_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `activity_type` varchar(20) NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
-- Admin actions and other audit events (shown with user_logs on User Logs page)
--

CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_chat`
-- From admin_chat.sql
--

CREATE TABLE IF NOT EXISTS `admin_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(50) NOT NULL,
  `receiver` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sender` (`sender`),
  KEY `idx_receiver` (`receiver`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_files`
-- For file uploads
--

CREATE TABLE IF NOT EXISTS `patient_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_record_id` int(11) DEFAULT NULL,
  `medical_certificate_id` int(11) DEFAULT NULL,
  `referral_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `file_type` varchar(100) DEFAULT NULL,
  `uploaded_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_patient_record` (`patient_record_id`),
  KEY `idx_medical_certificate` (`medical_certificate_id`),
  KEY `idx_referral` (`referral_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

