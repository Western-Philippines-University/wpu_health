-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2025 at 05:08 AM
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
(64, 'Jacob mabag', 23, 'Male', 'Single', 'sasdasd', '2025-10-20', '', 1, 1, 'hello', '', '', '2025-10-20', '23123123', '2025-10-20 14:31:17', '2025-10-20 14:43:40', '0'),
(65, 'Jomari Recalde', 23, 'Male', 'Single', '2323232', '2025-10-21', '', 1, 1, 'KKKK', '', 'WPU-MC-2025-7660', '2025-10-21', '123123123', '2025-10-20 14:44:07', '2025-10-21 02:48:03', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medical_certificates`
--
ALTER TABLE `medical_certificates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `medical_certificates`
--
ALTER TABLE `medical_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
