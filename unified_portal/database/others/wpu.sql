-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 04:21 PM
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
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-10-20 11:51:22'),
(2, 'joms_27', '$2y$10$.vB37GnsoYBoaTodJbzZueDy1MUkHnvfUhwuoiIYuBgPdj7M.avyi', '2025-10-20 13:53:43');

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
(63, 'Jomari Recalde', 23, 'Male', 'Single', '232323', '2025-10-20', '', 1, 1, 'none', '', '', '2025-10-20', '23123123', '2025-10-20 14:14:55', '2025-10-20 14:19:24', '0');

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
(4, 'ACER', '2025-10-20', 'Jomari Recalde', 23, 'MALE', 1, 0, 0, 0, 'asdasda', 'asdasdas', 'asdasdasd', 'WPU', NULL, 'Marvin Saik', 23, 'Male', 'asdasdasd', 'Francis Alterado', 'Dean', '2025-10-20 14:02:09', '2025-10-20 14:02:09', NULL, NULL, NULL);

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
(2, 'auto_lock_timeout', '60000', '2025-10-20 13:53:30');

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
(2, 'joms_27', 'login', '2025-10-20 16:04:32', NULL, NULL, 'Logged In', '2025-10-20 14:04:32');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff_signatures`
--
ALTER TABLE `staff_signatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
