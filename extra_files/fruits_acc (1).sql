-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2025 at 10:25 PM
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
-- Database: `fruits_acc`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounting_periods`
--

CREATE TABLE `accounting_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fiscal_year_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_periods`
--

INSERT INTO `accounting_periods` (`id`, `fiscal_year_id`, `name`, `start_date`, `end_date`, `is_closed`, `created_at`, `updated_at`) VALUES
(1, 1, '2026', '2025-11-01', '2025-11-30', 0, '2025-11-08 15:01:03', '2025-11-08 15:01:03');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `name`, `account_number`, `bank_name`, `current_balance`, `created_at`, `updated_at`) VALUES
(1, 'DBBL Main Account', '123456789', 'Dutch Bangla Bank', 18800.00, '2025-11-08 11:34:05', '2025-11-08 11:46:54'),
(2, 'City Bank Account', '5549365568', 'City Bank', 0.00, '2025-11-08 11:51:28', '2025-11-08 13:00:37');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transactions`
--

CREATE TABLE `bank_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_date` date NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` enum('deposit','withdrawal') NOT NULL,
  `is_reconciled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_transactions`
--

INSERT INTO `bank_transactions` (`id`, `bank_account_id`, `transaction_date`, `description`, `amount`, `type`, `is_reconciled`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-08', 'Deposit from customer', 5000.00, 'deposit', 0, '2025-11-08 11:34:15', '2025-11-08 11:37:37'),
(2, 1, '2025-11-14', NULL, 2000.00, 'withdrawal', 0, '2025-11-08 11:43:44', '2025-11-08 11:43:44'),
(4, 2, '2025-11-08', NULL, 20000.00, 'withdrawal', 0, '2025-11-08 13:00:37', '2025-11-08 13:01:05');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) NOT NULL,
  `bill_number` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `vendor_id`, `bill_number`, `date`, `due_date`, `total`, `paid_amount`, `status`, `posted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'BILL-00001', '2025-11-08', '2025-11-20', 3000.00, 3000.00, 'paid', NULL, '2025-11-08 07:26:20', '2025-11-08 13:38:18'),
