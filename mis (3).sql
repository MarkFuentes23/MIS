-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 23, 2025 at 08:45 AM
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department`, `created_at`, `updated_at`) VALUES
(5, 'MIS', '2025-05-22 02:36:15', NULL),
(6, 'Accounting', '2025-05-22 02:36:26', NULL),
(7, 'BCI', '2025-05-22 02:36:40', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees_info`
--

INSERT INTO `employees_info` (`id`, `firstname`, `lastname`, `middlename`, `suffix`, `location`, `department`, `job_title`, `evaluation`, `created_at`, `updated_at`) VALUES
(27, 'Mark Anthony', 'Fuentes', 'T', NULL, 'Edsa', 'MIS', 'Developer', 0.0, '2025-05-22 10:38:07', '2025-05-22 10:38:07'),
(28, 'Christoper', 'Reboton', 'J', NULL, 'Gabihan Trucking', 'Accounting', 'Accounting', 0.0, '2025-05-22 10:38:31', '2025-05-22 10:38:31'),
(29, 'John', 'Cenna', 'Y.', NULL, 'Mabalacat', 'BCI', 'UI Designer', 0.0, '2025-05-22 10:39:02', '2025-05-22 10:39:02');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `job_titles`
--

INSERT INTO `job_titles` (`id`, `job_title`, `created_at`, `updated_at`) VALUES
(9, 'Accounting', '2025-05-22 02:35:47', NULL),
(10, 'UI Designer', '2025-05-22 02:35:57', NULL),
(11, 'Developer', '2025-05-22 02:36:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kras`
--

DROP TABLE IF EXISTS `kras`;
CREATE TABLE IF NOT EXISTS `kras` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `kra` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kras`
--

INSERT INTO `kras` (`id`, `kra`, `description`, `created_at`, `updated_at`) VALUES
(1, 'FInance', NULL, '2025-05-21 01:28:14', '2025-05-22 02:35:19'),
(2, 'Accounting', NULL, '2025-05-22 02:37:36', '2025-05-22 02:37:36'),
(3, 'Position', NULL, '2025-05-22 02:37:40', '2025-05-22 02:37:40');

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location`, `created_at`, `updated_at`) VALUES
(7, 'Gabihan Trucking', '2025-05-22 02:37:22', NULL),
(6, 'Mabalacat', '2025-05-22 02:37:00', NULL),
(5, 'Edsa', '2025-05-22 02:36:45', '2025-05-22 02:36:50');

-- --------------------------------------------------------

--
-- Table structure for table `scorecards`
--

DROP TABLE IF EXISTS `scorecards`;
CREATE TABLE IF NOT EXISTS `scorecards` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` int UNSIGNED NOT NULL,
  `evaluation_period` varchar(100) NOT NULL,
  `position_title` varchar(150) NOT NULL,
  `department` varchar(150) NOT NULL,
  `reviewer` varchar(150) NOT NULL,
  `reviewer_designation` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_scorecards_employee_period` (`employee_id`,`evaluation_period`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `scorecards`
--

INSERT INTO `scorecards` (`id`, `employee_id`, `evaluation_period`, `position_title`, `department`, `reviewer`, `reviewer_designation`, `created_at`, `updated_at`) VALUES
(16, 28, '2025', 'Accounting', 'Accounting', '', '', '2025-05-23 03:59:13', '2025-05-23 03:59:13'),
(17, 27, '2025', 'Developer', 'MIS', '', '', '2025-05-23 04:00:30', '2025-05-23 04:00:30'),
(18, 29, '2025', 'UI Designer', 'BCI', '', '', '2025-05-23 05:09:47', '2025-05-23 05:09:47');

-- --------------------------------------------------------

--
-- Table structure for table `scorecard_goals`
--

DROP TABLE IF EXISTS `scorecard_goals`;
CREATE TABLE IF NOT EXISTS `scorecard_goals` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `scorecard_id` int UNSIGNED NOT NULL,
  `kra_id` int UNSIGNED NOT NULL,
  `perspective` varchar(100) NOT NULL,
  `goal` text NOT NULL,
  `measurement` varchar(255) NOT NULL,
  `weight` decimal(5,2) NOT NULL DEFAULT '0.00',
  `target` varchar(255) NOT NULL,
  `rating_period` enum('Annual','Semi Annual','Quarterly','Monthly') NOT NULL DEFAULT 'Annual',
  `jan_value` decimal(10,2) DEFAULT NULL,
  `feb_value` decimal(10,2) DEFAULT NULL,
  `mar_value` decimal(10,2) DEFAULT NULL,
  `apr_value` decimal(10,2) DEFAULT NULL,
  `may_value` decimal(10,2) DEFAULT NULL,
  `jun_value` decimal(10,2) DEFAULT NULL,
  `jul_value` decimal(10,2) DEFAULT NULL,
  `aug_value` decimal(10,2) DEFAULT NULL,
  `sep_value` decimal(10,2) DEFAULT NULL,
  `oct_value` decimal(10,2) DEFAULT NULL,
  `nov_value` decimal(10,2) DEFAULT NULL,
  `dec_value` decimal(10,2) DEFAULT NULL,
  `rating` decimal(5,2) DEFAULT NULL,
  `evidence` text,
  `score` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_scg_scorecard` (`scorecard_id`),
  KEY `idx_scg_kra` (`kra_id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `scorecard_goals`
--

INSERT INTO `scorecard_goals` (`id`, `scorecard_id`, `kra_id`, `perspective`, `goal`, `measurement`, `weight`, `target`, `rating_period`, `jan_value`, `feb_value`, `mar_value`, `apr_value`, `may_value`, `jun_value`, `jul_value`, `aug_value`, `sep_value`, `oct_value`, `nov_value`, `dec_value`, `rating`, `evidence`, `score`, `created_at`, `updated_at`) VALUES
(126, 17, 2, 'financial', '', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-23 06:19:26', '2025-05-23 06:19:26'),
(130, 17, 2, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '', NULL, '2025-05-23 06:27:53', '2025-05-23 06:27:53'),
(131, 17, 1, 'financial', '', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '', NULL, '2025-05-23 06:28:06', '2025-05-23 06:28:06'),
(132, 16, 1, 'financial', '', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-23 06:40:30', '2025-05-23 06:40:30'),
(137, 18, 1, 'financial', '', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-23 06:51:42', '2025-05-23 06:51:42');

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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scorecard_goals`
--
ALTER TABLE `scorecard_goals`
  ADD CONSTRAINT `fk_scg_kra` FOREIGN KEY (`kra_id`) REFERENCES `kras` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_scg_scorecard` FOREIGN KEY (`scorecard_id`) REFERENCES `scorecards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
