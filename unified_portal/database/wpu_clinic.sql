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
-- Database: `wpu_clinic`
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
(7, 'admin', '$2y$10$NgFY2HZeiiFe7sm2/KxvMuj9gzRLGk1FOhchIaOVNY0pf1z5.Ujyy', '2025-10-11 06:25:39'),
(10, 'joms_27', '$2y$10$hy48tFrT6hnWj9PEZEngvOX0KgbC.5G8wwjB8lPonrGTPek.EKWVe', '2025-10-20 13:51:34');

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
(1, 'Computer Science', '2025-10-11 00:46:37'),
(2, 'Engineering', '2025-10-11 00:46:37'),
(3, 'Agriculture', '2025-10-11 00:46:37'),
(4, 'Fisheries', '2025-10-11 00:46:37'),
(6, 'CAS', '2025-10-11 05:11:59'),
(8, 'Education', '2025-10-12 03:02:32');

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
(24, '1', '231232', 'Jomari Recalde', 'Male', 23, 'Single', 'Catholic', 'No', '', '1231232123', 'ssss', '1', '2025-10-18', '17', 'sss', 'sss', 'ss', 'ss', 'sss', 'ss', 'sss', 'Francis Alterado', '2025-10-18 02:21:42'),
(28, '2', '231232', 'Francis Alterado ', 'Male', 23, 'Single', 'Catholic', 'No', '', '0999999999', 'none', '2', '2025-10-18', '10', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'Francis Alterado', '2025-10-18 04:52:07'),
(29, '2', '231232', 'Francis Alterado ', 'Male', 23, 'Single', 'Catholic', 'No', '', '0999999999', 'none', '2', '2025-10-18', '10', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'Francis Alterado', '2025-10-18 04:54:22'),
(30, '1', '231232', 'Harman Rey Golez', 'Male', 23, 'Single', 'Catholic', 'No', '', '0999999999', 'none', '1', '2025-10-18', '15', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'Francis Alterado', '2025-10-18 05:01:03'),
(32, '1', 'STU1001', 'Juan Dela Cruz', 'Male', 21, 'Single', 'Catholic', 'No', '', '09171234567', 'Manila City', '1', '2025-01-12', '1', 'Fever', 'Paracetamol 500mg', 'Fever and headache', 'Temp: 38°C', 'CBC ordered', 'Viral infection suspected', 'Rest and hydrate', 'Dr. Santos', '2025-10-18 08:48:28'),
(33, '2', 'FAC2001', 'Maria Santos', 'Female', 35, 'Married', 'Catholic', 'No', '', '09182345678', 'Quezon City', '2', '2025-02-08', '3', 'Cough', 'Cough syrup', 'Dry cough', 'Lungs clear', 'Chest X-ray', 'Mild bronchitis', 'Hydration + meds', 'Dr. Dela Cruz', '2025-10-18 08:48:28'),
(34, '3', 'STU1002', 'Pedro Reyes', 'Male', 19, 'Single', 'Catholic', 'No', '', '09193456789', 'Caloocan City', '3', '2025-03-05', '2', 'Hypertension', 'Lifestyle advice', 'BP high', 'BP: 150/100', 'ECG', 'Stage 1 hypertension', 'Diet + exercise', 'Dr. Lim', '2025-10-18 08:48:28'),
(35, '1', 'STU1003', 'Ana Cruz', 'Female', 22, 'Single', 'Catholic', 'No', '', '09194567890', 'Pasig City', '4', '2025-04-10', '4', 'Diabetes Mellitus', 'Metformin', 'Frequent urination', 'FBS: 160 mg/dL', 'Urinalysis', 'Type 2 Diabetes', 'Continue meds', 'Dr. Mendoza', '2025-10-18 08:48:28'),
(36, '2', 'FAC2002', 'Jose Ramirez', 'Male', 40, 'Married', 'Christian', 'No', '', '09195678901', 'Makati City', '5', '2025-05-21', '6', 'Back pain', 'Pain reliever', 'Lower back pain', 'No tenderness', 'X-ray', 'Muscle strain', 'Warm compress', 'Dr. Santos', '2025-10-18 08:48:28'),
(37, '3', 'STF3001', 'Elena Garcia', 'Female', 29, 'Married', 'Catholic', 'No', '', '09196789012', 'Taguig City', '2', '2025-06-17', '5', 'Rash', 'Topical ointment', 'Itchy rash', 'Skin redness', 'None', 'Allergic dermatitis', 'Topical steroid', 'Dr. Reyes', '2025-10-18 08:48:28'),
(38, '1', 'STU1004', 'Mark Villanueva', 'Male', 20, 'Single', 'Catholic', 'No', '', '09197890123', 'Bulacan', '3', '2025-07-09', '10', 'Sprain', 'Rest + ice', 'Pain after basketball', 'Swollen ankle', 'None', 'Ankle sprain', 'Rest 1 week', 'Dr. Dela Cruz', '2025-10-18 08:48:28'),
(39, '2', 'FAC2003', 'Cynthia Lopez', 'Female', 42, 'Married', 'Christian', 'No', '', '09198901234', 'Cavite City', '1', '2025-08-15', '12', 'Eye irritation', 'Eye drops', 'Redness and itch', 'Conjunctiva red', 'Eye test', 'Conjunctivitis', 'Avoid touching eyes', 'Dr. Lim', '2025-10-18 08:48:28'),
(41, '1', 'STU001', 'Alice Mendoza', 'Female', 21, 'Single', 'Catholic', 'No', '', '09171234567', 'Cebu City', '2', '2024-08-05', '1', 'Flu', 'Rest and hydration', 'Fever and sore throat', 'Mild dehydration', 'CBC', 'Viral infection', 'Rest 3 days', 'Dr. Santos', '2025-10-18 08:48:28'),
(42, '1', 'STU002', 'Mark Reyes', 'Male', 22, 'Single', 'Christian', 'No', '', '09180001234', 'Mandaue City', '1', '2024-08-12', '2', 'Sprain', 'Cold compress', 'Ankle pain after basketball', 'Swelling in right ankle', 'X-ray', 'Grade 1 sprain', 'Physical rest', 'Dr. Dela Cruz', '2025-10-18 08:48:28'),
(43, '1', 'STU003', 'Jenna Lopez', 'Female', 20, 'Single', 'Catholic', 'No', '', '09192345678', 'Lapu-Lapu City', '3', '2024-08-20', '1', 'Migraine', 'Pain reliever', 'Recurring headache', 'No neurological deficits', 'CT Scan', 'Migraine', 'Avoid stress', 'Dr. Lim', '2025-10-18 08:48:28'),
(44, '1', 'STU004', 'Daniel Cruz', 'Male', 19, 'Single', 'Catholic', 'Yes', 'Maria Cruz', '09223456789', 'Talisay City', '1', '2024-09-03', '1', 'Cough', 'Antibiotics', 'Dry cough 1 week', 'Lungs clear', 'Chest X-ray', 'Bronchitis', 'Take meds 7 days', 'Dr. Ong', '2025-10-18 08:48:28'),
(45, '1', 'STU005', 'Faith Uy', 'Female', 23, 'Single', 'Christian', 'No', '', '09204561234', 'Cebu City', '4', '2024-09-06', '2', 'Back pain', 'Physical therapy', 'Pain after lifting', 'Tender lower back', 'X-ray', 'Muscle strain', '2 PT sessions', 'Dr. Garcia', '2025-10-18 08:48:28'),
(46, '1', 'STU006', 'Leo Tan', 'Male', 20, 'Single', 'Buddhist', 'No', '', '09331234567', 'Liloan', '3', '2024-09-10', '1', 'Allergy', 'Antihistamine', 'Rashes after food intake', 'Mild rash arms', 'Allergy test', 'Food allergy', 'Avoid nuts', 'Dr. Ramos', '2025-10-18 08:48:28'),
(47, '1', 'STU007', 'Nicole dela Peña', 'Female', 22, 'Single', 'Catholic', 'No', '', '09384561234', 'Minglanilla', '2', '2024-09-14', '2', 'Fever', 'Paracetamol', 'Fever 3 days', 'Temperature 38.5°C', 'CBC', 'Viral fever', 'Hydrate & rest', 'Dr. Villanueva', '2025-10-18 08:48:28'),
(48, '1', 'STU008', 'Rico Navarro', 'Male', 21, 'Single', 'Catholic', 'No', '', '09451234567', 'Consolacion', '2', '2024-09-18', '1', 'Stomach Pain', 'Antacid', 'Stomach ache after meal', 'Mild tenderness', 'Ultrasound', 'Gastritis', 'Avoid spicy food', 'Dr. Santos', '2025-10-18 08:48:28'),
(49, '1', 'STU009', 'Kyla Fernandez', 'Female', 18, 'Single', 'Christian', 'Yes', 'Robert Fernandez', '09177881234', 'Cebu City', '3', '2024-08-29', '1', 'Cold', 'Decongestant', 'Runny nose', 'Normal lungs', 'Nasal swab', 'Common cold', 'Rest', 'Dr. Ramos', '2025-10-18 08:48:28'),
(50, '1', 'STU010', 'John Paul Garcia', 'Male', 24, 'Single', 'Catholic', 'No', '', '09191234567', 'Cebu City', '1', '2024-09-22', '2', 'Toothache', 'Pain reliever', 'Tooth pain', 'Swelling gums', 'Dental exam', 'Gingivitis', 'Dental cleaning', 'Dr. Cruz', '2025-10-18 08:48:28'),
(51, '1', 'STU1001', 'John Doe', 'Male', 21, 'Single', 'Christian', 'No', '', '09171234567', 'Dorm A', '2', '2025-08-02', '1', 'Fever', 'Paracetamol 500mg', 'Headache, chills', 'Temperature 38.5°C', 'CBC', 'Viral infection', 'Rest & fluids', 'Dr. Ramos', '2025-10-18 08:48:28'),
(52, '1', 'STU1002', 'Maria Santos', 'Female', 20, 'Single', 'Catholic', 'No', '', '09181234567', 'Dorm B', '3', '2025-08-04', '2', 'Allergic rhinitis', 'Cetirizine 10mg', 'Sneezing, runny nose', 'Nasal congestion', 'Allergy test', 'Allergy confirmed', 'Avoid triggers', 'Dr. Cruz', '2025-10-18 08:48:28'),
(53, '2', 'EMP1001', 'Mark Lee', 'Male', 35, 'Married', 'Christian', 'No', '', '09192345678', 'Faculty Housing', '4', '2025-08-06', '1', 'Back pain', 'Ibuprofen 400mg', 'Lower back pain', 'Pain on movement', 'X-ray', 'Muscle strain', 'Physical therapy', 'Dr. Santos', '2025-10-18 08:48:28'),
(54, '1', 'STU1003', 'Angela Reyes', 'Female', 19, 'Single', 'Catholic', 'No', '', '09173456789', 'Dorm C', '2', '2025-08-08', '3', 'Migraine', 'Pain reliever', 'Headache for 2 days', 'No fever', 'CT Scan', 'Tension headache', 'Avoid stress', 'Dr. Ramos', '2025-10-18 08:48:28'),
(55, '1', 'STU1004', 'Carlos Dela Cruz', 'Male', 22, 'Single', 'Christian', 'No', '', '09184567890', 'Dorm D', '5', '2025-08-10', '2', 'Cough', 'Ambroxol', 'Cough for 1 week', 'Slight wheeze', 'Chest X-ray', 'Bronchitis', 'Antibiotic if needed', 'Dr. Reyes', '2025-10-18 08:48:28'),
(56, '2', 'EMP1002', 'Lisa Mendoza', 'Female', 40, 'Married', 'Catholic', 'No', '', '09195678901', 'Campus Staff Quarters', '1', '2025-08-13', '1', 'Hypertension', 'Amlodipine 5mg', 'Headache, dizziness', 'BP 150/100', 'BP monitoring', 'High BP', 'Lifestyle changes', 'Dr. Santos', '2025-10-18 08:48:28'),
(57, '1', 'STU1005', 'Daniel Cruz', 'Male', 18, 'Single', 'Christian', 'Yes', 'Jose Cruz', '09196789012', 'Dorm E', '3', '2025-08-15', '3', 'Sprained ankle', 'Cold compress', 'Twisted ankle during PE', 'Swollen ankle', 'Physical exam', 'Sprain', 'Rest 3 days', 'Dr. Ramos', '2025-10-18 08:48:28'),
(58, '1', 'STU1006', 'Julia Garcia', 'Female', 19, 'Single', 'Catholic', 'No', '', '09197890123', 'Dorm F', '4', '2025-08-17', '2', 'Sore throat', 'Lozenges', 'Pain swallowing', 'Red tonsils', 'Throat swab', 'Pharyngitis', 'Hydration, rest', 'Dr. Cruz', '2025-10-18 08:48:28'),
(59, '1', 'STU1007', 'Erica Lim', 'Female', 20, 'Single', 'Buddhist', 'No', '', '09199012345', 'Dorm G', '2', '2025-08-27', '3', 'Fever & cough', 'Paracetamol, fluids', 'Fever 38°C', 'Mild cough', 'CBC', 'Viral infection', 'Monitor temperature', 'Dr. Reyes', '2025-10-18 08:48:28'),
(60, '1', 'STU1008', 'Paolo Fernandez', 'Male', 21, 'Single', 'Christian', 'No', '', '09201234567', 'Dorm H', '2', '2025-09-01', '1', 'Fever', 'Paracetamol 500mg', 'Headache, chills', 'Temp 38.7°C', 'CBC', 'Viral', 'Rest & fluids', 'Dr. Ramos', '2025-10-18 08:48:28'),
(61, '1', 'STU1009', 'Grace Bautista', 'Female', 20, 'Single', 'Catholic', 'No', '', '09212345678', 'Dorm I', '3', '2025-09-03', '2', 'Cough', 'Carbocisteine', 'Cough, sore throat', 'No fever', 'Throat exam', 'Mild pharyngitis', 'Rest', 'Dr. Cruz', '2025-10-18 08:48:28'),
(62, '2', 'EMP1004', 'Henry Tan', 'Male', 45, 'Married', 'Christian', 'No', '', '09223456789', 'Admin Bldg', '4', '2025-09-04', '3', 'Joint pain', 'Pain reliever', 'Knee pain', 'Limited flexion', 'X-ray', 'Osteoarthritis', 'Regular exercise', 'Dr. Santos', '2025-10-18 08:48:28'),
(63, '1', 'STU1010', 'Anna Lopez', 'Female', 18, 'Single', 'Catholic', 'Yes', 'Laura Lopez', '09234567890', 'Dorm J', '5', '2025-09-06', '2', 'Cold', 'Decongestant', 'Runny nose', 'Mild congestion', 'Allergy test', 'Allergic rhinitis', 'Avoid dust', 'Dr. Reyes', '2025-10-18 08:48:28'),
(64, '1', 'STU1011', 'Patrick Cruz', 'Male', 22, 'Single', 'Christian', 'No', '', '09245678901', 'Dorm K', '3', '2025-09-08', '1', 'Fever & headache', 'Paracetamol', 'Fever 39°C', 'Tired look', 'CBC', 'Viral', 'Bed rest', 'Dr. Ramos', '2025-10-18 08:48:28');

