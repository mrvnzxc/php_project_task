-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2024 at 06:13 AM
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
-- Database: `taskmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `id` int(11) NOT NULL,
  `username` varchar(222) NOT NULL,
  `pass` varchar(222) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tbl`
--

INSERT INTO `admin_tbl` (`id`, `username`, `pass`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `confirm_task_tbl`
--

CREATE TABLE `confirm_task_tbl` (
  `id` int(11) NOT NULL,
  `task` varchar(255) DEFAULT NULL,
  `taskdescription` text DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `submitdate` date NOT NULL DEFAULT current_timestamp(),
  `employee` int(11) DEFAULT NULL,
  `department` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `confirm_task_tbl`
--

INSERT INTO `confirm_task_tbl` (`id`, `task`, `taskdescription`, `startdate`, `remarks`, `enddate`, `submitdate`, `employee`, `department`) VALUES
(5, 'HAHA', 'HAHAHA', '2024-11-07', 'Completed', '2024-11-16', '2024-11-11', 4, 3),
(6, 'HAH', 'AHAHAHA', '2024-11-07', 'Completed', '2024-11-08', '2024-11-11', 4, 3),
(7, 'HAH', 'AHAHAHA', '2024-11-07', 'Completed', '2024-11-08', '2024-11-11', 4, 3),
(8, 'HAH', 'AHAHAHA', '2024-11-07', 'Completed', '2024-11-08', '2024-11-11', 4, 3),
(14, 'ajjaja', 'jajajja', '2024-11-09', 'Completed', '2024-11-23', '2024-11-11', 4, 3),
(15, 'YHAHA', 'HAHAHA', '2024-11-07', 'Completed', '2024-11-22', '2024-11-11', 4, 3),
(24, 'HA', 'HAHAHA', '2024-11-11', 'Completed', '2024-11-11', '2024-11-11', 1, 4),
(25, 'HAH', 'AHAHA', '2024-11-11', 'Completed', '2024-11-30', '2024-11-11', 1, 4),
(29, 'haha', 'hahaha', '2024-11-11', 'Did Not Finish', '2024-11-11', '2024-11-13', 1, 4),
(35, 'HAHA', 'HAHA', '2024-11-13', 'Did Not Finish', '2024-11-13', '2024-11-13', 4, 3),
(38, 'gagagagagaga', 'gagagga', '2024-11-14', 'DID NOT FINISH', '2024-11-14', '2024-11-14', 4, 3),
(39, 'hahaha', 'haha', '2024-11-14', 'Did Not Finish', '2024-11-14', '2024-11-14', 5, 1),
(41, 'gha', 'gagaga', '2024-11-14', 'Abandoned', '2024-11-23', '2024-11-14', 4, 3),
(45, 'haha', 'hahaha', '2024-11-15', 'Did Not Finish', '2024-11-15', '2024-11-15', 1, 4),
(46, 'MINI SYSTEM', 'TO BE PASSED ON DECEMBER 15', '2024-11-14', 'Completed', '2024-12-19', '2024-11-16', 1, 1),
(47, 'ha', 'hahaha', '2024-11-15', 'Completed', '2024-11-22', '2024-11-16', 5, 1),
(48, 'haha', 'haha', '2024-11-16', 'Did Not Finish', '2024-11-16', '2024-11-16', 3, 1),
(49, 'HA', 'HAHAHAHA', '2024-11-16', 'Did Not Finish', '2024-11-16', '2024-11-16', 3, 1),
(50, '23423', '4324234324321', '2024-11-16', 'Completed', '2024-11-30', '2024-11-16', 3, 1),
(51, 'GA', 'AGAGA', '2024-11-16', 'Completed', '2024-11-30', '2024-11-17', 1, 4),
(52, 'Make a basic payroll', 'Basic payroll for small business organization (e.g. cofee shops)', '2024-11-17', 'Completed', '2025-01-17', '2024-11-17', 1, 4),
(53, 'ga', 'SA MAY LIKOD', '2024-11-17', 'Completed', '2024-11-30', '2024-11-18', 1, 4),
(54, 'EFFSFS', 'SEFESFES', '2024-11-19', 'Did Not Finish', '2024-11-19', '2024-11-19', 1, 4),
(55, 'DAWD', 'AWDAWDAW', '2024-11-16', 'Completed', '2024-11-30', '2024-11-19', 4, 3),
(56, 'COUNT BOOKS iIN HS LIBRARY', 'COUNT BOOKS IN ALL SHELVES', '2024-11-20', 'Completed', '2024-11-30', '2024-11-20', 4, 3),
(57, 'daw', 'awdwadaw', '2024-11-20', 'Did Not Finish', '2024-11-20', '2024-11-20', 4, 3),
(58, 'DAWD', 'AWDAWDAW', '2024-11-16', 'Completed', '2024-11-30', '2024-11-20', 4, 3),
(59, 'awd', 'dwadwa', '2024-11-29', 'Did Not Finish', '2024-11-20', '2024-11-20', 3, 1),
(60, 'wadawdaw', 'dwadawd', '2024-11-30', 'Did Not Finish', '2024-11-15', '2024-11-20', 4, 3),
(61, 'dawdwa', 'dawdaw', '2024-11-30', 'Did Not Finish', '2024-11-20', '2024-11-20', 1, 2),
(62, 'dawdwad', 'dawdwa', '2024-11-20', 'Did Not Finish', '2024-11-15', '2024-11-20', 1, 2),
(63, 'dawd', 'awdwa', '2024-11-20', 'Did Not Finish', '2024-11-14', '2024-11-20', 1, 2),
(64, 'dwadwa', 'dawdaw', '2024-11-20', 'Did Not Finish', '2024-11-09', '2024-11-20', 3, 1),
(65, 'awdwa', 'dawdwa', '2024-11-20', 'Did Not Finish', '2024-11-09', '2024-11-20', 1, 2),
(66, 'dawd', 'wadawdaw', '2024-11-20', 'Did Not Finish', '2024-11-09', '2024-11-20', 1, 2),
(67, 'dawd', 'wadawdaw', '2024-11-20', 'Did Not Finish', '2024-11-09', '2024-11-20', 1, 2),
(68, 'ha', 'hahaha', '2024-11-16', 'Did Not Finish', '2024-11-22', '2024-11-22', 3, 1),
(69, 'awdaw', 'dwadwa', '2024-11-22', 'Did Not Finish', '2024-11-22', '2024-11-22', 3, 1),
(70, 'wad', 'awdwa', '2024-11-09', 'Did Not Finish', '2024-11-22', '2024-11-22', 1, 2),
(71, '12312312', '312321312132123', '2024-11-25', 'Completed', '2024-11-30', '2024-11-25', 1, 2),
(72, '12321', '312312321', '2024-11-26', 'Did Not Finish', '2024-11-26', '2024-11-26', 1, 2),
(73, '12312', '3213123123', '2024-11-29', 'Completed', '2024-11-30', '2024-11-27', 1, 2),
(74, 'AWDWA', 'DWADA', '2024-11-28', 'Completed', '2024-11-30', '2024-11-27', 1, 2),
(75, '12312', '321321312', '2024-11-28', 'Completed', '2024-11-29', '2024-11-27', 1, 1),
(76, '12312', '312312312321', '2024-11-26', 'Completed', '2024-11-30', '2024-11-27', 1, 2),
(77, 'dwad', 'awdawd', '2024-11-22', 'Completed', '2024-11-30', '2024-11-27', 1, 2),
(78, '1323', '21312312', '2024-11-27', 'Completed', '2024-11-30', '2024-11-28', 1, 4),
(79, 'COUNT MACHINE', 'IN ALL COMPUTER LAB', '2024-11-28', 'Completed', '2024-11-30', '2024-11-28', 1, 4),
(80, '123213', '213213213', '2024-11-28', 'Completed', '2024-11-30', '2024-11-28', 1, 4),
(81, '123123', '12312312321312', '2024-11-30', 'Did Not Finish', '2024-11-30', '2024-12-01', 5, 2),
(82, '123', '12312321', '2024-11-28', 'Did Not Finish', '2024-11-30', '2024-12-01', 3, 1),
(83, '123', '1312312', '2024-11-24', 'Did Not Finish', '2024-11-30', '2024-12-01', 5, 2),
(84, 'dawdwa', 'dwadwa awda', '2024-11-23', 'Did Not Finish', '2024-11-30', '2024-12-01', 4, 3),
(85, '3123', '213213123', '2024-11-28', 'Did Not Finish', '2024-11-30', '2024-12-01', 1, 4),
(86, '123123', '132131', '2024-11-29', 'Did Not Finish', '2024-11-30', '2024-12-02', 1, 1),
(87, '123', '123', '2024-12-02', 'Completed', '2024-12-06', '2024-12-03', 1, 1),
(88, 'HAHA', 'HAHAHA', '2024-12-04', 'Completed', '2024-12-14', '2024-12-04', 1, 4),
(89, '123', '456', '2024-12-06', 'Completed', '2024-12-27', '2024-12-07', 1, 4),
(90, '456', '789', '2024-12-06', 'Abandoned', '2024-12-28', '2024-12-07', 1, 4),
(91, '123', '456', '2024-12-06', 'Did Not Finish', '2024-12-07', '2024-12-07', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `department_tbl`
--

CREATE TABLE `department_tbl` (
  `id` int(11) NOT NULL,
  `department` varchar(222) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_tbl`
--

INSERT INTO `department_tbl` (`id`, `department`) VALUES
(1, 'Financer'),
(2, 'Registrar'),
(3, 'Library'),
(4, 'MIS'),
(12, 'CBIT'),
(13, 'CSSH'),
(14, 'COED'),
(15, 'CRIMS');

-- --------------------------------------------------------

--
-- Table structure for table `employee_tbl`
--

CREATE TABLE `employee_tbl` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `department` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL DEFAULT '123'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_tbl`
--

INSERT INTO `employee_tbl` (`id`, `fname`, `lname`, `department`, `username`, `pass`) VALUES
(1, 'John Marvin', 'Bautista', 4, 'marvin', 'marvin'),
(3, 'Crisnovie', 'Tandoy', 1, 'crisno123', 'crisno123'),
(4, 'John', 'Doe', 3, 'johndoe', 'johndoe123'),
(5, 'Maria Leonora', 'Bautista', 2, 'nora123', 'nora123'),
(28, 'Kaye Angeli', 'Abad', 3, 'kaye123', '123'),
(29, 'Carl Ian', 'Macapas', 2, 'carlian', '123'),
(30, 'Ethan John', 'Salavedra', 1, 'ethan', '123'),
(31, 'Crisno', 'Tandoy', 1, 'tandoy', '123'),
(32, 'Jashmier', 'Cutamora', 12, 'jash123', '123');

-- --------------------------------------------------------

--
-- Table structure for table `finish_task_tbl`
--

CREATE TABLE `finish_task_tbl` (
  `id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `taskdescription` text DEFAULT NULL,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `submitdate` datetime DEFAULT current_timestamp(),
  `department` int(11) NOT NULL,
  `employee` int(11) NOT NULL,
  `submission_date` date NOT NULL DEFAULT current_timestamp(),
  `remarks` varchar(100) DEFAULT NULL,
  `comments` varchar(500) DEFAULT 'No Comments Made'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_tbl`
--

CREATE TABLE `task_tbl` (
  `id` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `task` varchar(222) NOT NULL,
  `taskdescription` text NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `remarks` varchar(222) DEFAULT 'Haven''t Started',
  `employee` int(11) DEFAULT NULL,
  `comments` varchar(500) DEFAULT 'No Comments Yet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_tbl`
--

INSERT INTO `task_tbl` (`id`, `department`, `task`, `taskdescription`, `startdate`, `enddate`, `remarks`, `employee`, `comments`) VALUES
(207, 4, '123', '4567', '2024-12-06 22:05:00', '2024-12-28 22:05:00', 'Haven\'t Started', 1, 'No Comments Yet'),
(210, 2, '123', '1231231', '2024-12-10 01:43:00', '2024-12-14 01:43:00', 'Haven\'t Started', 29, 'No Comments Yet');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `confirm_task_tbl`
--
ALTER TABLE `confirm_task_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department` (`department`),
  ADD KEY `employee` (`employee`);

--
-- Indexes for table `department_tbl`
--
ALTER TABLE `department_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_tbl`
--
ALTER TABLE `employee_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_tbl_ibfk_1` (`department`);

--
-- Indexes for table `finish_task_tbl`
--
ALTER TABLE `finish_task_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `finish_task_tbl_ibfk_2` (`employee`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `task_tbl`
--
ALTER TABLE `task_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_tbl_ibfk_2` (`employee`),
  ADD KEY `task_tbl_ibfk_1` (`department`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `confirm_task_tbl`
--
ALTER TABLE `confirm_task_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `department_tbl`
--
ALTER TABLE `department_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `employee_tbl`
--
ALTER TABLE `employee_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `finish_task_tbl`
--
ALTER TABLE `finish_task_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `task_tbl`
--
ALTER TABLE `task_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `confirm_task_tbl`
--
ALTER TABLE `confirm_task_tbl`
  ADD CONSTRAINT `confirm_task_tbl_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `confirm_task_tbl_ibfk_2` FOREIGN KEY (`employee`) REFERENCES `employee_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee_tbl`
--
ALTER TABLE `employee_tbl`
  ADD CONSTRAINT `employee_tbl_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `finish_task_tbl`
--
ALTER TABLE `finish_task_tbl`
  ADD CONSTRAINT `finish_task_tbl_ibfk_2` FOREIGN KEY (`employee`) REFERENCES `employee_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `finish_task_tbl_ibfk_3` FOREIGN KEY (`department`) REFERENCES `department_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_tbl`
--
ALTER TABLE `task_tbl`
  ADD CONSTRAINT `task_tbl_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_tbl_ibfk_2` FOREIGN KEY (`employee`) REFERENCES `employee_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
