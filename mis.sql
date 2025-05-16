-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 16, 2025 at 07:19 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mis`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `department` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department`, `created_at`, `updated_at`) VALUES
(4, 'job', '2025-05-09 01:45:56', NULL),
(2, 'asdf', '2025-05-06 08:27:44', NULL),
(3, 'asdf', '2025-05-06 08:37:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees_info`
--

DROP TABLE IF EXISTS `employees_info`;
CREATE TABLE IF NOT EXISTS `employees_info` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `evaluation` decimal(3,1) DEFAULT '0.0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees_info`
--

INSERT INTO `employees_info` (`id`, `firstname`, `lastname`, `middlename`, `suffix`, `location`, `department`, `job_title`, `evaluation`, `created_at`, `updated_at`) VALUES
(20, 'BCI', 'asdfasdf', 'asdfasdf', 'jr', 'asdfas', 'asdf', 'asdfa', 1.0, '2025-05-07 10:31:07', '2025-05-09 09:46:30'),
(23, 'hello', 'world', 'Z', 'jr', 'asdfas', 'asdf', 'asdfasdf', 1.0, '2025-05-09 09:45:41', '2025-05-09 09:45:41'),
(26, 'topher', 'azores', 'Z', 'jr', 'job', 'job', 'job', 2.0, '2025-05-15 09:07:10', '2025-05-15 09:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `job_title_id` int NOT NULL,
  `reviewer_id` int NOT NULL,
  `evaluation_period` int NOT NULL,
  `department_id` int NOT NULL,
  `reviewer_designation_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `job_title_id` (`job_title_id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `department_id` (`department_id`),
  KEY `reviewer_designation_id` (`reviewer_designation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_forms`
--

DROP TABLE IF EXISTS `evaluation_forms`;
CREATE TABLE IF NOT EXISTS `evaluation_forms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `evaluation_score` decimal(10,2) DEFAULT '0.00',
  `reviewer_id` int DEFAULT NULL,
  `reviewer_designation` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_titles`
--

DROP TABLE IF EXISTS `job_titles`;
CREATE TABLE IF NOT EXISTS `job_titles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_title` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `job_titles`
--

INSERT INTO `job_titles` (`id`, `job_title`, `created_at`, `updated_at`) VALUES
(8, 'job', '2025-05-09 01:45:51', NULL),
(6, 'asdfa', '2025-05-07 02:30:01', NULL),
(7, 'asdfasdf', '2025-05-07 02:31:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kras`
--

DROP TABLE IF EXISTS `kras`;
CREATE TABLE IF NOT EXISTS `kras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kra` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kras`
--

INSERT INTO `kras` (`id`, `kra`, `created_at`, `updated_at`) VALUES
(2, 'bestlink', '2025-05-14 07:51:54', '2025-05-15 00:06:41'),
(5, 'asdfasdfasd', '2025-05-14 08:35:23', NULL),
(4, 'asdfasdfsa', '2025-05-14 08:02:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location`, `created_at`, `updated_at`) VALUES
(1, 'asdfas', '2025-05-06 03:48:20', NULL),
(2, 'asdf', '2025-05-06 08:26:23', NULL),
(3, 'ortigas', '2025-05-06 08:27:38', NULL),
(4, 'job', '2025-05-09 01:46:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'mark@gmail.com', '$2y$10$eJi946pgqdLLe5MOfflLOOJh00PQ9lxnMmpUOsZMtFQmzekEZTmiS', '2025-05-05 07:52:24', '2025-05-05 07:52:24');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
