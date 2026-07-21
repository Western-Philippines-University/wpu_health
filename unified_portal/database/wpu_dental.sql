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
-- Database: `wpu_dental`
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
(7, 'admin', '$2y$10$bIjJ2eTjbpiM6csHYQT5VuxCyYxqlMCfNU0HVo49RexyPI1lOFvqa', '2025-10-11 06:25:39');

-- --------------------------------------------------------

--
-- Table structure for table `case_types`
--

CREATE TABLE `case_types` (
  `id` int(11) NOT NULL,
  `case_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_types`
--

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
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`) VALUES
(2, 'Engineering', '2025-10-11 00:46:37'),
(3, 'Agriculture', '2025-10-11 00:46:37'),
(4, 'Fisheries', '2025-10-11 00:46:37'),
(6, 'CAS', '2025-10-11 05:11:59'),
(7, 'Education', '2025-10-11 05:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `patient_records`
--

CREATE TABLE `patient_records` (
  `id` int(11) NOT NULL,
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_records`
--

INSERT INTO `patient_records` (`id`, `patient_type_id`, `student_id`, `full_name`, `gender`, `age`, `marital_status`, `religion`, `is_minor`, `guardian_name`, `phone_number`, `address`, `department_id`, `visit_date`, `case_type_id`, `diagnosis`, `treatment`, `subjective`, `objectives`, `diagnostics`, `assessment`, `plan`, `doctor`, `created_at`) VALUES
(1, '1', '231232', 'Jomari Recalde', 'Male', 23, 'Single', 'ss', 'No', '', 'ss', 'ss', '4', '2025-10-17', '15', 'ss', 'ss', 'ss', 'ss', 'ss', 'ss', 'ss', 'Francis Alterado', '2025-10-17 07:48:56'),
(2, '1', '231232', 'Harman Rey Golez', 'Male', 23, 'Single', 'Catholic', 'Yes', 'MARVIN SAIK', '0999999999', 'none', '1', '2025-10-18', '11', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'Francis Alterado', '2025-10-18 05:02:21');

-- --------------------------------------------------------

--
-- Table structure for table `patient_types`
--

CREATE TABLE `patient_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `color_code` varchar(7) DEFAULT '#1565C0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_types`
--

INSERT INTO `patient_types` (`id`, `type_name`, `color_code`, `created_at`) VALUES
(1, 'Student', '#1565C0', '2025-10-11 00:46:37'),
(2, 'Faculty', '#FF8F00', '2025-10-11 00:46:37'),
(3, 'Staff', '#5C6BC0', '2025-10-11 00:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'auto_lock_enabled', '1'),
(3, 'auto_lock_timeout', '300000');

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `activity_type` enum('login','logout','lock') NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logs`
--

INSERT INTO `user_logs` (`id`, `username`, `activity_type`, `login_time`, `logout_time`, `duration`, `status`, `created_at`) VALUES
(1, 'admin', 'login', '2025-10-17 09:56:20', NULL, NULL, 'Logged In', '2025-10-17 07:56:20'),
(2, 'admin', 'login', '2025-10-17 10:44:24', '2025-10-17 10:44:25', '0m 1s', 'Logged Out', '2025-10-17 08:44:24'),
(3, 'jomari', 'login', '2025-10-17 10:51:44', '2025-10-17 10:56:14', '4m 30s', 'Logged Out', '2025-10-17 08:51:44'),
(4, 'admin', 'login', '2025-10-18 04:11:27', NULL, NULL, 'Logged In', '2025-10-18 02:11:27'),
(5, 'admin', 'login', '2025-10-18 07:01:47', '2025-10-18 07:02:47', '1m 0s', 'Logged Out', '2025-10-18 05:01:47'),
(6, 'jomari', 'login', '2025-10-18 07:02:53', '2025-10-18 07:02:58', '0m 5s', 'Logged Out', '2025-10-18 05:02:53'),
(7, 'admin', 'login', '2025-10-18 12:38:04', '2025-10-18 12:39:44', '1m 40s', 'Logged Out', '2025-10-18 10:38:04'),
(8, 'admin', 'login', '2025-10-19 23:54:23', '2025-10-19 23:56:26', '2m 3s', 'Logged Out', '2025-10-19 21:54:23'),
(9, 'admin', 'login', '2025-10-21 00:25:22', NULL, NULL, 'Logged In', '2025-10-20 22:25:22'),
(10, 'admin', 'login', '2025-10-21 06:12:44', '2025-10-21 06:17:41', '4m 57s', 'Logged Out', '2025-10-21 04:12:44'),
(11, 'admin', 'login', '2025-10-21 13:32:47', '2025-10-21 13:33:21', '0m 34s', 'Logged Out', '2025-10-21 11:32:47'),
(12, 'admin', 'login', '2025-10-21 13:33:43', '2025-10-21 13:34:55', '1m 12s', 'Logged Out', '2025-10-21 11:33:43'),
(13, 'admin', 'login', '2025-10-23 12:09:45', '2025-10-23 12:09:59', '0m 14s', 'Logged Out', '2025-10-23 10:09:45'),
(14, 'admin', 'login', '2025-10-24 02:08:34', '2025-10-24 02:09:06', '0m 32s', 'Logged Out', '2025-10-24 00:08:34'),
(15, 'admin', 'login', '2025-10-24 02:08:34', NULL, NULL, 'Logged In', '2025-10-24 00:08:34'),
(16, 'admin', 'login', '2025-10-24 02:08:34', NULL, NULL, 'Logged In', '2025-10-24 00:08:34'),
(17, 'admin', 'login', '2025-10-24 02:08:34', NULL, NULL, 'Logged In', '2025-10-24 00:08:34'),
(18, 'admin', 'login', '2025-10-27 01:33:51', '2025-10-27 01:35:07', '1m 16s', 'Logged Out', '2025-10-27 00:33:51');

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
-- Indexes for table `case_types`
--
ALTER TABLE `case_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `case_name` (`case_name`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `patient_records`
--
ALTER TABLE `patient_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_types`
--
ALTER TABLE `patient_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `case_types`
--
ALTER TABLE `case_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `patient_records`
--
ALTER TABLE `patient_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patient_types`
--
ALTER TABLE `patient_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
