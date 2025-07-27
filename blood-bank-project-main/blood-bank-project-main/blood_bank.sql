-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2025 at 03:45 PM
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
-- Database: `blood_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_banks`
--

CREATE TABLE `blood_banks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_banks`
--

INSERT INTO `blood_banks` (`id`, `name`, `email`, `password`, `address`, `city`, `state`, `country`, `contact_number`, `license_number`, `status`, `created_at`, `latitude`, `longitude`) VALUES
(1, 'Mumbai Blood Bank Center', 'mumbai@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Blood Center Road', 'Mumbai', 'Maharashtra', 'India', '9876543210', 'BB001', 'active', '2025-04-24 06:48:11', NULL, NULL),
(2, 'Delhi Blood Bank Center', 'delhi@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Blood Center Avenue', 'Delhi', 'Delhi', 'India', '9876543211', 'BB002', 'active', '2025-04-24 06:48:11', NULL, NULL),
(3, 'Bangalore Blood Bank Center', 'bangalore@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Blood Center Street', 'Bangalore', 'Karnataka', 'India', '9876543212', 'BB003', 'active', '2025-04-24 06:48:11', NULL, NULL),
(4, 'Chennai Blood Bank Center', 'chennai@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '321 Blood Center Lane', 'Chennai', 'Tamil Nadu', 'India', '9876543213', 'BB004', 'active', '2025-04-24 06:48:11', NULL, NULL),
(5, 'Kolkata Blood Bank Center', 'kolkata@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '654 Blood Center Road', 'Kolkata', 'West Bengal', 'India', '9876543214', 'BB005', 'active', '2025-04-24 06:48:11', NULL, NULL),
(6, 'Hyderabad Blood Bank Center', 'hyderabad@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '987 Blood Center Avenue', 'Hyderabad', 'Telangana', 'India', '9876543215', 'BB006', 'active', '2025-04-24 06:48:11', NULL, NULL),
(7, 'Pune Blood Bank Center', 'pune@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '147 Blood Center Street', 'Pune', 'Maharashtra', 'India', '9876543216', 'BB007', 'active', '2025-04-24 06:48:11', NULL, NULL),
(8, 'Ahmedabad Blood Bank Center', 'ahmedabad@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '258 Blood Center Lane', 'Ahmedabad', 'Gujarat', 'India', '9876543217', 'BB008', 'active', '2025-04-24 06:48:11', NULL, NULL),
(9, 'Amaravati Blood Bank Center', 'Amaravati\n@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '369 Blood Center Road', 'Amaravati\n', 'Andhra Pradesh', 'India', '9876543218', 'BB009', 'active', '2025-04-24 06:48:11', NULL, NULL),
(10, 'Lucknow Blood Bank Center', 'lucknow@bloodbank.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '741 Blood Center Avenue', 'Lucknow', 'Uttar Pradesh', 'India', '9876543219', 'BB010', 'active', '2025-04-24 06:48:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blood_bank_inventory`
--

CREATE TABLE `blood_bank_inventory` (
  `id` int(11) NOT NULL,
  `blood_bank_id` int(11) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_bank_inventory`
--

INSERT INTO `blood_bank_inventory` (`id`, `blood_bank_id`, `blood_group`, `quantity`, `last_updated`) VALUES
(1, 1, 'A+', 1000, '2025-04-26 01:19:52'),
(2, 2, 'A+', 1000, '2025-04-26 01:19:52'),
(3, 3, 'A+', 1000, '2025-04-26 01:19:52'),
(4, 4, 'A+', 1000, '2025-04-26 01:19:52'),
(5, 5, 'A+', 1000, '2025-04-26 01:19:52'),
(6, 6, 'A+', 1000, '2025-04-26 01:19:52'),
(7, 7, 'A+', 1000, '2025-04-26 01:19:52'),
(8, 8, 'A+', 1000, '2025-04-26 01:19:52'),
(9, 9, 'A+', 994, '2025-04-26 01:33:53'),
(10, 10, 'A+', 1000, '2025-04-26 01:19:52'),
(11, 1, 'A-', 1000, '2025-04-26 01:19:52'),
(12, 2, 'A-', 1000, '2025-04-26 01:19:52'),
(13, 3, 'A-', 1000, '2025-04-26 01:19:52'),
(14, 4, 'A-', 1000, '2025-04-26 01:19:52'),
(15, 5, 'A-', 1000, '2025-04-26 01:19:52'),
(16, 6, 'A-', 1000, '2025-04-26 01:19:52'),
(17, 7, 'A-', 1000, '2025-04-26 01:19:52'),
(18, 8, 'A-', 1000, '2025-04-26 01:19:52'),
(19, 9, 'A-', 990, '2025-04-26 01:33:31'),
(20, 10, 'A-', 1000, '2025-04-26 01:19:52'),
(21, 1, 'B+', 1000, '2025-04-26 01:19:52'),
(22, 2, 'B+', 1000, '2025-04-26 01:19:52'),
(23, 3, 'B+', 1000, '2025-04-26 01:19:52'),
(24, 4, 'B+', 1000, '2025-04-26 01:19:52'),
(25, 5, 'B+', 1000, '2025-04-26 01:19:52'),
(26, 6, 'B+', 1000, '2025-04-26 01:19:52'),
(27, 7, 'B+', 1000, '2025-04-26 01:19:52'),
(28, 8, 'B+', 1000, '2025-04-26 01:19:52'),
(29, 9, 'B+', 1000, '2025-04-26 01:19:52'),
(30, 10, 'B+', 1000, '2025-04-26 01:19:52'),
(31, 1, 'B-', 1000, '2025-04-26 01:19:52'),
(32, 2, 'B-', 1000, '2025-04-26 01:19:52'),
(33, 3, 'B-', 1000, '2025-04-26 01:19:52'),
(34, 4, 'B-', 1000, '2025-04-26 01:19:52'),
(35, 5, 'B-', 1000, '2025-04-26 01:19:52'),
(36, 6, 'B-', 1000, '2025-04-26 01:19:52'),
(37, 7, 'B-', 1000, '2025-04-26 01:19:52'),
(38, 8, 'B-', 1000, '2025-04-26 01:19:52'),
(39, 9, 'B-', 1000, '2025-04-26 01:19:52'),
(40, 10, 'B-', 1000, '2025-04-26 01:19:52'),
(41, 1, 'AB+', 1000, '2025-04-26 01:19:52'),
(42, 2, 'AB+', 1000, '2025-04-26 01:19:52'),
(43, 3, 'AB+', 1000, '2025-04-26 01:19:52'),
(44, 4, 'AB+', 1000, '2025-04-26 01:19:52'),
(45, 5, 'AB+', 1000, '2025-04-26 01:19:52'),
(46, 6, 'AB+', 1000, '2025-04-26 01:19:52'),
(47, 7, 'AB+', 1000, '2025-04-26 01:19:52'),
(48, 8, 'AB+', 1000, '2025-04-26 01:19:52'),
(49, 9, 'AB+', 1000, '2025-04-26 01:19:52'),
(50, 10, 'AB+', 1000, '2025-04-26 01:19:52'),
(51, 1, 'AB-', 1000, '2025-04-26 01:19:52'),
(52, 2, 'AB-', 1000, '2025-04-26 01:19:52'),
(53, 3, 'AB-', 1000, '2025-04-26 01:19:52'),
(54, 4, 'AB-', 1000, '2025-04-26 01:19:52'),
(55, 5, 'AB-', 1000, '2025-04-26 01:19:52'),
(56, 6, 'AB-', 1000, '2025-04-26 01:19:52'),
(57, 7, 'AB-', 1000, '2025-04-26 01:19:52'),
(58, 8, 'AB-', 1000, '2025-04-26 01:19:52'),
(59, 9, 'AB-', 1000, '2025-04-26 01:19:52'),
(60, 10, 'AB-', 1000, '2025-04-26 01:19:52'),
(61, 1, 'O+', 1000, '2025-04-26 01:19:52'),
(62, 2, 'O+', 1000, '2025-04-26 01:19:52'),
(63, 3, 'O+', 1000, '2025-04-26 01:19:52'),
(64, 4, 'O+', 1000, '2025-04-26 01:19:52'),
(65, 5, 'O+', 1000, '2025-04-26 01:19:52'),
(66, 6, 'O+', 1000, '2025-04-26 01:19:52'),
(67, 7, 'O+', 1000, '2025-04-26 01:19:52'),
(68, 8, 'O+', 1000, '2025-04-26 01:19:52'),
(69, 9, 'O+', 1000, '2025-04-26 01:19:52'),
(70, 10, 'O+', 1000, '2025-04-26 01:19:52'),
(71, 1, 'O-', 1000, '2025-04-26 01:19:52'),
(72, 2, 'O-', 1000, '2025-04-26 01:19:52'),
(73, 3, 'O-', 1000, '2025-04-26 01:19:52'),
(74, 4, 'O-', 1000, '2025-04-26 01:19:52'),
(75, 5, 'O-', 1000, '2025-04-26 01:19:52'),
(76, 6, 'O-', 1000, '2025-04-26 01:19:52'),
(77, 7, 'O-', 1000, '2025-04-26 01:19:52'),
(78, 8, 'O-', 1000, '2025-04-26 01:19:52'),
(79, 9, 'O-', 1000, '2025-04-26 01:19:52'),
(80, 10, 'O-', 1000, '2025-04-26 01:19:52');

-- --------------------------------------------------------

--
-- Table structure for table `blood_inventory`
--

CREATE TABLE `blood_inventory` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_inventory`
--

INSERT INTO `blood_inventory` (`id`, `hospital_id`, `blood_group`, `quantity`, `last_updated`) VALUES
(7, 10, 'A+', 1, '2025-04-25 09:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `hospital_id`, `blood_group`, `quantity`, `status`, `request_date`) VALUES
(1, 10, 'A-', 10, 'approved', '2025-04-24 12:56:01'),
(2, 10, 'A-', 5, 'approved', '2025-04-24 13:11:21'),
(3, 10, 'A+', 3, 'approved', '2025-04-25 20:09:38'),
(4, 10, 'A+', 55, 'approved', '2025-04-25 22:04:16'),
(5, 10, 'A+', 6, 'approved', '2025-04-25 22:21:22'),
(6, 10, 'A-', 4, 'approved', '2025-04-25 23:06:13'),
(7, 10, 'A+', 10, 'approved', '2025-04-25 23:18:07');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `state_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `state_id`) VALUES
(1, 'Visakhapatnam', 1),
(2, 'Vijayawada', 1),
(3, 'Guntur', 1),
(4, 'Nellore', 1),
(5, 'Kurnool', 1),
(6, 'Itanagar', 2),
(7, 'Naharlagun', 2),
(8, 'Pasighat', 2),
(9, 'Tawang', 2),
(10, 'Ziro', 2),
(11, 'Guwahati', 3),
(12, 'Silchar', 3),
(13, 'Dibrugarh', 3),
(14, 'Jorhat', 3),
(15, 'Nagaon', 3),
(16, 'Patna', 4),
(17, 'Gaya', 4),
(18, 'Bhagalpur', 4),
(19, 'Muzaffarpur', 4),
(20, 'Darbhanga', 4),
(21, 'Raipur', 5),
(22, 'Bhilai', 5),
(23, 'Bilaspur', 5),
(24, 'Korba', 5),
(25, 'Durg', 5),
(26, 'Panaji', 6),
(27, 'Margao', 6),
(28, 'Vasco da Gama', 6),
(29, 'Mapusa', 6),
(30, 'Ponda', 6),
(31, 'Ahmedabad', 7),
(32, 'Surat', 7),
(33, 'Vadodara', 7),
(34, 'Rajkot', 7),
(35, 'Bhavnagar', 7),
(36, 'Faridabad', 8),
(37, 'Gurgaon', 8),
(38, 'Panipat', 8),
(39, 'Ambala', 8),
(40, 'Yamunanagar', 8),
(41, 'Shimla', 9),
(42, 'Mandi', 9),
(43, 'Solan', 9),
(44, 'Dharamshala', 9),
(45, 'Bilaspur', 9),
(46, 'Ranchi', 10),
(47, 'Jamshedpur', 10),
(48, 'Dhanbad', 10),
(49, 'Bokaro', 10),
(50, 'Hazaribagh', 10),
(51, 'Bangalore', 11),
(52, 'Mysore', 11),
(53, 'Hubli', 11),
(54, 'Mangalore', 11),
(55, 'Belgaum', 11),
(56, 'Thiruvananthapuram', 12),
(57, 'Kochi', 12),
(58, 'Kozhikode', 12),
(59, 'Thrissur', 12),
(60, 'Kollam', 12),
(61, 'Bhopal', 13),
(62, 'Indore', 13),
(63, 'Jabalpur', 13),
(64, 'Gwalior', 13),
(65, 'Ujjain', 13),
(66, 'Mumbai', 14),
(67, 'Pune', 14),
(68, 'Nagpur', 14),
(69, 'Thane', 14),
(70, 'Nashik', 14),
(71, 'Imphal', 15),
(72, 'Thoubal', 15),
(73, 'Bishnupur', 15),
(74, 'Churachandpur', 15),
(75, 'Ukhrul', 15),
(76, 'Shillong', 16),
(77, 'Tura', 16),
(78, 'Jowai', 16),
(79, 'Nongstoin', 16),
(80, 'Williamnagar', 16),
(81, 'Aizawl', 17),
(82, 'Lunglei', 17),
(83, 'Saiha', 17),
(84, 'Champhai', 17),
(85, 'Kolasib', 17),
(86, 'Kohima', 18),
(87, 'Dimapur', 18),
(88, 'Mokokchung', 18),
(89, 'Tuensang', 18),
(90, 'Wokha', 18),
(91, 'Bhubaneswar', 19),
(92, 'Cuttack', 19),
(93, 'Rourkela', 19),
(94, 'Brahmapur', 19),
(95, 'Sambalpur', 19),
(96, 'Ludhiana', 20),
(97, 'Amritsar', 20),
(98, 'Jalandhar', 20),
(99, 'Patiala', 20),
(100, 'Bathinda', 20),
(101, 'Jaipur', 21),
(102, 'Jodhpur', 21),
(103, 'Kota', 21),
(104, 'Bikaner', 21),
(105, 'Ajmer', 21),
(106, 'Gangtok', 22),
(107, 'Namchi', 22),
(108, 'Mangan', 22),
(109, 'Gyalshing', 22),
(110, 'Singtam', 22),
(111, 'Chennai', 23),
(112, 'Coimbatore', 23),
(113, 'Madurai', 23),
(114, 'Tiruchirappalli', 23),
(115, 'Salem', 23),
(116, 'Hyderabad', 24),
(117, 'Warangal', 24),
(118, 'Nizamabad', 24),
(119, 'Karimnagar', 24),
(120, 'Ramagundam', 24),
(121, 'Agartala', 25),
(122, 'Udaipur', 25),
(123, 'Dharmanagar', 25),
(124, 'Kailashahar', 25),
(125, 'Belonia', 25),
(126, 'Lucknow', 26),
(127, 'Kanpur', 26),
(128, 'Varanasi', 26),
(129, 'Agra', 26),
(130, 'Meerut', 26),
(131, 'Dehradun', 27),
(132, 'Haridwar', 27),
(133, 'Roorkee', 27),
(134, 'Haldwani', 27),
(135, 'Rudrapur', 27),
(136, 'Kolkata', 28),
(137, 'Howrah', 28),
(138, 'Durgapur', 28),
(139, 'Asansol', 28),
(140, 'Siliguri', 28);

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location_type` enum('hospital','blood_bank') NOT NULL,
  `location_id` int(11) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `donation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('completed','scheduled','cancelled') DEFAULT 'scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `location_type`, `location_id`, `blood_group`, `donation_date`, `status`) VALUES
(8, 1, 'hospital', 10, 'A+', '2025-12-31 03:30:00', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `donation_requests`
--

CREATE TABLE `donation_requests` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `status` enum('pending','accepted','rejected','completed') DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `response_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_requests`
--

INSERT INTO `donation_requests` (`id`, `hospital_id`, `donor_id`, `blood_group`, `status`, `request_date`, `response_date`) VALUES
(1, 10, 44, 'A+', 'pending', '2025-04-25 21:43:35', NULL),
(2, 10, 45, 'A+', 'pending', '2025-04-25 21:45:06', NULL),
(3, 10, 45, 'A+', 'pending', '2025-04-25 21:50:48', NULL),
(4, 10, 45, 'A+', 'pending', '2025-04-25 22:01:14', NULL),
(5, 10, 45, 'A+', 'pending', '2025-04-25 22:29:59', NULL),
(6, 10, 44, 'A+', 'pending', '2025-04-25 22:29:59', NULL),
(7, 10, 46, 'A+', 'pending', '2025-04-25 22:29:59', NULL),
(8, 10, 42, 'A+', 'pending', '2025-04-25 22:29:59', NULL),
(9, 10, 43, 'A+', 'pending', '2025-04-25 22:29:59', NULL),
(10, 10, 37, 'A+', 'pending', '2025-04-25 22:29:59', NULL),
(11, 10, 39, 'A+', 'pending', '2025-04-25 22:29:59', NULL),
(12, 10, 45, 'A+', 'pending', '2025-04-25 22:32:17', NULL),
(13, 10, 44, 'A+', 'pending', '2025-04-25 22:32:17', NULL),
(14, 10, 46, 'A+', 'pending', '2025-04-25 22:32:17', NULL),
(15, 10, 42, 'A+', 'pending', '2025-04-25 22:32:17', NULL),
(16, 10, 43, 'A+', 'pending', '2025-04-25 22:32:17', NULL),
(17, 10, 37, 'A+', 'pending', '2025-04-25 22:32:17', NULL),
(18, 10, 39, 'A+', 'pending', '2025-04-25 22:32:17', NULL),
(19, 10, 45, 'A+', 'pending', '2025-04-25 22:32:21', NULL),
(20, 10, 45, 'A+', 'pending', '2025-04-25 22:33:11', NULL),
(21, 10, 44, 'A+', 'pending', '2025-04-25 22:33:11', NULL),
(22, 10, 46, 'A+', 'pending', '2025-04-25 22:33:12', NULL),
(23, 10, 42, 'A+', 'pending', '2025-04-25 22:33:12', NULL),
(24, 10, 43, 'A+', 'pending', '2025-04-25 22:33:12', NULL),
(25, 10, 37, 'A+', 'pending', '2025-04-25 22:33:12', NULL),
(26, 10, 39, 'A+', 'pending', '2025-04-25 22:33:12', NULL),
(27, 10, 45, 'A+', 'pending', '2025-04-25 22:33:15', NULL),
(28, 10, 45, 'A+', 'pending', '2025-04-25 22:37:48', NULL),
(29, 10, 44, 'A+', 'pending', '2025-04-25 22:37:50', NULL),
(30, 10, 46, 'A+', 'pending', '2025-04-25 22:37:52', NULL),
(31, 10, 42, 'A+', 'pending', '2025-04-25 22:37:54', NULL),
(32, 10, 43, 'A+', 'pending', '2025-04-25 22:37:57', NULL),
(33, 10, 37, 'A+', 'pending', '2025-04-25 22:37:59', NULL),
(34, 10, 39, 'A+', 'pending', '2025-04-25 22:38:02', NULL),
(35, 10, 45, 'A+', 'pending', '2025-04-25 22:40:27', NULL),
(36, 10, 45, 'A+', 'pending', '2025-04-25 23:17:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `email`, `password`, `address`, `city`, `state`, `country`, `contact_number`, `license_number`, `status`, `created_at`, `latitude`, `longitude`) VALUES
(1, 'City General Hospital', 'citygeneral@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Main Street', 'Mumbai', 'Maharashtra', 'India', '9876543210', 'HOSP001', 'active', '2025-04-24 06:48:11', 19.07600000, 72.87770000),
(2, 'Metro Medical Center', 'metro@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Central Avenue', 'Delhi', 'Delhi', 'India', '9876543211', 'HOSP002', 'active', '2025-04-24 06:48:11', 28.61390000, 77.20900000),
(3, 'Sunrise Hospital', 'sunrise@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Park Road', 'Bangalore', 'Karnataka', 'India', '9876543212', 'HOSP003', 'active', '2025-04-24 06:48:11', 12.97160000, 77.59460000),
(4, 'Green Valley Medical', 'greenvalley@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '321 Valley Street', 'Chennai', 'Tamil Nadu', 'India', '9876543213', 'HOSP004', 'active', '2025-04-24 06:48:11', 13.08270000, 80.27070000),
(5, 'Royal Care Hospital', 'royalcare@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '654 Royal Avenue', 'Kolkata', 'West Bengal', 'India', '9876543214', 'HOSP005', 'active', '2025-04-24 06:48:11', 22.57260000, 88.36390000),
(6, 'Unity Health Center', 'unity@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '987 Unity Road', 'Hyderabad', 'Telangana', 'India', '9876543215', 'HOSP006', 'active', '2025-04-24 06:48:11', 17.38500000, 78.48670000),
(7, 'Life Care Hospital', 'lifecare@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '147 Life Street', 'Pune', 'Maharashtra', 'India', '9876543216', 'HOSP007', 'active', '2025-04-24 06:48:11', 18.52040000, 73.85670000),
(8, 'Hope Medical Center', 'hope@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '258 Hope Avenue', 'Ahmedabad', 'Gujarat', 'India', '9876543217', 'HOSP008', 'active', '2025-04-24 06:48:11', 23.02250000, 72.57140000),
(9, 'Prime Hospital', 'prime@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '369 Prime Road', 'Jaipur', 'Rajasthan', 'India', '9876543218', 'HOSP009', 'active', '2025-04-24 06:48:11', 26.91240000, 75.78730000),
(10, 'Elite Medical Center', 'elite@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Amaravati Capital City, Near Government Complex', 'Amaravati', 'Andhra Pradesh', 'India', '9876543219', 'HOSP010', 'active', '2025-04-24 06:48:11', 16.51570000, 80.51810000),
(11, 'Apollo Hospital Mumbai', 'apollo.mumbai@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Marine Drive', 'Mumbai', 'Maharashtra', 'India', '9876543220', 'HOSP011', 'active', '2025-04-25 06:03:40', 19.09101992, 72.85997619),
(12, 'Fortis Hospital Mumbai', 'fortis.mumbai@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Bandra West', 'Mumbai', 'Maharashtra', 'India', '9876543221', 'HOSP012', 'active', '2025-04-25 06:03:40', 19.09232117, 72.86247731),
(13, 'Kokilaben Hospital', 'kokilaben@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Andheri West', 'Mumbai', 'Maharashtra', 'India', '9876543222', 'HOSP013', 'active', '2025-04-25 06:03:40', 19.10092304, 72.89798328),
(14, 'Max Hospital Delhi', 'max.delhi@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Saket', 'Delhi', 'Delhi', 'India', '9876543223', 'HOSP014', 'active', '2025-04-25 06:03:40', 28.59054729, 77.18138660),
(15, 'BLK Hospital', 'blk@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Pusa Road', 'Delhi', 'Delhi', 'India', '9876543224', 'HOSP015', 'active', '2025-04-25 06:03:40', 28.59589112, 77.25179581),
(16, 'Indraprastha Apollo', 'apollo.delhi@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Sarita Vihar', 'Delhi', 'Delhi', 'India', '9876543225', 'HOSP016', 'active', '2025-04-25 06:03:40', 28.63190571, 77.22064110),
(17, 'Manipal Hospital', 'manipal@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 HAL Airport Road', 'Bangalore', 'Karnataka', 'India', '9876543226', 'HOSP017', 'active', '2025-04-25 06:03:40', 12.92578840, 77.58061868),
(18, 'Fortis Hospital Bangalore', 'fortis.bangalore@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Bannerghatta Road', 'Bangalore', 'Karnataka', 'India', '9876543227', 'HOSP018', 'active', '2025-04-25 06:03:40', 12.98912821, 77.57418500),
(19, 'Columbia Asia Hospital', 'columbia@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Hebbal', 'Bangalore', 'Karnataka', 'India', '9876543228', 'HOSP019', 'active', '2025-04-25 06:03:40', 12.96694038, 77.58254690),
(20, 'Apollo Hospital Chennai', 'apollo.chennai@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Greams Road', 'Chennai', 'Tamil Nadu', 'India', '9876543229', 'HOSP020', 'active', '2025-04-25 06:03:40', 13.08641338, 80.27542618),
(21, 'Fortis Malar Hospital', 'fortis.chennai@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Adyar', 'Chennai', 'Tamil Nadu', 'India', '9876543230', 'HOSP021', 'active', '2025-04-25 06:03:40', 13.04519077, 80.31897533),
(22, 'MIOT Hospital', 'miot@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Mount Poonamallee Road', 'Chennai', 'Tamil Nadu', 'India', '9876543231', 'HOSP022', 'active', '2025-04-25 06:03:40', 13.08660432, 80.29539564),
(23, 'Apollo Gleneagles Hospital', 'apollo.kolkata@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 EM Bypass', 'Kolkata', 'West Bengal', 'India', '9876543232', 'HOSP023', 'active', '2025-04-25 06:03:40', 22.53436521, 88.34863916),
(24, 'Fortis Hospital Kolkata', 'fortis.kolkata@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Anandapur', 'Kolkata', 'West Bengal', 'India', '9876543233', 'HOSP024', 'active', '2025-04-25 06:03:40', 22.56100018, 88.40168340),
(25, 'AMRI Hospital', 'amri@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '789 Salt Lake', 'Kolkata', 'West Bengal', 'India', '9876543234', 'HOSP025', 'active', '2025-04-25 06:03:40', 22.54631648, 88.36913221),
(26, 'Apollo Hospitals Jubilee Hills', 'apollo.hyderabad@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Road No 72, Jubilee Hills', 'Hyderabad', 'Telangana', 'India', '9876543235', 'HOSP026', 'active', '2025-04-25 06:05:09', 17.43425929, 78.40990650),
(27, 'Yashoda Hospitals Somajiguda', 'yashoda.somajiguda@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Raj Bhavan Road, Somajiguda', 'Hyderabad', 'Telangana', 'India', '9876543236', 'HOSP027', 'active', '2025-04-25 06:05:09', 17.40965460, 78.45225352),
(28, 'Continental Hospitals', 'continental@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nanakramguda, Financial District', 'Hyderabad', 'Telangana', 'India', '9876543237', 'HOSP028', 'active', '2025-04-25 06:05:09', 17.39780380, 78.35905844),
(29, 'KIMS Hospitals Secunderabad', 'kims.secunderabad@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Minister Road, Secunderabad', 'Hyderabad', 'Telangana', 'India', '9876543238', 'HOSP029', 'active', '2025-04-25 06:05:09', 17.44648082, 78.49372876),
(30, 'Medicover Hospitals', 'medicover@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hitech City Road, Madhapur', 'Hyderabad', 'Telangana', 'India', '9876543239', 'HOSP030', 'active', '2025-04-25 06:05:09', 17.45480134, 78.37722041),
(31, 'MGM Hospital Warangal', 'mgm.warangal@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hanamkonda, Warangal', 'Warangal', 'Telangana', 'India', '9876543240', 'HOSP031', 'active', '2025-04-25 06:05:09', 17.96029803, 79.59892893),
(32, 'Kamineni Hospital Karimnagar', 'kamineni.karimnagar@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Huzurabad Road, Karimnagar', 'Karimnagar', 'Telangana', 'India', '9876543241', 'HOSP032', 'active', '2025-04-25 06:05:09', 18.43855058, 79.12406608),
(33, 'Nizamabad Institute of Medical Sciences', 'nims@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mendora, Nizamabad', 'Nizamabad', 'Telangana', 'India', '9876543242', 'HOSP033', 'active', '2025-04-25 06:05:09', 18.67797866, 78.09139509),
(34, 'Kamineni Hospital Khammam', 'kamineni.khammam@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kothagudem Road, Khammam', 'Khammam', 'Telangana', 'India', '9876543243', 'HOSP034', 'active', '2025-04-25 06:05:09', 17.24083946, 80.15931201),
(35, 'SVS Medical College Hospital', 'svs.mahbubnagar@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'NH-44, Mahbubnagar', 'Mahbubnagar', 'Telangana', 'India', '9876543244', 'HOSP035', 'active', '2025-04-25 06:05:09', 16.74544170, 77.98117245);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` enum('read','unread') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `status`, `created_at`) VALUES
(1, 44, 'Blood Donation Request', 'Blood Donation Request: Elite Medical Center from Amaravati, Andhra Pradesh needs A+ blood. Please check your dashboard for more details.', 'donation_request', 'unread', '2025-04-25 21:43:35'),
(2, 45, 'Blood Donation Request', 'Blood Donation Request: Elite Medical Center from Amaravati, Andhra Pradesh needs A+ blood. Please check your dashboard for more details.', 'donation_request', 'unread', '2025-04-25 21:45:06'),
(3, 45, 'Blood Donation Request', 'Blood Donation Request: Elite Medical Center from Amaravati, Andhra Pradesh needs A+ blood. Please check your dashboard for more details.', 'donation_request', 'unread', '2025-04-25 21:50:48'),
(4, 45, 'Blood Donation Request', 'Blood Donation Request: Elite Medical Center from Amaravati, Andhra Pradesh needs A+ blood. Please check your dashboard for more details.', 'donation_request', 'unread', '2025-04-25 22:01:14'),
(5, 45, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 5', 'donation_request', 'unread', '2025-04-25 22:29:59'),
(6, 44, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 6', 'donation_request', 'unread', '2025-04-25 22:29:59'),
(7, 46, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 7', 'donation_request', 'unread', '2025-04-25 22:29:59'),
(8, 42, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 8', 'donation_request', 'unread', '2025-04-25 22:29:59'),
(9, 43, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 9', 'donation_request', 'unread', '2025-04-25 22:29:59'),
(10, 37, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 10', 'donation_request', 'unread', '2025-04-25 22:29:59'),
(11, 39, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 11', 'donation_request', 'unread', '2025-04-25 22:29:59'),
(12, 45, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 12', 'donation_request', 'unread', '2025-04-25 22:32:17'),
(13, 44, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 13', 'donation_request', 'unread', '2025-04-25 22:32:17'),
(14, 46, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 14', 'donation_request', 'unread', '2025-04-25 22:32:17'),
(15, 42, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 15', 'donation_request', 'unread', '2025-04-25 22:32:17'),
(16, 43, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 16', 'donation_request', 'unread', '2025-04-25 22:32:17'),
(17, 37, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 17', 'donation_request', 'unread', '2025-04-25 22:32:17'),
(18, 39, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 18', 'donation_request', 'unread', '2025-04-25 22:32:17'),
(19, 45, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 19', 'donation_request', 'unread', '2025-04-25 22:32:21'),
(20, 45, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 20', 'donation_request', 'unread', '2025-04-25 22:33:11'),
(21, 44, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 21', 'donation_request', 'unread', '2025-04-25 22:33:12'),
(22, 46, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 22', 'donation_request', 'unread', '2025-04-25 22:33:12'),
(23, 42, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 23', 'donation_request', 'unread', '2025-04-25 22:33:12'),
(24, 43, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 24', 'donation_request', 'unread', '2025-04-25 22:33:12'),
(25, 37, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 25', 'donation_request', 'unread', '2025-04-25 22:33:12'),
(26, 39, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 26', 'donation_request', 'unread', '2025-04-25 22:33:12'),
(27, 45, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 27', 'donation_request', 'unread', '2025-04-25 22:33:15'),
(28, 45, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 28', 'donation_request', 'unread', '2025-04-25 22:37:48'),
(29, 44, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 29', 'donation_request', 'unread', '2025-04-25 22:37:50'),
(30, 46, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 30', 'donation_request', 'unread', '2025-04-25 22:37:52'),
(31, 42, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 31', 'donation_request', 'unread', '2025-04-25 22:37:55'),
(32, 43, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 32', 'donation_request', 'unread', '2025-04-25 22:37:57'),
(33, 37, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 33', 'donation_request', 'unread', '2025-04-25 22:38:00'),
(34, 39, 'Blood Donation Request', 'Hospital Elite Medical Center needs blood group A+. Request ID: 34', 'donation_request', 'unread', '2025-04-25 22:38:02'),
(35, 45, 'Blood Donation Request', 'Blood Donation Request: Elite Medical Center from Amaravati, Andhra Pradesh needs A+ blood. Please check your dashboard for more details.', 'donation_request', 'unread', '2025-04-25 22:40:27'),
(36, 45, 'Blood Donation Request', 'Blood Donation Request: Elite Medical Center from Amaravati, Andhra Pradesh needs A+ blood. Please check your dashboard for more details.', 'donation_request', 'unread', '2025-04-25 23:17:02'),
(37, 10, 'Blood Request Approved', 'Your request for 10 units of A- blood has been approved. Blood will be supplied from Amaravati Blood Bank Center. Please collect within 24 hours.', 'blood_request', 'unread', '2025-04-26 01:33:31'),
(38, 10, 'Blood Request Approved', 'Your request for 6 units of A+ blood has been approved. Blood will be supplied from Amaravati Blood Bank Center. Please collect within 24 hours.', 'blood_request', 'unread', '2025-04-26 01:33:53');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`) VALUES
(1, 'Andhra Pradesh'),
(2, 'Arunachal Pradesh'),
(3, 'Assam'),
(4, 'Bihar'),
(5, 'Chhattisgarh'),
(6, 'Goa'),
(7, 'Gujarat'),
(8, 'Haryana'),
(9, 'Himachal Pradesh'),
(10, 'Jharkhand'),
(11, 'Karnataka'),
(12, 'Kerala'),
(13, 'Madhya Pradesh'),
(14, 'Maharashtra'),
(15, 'Manipur'),
(16, 'Meghalaya'),
(17, 'Mizoram'),
(18, 'Nagaland'),
(19, 'Odisha'),
(20, 'Punjab'),
(21, 'Rajasthan'),
(22, 'Sikkim'),
(23, 'Tamil Nadu'),
(24, 'Telangana'),
(25, 'Tripura'),
(26, 'Uttar Pradesh'),
(27, 'Uttarakhand'),
(28, 'West Bengal');

-- --------------------------------------------------------

--
-- Table structure for table `state_inventory`
--

CREATE TABLE `state_inventory` (
  `id` int(11) NOT NULL,
  `state` varchar(50) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `state_inventory`
--

INSERT INTO `state_inventory` (`id`, `state`, `blood_group`, `quantity`, `last_updated`) VALUES
(2, 'Andhra Pradesh', 'A+', 994, '2025-04-26 01:33:53'),
(3, 'Maharashtra', 'A-', 2000, '2025-04-26 01:19:52'),
(4, 'Maharashtra', 'A+', 2000, '2025-04-26 01:19:52'),
(9, 'Andhra Pradesh', 'A-', 990, '2025-04-26 01:33:31'),
(10, 'Andhra Pradesh', 'AB+', 1000, '2025-04-26 01:19:52'),
(11, 'Andhra Pradesh', 'AB-', 1000, '2025-04-26 01:19:52'),
(12, 'Andhra Pradesh', 'B+', 1000, '2025-04-26 01:19:52'),
(13, 'Andhra Pradesh', 'B-', 1000, '2025-04-26 01:19:52'),
(14, 'Andhra Pradesh', 'O+', 1000, '2025-04-26 01:19:52'),
(15, 'Andhra Pradesh', 'O-', 1000, '2025-04-26 01:19:52'),
(16, 'Delhi', 'A+', 1000, '2025-04-26 01:19:52'),
(17, 'Delhi', 'A-', 1000, '2025-04-26 01:19:52'),
(18, 'Delhi', 'AB+', 1000, '2025-04-26 01:19:52'),
(19, 'Delhi', 'AB-', 1000, '2025-04-26 01:19:52'),
(20, 'Delhi', 'B+', 1000, '2025-04-26 01:19:52'),
(21, 'Delhi', 'B-', 1000, '2025-04-26 01:19:52'),
(22, 'Delhi', 'O+', 1000, '2025-04-26 01:19:52'),
(23, 'Delhi', 'O-', 1000, '2025-04-26 01:19:52'),
(24, 'Gujarat', 'A+', 1000, '2025-04-26 01:19:52'),
(25, 'Gujarat', 'A-', 1000, '2025-04-26 01:19:52'),
(26, 'Gujarat', 'AB+', 1000, '2025-04-26 01:19:52'),
(27, 'Gujarat', 'AB-', 1000, '2025-04-26 01:19:52'),
(28, 'Gujarat', 'B+', 1000, '2025-04-26 01:19:52'),
(29, 'Gujarat', 'B-', 1000, '2025-04-26 01:19:52'),
(30, 'Gujarat', 'O+', 1000, '2025-04-26 01:19:52'),
(31, 'Gujarat', 'O-', 1000, '2025-04-26 01:19:52'),
(32, 'Karnataka', 'A+', 1000, '2025-04-26 01:19:52'),
(33, 'Karnataka', 'A-', 1000, '2025-04-26 01:19:52'),
(34, 'Karnataka', 'AB+', 1000, '2025-04-26 01:19:52'),
(35, 'Karnataka', 'AB-', 1000, '2025-04-26 01:19:52'),
(36, 'Karnataka', 'B+', 1000, '2025-04-26 01:19:52'),
(37, 'Karnataka', 'B-', 1000, '2025-04-26 01:19:52'),
(38, 'Karnataka', 'O+', 1000, '2025-04-26 01:19:52'),
(39, 'Karnataka', 'O-', 1000, '2025-04-26 01:19:52'),
(40, 'Maharashtra', 'AB+', 2000, '2025-04-26 01:19:52'),
(41, 'Maharashtra', 'AB-', 2000, '2025-04-26 01:19:52'),
(42, 'Maharashtra', 'B+', 2000, '2025-04-26 01:19:52'),
(43, 'Maharashtra', 'B-', 2000, '2025-04-26 01:19:52'),
(44, 'Maharashtra', 'O+', 2000, '2025-04-26 01:19:52'),
(45, 'Maharashtra', 'O-', 2000, '2025-04-26 01:19:52'),
(46, 'Tamil Nadu', 'A+', 1000, '2025-04-26 01:19:52'),
(47, 'Tamil Nadu', 'A-', 1000, '2025-04-26 01:19:52'),
(48, 'Tamil Nadu', 'AB+', 1000, '2025-04-26 01:19:52'),
(49, 'Tamil Nadu', 'AB-', 1000, '2025-04-26 01:19:52'),
(50, 'Tamil Nadu', 'B+', 1000, '2025-04-26 01:19:52'),
(51, 'Tamil Nadu', 'B-', 1000, '2025-04-26 01:19:52'),
(52, 'Tamil Nadu', 'O+', 1000, '2025-04-26 01:19:52'),
(53, 'Tamil Nadu', 'O-', 1000, '2025-04-26 01:19:52'),
(54, 'Telangana', 'A+', 1000, '2025-04-26 01:19:52'),
(55, 'Telangana', 'A-', 1000, '2025-04-26 01:19:52'),
(56, 'Telangana', 'AB+', 1000, '2025-04-26 01:19:52'),
(57, 'Telangana', 'AB-', 1000, '2025-04-26 01:19:52'),
(58, 'Telangana', 'B+', 1000, '2025-04-26 01:19:52'),
(59, 'Telangana', 'B-', 1000, '2025-04-26 01:19:52'),
(60, 'Telangana', 'O+', 1000, '2025-04-26 01:19:52'),
(61, 'Telangana', 'O-', 1000, '2025-04-26 01:19:52'),
(62, 'Uttar Pradesh', 'A+', 1000, '2025-04-26 01:19:52'),
(63, 'Uttar Pradesh', 'A-', 1000, '2025-04-26 01:19:52'),
(64, 'Uttar Pradesh', 'AB+', 1000, '2025-04-26 01:19:52'),
(65, 'Uttar Pradesh', 'AB-', 1000, '2025-04-26 01:19:52'),
(66, 'Uttar Pradesh', 'B+', 1000, '2025-04-26 01:19:52'),
(67, 'Uttar Pradesh', 'B-', 1000, '2025-04-26 01:19:52'),
(68, 'Uttar Pradesh', 'O+', 1000, '2025-04-26 01:19:52'),
(69, 'Uttar Pradesh', 'O-', 1000, '2025-04-26 01:19:52'),
(70, 'West Bengal', 'A+', 1000, '2025-04-26 01:19:52'),
(71, 'West Bengal', 'A-', 1000, '2025-04-26 01:19:52'),
(72, 'West Bengal', 'AB+', 1000, '2025-04-26 01:19:52'),
(73, 'West Bengal', 'AB-', 1000, '2025-04-26 01:19:52'),
(74, 'West Bengal', 'B+', 1000, '2025-04-26 01:19:52'),
(75, 'West Bengal', 'B-', 1000, '2025-04-26 01:19:52'),
(76, 'West Bengal', 'O+', 1000, '2025-04-26 01:19:52'),
(77, 'West Bengal', 'O-', 1000, '2025-04-26 01:19:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `id_proof` varchar(20) NOT NULL,
  `state` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `house_no` varchar(50) DEFAULT NULL,
  `colony` varchar(100) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `landmark` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','hospital','admin') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `points` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `age`, `mobile`, `email`, `id_proof`, `state`, `city`, `blood_group`, `house_no`, `colony`, `street`, `landmark`, `latitude`, `longitude`, `height`, `weight`, `password`, `role`, `status`, `points`, `created_at`, `updated_at`, `state_id`, `city_id`) VALUES
(1, 'uhi', 55, '6777777774', 'uhi@example.com', '345687654345', 'Andhra Pradesh', 'Visakhapatnam', 'A+', NULL, NULL, NULL, NULL, NULL, NULL, 100, 30, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 150, '2025-04-24 06:49:22', '2025-04-25 22:26:30', 14, 66),
(2, 'Rahul Sharma', 28, '9876543210', 'rahul.sharma@example.com', 'DL1234567890', 'Maharashtra', 'Mumbai', 'O+', 'Flat 302', 'Green Valley Society', 'MG Road', 'Near City Mall', NULL, NULL, 175, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'admin', 'active', 0, '2025-04-24 18:16:32', '2025-04-25 22:26:30', 14, 66),
(6, 'Admin User', 35, '9999999999', 'admin.user@example.com', 'ADMIN123', 'Delhi', 'New Delhi', 'O+', 'Office 101', 'Admin Block', 'Main Road', 'Near Government Hospital', NULL, NULL, 180, 75, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'admin', 'active', 0, '2025-04-24 18:35:03', '2025-04-25 22:26:30', 14, 66),
(7, 'Rajesh Kumar', 28, '9876543210', 'rajesh.kumar@example.com', 'AP1234567890', 'Andhra Pradesh', 'Visakhapatnam', 'O+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 175, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(8, 'Priya Reddy', 25, '9876543211', 'priya.reddy@example.com', 'AP1234567891', 'Andhra Pradesh', 'Visakhapatnam', 'A+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 165, 55, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(9, 'Suresh Babu', 32, '9876543212', 'suresh.babu@example.com', 'AP1234567892', 'Andhra Pradesh', 'Visakhapatnam', 'B+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 170, 65, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(10, 'Kiran Kumar', 30, '9876543213', 'kiran.kumar@example.com', 'AP1234567893', 'Andhra Pradesh', 'Vijayawada', 'AB+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 168, 68, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(11, 'Anjali Rao', 27, '9876543214', 'anjali.rao@example.com', 'AP1234567894', 'Andhra Pradesh', 'Vijayawada', 'O-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 162, 52, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(12, 'Ravi Teja', 35, '9876543215', 'ravi.teja@example.com', 'AP1234567895', 'Andhra Pradesh', 'Vijayawada', 'A-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 172, 72, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(13, 'Sai Krishna', 29, '9876543216', 'sai.krishna@example.com', 'AP1234567896', 'Andhra Pradesh', 'Guntur', 'B-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 169, 67, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(14, 'Swathi Reddy', 26, '9876543217', 'swathi.reddy@example.com', 'AP1234567897', 'Andhra Pradesh', 'Guntur', 'AB-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 164, 54, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(15, 'Mohan Rao', 33, '9876543218', 'mohan.rao@example.com', 'AP1234567898', 'Andhra Pradesh', 'Guntur', 'O+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 171, 69, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(16, 'Karthik Reddy', 31, '9876543219', 'karthik.reddy@example.com', 'AP1234567899', 'Andhra Pradesh', 'Nellore', 'A+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 173, 71, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(17, 'Divya Sri', 24, '9876543220', 'divya.sri@example.com', 'AP1234567900', 'Andhra Pradesh', 'Nellore', 'B+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 163, 53, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(18, 'Ramesh Babu', 36, '9876543221', 'ramesh.babu@example.com', 'AP1234567901', 'Andhra Pradesh', 'Nellore', 'O-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 174, 73, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(19, 'Srinivas Reddy', 34, '9876543222', 'srinivas.reddy@example.com', 'AP1234567902', 'Andhra Pradesh', 'Kurnool', 'A-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 176, 74, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(20, 'Lakshmi Priya', 28, '9876543223', 'lakshmi.priya@example.com', 'AP1234567903', 'Andhra Pradesh', 'Kurnool', 'B-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 166, 56, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(21, 'Venkatesh', 37, '9876543224', 'venkatesh@example.com', 'AP1234567904', 'Andhra Pradesh', 'Kurnool', 'AB+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 177, 75, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 10:56:24', '2025-04-25 22:26:30', NULL, NULL),
(22, 'Arjun Reddy', 28, '9876543210', 'arjun.reddy@example.com', 'AP9876543210', 'Andhra Pradesh', 'Amravati', 'O+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 175, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(23, 'Sneha Reddy', 25, '9876543211', 'sneha.reddy@example.com', 'AP9876543211', 'Andhra Pradesh', 'Amravati', 'A+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 165, 55, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(24, 'Vikram Raju', 32, '9876543212', 'vikram.raju@example.com', 'AP9876543212', 'Andhra Pradesh', 'Amravati', 'B+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 170, 65, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(25, 'Meera Rao', 30, '9876543213', 'meera.rao@example.com', 'AP9876543213', 'Andhra Pradesh', 'Amravati', 'AB+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 168, 68, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(26, 'Krishna Reddy', 27, '9876543214', 'krishna.reddy@example.com', 'AP9876543214', 'Andhra Pradesh', 'Amravati', 'O-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 162, 52, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(27, 'Ananya Rao', 35, '9876543215', 'ananya.rao@example.com', 'AP9876543215', 'Andhra Pradesh', 'Amravati', 'A-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 172, 72, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(28, 'Rahul Kumar', 29, '9876543216', 'rahul.kumar@example.com', 'AP9876543216', 'Andhra Pradesh', 'Amravati', 'B-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 169, 67, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(29, 'Divya Reddy', 26, '9876543217', 'divya.reddy@example.com', 'AP9876543217', 'Andhra Pradesh', 'Amravati', 'AB-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 164, 54, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(30, 'Sai Kumar', 33, '9876543218', 'sai.kumar@example.com', 'AP9876543218', 'Andhra Pradesh', 'Amravati', 'O+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 171, 69, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(31, 'Lakshmi Reddy', 31, '9876543219', 'lakshmi.reddy@example.com', 'AP9876543219', 'Andhra Pradesh', 'Amravati', 'A+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 173, 71, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(32, 'Ravi Kumar', 24, '9876543220', 'ravi.kumar@example.com', 'AP9876543220', 'Andhra Pradesh', 'Amravati', 'B+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 163, 53, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(33, 'Priya Rao', 36, '9876543221', 'priya.rao@example.com', 'AP9876543221', 'Andhra Pradesh', 'Amravati', 'O-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 174, 73, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(34, 'Suresh Reddy', 34, '9876543222', 'suresh.reddy@example.com', 'AP9876543222', 'Andhra Pradesh', 'Amravati', 'A-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 176, 74, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(35, 'Swathi Kumar', 28, '9876543223', 'swathi.kumar@example.com', 'AP9876543223', 'Andhra Pradesh', 'Amravati', 'B-', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 166, 56, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(36, 'Mohan Reddy', 37, '9876543224', 'mohan.reddy@example.com', 'AP9876543224', 'Andhra Pradesh', 'Amravati', 'AB+', 'Flat 101', 'Green Valley Society', 'Main Road', 'Near City Mall', NULL, NULL, 177, 75, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 11:01:57', '2025-04-25 22:26:30', NULL, NULL),
(37, 'Ravi Kumar', 28, '9876543201', 'ravi.kumar@example.com', 'AADHAR001', 'Andhra Pradesh', 'Amaravati', 'A+', '1-2-3', 'Velagapudi', 'Main Road', 'Near Government Complex', 16.52570000, 80.51810000, 170, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 20:32:32', '2025-04-25 22:26:30', NULL, NULL),
(38, 'Priya Reddy', 32, '9876543202', 'priya.reddy@example.com', 'AADHAR002', 'Andhra Pradesh', 'Amaravati', 'B+', '4-5-6', 'Thullur', 'Ring Road', 'Near CRDA Office', 16.50570000, 80.52810000, 170, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 20:32:32', '2025-04-25 22:26:30', NULL, NULL),
(39, 'Suresh Naidu', 45, '9876543203', 'suresh.naidu@example.com', 'AADHAR003', 'Andhra Pradesh', 'Amaravati', 'A+', '7-8-9', 'Mandadam', 'College Road', 'Near VIT University', 16.52070000, 80.52810000, 170, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 20:32:32', '2025-04-25 22:26:30', NULL, NULL),
(40, 'Lakshmi Devi', 35, '9876543204', 'lakshmi.devi@example.com', 'AADHAR004', 'Andhra Pradesh', 'Amaravati', 'B+', '10-11-12', 'Rayapudi', 'River Front', 'Near Krishna River', 16.51070000, 80.50810000, 170, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 20:32:32', '2025-04-25 22:26:30', NULL, NULL),
(41, 'Krishna Prasad', 29, '9876543205', 'krishna.prasad@example.com', 'AADHAR005', 'Andhra Pradesh', 'Amaravati', 'O-', '13-14-15', 'Nelapadu', 'Temple Road', 'Near Amaralingeswara Temple', 16.52570000, 80.50810000, 170, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 20:32:32', '2025-04-25 22:26:30', NULL, NULL),
(42, 'Kiran Reddy', 33, '9876521441', 'kiran.reddy@example.com', 'AADHAR616', 'Andhra Pradesh', 'Amaravati', 'A+', '37-30', 'Velagapudi', 'Bank Street', 'Near Hospital', 16.51991875, 80.52260900, 172, 84, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(43, 'Rekha Rao', 47, '9876532391', 'rekha.rao@example.com', 'AADHAR316', 'Andhra Pradesh', 'Amaravati', 'A+', '16-88', 'Mandadam', 'Hospital Road', 'Near Temple', 16.51848100, 80.51147150, 171, 50, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(44, 'Deepa Rao', 43, '9876525943', 'deepa.rao@example.com', 'AADHAR138', 'Andhra Pradesh', 'Amaravati', 'A+', '29-97', 'Rayapudi', 'Nehru Street', 'Near Police Station', 16.51750225, 80.51279450, 166, 61, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(45, 'Ganesh Naidu', 23, '9876548901', 'sree.mengji@gmail.com', 'AADHAR112', 'Andhra Pradesh', 'Amaravati', 'A+', '20-34', 'Krishnayapalem', 'Nehru Street', 'Near Market', 16.51426900, 80.51857925, 154, 60, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:32:56', NULL, NULL),
(46, 'Pavan Naidu', 38, '9876554463', 'pavan.naidu@example.com', 'AADHAR555', 'Andhra Pradesh', 'Amaravati', 'A+', '29-68', 'Malkapuram', 'Market Road', 'Near Bus Stop', 16.51054300, 80.52021275, 164, 70, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(47, 'Padma Naidu', 41, '9876512214', 'padma.naidu@example.com', 'AADHAR258', 'Andhra Pradesh', 'Amaravati', 'A-', '14-3', 'Krishnayapalem', 'School Road', 'Near Bank', 16.51550425, 80.52038825, 159, 89, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(48, 'Ramesh Naidu', 45, '9876503374', 'ramesh.naidu@example.com', 'AADHAR996', 'Andhra Pradesh', 'Amaravati', 'A-', '7-3', 'Rayapudi', 'Bank Street', 'Near Hospital', 16.51194700, 80.51391500, 175, 67, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(49, 'Savita Rao', 40, '9876516285', 'savita.rao@example.com', 'AADHAR735', 'Andhra Pradesh', 'Amaravati', 'A-', '40-44', 'Sakhamuru', 'Gandhi Road', 'Near Police Station', 16.51387075, 80.51910575, 186, 50, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(50, 'Sai Kumar', 30, '9876537263', 'sai.kumar@example.com', 'AADHAR694', 'Andhra Pradesh', 'Amaravati', 'A-', '24-26', 'Krishnayapalem', 'Cross Street', 'Near Police Station', 16.51921000, 80.51228150, 159, 74, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(51, 'Savita Sharma', 48, '9876518486', 'savita.sharma@example.com', 'AADHAR677', 'Andhra Pradesh', 'Amaravati', 'A-', '10-31', 'Ainavolu', 'Bank Street', 'Near Library', 16.51464025, 80.51645300, 166, 85, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:01:35', '2025-04-25 22:26:30', NULL, NULL),
(53, 'Padma Reddy', 31, '9876555831', 'padma.reddy@example.com', 'AADHAR9408', 'Andhra Pradesh', 'Amaravati', 'B+', '28-19', 'Rayapudi', 'Main Road', 'Near Temple', 16.50946975, 80.51462375, 183, 84, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:41', '2025-04-25 22:26:30', NULL, NULL),
(54, 'Sunita Sharma', 36, '9876573585', 'sunita.sharma@example.com', 'AADHAR3400', 'Andhra Pradesh', 'Amaravati', 'B+', '20-30', 'Malkapuram', 'Gandhi Road', 'Near Hospital', 16.51932475, 80.52210950, 164, 81, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:41', '2025-04-25 22:26:30', NULL, NULL),
(55, 'Satish Rao', 45, '9876508178', 'satish.rao@example.com', 'AADHAR8990', 'Andhra Pradesh', 'Amaravati', 'B+', '43-56', 'Mandadam', 'Hospital Road', 'Near Park', 16.51095475, 80.51437400, 182, 76, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:41', '2025-04-25 22:26:30', NULL, NULL),
(56, 'Anjali Rao', 48, '9876564168', 'anjali.rao@example.com', 'AADHAR4011', 'Andhra Pradesh', 'Amaravati', 'B+', '40-87', 'Nelapadu', 'Gandhi Road', 'Near Bus Stop', 16.51738075, 80.51150525, 187, 57, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:41', '2025-04-25 22:26:30', NULL, NULL),
(57, 'Jyoti Naidu', 21, '9876553830', 'jyoti.naidu@example.com', 'AADHAR6257', 'Andhra Pradesh', 'Amaravati', 'B+', '38-4', 'Thullur', 'School Road', 'Near Mall', 16.51715125, 80.52321650, 177, 71, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:41', '2025-04-25 22:26:30', NULL, NULL),
(58, 'Usha Naidu', 31, '9876524574', 'usha.naidu@example.com', 'AADHAR4645', 'Andhra Pradesh', 'Amaravati', 'B-', '3-84', 'Thullur', 'Gandhi Road', 'Near Hospital', 16.52031025, 80.51290925, 167, 50, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:41', '2025-04-25 22:26:30', NULL, NULL),
(59, 'Swati Reddy', 44, '9876550365', 'swati.reddy@example.com', 'AADHAR6196', 'Andhra Pradesh', 'Amaravati', 'B-', '2-37', 'Nekkallu', 'Hospital Road', 'Near School', 16.52062075, 80.51492750, 176, 67, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:41', '2025-04-25 22:26:30', NULL, NULL),
(60, 'Neha Reddy', 25, '9876577536', 'neha.reddy@example.com', 'AADHAR7310', 'Andhra Pradesh', 'Amaravati', 'B-', '18-11', 'Nelapadu', 'Nehru Street', 'Near Mall', 16.51559875, 80.51688500, 185, 76, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:41', '2025-04-25 22:26:30', NULL, NULL),
(61, 'Srinivas Kumar', 44, '9876558734', 'srinivas.kumar@example.com', 'AADHAR2154', 'Andhra Pradesh', 'Amaravati', 'B-', '33-55', 'Mandadam', 'Gandhi Road', 'Near School', 16.51385050, 80.51203850, 170, 65, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(62, 'Anil Kumar', 50, '9876512793', 'anil.kumar@example.com', 'AADHAR2322', 'Andhra Pradesh', 'Amaravati', 'B-', '17-85', 'Mandadam', 'Cross Street', 'Near Temple', 16.52041825, 80.51550125, 179, 74, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(63, 'Rajesh Reddy', 36, '9876573258', 'rajesh.reddy@example.com', 'AADHAR4801', 'Andhra Pradesh', 'Amaravati', 'AB+', '36-24', 'Mandadam', 'College Road', 'Near Police Station', 16.51004350, 80.51986850, 174, 68, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(64, 'Rekha Kumar', 26, '9876516499', 'rekha.kumar@example.com', 'AADHAR2949', 'Andhra Pradesh', 'Amaravati', 'AB+', '19-89', 'Malkapuram', 'College Road', 'Near Library', 16.51617250, 80.51263925, 159, 58, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(65, 'Ganesh Sharma', 31, '9876509030', 'ganesh.sharma@example.com', 'AADHAR3365', 'Andhra Pradesh', 'Amaravati', 'AB+', '28-22', 'Ainavolu', 'College Road', 'Near Mall', 16.51120450, 80.52413450, 154, 65, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(66, 'Satish Kumar', 46, '9876536057', 'satish.kumar@example.com', 'AADHAR8771', 'Andhra Pradesh', 'Amaravati', 'AB+', '43-50', 'Sakhamuru', 'Bank Street', 'Near Bus Stop', 16.51213600, 80.51559575, 185, 58, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(67, 'Ravi Kumar', 47, '9876575156', 'ravi.kumar@example.com', 'AADHAR8011', 'Andhra Pradesh', 'Amaravati', 'AB+', '44-94', 'Sakhamuru', 'Nehru Street', 'Near Temple', 16.51947325, 80.52476225, 178, 69, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(68, 'Divya Kumar', 32, '9876568585', 'divya.kumar@example.com', 'AADHAR7939', 'Andhra Pradesh', 'Amaravati', 'AB-', '35-84', 'Malkapuram', 'Gandhi Road', 'Near Temple', 16.51748200, 80.51510975, 186, 63, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(69, 'Pavan Naidu', 50, '9876542992', 'pavan.naidu@example.com', 'AADHAR2451', 'Andhra Pradesh', 'Amaravati', 'AB-', '19-38', 'Ainavolu', 'Nehru Street', 'Near Library', 16.52224750, 80.52221075, 154, 55, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(70, 'Ganesh Rao', 44, '9876544389', 'ganesh.rao@example.com', 'AADHAR2220', 'Andhra Pradesh', 'Amaravati', 'AB-', '19-27', 'Velagapudi', 'School Road', 'Near Bus Stop', 16.51546375, 80.51299700, 175, 68, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(71, 'Anita Naidu', 26, '9876513703', 'anita.naidu@example.com', 'AADHAR1730', 'Andhra Pradesh', 'Amaravati', 'AB-', '40-23', 'Sakhamuru', 'Hospital Road', 'Near Temple', 16.51626025, 80.51269325, 177, 80, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(72, 'Naresh Rao', 30, '9876509047', 'naresh.rao@example.com', 'AADHAR1880', 'Andhra Pradesh', 'Amaravati', 'AB-', '40-56', 'Sakhamuru', 'College Road', 'Near Bus Stop', 16.51090075, 80.51845100, 182, 75, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:42', '2025-04-25 22:26:30', NULL, NULL),
(73, 'Anita Sharma', 36, '9876529729', 'anita.sharma@example.com', 'AADHAR2785', 'Andhra Pradesh', 'Amaravati', 'O+', '27-47', 'Nelapadu', 'Temple Street', 'Near Temple', 16.51212250, 80.51386775, 175, 75, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(74, 'Praveen Rao', 42, '9876588798', 'praveen.rao@example.com', 'AADHAR7100', 'Andhra Pradesh', 'Amaravati', 'O+', '3-78', 'Nekkallu', 'Market Road', 'Near Bank', 16.51053625, 80.52305450, 161, 57, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(75, 'Ramesh Reddy', 45, '9876599823', 'ramesh.reddy@example.com', 'AADHAR1029', 'Andhra Pradesh', 'Amaravati', 'O+', '31-91', 'Thullur', 'Main Road', 'Near Market', 16.51733350, 80.51922725, 151, 50, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(76, 'Anil Kumar', 44, '9876517520', 'anil.kumar@example.com', 'AADHAR3294', 'Andhra Pradesh', 'Amaravati', 'O+', '42-68', 'Nekkallu', 'Nehru Street', 'Near School', 16.51131250, 80.52256850, 186, 57, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(77, 'Kiran Naidu', 40, '9876525742', 'kiran.naidu@example.com', 'AADHAR4250', 'Andhra Pradesh', 'Amaravati', 'O+', '43-12', 'Sakhamuru', 'Hospital Road', 'Near Park', 16.50916600, 80.51396900, 160, 66, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(78, 'Sunita Reddy', 46, '9876502998', 'sunita.reddy@example.com', 'AADHAR5110', 'Andhra Pradesh', 'Amaravati', 'O-', '21-36', 'Nelapadu', 'Hospital Road', 'Near Temple', 16.51915600, 80.52021950, 153, 58, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(79, 'Meena Kumar', 50, '9876512800', 'meena.kumar@example.com', 'AADHAR7132', 'Andhra Pradesh', 'Amaravati', 'O-', '3-56', 'Sakhamuru', 'Hospital Road', 'Near Bank', 16.51675975, 80.52163025, 186, 89, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(80, 'Praveen Naidu', 22, '9876550055', 'praveen.naidu@example.com', 'AADHAR2040', 'Andhra Pradesh', 'Amaravati', 'O-', '33-100', 'Ainavolu', 'Main Road', 'Near School', 16.52048575, 80.51810675, 153, 89, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(81, 'Ravi Reddy', 50, '9876562984', 'ravi.reddy@example.com', 'AADHAR5639', 'Andhra Pradesh', 'Amaravati', 'O-', '3-10', 'Rayapudi', 'Nehru Street', 'Near Market', 16.51497100, 80.52436400, 158, 64, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL),
(82, 'Sai Kumar', 46, '9876535673', 'sai.kumar@example.com', 'AADHAR4113', 'Andhra Pradesh', 'Amaravati', 'O-', '35-18', 'Malkapuram', 'Gandhi Road', 'Near Library', 16.51843375, 80.51270000, 188, 63, '$2y$10$8LgFPSaAKCaPzN.D0iLbMe/egUAgfBzV0lN2qAZd7w87CcIWYdP/O', 'user', 'active', 0, '2025-04-25 21:02:43', '2025-04-25 22:26:30', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_backup`
--

CREATE TABLE `users_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `id_proof` varchar(20) NOT NULL,
  `country` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','hospital','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_backup`
--

INSERT INTO `users_backup` (`id`, `name`, `age`, `mobile`, `id_proof`, `country`, `state`, `city`, `blood_group`, `height`, `weight`, `password`, `role`, `created_at`) VALUES
(1, 'uhi', 55, '6777777774', '345687654345', 'India', 'Andhra Pradesh', 'Visakhapatnam', 'A+', 100, 30, '$2y$10$aXLsk2DOZwhTb6rzMg/4qe1eDdiIiY804HZMjdhQCuoKC7fStqf4W', 'user', '2025-04-24 06:49:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_banks`
--
ALTER TABLE `blood_banks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `license_number` (`license_number`),
  ADD KEY `idx_blood_banks_city` (`city`);

--
-- Indexes for table `blood_bank_inventory`
--
ALTER TABLE `blood_bank_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blood_bank_id` (`blood_bank_id`);

--
-- Indexes for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `idx_blood_requests_status` (`status`),
  ADD KEY `idx_blood_requests_blood_group` (`blood_group`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_donations_user_id` (`user_id`),
  ADD KEY `idx_donations_donation_date` (`donation_date`);

--
-- Indexes for table `donation_requests`
--
ALTER TABLE `donation_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_donation_request_hospital` (`hospital_id`),
  ADD KEY `idx_donation_request_donor` (`donor_id`),
  ADD KEY `idx_donation_request_status` (`status`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `license_number` (`license_number`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notification_user` (`user_id`),
  ADD KEY `idx_notification_status` (`status`),
  ADD KEY `idx_notification_type` (`type`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state_inventory`
--
ALTER TABLE `state_inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `state_blood_group` (`state`,`blood_group`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_proof` (`id_proof`),
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_city` (`city`),
  ADD KEY `idx_blood_group` (`blood_group`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `idx_user_status` (`status`),
  ADD KEY `idx_user_blood_group` (`blood_group`),
  ADD KEY `idx_user_state` (`state`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_banks`
--
ALTER TABLE `blood_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blood_bank_inventory`
--
ALTER TABLE `blood_bank_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `donation_requests`
--
ALTER TABLE `donation_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `state_inventory`
--
ALTER TABLE `state_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_bank_inventory`
--
ALTER TABLE `blood_bank_inventory`
  ADD CONSTRAINT `blood_bank_inventory_ibfk_1` FOREIGN KEY (`blood_bank_id`) REFERENCES `blood_banks` (`id`);

--
-- Constraints for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  ADD CONSTRAINT `blood_inventory_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`);

--
-- Constraints for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD CONSTRAINT `blood_requests_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`);

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `donation_requests`
--
ALTER TABLE `donation_requests`
  ADD CONSTRAINT `donation_requests_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`),
  ADD CONSTRAINT `donation_requests_ibfk_2` FOREIGN KEY (`donor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
