-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 21, 2025 at 08:54 AM
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
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `kra` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kras`
--

INSERT INTO `kras` (`id`, `kra`, `description`, `created_at`, `updated_at`) VALUES
(1, 'financial', NULL, '2025-05-21 01:28:14', '2025-05-21 01:28:14');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `scorecards`
--

INSERT INTO `scorecards` (`id`, `employee_id`, `evaluation_period`, `position_title`, `department`, `reviewer`, `reviewer_designation`, `created_at`, `updated_at`) VALUES
(7, 20, '2025', 'asdfa', 'asdf', '', '', '2025-05-21 05:16:13', '2025-05-21 05:16:13'),
(8, 26, '2025', 'job', 'job', 'job', 'job', '2025-05-21 05:32:23', '2025-05-21 05:32:23'),
(9, 23, '2025', 'asdfasdf', 'asdf', '', '', '2025-05-21 06:03:04', '2025-05-21 06:03:04');

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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `scorecard_goals`
--

INSERT INTO `scorecard_goals` (`id`, `scorecard_id`, `kra_id`, `perspective`, `goal`, `measurement`, `weight`, `target`, `rating_period`, `jan_value`, `feb_value`, `mar_value`, `apr_value`, `may_value`, `jun_value`, `jul_value`, `aug_value`, `sep_value`, `oct_value`, `nov_value`, `dec_value`, `rating`, `evidence`, `score`, `created_at`, `updated_at`) VALUES
(38, 7, 1, 'financial', 'asfddasd', 'Revenue', 1.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 05:16:13', '2025-05-21 05:16:13'),
(39, 7, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 05:31:41', '2025-05-21 05:31:41'),
(40, 8, 1, 'financial', 'asfddasd', 'Revenue', 1.00, 'sdfasdfasdfasdf', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 05:32:23', '2025-05-21 05:32:23'),
(41, 7, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 05:44:31', '2025-05-21 05:44:31'),
(42, 7, 1, 'financial', 'asdfasd', 'Percentage', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 05:44:39', '2025-05-21 05:44:39'),
(43, 7, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 05:50:39', '2025-05-21 05:50:39'),
(44, 7, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 05:50:48', '2025-05-21 05:50:48'),
(45, 8, 1, 'financial', 'asfddasd', 'Revenue', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 05:57:15', '2025-05-21 05:57:15'),
(46, 7, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 06:00:21', '2025-05-21 06:00:21'),
(47, 9, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 06:03:04', '2025-05-21 06:03:04'),
(48, 8, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 06:07:04', '2025-05-21 06:07:04'),
(49, 8, 1, 'financial', 'asdfasd', 'Revenue', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 06:07:27', '2025-05-21 06:07:27'),
(50, 7, 1, 'financial', 'asfddasd', 'Revenue', 1.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 06:17:31', '2025-05-21 06:17:31'),
(51, 7, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 06:17:42', '2025-05-21 06:17:42'),
(52, 7, 1, 'financial', 'asfddasd', 'Revenue', 2.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 06:24:11', '2025-05-21 06:24:11'),
(53, 7, 1, 'financial', 'asfddasd', 'Savings', 2.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 06:35:03', '2025-05-21 06:35:03'),
(54, 9, 1, 'financial', 'asfddasd', 'Revenue', 1.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 07:11:14', '2025-05-21 07:11:14'),
(55, 7, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Monthly', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 07:44:56', '2025-05-21 07:45:24'),
(56, 7, 1, 'financial', 'asfddasd', 'Savings', 1.00, 'sdfasdfasdfasdf', 'Semi Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 08:02:03', '2025-05-21 08:02:03'),
(57, 7, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 08:03:46', '2025-05-21 08:03:46'),
(58, 7, 1, 'financial', 'asfddasd', 'Percentage', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 08:03:54', '2025-05-21 08:03:54'),
(59, 8, 1, 'financial', '', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 08:10:20', '2025-05-21 08:10:20'),
(60, 8, 1, 'financial', 'asdfasd', 'Savings', 0.00, '', 'Semi Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 08:10:28', '2025-05-21 08:10:28'),
(61, 8, 1, 'financial', 'asfddasd', 'Savings', 0.00, '', 'Annual', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '', 0.00, '2025-05-21 08:52:46', '2025-05-21 08:52:46');

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
