-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 05:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `papan_health_center`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangay`
--

CREATE TABLE `barangay` (
  `barangay_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay`
--

INSERT INTO `barangay` (`barangay_id`, `name`, `date_created`) VALUES
(1, 'Papan', '2025-09-05 11:48:08'),
(2, 'Cantolaroy', '2025-09-05 11:48:17'),
(3, 'Dugoan', '2025-09-05 11:48:31'),
(4, 'Bae', '2025-09-19 15:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `children`
--

CREATE TABLE `children` (
  `child_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `date_of_birth` date NOT NULL,
  `place_of_birth` varchar(150) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `sex` enum('Male','Female','','') NOT NULL,
  `mother_name` varchar(150) NOT NULL,
  `father_name` varchar(150) NOT NULL,
  `birth_height` decimal(5,2) NOT NULL,
  `birth_weight` decimal(5,2) NOT NULL,
  `family_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `children`
--

INSERT INTO `children` (`child_id`, `full_name`, `date_of_birth`, `place_of_birth`, `barangay_id`, `sex`, `mother_name`, `father_name`, `birth_height`, `birth_weight`, `family_id`, `date_created`) VALUES
(1, 'Ken Takakura Jr.', '2002-10-18', 'Cebu City', 2, 'Male', 'Ashima Takakura', 'Ken Takakura Sr.', 50.00, 23.00, 1, '2025-09-11 06:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `child_immunizations`
--

CREATE TABLE `child_immunizations` (
  `immunization_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `vaccine_id` int(11) NOT NULL,
  `dose_number` int(11) NOT NULL,
  `date_given` date NOT NULL,
  `lot_number` int(11) NOT NULL,
  `vaccinator` varchar(150) NOT NULL,
  `place_given` varchar(150) NOT NULL,
  `remarks` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `child_immunizations`
--

