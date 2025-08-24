-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2025 at 04:04 PM
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
-- Database: `sales`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_type_id` int(11) NOT NULL,
  `other_tables_id` int(11) DEFAULT NULL,
  `is_parent` enum('0','1') NOT NULL DEFAULT '0',
  `parent_account_number` int(11) DEFAULT NULL,
  `account_number` int(11) NOT NULL,
  `start_balance_status` enum('credit','debit','nun') NOT NULL DEFAULT 'credit',
  `start_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_type_id`, `other_tables_id`, `is_parent`, `parent_account_number`, `account_number`, `start_balance_status`, `start_balance`, `current_balance`, `notes`, `name`, `status`, `company_code`, `created_by`, `updated_by`, `date`, `created_at`, `updated_at`) VALUES
(1, 6, NULL, '1', NULL, 1, 'nun', 0.00, 0.00, NULL, 'حساب العملاء العام', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(2, 5, NULL, '1', NULL, 2, 'nun', 0.00, 0.00, NULL, 'حساب الموردين العام', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(3, 8, NULL, '1', NULL, 3, 'nun', 0.00, 0.00, NULL, 'حساب الموظقين العام', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(4, 7, NULL, '1', NULL, 4, 'nun', 0.00, 0.00, NULL, 'حساب المناديب العام', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(5, 3, NULL, '1', NULL, 5, 'credit', 300.00, 300.00, NULL, 'حساب المصروفات الاب', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(6, 3, NULL, '0', 5, 6, 'nun', 0.00, 0.00, NULL, 'المصروفات الفرعية', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(7, 8, NULL, '0', 3, 7, 'debit', -1000.00, -1000.00, NULL, 'admin', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(8, 2, NULL, '1', NULL, 8, 'nun', 0.00, 0.00, NULL, 'راس المال العام', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(9, 2, NULL, '0', 8, 9, 'nun', 0.00, 0.00, NULL, 'راس المال', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(10, 15, NULL, '1', NULL, 1, 'nun', 0.00, 0.00, NULL, 'حساب العملاء العام', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(11, 14, NULL, '1', NULL, 2, 'nun', 0.00, 0.00, NULL, 'حساب الموردين العام', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(12, 17, NULL, '1', NULL, 3, 'nun', 0.00, 0.00, NULL, 'حساب الموظقين العام', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(13, 16, NULL, '1', NULL, 4, 'nun', 0.00, 0.00, NULL, 'حساب المناديب العام', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(14, 12, NULL, '1', NULL, 5, 'credit', 300.00, 300.00, NULL, 'حساب المصروفات الاب', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(15, 12, NULL, '0', 5, 6, 'nun', 0.00, 0.00, NULL, 'المصروفات الفرعية', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(16, 17, NULL, '0', 3, 7, 'debit', -1000.00, -1000.00, NULL, 'admin1', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(17, 17, NULL, '0', 3, 8, 'credit', 1000.00, 1000.00, NULL, 'admin2', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(18, 11, NULL, '1', NULL, 18, 'nun', 0.00, 0.00, NULL, 'راس المال العام', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(19, 11, NULL, '0', 18, 19, 'nun', 0.00, 0.00, NULL, 'راس المال', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(20, 6, NULL, '0', 1, 8, 'nun', 0.00, 0.00, NULL, 'احمد', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(21, 6, NULL, '0', 1, 9, 'credit', -200.00, -200.00, NULL, 'محمد', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(22, 6, NULL, '0', 1, 9, 'debit', 500.00, 500.00, NULL, 'علي', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(23, 15, NULL, '0', 1, 9, 'nun', 0.00, 0.00, NULL, 'مني', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(24, 15, NULL, '0', 1, 10, 'credit', -400.00, -400.00, NULL, 'الاء', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(25, 15, NULL, '0', 1, 11, 'debit', 800.00, 800.00, NULL, 'سلمي', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(26, 5, NULL, '0', 2, 10, 'nun', 0.00, 0.00, NULL, 'مريم مورد', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(27, 5, NULL, '0', 2, 11, 'credit', -200.00, -200.00, NULL, 'هبة مورد', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(28, 5, NULL, '0', 2, 12, 'debit', 500.00, 500.00, NULL, 'اسراء مورد', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(29, 14, NULL, '0', 2, 12, 'nun', 0.00, 0.00, NULL, 'عمر مورد', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(30, 14, NULL, '0', 2, 13, 'credit', -200.00, -200.00, NULL, 'مازن مورد', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(31, 14, NULL, '0', 2, 14, 'debit', 500.00, 500.00, NULL, 'اسامة مورد', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(32, 7, NULL, '0', 4, 13, 'nun', 0.00, 0.00, NULL, 'عمر مندوب', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(33, 7, NULL, '0', 4, 14, 'credit', -200.00, -200.00, NULL, 'مسعد مندوب', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(34, 7, NULL, '0', 4, 15, 'debit', 500.00, 500.00, NULL, 'ابراهيم مندوب', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(35, 16, NULL, '0', 4, 15, 'nun', 0.00, 0.00, NULL, 'عمرو مندوب', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(36, 16, NULL, '0', 4, 16, 'credit', -200.00, -200.00, NULL, 'حمادة مندوب', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(37, 16, NULL, '0', 4, 17, 'debit', 500.00, 500.00, NULL, 'احمد مندوب', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `related_internal_accounts` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `name`, `status`, `company_code`, `created_by`, `updated_by`, `date`, `related_internal_accounts`, `created_at`, `updated_at`) VALUES
(1, 'عام', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(2, 'راس المال', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(3, 'مصروفات', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(4, 'بنك', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(5, 'مورد', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(6, 'عميل', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(7, 'مندوب', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(8, 'موظف', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(9, 'قسم داخلي', 'active', 10001000, 1, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(10, 'عام', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(11, 'راس المال', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(12, 'مصروفات', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(13, 'بنك', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(14, 'مورد', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(15, 'عميل', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(16, 'مندوب', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(17, 'موظف', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(18, 'قسم داخلي', 'active', 20002000, 2, 1, NULL, '0', '2025-07-28 13:58:51', '2025-07-28 13:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `action_histories`
--

CREATE TABLE `action_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `row_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `company_code` int(11) DEFAULT NULL,
  `employee_code` int(11) NOT NULL,
  `account_number` int(11) NOT NULL,
  `start_balance_status` enum('credit','debit','nun') NOT NULL DEFAULT 'credit',
  `start_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(255) DEFAULT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `company_code`, `employee_code`, `account_number`, `start_balance_status`, `start_balance`, `current_balance`, `notes`, `status`, `created_by`, `updated_by`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$GdqCSVq11VNd5GmNDnpLgOJBUjLuJFRmSFkJvAbH4d1wdHAyqAZku', 10001000, 1, 7, 'debit', -1000.00, -1000.00, NULL, 'active', NULL, NULL, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(2, 'admin1', 'admin1@gmail.com', NULL, '$2y$12$AATB/MkSkJcF7svkafD6luh8N8tsgSD/x0QiRLosOi9rMSY4c/qk.', 20002000, 1, 7, 'debit', -1000.00, -1000.00, NULL, 'active', NULL, NULL, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(3, 'admin2', 'admin2@gmail.com', NULL, '$2y$12$hy311LQuFAXaaYAiYXRxAujW3X6fEU8Yk0xOxKu7GxDE9jJHJyIRW', 20002000, 2, 8, 'credit', 1000.00, 1000.00, NULL, 'active', NULL, NULL, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `admin_sittings`
