-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2025 at 01:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(2, 'joms_27', '$2y$10$.vB37GnsoYBoaTodJbzZueDy1MUkHnvfUhwuoiIYuBgPdj7M.avyi', '2025-10-20 13:53:43'),
(3, 'admin', '$2y$10$YMfSNHk2hjH9Cr4tg0UYu..ShqiyzMBajbirin6.Mw61RaSTz0vqi', '2025-10-21 04:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_codes`
--

CREATE TABLE `certificate_codes` (
  `id` int(11) NOT NULL,
  `certificate_code` varchar(255) NOT NULL DEFAULT 'WPU-QSF-GASS-HSO-01 Rev.00 (09.20.24)',
  `referral_code` varchar(255) NOT NULL DEFAULT 'WPU-QSF-GASS-HSO-12 Rev.00 (09.20.24)',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_codes`
--

INSERT INTO `certificate_codes` (`id`, `certificate_code`, `referral_code`, `updated_at`) VALUES
(1, 'WPU-QSF-GASS-HSO-01 Rev.00 (09.20.24)', 'WPU-QSF-GASS-HSO-12 Rev.00 (09.20.25)', '2025-10-17 10:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `md_off`
--

CREATE TABLE `md_off` (
  `ID` int(11) NOT NULL,
  `Name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_certificates`
--

CREATE TABLE `medical_certificates` (
  `id` int(11) NOT NULL,
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
  `findings` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_certificates`
--

INSERT INTO `medical_certificates` (`id`, `name`, `age`, `gender`, `civil_status`, `address`, `examination_date`, `reason`, `findings_fit`, `findings_impression`, `impression_text`, `advice`, `receipt_no`, `date_issued`, `mc_no`, `created_at`, `updated_at`, `findings`) VALUES
(64, 'Jacob mabag', 23, 'Male', 'Single', 'sasdasd', '2025-10-20', 'ramu-ramu', 1, 1, 'helloooooooooooooooo', 'pahinga', 'WPU-MC-2025-8318', '2025-10-21', '23123123', '2025-10-20 14:31:17', '2025-10-21 04:04:37', '0'),
(65, 'Jomari Recalde', 23, 'Male', 'Single', '2323232', '2025-10-21', 'ramu-ramu', 1, 0, 'asdasdasd', 'asdasd', 'WPU-MC-2025-3510', '2025-10-21', '123123123', '2025-10-20 14:44:07', '2025-10-21 10:52:24', '0'),
(68, 'Francis Alterado', 45, 'Male', 'Single', 'asdasd', '2025-10-21', 'ramu-ramu', 1, 1, 'asdasd', 'asdasd', 'WPU-MC-2025-5999', '2025-10-21', '23123123', '2025-10-21 11:02:25', '2025-10-21 11:02:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
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
  `send_back_patient_sex` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `hospital_clinic`, `referral_date`, `patient_name`, `patient_age`, `patient_sex`, `patient_type_occupation`, `patient_type_faculty`, `patient_type_staff`, `patient_type_student`, `patient_address`, `case_summary`, `reason_for_referral`, `send_back_agency`, `return_date`, `return_patient_name`, `return_patient_age`, `return_patient_sex`, `services_findings`, `signature_name`, `designation`, `created_at`, `updated_at`, `send_back_patient_name`, `send_back_patient_age`, `send_back_patient_sex`) VALUES
(4, 'ACERER', '2025-10-20', 'Enkie Echague', 23, 'MALE', 1, 0, 0, 0, 'none', 'none', '', 'WPU', NULL, 'Jomari Recalde', 25, 'Male', 'HELLO', 'Francis Alterado', 'Dean', '2025-10-20 14:02:09', '2025-10-21 03:39:32', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff_signatures`
--

CREATE TABLE `staff_signatures` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'MICAELLA T. BAGALANON-LABUTOY, MD, OHP',
  `position` varchar(255) NOT NULL DEFAULT 'University Physician',
  `license_no` varchar(100) NOT NULL DEFAULT 'License. No. 0148115',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_signatures`
--

INSERT INTO `staff_signatures` (`id`, `name`, `position`, `license_no`, `updated_at`) VALUES
(1, 'Jomari B. Recalde, Instructor 1', 'University Physician', 'License. No. 123456', '2025-10-18 01:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `created_at`) VALUES
(1, 'auto_lock_enabled', '1', '2025-10-20 13:53:30'),
(2, 'auto_lock_timeout', '300000', '2025-10-20 13:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `activity_type` varchar(20) NOT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `username`, `activity_type`, `login_time`, `logout_time`, `duration`, `status`, `created_at`) VALUES
(1, 'joms_27', 'login', '2025-10-20 16:04:22', '2025-10-20 16:04:30', '0m 8s', 'Logged Out', '2025-10-20 14:04:22'),
(2, 'joms_27', 'login', '2025-10-20 16:04:32', NULL, NULL, 'Logged In', '2025-10-20 14:04:32'),
(3, 'joms_27', 'login', '2025-10-20 23:49:05', '2025-10-20 23:51:19', '2m 14s', 'Logged Out', '2025-10-20 21:49:05'),
(4, 'joms_27', 'login', '2025-10-21 00:43:13', '2025-10-21 00:54:56', '11m 43s', 'Logged Out', '2025-10-20 22:43:13'),
(5, 'joms_27', 'login', '2025-10-21 03:05:08', NULL, NULL, 'Logged In', '2025-10-21 01:05:08'),
(6, 'joms_27', 'login', '2025-10-21 04:15:04', '2025-10-21 05:43:56', '88m 52s', 'Logged Out', '2025-10-21 02:15:04'),
(7, 'joms_27', 'login', '2025-10-21 05:44:00', '2025-10-21 05:53:20', '9m 20s', 'Logged Out', '2025-10-21 03:44:00'),
(8, 'joms_27', 'login', '2025-10-21 05:53:45', '2025-10-21 05:54:15', '0m 30s', 'Logged Out', '2025-10-21 03:53:45'),
(9, 'joms_27', 'login', '2025-10-21 05:55:33', '2025-10-21 05:55:35', '0m 2s', 'Logged Out', '2025-10-21 03:55:33'),
(10, 'joms_27', 'login', '2025-10-21 05:55:58', '2025-10-21 05:55:59', '0m 1s', 'Logged Out', '2025-10-21 03:55:58'),
(11, 'joms_27', 'login', '2025-10-21 05:56:09', '2025-10-21 05:56:11', '0m 2s', 'Logged Out', '2025-10-21 03:56:09'),
(12, 'joms_27', 'login', '2025-10-21 05:56:21', '2025-10-21 05:56:22', '0m 1s', 'Logged Out', '2025-10-21 03:56:21'),
(13, 'joms_27', 'login', '2025-10-21 05:56:37', '2025-10-21 05:56:39', '0m 2s', 'Logged Out', '2025-10-21 03:56:37'),
(14, 'joms_27', 'login', '2025-10-21 05:56:46', '2025-10-21 06:10:02', '13m 16s', 'Logged Out', '2025-10-21 03:56:46'),
(15, 'admin', 'login', '2025-10-21 06:03:39', NULL, NULL, 'Logged In', '2025-10-21 04:03:39'),
(16, 'joms_27', 'login', '2025-10-21 06:12:04', NULL, NULL, 'Logged In', '2025-10-21 04:12:04'),
(17, 'joms_27', 'login', '2025-10-21 06:12:09', '2025-10-21 06:12:36', '0m 27s', 'Logged Out', '2025-10-21 04:12:09'),
(18, 'joms_27', 'login', '2025-10-21 06:17:47', '2025-10-21 06:25:11', '7m 24s', 'Logged Out', '2025-10-21 04:17:47'),
(19, 'joms_27', 'login', '2025-10-21 06:28:19', '2025-10-21 07:05:36', '37m 17s', 'Logged Out', '2025-10-21 04:28:19'),
(20, 'joms_27', 'login', '2025-10-21 12:04:49', '2025-10-21 13:32:37', '87m 48s', 'Logged Out', '2025-10-21 10:04:49'),
(21, 'joms_27', 'login', '2025-10-22 02:58:54', NULL, NULL, 'Logged In', '2025-10-22 00:58:54'),
(22, 'joms_27', 'login', '2025-10-23 08:29:09', NULL, NULL, 'Logged In', '2025-10-23 06:29:09'),
(23, 'admin', 'login', '2025-10-23 08:30:50', NULL, NULL, 'Logged In', '2025-10-23 06:30:50'),
(24, 'admin', 'login', '2025-10-23 12:09:25', '2025-10-23 12:09:35', '0m 10s', 'Logged Out', '2025-10-23 10:09:25'),
(25, 'admin', 'login', '2025-10-24 02:07:48', '2025-10-24 02:07:56', '0m 8s', 'Logged Out', '2025-10-24 00:07:48'),
(26, 'admin', 'login', '2025-10-27 01:32:42', '2025-10-27 01:33:43', '1m 1s', 'Logged Out', '2025-10-27 00:32:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `certificate_codes`
--
ALTER TABLE `certificate_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `md_off`
--
ALTER TABLE `md_off`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `medical_certificates`
--
ALTER TABLE `medical_certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_signatures`
--
ALTER TABLE `staff_signatures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certificate_codes`
--
ALTER TABLE `certificate_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `md_off`
--
ALTER TABLE `md_off`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_certificates`
--
ALTER TABLE `medical_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff_signatures`
--
ALTER TABLE `staff_signatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