(2, 2, 'BILL-00002', '2025-11-08', '2025-11-20', 3000.00, 0.00, 'draft', NULL, '2025-11-08 07:30:05', '2025-11-08 07:30:05'),
(3, 2, 'BILL-00003', '2025-11-08', '2025-11-20', 3000.00, 0.00, 'draft', NULL, '2025-11-08 07:30:17', '2025-11-08 07:30:17'),
(4, 2, 'BILL-00004', '2025-11-08', NULL, 5000.00, 5000.00, 'paid', NULL, '2025-11-08 07:53:54', '2025-11-08 09:34:48'),
(5, 2, 'BILL-00005', '2025-11-08', NULL, 5000.00, 0.00, 'draft', NULL, '2025-11-08 08:00:53', '2025-11-08 09:02:49'),
(6, 1, 'BILL-00006', '2025-11-08', NULL, 528.00, 0.00, 'draft', NULL, '2025-11-08 09:02:22', '2025-11-08 09:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` bigint(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_total_excl_tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`id`, `bill_id`, `description`, `qty`, `unit_price`, `tax_amount`, `line_total_excl_tax`, `created_at`, `updated_at`) VALUES
(1, 1, 'Office Supplies', 2.00, 1500.00, 0.00, 3000.00, '2025-11-08 07:26:20', '2025-11-08 07:26:20'),
(2, 2, 'Office invent Supplies', 2.00, 1500.00, 0.00, 3000.00, '2025-11-08 07:30:05', '2025-11-08 07:30:05'),
(3, 3, 'Office invent Supplies', 2.00, 1500.00, 0.00, 3000.00, '2025-11-08 07:30:17', '2025-11-08 07:30:17'),
(4, 4, 'sfsfffs', 1.00, 5000.00, 0.00, 5000.00, '2025-11-08 07:53:54', '2025-11-08 07:53:54'),
(6, 5, 'gdgd', 1.00, 5000.00, 0.00, 5000.00, '2025-11-08 08:08:31', '2025-11-08 08:08:31'),
(9, 6, 'gdgg', 1.00, 528.00, 0.00, 528.00, '2025-11-08 09:09:59', '2025-11-08 09:09:59');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `type` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Grand Hotel', 'grand@hotel.com', '0188888888', 'Chattogram', '2025-11-08 07:56:54', '2025-11-08 07:56:54'),
(2, 'Radison Blue', 'radison@hotel.com', '0188888888', 'Chattogram', '2025-11-08 07:57:08', '2025-11-08 07:57:08');

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
-- Table structure for table `fiscal_years`
--

CREATE TABLE `fiscal_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fiscal_years`
--

INSERT INTO `fiscal_years` (`id`, `name`, `start_date`, `end_date`, `is_locked`, `created_at`, `updated_at`) VALUES
(1, '2025', '2025-06-01', '2025-12-30', 0, '2025-11-08 14:58:08', '2025-11-08 14:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','posted') NOT NULL DEFAULT 'draft',
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_id`, `invoice_number`, `date`, `due_date`, `total`, `paid_amount`, `status`, `posted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'INV-00001', '2025-11-08', NULL, 500.00, 500.00, 'draft', NULL, '2025-11-08 07:57:26', '2025-11-08 13:42:51'),
(2, 2, 'INV-00002', '2025-11-13', NULL, 8550.00, 0.00, 'draft', NULL, '2025-11-08 10:22:01', '2025-11-08 13:42:44'),
(3, 2, 'INV-00003', '2025-11-14', NULL, 20000.00, 0.00, 'draft', NULL, '2025-11-08 13:43:11', '2025-11-08 13:43:11'),
(4, 1, 'INV-00004', '2025-11-08', NULL, 7000.00, 0.00, 'draft', NULL, '2025-11-08 13:53:01', '2025-11-08 13:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `line_total_excl_tax` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `qty`, `unit_price`, `tax_amount`, `line_total_excl_tax`, `created_at`, `updated_at`) VALUES
(1, 1, 'dgdgdfg', 1.00, 500.00, 0.00, 500.00, '2025-11-08 07:57:26', '2025-11-08 07:57:26'),
(2, 2, 'kjhj', 1.00, 8550.00, 0.00, 8550.00, '2025-11-08 10:22:01', '2025-11-08 10:22:01'),
(3, 3, 'gdfsgg', 1.00, 20000.00, 0.00, 20000.00, '2025-11-08 13:43:11', '2025-11-08 13:43:11'),
(4, 4, 'hyyhrhy', 1.00, 7000.00, 0.00, 7000.00, '2025-11-08 13:53:01', '2025-11-08 13:53:01');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `date`, `memo`, `posted_at`, `created_at`, `updated_at`) VALUES
(1, '2025-11-08', 'kij56', NULL, '2025-11-08 10:19:50', '2025-11-08 10:19:50'),
(2, '2025-11-09', 'khhn', NULL, '2025-11-08 10:20:44', '2025-11-08 10:20:44');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry_items`
--

CREATE TABLE `journal_entry_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_entry_id` bigint(20) NOT NULL,
  `account_id` bigint(20) NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entry_items`
--

