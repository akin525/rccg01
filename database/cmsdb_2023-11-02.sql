-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 02, 2023 at 05:32 PM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `by_who` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `stop_date` date NOT NULL,
  `start_time` time NOT NULL,
  `stop_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE IF NOT EXISTS `attendances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `male` bigint NOT NULL,
  `female` bigint NOT NULL,
  `children` bigint NOT NULL,
  `service_types_id` bigint UNSIGNED NOT NULL,
  `attendance_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_service_types_id_foreign` (`service_types_id`),
  KEY `attendances_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `branch_id`, `male`, `female`, `children`, `service_types_id`, `attendance_date`, `created_at`, `updated_at`) VALUES
(1, 1, 40, 55, 90, 1, '2023-07-03 23:00:00', '2023-07-04 21:03:03', '2023-07-04 21:03:03'),
(2, 1, 90, 44, 33, 2, '2023-07-02 23:00:00', '2023-07-04 21:06:32', '2023-07-04 21:06:32');

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE IF NOT EXISTS `collections` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `collections_types_id` bigint UNSIGNED NOT NULL,
  `service_types_id` bigint UNSIGNED NOT NULL,
  `amount` bigint NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collections_date_index` (`date`),
  KEY `collections_branch_id_foreign` (`branch_id`),
  KEY `collections_collections_types_id_foreign` (`collections_types_id`),
  KEY `collections_service_types_id_foreign` (`service_types_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collections_commissions`
--

DROP TABLE IF EXISTS `collections_commissions`;
CREATE TABLE IF NOT EXISTS `collections_commissions` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `settled` tinyint(1) NOT NULL DEFAULT '0',
  `collection_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collections_commissions_branch_id_foreign` (`branch_id`),
  KEY `collections_commissions_collection_date_foreign` (`collection_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collections_types`
--

DROP TABLE IF EXISTS `collections_types`;
CREATE TABLE IF NOT EXISTS `collections_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collections_types_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collections_types`
--

INSERT INTO `collections_types` (`id`, `branch_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'building_project', '2023-06-29 08:25:06', '2023-06-29 08:25:06');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `by_who` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assign_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `groups_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `branch_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'mmm', '2023-07-04 19:27:58', '2023-07-04 19:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

DROP TABLE IF EXISTS `group_members`;
CREATE TABLE IF NOT EXISTS `group_members` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` bigint UNSIGNED NOT NULL,
  `group_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_members_member_id_foreign` (`member_id`),
  KEY `group_members_group_id_foreign` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `title` enum('Mr','Mrs','Miss','Dr (Mrs)','Dr','Prof','Chief','Chief (Mrs)','Engr','Surveyor','HRH','Elder','Oba','Olori') COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `marital_status` enum('married','single') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_since` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wedding_anniversary` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relative` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_status` enum('old','new') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'old',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_email_unique` (`email`),
  KEY `members_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `branch_id`, `title`, `firstname`, `lastname`, `email`, `dob`, `phone`, `occupation`, `position`, `address`, `address2`, `postal`, `city`, `state`, `country`, `sex`, `marital_status`, `member_since`, `wedding_anniversary`, `photo`, `relative`, `member_status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mr', 'Samji', 'Odejinmi', 'samjidiamond@gmail.com', '1985', '8166939205', 'Engineer', '', 'J', 'CHRIS', NULL, 'Lagos', 'Lagos', 'Nigeria', 'male', 'single', '1988', '1968', 'profile.png', NULL, 'new', '2023-06-29 08:03:07', '2023-06-29 08:03:07'),
(2, 1, 'Mr', 'Samji', 'Odejinmi', 'samjidiamondi@gmail.com', '2020-06-29', '08166939205', NULL, 'member', NULL, NULL, NULL, NULL, NULL, NULL, 'male', NULL, NULL, NULL, '1688030537.jpg', NULL, 'new', '2023-06-29 08:22:16', '2023-06-29 08:22:17'),
(3, 1, 'Mr', 'adewale', 'adeyemi', 'baddest@gmail.com', '2023-07-13', '080373737373', NULL, 'member', NULL, NULL, NULL, 'ggf', 'gff', 'Angola', 'male', NULL, NULL, NULL, 'profile.png', '[{\"id\":\"2\",\"relationship\":\"father\"},{\"id\":\"1\",\"relationship\":\"husband\"}]', 'new', '2023-07-04 07:43:14', '2023-07-04 07:43:14'),
(5, 1, 'Miss', 'Lamido', 'Sanusi', 'lamidosanusi@gmail.com', '2023-07-18', '089112274747', 'Doctor', 'Violinist', '45, oyelade street', 'oniwaya street', NULL, 'akute', 'ogun', 'Nigeria', 'male', 'single', '2023-07-31', '1970-01-01', '1688462179.jpg', '[{\"id\":\"4\",\"relationship\":\"brother\"}]', 'new', '2023-07-04 08:16:18', '2023-07-04 08:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `member_attendances`
--

DROP TABLE IF EXISTS `member_attendances`;
CREATE TABLE IF NOT EXISTS `member_attendances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` bigint UNSIGNED NOT NULL,
  `service_types_id` bigint UNSIGNED NOT NULL,
  `attendance` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_attendances_service_types_id_foreign` (`service_types_id`),
  KEY `member_attendances_member_id_foreign` (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_attendances`
--

INSERT INTO `member_attendances` (`id`, `member_id`, `service_types_id`, `attendance`, `date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'yes', '2023-06-29', '2023-06-29 08:29:53', '2023-06-29 08:29:53'),
(2, 2, 1, 'no', '2023-06-29', '2023-06-29 08:29:53', '2023-06-29 08:29:53'),
(3, 1, 1, 'yes', '2023-07-04', '2023-07-04 19:42:08', '2023-07-04 19:42:08'),
(4, 2, 1, 'no', '2023-07-04', '2023-07-04 19:42:08', '2023-07-04 19:42:08'),
(5, 3, 1, 'no', '2023-07-04', '2023-07-04 19:42:08', '2023-07-04 19:42:08'),
(6, 4, 1, 'yes', '2023-07-04', '2023-07-04 19:42:08', '2023-07-04 19:42:08'),
(7, 5, 1, 'yes', '2023-07-04', '2023-07-04 19:42:08', '2023-07-04 19:42:08'),
(8, 1, 1, 'no', '2023-07-03', '2023-07-04 19:49:15', '2023-07-04 19:49:15'),
(9, 2, 1, 'yes', '2023-07-03', '2023-07-04 19:49:15', '2023-07-04 19:49:15'),
(10, 3, 1, 'no', '2023-07-03', '2023-07-04 19:49:15', '2023-07-04 19:49:15'),
(11, 4, 1, 'yes', '2023-07-03', '2023-07-04 19:49:15', '2023-07-04 19:49:15'),
(12, 5, 1, 'yes', '2023-07-03', '2023-07-04 19:49:15', '2023-07-04 19:49:15');

-- --------------------------------------------------------

--
-- Table structure for table `member_collections`
--

DROP TABLE IF EXISTS `member_collections`;
CREATE TABLE IF NOT EXISTS `member_collections` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `member_id` bigint UNSIGNED NOT NULL,
  `collections_types_id` bigint UNSIGNED NOT NULL,
  `service_types_id` bigint UNSIGNED NOT NULL,
  `amount` bigint NOT NULL,
  `date_collected` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_collections_branch_id_foreign` (`branch_id`),
  KEY `member_collections_member_id_foreign` (`member_id`),
  KEY `member_collections_collections_types_id_foreign` (`collections_types_id`),
  KEY `member_collections_service_types_id_foreign` (`service_types_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messaging`
--

DROP TABLE IF EXISTS `messaging`;
CREATE TABLE IF NOT EXISTS `messaging` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `msg_to` bigint UNSIGNED NOT NULL,
  `msg_from` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `msg` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messaging_msg_to_foreign` (`msg_to`),
  KEY `messaging_msg_from_foreign` (`msg_from`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_06_21_102424_create_members_table', 1),
(4, '2018_06_21_102545_create_service_types_table', 1),
(5, '2018_06_21_102546_create_attendances_table', 1),
(6, '2018_06_22_082217_create_events_table', 1),
(7, '2018_07_12_122248_create_groups_table', 1),
(8, '2018_07_12_123555_create_group_members_table', 1),
(9, '2018_08_24_111333_create_member_attendances_table', 1),
(10, '2018_09_11_113254_create_messaging_table', 1),
(11, '2018_09_13_162055_create_announcements_table', 1),
(12, '2018_12_14_114432_create_options_table', 1),
(13, '2019_02_06_224640_create_collections_types_table', 1),
(14, '2019_02_07_100536_create_collections_table', 1),
(15, '2019_02_07_101424_create_collections_commissions_table', 1),
(16, '2019_02_21_162246_create_payments_table', 1),
(17, '2019_04_16_223522_create_member_collections_table', 1),
(18, '2019_04_18_124833_create_settings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `options_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `order_ids` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `order_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorization_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=345226 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

DROP TABLE IF EXISTS `service_types`;
CREATE TABLE IF NOT EXISTS `service_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_types_branch_id_foreign` (`branch_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`id`, `branch_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sunday School', '2023-06-29 08:24:53', '2023-06-29 08:24:53'),
(2, 1, 'Sunday Service', '2023-06-29 08:24:53', '2023-06-29 08:24:53');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'name', 'RCCG', '2023-06-29 07:54:50', '2023-06-29 07:54:50'),
(2, 'logo', 'logo.png', '2023-06-29 07:54:50', '2023-06-29 07:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branchname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branchcode` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isadmin` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_branchcode_unique` (`branchcode`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branchname`, `country`, `state`, `city`, `branchcode`, `email`, `address`, `currency`, `isadmin`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Love Pavilion', 'Nigeria', 'Lagos', 'Island', 'll575', 'odejinmisamuel@gmail.com', 'AJUWON AREA\r\nAKUTE AREA', '₦', 'true', '$2y$10$SvelEdLMMClkKOVJGWimLOPAUziIscp48V8BNLKlZh2DYOiEQReHa', NULL, '2023-06-29 07:54:32', '2023-06-29 07:54:32');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