--

CREATE TABLE `admin_sittings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `status` enum('un_active','active') NOT NULL,
  `general_alert` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `company_code` int(11) DEFAULT NULL,
  `customer_parent_account_number` int(11) DEFAULT NULL,
  `supplier_parent_account_number` int(11) DEFAULT NULL,
  `servant_parent_account_number` int(11) DEFAULT NULL,
  `employee_parent_account_number` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_sittings`
--

INSERT INTO `admin_sittings` (`id`, `system_name`, `phone`, `photo`, `status`, `general_alert`, `address`, `company_code`, `customer_parent_account_number`, `supplier_parent_account_number`, `servant_parent_account_number`, `employee_parent_account_number`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Company 1', '01234567891', 'adminStiitngs/logo.png', 'active', NULL, 'طالبية - فيصل - الجيزة', 10001000, 1, 2, 4, 3, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(2, 'Company 2', '012345678910', 'adminStiitngs/logo.png', 'active', NULL, 'طالبية - فيصل - الجيزة', 20002000, 1, 2, 4, 3, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `admin_treasuries`
--

CREATE TABLE `admin_treasuries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `treasury_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_code` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `account_number` int(11) NOT NULL,
  `start_balance_status` enum('credit','debit','nun') NOT NULL DEFAULT 'credit',
  `start_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_code`, `name`, `account_number`, `start_balance_status`, `start_balance`, `current_balance`, `notes`, `created_by`, `updated_by`, `status`, `company_code`, `date`, `city_id`, `address`, `created_at`, `updated_at`) VALUES
(1, 1, 'احمد', 8, 'nun', 0.00, 0.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'طالبية فيصل', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 2, 'محمد', 8, 'credit', -200.00, -200.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'اكتوبر', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 3, 'علي', 9, 'debit', 500.00, 500.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'اكتوبر', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 1, 'مني', 9, 'nun', 0.00, 0.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'طالبية فيصل', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 2, 'الاء', 10, 'credit', -400.00, -400.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'اكتوبر', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 3, 'سلمي', 11, 'debit', 800.00, 800.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'اكتوبر', '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `item_type` enum('0','1','2') NOT NULL DEFAULT '0',
  `retail_unit` enum('0','1') NOT NULL DEFAULT '0',
  `item_category_id` int(11) NOT NULL,
  `item_unit_id` int(11) DEFAULT NULL,
  `sub_item_unit_id` int(11) DEFAULT NULL,
  `parent_item_id` int(11) DEFAULT NULL,
  `qty_sub_item_unit` int(11) NOT NULL DEFAULT 0,
  `item_code` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `item_wholesale_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `item_Half_wholesale_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `item_retail_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `item_cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sub_item_wholesale_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sub_item_Half_wholesale_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sub_item_retail_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sub_item_cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_qty_for_parent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sub_item_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_qty_for_sub_items` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_change` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `item_type`, `retail_unit`, `item_category_id`, `item_unit_id`, `sub_item_unit_id`, `parent_item_id`, `qty_sub_item_unit`, `item_code`, `barcode`, `item_wholesale_price`, `item_Half_wholesale_price`, `item_retail_price`, `item_cost_price`, `sub_item_wholesale_price`, `sub_item_Half_wholesale_price`, `sub_item_retail_price`, `sub_item_cost_price`, `total_qty_for_parent`, `sub_item_qty`, `total_qty_for_sub_items`, `is_change`, `status`, `company_code`, `created_by`, `updated_by`, `date`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'عليقة بادي', '1', '1', 1, 1, 3, NULL, 1000, 1, '1000', 5000.00, 5500.00, 6000.00, 4000.00, 5.00, 6.00, 7.00, 4.00, 0.00, 0.00, 0.00, '0', 'active', 10001000, 1, 1, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'عليقة نامي', '1', '1', 1, 1, 3, NULL, 1000, 2, '1111', 5500.00, 6000.00, 6500.00, 4500.00, 6.00, 8.00, 10.00, 4.50, 0.00, 0.00, 0.00, '1', 'active', 10001000, 1, 1, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'عليقة ناهي', '1', '1', 1, 1, 3, NULL, 1000, 3, '1222', 6000.00, 6500.00, 7000.00, 5000.00, 7.00, 8.00, 10.00, 5.00, 0.00, 0.00, 0.00, '1', 'active', 10001000, 1, 1, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'سماد للارض ', '0', '0', 2, 1, NULL, NULL, 0, 4, '1333', 1000.00, 1500.00, 2000.00, 500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '1', 'active', 10001000, 1, 1, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 'سماد للزراعة ', '0', '0', 2, 1, NULL, NULL, 0, 5, '1444', 3000.00, 3500.00, 4000.00, 2500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '0', 'active', 10001000, 1, 1, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 'فول', '1', '1', 3, 4, 5, NULL, 100, 1, '1000', 1500.00, 2000.00, 2500.00, 1000.00, 20.00, 25.00, 30.00, 10.00, 0.00, 0.00, 0.00, '1', 'active', 20002000, 2, 2, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(7, 'عدس', '1', '1', 3, 4, 5, NULL, 100, 2, '2000', 3000.00, 3500.00, 4000.00, 1500.00, 20.00, 25.00, 30.00, 15.00, 0.00, 0.00, 0.00, '1', 'active', 20002000, 2, 2, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(8, 'زباد', '1', '1', 4, 4, 5, NULL, 10, 3, '200', 300.00, 350.00, 400.00, 150.00, 15.00, 20.00, 25.00, 20.00, 0.00, 0.00, 0.00, '1', 'active', 20002000, 2, 2, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(9, 'سمسم', '0', '1', 3, 4, 5, NULL, 100, 4, '3500', 5000.00, 5500.00, 6000.00, 4000.00, 45.00, 60.00, 65.00, 40.00, 0.00, 0.00, 0.00, '1', 'active', 20002000, 2, 2, NULL, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `item_batches`
--

CREATE TABLE `item_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `item_unit_id` bigint(20) UNSIGNED NOT NULL,
  `item_code` int(11) NOT NULL,
  `item_cost_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deduction` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `production_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `auto_serial` int(11) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_card_movements`
--

CREATE TABLE `item_card_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_id` int(11) NOT NULL,
  `item_code` int(11) NOT NULL,
  `item_card_movements_category_id` int(11) NOT NULL,
  `item_card_movements_type_id` int(11) NOT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `purchase_orderdetiles__id` int(11) DEFAULT NULL,
  `sales_order_id` int(11) DEFAULT NULL,
  `item_batch_id` int(11) NOT NULL,
  `sales_orderdetiles__id` int(11) DEFAULT NULL,
  `qty_before_movement` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty_after_movement` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty_before_movement_in_store` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty_after_movement_in_store` decimal(10,2) NOT NULL DEFAULT 0.00,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_card_movement_categories`
--

CREATE TABLE `item_card_movement_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_card_movement_categories`
--

INSERT INTO `item_card_movement_categories` (`id`, `name`, `company_code`, `created_by`, `updated_by`, `date`, `created_at`, `updated_at`) VALUES
(1, 'اضافة كمية من الصنف الي المخزن نظير انشاء فاتورة مشتريات', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'صرف كمية من الصنف من المخزن نظير انشاء فاتورة مبيعات', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'اضافة كمية من الصنف في المخزن نظير تعديل و اضافة صنف جديد لفاتورة المشتريات ', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'صرف كمية من الصنف من المخزن نظير تعديل و اضافة صنف جديد لفاتورة المبيعات', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 'اضافة كمية من الصنف في المخزن نظير تعديل و حذف صنف من فاتورة المبيعات ', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 'صرف كمية من الصنف من المخزن نظير تعديل و حذف صنف من فاتورة المشتريات', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(7, 'اضافة كمية من الصنف في المخزن نظير مرتجع فاتورة مبيعات', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(8, 'صرف كمية من الصنف من المخزن نظير مرتجع فاتورة مشتريات', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(9, 'اضافة كمية من الصنف الي المخزن نظير انشاء فاتورة مشتريات', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(10, 'صرف كمية من الصنف من المخزن نظير انشاء فاتورة مبيعات', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(11, 'اضافة كمية من الصنف في المخزن نظير تعديل و اضافة صنف جديد لفاتورة المشتريات', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(12, 'صرف كمية من الصنف من المخزن نظير تعديل و اضافة صنف جديد لفاتورة المبيعات', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(13, 'اضافة كمية من الصنف في المخزن نظير تعديل و حذف صنف من فاتورة المبيعات', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(14, 'صرف كمية من الصنف من المخزن نظير تعديل و حذف صنف من فاتورة المشتريات', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(15, 'اضافة كمية من الصنف في المخزن نظير مرتجع فاتورة مبيعات', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(16, 'صرف كمية من الصنف من المخزن نظير مرتجع فاتورة مشتريات', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `item_card_movement_types`
--

CREATE TABLE `item_card_movement_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_card_movement_types`
--

INSERT INTO `item_card_movement_types` (`id`, `name`, `company_code`, `created_by`, `updated_by`, `date`, `created_at`, `updated_at`) VALUES
(1, 'اضافة الي المخزن', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'صرف من المخزن', 10001000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'اضافة الي المخزن', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'صرف من المخزن', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `name`, `status`, `company_code`, `created_by`, `updated_by`, `date`, `created_at`, `updated_at`) VALUES
(1, 'علف', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'سماد', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'بقوليات', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'مود غذائية', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 'ادوية بودرة', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 'ادوية سائلة', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `item_units`
--

CREATE TABLE `item_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `is_master` enum('master','sub_master') NOT NULL DEFAULT 'master',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_units`
--

INSERT INTO `item_units` (`id`, `name`, `status`, `is_master`, `company_code`, `created_by`, `updated_by`, `date`, `created_at`, `updated_at`) VALUES
(1, 'شيكارة', 'active', 'master', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'طن', 'active', 'master', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'كيلو', 'active', 'sub_master', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'كرتونة', 'active', 'master', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 'كيلو', 'active', 'sub_master', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 'زجاحة', 'active', 'master', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(7, 'سم', 'active', 'sub_master', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(8, 'طن', 'active', 'master', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `material_types`
--

CREATE TABLE `material_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_types`
--

INSERT INTO `material_types` (`id`, `name`, `status`, `company_code`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'قسم الفواتير 1', 'active', 10001000, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'قسم الفواتير 2', 'active', 10001000, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'قسم الفواتير 3', 'un_active', 10001000, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'قسم الفواتير 4', 'un_active', 10001000, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 'قسم الفواتير 5', 'un_active', 10001000, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 'قسم الفواتير 1', 'active', 20002000, 2, 2, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(7, 'قسم الفواتير 2', 'un_active', 20002000, 2, 2, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_04_05_150647_create_stores_table', 1),
(6, '2025_04_07_232501_create_admins_table', 1),
(7, '2025_04_08_070748_create_admin_sittings_table', 1),
(8, '2025_04_08_114725_create_treasuries_table', 1),
(9, '2025_04_13_155422_create_treasuries_detailes_table', 1),
(10, '2025_04_14_111436_create_material_types_table', 1),
(11, '2025_04_15_102409_create_item_units_table', 1),
(12, '2025_04_15_114614_create_item_categories_table', 1),
(13, '2025_04_15_160400_create_items_table', 1),
(14, '2025_04_27_164246_create_account_types_table', 1),
(15, '2025_04_27_175851_create_accounts_table', 1),
(16, '2025_04_30_143204_create_customers_table', 1),
(17, '2025_05_02_154840_create_supplier_categories_table', 1),
(18, '2025_05_02_163831_create_suppliers_table', 1),
(19, '2025_05_02_192244_create_purchase_orders_table', 1),
(20, '2025_05_04_165347_create_purchase_order_detailes_table', 1),
(21, '2025_05_08_223832_create_admin_treasuries_table', 1),
(22, '2025_05_10_013203_create_shifts_table', 1),
(23, '2025_05_11_052542_create_move_types_table', 1),
(24, '2025_05_11_092441_create_treasury_transations_table', 1),
(25, '2025_05_15_085724_create_item_batches_table', 1),
(26, '2025_05_16_122003_create_item_card_movement_categories_table', 1),
(27, '2025_05_16_125642_create_item_card_movement_types_table', 1),
(28, '2025_05_16_132233_create_item_card_movements_table', 1),
(29, '2025_05_19_125232_create_sales_orders_table', 1),
(30, '2025_05_19_134934_create_sales_order_details_table', 1),
(31, '2025_06_02_134104_create_servants_table', 1),
(32, '2025_06_18_201420_create_permission_tables', 1),
(33, '2025_06_24_201932_create_action_histories_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1),
(3, 'App\\Models\\Admin', 2),
(4, 'App\\Models\\Admin', 3);

-- --------------------------------------------------------

--
-- Table structure for table `move_types`
--

CREATE TABLE `move_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `in_screen` enum('pay','collect') NOT NULL DEFAULT 'pay',
  `is_private_internal` enum('global','private') NOT NULL DEFAULT 'global',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `move_types`
--

INSERT INTO `move_types` (`id`, `name`, `status`, `in_screen`, `is_private_internal`, `company_code`, `created_by`, `updated_by`, `date`, `created_at`, `updated_at`) VALUES
(1, 'صرف بفاتورة خدمات مقدمة لنا', 'active', 'pay', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'صرف لرد رأس المال', 'active', 'pay', 'private', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'صرف مرتب لموظف', 'active', 'pay', 'private', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'صرف للإيداع البنكي', 'active', 'pay', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 'صرف نظير مشتريات من مورد', 'active', 'pay', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 'صرف سلفة علي راتب موظف', 'active', 'pay', 'private', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(7, 'صرف نظير مرتجع مبيعات', 'active', 'pay', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(8, 'صرف مبلغ لحساب مالي', 'active', 'pay', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(9, 'تحصيل بفاتورة خدمات نقدمها للغير', 'active', 'collect', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(10, 'تحصيل خصومات موظفين', 'active', 'collect', 'private', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(11, 'تحصيل نظير مرتجع مشتريات الي مورد', 'active', 'collect', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(12, 'تحصيل ايراد مبيعات', 'active', 'collect', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(13, 'تحصيل مبلغ من حساب مالي', 'active', 'collect', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(14, 'سحب من البنك\\r\\n', 'active', 'collect', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(15, 'رد سلفة علي راتب موظف', 'active', 'collect', 'private', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(16, 'مصاريف شراء مثل النولون', 'active', 'pay', 'global', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(17, 'ايراد زيادة راس المال', 'active', 'collect', 'private', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(18, 'مراجعة واستلام نقدية شفت خزنة مستخدم', 'active', 'collect', 'private', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(19, 'صرف بفاتورة خدمات مقدمة لنا', 'active', 'pay', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(20, 'صرف لرد رأس المال', 'active', 'pay', 'private', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(21, 'صرف مرتب لموظف', 'active', 'pay', 'private', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(22, 'صرف للإيداع البنكي', 'active', 'pay', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(23, 'صرف نظير مشتريات من مورد', 'active', 'pay', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(24, 'صرف سلفة علي راتب موظف', 'active', 'pay', 'private', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(25, 'صرف نظير مرتجع مبيعات', 'active', 'pay', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(26, 'صرف مبلغ لحساب مالي', 'active', 'pay', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(27, 'تحصيل بفاتورة خدمات نقدمها للغير', 'active', 'collect', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(28, 'تحصيل خصومات موظفين', 'active', 'collect', 'private', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(29, 'تحصيل نظير مرتجع مشتريات الي مورد', 'active', 'collect', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(30, 'تحصيل ايراد مبيعات', 'active', 'collect', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(31, 'تحصيل مبلغ من حساب مالي', 'active', 'collect', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(32, 'سحب من البنك\\r\\n', 'active', 'collect', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(33, 'رد سلفة علي راتب موظف', 'active', 'collect', 'private', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(34, 'مصاريف شراء مثل النولون', 'active', 'pay', 'global', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(35, 'ايراد زيادة راس المال', 'active', 'collect', 'private', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(36, 'مراجعة واستلام نقدية شفت خزنة مستخدم', 'active', 'collect', 'private', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `company_code`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'عرض التفاصيل حركات النظام', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(2, 'عرض الاعدادات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(3, 'تعديل الاعدادات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(4, 'عرض الخزن و الشيفتات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(5, 'عرض الخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(6, 'اضافة خزنة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(7, 'تعديل الخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(8, 'حذف الخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(9, 'تفاصيل الخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(10, 'عرض الخزن المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(11, 'تفعيل الخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(12, 'اضافة خزنة فرعية للخزنة الرئيسية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(13, 'حذف خزنة فرعية للخزنة الرئيسية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(14, 'عرض اضافة خزن لنفس الموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(15, 'اضافة خزنة جديدة للموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(16, 'تعديل خزنة الموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(17, 'تفاصيل خزنة الموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(18, 'حذف خزنة الموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(19, 'الشيفتات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(20, 'اضافة شيفت', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(21, 'انهاء الشيفت', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(22, 'مراجعة الشيفت', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(23, 'تفاصيل الشيفت', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(24, 'الاصناف و المخازن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(25, 'فئات الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(26, 'عرض فئات الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(27, 'عرض فئات الاصناف المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(28, 'اضافة فئة جديدة للصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(29, 'تعديل فئة الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(30, 'حذف فئة الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(31, 'تفعيل فئة الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(32, 'وحدات الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(33, 'عرض وحدات الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(34, 'عرض وحدات الاصناف المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(35, 'اضافة وحدة صنف جديدة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(36, 'تعديل وحدة الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(37, 'حذف وحدة الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(38, 'تفعيل وحدة الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(39, 'عرض الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(40, 'عرض الاصناف المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(41, 'اضافة صنف جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(42, 'تعديل صنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(43, 'حذف صنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(44, 'تفاصيل الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(45, 'تفعيل صنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(46, 'عرض المخازن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(47, 'عرض المخازن المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(48, 'اضافة مخزن جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(49, 'تعديل المخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(50, 'حذف المخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(51, 'تفاصيل المخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(52, 'تفعيل المخزن', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(53, 'حركات الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(54, 'فئات حركات الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(55, 'عرض فئات حركات الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(56, 'اضافة فئة حركة جديدة للصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(57, 'تعديل فئة حركة الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(58, 'انواع حركات الاصناف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(59, 'اضافة حركة حركة جديدة للصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(60, 'تعديل حركة حركة الصنف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(61, 'حركات النقدية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(62, 'عرض انواع حركات النقدية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(63, 'عرض انواع حركات النقدية المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(64, 'اضافة نوع حركة النقدية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(65, 'تعديل انواع حركات النقدية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(66, 'حذف انواع حركات النقدية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(67, 'تفعيل انواع حركات النقدية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(68, 'الحسابات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(69, 'انواع الحسابات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(70, 'عرض انواع الحسابات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(71, 'عرض انواع الحسابات المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(72, 'اضافة نوع حساب جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(73, 'تعديل نوع الحساب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(74, 'حذف نوع الحساب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(75, 'تفعيل نوع الحساب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(76, 'عرض كل الحسابات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(77, 'عرض كل الحسابات المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(78, 'اضافة حساب جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(79, 'تعديل الحساب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(80, 'حذف الحساب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(81, 'تفاصيل الحساب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(82, 'تفعيل الحساب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(83, 'اقسام الموردين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(84, 'عرض اقسام الموردين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(85, 'عرض اقسام الموردين المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(86, 'اضافة قسم جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(87, 'تعديل القسم', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(88, 'حذف القسم', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(89, 'تفعيل القسم', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(90, 'الموردين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(91, 'عرض كل الموردين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(92, 'عرض كل الموردين المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(93, 'اضافة مورد جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(94, 'تعديل مورد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(95, 'حذف مورد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(96, 'تفعيل مورد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(97, 'تفاصيل مورد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(98, 'الموظفين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(99, 'عرض كل الموظفين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(100, 'عرض الموظفين المحذوفة', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(101, 'اضافة موظف جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(102, 'تعديل الموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(103, 'حذف الموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(104, 'تفاصيل الموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(105, 'تفعيل الموظف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(106, 'العملاء', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(107, 'عرض كل العملاء', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(108, 'عرض العملاء المحذوفين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(109, 'اضافة عميل جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(110, 'تعديل العميل', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(111, 'تفاصيل العميل', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(112, 'حذف العميل', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(113, 'تفعيل العميل', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(114, 'المناديب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(115, 'عرض كل المناديب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(116, 'عرض المناديب المحذوفين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(117, 'اضافة مندوب جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(118, 'تعديل المندوب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(119, 'تفاصيل المندوب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(120, 'حذف المندوب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(121, 'تفعيل المندوب', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(122, 'حركات تحصيل النقدية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(123, 'اضافة تحصيل جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(124, 'تعديل حركة تحصيل', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(125, 'حذف حركة تحصيل', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(126, 'تفاصيل حركة تحصيل', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(127, 'حركات صرف النقدية', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(128, 'اضافة صرف جديد', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(129, 'تعديل حركة صرف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(130, 'حذف حركة صرف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(131, 'تفاصيل حركة صرف', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(132, 'الفواتير', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(133, 'عرض فواتير المبيعات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(134, 'اضافة فاتورة المبيعات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(135, 'تعديل فاتورة المبيعات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(136, 'حذف فاتورة المبيعات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(137, 'تفاصيل فاتورة المبيعات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(138, 'عرض فواتير المشتريات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(139, 'اضافة فاتورة المشتريات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(140, 'تعديل فاتورة المشتريات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(141, 'حذف فاتورة المشتريات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(142, 'تفاصيل فاتورة المشتريات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(143, 'اعتماد فاتورة المشتريات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(144, 'عرض شيفتات كل المستخدمين', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(145, 'اضافة صنف جديد لفاتورة المبيعات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(146, 'تعديل صنف في فاتورة المبيعات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50'),
(147, 'حذف صنف من فاتورة المبيعات', 'admin', 10001000, 1, 1, '2025-07-28 13:58:50', '2025-07-28 13:58:50');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_type` enum('0','1','2') NOT NULL DEFAULT '0',
  `auto_serial` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `supplier_code` int(11) NOT NULL,
  `approve` enum('0','1') NOT NULL DEFAULT '0',
  `notes` varchar(255) DEFAULT NULL,
  `store_id` int(11) NOT NULL,
  `total_cost_before_all` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('0','1') DEFAULT '0',
  `discount_percent` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `tax_percent` decimal(10,2) DEFAULT 0.00,
  `tax_value` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `mony_for_account` decimal(10,2) NOT NULL DEFAULT 0.00,
  `invoice_type` enum('0','1') DEFAULT '0',
  `paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unpaid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `treasures_transactions` int(11) DEFAULT NULL,
  `supplier_balance_before` decimal(10,2) NOT NULL DEFAULT 0.00,
  `supplier_balance_after` decimal(10,2) NOT NULL DEFAULT 0.00,
  `company_code` int(11) NOT NULL,
  `account_number` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_detailes`
--

CREATE TABLE `purchase_order_detailes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_type` enum('0','1','2') NOT NULL DEFAULT '0',
  `auto_serial_purchase_orders` int(11) NOT NULL,
  `company_code` int(11) NOT NULL,
  `item_code` int(11) NOT NULL,
  `item_units_id` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `is_master` enum('master','sub_master') NOT NULL DEFAULT 'master',
  `item_type` enum('0','1','2') NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_date` date NOT NULL,
  `production_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `company_code`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '10001000 مدير رئيسي', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(2, '10001000مدير فرعي', 'admin', 10001000, 1, 1, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(3, 'مدير رئيسي 20002000', 'admin', 20002000, 2, 2, '2025-07-28 13:58:49', '2025-07-28 13:58:49'),
(4, '20002000 مدير فرعي', 'admin', 20002000, 2, 2, '2025-07-28 13:58:49', '2025-07-28 13:58:49');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(2, 1),
(2, 3),
(3, 1),
(3, 3),
(4, 1),
(4, 3),
(5, 1),
(5, 3),
(6, 1),
(6, 3),
(7, 1),
(7, 3),
(8, 1),
(8, 3),
(9, 1),
(9, 3),
(10, 1),
(10, 3),
(11, 1),
(11, 3),
(12, 1),
(12, 3),
(13, 1),
(13, 3),
(14, 1),
(14, 3),
(15, 1),
(15, 3),
(16, 1),
(16, 3),
(17, 1),
(17, 3),
(18, 1),
(18, 3),
(19, 1),
(19, 3),
(20, 1),
(20, 3),
(21, 1),
(21, 3),
(22, 1),
(22, 3),
(23, 1),
(23, 3),
(24, 1),
(24, 3),
(25, 1),
(25, 3),
(26, 1),
(26, 3),
(27, 1),
(27, 3),
(28, 1),
(28, 3),
(29, 1),
(29, 3),
(30, 1),
(30, 3),
(31, 1),
(31, 3),
(32, 1),
(32, 3),
(33, 1),
(33, 3),
(34, 1),
(34, 3),
(35, 1),
(35, 3),
(36, 1),
(36, 3),
(37, 1),
(37, 3),
(38, 1),
(38, 3),
(39, 1),
(39, 3),
(40, 1),
(40, 3),
(41, 1),
(41, 3),
(42, 1),
(42, 3),
(43, 1),
(43, 3),
(44, 1),
(44, 3),
(45, 1),
(45, 3),
(46, 1),
(46, 3),
(47, 1),
(47, 3),
(48, 1),
(48, 3),
(49, 1),
(49, 3),
(50, 1),
(50, 3),
(51, 1),
(51, 3),
(52, 1),
(52, 3),
(53, 1),
(53, 3),
(54, 1),
(54, 3),
(55, 1),
(55, 3),
(56, 1),
(56, 3),
(57, 1),
(57, 3),
(58, 1),
(58, 3),
(59, 1),
(59, 3),
(60, 1),
(60, 3),
(61, 1),
(61, 3),
(62, 1),
(62, 3),
(63, 1),
(63, 3),
(64, 1),
(64, 3),
(65, 1),
(65, 3),
(66, 1),
(66, 3),
(67, 1),
(67, 3),
(68, 1),
(68, 3),
(69, 1),
(69, 3),
(70, 1),
(70, 3),
(71, 1),
(71, 3),
(72, 1),
(72, 3),
(73, 1),
(73, 3),
(74, 1),
(74, 3),
(75, 1),
(75, 3),
(76, 1),
(76, 3),
(77, 1),
(77, 3),
(78, 1),
(78, 3),
(79, 1),
(79, 3),
(80, 1),
(80, 3),
(81, 1),
(81, 3),
(82, 1),
(82, 3),
(83, 1),
(83, 3),
(84, 1),
(84, 3),
(85, 1),
(85, 3),
(86, 1),
(86, 3),
(87, 1),
(87, 3),
(88, 1),
(88, 3),
(89, 1),
(89, 3),
(90, 1),
(90, 3),
(91, 1),
(91, 3),
(92, 1),
(92, 3),
(93, 1),
(93, 3),
(94, 1),
(94, 3),
(95, 1),
(95, 3),
(96, 1),
(96, 3),
(97, 1),
(97, 3),
(98, 1),
(98, 3),
(99, 1),
(99, 3),
(100, 1),
(100, 3),
(101, 1),
(101, 3),
(102, 1),
(102, 3),
(103, 1),
(103, 3),
(104, 1),
(104, 3),
(105, 1),
(105, 3),
(106, 1),
(106, 3),
(107, 1),
(107, 3),
(108, 1),
(108, 3),
(109, 1),
(109, 3),
(110, 1),
(110, 3),
(111, 1),
(111, 3),
(112, 1),
(112, 3),
(113, 1),
(113, 3),
(114, 1),
(114, 3),
(115, 1),
(115, 3),
(116, 1),
(116, 3),
(117, 1),
(117, 3),
(118, 1),
(118, 3),
(119, 1),
(119, 3),
(120, 1),
(120, 3),
(121, 1),
(121, 3),
(122, 1),
(122, 3),
(123, 1),
(123, 3),
(124, 1),
(124, 3),
(125, 1),
(125, 3),
(126, 1),
(126, 3),
(127, 1),
(127, 3),
(128, 1),
(128, 3),
(129, 1),
(129, 3),
(130, 1),
(130, 3),
(131, 1),
(131, 3),
(132, 1),
(132, 3),
(133, 1),
(133, 3),
(134, 1),
(134, 3),
(135, 1),
(135, 3),
(136, 1),
(136, 3),
(137, 1),
(137, 3),
(138, 1),
(138, 3),
(139, 1),
(139, 3),
(140, 1),
(140, 3),
(141, 1),
(141, 3),
(142, 1),
(142, 3),
(143, 1),
(143, 3),
(144, 1),
(144, 3),
(145, 1),
(145, 3),
(146, 1),
(146, 3),
(147, 1),
(147, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `auto_serial` int(11) NOT NULL,
  `auto_serial_servant_invoice` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `customer_code` int(11) NOT NULL,
  `customer_account_number` int(11) NOT NULL,
  `servant_code` int(11) DEFAULT NULL,
  `matrial_types_id` int(11) NOT NULL,
  `treasures_transactions_id` int(11) DEFAULT NULL,
  `approve` enum('0','1') NOT NULL DEFAULT '0',
  `is_fixed_customer` enum('0','1') NOT NULL DEFAULT '0',
  `sales_item_type` enum('0','1','2') NOT NULL DEFAULT '0',
  `items_type` enum('0','1') NOT NULL DEFAULT '0',
  `total_cost_before_all` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('0','1') DEFAULT '0',
  `discount_percent` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `total_before_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_percent` decimal(10,2) DEFAULT 0.00,
  `tax_value` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `mony_for_account` decimal(10,2) NOT NULL DEFAULT 0.00,
  `invoice_type` enum('0','1') DEFAULT '0',
  `paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unpaid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `servant_commission_percent_type` decimal(10,2) DEFAULT 0.00,
  `servant_commission_percent` decimal(10,2) DEFAULT 0.00,
  `servant_commission_amount` decimal(10,2) DEFAULT 0.00,
  `customer_balance_before` decimal(10,2) NOT NULL DEFAULT 0.00,
  `customer_balance_after` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(255) DEFAULT NULL,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_details`
--

CREATE TABLE `sales_order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_item_type_detailes` enum('0','1','2') NOT NULL DEFAULT '0',
  `item_type` enum('0','1','2') NOT NULL,
  `auto_serial_sales_order` int(11) NOT NULL,
  `item_code` int(11) NOT NULL,
  `item_units_id` int(11) NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `is_master` enum('master','sub_master') NOT NULL DEFAULT 'master',
  `is_bouns` enum('yes','no') NOT NULL DEFAULT 'no',
  `qty` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_date` date NOT NULL,
  `production_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servants`
--

CREATE TABLE `servants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `servant_code` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `account_number` int(11) NOT NULL,
  `start_balance_status` enum('credit','debit','nun') NOT NULL DEFAULT 'credit',
  `commission_type` enum('fixed','not_fixed') NOT NULL DEFAULT 'fixed',
  `start_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `servants`
--

INSERT INTO `servants` (`id`, `servant_code`, `name`, `account_number`, `start_balance_status`, `commission_type`, `start_balance`, `current_balance`, `notes`, `created_by`, `updated_by`, `status`, `company_code`, `date`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 1, 'عمر مندوب', 13, 'nun', 'fixed', 0.00, 0.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'طالبية فيصل', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 2, 'مسعد مندوب', 14, 'credit', 'fixed', -200.00, -200.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'اكتوبر', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 3, 'ابراهيم مندوب', 15, 'debit', 'fixed', 500.00, 500.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'اكتوبر', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 1, 'عمرو مندوب', 15, 'nun', 'fixed', 0.00, 0.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'طالبية فيصل', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 2, 'حمادة مندوب', 16, 'credit', 'fixed', -200.00, -200.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'اكتوبر', '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 3, 'احمد مندوب', 17, 'debit', 'fixed', 500.00, 500.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'اكتوبر', '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `treasury_id` bigint(20) UNSIGNED NOT NULL,
  `start_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `shift_status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `is_delevered_review` enum('yes','no') NOT NULL DEFAULT 'no',
  `delevered_to_admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delevered_to_shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delevered_to_treasury_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cash_should_delevered` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_actually_delivered` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_status` enum('nun','plus','mins') NOT NULL DEFAULT 'nun',
  `cash_status_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `recive_type` enum('same','anther') NOT NULL DEFAULT 'anther',
  `Review_recive_date` datetime DEFAULT NULL,
  `treasuries_transaction_id` int(11) DEFAULT NULL,
  `auto_serial` int(11) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `status`, `company_code`, `created_by`, `updated_by`, `phone`, `address`, `date`, `created_at`, `updated_at`) VALUES
(1, 'مخزن 1', 'active', 10001000, 1, 1, '342391', 'طالبية جيزة', NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'مخزن 2', 'active', 10001000, 1, 1, '175298935', 'ميامي الاسكندرية', NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'مخزن 3', 'active', 10001000, 1, 1, '17488247', 'طنطا الغربية', NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'مخزن 1', 'active', 20002000, 2, 2, '342391', 'طالبية فيصل', NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 'مخزن 2', 'active', 20002000, 2, 2, '175298935', 'مندرة الاسكندرية', NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_code` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `account_number` int(11) NOT NULL,
  `start_balance_status` enum('credit','debit','nun') NOT NULL DEFAULT 'credit',
  `start_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `supplier_Category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_code`, `name`, `account_number`, `start_balance_status`, `start_balance`, `current_balance`, `notes`, `created_by`, `updated_by`, `status`, `company_code`, `date`, `city_id`, `address`, `supplier_Category_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'مريم مورد', 10, 'nun', 0.00, 0.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'طالبية فيصل', 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 2, 'هبة مورد', 11, 'credit', -200.00, -200.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'اكتوبر', 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 3, 'اسراء مورد', 12, 'debit', 500.00, 500.00, NULL, 1, 1, 'active', 10001000, NULL, NULL, 'اكتوبر', 2, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 1, 'عمر مورد', 12, 'nun', 0.00, 0.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'طالبية فيصل', 3, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 2, 'مازن مورد', 13, 'credit', -200.00, -200.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'اكتوبر', 4, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 3, 'اسامة مورد', 14, 'debit', 500.00, 500.00, NULL, 2, 2, 'active', 20002000, NULL, NULL, 'اكتوبر', 4, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_categories`
--

CREATE TABLE `supplier_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_categories`
--

INSERT INTO `supplier_categories` (`id`, `name`, `status`, `company_code`, `created_by`, `updated_by`, `date`, `created_at`, `updated_at`) VALUES
(1, 'موردين اعلاف', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 'موردين سماد', 'active', 10001000, 1, 1, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 'موردين بقوليات', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 'موردين مواد غذائية', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 'ادوبة مستوردة سائلة', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 'ادوية محلية سائلة', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(7, 'ادوية مستوردة بودرة', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(8, 'ادوية محلية بودرة', 'active', 20002000, 2, 2, NULL, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `treasuries`
--

CREATE TABLE `treasuries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('active','un_active') NOT NULL DEFAULT 'active',
  `is_master` enum('master','user') NOT NULL DEFAULT 'master',
  `last_recept_pay` bigint(20) DEFAULT NULL,
  `last_recept_recive` bigint(20) DEFAULT NULL,
  `company_code` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `treasuries`
--

INSERT INTO `treasuries` (`id`, `name`, `status`, `is_master`, `last_recept_pay`, `last_recept_recive`, `company_code`, `created_by`, `updated_by`, `date`, `created_at`, `updated_at`) VALUES
(1, 'خزنة 1', 'active', 'master', 0, 0, 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(2, 'خزنة 2', 'active', 'user', 0, 0, 10001000, 1, 1, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(3, 'خزنة 1', 'active', 'master', 0, 0, 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51'),
(4, 'خزنة 2', 'active', 'user', 0, 0, 20002000, 2, 2, NULL, '2025-07-28 13:58:51', '2025-07-28 13:58:51');

-- --------------------------------------------------------

--
-- Table structure for table `treasuries_detailes`
--

CREATE TABLE `treasuries_detailes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `treasuries_id` int(11) DEFAULT NULL,
  `sub_treasuries_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `treasuries_detailes`
--

INSERT INTO `treasuries_detailes` (`id`, `treasuries_id`, `sub_treasuries_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(2, 1, 2, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(3, 2, 1, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(4, 2, 2, 1, 1, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(5, 3, 3, 2, 2, '2025-07-28 13:58:52', '2025-07-28 13:58:52'),
(6, 3, 4, 2, 2, '2025-07-28 13:58:52', '2025-07-28 13:58:52');

-- --------------------------------------------------------

--
-- Table structure for table `treasury_transations`
--

CREATE TABLE `treasury_transations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `treasury_id` bigint(20) UNSIGNED NOT NULL,
  `moveType_id` bigint(20) UNSIGNED NOT NULL,
  `servant_account_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `invoice_type_accounts` enum('purchases','sales') DEFAULT NULL,
  `invoice_type` enum('0','1') NOT NULL DEFAULT '0',
  `shift_id` int(11) DEFAULT NULL,
  `cash_source_type` enum('account','treasury') NOT NULL DEFAULT 'account',
  `account_type` enum('suppliers','customers','servants','employee','general') NOT NULL DEFAULT 'general',
  `is_approve` enum('approve','un_approve') NOT NULL DEFAULT 'approve',
  `cash_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `servant_cash_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_for_account` decimal(10,2) NOT NULL DEFAULT 0.00,
  `account_balance_before` decimal(10,2) NOT NULL DEFAULT 0.00,
  `account_balance_after` decimal(10,2) NOT NULL DEFAULT 0.00,
  `auto_serial` int(11) NOT NULL,
  `isal_number` int(11) NOT NULL,
  `move_date` date DEFAULT NULL,
  `company_code` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `action_histories`
--
ALTER TABLE `action_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `admin_sittings`
--
ALTER TABLE `admin_sittings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_sittings_system_name_unique` (`system_name`),
  ADD UNIQUE KEY `admin_sittings_company_code_unique` (`company_code`);

--
-- Indexes for table `admin_treasuries`
--
ALTER TABLE `admin_treasuries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_treasuries_admin_id_foreign` (`admin_id`),
  ADD KEY `admin_treasuries_treasury_id_foreign` (`treasury_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_batches`
--
ALTER TABLE `item_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_batches_store_id_foreign` (`store_id`),
  ADD KEY `item_batches_item_unit_id_foreign` (`item_unit_id`);

--
-- Indexes for table `item_card_movements`
--
ALTER TABLE `item_card_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_card_movement_categories`
--
ALTER TABLE `item_card_movement_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_card_movement_types`
--
ALTER TABLE `item_card_movement_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_units`
--
ALTER TABLE `item_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_types`
--
ALTER TABLE `material_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `move_types`
--
ALTER TABLE `move_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_detailes`
--
ALTER TABLE `purchase_order_detailes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_details`
--
ALTER TABLE `sales_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servants`
--
ALTER TABLE `servants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shifts_admin_id_foreign` (`admin_id`),
  ADD KEY `shifts_treasury_id_foreign` (`treasury_id`),
  ADD KEY `shifts_delevered_to_admin_id_foreign` (`delevered_to_admin_id`),
  ADD KEY `shifts_delevered_to_treasury_id_foreign` (`delevered_to_treasury_id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_categories`
--
ALTER TABLE `supplier_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treasuries`
--
ALTER TABLE `treasuries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treasuries_detailes`
--
ALTER TABLE `treasuries_detailes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treasury_transations`
--
ALTER TABLE `treasury_transations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treasury_transations_treasury_id_foreign` (`treasury_id`),
  ADD KEY `treasury_transations_movetype_id_foreign` (`moveType_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `action_histories`
--
ALTER TABLE `action_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_sittings`
--
ALTER TABLE `admin_sittings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_treasuries`
--
ALTER TABLE `admin_treasuries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `item_batches`
--
ALTER TABLE `item_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_card_movements`
--
ALTER TABLE `item_card_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_card_movement_categories`
--
ALTER TABLE `item_card_movement_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `item_card_movement_types`
--
ALTER TABLE `item_card_movement_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item_units`
--
ALTER TABLE `item_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `material_types`
--
ALTER TABLE `material_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `move_types`
--
ALTER TABLE `move_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_detailes`
--
ALTER TABLE `purchase_order_detailes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_details`
--
ALTER TABLE `sales_order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servants`
--
ALTER TABLE `servants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `supplier_categories`
--
ALTER TABLE `supplier_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `treasuries`
--
ALTER TABLE `treasuries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `treasuries_detailes`
--
ALTER TABLE `treasuries_detailes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `treasury_transations`
--
ALTER TABLE `treasury_transations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_treasuries`
--
ALTER TABLE `admin_treasuries`
  ADD CONSTRAINT `admin_treasuries_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_treasuries_treasury_id_foreign` FOREIGN KEY (`treasury_id`) REFERENCES `treasuries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_batches`
--
ALTER TABLE `item_batches`
  ADD CONSTRAINT `item_batches_item_unit_id_foreign` FOREIGN KEY (`item_unit_id`) REFERENCES `item_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_batches_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shifts`
--
ALTER TABLE `shifts`
  ADD CONSTRAINT `shifts_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shifts_delevered_to_admin_id_foreign` FOREIGN KEY (`delevered_to_admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shifts_delevered_to_treasury_id_foreign` FOREIGN KEY (`delevered_to_treasury_id`) REFERENCES `treasuries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shifts_treasury_id_foreign` FOREIGN KEY (`treasury_id`) REFERENCES `treasuries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `treasury_transations`
--
ALTER TABLE `treasury_transations`
  ADD CONSTRAINT `treasury_transations_movetype_id_foreign` FOREIGN KEY (`moveType_id`) REFERENCES `move_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treasury_transations_treasury_id_foreign` FOREIGN KEY (`treasury_id`) REFERENCES `treasuries` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
