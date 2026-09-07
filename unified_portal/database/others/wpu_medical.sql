-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2025 at 05:16 AM
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
-- Database: `wpu_medical`
--

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
(1, 'WPU-QSF-GASS-JOM-01 Rev.00 (09.20.24)', 'WPU-QSF-GASS-HSO-12 Rev.00 (09.20.24)', '2025-10-06 02:37:40');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_certificates`
--

INSERT INTO `medical_certificates` (`id`, `name`, `age`, `gender`, `civil_status`, `address`, `examination_date`, `reason`, `findings_fit`, `findings_impression`, `impression_text`, `advice`, `receipt_no`, `date_issued`, `mc_no`, `created_at`, `updated_at`) VALUES
(19, 'Dr. Elienta C. Santos', 23, 'Male', 'Single', 'SAN JOSE', '2025-10-06', 'Lagnats', 1, 1, 'PAHNGA', 'PAHINGA', 'R1759713090712782', '2025-10-06', '2222231', '2025-10-06 01:12:04', '2025-10-06 01:12:04'),
(20, 'Enkie Carl B. Echague', 22, 'Male', 'Single', 'ewrew', '2025-10-06', 'ewrw', 1, 1, 'ewer', 'ewrwewerwe', 'R1759718369082889', '2025-10-06', '42343', '2025-10-06 02:53:45', '2025-10-06 02:53:45');

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `hospital_clinic`, `referral_date`, `patient_name`, `patient_age`, `patient_sex`, `patient_type_occupation`, `patient_type_faculty`, `patient_type_staff`, `patient_type_student`, `patient_address`, `case_summary`, `reason_for_referral`, `send_back_agency`, `return_date`, `return_patient_name`, `return_patient_age`, `return_patient_sex`, `services_findings`, `signature_name`, `designation`, `created_at`, `updated_at`) VALUES
(4, 'ONP', '2025-10-06', 'Jomari Recalde', 23, 'FEMALE', 1, 0, 0, 0, 'SAN JOSE', 'LAGNAT', 'KULANG', 'WPU', '2025-10-06', 'JOMARI RECALDE', 23, 'Male', 'NONE', 'JOMARI RECALDE', '123123', '2025-10-06 01:08:46', '2025-10-06 01:08:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificate_codes`
--
ALTER TABLE `certificate_codes`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificate_codes`
--
ALTER TABLE `certificate_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `medical_certificates`
--
ALTER TABLE `medical_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
