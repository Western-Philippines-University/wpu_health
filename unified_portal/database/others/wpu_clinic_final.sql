-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 07:33 AM
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
(7, 'admin', '$2y$10$bIjJ2eTjbpiM6csHYQT5VuxCyYxqlMCfNU0HVo49RexyPI1lOFvqa', '2025-10-11 06:25:39'),
(8, 'jomari', '$2y$10$mIUx4M36X9.HsIcRU5DA4.MYFeZHwV1pLxfbMCH1DqSuWjIko3I66', '2025-10-12 01:09:47');

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
(2, '7', '231232', 'Jomari Recalde', 'Male', 23, 'Single', 'Catholic', 'Yes', 'MARVIN SAIK', '1231232123', 'asdasdasdasd', '6', '2025-10-11', '14', 'asdasdasdasd', 'asasdasdasd', 'asdasdasd', 'asdasdasd', 'asdasdasd', 'asdasdasdasd', 'asdasdasdasd', 'Francis Alterado', '2025-10-11 07:24:46'),
(3, '1', 'STU1001', 'Juan Dela Cruz', 'Male', 21, 'Single', 'Catholic', 'No', '', '09171234567', 'Manila City', '1', '2025-01-12', '1', 'Fever', 'Paracetamol 500mg', 'Fever and headache', 'Temp: 38°C', 'CBC ordered', 'Viral infection suspected', 'Rest and hydrate', 'Dr. Santos', '2025-10-11 07:24:46'),
(4, '2', 'FAC2001', 'Maria Santos', 'Female', 35, 'Married', 'Catholic', 'No', '', '09182345678', 'Quezon City', '2', '2025-02-08', '3', 'Cough', 'Cough syrup', 'Dry cough', 'Lungs clear', 'Chest X-ray', 'Mild bronchitis', 'Hydration + meds', 'Dr. Dela Cruz', '2025-10-11 07:24:46'),
(5, '3', 'STU1002', 'Pedro Reyes', 'Male', 19, 'Single', 'Catholic', 'No', '', '09193456789', 'Caloocan City', '3', '2025-03-05', '2', 'Hypertension', 'Lifestyle advice', 'BP high', 'BP: 150/100', 'ECG', 'Stage 1 hypertension', 'Diet + exercise', 'Dr. Lim', '2025-10-11 07:24:46'),
(6, '1', 'STU1003', 'Ana Cruz', 'Female', 22, 'Single', 'Catholic', 'No', '', '09194567890', 'Pasig City', '4', '2025-04-10', '4', 'Diabetes Mellitus', 'Metformin', 'Frequent urination', 'FBS: 160 mg/dL', 'Urinalysis', 'Type 2 Diabetes', 'Continue meds', 'Dr. Mendoza', '2025-10-11 07:24:46'),
(7, '2', 'FAC2002', 'Jose Ramirez', 'Male', 40, 'Married', 'Christian', 'No', '', '09195678901', 'Makati City', '5', '2025-05-21', '6', 'Back pain', 'Pain reliever', 'Lower back pain', 'No tenderness', 'X-ray', 'Muscle strain', 'Warm compress', 'Dr. Santos', '2025-10-11 07:24:46'),
(8, '3', 'STF3001', 'Elena Garcia', 'Female', 29, 'Married', 'Catholic', 'No', '', '09196789012', 'Taguig City', '2', '2025-06-17', '5', 'Rash', 'Topical ointment', 'Itchy rash', 'Skin redness', 'None', 'Allergic dermatitis', 'Topical steroid', 'Dr. Reyes', '2025-10-11 07:24:46'),
(9, '1', 'STU1004', 'Mark Villanueva', 'Male', 20, 'Single', 'Catholic', 'No', '', '09197890123', 'Bulacan', '3', '2025-07-09', '10', 'Sprain', 'Rest + ice', 'Pain after basketball', 'Swollen ankle', 'None', 'Ankle sprain', 'Rest 1 week', 'Dr. Dela Cruz', '2025-10-11 07:24:46'),
(10, '2', 'FAC2003', 'Cynthia Lopez', 'Female', 42, 'Married', 'Christian', 'No', '', '09198901234', 'Cavite City', '1', '2025-08-15', '12', 'Eye irritation', 'Eye drops', 'Redness and itch', 'Conjunctiva red', 'Eye test', 'Conjunctivitis', 'Avoid touching eyes', 'Dr. Lim', '2025-10-11 07:24:46'),
(11, '3', 'STF3002', 'Robert Cruz', 'Male', 33, 'Married', 'Catholic', 'No', '', '09199012345', 'Batangas', '4', '2025-09-26', '11', 'Headache', 'Pain reliever', 'Occasional headache', 'BP: 130/90', 'CT scan recommended', 'Tension headache', 'Hydration + rest', 'Dr. Mendoza', '2025-10-11 07:24:46'),
(14, '1', 'STU001', 'Alice Mendoza', 'Female', 21, 'Single', 'Catholic', 'No', '', '09171234567', 'Cebu City', '2', '2024-08-05', '1', 'Flu', 'Rest and hydration', 'Fever and sore throat', 'Mild dehydration', 'CBC', 'Viral infection', 'Rest 3 days', 'Dr. Santos', '2025-10-11 07:24:46'),
(15, '1', 'STU002', 'Mark Reyes', 'Male', 22, 'Single', 'Christian', 'No', '', '09180001234', 'Mandaue City', '1', '2024-08-12', '2', 'Sprain', 'Cold compress', 'Ankle pain after basketball', 'Swelling in right ankle', 'X-ray', 'Grade 1 sprain', 'Physical rest', 'Dr. Dela Cruz', '2025-10-11 07:24:46'),
(16, '1', 'STU003', 'Jenna Lopez', 'Female', 20, 'Single', 'Catholic', 'No', '', '09192345678', 'Lapu-Lapu City', '3', '2024-08-20', '1', 'Migraine', 'Pain reliever', 'Recurring headache', 'No neurological deficits', 'CT Scan', 'Migraine', 'Avoid stress', 'Dr. Lim', '2025-10-11 07:24:46'),
(17, '1', 'STU004', 'Daniel Cruz', 'Male', 19, 'Single', 'Catholic', 'Yes', 'Maria Cruz', '09223456789', 'Talisay City', '1', '2024-09-03', '1', 'Cough', 'Antibiotics', 'Dry cough 1 week', 'Lungs clear', 'Chest X-ray', 'Bronchitis', 'Take meds 7 days', 'Dr. Ong', '2025-10-11 07:24:46'),
(18, '1', 'STU005', 'Faith Uy', 'Female', 23, 'Single', 'Christian', 'No', '', '09204561234', 'Cebu City', '4', '2024-09-06', '2', 'Back pain', 'Physical therapy', 'Pain after lifting', 'Tender lower back', 'X-ray', 'Muscle strain', '2 PT sessions', 'Dr. Garcia', '2025-10-11 07:24:46'),
(19, '1', 'STU006', 'Leo Tan', 'Male', 20, 'Single', 'Buddhist', 'No', '', '09331234567', 'Liloan', '3', '2024-09-10', '1', 'Allergy', 'Antihistamine', 'Rashes after food intake', 'Mild rash arms', 'Allergy test', 'Food allergy', 'Avoid nuts', 'Dr. Ramos', '2025-10-11 07:24:46'),
(20, '1', 'STU007', 'Nicole dela Peña', 'Female', 22, 'Single', 'Catholic', 'No', '', '09384561234', 'Minglanilla', '2', '2024-09-14', '2', 'Fever', 'Paracetamol', 'Fever 3 days', 'Temperature 38.5°C', 'CBC', 'Viral fever', 'Hydrate & rest', 'Dr. Villanueva', '2025-10-11 07:24:46'),
(21, '1', 'STU008', 'Rico Navarro', 'Male', 21, 'Single', 'Catholic', 'No', '', '09451234567', 'Consolacion', '2', '2024-09-18', '1', 'Stomach Pain', 'Antacid', 'Stomach ache after meal', 'Mild tenderness', 'Ultrasound', 'Gastritis', 'Avoid spicy food', 'Dr. Santos', '2025-10-11 07:24:46'),
(22, '1', 'STU009', 'Kyla Fernandez', 'Female', 18, 'Single', 'Christian', 'Yes', 'Robert Fernandez', '09177881234', 'Cebu City', '3', '2024-08-29', '1', 'Cold', 'Decongestant', 'Runny nose', 'Normal lungs', 'Nasal swab', 'Common cold', 'Rest', 'Dr. Ramos', '2025-10-11 07:24:46'),
(23, '1', 'STU010', 'John Paul Garcia', 'Male', 24, 'Single', 'Catholic', 'No', '', '09191234567', 'Cebu City', '1', '2024-09-22', '2', 'Toothache', 'Pain reliever', 'Tooth pain', 'Swelling gums', 'Dental exam', 'Gingivitis', 'Dental cleaning', 'Dr. Cruz', '2025-10-11 07:24:46'),
(24, '1', 'STU1001', 'John Doe', 'Male', 21, 'Single', 'Christian', 'No', '', '09171234567', 'Dorm A', '2', '2025-08-02', '1', 'Fever', 'Paracetamol 500mg', 'Headache, chills', 'Temperature 38.5°C', 'CBC', 'Viral infection', 'Rest & fluids', 'Dr. Ramos', '2025-10-11 07:24:46'),
(25, '1', 'STU1002', 'Maria Santos', 'Female', 20, 'Single', 'Catholic', 'No', '', '09181234567', 'Dorm B', '3', '2025-08-04', '2', 'Allergic rhinitis', 'Cetirizine 10mg', 'Sneezing, runny nose', 'Nasal congestion', 'Allergy test', 'Allergy confirmed', 'Avoid triggers', 'Dr. Cruz', '2025-10-11 07:24:46'),
(26, '2', 'EMP1001', 'Mark Lee', 'Male', 35, 'Married', 'Christian', 'No', '', '09192345678', 'Faculty Housing', '4', '2025-08-06', '1', 'Back pain', 'Ibuprofen 400mg', 'Lower back pain', 'Pain on movement', 'X-ray', 'Muscle strain', 'Physical therapy', 'Dr. Santos', '2025-10-11 07:24:46'),
(27, '1', 'STU1003', 'Angela Reyes', 'Female', 19, 'Single', 'Catholic', 'No', '', '09173456789', 'Dorm C', '2', '2025-08-08', '3', 'Migraine', 'Pain reliever', 'Headache for 2 days', 'No fever', 'CT Scan', 'Tension headache', 'Avoid stress', 'Dr. Ramos', '2025-10-11 07:24:46'),
(28, '1', 'STU1004', 'Carlos Dela Cruz', 'Male', 22, 'Single', 'Christian', 'No', '', '09184567890', 'Dorm D', '5', '2025-08-10', '2', 'Cough', 'Ambroxol', 'Cough for 1 week', 'Slight wheeze', 'Chest X-ray', 'Bronchitis', 'Antibiotic if needed', 'Dr. Reyes', '2025-10-11 07:24:46'),
(29, '2', 'EMP1002', 'Lisa Mendoza', 'Female', 40, 'Married', 'Catholic', 'No', '', '09195678901', 'Campus Staff Quarters', '1', '2025-08-13', '1', 'Hypertension', 'Amlodipine 5mg', 'Headache, dizziness', 'BP 150/100', 'BP monitoring', 'High BP', 'Lifestyle changes', 'Dr. Santos', '2025-10-11 07:24:46'),
(30, '1', 'STU1005', 'Daniel Cruz', 'Male', 18, 'Single', 'Christian', 'Yes', 'Jose Cruz', '09196789012', 'Dorm E', '3', '2025-08-15', '3', 'Sprained ankle', 'Cold compress', 'Twisted ankle during PE', 'Swollen ankle', 'Physical exam', 'Sprain', 'Rest 3 days', 'Dr. Ramos', '2025-10-11 07:24:46'),
(31, '1', 'STU1006', 'Julia Garcia', 'Female', 19, 'Single', 'Catholic', 'No', '', '09197890123', 'Dorm F', '4', '2025-08-17', '2', 'Sore throat', 'Lozenges', 'Pain swallowing', 'Red tonsils', 'Throat swab', 'Pharyngitis', 'Hydration, rest', 'Dr. Cruz', '2025-10-11 07:24:46'),
(33, '1', 'STU1007', 'Erica Lim', 'Female', 20, 'Single', 'Buddhist', 'No', '', '09199012345', 'Dorm G', '2', '2025-08-27', '3', 'Fever & cough', 'Paracetamol, fluids', 'Fever 38°C', 'Mild cough', 'CBC', 'Viral infection', 'Monitor temperature', 'Dr. Reyes', '2025-10-11 07:24:46'),
(34, '1', 'STU1008', 'Paolo Fernandez', 'Male', 21, 'Single', 'Christian', 'No', '', '09201234567', 'Dorm H', '2', '2025-09-01', '1', 'Fever', 'Paracetamol 500mg', 'Headache, chills', 'Temp 38.7°C', 'CBC', 'Viral', 'Rest & fluids', 'Dr. Ramos', '2025-10-11 07:24:46'),
(35, '1', 'STU1009', 'Grace Bautista', 'Female', 20, 'Single', 'Catholic', 'No', '', '09212345678', 'Dorm I', '3', '2025-09-03', '2', 'Cough', 'Carbocisteine', 'Cough, sore throat', 'No fever', 'Throat exam', 'Mild pharyngitis', 'Rest', 'Dr. Cruz', '2025-10-11 07:24:46'),
(36, '2', 'EMP1004', 'Henry Tan', 'Male', 45, 'Married', 'Christian', 'No', '', '09223456789', 'Admin Bldg', '4', '2025-09-04', '3', 'Joint pain', 'Pain reliever', 'Knee pain', 'Limited flexion', 'X-ray', 'Osteoarthritis', 'Regular exercise', 'Dr. Santos', '2025-10-11 07:24:46'),
(37, '1', 'STU1010', 'Anna Lopez', 'Female', 18, 'Single', 'Catholic', 'Yes', 'Laura Lopez', '09234567890', 'Dorm J', '5', '2025-09-06', '2', 'Cold', 'Decongestant', 'Runny nose', 'Mild congestion', 'Allergy test', 'Allergic rhinitis', 'Avoid dust', 'Dr. Reyes', '2025-10-11 07:24:46'),
(38, '1', 'STU1011', 'Patrick Cruz', 'Male', 22, 'Single', 'Christian', 'No', '', '09245678901', 'Dorm K', '3', '2025-09-08', '1', 'Fever & headache', 'Paracetamol', 'Fever 39°C', 'Tired look', 'CBC', 'Viral', 'Bed rest', 'Dr. Ramos', '2025-10-11 07:24:46'),
(39, '2', 'EMP1005', 'Martha Reyes', 'Female', 33, 'Married', 'Christian', 'No', '', '09256789012', 'Registrar Office', '1', '2025-09-11', '2', 'Fatigue', 'Multivitamins', 'Feeling tired', 'Normal exam', 'Blood test', 'Anemia', 'Iron supplements', 'Dr. Cruz', '2025-10-11 07:24:46'),
(40, '1', 'STU1012', 'Joseph Ong', 'Male', 20, 'Single', 'Buddhist', 'No', '', '09267890123', 'Dorm L', '2', '2025-09-15', '1', 'Cough', 'Ambroxol', 'Cough for 3 days', 'Normal vitals', 'Chest auscultation', 'Bronchitis', 'Rest & meds', 'Dr. Santos', '2025-10-11 07:24:46'),
(41, '1', 'STU1013', 'Bea Ramirez', 'Female', 21, 'Single', 'Catholic', 'No', '', '09278901234', 'Dorm M', '4', '2025-09-18', '2', 'Sore throat', 'Lozenges', 'Pain swallowing', 'Red tonsils', 'Throat swab', 'Pharyngitis', 'Hydration', 'Dr. Cruz', '2025-10-11 07:24:46'),
(43, '1', 'STU1014', 'Nicole Torres', 'Female', 19, 'Single', 'Christian', 'No', '', '09290123456', 'Dorm N', '2', '2025-09-27', '1', 'Fever & cough', 'Paracetamol', 'Fever 38.3°C', 'Mild cough', 'CBC', 'Viral infection', 'Monitor symptoms', 'Dr. Reyes', '2025-10-11 07:24:46'),
(44, '1', '231232', 'Jomari Recalde', 'Male', 23, 'Single', 'Catholic', 'No', '', '2222222222', 'assfsdfsdfsdfsdf', '1', '2025-10-13', '15', 'sdsdfsdfsdfsd', 'dfsdfsdfsdfsdf', 'sdfsdfsdfsdf', 'sfsdfsdfsdfsd', 'dfsdfsdfsdf', 'dfsdfsdfsdfsdf', 'adsfsdfsdfsdfsdf', 'Francis Alterado', '2025-10-13 00:31:50');

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
(1, 'admin', 'logout', '2025-10-11 05:18:44', '2025-10-11 05:30:23', '11m 39s', 'Logged Out', '2025-10-11 03:30:23'),
(2, 'admin', 'logout', '2025-10-11 05:30:28', '2025-10-11 05:48:12', '17m 44s', 'Logged Out', '2025-10-11 03:48:12'),
(3, 'admin', 'logout', '2025-10-11 05:48:16', '2025-10-11 06:44:17', '56m 1s', 'Logged Out', '2025-10-11 04:44:17'),
(4, 'admin', 'logout', '2025-10-11 06:44:21', '2025-10-11 06:47:33', '3m 12s', 'Logged Out', '2025-10-11 04:47:33'),
(5, 'admin', 'logout', '2025-10-11 06:47:36', '2025-10-11 06:49:04', '1m 28s', 'Logged Out', '2025-10-11 04:49:04'),
(6, 'jomari', 'logout', '2025-10-11 06:49:32', '2025-10-11 06:53:03', '3m 31s', 'Logged Out', '2025-10-11 04:53:03'),
(7, 'admin', 'logout', '2025-10-11 06:54:43', '2025-10-11 06:55:02', '0m 19s', 'Logged Out', '2025-10-11 04:55:02'),
(8, 'admin', 'logout', '2025-10-11 06:55:30', '2025-10-11 06:58:33', '3m 3s', 'Logged Out', '2025-10-11 04:58:33'),
(9, 'admin', 'logout', '2025-10-11 07:38:55', '2025-10-11 08:14:40', '35m 45s', 'Logged Out', '2025-10-11 06:14:40'),
(10, 'jomari', 'logout', '2025-10-11 08:19:31', '2025-10-11 08:19:36', '0m 5s', 'Logged Out', '2025-10-11 06:19:36'),
(11, 'jomari', 'logout', '2025-10-11 08:19:40', '2025-10-11 08:25:40', '6m 0s', 'Logged Out', '2025-10-11 06:25:40'),
(12, 'admin', 'logout', '2025-10-11 08:25:44', '2025-10-11 08:26:14', '0m 30s', 'Logged Out', '2025-10-11 06:26:14'),
(13, 'admin', 'logout', '2025-10-11 08:26:17', '2025-10-11 08:42:44', '16m 27s', 'Logged Out', '2025-10-11 06:42:44'),
(14, 'admin', 'logout', '2025-10-11 08:42:47', '2025-10-11 08:42:54', '0m 7s', 'Logged Out', '2025-10-11 06:42:54'),
(15, 'jomari', 'logout', '2025-10-11 08:42:59', '2025-10-11 09:05:52', '22m 53s', 'Logged Out', '2025-10-11 07:05:52'),
(16, 'admin', 'logout', '2025-10-12 03:04:00', '2025-10-12 03:09:49', '5m 49s', 'Logged Out', '2025-10-12 01:09:49'),
(17, 'jomari', 'login', '2025-10-12 03:41:47', '2025-10-12 03:42:01', '0m 14s', 'Logged Out', '2025-10-12 01:41:47'),
(18, 'admin', 'login', '2025-10-12 03:42:06', '2025-10-12 04:48:41', '66m 35s', 'Logged Out', '2025-10-12 01:42:06'),
(19, 'jomari', 'login', '2025-10-12 04:48:45', NULL, NULL, 'Logged In', '2025-10-12 02:48:45'),
(20, 'admin', 'login', '2025-10-12 12:06:29', '2025-10-12 12:06:43', '0m 14s', 'Logged Out', '2025-10-12 10:06:29'),
(21, 'jomari', 'login', '2025-10-12 12:06:48', NULL, NULL, 'Logged In', '2025-10-12 10:06:48'),
(22, 'admin', 'login', '2025-10-13 02:03:18', '2025-10-13 02:23:03', '19m 45s', 'Logged Out', '2025-10-13 00:03:18'),
(23, 'admin', 'login', '2025-10-13 02:23:11', NULL, NULL, 'Logged In', '2025-10-13 00:23:11'),
(24, 'admin', 'login', '2025-10-13 03:02:24', NULL, NULL, 'Logged In', '2025-10-13 01:02:24'),
(25, 'admin', 'login', '2025-10-13 04:35:39', NULL, NULL, 'Logged In', '2025-10-13 02:35:39');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `patient_types`
--
ALTER TABLE `patient_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