INSERT INTO `journal_entry_items` (`id`, `journal_entry_id`, `account_id`, `debit`, `credit`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 500.00, 500.00, '2025-11-08 10:19:50', '2025-11-08 10:19:50'),
(2, 2, 2, 852.00, 852.00, '2025-11-08 10:20:44', '2025-11-08 10:20:44');

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
(22, '2014_10_12_000000_create_users_table', 1),
(23, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(24, '2019_08_19_000000_create_failed_jobs_table', 1),
(25, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(26, '2025_11_02_041342_create_customers_table', 1),
(27, '2025_11_02_041342_create_vendors_table', 1),
(28, '2025_11_02_041343_create_invoices_table', 1),
(29, '2025_11_02_041344_create_bills_table', 1),
(30, '2025_11_02_041344_create_invoice_items_table', 1),
(31, '2025_11_02_041347_create_bill_items_table', 1),
(32, '2025_11_02_041348_create_chart_of_accounts_table', 1),
(33, '2025_11_02_041350_create_journal_entries_table', 1),
(34, '2025_11_02_041350_create_journal_entry_items_table', 1),
(35, '2025_11_02_041351_create_payment_receiveds_table', 1),
(36, '2025_11_02_041352_create_payment_mades_table', 1),
(37, '2025_11_07_192446_create_bank_accounts_table', 1),
(38, '2025_11_07_192448_create_bank_transactions_table', 1),
(39, '2025_11_07_194818_create_fiscal_years_table', 1),
(40, '2025_11_07_195205_create_accounting_periods_table', 1),
(41, '2025_11_08_121100_add_paid_amount_to_invoices_table', 1),
(42, '2025_11_08_122357_add_date_to_payments_received_table', 1),
(43, '2025_11_08_145129_add_paid_fields_to_bills_table', 2),
(44, '2025_11_08_153311_modify_status_column_on_bills_table', 3);

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
-- Table structure for table `payments_made`
--

CREATE TABLE `payments_made` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) NOT NULL,
  `bill_id` bigint(20) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('draft','posted') NOT NULL DEFAULT 'posted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments_made`
--

INSERT INTO `payments_made` (`id`, `vendor_id`, `bill_id`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(18, 2, 4, 5000.00, 'posted', '2025-11-08 09:34:48', '2025-11-08 09:34:48'),
(19, 1, 1, 3000.00, 'posted', '2025-11-08 13:38:18', '2025-11-08 13:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `payments_received`
--

CREATE TABLE `payments_received` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) NOT NULL,
  `invoice_id` bigint(20) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date DEFAULT NULL,
  `status` enum('draft','posted') NOT NULL DEFAULT 'posted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments_received`
--

INSERT INTO `payments_received` (`id`, `customer_id`, `invoice_id`, `amount`, `date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 500.00, NULL, 'posted', '2025-11-08 08:15:01', '2025-11-08 08:15:01');

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

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Nakib Traders', 'nak@traders.com', '019999999', 'Dhaka', '2025-11-08 07:23:22', '2025-11-08 07:23:22'),
(2, 'ABCd Traders', 'abc@traders.com', '019999999', 'Dhaka', '2025-11-08 07:23:32', '2025-11-08 07:23:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_periods`
--
ALTER TABLE `accounting_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounting_periods_fiscal_year_id_foreign` (`fiscal_year_id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_accounts_account_number_unique` (`account_number`);

--
-- Indexes for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_transactions_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bills_bill_number_unique` (`bill_number`),
  ADD KEY `bills_status_posted_at_index` (`status`,`posted_at`),
  ADD KEY `bills_vendor_id_index` (`vendor_id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_items_bill_id_index` (`bill_id`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chart_of_accounts_code_index` (`code`),
  ADD KEY `chart_of_accounts_type_index` (`type`);

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
-- Indexes for table `fiscal_years`
--
ALTER TABLE `fiscal_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fiscal_years_name_unique` (`name`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_status_posted_at_index` (`status`,`posted_at`),
  ADD KEY `invoices_customer_id_index` (`customer_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_index` (`invoice_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entries_date_posted_at_index` (`date`,`posted_at`);

--
-- Indexes for table `journal_entry_items`
--
ALTER TABLE `journal_entry_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_items_journal_entry_id_index` (`journal_entry_id`),
  ADD KEY `journal_entry_items_account_id_index` (`account_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments_made`
--
ALTER TABLE `payments_made`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_made_vendor_id_index` (`vendor_id`),
  ADD KEY `payments_made_bill_id_index` (`bill_id`),
  ADD KEY `payments_made_status_index` (`status`);

--
-- Indexes for table `payments_received`
--
ALTER TABLE `payments_received`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_received_customer_id_index` (`customer_id`),
  ADD KEY `payments_received_invoice_id_index` (`invoice_id`),
  ADD KEY `payments_received_status_index` (`status`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounting_periods`
--
ALTER TABLE `accounting_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fiscal_years`
--
ALTER TABLE `fiscal_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `journal_entry_items`
--
ALTER TABLE `journal_entry_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `payments_made`
--
ALTER TABLE `payments_made`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payments_received`
--
ALTER TABLE `payments_received`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounting_periods`
--
ALTER TABLE `accounting_periods`
  ADD CONSTRAINT `accounting_periods_fiscal_year_id_foreign` FOREIGN KEY (`fiscal_year_id`) REFERENCES `fiscal_years` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD CONSTRAINT `bank_transactions_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