-- --------------------------------------------------------

--
-- Table structure for table `patient_types`
--

CREATE TABLE `patient_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `color_code` varchar(7) DEFAULT '#1565C0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_types`
--

INSERT INTO `patient_types` (`id`, `type_name`, `created_at`, `color_code`) VALUES
(1, 'Student', '2025-10-11 00:46:37', '#F54927'),
(2, 'Faculty', '2025-10-11 00:46:37', '#276CF5'),
(3, 'Staff', '2025-10-11 00:46:37', '#F52765'),
(7, 'Outsider', '2025-10-13 01:33:06', '#14acd7');

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
(3, 'auto_lock_timeout', '60000');

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
(1, 'admin', 'login', '2025-10-15 04:22:29', '2025-10-15 04:34:57', '12m 28s', 'Logged Out', '2025-10-15 02:22:29'),
(2, 'admin', 'login', '2025-10-15 04:35:01', '2025-10-18 04:16:44', '5m 17s', 'Logged Out', '2025-10-15 02:35:01'),
(3, 'admin', 'login', '2025-10-17 07:08:38', '2025-10-17 09:42:38', '154m 0s', 'Logged Out', '2025-10-17 05:08:38'),
(4, 'admin', 'login', '2025-10-17 09:42:43', '2025-10-17 10:12:22', '16m 2s', 'Logged Out', '2025-10-17 07:42:43'),
(5, 'admin', 'login', '2025-10-17 10:12:24', '2025-10-17 10:37:05', '24m 41s', 'Logged Out', '2025-10-17 08:12:24'),
(6, 'admin', 'login', '2025-10-17 10:48:00', '2025-10-17 10:50:43', '2m 43s', 'Logged Out', '2025-10-17 08:48:00'),
(7, 'admin', 'login', '2025-10-17 11:02:09', '2025-10-17 11:03:49', '1m 40s', 'Logged Out', '2025-10-17 09:02:09'),
(8, 'admin', 'login', '2025-10-18 03:51:56', '2025-10-18 03:56:02', '4m 6s', 'Logged Out', '2025-10-18 01:51:56'),
(9, 'admin', 'login', '2025-10-18 04:16:53', '2025-10-18 04:18:59', '2m 6s', 'Logged Out', '2025-10-18 02:16:53'),
(10, 'admin', 'login', '2025-10-18 04:20:00', '2025-10-18 04:21:57', '1m 57s', 'Logged Out', '2025-10-18 02:20:00'),
(11, 'admin', 'login', '2025-10-18 04:25:04', '2025-10-18 04:26:16', '1m 12s', 'Logged Out', '2025-10-18 02:25:04'),
(12, 'admin', 'login', '2025-10-18 06:03:55', NULL, NULL, 'Logged In', '2025-10-18 04:03:55'),
(13, 'admin', 'login', '2025-10-18 06:46:38', '2025-10-18 07:01:42', '15m 4s', 'Logged Out', '2025-10-18 04:46:38'),
(14, 'admin', 'login', '2025-10-18 07:03:19', NULL, NULL, 'Logged In', '2025-10-18 05:03:19'),
(15, 'admin', 'login', '2025-10-18 10:46:17', '2025-10-18 10:51:27', '5m 10s', 'Logged Out', '2025-10-18 08:46:17'),
(16, 'admin', 'login', '2025-10-18 11:10:55', '2025-10-18 11:12:51', '1m 56s', 'Logged Out', '2025-10-18 09:10:55'),
(17, 'admin', 'login', '2025-10-18 12:37:20', '2025-10-18 12:37:58', '0m 38s', 'Logged Out', '2025-10-18 10:37:20'),
(18, 'admin', 'login', '2025-10-18 12:39:50', '2025-10-18 13:02:59', '23m 9s', 'Logged Out', '2025-10-18 10:39:50'),
(19, 'admin', 'login', '2025-10-19 03:39:27', NULL, NULL, 'Logged In', '2025-10-19 01:39:27'),
(20, 'admin', 'login', '2025-10-19 10:09:23', NULL, NULL, 'Logged In', '2025-10-19 08:09:23'),
(21, 'admin', 'login', '2025-10-19 23:54:06', '2025-10-19 23:54:16', '0m 10s', 'Logged Out', '2025-10-19 21:54:06'),
(22, 'admin', 'login', '2025-10-20 00:01:22', '2025-10-20 00:01:34', '0m 12s', 'Logged Out', '2025-10-19 22:01:22'),
(23, 'admin', 'login', '2025-10-20 00:13:53', NULL, NULL, 'Logged In', '2025-10-19 22:13:53'),
(24, 'admin', 'login', '2025-10-20 05:06:05', '2025-10-20 07:54:21', '168m 16s', 'Logged Out', '2025-10-20 03:06:05'),
(25, 'admin', 'login', '2025-10-20 08:23:19', '2025-10-20 08:25:31', '2m 12s', 'Logged Out', '2025-10-20 06:23:19'),
(26, 'jomari', 'login', '2025-10-20 08:26:56', NULL, NULL, 'Logged In', '2025-10-20 06:26:56'),
(27, 'jomari', 'login', '2025-10-20 08:27:00', NULL, NULL, 'Logged In', '2025-10-20 06:27:00'),
(28, 'admin', 'login', '2025-10-20 09:31:57', NULL, NULL, 'Logged In', '2025-10-20 07:31:57'),
(29, 'admin', 'login', '2025-10-20 09:37:58', '2025-10-20 09:49:45', '11m 47s', 'Logged Out', '2025-10-20 07:37:58'),
(30, 'admin', 'login', '2025-10-20 09:49:54', '2025-10-20 09:52:10', '2m 16s', 'Logged Out', '2025-10-20 07:49:54'),
(31, 'admin', 'login', '2025-10-20 09:55:13', NULL, NULL, 'Logged In', '2025-10-20 07:55:13'),
(32, 'admin', 'login', '2025-10-20 14:53:03', '2025-10-20 14:53:59', '0m 56s', 'Logged Out', '2025-10-20 12:53:03'),
(33, 'admin', 'login', '2025-10-20 14:54:03', '2025-10-20 15:11:58', '17m 55s', 'Logged Out', '2025-10-20 12:54:03'),
(34, 'admin', 'login', '2025-10-20 15:13:49', '2025-10-20 15:20:07', '6m 18s', 'Logged Out', '2025-10-20 13:13:49'),
(35, 'admin', 'login', '2025-10-20 15:20:11', '2025-10-20 15:36:11', '16m 0s', 'Logged Out', '2025-10-20 13:20:11'),
(36, 'admin', 'login', '2025-10-20 15:40:18', '2025-10-20 15:43:09', '2m 51s', 'Logged Out', '2025-10-20 13:40:18'),
(37, 'admin', 'login', '2025-10-20 15:43:12', NULL, NULL, 'Logged In', '2025-10-20 13:43:12'),
(38, 'admin', 'login', '2025-10-20 16:04:02', '2025-10-20 16:04:10', '0m 8s', 'Logged Out', '2025-10-20 14:04:02'),
(39, 'admin', 'login', '2025-10-20 23:51:26', '2025-10-20 23:51:36', '0m 10s', 'Logged Out', '2025-10-20 21:51:26'),
(40, 'admin', 'login', '2025-10-20 23:51:39', '2025-10-21 00:02:36', '10m 57s', 'Logged Out', '2025-10-20 21:51:39'),
(41, 'admin', 'login', '2025-10-21 00:02:39', '2025-10-21 00:25:16', '22m 37s', 'Logged Out', '2025-10-20 22:02:39'),
(42, 'admin', 'login', '2025-10-21 00:55:05', NULL, NULL, 'Logged In', '2025-10-20 22:55:05'),
(43, 'joms_27', 'login', '2025-10-21 06:26:08', '2025-10-21 06:28:10', '2m 2s', 'Logged Out', '2025-10-21 04:26:08'),
(44, 'admin', 'login', '2025-10-21 13:33:26', '2025-10-21 13:33:37', '0m 11s', 'Logged Out', '2025-10-21 11:33:26'),
(45, 'joms_27', 'login', '2025-10-24 02:09:12', '2025-10-24 02:43:23', '34m 11s', 'Logged Out', '2025-10-24 00:09:12'),
(46, 'admin', 'login', '2025-10-27 01:35:15', NULL, NULL, 'Logged In', '2025-10-27 00:35:15');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `case_types`
--
ALTER TABLE `case_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `patient_records`
--
ALTER TABLE `patient_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `patient_types`
--
ALTER TABLE `patient_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