INSERT INTO `child_immunizations` (`immunization_id`, `child_id`, `vaccine_id`, `dose_number`, `date_given`, `lot_number`, `vaccinator`, `place_given`, `remarks`, `created_at`) VALUES
(3, 1, 7, 4, '2025-09-11', 0, 'John Roa', 'PapanHealth Center', 'tyuyteriu5', '2025-09-11 08:24:11'),
(4, 1, 8, 4, '2025-09-16', 0, 'John Roa', 'PapanHealth Center', '454trwetwtwt', '2025-09-17 04:34:40'),
(5, 1, 8, 5, '2025-09-16', 0, 'John Roa', 'PapanHealth Center', '57hgjhgjhg', '2025-09-17 04:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `family_number`
--

CREATE TABLE `family_number` (
  `family_id` int(11) NOT NULL,
  `family_no` varchar(50) NOT NULL,
  `family_head` varchar(150) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family_number`
--

INSERT INTO `family_number` (`family_id`, `family_no`, `family_head`, `barangay_id`, `date_created`) VALUES
(1, '01', 'Ken Takakura Sr.', 2, '2025-09-11 05:47:33');

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `record_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `systolic_bp` int(11) NOT NULL,
  `diastolic_bp` int(11) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `pulse` int(11) NOT NULL,
  `assessment` varchar(255) NOT NULL,
  `plan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`record_id`, `patient_id`, `date`, `systolic_bp`, `diastolic_bp`, `height`, `weight`, `pulse`, `assessment`, `plan`) VALUES
(1, 0, '2025-08-28', 110, 60, 160.00, 44.00, 33, 'dfrgdsfgh', 'dfhgdfgdg'),
(3, 0, '2025-08-29', 110, 60, 160.00, 44.00, 45, 'dfrgdsfgh', 'dfhgdfgdg'),
(4, 0, '2025-08-29', 1106, 756, 160.00, 44.00, 65, 'dfrgdsfgh', 'dfhgdfgdg'),
(6, 2, '2025-08-29', 110, 75, 160.00, 44.00, 55, 'dfrgdsfgh', 'dfhgdfgdg'),
(7, 3, '2025-08-29', 110, 60, 160.00, 44.00, 44, 'dfrgdsfgh', 'dfhgdfgdg'),
(8, 9, '2025-09-09', 110, 60, 160.00, 44.00, 22, 'effdsfdsf', 'sdfdsf');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `medicine_name`, `description`, `stock`, `expiry_date`, `date_added`) VALUES
(9, 'Amoxicillin', '4t4trwtwt', 7, '2026-05-19', '2025-09-19 15:32:59'),
(10, 'Biogesic', 'dfhgdhh', 51, '2026-10-20', '2025-09-19 15:34:44');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_given`
--

CREATE TABLE `medicine_given` (
  `give_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity_given` int(11) NOT NULL,
  `date_given` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_given`
--

INSERT INTO `medicine_given` (`give_id`, `patient_id`, `medicine_id`, `quantity_given`, `date_given`) VALUES
(2, 2, 2, 3, '2025-08-30 03:14:24'),
(3, 4, 5, 2, '2025-08-30 04:00:58'),
(4, 9, 3, 2, '2025-09-10 23:59:38'),
(5, 10, 10, 8, '2025-09-19 15:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('male','female','','') NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `full_name`, `age`, `gender`, `barangay_id`, `date_created`) VALUES
(9, 'Ken Takakura', 22, 'male', 1, '2025-09-10 06:38:33'),
(10, 'Momo Ayase', 22, 'female', 1, '2025-09-10 06:40:42'),
(11, 'Aira Shiraturi', 22, 'female', 2, '2025-09-11 13:02:08');

-- --------------------------------------------------------

--
-- Table structure for table `prenatal_records`
--

CREATE TABLE `prenatal_records` (
  `prenatal_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `lmp` date NOT NULL,
  `edd` date NOT NULL,
  `gestational_age` varchar(50) NOT NULL,
  `blood_pressure` varchar(100) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `fetal_heart_rate` varchar(10) NOT NULL,
  `fundal_height` varchar(10) NOT NULL,
  `complaints` text NOT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text NOT NULL,
  `next_visit` date NOT NULL,
  `created-at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prenatal_records`
--

INSERT INTO `prenatal_records` (`prenatal_id`, `patient_id`, `visit_date`, `lmp`, `edd`, `gestational_age`, `blood_pressure`, `weight`, `height`, `fetal_heart_rate`, `fundal_height`, `complaints`, `diagnosis`, `treatment`, `next_visit`, `created-at`) VALUES
(3, 3, '2025-09-04', '2025-09-06', '2025-09-25', '33', '33', 44.00, 160.00, '33', '33', 'dfgdfgf', 'dsfgdfg', 'dfgdfgdfg', '2025-09-26', '2025-09-04 09:05:37'),
(4, 10, '2025-09-09', '2025-09-10', '2025-09-19', '33', '33', 44.00, 160.00, '33', '33', 'zdfdsfdddd', 'sdfsdf', 'sdfdsf', '2025-09-13', '2025-09-10 06:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `stock_request`
--

CREATE TABLE `stock_request` (
  `request_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_request`
--

INSERT INTO `stock_request` (`request_id`, `medicine_id`, `quantity`, `request_date`) VALUES
(1, 8, 12, '2025-09-11 13:48:55'),
(2, 3, 30, '2025-09-12 07:22:36'),
(3, 4, 22, '2025-09-12 07:28:35'),
(4, 10, 10, '2025-09-19 15:35:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `role` enum('admin','staff','','') NOT NULL,
  `security_question` varchar(255) NOT NULL,
  `security_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `role`, `security_question`, `security_answer`) VALUES
(8, 'admin', '$2y$10$CL0ItiuoTkbdECoZFGifbezHp.ZcRp/N5ePrRQMtrpmxJ60gPuFIO', 'Admin', 'admin', 'What was the name of your first pet?', 'bulldog'),
(12, 'okarun', '$2y$10$zEzjSwnaYasYmOxuP3fTnuc1FtfNOJLMz2d3iBNP/5scERLFF2E9G', 'Ken Takakura', 'admin', 'What city were you born in?', 'japan'),
(13, 'fjgripo', '$2y$10$WC3sLNYHXIy/HYjOUp/PP.Ma4amQqtUlizDxgoUwnl4iZjsqHM8rW', 'Flor Jhon Gripo', 'staff', 'What city were you born in?', 'cebu city'),
(14, 'fjgripo', '$2y$10$awpLMzSgwVvFsLCCqz2IEurASFruK505.8VPMa0Q/jhgMkXeMNnMm', 'Flor Jhon Gripo', 'staff', 'What city were you born in?', 'cebu city'),
(15, 'fjgripo', '$2y$10$pg31wJBauTK5TYYTXmIlRun/AaH8Rf6gO3YFC8BjYXbJAbC3DXmJe', 'Flor Jhon Gripo', 'staff', 'What city were you born in?', 'cebu city');

-- --------------------------------------------------------

--
-- Table structure for table `vaccines`
--

CREATE TABLE `vaccines` (
  `vaccine_id` int(11) NOT NULL,
  `vaccine_name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_doses` int(11) NOT NULL,
  `recommended_ages` varchar(100) NOT NULL,
  `expiry_date` date NOT NULL,
  `date_received` date NOT NULL,
  `lot_number` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccines`
--

INSERT INTO `vaccines` (`vaccine_id`, `vaccine_name`, `description`, `supplier_id`, `quantity`, `total_doses`, `recommended_ages`, `expiry_date`, `date_received`, `lot_number`) VALUES
(7, 'Hepatitis B (HBV) Vacciner', 'yoiuyuoi', 4, 34, 2, '8-18', '2026-10-13', '2025-09-10', 'HBV-2024-001'),
(8, 'OPV (Oral Polio Vaccine)', 'awrfqtr', 4, 23, 3, '8-18', '2027-10-28', '2025-09-13', 'RV-2024-067'),
(9, 'DTP (Diphtheria, Tetanus, Pertussis)', '67586576', 4, 45, 4, '8-18', '2029-10-15', '2025-09-13', 'DTP-2024-045');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_stock_requests`
--

CREATE TABLE `vaccine_stock_requests` (
  `vaccine_request_id` int(11) NOT NULL,
  `vaccine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccine_stock_requests`
--

INSERT INTO `vaccine_stock_requests` (`vaccine_request_id`, `vaccine_id`, `quantity`, `request_date`) VALUES
(1, 7, 11, '2025-09-13 07:31:00'),
(2, 7, 2, '2025-09-13 07:32:58'),
(3, 8, 23, '2025-09-13 07:54:21'),
(4, 8, 45, '2025-09-13 22:30:58'),
(5, 7, 55, '2025-09-13 13:48:48');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_suppliers`
--

CREATE TABLE `vaccine_suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `contact_person` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccine_suppliers`
--

INSERT INTO `vaccine_suppliers` (`supplier_id`, `supplier_name`, `contact_person`, `phone_number`, `address`, `date_created`) VALUES
(4, 'Poblacion Health Station', 'Johnny sins', '09094219176', 'Manatad,Sibonga,Cebu', '2025-09-02 10:57:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangay`
--
ALTER TABLE `barangay`
  ADD PRIMARY KEY (`barangay_id`);

--
-- Indexes for table `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`child_id`);

--
-- Indexes for table `child_immunizations`
--
ALTER TABLE `child_immunizations`
  ADD PRIMARY KEY (`immunization_id`);

--
-- Indexes for table `family_number`
--
ALTER TABLE `family_number`
  ADD PRIMARY KEY (`family_id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`);

--
-- Indexes for table `medicine_given`
--
ALTER TABLE `medicine_given`
  ADD PRIMARY KEY (`give_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `prenatal_records`
--
ALTER TABLE `prenatal_records`
  ADD PRIMARY KEY (`prenatal_id`);

--
-- Indexes for table `stock_request`
--
ALTER TABLE `stock_request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vaccines`
--
ALTER TABLE `vaccines`
  ADD PRIMARY KEY (`vaccine_id`);

--
-- Indexes for table `vaccine_stock_requests`
--
ALTER TABLE `vaccine_stock_requests`
  ADD PRIMARY KEY (`vaccine_request_id`);

--
-- Indexes for table `vaccine_suppliers`
--
ALTER TABLE `vaccine_suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangay`
--
ALTER TABLE `barangay`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `children`
--
ALTER TABLE `children`
  MODIFY `child_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `child_immunizations`
--
ALTER TABLE `child_immunizations`
  MODIFY `immunization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `family_number`
--
ALTER TABLE `family_number`
  MODIFY `family_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `medicine_given`
--
ALTER TABLE `medicine_given`
  MODIFY `give_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `prenatal_records`
--
ALTER TABLE `prenatal_records`
  MODIFY `prenatal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_request`
--
ALTER TABLE `stock_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `vaccines`
--
ALTER TABLE `vaccines`
  MODIFY `vaccine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vaccine_stock_requests`
--
ALTER TABLE `vaccine_stock_requests`
  MODIFY `vaccine_request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vaccine_suppliers`
--
ALTER TABLE `vaccine_suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
