-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250422.c097b1deca
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 12, 2025 at 11:02 AM
-- Server version: 8.4.3
-- PHP Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nurani`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` bigint UNSIGNED NOT NULL,
  `about_us` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_background` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `icon_one` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_one` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_one` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_two` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `icon_three` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_three` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_three` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `video_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `about_us`, `banner_image`, `video_background`, `image_two`, `status`, `icon_one`, `title_one`, `description_one`, `icon_two`, `title_two`, `description_two`, `icon_three`, `title_three`, `description_three`, `video_id`, `created_at`, `updated_at`) VALUES
(1, '<p>At&nbsp;Nooraani, we celebrate the beauty, culture and heritage of South Asian jewellery. Our collection brings together timeless elegance and contemporary style, offering pieces that capture the essence of tradition while complementing the modern wearer. Each design is chosen with care to ensure it radiates sophistication, charm, and meaning.</p>\r\n<p>We take pride in offering jewellery that allows our customers to express themselves, whether it&rsquo;s for a special occasion or everyday wear. Our goal is to help you find pieces that not only enhance your look but also tell your story with grace and confidence.</p>', 'uploads/website-images/about-us-2025-10-05-05-55-49-3710.jpg', 'uploads/website-images/video_background-2022-12-11-11-23-17-1758.png', 'uploads/website-images/about-us-2025-10-05-05-55-10-8072.jpg', NULL, 'fas fa-boxes', 'Choose product', 'Experience hassle-free online shopping with our service!\r\nSimply choose the product you want', 'fas fa-credit-card', 'Make Your Payment', 'Experience hassle-free online shopping with our service! make your payment securely.', 'fas fa-shipping-fast', 'Fast Delivery', 'Experience hassle-free online shopping with our service! enjoy fast delivery right to your doorstep.', 'lcU3pruVyUw', '2022-09-20 08:21:31', '2025-10-07 05:48:09');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int NOT NULL DEFAULT '0',
  `state_id` int NOT NULL DEFAULT '0',
  `city_id` int NOT NULL DEFAULT '0',
  `zip_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_shipping` int NOT NULL DEFAULT '0',
  `default_billing` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `name`, `email`, `phone`, `country_id`, `state_id`, `city_id`, `zip_code`, `address`, `type`, `default_shipping`, `default_billing`, `created_at`, `updated_at`) VALUES
(1, 5, 'a b', 'marohman74@gmail.com', '123355454544', 1, 1, 2, NULL, 'ewewee', '1', 1, 1, '2022-09-21 05:45:32', '2022-09-21 05:45:32'),
(2, 5, 'dsds dsdsd', 'marohman74@gmail.com', '1212121212121', 1, 1, 2, NULL, 'sdsdsd', '1', 0, 0, '2022-09-21 05:46:27', '2022-09-21 05:46:27'),
(3, 6, 'nayeem', 'nayeem@gmail.com', '1234', 4, 7, 10, NULL, 'lonon', 'home', 1, 1, '2022-09-21 06:56:14', '2022-09-21 06:56:14'),
(5, 4, 'Reprehenderit in ni Aliquid dolor labori', 'Quo culpa et offici', 'Architecto quis assu', 2, 4, 8, NULL, 'Porro minima consect', '1', 1, 1, '2022-09-21 07:35:41', '2022-09-21 07:35:41'),
(7, 7, 'shibo', 'suvobd@gmail.com', '01777777776', 4, 7, 10, NULL, 'Dhaka, Mirpur12', 'home', 1, 1, '2022-09-24 18:41:35', '2023-03-30 06:28:50'),
(9, 6, 'test', 'test@gmail.com', '12348767', 4, 7, 10, NULL, 'test', 'home', 0, 0, '2022-09-25 06:35:37', '2022-09-25 06:35:37'),
(11, 19, 'Dolores hic in volup Adipisicing accusant', 'Sed quibusdam dolore', 'Sunt praesentium qui', 2, 5, 9, NULL, 'Nesciunt dolore dol', '1', 1, 1, '2022-10-27 16:23:38', '2022-10-27 16:23:38'),
(19, 30, 'Mohammad Ali', 'msalisapan@gmail.com', '01624188877', 4, 7, 11, NULL, 'Dhaka, Bangladesh', 'home', 1, 1, '2022-11-10 22:59:45', '2022-11-10 22:59:45'),
(20, 30, 'Ali', 'alimohammad@outlook.com', '01723456789', 1, 1, 2, NULL, 'America', 'home', 0, 0, '2022-11-10 23:03:56', '2022-11-10 23:03:56'),
(24, 31, 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 1, 1, 2, NULL, 'Los Angeles, USA', 'home', 1, 1, '2022-11-15 15:09:23', '2022-11-15 15:09:23'),
(26, 32, 'abdur', 'abdur.rohman2003@gmail.com', '01682825213', 2, 5, 9, NULL, 'v', 'home', 1, 1, '2022-11-17 22:52:15', '2022-11-17 22:52:15'),
(29, 34, 'Rafiqul Islam', 'user@u.com', '0177777777777', 2, 5, 9, NULL, 'Dhaka,Bangladesh', 'office', 1, 1, '2022-11-30 15:58:42', '2022-12-04 16:16:24'),
(41, 38, 'abdur', 'abdur.rohman2003@gmail.com', '01682825123', 2, 5, 9, NULL, 'asdgg', 'home', 1, 1, '2022-12-02 16:05:17', '2022-12-02 16:05:17'),
(46, 7, 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 2, 4, 8, NULL, 'Kolkata City', 'home', 0, 0, '2022-12-04 16:36:30', '2022-12-04 16:38:13'),
(49, 7, 'Hassan Khan', 'youabdme@gamail.com', '0197854544', 2, 4, 8, NULL, 'Mirpur 11, Dhaka', 'home', 0, 0, '2022-12-08 19:42:16', '2023-01-16 20:42:02'),
(50, 41, 'Ibrahim Khan', 'ibrahim@his.mail.com', '12345678910', 2, 4, 8, NULL, 'Mirpur 11', 'home', 1, 1, '2023-01-15 17:46:03', '2023-01-15 17:46:03'),
(51, 42, 'Your name', 'myemail@mail.com.bd', '1236547898', 4, 7, 10, NULL, 'Dhaka, Mirpur', 'home', 1, 1, '2023-01-16 15:39:29', '2023-01-16 17:29:13'),
(52, 7, 'Mamunn', 'mamjn@gmakl.com', '0165731884', 4, 7, 10, NULL, 'dhaka', 'home', 0, 0, '2023-01-17 16:02:04', '2023-01-17 16:02:04'),
(53, 57, 'qusacog@mailinator.com', 'lenij@mailinator.com', '+1 (256) 165-4855', 5, 9, 5, NULL, 'sadfsaf', 'Office', 1, 1, '2025-08-24 05:27:41', '2025-08-24 05:27:41'),
(54, 59, 'mkhsojib', 'mkhsojib@gmail.com', '0464654564', 4, 7, 21, '1230', 'gfsdfgdgdf', 'office', 1, 0, '2025-08-30 04:46:41', '2025-09-09 04:06:37'),
(55, 59, 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 4, 9, 22, '1245', 'Dicta esse ut eiusmo', 'office', 0, 1, '2025-08-30 04:48:53', '2025-09-09 04:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_type` int NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `forget_password_token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `admin_type`, `name`, `email`, `image`, `email_verified_at`, `password`, `remember_token`, `status`, `forget_password_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'NOORAANI', 'admin@example.com', 'uploads/website-images/nooraani-2025-10-05-10-03-59-5486.png', NULL, '$2y$10$bVBJPt77amiMjktrpf0W.OXsBzEhILnYVBmAkIlEsk5Rw.r1ff4Rq', 'DWandrZSPEXbZF6gzos3Y1efk2OM6Cmc3eEeiypKYik5P6oofQ2pSRxBTJyk', 1, '817805', NULL, '2025-10-05 04:04:01');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_modals`
--

CREATE TABLE `announcement_modals` (
  `id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expired_date` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcement_modals`
--

INSERT INTO `announcement_modals` (`id`, `status`, `title`, `description`, `image`, `expired_date`, `created_at`, `updated_at`) VALUES
(1, 0, 'GET UP TO 75% OFF', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, facere nesciunt doloremque nobis debitis sint?', 'uploads/website-images/announcement-2022-12-11-11-10-10-4172.png', 5, NULL, '2023-03-23 09:31:48');

-- --------------------------------------------------------

--
-- Table structure for table `bank_payments`
--

CREATE TABLE `bank_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `account_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cash_on_delivery_status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_payments`
--

INSERT INTO `bank_payments` (`id`, `status`, `account_info`, `cash_on_delivery_status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bank Name: Noble Edge\r\nAccount Number:  123456789\r\nRouting Number: 987654321\r\nBranch: London\r\nCountry: England', 1, NULL, '2025-08-31 00:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `banner_images`
--

CREATE TABLE `banner_images` (
  `id` bigint UNSIGNED NOT NULL,
  `header` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `after_product_qty` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title_one` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `badge` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_slug` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banner_images`
--

INSERT INTO `banner_images` (`id`, `header`, `title`, `link`, `image`, `banner_location`, `after_product_qty`, `status`, `created_at`, `updated_at`, `title_one`, `title_two`, `badge`, `product_slug`) VALUES
(13, NULL, NULL, '', 'uploads/website-images/banner-2023-01-24-11-17-38-9903.png', 'Login page', 0, 0, NULL, '2023-01-24 16:17:39', NULL, NULL, NULL, NULL),
(15, NULL, NULL, NULL, 'uploads/website-images/default-avatar-2022-10-31-04-54-06-2202.jpg', 'Default Profile Image', 0, 0, NULL, '2022-10-31 20:54:07', NULL, NULL, NULL, NULL),
(16, NULL, NULL, NULL, 'uploads/website-images/Mega-menu-2025-08-24-02-05-47-6689.jpg', 'Three Column First Banner', 0, 1, NULL, '2025-10-05 03:10:09', 'New Style', 'Get 65% Offer & Make New Fusion.', 'Badge', 'sets'),
(17, NULL, NULL, NULL, 'uploads/website-images/Mega-menu-2025-08-24-02-05-36-6431.jpg', 'Three Column Second Banner', 0, 1, NULL, '2025-09-10 01:28:04', 'Mega Offer', 'Make your New Styles with Our Products', 'Badge', 'necklaces'),
(18, NULL, NULL, NULL, 'uploads/website-images/Mega-menu-2025-08-24-10-02-17-5308.jpg', 'Three Column Third Banner', 0, 1, NULL, '2025-09-10 01:28:11', 'Dresses', 'Best Dresses Collection', NULL, 'rings'),
(19, NULL, NULL, NULL, 'uploads/website-images/Mega-menu-2025-08-24-03-33-17-3866.jpg', 'Two Column banner One', 0, 1, NULL, '2025-10-05 03:10:17', 'New Style', 'Get 65% Offer & Make New Fusion.', 'Badge', 'necklaces'),
(20, NULL, NULL, NULL, 'uploads/website-images/Mega-menu-2025-08-24-02-06-19-5524.jpg', 'Two Column Banner two', 0, 1, NULL, '2025-09-10 00:48:24', 'Mega oFFER', 'Make your New Styles with Our Products', 'Badge', 'dresses'),
(21, NULL, NULL, NULL, 'uploads/website-images/Mega-menu-2022-10-26-11-38-07-1954.png', 'Homepage Single banner one', 0, 1, NULL, '2025-09-10 00:48:24', 'Get the best deal for Grocery', 'You get into the 2k+ best Products in Flash offer with as into the find to makein shaped sofa for sale.', NULL, 'dresses'),
(22, NULL, NULL, NULL, 'uploads/website-images/Mega-menu-2022-10-26-11-39-41-1909.png', 'Homepage Single banner Two', 0, 1, NULL, '2025-09-10 00:48:24', 'Get the best deal for Modran Grocery', NULL, NULL, 'dresses'),
(23, NULL, NULL, 'https://shopo-ecom.vercel.app/single-product?slug=wireless-headphones-and-earbuds-', 'uploads/website-images/Mega-menu-2025-08-24-03-29-06-3510.jpg', 'Megamenu banner', 0, 1, NULL, '2025-09-10 00:48:24', 'Accessories', 'Try Out The Best', NULL, 'dresses'),
(24, NULL, '#', '#', 'uploads/website-images/Mega-menu-2022-09-20-10-59-27-3427.png', 'Homepage flash sale sidebar banner', 0, 1, NULL, '2022-09-20 04:59:27', NULL, NULL, NULL, NULL),
(25, NULL, NULL, 'https://shopo-ecom.vercel.app/single-product?slug=wireless-headphones-and-earbuds-', 'uploads/website-images/Mega-menu-2022-12-11-10-54-02-7881.png', 'Shop page center banner', 3, 1, NULL, '2025-09-10 00:48:24', 'Get the best deal for Headphones', NULL, NULL, 'dresses'),
(26, '', '', 'https://shopo-ecom.vercel.app/single-product?slug=wireless-headphones-and-earbuds-', 'uploads/website-images/Mega-menu-2025-08-24-03-29-30-1339.jpg', 'Shop page sidebar banner', 0, 1, NULL, '2025-09-10 00:48:24', 'Trendy', 'Best wireless Shoes', NULL, 'dresses'),
(27, 'Get our latest offer', 'by subscription', '', 'uploads/website-images/banner-2022-11-13-04-44-20-9795.png', 'Subscribe section banner', 0, 1, NULL, '2022-11-13 21:44:20', NULL, NULL, NULL, NULL),
(28, NULL, NULL, 'link', 'uploads/website-images/Mega-menu-2022-06-14-11-26-55-8951.jpg', 'Featured category sidebar banner', 0, 1, NULL, '2022-06-14 05:26:56', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_category_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `seo_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `show_homepage` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `admin_id`, `title`, `slug`, `blog_category_id`, `image`, `description`, `views`, `seo_title`, `seo_description`, `status`, `show_homepage`, `created_at`, `updated_at`) VALUES
(2, 1, 'Business-to-consumer that involves selling fight products', 'businesstoconsumer-that-involves-selling-fight-products', 2, 'uploads/custom-images/blog--2022-12-11-11-13-27-5463.png', '<p>Pri tempor appareat no, eruditi repudiandae vix at. Eos at brute omnesque voluptaria, facer putent intellegam eu pri. Mei debitis ullamcorper eu, at quo idque mundi. Vis in suas porro consequat, nec ad dolor adversarium, ut praesent cotidieque sit. Veniam civibus omittantur duo ut, te his alterum complectitur. Mea omnis oratio impedit ne.</p>\r\n<p>Meliore inimicus duo ut, tation veritus elaboraret eam cu. Cum in alii agam aliquip, aperiam salutandi et per. Ex vis summo probatus ocurreret, ex assum sententiae pri, blandit sensibus moderatius ei eos. Vix nobis phaedrum neglegentur et.</p>\r\n<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu. Usu ei perfecto repudiare tincidunt, ut quas malis erant vim. An mel vidit iudicabit.</p>\r\n<p>Pri tempor appareat no, eruditi repudiandae vix at. Eos at brute omnesque voluptaria, facer putent intellegam eu pri. Mei debitis ullamcorper eu, at quo idque mundi. Vis in suas porro consequat, nec ad dolor adversarium, ut praesent cotidieque sit. Veniam civibus omittantur duo ut, te his alterum complectitur. Mea omnis oratio impedit ne.</p>\r\n<p>Ei usu malis aeque efficiantur. Mazim dolor denique duo ad, augue ornatus sententiae vel at, duo id sumo vulputate. His legimus assueverit ut, commune maluisset deterruisset id mel. Oblique volumus eos ut, quo autem posidonium definitiones cu. Cu usu lorem consul concludaturque, pro ea fuisset consectetuer. Ex aeterno forensibus has, dicta propriae est ei, ex alterum apeirian quo.</p>', 30, 'Business-to-consumer that involves selling fight products and services', 'Business-to-consumer that involves selling fight products and services', 1, 1, '2022-09-22 10:09:44', '2023-03-23 04:56:33'),
(3, 1, 'Top 10 Best Professional Blogging Platforms for 2022', 'top-10-best-professional-blogging-platforms-for-2022', 3, 'uploads/custom-images/blog--2022-12-11-11-13-35-9969.png', '<p>Ei usu malis aeque efficiantur. Mazim dolor denique duo ad, augue ornatus sententiae vel at, duo id sumo vulputate. His legimus assueverit ut, commune maluisset deterruisset id mel. Oblique volumus eos ut, quo autem posidonium definitiones cu. Cu usu lorem consul concludaturque, pro ea fuisset consectetuer. Ex aeterno forensibus has, dicta propriae est ei, ex alterum apeirian quo.</p>\r\n<p>Oblique volumus eos ut, quo autem posidonium definitiones cu. Cu usu lorem consul concludaturque, pro ea fuisset consectetuer. Ex aeterno forensibus has, dicta propriae est ei, ex alterum apeirian quo.</p>\r\n<p>Ei usu malis aeque efficiantur. Mazim dolor denique duo ad, augue ornatus sententiae vel at, duo id sumo vulputate. His legimus assueverit ut, commune maluisset deterruisset id mel. Oblique volumus eos ut, quo autem posidonium definitiones cu.</p>\r\n<p>Pri tempor appareat no, eruditi repudiandae vix at. Eos at brute omnesque voluptaria, facer putent intellegam eu pri. Mei debitis ullamcorper eu, at quo idque mundi. Vis in suas porro consequat, nec ad dolor adversarium, ut praesent cotidieque sit. Veniam civibus omittantur duo ut, te his alterum complectitur.</p>\r\n<p>Eos at brute omnesque voluptaria, facer putent intellegam eu pri. Mei debitis ullamcorper eu, at quo idque mundi. Vis in suas porro consequat, nec ad dolor adversarium, ut praesent cotidieque sit. Veniam civibus omittantur duo ut, te his alterum complectitur. Mea omnis oratio impedit ne.</p>\r\n<p>In vim natum soleat nostro, pri in eloquentiam contentiones. Eu sit sapientem reprehendunt, omnis aliquid eu eos. No quot illum veniam est, ne pro iudico saperet mnesarchum. Ea pri nostro disputando contentiones, eu nec menandri qualisque, vis ex equidem invidunt. Et accusam detracto splendide per, congue meliore id sea. Has eu aeterno patrioque expetendis, mel ei dissentiet reformidans.</p>', 40, 'Top 10 Best Professional Blogging Platforms for 2022', 'Top 10 Best Professional Blogging Platforms for 2022', 1, 1, '2022-09-22 10:12:00', '2023-05-27 07:00:00'),
(4, 1, '6 Best WordPress E-commerce Plugins for Online Stores in 2022', '6-best-wordpress-ecommerce-plugins-for-online-stores-in-2022', 5, 'uploads/custom-images/blog--2022-12-11-11-13-43-6228.png', '<p>In vim natum soleat nostro, pri in eloquentiam contentiones. Eu sit sapientem reprehendunt, omnis aliquid eu eos. No quot illum veniam est, ne pro iudico saperet mnesarchum. Ea pri nostro disputando contentiones, eu nec menandri qualisque, vis ex equidem invidunt. Et accusam detracto splendide per, congue meliore id sea. Has eu aeterno patrioque expetendis, mel ei dissentiet reformidans.</p>\r\n<p>Meliore inimicus duo ut, tation veritus elaboraret eam cu. Cum in alii agam aliquip, aperiam salutandi et per. Ex vis summo probatus ocurreret, ex assum sententiae pri, blandit sensibus moderatius ei eos. Vix nobis phaedrum neglegentur et.</p>\r\n<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu. Usu ei perfecto repudiare tincidunt, ut quas malis erant vim. An mel vidit iudicabit.</p>\r\n<p>Appetere fabellas ius te. Nonumes splendide deseruisse ea vis, alii velit vel eu. Eos ut scaevola platonem rationibus. Vis natum vivendo sententiae in, ea aperiam apeirian pri, in partem eleifend quo. Pro ex nobis utinam, nam et vidit numquam fastidii, ne per munere adolescens.</p>\r\n<p>Appetere fabellas ius te. Nonumes splendide deseruisse ea vis, alii velit vel eu. Eos ut scaevola platonem rationibus. Vis natum vivendo sententiae in, ea aperiam apeirian pri, in partem eleifend quo. Pro ex nobis utinam, nam et vidit numquam fastidii, ne per munere adolescens. Appetere fabellas ius te. Nonumes splendide deseruisse ea vis, alii velit vel eu. Eos ut scaevola platonem rationibus. Vis natum vivendo sententiae in, ea aperiam apeirian pri, in partem eleifend quo.&nbsp;</p>', 21, '6 Best WordPress E-commerce Plugins for Online Stores in 2022', '6 Best WordPress E-commerce Plugins for Online Stores in 2022', 1, 1, '2022-09-22 10:13:57', '2023-03-30 07:03:57'),
(5, 1, '15 Best WordPress Newspaper Themes to Look Out for in 2022', '15-best-wordpress-newspaper-themes-to-look-out-for-in-2022', 5, 'uploads/custom-images/blog--2022-12-11-11-14-38-3134.png', '<p>Appetere fabellas ius te. Nonumes splendide deseruisse ea vis, alii velit vel eu. Eos ut scaevola platonem rationibus. Vis natum vivendo sententiae in, ea aperiam apeirian pri, in partem eleifend quo. Pro ex nobis utinam, nam et vidit numquam fastidii, ne per munere adolescens.</p>\r\n<p>Ipsum volumus pertinax mea ut, eu erat tacimates nam. Tibique copiosae verterem mea no, eam ex melius option, soluta timeam et his. Sit simul gubergren reformidans id, amet minimum nominavi eos ea. Et augue dicta vix. Mea ne utamur referrentur.</p>\r\n<p>Ipsum volumus pertinax mea ut, eu erat tacimates nam. Tibique copiosae verterem mea no, eam ex melius option, soluta timeam et his. Sit simul gubergren reformidans id, amet minimum nominavi eos ea. Et augue dicta vix. Mea ne utamur referrentur. Ipsum volumus pertinax mea ut, eu erat tacimates nam. Tibique copiosae verterem mea no.&nbsp;</p>\r\n<p>Pri tempor appareat no, eruditi repudiandae vix at. Eos at brute omnesque voluptaria, facer putent intellegam eu pri. Mei debitis ullamcorper eu, at quo idque mundi. Vis in suas porro consequat, nec ad dolor adversarium, ut praesent cotidieque sit.&nbsp;</p>\r\n<p>Ei usu malis aeque efficiantur. Mazim dolor denique duo ad, augue ornatus sententiae vel at, duo id sumo vulputate. His legimus assueverit ut, commune maluisset deterruisset id mel. Oblique volumus eos ut, quo autem posidonium definitiones cu. Cu usu lorem consul concludaturque, pro ea fuisset consectetuer.</p>\r\n<p>Ei usu malis aeque efficiantur. Mazim dolor denique duo ad, augue ornatus sententiae vel at, duo id sumo vulputate. His legimus assueverit ut, commune maluisset deterruisset id mel. Oblique volumus eos ut, quo autem posidonium definitiones cu. Cu usu lorem consul concludaturque, pro ea fuisset consectetuer. Ex aeterno forensibus has, dicta propriae est ei, ex alterum apeirian quo.</p>\r\n<p>Sint dignissim consectetuer nec et, per ad probatus referrentur, vel cu consequat sententiae. Ad duis fugit dictas mea, et cum stet oratio cetero. Ne pri omittam fastidii. No per harum dicant neglegentur, sea ei esse volumus adolescens. Nulla argumentum at pri, vel apeirian principes in. An dicam dicant consul me</p>', 55, '15 Best WordPress Newspaper Themes to Look Out for in 2022', '15 Best WordPress Newspaper Themes to Look Out for in 2022', 1, 1, '2022-09-22 10:14:55', '2023-03-30 07:07:47'),
(6, 1, 'Must-Have WordPress Plugins for Ecommerce Websites in 2022', 'musthave-wordpress-plugins-for-ecommerce-websites-in-2022', 4, 'uploads/custom-images/blog--2022-12-11-11-14-46-2660.png', '<p>Sint dignissim consectetuer nec et, per ad probatus referrentur, vel cu consequat sententiae. Ad duis fugit dictas mea, et cum stet oratio cetero. Ne pri omittam fastidii. No per harum dicant neglegentur, sea ei esse volumus adolescens. Nulla argumentum at pri, vel apeirian principes in. An dicam dicant consul mea, ne per option appetere argumentum, vim legere senserit et.</p>\r\n<p>Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel, melius patrioque est cu, eum at audire probatus repudiandae. Ei tempor definitiones eam, sea dico omnium ne. Eam ad ubique tincidunt elaboraret, malis aperiri sit et. Ut quo vero inimicus. Sed at munere fuisset noluisse, eleifend senserit an vix.</p>\r\n<p>Ipsum volumus pertinax mea ut, eu erat tacimates nam. Tibique copiosae verterem mea no, eam ex melius option, soluta timeam et his. Sit simul gubergren reformidans id, amet minimum nominavi eos ea. Et augue dicta vix. Mea ne utamur referrentur.</p>\r\n<p>Appetere fabellas ius te. Nonumes splendide deseruisse ea vis, alii velit vel eu. Eos ut scaevola platonem rationibus. Vis natum vivendo sententiae in, ea aperiam apeirian pri, in partem eleifend quo. Pro ex nobis utinam, nam et vidit numquam fastidii, ne per munere adolescens.</p>\r\n<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu. Usu ei perfecto repudiare tincidunt, ut quas malis erant vim. An mel vidit iudicabit.</p>', 45, 'Must-Have WordPress Plugins for Ecommerce Websites in 2022', 'Must-Have WordPress Plugins for Ecommerce Websites in 2022', 1, 0, '2022-09-22 10:15:55', '2023-05-27 10:13:25'),
(7, 1, 'It’s official! The iPhone 14 Series is on its way! Rumors turned out to be true. The Goods & the Bads.', 'its-official-the-iphone-14-series-is-on-its-way-rumors-turned-out-to-be-true-the-goods-the-bads', 2, 'uploads/custom-images/blog--2022-12-11-11-14-55-9096.png', '<p>Id est maiorum volutpat, ad nominavi suscipit suscipiantur vix. Ut ius veri aperiam reprehendunt. Ut per unum sapientem consequuntur, usu ut quot scripta. Sea te nisl expetenda, ad quo congue argumentum, sit quis simul accusam cu. Usu ei perfecto repudiare tincidunt, ut quas malis erant vim. An mel vidit iudicabit.</p>\r\n<p>Per ex vero nonumy. Ius eu doming nominavi mediocrem, aliquid efficiantur no vim, sanctus admodum mnesarchum ad pro. No sea invidunt partiendo. No postea numquam ocurreret duo, unum abhorreant cu nam, fugit fastidii percipitur nam id.</p>\r\n<p>Sit vivendum eleifend adipiscing ea. Modus legere suscipiantur an vel, melius patrioque est cu, eum at audire probatus repudiandae. Ei tempor definitiones eam, sea dico omnium ne. Eam ad ubique tincidunt elaboraret, malis aperiri sit et. Ut quo vero inimicus. Sed at munere fuisset noluisse, eleifend senserit an vix.</p>\r\n<p>Sint dignissim consectetuer nec et, per ad probatus referrentur, vel cu consequat sententiae. Ad duis fugit dictas mea, et cum stet oratio cetero. Ne pri omittam fastidii. No per harum dicant neglegentur, sea ei esse volumus adolescens. Nulla argumentum at pri, vel apeirian principes in. An dicam dicant consul mea, ne per option appetere argumentum, vim legere senserit et.</p>\r\n<p>No per harum dicant neglegentur, sea ei esse volumus adolescens. Nulla argumentum at pri, vel apeirian principes in. An dicam dicant consul mea, ne per option appetere argumentum, vim legere senserit et.</p>\r\n<p>Appetere fabellas ius te. Nonumes splendide deseruisse ea vis, alii velit vel eu. Eos ut scaevola platonem rationibus. Vis natum vivendo sententiae in, ea aperiam apeirian pri, in partem eleifend quo. Pro ex nobis utinam, nam et vidit numquam fastidii, ne per munere adolescens.</p>', 36, 'It’s official! The iPhone 14 Series is on its way! Rumors turned out to be true. The Goods & the Bads.', 'It’s official! The iPhone 14 Series is on its way! Rumors turned out to be true. The Goods & the Bads.', 1, 1, '2022-09-22 10:18:09', '2023-05-27 10:36:58');

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Development', 'development', 1, '2022-09-22 10:05:56', '2022-09-22 10:05:56'),
(3, 'Guide', 'guide-', 1, '2022-09-22 10:06:06', '2022-09-22 10:06:06'),
(4, 'Inspiration', 'inspiration-', 1, '2022-09-22 10:06:13', '2022-09-22 10:06:13'),
(5, 'Latest News', 'latest-news', 1, '2022-09-22 10:06:25', '2022-09-22 10:06:25'),
(6, 'Revenue', 'revenue-', 1, '2022-09-22 10:06:37', '2022-09-22 10:06:37'),
(7, 'Start Up', 'start-up', 1, '2022-09-22 10:06:48', '2022-09-22 10:06:48'),
(8, 'Technology', 'technology', 1, '2022-09-22 10:06:56', '2022-09-22 10:06:56');

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `blog_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_comments`
--

INSERT INTO `blog_comments` (`id`, `blog_id`, `name`, `email`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(3, 5, 'Abel Spencer', 'sicyxohore@mailinator.com', 'Molestiae totam magn', 1, '2022-09-25 10:22:20', '2022-11-03 06:52:12'),
(4, 6, 'Hannah Nunez', 'hupymug@mailinator.com', 'Vel ea fugit ad duc', 1, '2022-09-25 11:05:46', '2022-11-03 06:52:13'),
(5, 2, 'Dara Odom', 'caqo@mailinator.com', 'Ducimus doloremque', 1, '2022-09-25 11:38:22', '2022-11-03 06:52:13'),
(6, 2, 'Boris David', 'jypiw@mailinator.com', 'Ipsum quam harum co', 1, '2022-09-25 11:39:26', '2022-11-03 06:52:14'),
(7, 3, 'Cara Jarvis', 'matekyd@mailinator.com', 'Corporis deserunt ea', 1, '2022-10-31 20:54:48', '2022-11-03 06:52:15'),
(8, 2, 'AJOY', 'ajoy.quomodosoft@gmail.com', 'Very helpfull', 0, '2023-01-21 23:06:08', '2023-01-21 23:06:08'),
(9, 6, 'Ajoy', 'ajoy.quomodosoft@gmail.com', 'Very fantace', 0, '2023-01-21 23:13:24', '2023-01-21 23:13:24'),
(10, 3, 'John Doe', 'khalil.cmt.bpi@gmail.com', 'Leave a comment', 0, '2023-02-11 21:39:59', '2023-02-11 21:39:59');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Refined Threads', 'refined-threads', 'uploads/custom-images/oneplus-2022-09-25-04-15-53-8330.png', 1, '2022-09-20 07:16:12', '2023-03-22 06:58:47'),
(2, 'Ethereal Chic', 'ethereal-chic', 'uploads/custom-images/tencent-2022-09-25-04-16-01-9474.png', 1, '2022-09-20 07:16:24', '2023-03-22 06:59:01'),
(3, 'Urban Hive', 'urban-hive', 'uploads/custom-images/apple-2022-09-25-04-16-05-2914.png', 1, '2022-09-20 07:16:43', '2023-03-22 06:59:04'),
(4, 'Dapper Daze', 'dapper-daze', 'uploads/custom-images/mircrosoft-2022-09-25-04-16-10-7094.png', 1, '2022-09-20 07:16:59', '2023-03-22 06:59:13'),
(5, 'Velvet Vista', 'velvet-vista', 'uploads/custom-images/lenovo-2022-09-25-04-16-19-9532.png', 1, '2022-09-20 07:17:17', '2023-03-22 06:59:25'),
(6, 'Bohemian Muse', 'bohemian-muse', 'uploads/custom-images/huawei-2022-09-25-04-16-23-2134.png', 1, '2022-09-20 07:17:29', '2023-03-22 06:59:31'),
(7, 'Solstice Style', 'solstice-style', 'uploads/custom-images/nexus-2022-09-25-04-16-31-3263.png', 1, '2022-09-20 07:17:44', '2023-03-22 06:59:39'),
(8, 'Boldly Blue', 'boldly-blue', 'uploads/custom-images/google-2022-09-25-04-16-35-5464.png', 1, '2022-09-20 07:17:57', '2023-03-22 07:00:23'),
(9, 'Minted Mode', 'minted-mode', 'uploads/custom-images/firefox-2022-09-25-04-16-43-3374.png', 1, '2022-09-20 07:18:11', '2023-03-22 07:00:41'),
(10, 'Eclectic Ensemble', 'eclectic-ensemble', 'uploads/custom-images/tesla-2022-09-25-04-16-47-6997.png', 1, '2022-09-20 07:18:29', '2023-03-22 07:00:49'),
(11, 'BraveAlchemy Attire', 'bravealchemy-attire', 'uploads/custom-images/brave-2022-09-25-04-16-55-9281.png', 1, '2022-09-20 07:19:01', '2023-03-22 07:00:56'),
(12, 'Cascade Couture', 'facebook', 'uploads/custom-images/facebook-2022-09-25-04-17-01-2209.png', 1, '2022-09-20 07:19:14', '2023-03-22 07:01:09');

-- --------------------------------------------------------

--
-- Table structure for table `breadcrumb_images`
--

CREATE TABLE `breadcrumb_images` (
  `id` bigint UNSIGNED NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_type` int NOT NULL DEFAULT '1',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `breadcrumb_images`
--

INSERT INTO `breadcrumb_images` (`id`, `location`, `image_type`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Brand Page', 1, 'uploads/website-images/banner-us-2022-02-11-03-19-00-6529.jpg', NULL, '2022-02-11 09:19:03'),
(2, 'Cart Page', 1, 'uploads/website-images/banner-us-2022-02-11-03-19-13-2295.jpg', NULL, '2022-02-11 09:19:16'),
(3, 'Campaign Page', 1, 'uploads/website-images/banner-us-2022-02-11-03-19-26-4555.jpg', NULL, '2022-02-11 09:19:28'),
(4, 'FAQ page', 1, 'uploads/website-images/banner-us-2022-02-11-03-19-38-5297.jpg', NULL, '2022-02-11 09:19:40'),
(5, 'User Authentication', 1, 'uploads/website-images/banner-us-2022-02-11-03-19-51-4946.jpg', NULL, '2022-02-11 09:19:53'),
(6, 'Compare Page', 1, 'uploads/website-images/banner-us-2022-02-11-03-20-02-1928.jpg', NULL, '2022-02-11 09:20:04'),
(7, 'Order Tracking Page', 1, 'uploads/website-images/banner-us-2022-02-11-03-20-16-5029.jpg', NULL, '2022-02-11 09:20:18'),
(8, 'Vendor Page', 1, 'uploads/website-images/banner-us-2022-02-11-03-20-28-1461.jpg', NULL, '2022-02-11 09:20:30'),
(9, 'Shop Page', 1, 'uploads/website-images/banner-us-2022-02-11-03-20-39-4557.jpg', NULL, '2022-02-11 09:20:41'),
(10, 'Blog page', 1, 'uploads/website-images/banner-us-2022-02-11-03-20-51-3046.jpg', NULL, '2022-02-11 09:20:54'),
(11, 'Flash Deal Page', 1, 'uploads/website-images/banner-us-2022-02-11-03-21-04-8636.jpg', NULL, '2022-02-11 09:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sets', 'sets', 'fa-solid fa-vest', 'uploads/custom-images/category-2025-08-24-11-22-37-8185.jpg', 1, '2022-09-20 05:16:25', '2025-10-05 00:47:15'),
(2, 'Earrings', 'earrings', 'fa-solid fa-shirt', 'uploads/custom-images/category-2025-08-24-11-22-44-8790.jpg', 1, '2022-09-20 05:16:50', '2025-10-05 00:47:28'),
(3, 'Rings', 'rings', 'fas fa-mobile-alt', 'uploads/custom-images/category-2025-08-23-06-04-30-1028.jpg', 1, '2022-09-20 05:17:43', '2025-09-10 01:13:36'),
(4, 'Necklaces', 'necklaces', 'fas fa-home', 'uploads/custom-images/category-2025-08-23-06-04-48-8061.jpg', 1, '2022-09-20 05:24:47', '2025-10-05 00:47:44'),
(5, 'Bangles', 'bangles', 'fas fa-basketball-ball', 'uploads/custom-images/category-2022-12-11-11-33-53-3215.png', 1, '2022-09-20 05:26:13', '2025-10-05 00:48:02'),
(6, 'Tikli’s', 'tiklis', 'fas fa-bicycle', 'uploads/custom-images/category-2022-12-11-11-34-02-8745.png', 1, '2022-09-20 05:28:22', '2025-10-05 00:48:27'),
(7, 'Other Accessories', 'other-accessories', 'fas fa-street-view', 'uploads/custom-images/category-2023-03-23-02-24-28-7377.png', 1, '2022-09-20 05:35:28', '2025-10-05 00:48:47'),
(8, 'Activewear', 'activewear', 'fab fa-android', 'uploads/custom-images/category-2023-03-23-02-24-38-1948.png', 0, '2022-09-20 05:38:38', '2025-08-23 12:04:08'),
(9, 'Grooming', 'grooming', 'fas fa-adjust', 'uploads/custom-images/category-2023-03-23-02-24-47-5149.png', 0, '2022-09-20 06:04:03', '2025-08-23 12:04:07'),
(10, 'Cosmetics', 'cosmetics', 'fas fa-cogs', 'uploads/custom-images/category-2023-03-23-02-24-58-4960.png', 0, '2022-09-20 06:05:41', '2025-08-23 12:04:07'),
(11, 'Watch', 'watch', 'fab fa-adn', 'uploads/custom-images/category-2023-03-23-12-45-28-4524.png', 0, '2022-10-17 04:20:57', '2025-08-23 12:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `child_categories`
--

CREATE TABLE `child_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `sub_category_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `child_categories`
--

INSERT INTO `child_categories` (`id`, `category_id`, `sub_category_id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'LG', 'lg', 1, '2022-09-20 06:02:47', '2022-09-20 06:02:47'),
(2, 1, 2, 'HP', 'hp', 1, '2022-09-20 06:03:17', '2022-09-20 06:03:17'),
(3, 2, 5, 'PlayStation 4', 'playstation-4', 1, '2022-09-20 07:13:18', '2022-09-20 07:13:18'),
(4, 2, 5, 'PlayStation 5', 'playstation-5', 1, '2022-09-20 07:13:29', '2022-09-20 07:13:29'),
(5, 1, 1, 'Samsung', 'samsung', 1, '2022-09-20 07:13:41', '2022-09-20 07:13:41'),
(6, 1, 1, 'Apple', 'apple', 1, '2022-09-20 07:13:51', '2022-09-20 07:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `country_state_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `country_state_id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Florida City', 'florida-city', 1, '2022-01-30 09:29:19', '2022-02-06 04:18:33'),
(2, 1, 'Los Angeles', 'los-angeles', 1, '2022-01-30 09:29:29', '2022-02-06 04:20:30'),
(4, 2, 'Tallahassee', 'tallahassee', 1, '2022-02-06 04:18:49', '2022-02-06 04:18:49'),
(5, 2, 'Weston', 'weston', 1, '2022-02-06 04:19:56', '2022-02-06 04:19:56'),
(6, 1, 'San Jose', 'san-jose', 1, '2022-02-06 04:21:08', '2022-02-06 04:21:08'),
(7, 1, 'San Diego', 'san-diego', 1, '2022-02-06 04:21:26', '2022-02-06 04:21:26'),
(8, 4, 'Gandhinagar', 'gandhinagar', 1, '2022-02-06 04:22:21', '2022-02-06 04:22:21'),
(9, 5, 'Chandigarh', 'chandigarh', 1, '2022-02-06 04:22:44', '2022-02-06 04:22:44'),
(10, 7, 'London', 'london', 1, '2022-02-06 04:23:12', '2022-02-06 04:23:12'),
(11, 7, 'Liverpool', 'liverpool', 1, '2022-02-06 04:23:29', '2022-07-31 02:52:47'),
(21, 7, 'Edinburgh', 'edinburgh', 1, '2025-08-30 04:40:02', '2025-08-30 04:40:02'),
(22, 9, 'Cardiff', 'cardiff', 1, '2025-08-30 04:40:46', '2025-08-30 04:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `compare_products`
--

CREATE TABLE `compare_products` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `compare_products`
--

INSERT INTO `compare_products` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(16, 16, 13, '2022-10-26 19:40:35', '2022-10-26 19:40:35'),
(17, 21, 26, '2022-10-30 21:34:20', '2022-10-30 21:34:20'),
(18, 21, 36, '2022-10-30 21:36:39', '2022-10-30 21:36:39'),
(41, 7, 9, '2023-03-30 07:15:40', '2023-03-30 07:15:40'),
(42, 7, 10, '2023-05-26 12:53:29', '2023-05-26 12:53:29'),
(43, 7, 11, '2023-05-26 12:53:31', '2023-05-26 12:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_pages`
--

CREATE TABLE `contact_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `map` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_pages`
--

INSERT INTO `contact_pages` (`id`, `title`, `description`, `email`, `address`, `phone`, `map`, `created_at`, `updated_at`) VALUES
(1, 'Contact Information', 'Fill the form below or write us .We will help you as soon as possible.', 'shopus@gmail.com', '2140 W Thunderbird Rd, Phoenix, Arkansas 85023, United States', '+1347-430-9510', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7304.67270436821!2d90.38005526977537!3d23.735382200000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8c6d25f0a91%3A0xf8da21d64c3856dc!2sNew%20Work%20Aquarium%20Center!5e0!3m2!1sen!2sbd!4v1668246013351!5m2!1sen!2sbd', '2022-09-22 11:08:24', '2023-03-22 10:48:18');

-- --------------------------------------------------------

--
-- Table structure for table `cookie_consents`
--

CREATE TABLE `cookie_consents` (
  `id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `border` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `corners` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `border_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_bg_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_text_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `link_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btn_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cookie_consents`
--

INSERT INTO `cookie_consents` (`id`, `status`, `border`, `corners`, `background_color`, `text_color`, `border_color`, `btn_bg_color`, `btn_text_color`, `message`, `link_text`, `btn_text`, `link`, `created_at`, `updated_at`) VALUES
(1, 1, 'thin', 'normal', '#184dec', '#fafafa', '#0a58d6', '#fffceb', '#222758', 'This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only upon approval.', 'More Info', 'Yes', NULL, NULL, '2022-11-14 03:39:20');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'United State', 'united-state', 0, '2022-01-30 09:28:28', '2025-08-23 09:49:41'),
(2, 'India', 'india', 0, '2022-01-30 09:28:39', '2025-08-23 09:49:42'),
(4, 'United Kindom', 'united-kindom', 1, '2022-02-06 04:11:51', '2025-08-23 09:49:38'),
(5, 'Australia', 'australia', 0, '2022-02-06 04:12:36', '2025-08-23 09:49:43'),
(10, 'Bangladesh', 'bangladesh', 0, '2022-09-22 05:46:54', '2025-08-23 09:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `country_states`
--

CREATE TABLE `country_states` (
  `id` bigint UNSIGNED NOT NULL,
  `country_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `country_states`
--

INSERT INTO `country_states` (`id`, `country_id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'California', 'california', 1, '2022-01-30 09:29:00', '2022-02-06 04:14:28'),
(2, 1, 'Florida', 'florida', 1, '2022-01-30 09:29:07', '2022-02-06 04:14:42'),
(3, 1, 'Alaska', 'alaska', 1, '2022-02-05 07:49:14', '2022-02-06 04:15:09'),
(4, 2, 'Gujarat', 'gujarat', 1, '2022-02-06 04:16:27', '2022-02-06 04:16:27'),
(5, 2, 'Punjab', 'punjab', 1, '2022-02-06 04:16:39', '2022-02-06 04:16:39'),
(6, 2, 'Rajasthan', 'rajasthan', 1, '2022-02-06 04:16:48', '2022-02-06 04:16:48'),
(7, 4, 'England', 'england', 1, '2022-02-06 04:17:35', '2022-02-06 04:17:35'),
(8, 4, 'Scotland', 'scotland', 1, '2022-02-06 04:17:44', '2022-02-06 04:17:44'),
(9, 4, 'Wales', 'wales', 1, '2022-02-06 04:17:53', '2022-02-06 04:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_purchase_price` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `offer_type` int NOT NULL DEFAULT '0',
  `discount` double NOT NULL DEFAULT '0',
  `max_quantity` int NOT NULL DEFAULT '0',
  `expired_date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_qty` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `name`, `code`, `min_purchase_price`, `offer_type`, `discount`, `max_quantity`, `expired_date`, `apply_qty`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Happy New Year', 'newyear', '600', 1, 50, 100, '2025-11-26', 9, 1, '2022-11-10 19:33:12', '2022-11-27 20:24:58');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int NOT NULL,
  `code` varchar(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(1, 'AFA', 'Afghan Afghani', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'ALL', 'Albanian Lek', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'DZD', 'Algerian Dinar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'AOA', 'Angolan Kwanza', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'ARS', 'Argentine Peso', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'AMD', 'Armenian Dram', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'AWG', 'Aruban Florin', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'AUD', 'Australian Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'AZN', 'Azerbaijani Manat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'BSD', 'Bahamian Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'BHD', 'Bahraini Dinar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'BDT', 'Bangladeshi Taka', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'BBD', 'Barbadian Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'BYR', 'Belarusian Ruble', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'BEF', 'Belgian Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'BZD', 'Belize Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'BMD', 'Bermudan Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'BTN', 'Bhutanese Ngultrum', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'BTC', 'Bitcoin', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'BOB', 'Bolivian Boliviano', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'BAM', 'Bosnia-Herzegovina Convertible Mark', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'BWP', 'Botswanan Pula', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'BRL', 'Brazilian Real', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'GBP', 'British Pound Sterling', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'BND', 'Brunei Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'BGN', 'Bulgarian Lev', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'BIF', 'Burundian Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'KHR', 'Cambodian Riel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'CAD', 'Canadian Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'CVE', 'Cape Verdean Escudo', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'KYD', 'Cayman Islands Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'XOF', 'CFA Franc BCEAO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'XAF', 'CFA Franc BEAC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'XPF', 'CFP Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'CLP', 'Chilean Peso', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'CNY', 'Chinese Yuan', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'COP', 'Colombian Peso', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'KMF', 'Comorian Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'CDF', 'Congolese Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'CRC', 'Costa Rican ColÃ³n', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'HRK', 'Croatian Kuna', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'CUC', 'Cuban Convertible Peso', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'CZK', 'Czech Republic Koruna', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'DKK', 'Danish Krone', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'DJF', 'Djiboutian Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'DOP', 'Dominican Peso', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'XCD', 'East Caribbean Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'EGP', 'Egyptian Pound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'ERN', 'Eritrean Nakfa', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'EEK', 'Estonian Kroon', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'ETB', 'Ethiopian Birr', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'EUR', 'Euro', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'FKP', 'Falkland Islands Pound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'FJD', 'Fijian Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'GMD', 'Gambian Dalasi', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'GEL', 'Georgian Lari', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'DEM', 'German Mark', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'GHS', 'Ghanaian Cedi', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'GIP', 'Gibraltar Pound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'GRD', 'Greek Drachma', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'GTQ', 'Guatemalan Quetzal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'GNF', 'Guinean Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'GYD', 'Guyanaese Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'HTG', 'Haitian Gourde', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'HNL', 'Honduran Lempira', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'HKD', 'Hong Kong Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'HUF', 'Hungarian Forint', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'ISK', 'Icelandic KrÃ³na', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'INR', 'Indian Rupee', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'IDR', 'Indonesian Rupiah', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'IRR', 'Iranian Rial', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'IQD', 'Iraqi Dinar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'ILS', 'Israeli New Sheqel', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'ITL', 'Italian Lira', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'JMD', 'Jamaican Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'JPY', 'Japanese Yen', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'JOD', 'Jordanian Dinar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'KZT', 'Kazakhstani Tenge', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'KES', 'Kenyan Shilling', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'KWD', 'Kuwaiti Dinar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'KGS', 'Kyrgystani Som', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'LAK', 'Laotian Kip', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'LVL', 'Latvian Lats', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'LBP', 'Lebanese Pound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'LSL', 'Lesotho Loti', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'LRD', 'Liberian Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'LYD', 'Libyan Dinar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'LTL', 'Lithuanian Litas', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'MOP', 'Macanese Pataca', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'MKD', 'Macedonian Denar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'MGA', 'Malagasy Ariary', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'MWK', 'Malawian Kwacha', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'MYR', 'Malaysian Ringgit', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'MVR', 'Maldivian Rufiyaa', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'MRO', 'Mauritanian Ouguiya', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'MUR', 'Mauritian Rupee', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'MXN', 'Mexican Peso', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'MDL', 'Moldovan Leu', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'MNT', 'Mongolian Tugrik', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'MAD', 'Moroccan Dirham', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'MZM', 'Mozambican Metical', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'MMK', 'Myanmar Kyat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'NAD', 'Namibian Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'NPR', 'Nepalese Rupee', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'ANG', 'Netherlands Antillean Guilder', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'TWD', 'New Taiwan Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'NZD', 'New Zealand Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'NIO', 'Nicaraguan CÃ³rdoba', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'NGN', 'Nigerian Naira', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'KPW', 'North Korean Won', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'NOK', 'Norwegian Krone', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 'OMR', 'Omani Rial', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'PKR', 'Pakistani Rupee', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'PAB', 'Panamanian Balboa', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'PGK', 'Papua New Guinean Kina', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'PYG', 'Paraguayan Guarani', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'PEN', 'Peruvian Nuevo Sol', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'PHP', 'Philippine Peso', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'PLN', 'Polish Zloty', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'QAR', 'Qatari Rial', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'RON', 'Romanian Leu', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'RUB', 'Russian Ruble', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'RWF', 'Rwandan Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 'SVC', 'Salvadoran ColÃ³n', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'WST', 'Samoan Tala', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'SAR', 'Saudi Riyal', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'RSD', 'Serbian Dinar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'SCR', 'Seychellois Rupee', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'SLL', 'Sierra Leonean Leone', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 'SGD', 'Singapore Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 'SKK', 'Slovak Koruna', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 'SBD', 'Solomon Islands Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 'SOS', 'Somali Shilling', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 'ZAR', 'South African Rand', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 'KRW', 'South Korean Won', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 'XDR', 'Special Drawing Rights', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 'LKR', 'Sri Lankan Rupee', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 'SHP', 'St. Helena Pound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 'SDG', 'Sudanese Pound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 'SRD', 'Surinamese Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 'SZL', 'Swazi Lilangeni', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 'SEK', 'Swedish Krona', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 'CHF', 'Swiss Franc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 'SYP', 'Syrian Pound', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 'STD', 'São Tomé and Príncipe Dobra', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 'TJS', 'Tajikistani Somoni', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 'TZS', 'Tanzanian Shilling', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 'THB', 'Thai Baht', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 'TOP', 'Tongan pa\'anga', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 'TTD', 'Trinidad & Tobago Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 'TND', 'Tunisian Dinar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 'TRY', 'Turkish Lira', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 'TMT', 'Turkmenistani Manat', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 'UGX', 'Ugandan Shilling', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 'UAH', 'Ukrainian Hryvnia', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 'AED', 'United Arab Emirates Dirham', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 'UYU', 'Uruguayan Peso', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 'USD', 'US Dollar', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 'UZS', 'Uzbekistan Som', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 'VUV', 'Vanuatu Vatu', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 'VEF', 'Venezuelan BolÃ­var', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 'VND', 'Vietnamese Dong', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 'YER', 'Yemeni Rial', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 'ZMK', 'Zambian Kwacha', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `currency_countries`
--

CREATE TABLE `currency_countries` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `code` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

--
-- Dumping data for table `currency_countries`
--

INSERT INTO `currency_countries` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Andorra', 'AD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Afghanistan', 'AF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Åland Islands', 'AX', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Albania', 'AL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Algeria', 'DZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'American Samoa', 'AS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Angola', 'AO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'Anguilla', 'AI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Antarctica', 'AQ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Antigua and Barbuda', 'AG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Argentina', 'AR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'Armenia', 'AM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'Aruba', 'AW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(14, 'Australia', 'AU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'Austria', 'AT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'Azerbaijan', 'AZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'Bahamas', 'BS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'Bahrain', 'BH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'Bangladesh', 'BD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'Barbados', 'BB', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'Belarus', 'BY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'Belgium', 'BE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'Belize', 'BZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'Benin', 'BJ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'Bermuda', 'BM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'Bhutan', 'BT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'Bolivia (Plurinational State of)', 'BO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'Bonaire, Sint Eustatius and Saba', 'BQ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Bosnia and Herzegovina', 'BA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 'Botswana', 'BW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Bouvet Island', 'BV', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 'Brazil', 'BR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(33, 'British Indian Ocean Territory', 'IO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, 'Brunei Darussalam', 'BN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, 'Bulgaria', 'BG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'Burkina Faso', 'BF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(37, 'Burundi', 'BI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(38, 'Cabo Verde', 'CV', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(39, 'Cambodia', 'KH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(40, 'Cameroon', 'CM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'Canada', 'CA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(42, 'Cayman Islands', 'KY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(43, 'Central African Republic', 'CF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(44, 'Chad', 'TD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(45, 'Chile', 'CL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(46, 'China', 'CN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(47, 'Christmas Island', 'CX', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(48, 'Cocos (Keeling) Islands', 'CC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(49, 'Colombia', 'CO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(50, 'Comoros', 'KM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(51, 'Congo', 'CG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(52, 'Congo (Democratic Republic of the)', 'CD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(53, 'Cook Islands', 'CK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(54, 'Costa Rica', 'CR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(55, 'Côte d\'Ivoire', 'CI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(56, 'Croatia', 'HR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(57, 'Cuba', 'CU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(58, 'Curaçao', 'CW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(59, 'Cyprus', 'CY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(60, 'Czech Republic', 'CZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(61, 'Denmark', 'DK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(62, 'Djibouti', 'DJ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(63, 'Dominica', 'DM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(64, 'Dominican Republic', 'DO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(65, 'Ecuador', 'EC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(66, 'Egypt', 'EG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(67, 'El Salvador', 'SV', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(68, 'Equatorial Guinea', 'GQ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(69, 'Eritrea', 'ER', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(70, 'Estonia', 'EE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(71, 'Ethiopia', 'ET', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(72, 'Falkland Islands (Malvinas)', 'FK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(73, 'Faroe Islands', 'FO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(74, 'Fiji', 'FJ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(75, 'Finland', 'FI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(76, 'France', 'FR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(77, 'French Guiana', 'GF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(78, 'French Polynesia', 'PF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(79, 'French Southern Territories', 'TF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(80, 'Gabon', 'GA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(81, 'Gambia', 'GM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(82, 'Georgia', 'GE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(83, 'Germany', 'DE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(84, 'Ghana', 'GH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(85, 'Gibraltar', 'GI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(86, 'Greece', 'GR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(87, 'Greenland', 'GL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(88, 'Grenada', 'GD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(89, 'Guadeloupe', 'GP', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(90, 'Guam', 'GU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(91, 'Guatemala', 'GT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(92, 'Guernsey', 'GG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(93, 'Guinea', 'GN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(94, 'Guinea-Bissau', 'GW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(95, 'Guyana', 'GY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(96, 'Haiti', 'HT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(97, 'Heard Island and McDonald Islands', 'HM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(98, 'Holy See', 'VA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(99, 'Honduras', 'HN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(100, 'Hong Kong', 'HK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(101, 'Hungary', 'HU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(102, 'Iceland', 'IS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(103, 'India', 'IN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(104, 'Indonesia', 'ID', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(105, 'Iran (Islamic Republic of)', 'IR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(106, 'Iraq', 'IQ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(107, 'Ireland', 'IE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(108, 'Isle of Man', 'IM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(109, 'Israel', 'IL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(110, 'Italy', 'IT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(111, 'Jamaica', 'JM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(112, 'Japan', 'JP', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(113, 'Jersey', 'JE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(114, 'Jordan', 'JO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(115, 'Kazakhstan', 'KZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(116, 'Kenya', 'KE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(117, 'Kiribati', 'KI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(118, 'Korea (Democratic People\'s Republic of)', 'KP', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(119, 'Korea (Republic of)', 'KR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(120, 'Kuwait', 'KW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(121, 'Kyrgyzstan', 'KG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(122, 'Lao People\'s Democratic Republic', 'LA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(123, 'Latvia', 'LV', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(124, 'Lebanon', 'LB', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'Lesotho', 'LS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'Liberia', 'LR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'Libya', 'LY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'Liechtenstein', 'LI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'Lithuania', 'LT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(130, 'Luxembourg', 'LU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(131, 'Macao', 'MO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(132, 'Macedonia (the former Yugoslav Republic of)', 'MK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(133, 'Madagascar', 'MG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(134, 'Malawi', 'MW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(135, 'Malaysia', 'MY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(136, 'Maldives', 'MV', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(137, 'Mali', 'ML', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(138, 'Malta', 'MT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(139, 'Marshall Islands', 'MH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(140, 'Martinique', 'MQ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(141, 'Mauritania', 'MR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(142, 'Mauritius', 'MU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 'Mayotte', 'YT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 'Mexico', 'MX', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 'Micronesia (Federated States of)', 'FM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 'Moldova (Republic of)', 'MD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 'Monaco', 'MC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 'Mongolia', 'MN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 'Montenegro', 'ME', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 'Montserrat', 'MS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 'Morocco', 'MA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 'Mozambique', 'MZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 'Myanmar', 'MM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 'Namibia', 'NA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 'Nauru', 'NR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 'Nepal', 'NP', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 'Netherlands', 'NL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 'New Caledonia', 'NC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 'New Zealand', 'NZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 'Nicaragua', 'NI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 'Niger', 'NE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 'Nigeria', 'NG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 'Niue', 'NU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 'Norfolk Island', 'NF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 'Northern Mariana Islands', 'MP', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 'Norway', 'NO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 'Oman', 'OM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 'Pakistan', 'PK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 'Palau', 'PW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 'Palestine, State of', 'PS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 'Panama', 'PA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 'Papua New Guinea', 'PG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 'Paraguay', 'PY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 'Peru', 'PE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 'Philippines', 'PH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 'Pitcairn', 'PN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 'Poland', 'PL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, 'Portugal', 'PT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, 'Puerto Rico', 'PR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, 'Qatar', 'QA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, 'Réunion', 'RE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, 'Romania', 'RO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, 'Russian Federation', 'RU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, 'Rwanda', 'RW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, 'Saint Barthélemy', 'BL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, 'Saint Helena, Ascension and Tristan da Cunha', 'SH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, 'Saint Kitts and Nevis', 'KN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, 'Saint Lucia', 'LC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, 'Saint Martin (French part)', 'MF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, 'Saint Pierre and Miquelon', 'PM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, 'Saint Vincent and the Grenadines', 'VC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, 'Samoa', 'WS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, 'San Marino', 'SM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, 'Sao Tome and Principe', 'ST', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, 'Saudi Arabia', 'SA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, 'Senegal', 'SN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, 'Serbia', 'RS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, 'Seychelles', 'SC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, 'Sierra Leone', 'SL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, 'Singapore', 'SG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, 'Sint Maarten (Dutch part)', 'SX', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, 'Slovakia', 'SK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, 'Slovenia', 'SI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, 'Solomon Islands', 'SB', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, 'Somalia', 'SO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, 'South Africa', 'ZA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, 'South Georgia and the South Sandwich Islands', 'GS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, 'South Sudan', 'SS', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, 'Spain', 'ES', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, 'Sri Lanka', 'LK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(211, 'Sudan', 'SD', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(212, 'Suriname', 'SR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(213, 'Svalbard and Jan Mayen', 'SJ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(214, 'Swaziland', 'SZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(215, 'Sweden', 'SE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(216, 'Switzerland', 'CH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(217, 'Syrian Arab Republic', 'SY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(218, 'Taiwan, Province of China', 'TW', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(219, 'Tajikistan', 'TJ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(220, 'Tanzania, United Republic of', 'TZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(221, 'Thailand', 'TH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(222, 'Timor-Leste', 'TL', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(223, 'Togo', 'TG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(224, 'Tokelau', 'TK', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(225, 'Tonga', 'TO', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(226, 'Trinidad and Tobago', 'TT', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(227, 'Tunisia', 'TN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(228, 'Turkey', 'TR', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(229, 'Turkmenistan', 'TM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(230, 'Turks and Caicos Islands', 'TC', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(231, 'Tuvalu', 'TV', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(232, 'Uganda', 'UG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(233, 'Ukraine', 'UA', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(234, 'United Arab Emirates', 'AE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(235, 'United Kingdom of Great Britain and Northern Ireland', 'GB', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(236, 'United States Minor Outlying Islands', 'UM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(237, 'United States of America', 'US', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(238, 'Uruguay', 'UY', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(239, 'Uzbekistan', 'UZ', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(240, 'Vanuatu', 'VU', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(241, 'Venezuela (Bolivarian Republic of)', 'VE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(242, 'Viet Nam', 'VN', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(243, 'Virgin Islands (British)', 'VG', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(244, 'Virgin Islands (U.S.)', 'VI', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(245, 'Wallis and Futuna', 'WF', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(246, 'Western Sahara', 'EH', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(247, 'Yemen', 'YE', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(248, 'Zambia', 'ZM', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(249, 'Zimbabwe', 'ZW', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `custom_pages`
--

CREATE TABLE `custom_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `page_name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `custom_pages`
--

INSERT INTO `custom_pages` (`id`, `page_name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Returns and Refunds', 'returns-and-refunds', '<p>Return Policy</p>\r\n<p>All sales are final. No Refunds or exchanges after point of sale.</p>\r\n<p>For any client specific or order specific questions please contact us via email nooraani@gmail.com or DM us on Instagram @Nooraani.official</p>', 1, '2025-10-05 23:19:09', '2025-10-07 04:45:20'),
(4, 'Shipping & Delivery', 'shipping-delivery', '<p>Standard Shipping Time is 5-7 working days, in some cases this can vary. For orders required sooner than this please contact us via email on nooraani@gmail.com or DM us on Instagram to confirm timescales prior to placing your order.&nbsp;</p>\r\n<p>International couriers are sent by DHL express.</p>\r\n<p>National couriers are sent by Royal Mail unless during strike action whereby, the next best alternative (DHL or DPD) is used.</p>\r\n<p>Clients need to specify the COMPLETE address and shipment details of the person who will be available to receive the order. Business addresses must be clearly stated and informed of at time of order.</p>\r\n<p>The recipient is required to cooperate with the courier agent in providing the identification proof.</p>\r\n<p>The user is required to bear all the shipping and handling charges or customs charges. &nbsp;</p>\r\n<p>For both free and paid deliveries, you will need to make sure that you cover any ongoing customs or taxes costs should any be incurred when shipping to your destination. At Nooraani we do our best to deliver within the agreed time, however we cannot be held responsible for any delays caused by the customs clearance of the destination country.</p>\r\n<p>Nooraani is not responsible for items dispatched to wrong address because of incorrect details given at check out, however we will try to rectify incorrect address issues at the best of our ability.</p>', 1, '2025-10-05 23:22:28', '2025-10-07 04:34:45');

-- --------------------------------------------------------

--
-- Table structure for table `custom_paginations`
--

CREATE TABLE `custom_paginations` (
  `id` bigint UNSIGNED NOT NULL,
  `page_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `custom_paginations`
--

INSERT INTO `custom_paginations` (`id`, `page_name`, `qty`, `created_at`, `updated_at`) VALUES
(1, 'Blog Page', 6, NULL, '2022-11-15 21:45:36'),
(2, 'Product Page', 9, NULL, '2022-11-15 21:45:59'),
(3, 'Brand Page', 10, NULL, '2022-11-03 06:53:14'),
(4, 'Blog Comment', 6, NULL, '2022-11-03 06:53:46'),
(5, 'Product Review', 8, NULL, '2022-06-11 11:13:13'),
(6, 'Seller page', 8, NULL, '2022-06-11 11:13:13');

-- --------------------------------------------------------

--
-- Table structure for table `email_configurations`
--

CREATE TABLE `email_configurations` (
  `id` bigint UNSIGNED NOT NULL,
  `mail_type` tinyint DEFAULT NULL,
  `mail_host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_port` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_encryption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_configurations`
--

INSERT INTO `email_configurations` (`id`, `mail_type`, `mail_host`, `mail_port`, `email`, `smtp_username`, `smtp_password`, `mail_encryption`, `created_at`, `updated_at`) VALUES
(1, 2, 'localhost', '1025', 'test@gmail.com', 'null', 'null', 'tls', NULL, '2025-08-24 04:10:16');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `subject`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Password Reset', 'Password Reset', '<h4>Dear <b>{{name}}</b>,</h4><p>You have requested to reset your password. Please click the link below to reset your password:</p><p><a href=\"https://ecom.test/reset-password/{{token}}\" style=\"background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;\">Reset Password</a></p><p>If you did not request this password reset, please ignore this email.</p><p>This link will expire in 60 minutes.</p>', NULL, '2025-08-27 05:23:37'),
(2, 'Contact Email', 'Contact Email', '<p>Name: <b>{{name}}</b></p><p>\r\n\r\nEmail: <b>{{email}}</b></p><p>\r\n\r\nPhone: <b>{{phone}}</b></p><p><span style=\"background-color: transparent;\">Subject: <b>{{subject}}</b></span></p><p>\r\n\r\nMessage: <b>{{message}}</b></p>', NULL, '2021-12-10 23:44:34'),
(3, 'Subscribe Notification', 'Subscribe Notification', '<h2><b>Hi there</b>,</h2><p>\r\nCongratulations! Your Subscription has been created successfully. Please copy and past this code and Verified Your Subscription. If you won\'t verified, after 24hourse your subscription will be deny</p>', NULL, '2021-12-10 23:44:53'),
(4, 'User Verification', 'User Verification', '<p>Dear <b>{{user_name}}</b>,\r\n</p><p>Congratulations! Your Account has been created successfully. Please copy and past and Active your Account.</p>', NULL, '2021-12-10 23:45:25'),
(5, 'Seller Withdraw', 'Seller Withdraw Approval', '<p>Hi <b>{{seller_name}}</b>,</p><p>Your withdraw Request Approval successfully. Please check your account.</p><p>Withdraw Details:</p><p>Withdraw method : <b>{{withdraw_method}}</b>,</p><p>Total Amount :<b> {{total_amount}}</b>,</p><p>Withdraw charge : <b>{{withdraw_charge}}</b>,</p><p>Withdraw&nbsp; Amount : <b>{{withdraw_amount}}</b>,</p><p>Approval Date :<b> {{approval_date}}</b></p>', NULL, '2021-12-26 03:24:45'),
(6, 'Order Successfully', 'Order Successfully', '<p>Hi {{user_name}},</p><p> \r\nThanks for your new order. Your order id has been submited .</p><p>Total Amount : {{total_amount}},</p><p>Payment Method : {{payment_method}},</p><p>Payment Status : {{payment_status}},</p><p>Order Status : {{order_status}},</p><p>Order Date: {{order_date}},</p><p>Order Detail: {{order_detail}}</p>', NULL, '2022-01-10 21:37:03'),
(7, 'Seller Request Approved', 'Seller Request Approved', '<p>Hi {{name}},\r\n</p><p><span style=\"background-color: transparent;\">Congratulations !!&nbsp;</span>Your Shop account has been approved successfully</p>', NULL, '2022-02-05 08:59:34'),
(8, 'UserRegistration', 'Create An Account', '<p>Hi {{name}},</p><p><span style=\"background-color: transparent;\">Congratulations !!&nbsp;</span>Your Shop account has been Created successfully</p>', '2023-11-29 08:51:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `error_pages`
--

CREATE TABLE `error_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `page_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `error_pages`
--

INSERT INTO `error_pages` (`id`, `page_name`, `image`, `header`, `button_text`, `created_at`, `updated_at`) VALUES
(1, '404 Error', 'uploads/website-images/errorpage-2022-12-11-11-17-14-2735.png', 'That Page Doesn\'t Exist!', '', NULL, '2025-10-04 23:32:39'),
(2, '500 Error', '500.jpg', 'That Page Doesn\'t Exist!', 'Go to Home', NULL, '2021-12-06 09:46:52'),
(3, '505 Error', '505.jpg', 'That Page Doesn\'t Exist!', 'Go to Home', NULL, '2021-12-06 09:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `facebook_comments`
--

CREATE TABLE `facebook_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment_type` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facebook_comments`
--

INSERT INTO `facebook_comments` (`id`, `app_id`, `comment_type`, `created_at`, `updated_at`) VALUES
(1, 'test', 1, NULL, '2022-06-11 11:07:29');

-- --------------------------------------------------------

--
-- Table structure for table `facebook_pixels`
--

CREATE TABLE `facebook_pixels` (
  `id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facebook_pixels`
--

INSERT INTO `facebook_pixels` (`id`, `status`, `app_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'fsdf45sdf4sdf', NULL, '2022-06-11 11:17:42');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint UNSIGNED NOT NULL,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `status`, `created_at`, `updated_at`) VALUES
(2, 'How do I place an order on your website?', '<p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify;\">To place an order on our website, simply browse our products and add the items you want to your cart. Once you are ready to checkout, follow the prompts to enter your shipping and payment information. Once your order is confirmed, we will process and ship it to you as soon as possible.</p>\r\n<p>&nbsp;</p>', 1, '2022-09-22 13:27:39', '2023-03-22 11:02:11'),
(3, 'How can I track my order?', '<p>Once your order has been shipped, we will provide you with a tracking number that you can use to track your package. You can use this number to track your package on our website or through the carrier\'s website.</p>', 1, '2022-09-22 13:28:08', '2023-03-22 11:02:23'),
(4, 'What payment methods do you accept?', '<p>We accept a variety of payment methods, including credit and debit cards, PayPal, and other third-party payment platforms. All payments are processed securely to ensure the safety of your personal information.</p>', 1, '2022-09-22 13:28:20', '2023-03-22 11:02:36'),
(6, 'Do you offer international shipping?', '<p>Yes, we offer international shipping to many countries around the world. However, shipping times and fees may vary depending on your location. Please check our shipping policy page for more information about international shipping.</p>\r\n<p>&nbsp;</p>', 1, '2022-10-30 21:26:43', '2023-03-22 11:03:02'),
(7, 'What if a stone falls out?', '<p>our jewellery is quality checked before packaged and dispatched. If there are any issues, please email us or DM us @nooraani.official</p>', 1, '2025-10-07 04:24:14', '2025-10-07 04:24:14'),
(8, 'What if we dont like the set?', '<p>We have a &nbsp;strict no return or replacement policy. In order to keep our pieces affordable for our customers, we are unable to offer refunds or replacements.</p>', 1, '2025-10-07 04:24:33', '2025-10-07 04:24:33'),
(9, 'What if I have written the wrong address?', '<p>Please email us or DM us as soon as possible and we will attempt to rectify any issues.</p>', 1, '2025-10-07 04:24:45', '2025-10-07 04:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `featured_categories`
--

CREATE TABLE `featured_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `featured_categories`
--

INSERT INTO `featured_categories` (`id`, `category_id`, `created_at`, `updated_at`) VALUES
(3, 1, '2025-09-10 01:18:51', '2025-09-10 01:18:51'),
(4, 2, '2025-09-10 01:19:01', '2025-09-10 01:19:01'),
(7, 3, '2025-09-10 01:32:56', '2025-09-10 01:32:56'),
(8, 4, '2025-09-10 01:33:00', '2025-09-10 01:33:00');

-- --------------------------------------------------------

--
-- Table structure for table `flash_sales`
--

CREATE TABLE `flash_sales` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `homepage_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `flashsale_page_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_time` datetime NOT NULL,
  `offer` double NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `background_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#dc3545',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flash_sales`
--

INSERT INTO `flash_sales` (`id`, `title`, `description`, `homepage_image`, `flashsale_page_image`, `end_time`, `offer`, `status`, `background_color`, `created_at`, `updated_at`) VALUES
(1, 'WOO! Flash Sale', 'You get into the 2k+ best Products in Flash offer with as in shaped sofa for sale.', 'uploads/website-images/flash_sale--2022-10-26-12-05-45-2348.png', 'uploads/website-images/flash_sale--2022-09-20-10-54-12-8555.png', '2025-12-31 17:29:00', 20, 1, '#ababbf', NULL, '2025-10-04 01:13:45');

-- --------------------------------------------------------

--
-- Table structure for table `flash_sale_products`
--

CREATE TABLE `flash_sale_products` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flash_sale_products`
--

INSERT INTO `flash_sale_products` (`id`, `product_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2022-09-20 08:02:41', '2025-08-23 10:05:08'),
(2, 2, 1, '2022-09-20 08:02:45', '2022-09-20 08:02:45'),
(3, 3, 1, '2022-09-20 08:02:48', '2022-09-20 08:02:48'),
(4, 4, 1, '2022-09-20 08:02:52', '2022-09-20 08:02:52'),
(5, 8, 1, '2022-09-20 08:02:56', '2022-09-20 08:02:56'),
(6, 9, 1, '2022-09-20 08:03:01', '2022-09-20 08:03:01'),
(7, 11, 1, '2022-09-20 08:03:05', '2022-09-20 08:03:05'),
(8, 27, 1, '2025-08-23 10:05:23', '2025-08-23 10:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `flutterwaves`
--

CREATE TABLE `flutterwaves` (
  `id` bigint UNSIGNED NOT NULL,
  `public_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_rate` double NOT NULL DEFAULT '1',
  `country_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flutterwaves`
--

INSERT INTO `flutterwaves` (`id`, `public_key`, `secret_key`, `currency_rate`, `country_code`, `currency_code`, `title`, `logo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'FLWPUBK_TEST-REPLACE_ME', 'FLWSECK_TEST-REPLACE_ME', 417.35, 'NG', 'NGN', 'Ecommerce', 'uploads/website-images/flutterwave-2021-12-30-03-44-30-8813.jpg', 1, NULL, '2025-09-16 23:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `footers`
--

CREATE TABLE `footers` (
  `id` bigint UNSIGNED NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_column` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_column` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `third_column` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copyright` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#343a40',
  `background_image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footers`
--

INSERT INTO `footers` (`id`, `phone`, `email`, `address`, `first_column`, `second_column`, `third_column`, `copyright`, `footer_color`, `background_image`, `created_at`, `updated_at`) VALUES
(1, '+8801841262729', 'nobleedgegroup@gmail.com', 'Noakhali, Dhaka Bangaldesh', 'Important Links', 'General Links', 'Contact Info', 'Nooraani All rights reserved', '#343a40', 'uploads/website-images/payment-card-2022-12-06-03-36-53-4540.png', NULL, '2025-10-05 23:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `footer_links`
--

CREATE TABLE `footer_links` (
  `id` bigint UNSIGNED NOT NULL,
  `column` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_links`
--

INSERT INTO `footer_links` (`id`, `column`, `link`, `title`, `created_at`, `updated_at`) VALUES
(1, '1', '/about', 'About Us', '2022-09-20 08:19:02', '2022-11-01 14:49:14'),
(2, '1', '/privacy-policy', 'Privacy Policy', '2022-09-20 08:19:15', '2022-11-01 14:54:01'),
(3, '1', '/faq', 'FAQ', '2022-09-20 08:19:24', '2022-11-01 14:54:29'),
(5, '2', '/page/returns-and-refunds', 'Returns and Refunds', '2022-09-20 08:19:46', '2025-10-05 23:19:58'),
(6, '2', '/page/shipping-delivery', 'Shipping & Delivery', '2022-09-20 08:19:52', '2025-10-05 23:23:27'),
(7, '3', '/tracking-order', 'Track Order', '2022-09-20 08:20:05', '2022-11-01 15:22:50'),
(8, '3', '/terms-condition', 'Delivery & Returns', '2022-09-20 08:20:11', '2023-03-23 08:59:24'),
(9, '3', '/contact', 'Contact Us', '2022-09-20 08:20:19', '2022-11-01 14:51:34'),
(11, '1', '/terms-conditions', 'Terms and Condition', '2022-11-01 14:55:00', '2025-10-04 23:39:51'),
(12, '3', '/flash-sale', 'Flash Sale', '2022-11-01 15:35:49', '2022-11-01 15:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `footer_social_links`
--

CREATE TABLE `footer_social_links` (
  `id` bigint UNSIGNED NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_social_links`
--

INSERT INTO `footer_social_links` (`id`, `link`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'https://www.facebook.com/', 'fab fa-facebook-f', '2022-09-20 08:18:12', '2022-09-20 08:18:12'),
(4, 'https://www.instagram.com/', 'fab fa-instagram', '2025-10-04 03:32:05', '2025-10-04 03:32:05'),
(5, 'https://www.tiktalk.com/', 'fab fa-tiktok', '2025-10-04 03:33:44', '2025-10-04 03:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `google_analytics`
--

CREATE TABLE `google_analytics` (
  `id` bigint UNSIGNED NOT NULL,
  `analytic_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `google_analytics`
--

INSERT INTO `google_analytics` (`id`, `analytic_id`, `status`, `created_at`, `updated_at`) VALUES
(1, '5248-fd-5fds', 1, NULL, '2022-06-11 11:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `google_recaptchas`
--

CREATE TABLE `google_recaptchas` (
  `id` bigint UNSIGNED NOT NULL,
  `site_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `google_recaptchas`
--

INSERT INTO `google_recaptchas` (`id`, `site_key`, `secret_key`, `status`, `created_at`, `updated_at`) VALUES
(1, '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe', 0, NULL, '2025-08-24 03:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `home_page_one_visibilities`
--

CREATE TABLE `home_page_one_visibilities` (
  `id` bigint UNSIGNED NOT NULL,
  `default_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `qty` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_page_one_visibilities`
--

INSERT INTO `home_page_one_visibilities` (`id`, `default_name`, `section_name`, `status`, `qty`, `created_at`, `updated_at`) VALUES
(1, 'Slider', 'Slider', 1, 6, NULL, '2022-06-14 06:30:37'),
(2, 'Our Service', 'Our Service', 1, 6, NULL, '2022-06-14 06:29:47'),
(4, 'Popular Category', 'Popular Categories', 1, 24, NULL, '2022-06-14 06:29:47'),
(5, 'Brand', 'Our Brand', 1, 12, NULL, '2022-06-14 06:29:47'),
(6, 'Top Rated Product', 'Top Rated Product', 1, 16, NULL, '2022-06-14 06:29:47'),
(7, 'Best Seller', 'Best Sellers', 1, 8, NULL, '2022-06-14 06:29:47'),
(8, 'Featured Product', 'Featured Products', 1, 15, NULL, '2022-06-14 06:29:47'),
(9, 'New Arrival', 'New Arrival', 1, 19, NULL, '2022-06-14 06:29:47'),
(10, 'Best Product', 'Best Product', 1, 16, NULL, '2022-06-14 06:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `instamojo_payments`
--

CREATE TABLE `instamojo_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `api_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_rate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `account_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sandbox',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instamojo_payments`
--

INSERT INTO `instamojo_payments` (`id`, `api_key`, `auth_token`, `currency_rate`, `account_mode`, `status`, `created_at`, `updated_at`) VALUES
(1, 'test_REPLACE_ME', 'test_REPLACE_ME', '74.66', 'Sandbox', 1, NULL, '2025-09-16 23:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `stock_in` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `product_id`, `stock_in`, `created_at`, `updated_at`) VALUES
(2, 38, '100', '2023-03-07 03:20:53', '2023-03-07 03:20:53'),
(3, 38, '50', '2023-03-07 03:21:02', '2023-03-07 03:21:02'),
(4, 38, '80', '2023-03-07 03:21:08', '2023-03-07 03:21:08'),
(5, 13, '200', '2023-03-07 03:21:20', '2023-03-07 03:21:20'),
(7, 37, '5', '2023-03-07 03:29:50', '2023-03-07 03:29:50'),
(8, 37, '10', '2023-03-07 03:30:01', '2023-03-07 03:30:01'),
(9, 37, '15', '2023-03-07 03:30:08', '2023-03-07 03:30:08'),
(10, 36, '20', '2023-03-07 03:30:19', '2023-03-07 03:30:19'),
(11, 36, '150', '2023-03-07 03:30:25', '2023-03-07 03:30:25'),
(12, 7, '100', '2025-08-23 10:02:47', '2025-08-23 10:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `maintainance_texts`
--

CREATE TABLE `maintainance_texts` (
  `id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintainance_texts`
--

INSERT INTO `maintainance_texts` (`id`, `status`, `image`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 'uploads/website-images/maintainance-mode-2022-12-12-03-03-31-1726.png', 'We are upgrading our site. We will come back soon. Please stay with us. Thank you.', NULL, '2025-08-23 08:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `mega_menu_categories`
--

CREATE TABLE `mega_menu_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `serial` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mega_menu_categories`
--

INSERT INTO `mega_menu_categories` (`id`, `category_id`, `status`, `serial`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2022-09-20 07:06:01', '2022-09-20 07:06:01'),
(2, 2, 1, 2, '2022-09-20 07:07:39', '2022-09-20 07:07:39'),
(3, 10, 1, 3, '2022-09-20 07:11:07', '2022-09-20 07:11:07');

-- --------------------------------------------------------

--
-- Table structure for table `mega_menu_sub_categories`
--

CREATE TABLE `mega_menu_sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `mega_menu_category_id` int NOT NULL,
  `sub_category_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `serial` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mega_menu_sub_categories`
--

INSERT INTO `mega_menu_sub_categories` (`id`, `mega_menu_category_id`, `sub_category_id`, `status`, `serial`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2022-09-20 07:06:23', '2022-09-20 07:06:23'),
(2, 1, 2, 1, 2, '2022-09-20 07:06:34', '2022-09-20 07:06:34'),
(3, 1, 3, 1, 3, '2022-09-20 07:06:47', '2022-09-20 07:06:47'),
(4, 2, 4, 1, 1, '2022-09-20 07:10:04', '2022-09-20 07:10:04'),
(5, 2, 5, 1, 2, '2022-09-20 07:10:18', '2022-09-20 07:10:18'),
(6, 2, 6, 1, 3, '2022-09-20 07:10:29', '2022-09-20 07:10:29'),
(7, 3, 7, 1, 1, '2022-09-20 07:12:13', '2022-09-20 07:12:13'),
(8, 3, 8, 1, 2, '2022-09-20 07:12:22', '2022-09-20 07:12:22'),
(9, 3, 9, 1, 3, '2022-09-20 07:12:29', '2022-09-20 07:12:29');

-- --------------------------------------------------------

--
-- Table structure for table `menu_visibilities`
--

CREATE TABLE `menu_visibilities` (
  `id` bigint UNSIGNED NOT NULL,
  `menu_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_visibilities`
--

INSERT INTO `menu_visibilities` (`id`, `menu_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Home', 1, NULL, '2022-01-23 20:05:32'),
(2, 'Shop', 1, NULL, '2022-01-23 20:05:31'),
(3, 'Mega Menu', 1, NULL, '2022-01-16 20:51:23'),
(4, 'Sellers', 1, NULL, '2022-01-16 20:52:31'),
(5, 'Blog', 1, NULL, '2022-01-16 20:52:32'),
(6, 'Campaign', 1, NULL, '2022-01-16 20:52:33'),
(7, 'Pages', 1, NULL, '2022-01-16 20:52:34'),
(8, 'About us', 1, NULL, '2022-01-16 20:57:27'),
(9, 'Contact Us', 1, NULL, '2022-01-16 20:57:28'),
(10, 'Checkout', 1, NULL, '2022-01-16 20:57:29'),
(11, 'Brand', 1, NULL, '2022-01-16 20:57:25'),
(12, 'FAQ', 1, NULL, '2022-01-16 20:57:26'),
(13, 'Privacy Policy', 1, NULL, '2022-01-16 20:57:23'),
(14, 'Terms and Conditions', 1, NULL, '2022-01-16 20:57:22'),
(15, 'Track Order', 1, NULL, '2022-01-16 20:52:29'),
(16, 'Flash Deal', 1, NULL, '2022-01-16 20:52:28'),
(17, 'My Account', 1, NULL, '2022-01-16 20:04:54'),
(18, 'Login/Register', 1, NULL, '2022-01-16 20:04:47'),
(19, 'Shopping Cart', 1, NULL, '2022-01-16 20:09:28'),
(20, 'Compare', 1, NULL, '2022-01-16 20:37:54'),
(21, 'Wishlist', 1, NULL, '2022-01-16 20:37:55'),
(22, 'Topbar Phone', 1, NULL, '2022-01-16 20:02:07'),
(23, 'Menu Phone', 1, NULL, '2022-01-16 20:08:00'),
(24, 'Categories', 1, NULL, '2022-01-16 23:52:39'),
(25, 'Search', 1, NULL, '2022-01-16 20:37:56');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` int NOT NULL,
  `seller_id` int NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_read_msg` int NOT NULL DEFAULT '0',
  `seller_read_msg` int NOT NULL,
  `send_customer` int NOT NULL DEFAULT '0',
  `send_seller` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_11_30_035230_create_admins_table', 2),
(6, '2021_11_30_065435_create_email_configurations_table', 3),
(7, '2021_11_30_065508_create_email_templates_table', 3),
(8, '2021_12_01_035206_create_categories_table', 4),
(9, '2021_12_01_035220_create_sub_categories_table', 4),
(10, '2021_12_01_035231_create_child_categories_table', 4),
(11, '2021_12_01_035735_create_brands_table', 4),
(12, '2021_12_02_055907_create_product_taxes_table', 5),
(13, '2021_12_02_083742_create_return_policies_table', 6),
(14, '2021_12_02_084030_create_product_specification_keys_table', 6),
(15, '2021_12_03_093645_create_products_table', 7),
(16, '2021_12_03_101949_create_product_galleries_table', 7),
(17, '2021_12_04_053018_create_product_specifications_table', 8),
(18, '2021_12_06_045447_create_services_table', 9),
(19, '2021_12_06_054423_create_about_us_table', 10),
(20, '2021_12_06_055028_create_custom_pages_table', 10),
(21, '2021_12_07_030532_create_terms_and_conditions_table', 11),
(22, '2021_12_07_035810_create_blog_categories_table', 12),
(23, '2021_12_07_035822_create_blogs_table', 12),
(24, '2021_12_07_040749_create_popular_posts_table', 12),
(25, '2021_12_07_061613_create_blog_comments_table', 13),
(26, '2021_12_07_081832_create_product_variants_table', 14),
(27, '2021_12_07_081858_create_product_variant_items_table', 14),
(28, '2021_12_08_125540_create_campaigns_table', 15),
(29, '2021_12_08_130025_create_campaign_products_table', 15),
(30, '2021_12_09_095158_create_contact_messages_table', 16),
(31, '2021_12_09_095220_create_subscribers_table', 16),
(32, '2021_12_09_124226_create_settings_table', 17),
(33, '2021_12_11_022207_create_cookie_consents_table', 18),
(34, '2021_12_11_025358_create_google_recaptchas_table', 19),
(35, '2021_12_11_025449_create_facebook_comments_table', 19),
(36, '2021_12_11_025556_create_tawk_chats_table', 19),
(37, '2021_12_11_025618_create_google_analytics_table', 19),
(38, '2021_12_11_025712_create_custom_paginations_table', 19),
(39, '2021_12_11_083503_create_faqs_table', 20),
(40, '2021_12_11_094707_create_currencies_table', 21),
(41, '2021_12_13_085612_create_product_reviews_table', 22),
(42, '2021_12_13_090609_create_product_review_galleries_table', 22),
(43, '2021_12_13_101056_create_error_pages_table', 23),
(44, '2021_12_13_102725_create_maintainance_texts_table', 24),
(45, '2021_12_13_110144_create_subscribe_modals_table', 25),
(46, '2021_12_13_111140_create_announcement_modals_table', 26),
(47, '2021_12_13_132626_create_countries_table', 27),
(48, '2021_12_13_132909_create_country_states_table', 27),
(49, '2021_12_13_132935_create_cities_table', 27),
(50, '2021_12_14_032937_create_social_login_information_table', 28),
(51, '2021_12_14_042928_create_facebook_pixels_table', 29),
(52, '2021_12_14_054908_create_paypal_payments_table', 30),
(53, '2021_12_14_054922_create_stripe_payments_table', 30),
(54, '2021_12_14_054939_create_razorpay_payments_table', 30),
(55, '2021_12_14_055252_create_bank_payments_table', 30),
(56, '2021_12_14_084759_create_vendors_table', 31),
(57, '2021_12_14_090013_create_vendor_social_links_table', 31),
(58, '2021_12_15_095059_create_wholesells_table', 32),
(59, '2021_12_16_071213_create_seller_mail_logs_table', 33),
(60, '2021_12_21_093939_create_mega_menu_categories_table', 34),
(61, '2021_12_21_093958_create_mega_menu_sub_categories_table', 34),
(62, '2021_12_22_034106_create_banner_images_table', 35),
(63, '2021_12_22_044839_create_sliders_table', 36),
(64, '2021_12_22_081311_create_popular_categories_table', 37),
(65, '2021_12_23_021844_create_three_column_categories_table', 38),
(66, '2021_12_23_033230_create_shipping_methods_table', 39),
(67, '2021_12_23_065722_create_paystack_and_mollies_table', 40),
(68, '2021_12_23_085225_create_withdraw_methods_table', 41),
(71, '2021_12_25_172918_create_seller_withdraws_table', 42),
(74, '2021_12_25_200413_create_product_reports_table', 43),
(75, '2021_12_25_200707_create_product_report_images_table', 44),
(79, '2021_12_26_052326_create_billing_addresses_table', 45),
(80, '2021_12_26_053952_create_shipping_addresses_table', 45),
(81, '2021_12_26_054841_create_orders_table', 45),
(82, '2021_12_26_164912_create_order_addresses_table', 45),
(83, '2021_12_26_165705_create_order_products_table', 45),
(84, '2021_12_26_170803_create_order_product_variants_table', 45),
(87, '2021_12_28_163200_create_coupons_table', 46),
(88, '2021_12_28_192057_create_contact_pages_table', 47),
(89, '2021_12_28_200846_create_breadcrumb_images_table', 48),
(90, '2021_12_30_032959_create_flutterwaves_table', 49),
(91, '2021_12_30_034716_create_footers_table', 50),
(92, '2021_12_30_035201_create_footer_links_table', 50),
(93, '2021_12_30_035247_create_footer_social_links_table', 50),
(95, '2021_12_30_061157_create_home_page_one_visibilities_table', 51),
(96, '2022_01_11_103950_create_wishlists_table', 52),
(97, '2022_01_12_070110_create_shop_pages_table', 53),
(99, '2022_01_12_080218_create_seo_settings_table', 54),
(100, '2022_01_17_012111_create_menu_visibilities_table', 55),
(101, '2022_01_17_122016_create_instamojo_payments_table', 56),
(102, '2022_01_29_055523_create_messages_table', 57),
(103, '2022_01_29_122621_create_pusher_credentails_table', 58),
(104, '2022_04_27_083907_create_shopping_carts_table', 59),
(105, '2022_04_27_084235_create_shopping_cart_variants_table', 59),
(106, '2022_06_11_095338_create_testimonials_table', 60),
(107, '2022_06_13_053409_create_flash_sales_table', 61),
(108, '2022_06_13_053644_create_flash_sale_products_table', 62),
(109, '2022_06_14_102451_create_shippings_table', 63),
(112, '2022_06_19_073137_create_addresses_table', 64),
(113, '2022_06_19_093939_create_compare_products_table', 65),
(114, '2022_07_27_101723_create_featured_categories_table', 66),
(115, '2023_03_07_030217_create_inventories_table', 67),
(116, '2023_03_07_033625_create_twilio_sms_table', 68),
(117, '2023_03_07_033758_create_sms_templates_table', 68),
(118, '2023_05_30_063954_add_app_version_to_settings', 69),
(119, '2023_10_09_122324_add_tax_to_settings', 70),
(120, '2025_08_24_065616_add_text_position_to_sliders_table', 71),
(121, '2025_08_24_072647_add_background_color_to_flash_sales_table', 72),
(122, '2025_08_24_103937_add_footer_color_to_footers_table', 73),
(123, '2025_08_24_105902_add_statistics_color_to_settings_table', 74),
(124, '2025_08_24_110153_add_statistics_font_color_to_settings_table', 75),
(125, '2025_09_09_092736_add_zip_code_to_addresses_table', 76);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` double NOT NULL DEFAULT '0',
  `product_qty` int NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` int NOT NULL DEFAULT '0',
  `payment_approval_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transection_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` double NOT NULL DEFAULT '0',
  `coupon_coast` double NOT NULL DEFAULT '0',
  `order_status` int NOT NULL DEFAULT '0',
  `order_approval_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_delivered_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_completed_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_declined_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cash_on_delivery` int NOT NULL DEFAULT '0',
  `additional_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `user_id`, `total_amount`, `product_qty`, `payment_method`, `payment_status`, `payment_approval_date`, `transection_id`, `shipping_method`, `shipping_cost`, `coupon_coast`, `order_status`, `order_approval_date`, `order_delivered_date`, `order_completed_date`, `order_declined_date`, `cash_on_delivery`, `additional_info`, `created_at`, `updated_at`) VALUES
(68, '876326941', 7, 988.13, 6, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-15 16:42:47', '2022-11-15 16:42:47'),
(69, '480112268', 7, 1366.24, 11, 'Mollie', 1, NULL, 'tr_8iN5HVoYSK', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(70, '1329506784', 31, 333.89, 3, 'Bank Payment', 0, NULL, 'dddd', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-15 20:16:47', '2022-11-15 20:16:47'),
(71, '120660403', 31, 130, 1, 'Bank Payment', 0, NULL, 'ghgfghgfh', 'Home Delivery', 120, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-15 21:30:41', '2022-11-15 21:30:41'),
(72, '1490840797', 7, 888.8, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Home Delivery Free Shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-16 15:59:54', '2022-11-16 15:59:54'),
(73, '625434571', 31, 137.89, 7, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Home Delivery Free Shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-17 19:30:53', '2022-11-17 19:30:53'),
(74, '1393035052', 31, 133, 2, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-17 19:48:35', '2022-11-17 19:48:35'),
(75, '1077950798', 31, 11.33, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-17 20:09:10', '2022-11-17 20:09:10'),
(76, '390285855', 31, 923, 4, 'Bank Payment', 0, NULL, 'dsdsd', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-17 21:37:32', '2022-11-17 21:37:32'),
(77, '415922846', 32, 450, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-17 22:52:28', '2022-11-17 22:52:28'),
(78, '1017803777', 7, 413.06, 6, 'Bank Payment', 0, NULL, 'ddd', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-17 23:04:49', '2022-11-17 23:04:49'),
(79, '665674974', 7, 650, 2, 'Bank Payment', 0, NULL, 'ddddd', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-17 23:16:19', '2022-11-17 23:16:19'),
(80, '1632805578', 7, 530, 3, 'Bank Payment', 0, NULL, 'afasfda', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-17 23:47:34', '2022-11-17 23:47:34'),
(81, '184956296', 7, 773, 10, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(82, '24839802', 7, 530, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-18 02:57:47', '2022-11-18 02:57:47'),
(83, '1020972631', 7, 453.4, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-18 04:30:28', '2022-11-18 04:30:28'),
(84, '137336772', 7, 560, 8, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-19 17:21:22', '2022-11-19 17:21:22'),
(85, '1431220726', 7, 470, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-20 12:02:44', '2022-11-20 12:02:44'),
(86, '1598736679', 7, 282.73, 8, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Home Delivery Free Shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-20 23:40:19', '2022-11-20 23:40:19'),
(87, '270232199', 7, 1666, 12, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-21 20:34:56', '2022-11-21 20:34:56'),
(88, '81318744', 7, 4166.93, 12, 'Cash on Delivery', 1, '2022-11-22', 'cash_on_delivery', 'Home Delivery Free Shipping', 0, 0, 2, NULL, '2022-11-22', NULL, NULL, 1, NULL, '2022-11-22 12:32:08', '2022-11-22 23:50:51'),
(89, '1362369801', 7, 25, 5, 'Cash on Delivery', 1, '2022-11-22', 'cash_on_delivery', 'Home Delivery Free Shipping', 0, 25, 3, NULL, NULL, '2022-11-22', NULL, 1, NULL, '2022-11-22 16:49:41', '2022-11-22 23:49:53'),
(90, '1554916729', 7, 758.4, 4, 'Stripe', 1, '2022-11-22', 'txn_3M6vjwF56Pb8BOOX06psH5Id', 'Shipping Rule Based on qty(6-10)', 190, 0, 3, NULL, NULL, '2022-11-22', NULL, 0, NULL, '2022-11-22 23:38:16', '2022-11-22 23:46:07'),
(91, '116409371', 7, 550.58, 4, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Home Delivery Free Shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-23 16:43:19', '2022-11-23 16:43:19'),
(92, '1667036255', 7, 1077.01, 8, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Home Delivery', 120, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-24 23:18:50', '2022-11-24 23:18:50'),
(93, '366600446', 7, 346.4, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-26 18:44:38', '2022-11-26 18:44:38'),
(94, '1452995012', 7, 655.94, 10, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-27 02:01:10', '2022-11-27 02:01:10'),
(95, '1230276093', 7, 3021.76, 9, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 2831.756, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(96, '686395984', 7, 456, 4, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-28 21:49:52', '2022-11-28 21:49:52'),
(97, '1404982009', 7, 30, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-28 23:43:55', '2022-11-28 23:43:55'),
(98, '1624912686', 7, 223.99, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-11-30 15:15:41', '2022-11-30 15:15:41'),
(99, '1032419047', 7, 200, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-12-01 23:12:56', '2022-12-01 23:12:56'),
(100, '676563119', 38, 266.4, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Free shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-12-02 16:05:44', '2022-12-02 16:05:44'),
(101, '1023682722', 7, 584.78, 10, 'Bank Payment', 0, NULL, 'Ibn Sina', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-12-04 16:50:09', '2022-12-04 16:50:09'),
(102, '1177937717', 7, 2491.8, 13, 'Bank Payment', 0, NULL, 'Ibn Sina', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-12-08 19:35:16', '2022-12-08 19:35:16'),
(103, '1319308652', 7, 1350, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Home Delivery Free Shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-12-08 20:15:19', '2022-12-08 20:15:19'),
(104, '901635737', 7, 436, 2, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-12-11 21:03:13', '2022-12-11 21:03:13'),
(105, '716084011', 7, 1555, 4, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Shipping Rule Based on qty(6-10)', 190, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-12-12 15:41:05', '2022-12-12 15:41:05'),
(106, '444339660', 7, 160, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Express', 150, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2022-12-17 21:04:20', '2022-12-17 21:04:20'),
(107, '1533666571', 42, 570, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Quantity(5-10)', 120, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2023-01-16 15:41:29', '2023-01-16 15:41:29'),
(108, '636955803', 7, 920, 2, 'Instamojo', 1, NULL, 'MOJO3116O05A85306128', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-01-16 20:51:00', '2023-01-16 20:51:00'),
(109, '532759133', 7, 30, 1, 'Flutterwave', 1, NULL, '4093530', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-01-16 20:57:09', '2023-01-16 20:57:09'),
(110, '1153586621', 7, 852.8, 6, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Quantity(5-10)', 120, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2023-01-17 01:00:43', '2023-01-17 01:00:43'),
(111, '1569688916', 7, 418.99, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2023-01-17 15:59:11', '2023-01-17 15:59:11'),
(112, '1124913845', 7, 990, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2023-01-21 23:09:27', '2023-01-21 23:09:27'),
(113, '513798334', 7, 28, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2023-01-23 16:39:20', '2023-01-23 16:39:20'),
(114, '68644526', 7, 220.14, 3, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2023-02-11 22:00:26', '2023-02-11 22:00:26'),
(115, '1425917836', 7, 479, 6, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2023-03-23 07:06:46', '2023-03-23 07:06:46'),
(116, '759001713', 7, 90, 1, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Fixed Shipping', 20, 0, 2, NULL, '2025-08-23', '2025-08-23', NULL, 1, NULL, '2023-03-23 08:14:01', '2025-08-23 10:04:37'),
(117, '56138636', 7, 768.4, 6, 'Cash on Delivery', 0, NULL, 'cash_on_delivery', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2023-03-26 05:31:49', '2023-03-26 05:31:49'),
(118, '54507384', 7, 40, 1, 'Paystack', 1, NULL, '2663582623', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-03-26 09:02:55', '2023-03-26 09:02:55'),
(119, '52582734', 7, 29.59, 1, 'Paystack', 1, NULL, '2663608352', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-03-26 09:11:08', '2023-03-26 09:11:08'),
(120, '1400201833', 7, 80, 3, 'Paystack', 1, NULL, '2834759768', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 04:43:38', '2023-05-27 04:43:38'),
(121, '832780362', 7, 1475.68, 13, 'Instamojo', 1, NULL, 'MOJO3527A05A83091024', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(122, '1121524748', 7, 49.59, 2, 'Paystack', 1, NULL, '2835237120', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 07:57:33', '2023-05-27 07:57:33'),
(123, '647373056', 7, 960, 2, 'Flutterwave', 1, NULL, '4353182', 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 08:02:47', '2023-05-27 08:02:47'),
(124, '17723170', 7, 80, 1, 'Instamojo', 1, NULL, 'MOJO3527Q05A83091097', 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 08:07:19', '2023-05-27 08:07:19'),
(125, '827022380', 7, 367.73, 3, 'Mollie', 1, NULL, 'tr_T8cee4vgpd', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 08:23:47', '2023-05-27 08:23:47'),
(126, '1611100345', 7, 80, 3, 'Paypal', 1, NULL, 'PAYID-MRY343A96B21570TF590403X', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 08:26:29', '2023-05-27 08:26:29'),
(127, '430096949', 7, 39.18, 2, 'Paystack', 1, NULL, '2835313971', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 08:32:11', '2023-05-27 08:32:11'),
(128, '1435303000', 7, 356.4, 2, 'Instamojo', 1, NULL, NULL, 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 08:35:26', '2023-05-27 08:35:26'),
(129, '1201045373', 7, 248.62, 3, 'Instamojo', 1, NULL, NULL, 'Quantity(5-10)', 120, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 08:39:33', '2023-05-27 08:39:33'),
(130, '890025427', 7, 40, 1, 'Mollie', 1, NULL, 'tr_pxzYMPvcY6', 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2023-05-27 08:42:39', '2025-08-23 09:25:48'),
(131, '169567029', 5, 263, 2, 'Cash', 1, NULL, 'Cash', 'Quantity(5-10)', 120, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, '2025-08-23 09:42:50', '2025-08-23 09:42:50'),
(132, '1297077466', 56, 254.99, 2, 'Cash on Delivery', 0, NULL, 'Cash on Delivery', 'Quantity(5-10)', 120, 0, 3, NULL, NULL, NULL, NULL, 3, NULL, '2025-08-24 05:27:20', '2025-08-24 05:27:20'),
(133, '699151192', 57, 254.99, 2, 'Cash on Delivery', 0, NULL, 'Cash on Delivery', 'Quantity(5-10)', 120, 0, 0, NULL, NULL, NULL, NULL, 3, NULL, '2025-08-24 05:27:57', '2025-08-24 05:31:44'),
(134, '1728186131', 67, 8, 1, 'cash_on_delivery', 1, NULL, NULL, 'Fixed Shipping', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 00:54:26', '2025-09-09 00:54:26'),
(135, '863537962', 68, 8, 1, 'bank_payment', 0, NULL, NULL, 'Regular', 0, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-09 01:00:53', '2025-09-09 01:00:53'),
(136, '1319258570', 69, 8, 1, 'sslcommerz', 0, NULL, NULL, 'Quantity(5-10)', 0, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-09 01:01:12', '2025-09-09 01:01:12'),
(137, '1537356197', 69, 8, 1, 'cash_on_delivery', 1, NULL, NULL, 'Quantity(5-10)', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 01:01:20', '2025-09-09 01:01:20'),
(138, '1175817235', 70, 8, 1, 'cash_on_delivery', 1, NULL, NULL, 'Regular', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 01:03:38', '2025-09-09 01:03:38'),
(139, '574200105', 71, 11.11, 1, 'cash_on_delivery', 1, NULL, NULL, 'Regular', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 01:15:50', '2025-09-09 01:15:50'),
(140, '1695117772', 72, 11.33, 1, 'cash_on_delivery', 1, NULL, NULL, 'Regular', 0, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 01:25:17', '2025-09-09 01:25:17'),
(141, '345149356', 73, 70, 1, 'cash_on_delivery', 1, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 01:48:47', '2025-09-09 01:48:47'),
(142, '1722053193', 59, 160, 1, 'cash_on_delivery', 1, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 01:50:36', '2025-09-09 01:50:36'),
(143, '979543134', 74, 183, 1, 'cash_on_delivery', 1, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 03:03:26', '2025-09-09 03:03:26'),
(144, '138922717', 59, 110, 1, 'cash_on_delivery', 1, '2025-09-09', NULL, 'Regular', 60, 0, 3, NULL, NULL, '2025-09-09', NULL, 1, NULL, '2025-09-09 03:09:19', '2025-09-09 03:40:26'),
(145, '1053699027', 59, 71.33, 1, 'Stripe', 1, '2025-09-17 09:25:39', 'ch_3S8HcOF56Pb8BOOX1Rt1Wc5u', 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-09 03:41:31', '2025-09-17 03:25:39'),
(146, '1637167664', 75, 243, 1, 'cash_on_delivery', 0, NULL, NULL, 'Quantity(5-10)', 120, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-15 23:48:33', '2025-09-15 23:48:33'),
(147, '436081339', 77, 70, 1, 'cash_on_delivery', 0, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-23 05:41:24', '2025-09-23 05:41:24'),
(148, '292346782', 78, 143, 1, 'stripe', 0, NULL, NULL, 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-23 05:42:50', '2025-09-23 05:42:50'),
(149, '1726196267', 78, 143, 1, 'paypal', 0, NULL, NULL, 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-23 05:44:32', '2025-09-23 05:44:32'),
(150, '1422996940', 78, 143, 1, 'stripe', 0, NULL, NULL, 'Fixed Shipping', 20, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-23 05:44:46', '2025-09-23 05:44:46'),
(151, '1374156111', 59, 160, 1, 'Stripe', 1, '2025-09-23 11:45:46', 'ch_3SAUfFF56Pb8BOOX1XliP5Ih', 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-23 05:45:17', '2025-09-23 05:45:46'),
(152, '983922307', 59, 130, 1, 'stripe', 0, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-23 05:46:28', '2025-09-23 05:46:28'),
(153, '369465095', 59, 110, 1, 'Stripe', 1, '2025-09-23 11:50:05', 'ch_3SAUjRF56Pb8BOOX0M0cYEt9', 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-23 05:49:28', '2025-09-23 05:50:05'),
(154, '1472082028', 59, 130, 1, 'stripe', 0, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-23 05:50:37', '2025-09-23 05:50:37'),
(155, '1739550986', 79, 110, 1, 'cash_on_delivery', 0, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-09-27 00:13:06', '2025-09-27 00:13:06'),
(156, '1646708302', 59, 68, 1, 'Stripe', 1, '2025-09-29 07:13:35', 'ch_3SCbH9F56Pb8BOOX0pTVKIeA', 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 0, NULL, '2025-09-29 01:13:12', '2025-09-29 01:13:35'),
(157, '185713601', 79, 196, 3, 'cash_on_delivery', 0, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-05 01:23:20', '2025-10-05 01:23:20'),
(158, '691829602', 79, 526, 3, 'cash_on_delivery', 0, NULL, NULL, 'Regular', 60, 0, 0, NULL, NULL, NULL, NULL, 1, NULL, '2025-10-05 01:25:25', '2025-10-05 01:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_addresses`
--

CREATE TABLE `order_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` int NOT NULL,
  `billing_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_country` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_state` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_addresses`
--

INSERT INTO `order_addresses` (`id`, `order_id`, `billing_name`, `billing_email`, `billing_phone`, `billing_address`, `billing_country`, `billing_state`, `billing_city`, `billing_address_type`, `shipping_name`, `shipping_email`, `shipping_phone`, `shipping_address`, `shipping_country`, `shipping_state`, `shipping_city`, `shipping_address_type`, `created_at`, `updated_at`) VALUES
(68, 68, 'John  Doe', 'user@gmail.com', '123-984-4587', 'Florida city 8475, House 8765', 'United Stat', 'Florida', 'Florida City', '1', 'John  Doe', 'user@gmail.com', '123-984-4587', 'Florida city 8475, House 8765', 'United State', 'Florida', 'Florida City', '1', '2022-11-15 16:42:47', '2022-11-15 16:42:47'),
(69, 69, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(70, 70, 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United Stat', 'California', 'Los Angeles', 'home', 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United State', 'California', 'Los Angeles', 'home', '2022-11-15 20:16:47', '2022-11-15 20:16:47'),
(71, 71, 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United Stat', 'California', 'Los Angeles', 'home', 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United State', 'California', 'Los Angeles', 'home', '2022-11-15 21:30:41', '2022-11-15 21:30:41'),
(72, 72, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Mohammad Ali', 'mscsapan@gmail.com', '01612042021', 'House', 'United Kindom', 'England', 'Liverpool', 'home', '2022-11-16 15:59:54', '2022-11-16 15:59:54'),
(73, 73, 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United Stat', 'California', 'Los Angeles', 'home', 'Mohammad', 'mali@gmail.com', '01624188877', 'London', 'United Kindom', 'England', 'London', 'home', '2022-11-17 19:30:53', '2022-11-17 19:30:53'),
(74, 74, 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United Stat', 'California', 'Los Angeles', 'home', 'Mohammad', 'mali@gmail.com', '01624188877', 'London', 'United Kindom', 'England', 'London', 'home', '2022-11-17 19:48:35', '2022-11-17 19:48:35'),
(75, 75, 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United Stat', 'California', 'Los Angeles', 'home', 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United State', 'California', 'Los Angeles', 'home', '2022-11-17 20:09:10', '2022-11-17 20:09:10'),
(76, 76, 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United Stat', 'California', 'Los Angeles', 'home', 'Mohammad Ali', 'msalisapan@gmail.com', '01624188866', 'Los Angeles, USA', 'United State', 'California', 'Los Angeles', 'home', '2022-11-17 21:37:32', '2022-11-17 21:37:32'),
(77, 77, 'abdur', 'abdur.rohman2003@gmail.com', '01682825213', 'v', 'India', 'Punjab', 'Chandigarh', 'home', 'abdur', 'abdur.rohman2003@gmail.com', '01682825213', 'v', 'India', 'Punjab', 'Chandigarh', 'home', '2022-11-17 22:52:28', '2022-11-17 22:52:28'),
(78, 78, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-17 23:04:49', '2022-11-17 23:04:49'),
(79, 79, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-17 23:16:19', '2022-11-17 23:16:19'),
(80, 80, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-17 23:47:34', '2022-11-17 23:47:34'),
(81, 81, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(82, 82, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-18 02:57:47', '2022-11-18 02:57:47'),
(83, 83, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-18 04:30:28', '2022-11-18 04:30:28'),
(84, 84, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-19 17:21:22', '2022-11-19 17:21:22'),
(85, 85, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-20 12:02:44', '2022-11-20 12:02:44'),
(86, 86, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-20 23:40:19', '2022-11-20 23:40:19'),
(87, 87, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-21 20:34:56', '2022-11-21 20:34:56'),
(88, 88, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-22 12:32:08', '2022-11-22 12:32:08'),
(89, 89, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-22 16:49:41', '2022-11-22 16:49:41'),
(90, 90, 'John Doe', 'user@gmail.com', '1234567893', 'california', 'United Stat', 'California', 'Los Angeles', 'home', 'John Doe', 'user@gmail.com', '1234567893', 'california', 'United State', 'California', 'Los Angeles', 'home', '2022-11-22 23:38:16', '2022-11-22 23:38:16'),
(91, 91, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-23 16:43:19', '2022-11-23 16:43:19'),
(92, 92, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'John Doe', 'user@gmail.com', '1234567893', 'california', 'United State', 'California', 'Los Angeles', 'home', '2022-11-24 23:18:50', '2022-11-24 23:18:50'),
(93, 93, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-26 18:44:38', '2022-11-26 18:44:38'),
(94, 94, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-27 02:01:10', '2022-11-27 02:01:10'),
(95, 95, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(96, 96, 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', 'Sed et error eligend Minim aut molestiae', 'Et labore exercitati', 'Deserunt beatae ulla', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', '1', '2022-11-28 21:49:52', '2022-11-28 21:49:52'),
(97, 97, 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', '2022-11-28 23:43:55', '2022-11-28 23:43:55'),
(98, 98, 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', '2022-11-30 15:15:41', '2022-11-30 15:15:41'),
(99, 99, 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', '2022-12-01 23:12:56', '2022-12-01 23:12:56'),
(100, 100, 'abdur', 'abdur.rohman2003@gmail.com', '01682825123', 'asdgg', 'India', 'Punjab', 'Chandigarh', 'home', 'abdur', 'abdur.rohman2003@gmail.com', '01682825123', 'asdgg', 'India', 'Punjab', 'Chandigarh', 'home', '2022-12-02 16:05:44', '2022-12-02 16:05:44'),
(101, 101, 'Ibn Sina', 'ibn.sina@outlook.com', '1234567878', 'Liverpool City', 'United Kind', 'England', 'Liverpool', 'home', 'Ibn Sina', 'ibn.sina@outlook.com', '1234567878', 'Liverpool City', 'United Kindom', 'England', 'Liverpool', 'home', '2022-12-04 16:50:09', '2022-12-04 16:50:09'),
(102, 102, 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', '2022-12-08 19:35:16', '2022-12-08 19:35:16'),
(103, 103, 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', '2022-12-08 20:15:19', '2022-12-08 20:15:19'),
(104, 104, 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', '2022-12-11 21:03:13', '2022-12-11 21:03:13'),
(105, 105, 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Abraham', 'abraham@yahoo.com', '01624188877', 'Jos City', 'United State', 'California', 'San Jose', 'home', '2022-12-12 15:41:05', '2022-12-12 15:41:05'),
(106, 106, 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul Islam Suvo', 'rafiqulislamsuvobd@gmail.com', '01777777777', 'Aliquip accusantium', 'India', 'Gujarat', 'Gandhinagar', 'home', '2022-12-17 21:04:20', '2022-12-17 21:04:20'),
(107, 107, 'Your', 'myemail@mail.com', '123654789', 'Dhaka', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Your', 'myemail@mail.com', '123654789', 'Dhaka', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-01-16 15:41:29', '2023-01-16 15:41:29'),
(108, 108, 'Rafiqul Islam', 'rafiqulislamsuvobd@gmail.com', '01777777778', 'Dhaka', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul Islam', 'rafiqulislamsuvobd@gmail.com', '01777777778', 'Dhaka', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-01-16 20:51:00', '2023-01-16 20:51:00'),
(109, 109, 'Rafiqul Islam', 'rafiqulislamsuvobd@gmail.com', '01777777778', 'Dhaka', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-01-16 20:57:09', '2023-01-16 20:57:09'),
(110, 110, 'Rafiqul', 'rafiqulislamsuvobd@gmail.com', '01777777778', 'Dhaka, Mirpur', 'India', 'Gujarat', 'Gandhinagar', 'home', 'Rafiqul', 'rafiqulislamsuvobd@gmail.com', '01777777778', 'Dhaka, Mirpur', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-01-17 01:00:43', '2023-01-17 01:00:43'),
(111, 111, 'Mamjn', 'maiqulislamsuvobd@gmail.com', '01777777775', 'Dhaka, Mirpur11', 'United Kind', 'England', 'London', 'home', 'Mamjn', 'maiqulislamsuvobd@gmail.com', '01777777775', 'Dhaka, Mirpur11', 'United Kindom', 'England', 'London', 'home', '2023-01-17 15:59:11', '2023-01-17 15:59:11'),
(112, 112, 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-01-21 23:09:27', '2023-01-21 23:09:27'),
(113, 113, 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-01-23 16:39:20', '2023-01-23 16:39:20'),
(114, 114, 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-02-11 22:00:26', '2023-02-11 22:00:26'),
(115, 115, 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-03-23 07:06:46', '2023-03-23 07:06:46'),
(116, 116, 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-03-23 08:14:01', '2023-03-23 08:14:01'),
(117, 117, 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-03-26 05:31:49', '2023-03-26 05:31:49'),
(118, 118, 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-03-26 09:02:55', '2023-03-26 09:02:55'),
(119, 119, 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', 'shibo', 'maiqulislamsuvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-03-26 09:11:08', '2023-03-26 09:11:08'),
(120, 120, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-05-27 04:43:38', '2023-05-27 04:43:38'),
(121, 121, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Hassan Khan', 'youabdme@gamail.com', '0197854544', 'Mirpur 11, Dhaka', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(122, 122, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-05-27 07:57:33', '2023-05-27 07:57:33'),
(123, 123, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-05-27 08:02:47', '2023-05-27 08:02:47'),
(124, 124, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-05-27 08:07:19', '2023-05-27 08:07:19'),
(125, 125, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-05-27 08:23:48', '2023-05-27 08:23:48'),
(126, 126, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-05-27 08:26:29', '2023-05-27 08:26:29'),
(127, 127, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-05-27 08:32:11', '2023-05-27 08:32:11'),
(128, 128, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-05-27 08:35:27', '2023-05-27 08:35:27'),
(129, 129, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'Jhon Abraham', 'jhon.ab@gamil.com', '3214567777', 'Kolkata City', 'India', 'Gujarat', 'Gandhinagar', 'home', '2023-05-27 08:39:33', '2023-05-27 08:39:33'),
(130, 130, 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kind', 'England', 'London', 'home', 'shibo', 'suvobd@gmail.com', '01777777776', 'Dhaka, Mirpur12', 'United Kindom', 'England', 'London', 'home', '2023-05-27 08:42:39', '2023-05-27 08:42:39'),
(131, 131, 'a b', 'marohman74@gmail.com', '123355454544', 'ewewee', 'United Stat', 'California', 'Los Angeles', '1', 'a b', 'marohman74@gmail.com', '123355454544', 'ewewee', 'United State', 'California', 'Los Angeles', '1', '2025-08-23 09:42:50', '2025-08-23 09:42:50'),
(132, 133, 'qusacog@mailinator.com', 'lenij@mailinator.com', '+1 (256) 165-4855', 'sadfsaf', 'Australia', 'Wales', 'Weston', 'Office', 'qusacog@mailinator.com', 'lenij@mailinator.com', '+1 (256) 165-4855', 'sadfsaf', 'Australia', 'Wales', 'Weston', 'Office', '2025-08-24 05:27:57', '2025-08-24 05:27:57'),
(133, 134, 'Gretchen Juarez', 'soqatet@mailinator.com', '+1 (299) 171-1671', 'Tempore vero aut vo', 'United Kind', 'England', 'Edinburgh', 'billing', 'Gretchen Juarez', 'soqatet@mailinator.com', '+1 (299) 171-1671', 'Tempore vero aut vo', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 00:54:26', '2025-09-09 00:54:26'),
(134, 135, 'Gisela Huber', 'lezyryge@mailinator.com', '+1 (875) 318-5512', 'Suscipit eius facili', 'United Kind', 'England', 'Edinburgh', 'billing', 'Gisela Huber', 'lezyryge@mailinator.com', '+1 (875) 318-5512', 'Suscipit eius facili', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 01:00:53', '2025-09-09 01:00:53'),
(135, 136, 'Zenia Rodriquez', 'totu@mailinator.com', '+1 (719) 499-2953', 'Proident sit labore', 'United Kind', 'England', 'Edinburgh', 'billing', 'Zenia Rodriquez', 'totu@mailinator.com', '+1 (719) 499-2953', 'Proident sit labore', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 01:01:12', '2025-09-09 01:01:12'),
(136, 137, 'Zenia Rodriquez', 'totu@mailinator.com', '+1 (719) 499-2953', 'Proident sit labore', 'United Kind', 'England', 'Edinburgh', 'billing', 'Zenia Rodriquez', 'totu@mailinator.com', '+1 (719) 499-2953', 'Proident sit labore', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 01:01:20', '2025-09-09 01:01:20'),
(137, 138, 'Ifeoma Mccarty', 'kazyza@mailinator.com', '+1 (852) 784-4411', 'Sapiente incididunt', 'United Kind', 'England', 'Edinburgh', 'billing', 'Ifeoma Mccarty', 'kazyza@mailinator.com', '+1 (852) 784-4411', 'Sapiente incididunt', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 01:03:38', '2025-09-09 01:03:38'),
(138, 139, 'Mason Myers', 'zizu@mailinator.com', '+1 (464) 759-6295', 'Sunt sit hic aute di', 'United Kind', 'England', 'Edinburgh', 'billing', 'Mason Myers', 'zizu@mailinator.com', '+1 (464) 759-6295', 'Sunt sit hic aute di', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 01:15:50', '2025-09-09 01:15:50'),
(139, 140, 'Eliana Tyson', 'xubet@mailinator.com', '+1 (868) 996-7193', 'Officia quis ut obca', 'United Kind', 'England', 'Edinburgh', 'billing', 'Eliana Tyson', 'xubet@mailinator.com', '+1 (868) 996-7193', 'Officia quis ut obca', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 01:25:17', '2025-09-09 01:25:17'),
(140, 141, 'Nigel Bird', 'nysimirele@mailinator.com', '+1 (563) 552-9843', 'Consectetur alias e', 'United Kind', 'England', 'Edinburgh', 'billing', 'Nigel Bird', 'nysimirele@mailinator.com', '+1 (563) 552-9843', 'Consectetur alias e', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 01:48:47', '2025-09-09 01:48:47'),
(141, 142, 'mkhsojib Mejia', 'mkhsojib@gmail.com', '0464654564', 'gfsdfgdgdf', 'United Kind', 'England', 'Edinburgh', 'billing', 'mkhsojib Mejia', 'mkhsojib@gmail.com', '0464654564', 'gfsdfgdgdf', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 01:50:36', '2025-09-09 01:50:36'),
(142, 143, 'Ann Wiggins', 'tygiva@mailinator.com', '+1 (812) 572-5544', 'In reprehenderit cu', 'United Kind', 'England', 'Edinburgh', 'billing', 'Ann Wiggins', 'tygiva@mailinator.com', '+1 (812) 572-5544', 'In reprehenderit cu', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 03:03:26', '2025-09-09 03:03:26'),
(143, 144, 'mkhsojib Mejia', 'mkhsojib@gmail.com', '+1 (346) 432-8913', 'gfsdfgdgdf', 'United Kind', 'England', 'Edinburgh', 'billing', 'mkhsojib Mejia', 'mkhsojib@gmail.com', '+1 (346) 432-8913', 'gfsdfgdgdf', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-09 03:09:19', '2025-09-09 03:09:19'),
(144, 145, 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 'Dicta esse ut eiusmo', 'United Kind', 'Wales', 'Cardiff', 'billing', 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 'Dicta esse ut eiusmo', 'United Kindom', 'Wales', 'Cardiff', 'shipping', '2025-09-09 03:41:31', '2025-09-09 03:41:31'),
(145, 146, 'Garth Dodson', 'cubydyge@mailinator.com', '+1 (688) 509-9489', 'Quia accusamus conse', 'United Kind', 'England', 'Edinburgh', 'billing', 'Garth Dodson', 'cubydyge@mailinator.com', '+1 (688) 509-9489', 'Quia accusamus conse', 'United Kindom', 'England', 'Edinburgh', 'shipping', '2025-09-15 23:48:33', '2025-09-15 23:48:33'),
(146, 147, 'Brent Hopper', 'gokymajy@mailinator.com', '+1 (747) 681-4959', 'Dolor fuga Voluptat', 'United Kind', 'England', 'London', 'billing', 'Brent Hopper', 'gokymajy@mailinator.com', '+1 (747) 681-4959', 'Dolor fuga Voluptat', 'United Kindom', 'England', 'London', 'shipping', '2025-09-23 05:41:24', '2025-09-23 05:41:24'),
(147, 148, 'Nigel Carlson', 'vakuxabivy@mailinator.com', '+1 (673) 879-9231', 'Ex possimus ullamco', 'United Kind', 'England', 'London', 'billing', 'Carl Fry', 'lany@mailinator.com', '+1 (442) 569-2947', 'Quae vel asperiores', 'United Kindom', 'England', 'London', 'shipping', '2025-09-23 05:42:50', '2025-09-23 05:42:50'),
(148, 149, 'Nigel Carlson', 'vakuxabivy@mailinator.com', '+1 (673) 879-9231', 'Ex possimus ullamco', 'United Kind', 'England', 'London', 'billing', 'Carl Fry', 'lany@mailinator.com', '+1 (442) 569-2947', 'Quae vel asperiores', 'United Kindom', 'England', 'London', 'shipping', '2025-09-23 05:44:32', '2025-09-23 05:44:32'),
(149, 150, 'Nigel Carlson', 'vakuxabivy@mailinator.com', '+1 (673) 879-9231', 'Ex possimus ullamco', 'United Kind', 'England', 'London', 'billing', 'Carl Fry', 'lany@mailinator.com', '+1 (442) 569-2947', 'Quae vel asperiores', 'United Kindom', 'England', 'London', 'shipping', '2025-09-23 05:44:46', '2025-09-23 05:44:46'),
(150, 151, 'mkhsojib Carlson', 'mkhsojib@gmail.com', '0464654564', 'gfsdfgdgdf', 'United Kind', 'England', 'London', 'billing', 'mkhsojib Carlson', 'mkhsojib@gmail.com', '0464654564', 'gfsdfgdgdf', 'United Kindom', 'England', 'London', 'shipping', '2025-09-23 05:45:17', '2025-09-23 05:45:17'),
(151, 152, 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 'Dicta esse ut eiusmo', 'United Kind', 'Wales', 'Cardiff', 'billing', 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 'Dicta esse ut eiusmo', 'United Kindom', 'Wales', 'Cardiff', 'shipping', '2025-09-23 05:46:28', '2025-09-23 05:46:28'),
(152, 153, 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 'Dicta esse ut eiusmo', 'United Kind', 'Wales', 'Cardiff', 'billing', 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 'Dicta esse ut eiusmo', 'United Kindom', 'Wales', 'Cardiff', 'shipping', '2025-09-23 05:49:28', '2025-09-23 05:49:28'),
(153, 154, 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 'Dicta esse ut eiusmo', 'United Kind', 'Wales', 'Cardiff', 'billing', 'Gloria Mejia', 'gozo@mailinator.com', '+1 (314) 366-2787', 'Dicta esse ut eiusmo', 'United Kindom', 'Wales', 'Cardiff', 'shipping', '2025-09-23 05:50:37', '2025-09-23 05:50:37'),
(154, 155, 'Aurelia Sandoval', 'kefahoqule@mailinator.com', '+1 (646) 334-6224', 'Et consectetur labo', 'United Kind', 'England', 'London', 'billing', 'Aurelia Sandoval', 'kefahoqule@mailinator.com', '+1 (646) 334-6224', 'Et consectetur labo', 'United Kindom', 'England', 'London', 'shipping', '2025-09-27 00:13:06', '2025-09-27 00:13:06'),
(155, 156, 'mkhsojib Mejia', 'mkhsojib@gmail.com', '0464654564', 'gfsdfgdgdf', 'United Kind', 'Wales', 'Cardiff', 'billing', 'mkhsojib Mejia', 'mkhsojib@gmail.com', '0464654564', 'gfsdfgdgdf', 'United Kindom', 'Wales', 'Cardiff', 'shipping', '2025-09-29 01:13:12', '2025-09-29 01:13:12'),
(156, 157, 'Aurelia Sandoval', 'kefahoqule@mailinator.com', '+1 (646) 334-6224', 'Et consectetur labo', 'United Kind', 'England', 'London', 'billing', 'Aurelia Sandoval', 'kefahoqule@mailinator.com', '+1 (646) 334-6224', 'Et consectetur labo', 'United Kindom', 'England', 'London', 'shipping', '2025-10-05 01:23:20', '2025-10-05 01:23:20'),
(157, 158, 'Aurelia Sandoval', 'kefahoqule@mailinator.com', '+1 (646) 334-6224', 'Et consectetur labo', 'United Kind', 'England', 'London', 'billing', 'Aurelia Sandoval', 'kefahoqule@mailinator.com', '+1 (646) 334-6224', 'Et consectetur labo', 'United Kindom', 'England', 'London', 'shipping', '2025-10-05 01:25:25', '2025-10-05 01:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `seller_id` int NOT NULL DEFAULT '0',
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_price` double NOT NULL DEFAULT '0',
  `qty` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_products`
--

INSERT INTO `order_products` (`id`, `order_id`, `product_id`, `seller_id`, `product_name`, `unit_price`, `qty`, `created_at`, `updated_at`) VALUES
(132, 68, 1, 0, 'Eggplant fruit Leucinodes orbonalis', 98.4, 2, '2022-11-15 16:42:47', '2022-11-15 16:42:47'),
(133, 68, 7, 0, 'Watermelon Big Basket', 11.33, 1, '2022-11-15 16:42:47', '2022-11-15 16:42:47'),
(134, 68, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2022-11-15 16:42:47', '2022-11-15 16:42:47'),
(135, 68, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-15 16:42:47', '2022-11-15 16:42:47'),
(136, 68, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2022-11-15 16:42:47', '2022-11-15 16:42:47'),
(137, 69, 7, 0, 'Watermelon Big Basket', 11.33, 1, '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(138, 69, 7, 0, 'Watermelon Big Basket', 11.33, 5, '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(139, 69, 7, 0, 'Watermelon Big Basket', 11.33, 1, '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(140, 69, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(141, 69, 7, 0, 'Watermelon Big Basket', 11.33, 1, '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(142, 69, 1, 0, 'Eggplant fruit Leucinodes orbonalis', 98.4, 1, '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(143, 69, 1, 0, 'Eggplant fruit Leucinodes orbonalis', 98.4, 1, '2022-11-15 19:01:35', '2022-11-15 19:01:35'),
(144, 70, 8, 0, 'broiler chicken Egg', 8.888, 1, '2022-11-15 20:16:47', '2022-11-15 20:16:47'),
(145, 70, 25, 1, 'Boomchickapop Sweet & Salty Kettle Corn', 120, 1, '2022-11-15 20:16:47', '2022-11-15 20:16:47'),
(146, 70, 34, 4, 'Perdue Simply Smart Organics Gluten Free', 15, 1, '2022-11-15 20:16:47', '2022-11-15 20:16:47'),
(147, 71, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-15 21:30:41', '2022-11-15 21:30:41'),
(148, 72, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-16 15:59:54', '2022-11-16 15:59:54'),
(149, 73, 33, 4, 'Signature Wood-Fired Mushroom Meals', 33, 3, '2022-11-17 19:30:53', '2022-11-17 19:30:53'),
(150, 73, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 3, '2022-11-17 19:30:53', '2022-11-17 19:30:53'),
(151, 73, 8, 0, 'broiler chicken Egg', 8.888, 1, '2022-11-17 19:30:53', '2022-11-17 19:30:53'),
(152, 74, 35, 5, 'Nestle Original Coffee-Mate Coffee Creamer', 123, 1, '2022-11-17 19:48:35', '2022-11-17 19:48:35'),
(153, 74, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-17 19:48:35', '2022-11-17 19:48:35'),
(154, 75, 7, 0, 'Watermelon Big Basket', 11.33, 1, '2022-11-17 20:09:10', '2022-11-17 20:09:10'),
(155, 76, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-17 21:37:32', '2022-11-17 21:37:32'),
(156, 76, 34, 4, 'Perdue Simply Smart Organics Gluten Free', 15, 1, '2022-11-17 21:37:32', '2022-11-17 21:37:32'),
(157, 76, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-17 21:37:32', '2022-11-17 21:37:32'),
(158, 76, 32, 3, 'All Natural Italian-Style Chicken Meatballsd', 8, 1, '2022-11-17 21:37:32', '2022-11-17 21:37:32'),
(159, 77, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-17 22:52:28', '2022-11-17 22:52:28'),
(160, 78, 7, 0, 'Watermelon Big Basket', 11.33, 2, '2022-11-17 23:04:49', '2022-11-17 23:04:49'),
(161, 78, 31, 3, 'All Natural Italian-Style Chicken Meatballs', 100, 1, '2022-11-17 23:04:49', '2022-11-17 23:04:49'),
(162, 78, 29, 2, 'Sony playstone pro', 120, 1, '2022-11-17 23:04:49', '2022-11-17 23:04:49'),
(163, 78, 2, 0, 'Sahale Asias Sesame Edamame Snacks', 70.4, 1, '2022-11-17 23:04:49', '2022-11-17 23:04:49'),
(164, 78, 31, 3, 'All Natural Italian-Style Chicken Meatballs', 100, 1, '2022-11-17 23:04:49', '2022-11-17 23:04:49'),
(165, 79, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-17 23:16:19', '2022-11-17 23:16:19'),
(166, 79, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-17 23:16:19', '2022-11-17 23:16:19'),
(167, 80, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-17 23:47:34', '2022-11-17 23:47:34'),
(168, 80, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2022-11-17 23:47:34', '2022-11-17 23:47:34'),
(169, 80, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-17 23:47:34', '2022-11-17 23:47:34'),
(170, 81, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(171, 81, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(172, 81, 35, 5, 'Nestle Original Coffee-Mate Coffee Creamer', 123, 1, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(173, 81, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(174, 81, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(175, 81, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(176, 81, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 3, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(177, 81, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-18 00:00:35', '2022-11-18 00:00:35'),
(178, 82, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2022-11-18 02:57:47', '2022-11-18 02:57:47'),
(179, 82, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-18 02:57:47', '2022-11-18 02:57:47'),
(180, 82, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-18 02:57:47', '2022-11-18 02:57:47'),
(181, 83, 10, 0, 'Seeds of Change Brown & Red Rice', 123, 1, '2022-11-18 04:30:28', '2022-11-18 04:30:28'),
(182, 83, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2022-11-18 04:30:28', '2022-11-18 04:30:28'),
(183, 83, 2, 0, 'Sahale Asias Sesame Edamame Snacks', 70.4, 1, '2022-11-18 04:30:28', '2022-11-18 04:30:28'),
(184, 84, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 8, '2022-11-19 17:21:22', '2022-11-19 17:21:22'),
(185, 85, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-20 12:02:44', '2022-11-20 12:02:44'),
(186, 85, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 2, '2022-11-20 12:02:44', '2022-11-20 12:02:44'),
(187, 86, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 5, '2022-11-20 23:40:19', '2022-11-20 23:40:19'),
(188, 86, 10, 0, 'Seeds of Change Brown & Red Rice', 123, 1, '2022-11-20 23:40:19', '2022-11-20 23:40:19'),
(189, 86, 1, 0, 'Eggplant fruit Leucinodes orbonalis', 98.4, 1, '2022-11-20 23:40:19', '2022-11-20 23:40:19'),
(190, 86, 7, 0, 'Watermelon Big Basket', 11.33, 1, '2022-11-20 23:40:19', '2022-11-20 23:40:19'),
(191, 87, 35, 5, 'Nestle Original Coffee-Mate Coffee Creamer', 123, 12, '2022-11-21 20:34:56', '2022-11-21 20:34:56'),
(192, 88, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-22 12:32:08', '2022-11-22 12:32:08'),
(193, 88, 4, 0, 'Organic California Spirulina Superfood', 888.8, 4, '2022-11-22 12:32:08', '2022-11-22 12:32:08'),
(194, 88, 7, 0, 'Watermelon Big Basket', 11.33, 1, '2022-11-22 12:32:08', '2022-11-22 12:32:08'),
(195, 88, 1, 0, 'Eggplant fruit Leucinodes orbonalis', 98.4, 3, '2022-11-22 12:32:08', '2022-11-22 12:32:08'),
(196, 88, 1, 0, 'Eggplant fruit Leucinodes orbonalis', 98.4, 2, '2022-11-22 12:32:08', '2022-11-22 12:32:08'),
(197, 88, 1, 0, 'Eggplant fruit Leucinodes orbonalis', 98.4, 1, '2022-11-22 12:32:08', '2022-11-22 12:32:08'),
(198, 89, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 5, '2022-11-22 16:49:41', '2022-11-22 16:49:41'),
(199, 90, 8, 0, 'broiler chicken Egg', 8.888, 1, '2022-11-22 23:38:16', '2022-11-22 23:38:16'),
(200, 90, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2022-11-22 23:38:16', '2022-11-22 23:38:16'),
(201, 90, 1, 0, 'Eggplant fruit Leucinodes orbonalis', 98.4, 1, '2022-11-22 23:38:16', '2022-11-22 23:38:16'),
(202, 90, 6, 0, 'Beef Sirloin Steak', 11.11, 1, '2022-11-22 23:38:16', '2022-11-22 23:38:16'),
(203, 91, 11, 0, 'Crispy Beef Patties', 266.4, 2, '2022-11-23 16:43:19', '2022-11-23 16:43:19'),
(204, 91, 8, 0, 'broiler chicken Egg', 8.888, 2, '2022-11-23 16:43:19', '2022-11-23 16:43:19'),
(205, 92, 7, 0, 'Watermelon Big Basket', 11.33, 2, '2022-11-24 23:18:50', '2022-11-24 23:18:50'),
(206, 92, 8, 0, 'broiler chicken Egg', 8.888, 4, '2022-11-24 23:18:50', '2022-11-24 23:18:50'),
(207, 92, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-24 23:18:50', '2022-11-24 23:18:50'),
(208, 92, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-24 23:18:50', '2022-11-24 23:18:50'),
(209, 93, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2022-11-26 18:44:38', '2022-11-26 18:44:38'),
(210, 93, 11, 0, 'Crispy Beef Patties', 266.4, 1, '2022-11-26 18:44:38', '2022-11-26 18:44:38'),
(211, 93, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-26 18:44:38', '2022-11-26 18:44:38'),
(212, 94, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 4, '2022-11-27 02:01:10', '2022-11-27 02:01:10'),
(213, 94, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 2, '2022-11-27 02:01:10', '2022-11-27 02:01:10'),
(214, 94, 35, 5, 'Nestle Original Coffee-Mate Coffee Creamer', 123, 1, '2022-11-27 02:01:10', '2022-11-27 02:01:10'),
(215, 94, 3, 0, 'Veggie Pops Broccoli Chedda Supersnacks', 54.312, 3, '2022-11-27 02:01:10', '2022-11-27 02:01:10'),
(216, 95, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(217, 95, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(218, 95, 3, 0, 'Veggie Pops Broccoli Chedda Supersnacks', 54.312, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(219, 95, 11, 0, 'Crispy Beef Patties', 266.4, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(220, 95, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(221, 95, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(222, 95, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(223, 95, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(224, 95, 4, 0, 'Organic California Spirulina Superfood', 888.8, 1, '2022-11-27 20:24:58', '2022-11-27 20:24:58'),
(225, 96, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-28 21:49:52', '2022-11-28 21:49:52'),
(226, 96, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-11-28 21:49:52', '2022-11-28 21:49:52'),
(227, 96, 35, 5, 'Nestle Original Coffee-Mate Coffee Creamer', 123, 2, '2022-11-28 21:49:52', '2022-11-28 21:49:52'),
(228, 97, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 3, '2022-11-28 23:43:55', '2022-11-28 23:43:55'),
(229, 98, 7, 0, 'Watermelon Big Basket', 11.33, 3, '2022-11-30 15:15:41', '2022-11-30 15:15:41'),
(230, 99, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-12-01 23:12:56', '2022-12-01 23:12:56'),
(231, 100, 11, 0, 'Crispy Beef Patties', 266.4, 1, '2022-12-02 16:05:44', '2022-12-02 16:05:44'),
(232, 101, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 5, '2022-12-04 16:50:09', '2022-12-04 16:50:09'),
(233, 101, 32, 3, 'All Natural Italian-Style Chicken Meatballsd', 8, 2, '2022-12-04 16:50:09', '2022-12-04 16:50:09'),
(234, 101, 9, 0, 'Fresh Vegetable Tomato', 9.592, 3, '2022-12-04 16:50:09', '2022-12-04 16:50:09'),
(235, 102, 11, 0, 'Crispy Beef Patties', 266.4, 2, '2022-12-08 19:35:16', '2022-12-08 19:35:16'),
(236, 102, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 2, '2022-12-08 19:35:16', '2022-12-08 19:35:16'),
(237, 102, 35, 5, 'Nestle Original Coffee-Mate Coffee Creamer', 123, 3, '2022-12-08 19:35:16', '2022-12-08 19:35:16'),
(238, 102, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 2, '2022-12-08 19:35:16', '2022-12-08 19:35:16'),
(239, 102, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-12-08 19:35:16', '2022-12-08 19:35:16'),
(240, 102, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 3, '2022-12-08 19:35:16', '2022-12-08 19:35:16'),
(241, 103, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 3, '2022-12-08 20:15:19', '2022-12-08 20:15:19'),
(242, 104, 35, 5, 'Nestle Original Coffee-Mate Coffee Creamer', 123, 2, '2022-12-11 21:03:13', '2022-12-11 21:03:13'),
(243, 105, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 3, '2022-12-12 15:41:05', '2022-12-12 15:41:05'),
(244, 105, 34, 4, 'Perdue Simply Smart Organics Gluten Free', 15, 1, '2022-12-12 15:41:05', '2022-12-12 15:41:05'),
(245, 106, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2022-12-17 21:04:20', '2022-12-17 21:04:20'),
(246, 107, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 1, '2023-01-16 15:41:29', '2023-01-16 15:41:29'),
(247, 108, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 2, '2023-01-16 20:51:00', '2023-01-16 20:51:00'),
(248, 109, 38, 0, 'KOSPET TANK T1 MIL-STD Waterproof Smartwatch', 10, 1, '2023-01-16 20:57:09', '2023-01-16 20:57:09'),
(249, 110, 26, 1, 'Naturally Flavored Cinnamon Coffee', 50, 3, '2023-01-17 01:00:43', '2023-01-17 01:00:43'),
(250, 110, 26, 1, 'Naturally Flavored Cinnamon Coffee', 50, 1, '2023-01-17 01:00:43', '2023-01-17 01:00:43'),
(251, 110, 11, 0, 'Crispy Beef Patties', 266.4, 1, '2023-01-17 01:00:43', '2023-01-17 01:00:43'),
(252, 110, 11, 0, 'Crispy Beef Patties', 266.4, 1, '2023-01-17 01:00:43', '2023-01-17 01:00:43'),
(253, 111, 11, 0, 'Crispy Beef Patties', 266.4, 1, '2023-01-17 15:59:11', '2023-01-17 15:59:11'),
(254, 111, 10, 0, 'Seeds of Change Brown & Red Rice', 123, 1, '2023-01-17 15:59:11', '2023-01-17 15:59:11'),
(255, 111, 9, 0, 'Fresh Vegetable Tomato', 9.592, 1, '2023-01-17 15:59:11', '2023-01-17 15:59:11'),
(256, 112, 37, 6, 'Pepperidge Farm Farmhouse Bread', 450, 2, '2023-01-21 23:09:27', '2023-01-21 23:09:27'),
(257, 112, 36, 6, 'All Natural Italian-Style Chicken Meatballs', 70, 1, '2023-01-21 23:09:27', '2023-01-21 23:09:27'),
(258, 113, 32, 3, 'All Natural Italian-Style Chicken Meatballsd', 8, 1, '2023-01-23 16:39:20', '2023-01-23 16:39:20'),
(259, 114, 2, 0, 'Sahale Asias Sesame Edamame Snacks', 70.4, 1, '2023-02-11 22:00:26', '2023-02-11 22:00:26'),
(260, 114, 3, 0, 'Veggie Pops Broccoli Chedda Supersnacks', 54.312, 1, '2023-02-11 22:00:26', '2023-02-11 22:00:26'),
(261, 114, 5, 0, 'Brokoli Fresh Vegetable', 35.43, 1, '2023-02-11 22:00:26', '2023-02-11 22:00:26'),
(262, 115, 38, 0, 'Classic Oxford Shirt', 20, 2, '2023-03-23 07:06:46', '2023-03-23 07:06:46'),
(263, 115, 38, 0, 'Classic Oxford Shirt', 20, 1, '2023-03-23 07:06:46', '2023-03-23 07:06:46'),
(264, 115, 13, 0, 'Slim-Fit Chinos', 133, 3, '2023-03-23 07:06:46', '2023-03-23 07:06:46'),
(265, 116, 36, 6, 'Trendy Bucket Hat', 70, 1, '2023-03-23 08:14:01', '2023-03-23 08:14:01'),
(266, 117, 32, 3, 'Casual Denim Jacket', 8, 4, '2023-03-26 05:31:49', '2023-03-26 05:31:49'),
(267, 117, 37, 6, 'Feminine Wrap Blouse', 450, 1, '2023-03-26 05:31:49', '2023-03-26 05:31:49'),
(268, 117, 11, 0, 'Leather Dress Shoes', 266.4, 1, '2023-03-26 05:31:49', '2023-03-26 05:31:49'),
(269, 118, 38, 0, 'Classic Oxford Shirt', 20, 1, '2023-03-26 09:02:55', '2023-03-26 09:02:55'),
(270, 119, 9, 0, 'Leather Messenger Bag', 9.592, 1, '2023-03-26 09:11:08', '2023-03-26 09:11:08'),
(271, 120, 38, 0, 'Classic Oxford Shirt', 20, 3, '2023-05-27 04:43:38', '2023-05-27 04:43:38'),
(272, 121, 11, 0, 'Leather Dress Shoes', 266.4, 2, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(273, 121, 38, 0, 'Classic Oxford Shirt', 20, 3, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(274, 121, 37, 6, 'Feminine Wrap Blouse', 450, 1, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(275, 121, 36, 6, 'Trendy Bucket Hat', 70, 1, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(276, 121, 32, 3, 'Casual Denim Jacket', 8, 1, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(277, 121, 9, 0, 'Leather Messenger Bag', 9.592, 1, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(278, 121, 2, 0, 'Slim-Fit Blazer', 70.4, 1, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(279, 121, 8, 0, 'Hooded Sweatshirt', 8.888, 1, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(280, 121, 35, 5, 'Romantic Lace Dress', 123, 1, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(281, 121, 10, 0, 'Wool Peacoat', 123, 1, '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(282, 122, 38, 0, 'Classic Oxford Shirt', 20, 1, '2023-05-27 07:57:33', '2023-05-27 07:57:33'),
(283, 122, 9, 0, 'Leather Messenger Bag', 9.592, 1, '2023-05-27 07:57:33', '2023-05-27 07:57:33'),
(284, 123, 37, 6, 'Feminine Wrap Blouse', 450, 2, '2023-05-27 08:02:47', '2023-05-27 08:02:47'),
(285, 124, 38, 0, 'Classic Oxford Shirt', 20, 1, '2023-05-27 08:07:19', '2023-05-27 08:07:19'),
(286, 125, 36, 6, 'Trendy Bucket Hat', 70, 1, '2023-05-27 08:23:47', '2023-05-27 08:23:47'),
(287, 125, 7, 0, 'Performance Polo Shirt', 11.33, 1, '2023-05-27 08:23:48', '2023-05-27 08:23:48'),
(288, 125, 11, 0, 'Leather Dress Shoes', 266.4, 1, '2023-05-27 08:23:48', '2023-05-27 08:23:48'),
(289, 126, 38, 0, 'Classic Oxford Shirt', 20, 3, '2023-05-27 08:26:29', '2023-05-27 08:26:29'),
(290, 127, 9, 0, 'Leather Messenger Bag', 9.592, 2, '2023-05-27 08:32:11', '2023-05-27 08:32:11'),
(291, 128, 11, 0, 'Leather Dress Shoes', 266.4, 1, '2023-05-27 08:35:27', '2023-05-27 08:35:27'),
(292, 128, 36, 6, 'Trendy Bucket Hat', 70, 1, '2023-05-27 08:35:27', '2023-05-27 08:35:27'),
(293, 129, 38, 0, 'Classic Oxford Shirt', 20, 1, '2023-05-27 08:39:33', '2023-05-27 08:39:33'),
(294, 129, 3, 0, 'Wool Scarf', 54.312, 2, '2023-05-27 08:39:33', '2023-05-27 08:39:33'),
(295, 130, 38, 0, 'Classic Oxford Shirt', 20, 1, '2023-05-27 08:42:39', '2025-08-23 09:25:48'),
(296, 131, 38, 0, 'Classic Oxford Shirt', 10, 1, '2025-08-23 09:42:50', '2025-08-23 09:42:50'),
(297, 131, 13, 0, 'Slim-Fit Chinos', 133, 1, '2025-08-23 09:42:50', '2025-08-23 09:42:50'),
(298, 132, 10, 0, 'Wool Peacoat', 123, 1, '2025-08-24 05:27:20', '2025-08-24 05:27:20'),
(299, 132, 9, 0, 'Leather Messenger Bag', 9.592, 1, '2025-08-24 05:27:20', '2025-08-24 05:27:20'),
(300, 133, 10, 0, 'Wool Peacoat', 123, 1, '2025-08-24 05:27:57', '2025-08-24 05:27:57'),
(301, 133, 9, 0, 'Leather Messenger Bag', 9.592, 1, '2025-08-24 05:27:57', '2025-08-24 05:27:57'),
(302, 134, 32, 3, 'Casual Denim Jacket', 8, 1, '2025-09-09 00:54:26', '2025-09-09 00:54:26'),
(303, 135, 32, 3, 'Casual Denim Jacket', 8, 1, '2025-09-09 01:00:53', '2025-09-09 01:00:53'),
(304, 136, 32, 3, 'Casual Denim Jacket', 8, 1, '2025-09-09 01:01:12', '2025-09-09 01:01:12'),
(305, 137, 32, 3, 'Casual Denim Jacket', 8, 1, '2025-09-09 01:01:20', '2025-09-09 01:01:20'),
(306, 138, 32, 3, 'Casual Denim Jacket', 8, 1, '2025-09-09 01:03:38', '2025-09-09 01:03:38'),
(307, 139, 8, 0, 'Hooded Sweatshirt', 8.888, 1, '2025-09-09 01:15:50', '2025-09-09 01:15:50'),
(308, 140, 7, 0, 'Performance Polo Shirt', 11.33, 1, '2025-09-09 01:25:17', '2025-09-09 01:25:17'),
(309, 141, 38, 0, 'Classic Oxford Shirt', 20, 1, '2025-09-09 01:48:47', '2025-09-09 01:48:47'),
(310, 142, 31, 3, 'Chic Leather Boots', 100, 1, '2025-09-09 01:50:36', '2025-09-09 01:50:36'),
(311, 143, 1, 0, 'Suede Desert Boots', 98.4, 1, '2025-09-09 03:03:26', '2025-09-09 03:03:26'),
(312, 144, 26, 1, 'Stylish Statement Earrings', 50, 1, '2025-09-09 03:09:19', '2025-09-09 03:09:19'),
(313, 145, 7, 0, 'Performance Polo Shirt', 11.33, 1, '2025-09-09 03:41:31', '2025-09-09 03:41:31'),
(314, 146, 35, 5, 'Romantic Lace Dress', 123, 1, '2025-09-15 23:48:33', '2025-09-15 23:48:33'),
(315, 147, 38, 0, 'Classic Oxford Shirt', 20, 1, '2025-09-23 05:41:24', '2025-09-23 05:41:24'),
(316, 148, 35, 5, 'Romantic Lace Dress', 123, 1, '2025-09-23 05:42:50', '2025-09-23 05:42:50'),
(317, 149, 35, 5, 'Romantic Lace Dress', 123, 1, '2025-09-23 05:44:32', '2025-09-23 05:44:32'),
(318, 150, 35, 5, 'Romantic Lace Dress', 123, 1, '2025-09-23 05:44:46', '2025-09-23 05:44:46'),
(319, 151, 31, 3, 'Chic Leather Boots', 100, 1, '2025-09-23 05:45:17', '2025-09-23 05:45:17'),
(320, 152, 36, 6, 'Trendy Bucket Hat', 70, 1, '2025-09-23 05:46:28', '2025-09-23 05:46:28'),
(321, 153, 26, 1, 'Stylish Statement Earrings', 50, 1, '2025-09-23 05:49:28', '2025-09-23 05:49:28'),
(322, 154, 36, 6, 'Trendy Bucket Hat', 70, 1, '2025-09-23 05:50:37', '2025-09-23 05:50:37'),
(323, 155, 26, 1, 'Stylish Statement Earrings', 50, 1, '2025-09-27 00:13:06', '2025-09-27 00:13:06'),
(324, 156, 32, 3, 'Casual Denim Jacket', 8, 1, '2025-09-29 01:13:12', '2025-09-29 01:13:12'),
(325, 157, 32, 3, 'Casual Denim Jacket', 8, 2, '2025-10-05 01:23:20', '2025-10-05 01:23:20'),
(326, 157, 25, 1, 'Comfortable Yoga Pants', 120, 1, '2025-10-05 01:23:20', '2025-10-05 01:23:20'),
(327, 158, 37, 6, 'Feminine Wrap Blouse', 450, 1, '2025-10-05 01:25:25', '2025-10-05 01:25:25'),
(328, 158, 32, 3, 'Casual Denim Jacket', 8, 2, '2025-10-05 01:25:25', '2025-10-05 01:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_product_variants`
--

CREATE TABLE `order_product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `order_product_id` int NOT NULL,
  `product_id` int NOT NULL,
  `variant_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_product_variants`
--

INSERT INTO `order_product_variants` (`id`, `order_product_id`, `product_id`, `variant_name`, `variant_value`, `created_at`, `updated_at`) VALUES
(2, 262, 38, 'Size', 'xl', '2023-03-23 07:06:46', '2023-03-23 07:06:46'),
(3, 263, 38, 'Size', 'xl', '2023-03-23 07:06:46', '2023-03-23 07:06:46'),
(4, 269, 38, 'Size', 'xl', '2023-03-26 09:02:55', '2023-03-26 09:02:55'),
(5, 271, 38, 'Size', 'xl', '2023-05-27 04:43:38', '2023-05-27 04:43:38'),
(6, 273, 38, 'Size', 'xl', '2023-05-27 06:03:47', '2023-05-27 06:03:47'),
(7, 282, 38, 'Size', 'xl', '2023-05-27 07:57:33', '2023-05-27 07:57:33'),
(8, 285, 38, 'Size', 'xl', '2023-05-27 08:07:19', '2023-05-27 08:07:19'),
(9, 289, 38, 'Size', 'xl', '2023-05-27 08:26:29', '2023-05-27 08:26:29'),
(10, 293, 38, 'Size', 'xl', '2023-05-27 08:39:33', '2023-05-27 08:39:33'),
(11, 295, 38, 'Size', 'xl', '2023-05-27 08:42:39', '2023-05-27 08:42:39'),
(12, 309, 38, 'Size', 'XL', '2025-09-09 01:48:47', '2025-09-09 01:48:47'),
(13, 309, 38, 'Color', 'Blue', '2025-09-09 01:48:47', '2025-09-09 01:48:47'),
(14, 315, 38, 'Size', 'XL', '2025-09-23 05:41:24', '2025-09-23 05:41:24'),
(15, 315, 38, 'Color', 'Red', '2025-09-23 05:41:24', '2025-09-23 05:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymongo_payments`
--

CREATE TABLE `paymongo_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `currency_rate` double NOT NULL DEFAULT '1',
  `country_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paymongo_payments`
--

INSERT INTO `paymongo_payments` (`id`, `secret_key`, `public_key`, `status`, `currency_rate`, `country_code`, `currency_code`, `image`, `created_at`, `updated_at`) VALUES
(1, 'sk_test_REPLACE_ME', 'pk_test_REPLACE_ME', 1, 55.07, 'PH', 'PHP', '62c01dbd46dc01656757693.jpg', NULL, '2022-07-03 10:06:24');

-- --------------------------------------------------------

--
-- Table structure for table `paypal_payments`
--

CREATE TABLE `paypal_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `account_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `secret_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `country_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_rate` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paypal_payments`
--

INSERT INTO `paypal_payments` (`id`, `status`, `account_mode`, `client_id`, `secret_id`, `country_code`, `currency_code`, `currency_rate`, `created_at`, `updated_at`) VALUES
(1, 1, 'sandbox', 'PAYPAL_CLIENT_ID_REPLACE_ME', 'PAYPAL_SECRET_REPLACE_ME', 'US', 'USD', 1, NULL, '2025-09-16 23:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `paystack_and_mollies`
--

CREATE TABLE `paystack_and_mollies` (
  `id` bigint UNSIGNED NOT NULL,
  `mollie_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mollie_status` int NOT NULL DEFAULT '0',
  `mollie_currency_rate` double NOT NULL DEFAULT '1',
  `paystack_public_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paystack_secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paystack_currency_rate` double NOT NULL DEFAULT '1',
  `paystack_status` int NOT NULL DEFAULT '0',
  `mollie_country_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mollie_currency_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paystack_country_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paystack_currency_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paystack_and_mollies`
--

INSERT INTO `paystack_and_mollies` (`id`, `mollie_key`, `mollie_status`, `mollie_currency_rate`, `paystack_public_key`, `paystack_secret_key`, `paystack_currency_rate`, `paystack_status`, `mollie_country_code`, `mollie_currency_code`, `paystack_country_code`, `paystack_currency_code`, `created_at`, `updated_at`) VALUES
(1, 'test_REPLACE_ME', 1, 1.27, 'pk_test_REPLACE_ME', 'sk_test_REPLACE_ME', 417.35, 1, 'GB', 'GBP', 'NG', 'NGN', NULL, '2025-09-16 23:53:02');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `popular_categories`
--

CREATE TABLE `popular_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `popular_categories`
--

INSERT INTO `popular_categories` (`id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 3, '2022-09-20 07:14:58', '2022-09-20 07:14:58'),
(2, 1, '2022-09-20 07:15:05', '2022-09-20 07:15:05'),
(3, 2, '2022-09-20 07:15:11', '2022-09-20 07:15:11'),
(4, 4, '2025-09-10 01:18:14', '2025-09-10 01:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `popular_posts`
--

CREATE TABLE `popular_posts` (
  `id` bigint UNSIGNED NOT NULL,
  `blog_id` int NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `popular_posts`
--

INSERT INTO `popular_posts` (`id`, `blog_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2022-09-22 10:19:05', '2022-09-22 10:19:05'),
(2, 5, 1, '2022-09-22 10:19:08', '2022-09-22 10:19:08'),
(3, 4, 1, '2022-09-22 10:19:10', '2022-09-22 10:19:10'),
(4, 3, 1, '2022-09-22 10:19:22', '2022-09-22 10:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` int NOT NULL DEFAULT '0',
  `category_id` int NOT NULL,
  `sub_category_id` int NOT NULL DEFAULT '0',
  `child_category_id` int NOT NULL DEFAULT '0',
  `brand_id` int NOT NULL DEFAULT '0',
  `qty` int NOT NULL DEFAULT '0',
  `weight` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sold_qty` int NOT NULL DEFAULT '0',
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `long_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `offer_price` double DEFAULT NULL,
  `tags` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `show_homepage` tinyint NOT NULL DEFAULT '0',
  `is_undefine` tinyint NOT NULL DEFAULT '0',
  `is_featured` tinyint NOT NULL DEFAULT '0',
  `new_product` tinyint NOT NULL DEFAULT '0',
  `is_top` tinyint NOT NULL DEFAULT '0',
  `is_best` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `is_specification` int NOT NULL DEFAULT '1',
  `approve_by_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `short_name`, `slug`, `thumb_image`, `vendor_id`, `category_id`, `sub_category_id`, `child_category_id`, `brand_id`, `qty`, `weight`, `sold_qty`, `short_description`, `long_description`, `video_link`, `sku`, `seo_title`, `seo_description`, `price`, `offer_price`, `tags`, `show_homepage`, `is_undefine`, `is_featured`, `new_product`, `is_top`, `is_best`, `status`, `is_specification`, `approve_by_admin`, `created_at`, `updated_at`) VALUES
(1, 'Suede Desert Boots', 'Suede', 'suede-desert-boots', 'uploads/custom-images/suede-desert-boots-2025-08-24-01-04-15-4972.jpg', 0, 3, 0, 0, 4, 92, '222', 0, 'Our Suede Desert Boots are made with high-quality suede and feature a modern, stylish design. They\'re perfect for any casual occasion and come in a variety of colors.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop12345', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 123, NULL, NULL, 1, 1, 1, 0, 0, 1, 1, 0, 1, '2022-09-20 07:22:29', '2025-09-09 03:03:26'),
(2, 'Slim-Fit Blazer', 'Blazer', 'slimfit-blazer', 'uploads/custom-images/slim-fit-blazer-2025-08-24-01-04-05-1492.jpg', 0, 2, 4, 0, 0, 226, '133', 0, 'Our Slim-Fit Blazer is made with high-quality wool and features a modern, slim fit. It\'s perfect for any formal occasion and comes in a variety of colors. Our Slim-Fit Blazer is made with high-quality wool and features a modern, slim fit. It\'s perfect for any formal occasion and comes in a variety of colors.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop1234566', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 88, NULL, NULL, 0, 1, 0, 1, 1, 1, 1, 0, 1, '2022-09-20 07:32:02', '2025-08-24 07:04:05'),
(3, 'Wool Scarf', 'Scarf', 'wool-scarf', 'uploads/custom-images/wool-scarf-2025-08-24-01-03-53-1665.jpg', 0, 2, 5, 0, 3, 34, '133', 0, 'Stay warm and stylish this winter with our Wool Scarf. Made with a thick, cozy wool blend and featuring a classic design, it\'s the perfect accessory for any cold weather occasion. Stay warm and stylish this winter with our Wool Scarf. Made with a thick, cozy wool blend and featuring a classic design, it\'s the perfect accessory for any cold weather occasion', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop123451', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 88.88, 67.89, NULL, 0, 1, 0, 1, 1, 1, 1, 0, 1, '2022-09-20 07:33:46', '2025-08-24 07:03:53'),
(4, 'Leather Watch', 'Watch', 'leather-watch', 'uploads/custom-images/leather-watch-2025-08-24-01-03-41-7533.jpg', 0, 2, 6, 0, 3, -5, '133', 0, 'Our Leather Watch is a timeless accessory that every man should have in his collection. Made with high-quality leather and featuring a sleek, modern design, it\'s the perfect finishing touch to any outfit.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop1234515', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 1111, NULL, NULL, 1, 1, 1, 0, 0, 0, 1, 0, 1, '2022-09-20 07:37:26', '2025-08-24 07:03:41'),
(5, 'Denim Jacket', 'Denim Jacket', 'denim-jacket', 'uploads/custom-images/denim-jacket-2025-08-24-01-03-25-1223.jpg', 0, 10, 9, 0, 5, 31, '234', 0, 'A classic piece that never goes out of style, our Denim Jacket is made with high-quality denim and features a modern, slim fit. It\'s perfect for layering and comes in a variety of washes. A classic piece that never goes out of style, our Denim Jacket .', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop123456633', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 99.88, 35.43, NULL, 0, 1, 0, 1, 0, 1, 1, 0, 1, '2022-09-20 07:42:50', '2025-08-24 07:03:25'),
(6, 'Wool Suit Jacket', 'Wool Suit Jacket', 'wool-suit-jacket', 'uploads/custom-images/wool-suit-jacket-2025-08-24-01-03-09-4466.jpg', 0, 10, 8, 0, 2, 21, '21', 0, 'Our Wool Suit Jacket is made with high-quality wool and features a modern, slim fit. It\'s perfect for any formal occasion and comes in a variety of colors. Our Wool Suit Jacket is made with high-quality wool and features a modern, slim fit. It\'s perfect for any formal occasion and comes in a variety of colors.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop1234512', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 11.11, NULL, NULL, 0, 1, 0, 0, 0, 1, 1, 0, 1, '2022-09-20 07:44:22', '2025-08-24 07:03:09'),
(7, 'Performance Polo Shirt', 'Performance Polo Shirt', 'performance-polo-shirt', 'uploads/custom-images/performance-polo-shirt-2025-08-24-01-03-01-3309.jpg', 0, 3, 0, 0, 4, 98, '33', 0, 'Our Performance Polo Shirt is made with moisture-wicking fabric that\'s perfect for staying cool and comfortable on hot days. It features a classic design and comes in a variety of colors. Our Performance Polo Shirt is made with moisture-wicking fabric that\'s perfect for staying cool and comfortable on hot days. It features a classic design and comes in a variety of colors.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop12993452', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 11.33, NULL, NULL, 1, 1, 1, 1, 0, 1, 1, 0, 1, '2022-09-20 07:46:26', '2025-09-09 03:41:31'),
(8, 'Hooded Sweatshirt', 'Hooded Sweatshirt', 'hooded-sweatshirt', 'uploads/custom-images/hooded-sweatshirt-2025-08-24-01-02-52-7271.jpg', 0, 1, 3, 0, 0, 31, '12', 0, 'Our Hooded Sweatshirt is made with a soft, comfortable cotton blend and features a modern, slim fit. It\'s perfect for layering and comes in a variety of colors. Our Hooded Sweatshirt is made with a soft, comfortable cotton blend and features a modern, slim fit. It\'s perfect for layering and comes in a variety of colors.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop12345133', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 11.11, NULL, NULL, 1, 1, 1, 0, 1, 1, 1, 0, 1, '2022-09-20 07:49:30', '2025-09-09 01:15:50'),
(9, 'Leather Messenger Bag', 'Leather Messenger Bag', 'leather-messenger-bag', 'uploads/custom-images/leather-messenger-bag-2025-08-24-01-02-43-1229.jpg', 0, 4, 0, 0, 0, 18, '55', 0, 'Keep your essentials organized and within reach with our Leather Messenger Bag. Made with high-quality leather and featuring multiple pockets and compartments, it\'s the perfect accessory for any man on the go.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop1234522', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 33, 11.99, NULL, 1, 1, 1, 1, 0, 1, 1, 0, 1, '2022-09-20 07:56:37', '2025-08-24 05:27:57'),
(10, 'Wool Peacoat', 'Wool Peacoat', 'wool-peacoat', 'uploads/custom-images/wool-peacoat-2025-08-24-01-02-35-7267.jpg', 0, 4, 0, 0, 4, 14, '123', 0, 'Stay warm and stylish this winter with our Wool Peacoat. Made with a thick wool blend and featuring a classic design, it\'s the perfect outerwear choice for any cold weather occasion.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop123456622', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 123, NULL, NULL, 1, 1, 1, 1, 0, 1, 1, 0, 1, '2022-09-20 07:58:06', '2025-08-24 05:27:57'),
(11, 'Leather Dress Shoes', 'Leather Dress Shoes', 'leather-dress-shoes', 'uploads/custom-images/leather-dress-shoes-2025-08-24-01-02-24-3707.jpg', 0, 10, 7, 0, 1, 28, '44', 0, 'Every man needs a great pair of dress shoes in his wardrobe, and our Leather Dress Shoes are the perfect choice. Made with high-quality leather and featuring a sleek, modern design, they\'re sure to impress.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop123456611', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 333, NULL, NULL, 1, 1, 1, 1, 0, 1, 1, 0, 1, '2022-09-20 07:59:33', '2025-08-24 07:02:24'),
(12, 'Crew Neck Sweater', 'Crew Neck Sweater', 'crew-neck-sweater', 'uploads/custom-images/crew-neck-sweater-2025-08-24-01-02-13-6427.jpg', 0, 4, 0, 0, 0, 51, '44', 0, 'A versatile wardrobe staple, our Crew Neck Sweater is made with a soft, cozy blend of wool and cashmere. It features a classic, timeless design and comes in a variety of colors.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop123456655', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 37, NULL, NULL, 1, 1, 1, 0, 0, 0, 1, 0, 1, '2022-09-20 08:00:33', '2025-08-24 07:02:13'),
(13, 'Slim-Fit Chinos', 'Slim-Fit Chinos', 'slimfit-chinos', 'uploads/custom-images/slim-fit-chinos-2025-08-24-01-02-02-7168.jpg', 0, 3, 0, 0, 5, 196, '33', 0, 'Slim-Fit Chinos: Our Slim-Fit Chinos are made with a comfortable stretch fabric that allows for ease of movement. They come in a variety of colors and feature a modern, slim fit that\'s perfect for any occasion.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'bigshop12345133', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 133, NULL, '[{\"value\":\"test\"},{\"value\":\"ted3\"},{\"value\":\"tes\"},{\"value\":\"edw\"}]', 0, 1, 1, 0, 0, 1, 1, 0, 1, '2022-09-20 08:01:55', '2025-08-24 07:02:02'),
(14, 'Appple macbook pro 2020', 'Apple macbook', 'appple-macbook-pro-2020', 'uploads/custom-images/appple-macbook-pro-2020-2022-09-21-03-15-03-6666.png', 1, 1, 0, 0, 3, 10, 'Dolor ut corporis do', 0, 'Excepteur alias est', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, 'Enim numquam possimu', 'Id minus at blanditi', 'Nesciunt illo quisq', 120000, 100000, NULL, 0, 1, 0, 0, 0, 1, 0, 0, 1, '2022-09-21 09:15:03', '2022-09-21 09:34:11'),
(15, 'Sony mini sound box', 'Sony sound', 'sony-mini-sound-box', 'uploads/custom-images/sony-mini-sound-box-2022-09-21-03-16-48-7639.png', 1, 1, 0, 0, 1, 10, '45', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, '25536', 'Sony mini sound box', 'Sony mini sound box', 500, 450, NULL, 0, 1, 0, 0, 0, 1, 0, 0, 1, '2022-09-21 09:16:48', '2022-09-21 09:34:11'),
(16, 'Mi Laptop pro', 'Mi Lapotop', 'mi-laptop-pro', 'uploads/custom-images/mi-laptop-pro-2022-09-21-03-18-52-8009.png', 1, 1, 2, 0, 0, 458, '89', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, '4545ouj', 'Mi Laptop pro', 'Mi Laptop pro', 50000, 41000, NULL, 0, 1, 0, 0, 0, 1, 0, 0, 1, '2022-09-21 09:18:52', '2022-09-21 09:34:12'),
(17, 'JBL sound box water proof', 'JBL sound', 'jbl-sound-box-water-proof', 'uploads/custom-images/jbl-sound-box-water-proof-2022-09-21-03-20-10-4755.png', 1, 4, 0, 0, 0, 78, '15', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, 'colorful-cloths', 'JBL sound box water proof', 'JBL sound box water proof', 1200, 1000, NULL, 0, 1, 0, 0, 0, 1, 0, 0, 1, '2022-09-21 09:20:10', '2022-09-21 09:34:12'),
(18, 'Sony joystick SJ pro', 'Sony joystick', 'sony-joystick-sj-pro', 'uploads/custom-images/sony-joystick-sj-pro-2022-09-21-03-25-19-2532.png', 1, 1, 0, 0, 0, 589, '45', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, '255po', 'Sony joystick SJ pro', 'Sony joystick SJ pro', 2000, 1200, NULL, 0, 1, 1, 1, 1, 1, 0, 0, 1, '2022-09-21 09:25:19', '2022-09-21 09:34:13'),
(19, 'Gopor cam v1 pro', 'Gopro cam', 'gopor-cam-v1-pro', 'uploads/custom-images/gopor-cam-v1-pro-2022-09-21-03-26-33-7496.png', 1, 1, 3, 0, 0, 478, '45', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, 'loiu45', 'Gopor cam v1 pro', 'Gopor cam v1 pro', 30000, 25000, NULL, 0, 1, 1, 1, 1, 1, 0, 1, 1, '2022-09-21 09:26:33', '2022-09-21 09:34:14'),
(20, 'HP playstation 11', 'Hp playstation', 'hp-playstation-11', 'uploads/custom-images/hp-playstation-11-2022-09-21-03-28-49-9507.png', 1, 1, 1, 0, 0, 45, '452', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, 'lki12', 'HP playstation 11', 'HP playstation 11', 10000, 8000, NULL, 0, 1, 0, 1, 1, 0, 0, 0, 1, '2022-09-21 09:28:49', '2022-09-21 09:34:16'),
(21, 'Realme speaker 2 prod', 'Realme speaker', 'realme-speaker-2-prod', 'uploads/custom-images/realme-speaker-2-prod-2022-09-21-03-31-19-6994.png', 1, 4, 0, 0, 0, 15, '2', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, '14]poi', 'Realme speaker 2 prod', 'Realme speaker 2 prod', 1200, 1100, NULL, 0, 1, 0, 1, 1, 0, 0, 0, 1, '2022-09-21 09:31:20', '2022-09-21 09:34:16'),
(22, 'Xioami Joystick pro', 'Xioami Joystick', 'xioami-joystick-pro', 'uploads/custom-images/xioami-joystick-pro-2022-09-21-03-33-43-9999.png', 1, 2, 4, 0, 0, 45, '78', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, 'kju45', 'Xioami Joystick pro', 'Xioami Joystick pro', 2000, 1450, NULL, 0, 1, 1, 0, 0, 0, 0, 0, 1, '2022-09-21 09:33:43', '2022-09-21 09:34:17'),
(23, 'Apple macbook pro', 'Apple macbook', 'apple-macbook-pro', 'uploads/custom-images/apple-macbook-pro-2022-09-21-03-38-33-4014.png', 1, 1, 0, 0, 0, 45, '78', 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '<p><strong style=\"margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">Lorem Ipsum</strong><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and</span><br></p>', NULL, 'ujy45', 'Apple macbook pro', 'Apple macbook pro', 120000, 10000, NULL, 0, 1, 1, 1, 1, 1, 0, 0, 1, '2022-09-21 09:38:33', '2022-09-21 09:38:43'),
(25, 'Comfortable Yoga Pants', 'Comfortable Yoga Pants', 'comfortable-yoga-pants', 'uploads/custom-images/comfortable-yoga-pants-2023-03-23-11-59-40-7568.png', 1, 1, 2, 0, 3, 10, '12', 0, 'These high-waisted, stretchy yoga pants are perfect for any workout or lounging at home. Made from soft, breathable fabric, they offer ultimate comfort and flexibility. These high-waisted, stretchy yoga pants are perfect for any workout or lounging at home. Made from soft, breathable fabric, they offer ultimate comfort and flexibility.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'hjht6', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 120, NULL, NULL, 0, 1, 1, 0, 0, 1, 1, 0, 1, '2022-09-21 10:27:26', '2025-10-05 01:23:20'),
(26, 'Stylish Statement Earrings', 'Stylish Statement Earrings', 'stylish-statement-earrings', 'uploads/custom-images/naturally-flavored-cinnamon-coffee-2022-12-11-06-29-53-4105.png', 1, 4, 0, 0, 1, 37, '87', 0, 'These unique statement earrings will add a pop of color and style to any outfit. Perfect for dressing up or down, they\'re made from high-quality materials and are sure to turn heads.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'd45gg', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 50, NULL, NULL, 0, 1, 1, 1, 0, 1, 1, 0, 1, '2022-09-21 10:34:41', '2025-09-27 00:13:06'),
(27, 'Cozy Knit Sweater', 'Cozy Knit Sweater', 'cozy-knit-sweater', 'uploads/custom-images/dandy-seeds-of-change-organic-quinoe-2022-12-11-06-31-16-5736.png', 2, 8, 0, 0, 0, 15, '75', 0, 'Stay warm and stylish in this cozy knit sweater. Featuring a classic cable-knit design and a relaxed fit, it\'s perfect for layering and staying comfortable during the cooler months. Stay warm and stylish in this cozy knit sweater. Featuring a classic cable-knit design and a relaxed fit, it\'s perfect for layering and staying comfortable during the cooler months.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'kjkjh7', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 30, NULL, NULL, 0, 1, 1, 0, 0, 1, 1, 0, 1, '2022-09-21 10:40:16', '2025-08-23 10:00:02'),
(29, 'Women Wrap Blouses', 'Women Wrap Blouses', 'women-wrap-blouses', 'uploads/custom-images/sony-playstone-pro-2022-12-11-06-32-40-7991.png', 2, 2, 5, 0, 0, 77, '98', 0, 'Feminine Wrap Blouse: Add a touch of femininity to any outfit with this beautiful wrap blouse. Made from high-quality materials and featuring a flattering silhouette, it\'s perfect for any occasion. Add a touch of femininity to any outfit with this beautiful wrap blouse. Made from high-quality materials and featuring a flattering silhouette, it\'s perfect for any occasion.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'jhuy67', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 120, NULL, NULL, 0, 1, 1, 0, 0, 1, 1, 0, 1, '2022-09-21 10:43:08', '2025-08-23 10:00:11'),
(30, 'Elegant Silk Scarf', 'Elegant Silk Scarf', 'elegant-silk-scarf', 'uploads/custom-images/elegant-silk-scarf-2023-03-23-12-09-43-5336.png', 2, 1, 0, 0, 0, 86, '87', 0, 'Add a touch of sophistication to any outfit with this elegant silk scarf. Perfect for dressing up or down, it\'s made from high-quality silk and features a beautiful pattern. Add a touch of sophistication to any outfit with this elegant silk scarf. Perfect for dressing up or down, it\'s made from high-quality silk and features a beautiful pattern.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'hjhg67', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 70, NULL, NULL, 0, 1, 1, 0, 1, 0, 1, 0, 1, '2022-09-21 10:43:59', '2025-08-23 10:00:41'),
(31, 'Chic Leather Boots', 'Chic Leather Boots', 'chic-leather-boots', 'uploads/custom-images/chic-leather-boots-2023-03-23-12-11-08-3622.png', 3, 2, 4, 0, 0, 30, '87', 0, 'These chic leather boots are perfect for any occasion. With a comfortable heel and a sleek design, they\'re ideal for both work and play. With a comfortable heel and a sleek design, they\'re ideal for both work and play.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'kjjhy7', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 100, NULL, NULL, 0, 1, 1, 0, 0, 1, 1, 0, 1, '2022-09-21 10:46:59', '2025-09-23 05:45:17');
INSERT INTO `products` (`id`, `name`, `short_name`, `slug`, `thumb_image`, `vendor_id`, `category_id`, `sub_category_id`, `child_category_id`, `brand_id`, `qty`, `weight`, `sold_qty`, `short_description`, `long_description`, `video_link`, `sku`, `seo_title`, `seo_description`, `price`, `offer_price`, `tags`, `show_homepage`, `is_undefine`, `is_featured`, `new_product`, `is_top`, `is_best`, `status`, `is_specification`, `approve_by_admin`, `created_at`, `updated_at`) VALUES
(32, 'Casual Denim Jacket', 'Casual Denim Jacket', 'casual-denim-jacket', 'uploads/custom-images/casual-denim-jacket-2023-03-23-12-11-36-9094.png', 3, 1, 0, 0, 2, 48, '45', 0, 'Stay cool and casual in this versatile denim jacket. Perfect for layering over any outfit, it\'s made from high-quality denim and features classic styling. Stay cool and casual in this versatile denim jacket. Perfect for layering over any outfit, it\'s made from high-quality denim and features classic styling.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'kju7', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 8, NULL, NULL, 0, 1, 1, 1, 0, 1, 1, 0, 1, '2022-09-21 10:48:07', '2025-10-05 01:25:25'),
(33, 'Boho Maxi Dress', 'Boho Maxi Dress', 'boho-maxi-dress', 'uploads/custom-images/boho-maxi-dress-2023-03-23-12-11-20-1047.png', 4, 10, 0, 0, 6, 0, '87', 0, 'Embrace your inner bohemian with this beautiful maxi dress. Featuring a flowy silhouette and a stunning print, it\'s perfect for summer days and nights. Embrace your inner bohemian with this beautiful maxi dress. Featuring a flowy silhouette and a stunning print, it\'s perfect for summer days and nights.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'kjhyy78', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 35, 33, NULL, 0, 1, 1, 1, 0, 1, 1, 0, 1, '2022-09-21 10:50:36', '2025-08-23 10:01:27'),
(34, 'Modern Geometric Necklace', 'Modern Geometric Necklace', 'modern-geometric-necklace', 'uploads/custom-images/modern-geometric-necklace-2023-03-23-12-11-48-1363.png', 4, 1, 0, 0, 1, 979, '76', 0, 'Add a touch of modern elegance to any outfit with this geometric necklace. Made from high-quality materials, it\'s the perfect finishing touch to any look. Add a touch of modern elegance to any outfit with this geometric necklace. Made from high-quality materials, it\'s the perfect finishing touch to any look.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'nmjbff45', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 25, 15, NULL, 0, 1, 1, 0, 0, 1, 1, 0, 1, '2022-09-21 10:51:56', '2025-08-23 10:01:38'),
(35, 'Romantic Lace Dress', 'Romantic Lace Dress', 'romantic-lace-dress', 'uploads/custom-images/romantic-lace-dress-2023-03-23-12-12-16-7553.png', 5, 4, 0, 0, 0, 50, '34', 0, 'Romantic Lace Dress: Embrace your romantic side with this beautiful lace dress. Featuring a flattering silhouette and delicate details, it\'s perfect for special occasions or a night out.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'DFH45', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 123, NULL, NULL, 0, 1, 1, 0, 0, 1, 1, 0, 1, '2022-09-21 10:54:12', '2025-09-23 05:44:46'),
(36, 'Trendy Bucket Hat', 'Bucket', 'trendy-bucket-hat', 'uploads/custom-images/trendy-bucket-hat-2023-03-23-12-12-02-7850.png', 6, 2, 4, 0, 0, 186, '45', 0, 'Stay stylish and protected from the sun with this trendy bucket hat. Made from high-quality materials and featuring a classic design, it\'s the perfect accessory for any outdoor activity.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'dfgh6', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 70, NULL, NULL, 0, 1, 1, 1, 1, 1, 1, 0, 1, '2022-09-21 10:56:12', '2025-09-23 05:50:37'),
(37, 'Feminine Wrap Blouse', 'Blouse', 'feminine-wrap-blouse', 'uploads/custom-images/feminine-wrap-blouse-2023-03-23-12-08-55-5449.png', 6, 1, 0, 0, 3, 490, '12', 0, 'Add a touch of femininity to any outfit with this beautiful wrap blouse. Made from high-quality materials and featuring a flattering silhouette, it\'s perfect for any occasion. Add a touch of femininity to any outfit with this beautiful wrap blouse. Made from high-quality materials and featuring a flattering silhouette, it\'s perfect for any occasion.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, 'wer56', 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 450, NULL, NULL, 0, 1, 1, 1, 0, 1, 1, 0, 1, '2022-09-21 11:00:41', '2025-10-05 01:25:25'),
(38, 'Classic Oxford Shirt', 'Classic Oxford Shirt', 'classic-oxford-shirt', 'uploads/custom-images/classic-oxford-shirt-2025-08-24-01-01-51-6910.jpg', 0, 1, 1, 0, 1, 218, '10', 0, 'A timeless piece that every man should have in his wardrobe. Our Classic Oxford Shirt is made with soft, high-quality cotton and features a button-down collar and a slim fit. It\'s perfect for dressing up or down.', '<p class=\"p1\">Introducing the latest addition to our collection, the \"Ultra-Soft Lounge Pants.\" Made from a luxurious blend of cotton and polyester, these pants are designed with comfort in mind. The ultra-soft material is perfect for lounging around the house, but stylish enough to wear out and about. With their elastic waistband and drawstring tie, they offer a custom fit for ultimate comfort. These lounge pants are available in a range of colors and sizes, making them the perfect addition to any wardrobe. Whether you\'re relaxing at home or running errands, you won\'t want to take these pants off. Order now and experience the ultimate in comfort and style!<br><br>Looking for the perfect outfit to wear to your next event? Look no further than our \"Glamorous Sequin Dress.\" This stunning dress features a flattering A-line silhouette, with a fully lined sequin bodice and flowing chiffon skirt. The dress also features a deep V-neckline and delicate spaghetti straps, adding a touch of elegance and sophistication. Perfect for proms, weddings, or any formal event, this dress will have you turning heads all night long. Choose from a range of sizes and colors to find the perfect fit for you. Make a statement at your next event with the \"Glamorous Sequin Dress.\" Order now and get ready to dazzle!</p>', NULL, NULL, 'ShopUS: Your One-Stop Destination for Fashion and Style', 'ShopUS: Your One-Stop Destination for Fashion and Style', 10, NULL, '[{\"value\":\"something\"},{\"value\":\"Hot deal\"},{\"value\":\"new\"}]', 0, 1, 1, 1, 1, 1, 1, 0, 1, '2022-10-26 16:30:39', '2025-09-23 05:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_galleries`
--

CREATE TABLE `product_galleries` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_reports`
--

CREATE TABLE `product_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `seller_id` int NOT NULL DEFAULT '0',
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reports`
--

INSERT INTO `product_reports` (`id`, `user_id`, `product_id`, `seller_id`, `subject`, `description`, `created_at`, `updated_at`) VALUES
(1, 7, 30, 2, 'John Doe', 'test report', '2022-10-31 17:49:14', '2022-10-31 17:49:14'),
(2, 7, 7, 0, 'Ivy Townsend', 'Qui doloribus magna', '2022-10-31 17:50:56', '2022-10-31 17:50:56'),
(3, 7, 7, 0, 'John Doe', 'Enter Report Not', '2022-11-12 15:30:53', '2022-11-12 15:30:53');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `product_vendor_id` int NOT NULL DEFAULT '0',
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `product_vendor_id`, `review`, `rating`, `status`, `created_at`, `updated_at`) VALUES
(1, 13, 4, 0, 'Corrupti dolor enimCorrupti dolor enimCorrupti dolor enimCorrupti dolor enimCorrupti dolor enimCorrupti dolor enimCorrupti dolor enimCorrupti dolor enim', 4, 1, '2022-09-21 07:36:23', '2022-09-21 07:36:55'),
(2, 7, 4, 0, 'Enim et ducimus fug', 4, 1, '2022-09-21 07:38:38', '2022-09-21 08:26:36'),
(9, 13, 19, 0, 'Voluptas ut molestia', 4, 1, '2022-10-27 16:24:37', '2022-11-01 22:09:35'),
(10, 37, 7, 6, 'gfgfg', 5, 0, '2022-11-08 20:23:11', '2022-11-08 20:23:11'),
(11, 38, 30, 0, 'Gg', 4, 0, '2022-11-10 23:04:35', '2022-11-10 23:04:35'),
(12, 8, 31, 0, 'xx', 5, 1, '2022-11-17 19:48:56', '2022-11-22 23:43:31'),
(13, 38, 7, 0, 'good', 5, 0, '2022-11-20 12:03:03', '2022-11-20 12:03:03'),
(14, 8, 7, 0, 'This is nice product.', 4, 1, '2022-11-22 23:39:11', '2022-11-22 23:42:03'),
(15, 11, 38, 0, 'Google', 5, 0, '2022-12-02 16:05:57', '2022-12-02 16:05:57'),
(16, 32, 7, 3, 'Better', 0, 0, '2022-12-07 20:21:43', '2022-12-07 20:21:43'),
(17, 11, 7, 0, 'gghh', 4, 0, '2022-12-08 19:38:49', '2022-12-08 19:38:49'),
(18, 37, 42, 6, 'This is nice product', 0, 0, '2023-01-16 16:05:30', '2023-01-16 16:05:30'),
(19, 9, 7, 0, 'gfgfggg', 0, 0, '2023-03-26 09:11:55', '2023-03-26 09:11:55');

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `product_specification_key_id` int NOT NULL,
  `specification` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_specification_keys`
--

CREATE TABLE `product_specification_keys` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_specification_keys`
--

INSERT INTO `product_specification_keys` (`id`, `key`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bettary', 1, '2022-11-15 15:13:21', '2022-11-15 15:13:21'),
(2, 'CC', 1, '2022-11-15 15:13:27', '2022-11-15 15:13:27'),
(3, 'Camera', 1, '2022-11-15 15:13:32', '2022-11-15 15:13:32'),
(4, 'RAM', 1, '2022-11-15 15:13:45', '2022-11-15 15:13:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 12, 'size', 1, '2022-11-02 17:10:52', '2022-11-02 17:10:52'),
(8, 38, 'Size', 1, '2023-03-23 04:29:07', '2023-03-23 04:29:07'),
(9, 38, 'Color', 1, '2023-08-06 10:03:56', '2023-08-06 10:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_items`
--

CREATE TABLE `product_variant_items` (
  `id` bigint UNSIGNED NOT NULL,
  `product_variant_id` int NOT NULL,
  `product_variant_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `is_default` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_items`
--

INSERT INTO `product_variant_items` (`id`, `product_variant_id`, `product_variant_name`, `product_id`, `name`, `price`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'size', 12, 'small', 10, 1, 0, '2022-11-02 17:11:25', '2022-11-02 17:11:25'),
(2, 1, 'size', 12, 'big', 20, 1, 0, '2022-11-02 17:11:35', '2022-11-02 17:11:35'),
(11, 8, 'Size', 38, 'XL', 10, 1, 0, '2023-03-23 04:29:39', '2023-08-06 10:02:43'),
(12, 8, 'Size', 38, 'Medium', 50, 1, 0, '2023-08-06 10:03:02', '2023-08-06 10:03:02'),
(13, 8, 'Size', 38, 'Large', 50, 1, 0, '2023-08-06 10:03:14', '2023-08-06 10:03:14'),
(14, 9, 'Color', 38, 'Red', 0, 1, 0, '2023-08-06 10:04:08', '2023-08-06 10:04:08'),
(15, 9, 'Color', 38, 'Blue', 0, 1, 0, '2023-08-06 10:04:18', '2023-08-06 10:04:18'),
(16, 9, 'Color', 38, 'Navy', 0, 1, 0, '2023-08-06 10:04:30', '2023-08-06 10:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `pusher_credentails`
--

CREATE TABLE `pusher_credentails` (
  `id` bigint UNSIGNED NOT NULL,
  `app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_cluster` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pusher_credentails`
--

INSERT INTO `pusher_credentails` (`id`, `app_id`, `app_key`, `app_secret`, `app_cluster`, `created_at`, `updated_at`) VALUES
(1, '1338069', 'e013174602072a186b1d', '46de951521010c14b205', 'mt1', NULL, '2022-01-29 12:41:05');

-- --------------------------------------------------------

--
-- Table structure for table `razorpay_payments`
--

CREATE TABLE `razorpay_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_rate` double NOT NULL DEFAULT '1',
  `country_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `secret_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `razorpay_payments`
--

INSERT INTO `razorpay_payments` (`id`, `status`, `name`, `currency_rate`, `country_code`, `currency_code`, `description`, `image`, `color`, `key`, `secret_key`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ecommerce', 74.66, 'IN', 'INR', 'This is description', 'uploads/website-images/razorpay-2021-12-14-06-35-49-6602.png', '#2d15e5', 'rzp_test_REPLACE_ME', 'REPLACE_ME', NULL, '2025-09-16 23:52:46');

-- --------------------------------------------------------

--
-- Table structure for table `seller_mail_logs`
--

CREATE TABLE `seller_mail_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` int NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_withdraws`
--

CREATE TABLE `seller_withdraws` (
  `id` bigint UNSIGNED NOT NULL,
  `seller_id` int NOT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount` double NOT NULL,
  `withdraw_amount` double NOT NULL,
  `withdraw_charge` double NOT NULL,
  `account_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `approved_date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seo_settings`
--

CREATE TABLE `seo_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `page_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seo_settings`
--

INSERT INTO `seo_settings` (`id`, `page_name`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 'Home Page', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', NULL, '2025-10-05 23:33:35'),
(2, 'About Us', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', NULL, '2025-10-05 23:33:40'),
(3, 'Contact Us', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', NULL, '2025-08-23 10:16:03'),
(5, 'Seller Page', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', NULL, '2025-10-05 23:33:44'),
(6, 'Blog', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', NULL, '2025-10-05 23:33:47'),
(8, 'Flash Deal', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', NULL, '2025-10-05 23:33:51'),
(9, 'Shop Page', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', 'Nuter Guru: Your One-Stop Destination for Fashion and Style', NULL, '2025-10-05 23:33:55');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `icon`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Free Shipping', 'fas fa-shipping-fast', 'Free Shipping on Payment', 1, '2022-09-20 08:07:40', '2022-09-20 08:07:40'),
(2, 'Return Policy', 'fas fa-chevron-circle-left', '24 Hours Return Policy', 1, '2022-09-20 08:08:44', '2022-09-20 08:08:44'),
(3, 'Secured Payment', 'fab fa-cc-amazon-pay', 'Secure Card Payments', 1, '2022-09-20 08:09:50', '2022-09-20 08:09:50'),
(4, 'Best Quality', 'fas fa-check-circle', 'Best Quality Products', 1, '2022-09-20 08:10:27', '2022-09-22 04:24:58');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `maintenance_mode` int NOT NULL DEFAULT '0',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_user_register` int NOT NULL DEFAULT '1',
  `phone_number_required` int NOT NULL DEFAULT '1',
  `default_phone_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_multivendor` int NOT NULL DEFAULT '1',
  `enable_subscription_notify` int NOT NULL DEFAULT '1',
  `enable_save_contact_message` int NOT NULL DEFAULT '1',
  `text_direction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'LTR',
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_lg_header` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_sm_header` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topbar_phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `topbar_email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_icon` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_rate` double NOT NULL DEFAULT '1',
  `show_product_progressbar` int NOT NULL DEFAULT '1',
  `theme_one` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme_two` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `statistics_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#2c3e50',
  `statistics_font_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#ffffff',
  `seller_condition` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `popular_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Popular Category',
  `popular_category_product_qty` int NOT NULL DEFAULT '9',
  `frontend_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `popular_category_banner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_category_banner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `homepage_section_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `empty_cart` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empty_wishlist` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `change_password_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `become_seller_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `become_seller_banner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_login_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `app_version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'App version : 1.1',
  `tax` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `maintenance_mode`, `logo`, `favicon`, `contact_email`, `enable_user_register`, `phone_number_required`, `default_phone_code`, `enable_multivendor`, `enable_subscription_notify`, `enable_save_contact_message`, `text_direction`, `timezone`, `sidebar_lg_header`, `sidebar_sm_header`, `topbar_phone`, `topbar_email`, `currency_name`, `currency_icon`, `currency_rate`, `show_product_progressbar`, `theme_one`, `theme_two`, `statistics_color`, `statistics_font_color`, `seller_condition`, `popular_category`, `popular_category_product_qty`, `frontend_url`, `popular_category_banner`, `featured_category_banner`, `homepage_section_title`, `empty_cart`, `empty_wishlist`, `change_password_image`, `become_seller_avatar`, `become_seller_banner`, `admin_login_page`, `created_at`, `updated_at`, `app_version`, `tax`) VALUES
(1, 1, 'uploads/website-images/logo-2025-10-12-11-00-31-3844.png', 'uploads/website-images/favicon-2025-08-24-11-44-07-1423.png', 'mkhsojib@gmail.com', 1, 1, 'BD', 1, 1, 1, 'ltr', 'Asia/Dhaka', 'Nuter Guru', 'NG', '123-854-7896', 'contact@gmail.com', 'GBP', '£', 85.76, 0, '#82829c', '#b2aeae', '#e3e3e3', '#363630', '<h1><span style=\"color: rgb(0, 0, 0);\">Terms and Conditions</span></h1>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Welcome to QuomodoTheme!</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">These terms and conditions outline the rules and regulations for the use of Quomodo Theme Us Inc.\'s Website, located at https://QuomodoTheme.com.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">By accessing this website we assume you accept these terms and conditions. Do not continue to use QuomodoTheme if you do not agree to take all of the terms and conditions stated on this page.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: \"Client\", \"You\" and \"Your\" refers to you, the person log on this website and compliant to the Company&rsquo;s terms and conditions. \"The Company\", \"Ourselves\", \"We\", \"Our\" and \"Us\", refers to our Company. \"Party\", \"Parties\", or \"Us\", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client&rsquo;s needs in respect of provision of the Company&rsquo;s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.</span></p>\r\n<h3><span style=\"color: rgb(0, 0, 0);\">Cookies</span></h3>\r\n<p><span style=\"color: rgb(0, 0, 0);\">We employ the use of cookies. By accessing QuomodoTheme, you agreed to use cookies in agreement with the Quomodo Theme Us Inc.\'s Privacy Policy.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Most interactive websites use cookies to let us retrieve the user&rsquo;s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</span></p>\r\n<h3><span style=\"color: rgb(0, 0, 0);\">License</span></h3>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Unless otherwise stated, Quomodo Theme Us Inc. and/or its licensors own the intellectual property rights for all material on QuomodoTheme. All intellectual property rights are reserved. You may access this from QuomodoTheme for your own personal use subjected to restrictions set in these terms and conditions.</span></p>\r\n<h3><span style=\"color: rgb(0, 0, 0);\">You must not:</span></h3>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Republish material from QuomodoTheme</span><br><span style=\"color: rgb(0, 0, 0);\">Sell, rent or sub-license material from QuomodoTheme</span><br><span style=\"color: rgb(0, 0, 0);\">Reproduce, duplicate or copy material from QuomodoTheme</span><br><span style=\"color: rgb(0, 0, 0);\">Redistribute content from QuomodoTheme</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">This Agreement shall begin on the date hereof. Our Terms and Conditions were created with the help of the Terms And Conditions Template.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. Quomodo Theme Us Inc. does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of Quomodo Theme Us Inc.,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, Quomodo Theme Us Inc. shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Quomodo Theme Us Inc. reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.</span></p>\r\n<h3><span style=\"color: rgb(0, 0, 0);\">You warrant and represent that:</span></h3>\r\n<p><span style=\"color: rgb(0, 0, 0);\">You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;</span><br><span style=\"color: rgb(0, 0, 0);\">The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;</span><br><span style=\"color: rgb(0, 0, 0);\">The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy</span><br><span style=\"color: rgb(0, 0, 0);\">The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">You hereby grant Quomodo Theme Us Inc. a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Hyperlinking to our Content</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">The following organizations may link to our Website without prior written approval:</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Government agencies;</span><br><span style=\"color: rgb(0, 0, 0);\">Search engines;</span><br><span style=\"color: rgb(0, 0, 0);\">News organizations;</span><br><span style=\"color: rgb(0, 0, 0);\">Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and</span><br><span style=\"color: rgb(0, 0, 0);\">System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party&rsquo;s site.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">We may consider and approve other link requests from the following types of organizations:</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">commonly-known consumer and/or business information sources;</span><br><span style=\"color: rgb(0, 0, 0);\">dot.com community sites;</span><br><span style=\"color: rgb(0, 0, 0);\">associations or other groups representing charities;</span><br><span style=\"color: rgb(0, 0, 0);\">online directory distributors;</span><br><span style=\"color: rgb(0, 0, 0);\">internet portals;</span><br><span style=\"color: rgb(0, 0, 0);\">accounting, law and consulting firms; and</span><br><span style=\"color: rgb(0, 0, 0);\">educational institutions and trade associations.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of Quomodo Theme Us Inc.; and (d) the link is in the context of general resource information.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party&rsquo;s site.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to Quomodo Theme Us Inc.. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.</span></p>\r\n<h3><span style=\"color: rgb(0, 0, 0);\">Approved organizations may hyperlink to our Website as follows:</span></h3>\r\n<p><span style=\"color: rgb(0, 0, 0);\">By use of our corporate name; or</span><br><span style=\"color: rgb(0, 0, 0);\">By use of the uniform resource locator being linked to; or</span><br><span style=\"color: rgb(0, 0, 0);\">By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party&rsquo;s site.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">No use of Quomodo Theme Us Inc.\'s logo or other artwork will be allowed for linking absent a trademark license agreement.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">iFrames</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</span></p>\r\n<h3><span style=\"color: rgb(0, 0, 0);\">Content Liability</span></h3>\r\n<p><span style=\"color: rgb(0, 0, 0);\">We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</span></p>\r\n<h3><span style=\"color: rgb(0, 0, 0);\">Your Privacy</span></h3>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Please read Privacy Policy</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Reservation of Rights</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it&rsquo;s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">Removal of links from our website</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</span></p>\r\n<h3><span style=\"color: rgb(0, 0, 0);\">Disclaimer</span></h3>\r\n<p><span style=\"color: rgb(0, 0, 0);\">To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">limit or exclude our or your liability for death or personal injury;</span><br><span style=\"color: rgb(0, 0, 0);\">limit or exclude our or your liability for fraud or fraudulent misrepresentation;</span><br><span style=\"color: rgb(0, 0, 0);\">limit any of our or your liabilities in any way that is not permitted under applicable law; or</span><br><span style=\"color: rgb(0, 0, 0);\">exclude any of our or your liabilities that may not be excluded under applicable law.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</span></p>\r\n<p><span style=\"color: rgb(0, 0, 0);\">As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.</span></p>', 'Popular Category', 9, 'https://nooraani.co.uk/', 'uploads/website-images/popular-cat-banner-2022-09-20-01-15-44-7577.jpg', 'uploads/website-images/featured-cat-banner-2022-09-21-02-43-49-4710.jpg', '[{\"key\":\"My_Market_Category\",\"default\":\"My Market Category\",\"custom\":\"Hot Shot Category\"},{\"key\":\"Popular_Category\",\"default\":\"Popular Category\",\"custom\":\"Popular Category\"},{\"key\":\"Top_Rated_Products\",\"default\":\"Top Rated Products\",\"custom\":\"Top Rated Products\"},{\"key\":\"Best_Seller\",\"default\":\"Best Seller\",\"custom\":\"Best Seller\"},{\"key\":\"Featured_Products\",\"default\":\"Featured Products\",\"custom\":\"Featured Products\"},{\"key\":\"New_Arrivals\",\"default\":\"New Arrivals\",\"custom\":\"New Arrivals\"},{\"key\":\"Best_Products\",\"default\":\"Best Products\",\"custom\":\"Best Products\"}]', 'uploads/website-images/empty_cart-2022-12-11-11-20-13-2650.png', 'uploads/website-images/empty_wishlist-2022-12-11-11-20-13-5116.png', 'uploads/website-images/change_password_image-2022-12-11-11-20-13-6890.png', 'uploads/website-images/become_seller_avatar-2022-11-13-05-29-56-5617.png', 'uploads/website-images/become_seller_banner-2022-11-13-05-31-26-2357.png', 'uploads/website-images/admin_login_page-2025-10-04-09-55-30-5085.jpg', NULL, '2025-10-12 05:00:33', 'App Version : 2.0.0', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shippings`
--

CREATE TABLE `shippings` (
  `id` bigint UNSIGNED NOT NULL,
  `city_id` int NOT NULL,
  `shipping_rule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_fee` double NOT NULL,
  `condition_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shippings`
--

INSERT INTO `shippings` (`id`, `city_id`, `shipping_rule`, `type`, `shipping_fee`, `condition_from`, `condition_to`, `created_at`, `updated_at`) VALUES
(21, 8, 'Regular', 'base_on_price', 60, '1', '1000', '2022-12-13 20:10:08', '2022-12-13 20:13:18'),
(22, 8, 'Quantity(5-10)', 'base_on_qty', 120, '5', '10', '2022-12-13 20:11:16', '2022-12-13 20:13:24'),
(23, 0, 'Fixed Shipping', 'base_on_price', 20, '1', '1000', '2022-12-13 20:11:53', '2022-12-13 20:11:53');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_carts`
--

CREATE TABLE `shopping_carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `coupon_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_price` double NOT NULL,
  `offer_type` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shopping_carts`
--

INSERT INTO `shopping_carts` (`id`, `user_id`, `product_id`, `qty`, `coupon_name`, `coupon_price`, `offer_type`, `created_at`, `updated_at`) VALUES
(36, 12, 13, 1, '', 0, 0, '2022-09-22 05:45:27', '2022-09-22 05:45:27'),
(70, 6, 37, 1, '', 0, 0, '2022-09-26 10:35:34', '2022-09-26 10:35:34'),
(107, 5, 37, 2, '', 0, 0, '2022-09-27 11:38:08', '2022-09-27 11:38:08'),
(108, 5, 37, 1, '', 0, 0, '2022-09-27 11:38:13', '2022-09-27 11:38:13'),
(109, 16, 7, 1, '', 0, 0, '2022-10-26 19:41:39', '2022-10-26 19:41:39'),
(110, 16, 7, 1, '', 0, 0, '2022-10-26 19:41:44', '2022-10-26 19:41:44'),
(117, 21, 7, 2, '', 0, 0, '2022-10-30 21:29:04', '2022-10-30 21:29:25'),
(118, 21, 3, 2, '', 0, 0, '2022-10-30 21:29:09', '2022-10-30 21:29:29'),
(119, 21, 11, 1, '', 0, 0, '2022-10-30 21:29:15', '2022-10-30 21:29:15'),
(228, 27, 38, 1, '', 0, 0, '2022-11-07 21:59:13', '2022-11-07 21:59:13'),
(229, 27, 30, 1, '', 0, 0, '2022-11-07 21:59:21', '2022-11-07 21:59:21'),
(230, 27, 33, 1, '', 0, 0, '2022-11-07 21:59:25', '2022-11-07 21:59:25'),
(231, 27, 33, 2, '', 0, 0, '2022-11-07 22:12:04', '2022-11-07 22:12:04'),
(232, 27, 10, 1, '', 0, 0, '2022-11-07 22:17:53', '2022-11-07 22:17:53'),
(464, 31, 38, 1, '', 0, 0, '2022-11-17 21:48:51', '2022-11-17 21:48:51'),
(549, 31, 11, 1, '', 0, 0, '2022-11-23 18:12:09', '2022-11-23 18:12:09'),
(614, 37, 38, 1, '', 0, 0, '2022-12-01 23:13:06', '2022-12-01 23:13:06'),
(615, 37, 38, 1, '', 0, 0, '2022-12-01 23:13:11', '2022-12-01 23:13:11'),
(680, 42, 38, 1, '', 0, 0, '2023-01-16 18:40:47', '2023-01-16 18:40:47'),
(692, 41, 36, 1, '', 0, 0, '2023-01-17 12:14:35', '2023-01-17 12:14:35'),
(693, 41, 38, 1, '', 0, 0, '2023-01-17 13:05:17', '2023-01-17 13:05:17'),
(701, 43, 37, 2, '', 0, 0, '2023-01-21 22:52:20', '2023-01-21 22:53:47'),
(702, 43, 38, 2, '', 0, 0, '2023-01-21 22:54:22', '2023-01-21 22:54:22'),
(737, 54, 37, 1, '', 0, 0, '2023-04-04 04:35:03', '2023-04-04 04:35:03'),
(738, 56, 36, 1, '', 0, 0, '2023-04-04 04:42:15', '2023-04-04 04:42:15'),
(845, 7, 36, 1, '', 0, 0, '2023-05-27 18:58:57', '2023-05-27 18:58:57'),
(846, 7, 8, 1, '', 0, 0, '2023-05-27 19:26:59', '2023-05-27 19:26:59'),
(847, 7, 38, 1, '', 0, 0, '2023-05-27 21:04:05', '2023-05-27 21:04:05'),
(848, 7, 2, 1, '', 0, 0, '2023-05-27 23:38:42', '2023-05-27 23:38:42'),
(849, 7, 32, 1, '', 0, 0, '2023-05-28 00:09:58', '2023-05-28 00:09:58'),
(850, 7, 11, 1, '', 0, 0, '2023-05-28 00:39:41', '2023-05-28 00:39:41'),
(851, 7, 32, 1, '', 0, 0, '2023-05-28 00:56:32', '2023-05-28 00:56:32'),
(852, 7, 38, 1, '', 0, 0, '2023-05-28 01:57:01', '2023-05-28 01:57:01'),
(853, 7, 38, 1, '', 0, 0, '2023-05-28 02:40:05', '2023-05-28 02:40:05'),
(854, 7, 11, 1, '', 0, 0, '2023-05-28 04:22:15', '2023-05-28 04:22:15'),
(855, 7, 9, 1, '', 0, 0, '2023-05-28 04:23:31', '2023-05-28 04:23:31'),
(856, 7, 36, 1, '', 0, 0, '2023-05-28 05:09:34', '2023-05-28 05:09:34'),
(863, 58, 5, 1, '', 0, 0, '2025-08-26 04:45:56', '2025-08-26 04:45:56'),
(864, 58, 7, 1, '', 0, 0, '2025-08-26 04:48:29', '2025-08-26 04:48:29'),
(865, 58, 11, 3, '', 0, 0, '2025-08-26 04:58:21', '2025-08-26 04:58:21'),
(866, 58, 26, 1, '', 0, 0, '2025-08-26 05:02:02', '2025-08-26 05:02:02'),
(867, 58, 32, 3, '', 0, 0, '2025-08-26 05:11:27', '2025-08-26 06:06:13'),
(868, 58, 31, 1, '', 0, 0, '2025-08-26 05:11:33', '2025-08-26 05:11:33'),
(869, 58, 27, 1, '', 0, 0, '2025-08-26 05:11:37', '2025-08-26 05:11:37'),
(870, 58, 30, 1, '', 0, 0, '2025-08-26 05:12:14', '2025-08-26 05:12:14'),
(871, 58, 38, 1, '', 0, 0, '2025-08-26 05:12:16', '2025-08-26 05:12:16'),
(873, 61, 10, 6, '', 0, 0, '2025-08-26 23:38:53', '2025-08-26 23:38:53'),
(881, 1, 13, 1, NULL, 0, 0, '2025-08-27 04:16:51', '2025-08-27 04:16:51'),
(882, 1, 12, 1, NULL, 0, 0, '2025-08-27 04:16:53', '2025-08-27 04:16:53');

-- --------------------------------------------------------

--
-- Table structure for table `shopping_cart_variants`
--

CREATE TABLE `shopping_cart_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `shopping_cart_id` int NOT NULL,
  `variant_id` int NOT NULL,
  `variant_item_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shopping_cart_variants`
--

INSERT INTO `shopping_cart_variants` (`id`, `shopping_cart_id`, `variant_id`, `variant_item_id`, `created_at`, `updated_at`) VALUES
(8, 231, 11, 28, '2022-11-07 22:12:04', '2022-11-07 22:12:04'),
(9, 231, 12, 31, '2022-11-07 22:12:04', '2022-11-07 22:12:04'),
(72, 847, 8, 11, '2023-05-27 21:04:05', '2023-05-27 21:04:05'),
(73, 852, 8, 11, '2023-05-28 01:57:01', '2023-05-28 01:57:01'),
(74, 853, 8, 11, '2023-05-28 02:40:05', '2023-05-28 02:40:05');

-- --------------------------------------------------------

--
-- Table structure for table `shop_pages`
--

CREATE TABLE `shop_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `header_one` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_one` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `filter_price_range` double NOT NULL DEFAULT '10000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_pages`
--

INSERT INTO `shop_pages` (`id`, `header_one`, `header_two`, `title_one`, `title_two`, `banner`, `link`, `button_text`, `status`, `filter_price_range`, `created_at`, `updated_at`) VALUES
(1, 'Up To', '70% Off', 'Women\'s Jeans Collection', 'Fashion For Women\'s', 'uploads/website-images/banner-2022-02-06-04-22-39-1426.jpg', 'product', 'Discover now', 1, 5000, NULL, '2022-09-20 08:07:01');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint UNSIGNED NOT NULL,
  `title_one` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '0',
  `serial` int NOT NULL DEFAULT '0',
  `slider_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_slug` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `text_position` enum('left','right') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title_one`, `title_two`, `image`, `link`, `status`, `serial`, `slider_location`, `product_slug`, `text_position`, `created_at`, `updated_at`) VALUES
(1, 'UP TO 70% OFF', 'Fashion Collection Summer Sale', 'uploads/custom-images/slider-2025-08-24-12-49-59-7769.jpg', 'https://shopo-ecom.vercel.app/products?category=mobile', 1, 1, NULL, 'slimfit-blazer', 'left', '2022-09-20 03:55:57', '2025-10-05 03:08:44'),
(2, 'UP TO 70% OFF', 'Fashion Collection Summer Sale', 'uploads/custom-images/slider-2025-08-24-12-50-18-4476.jpg', 'https://shopo-ecom.vercel.app/products?category=lifestyle', 1, 2, NULL, 'wool-scarf', 'right', '2022-09-20 03:57:41', '2025-08-24 07:01:00'),
(3, 'UP TO 70% OFF', 'Fashion Collection Summer Sale', 'uploads/custom-images/slider-2025-08-24-12-50-30-2708.jpg', 'https://shopo-ecom.vercel.app/products?category=game', 1, 3, NULL, 'leather-watch', 'left', '2022-09-20 03:58:20', '2025-08-24 06:50:30');

-- --------------------------------------------------------

--
-- Table structure for table `sms_templates`
--

CREATE TABLE `sms_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subject` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_templates`
--

INSERT INTO `sms_templates` (`id`, `name`, `subject`, `description`, `created_at`, `updated_at`) VALUES
(1, 'User Verification', NULL, 'Dear {{user_name}},\r\n\r\nCongratulations! Your Account has been created successfully. Please copy and past and Active your Account. Your OTP code is {{otp_code}}', NULL, '2023-01-11 12:07:47'),
(2, 'Password Reset', NULL, 'Dear {{name}},\r\n\r\nDo you want to reset your password? Please Copy and past this code. Your OTP Code is {{otp_code}}', NULL, '2023-01-12 03:52:23'),
(3, 'Order Successfully', NULL, 'Hi {{user_name}},\r\n\r\nThanks for your new order. Your order id has been submited .\r\nOrder Id : {{order_id}},', NULL, '2023-01-11 11:43:08');

-- --------------------------------------------------------

--
-- Table structure for table `social_login_information`
--

CREATE TABLE `social_login_information` (
  `id` bigint UNSIGNED NOT NULL,
  `is_facebook` int NOT NULL DEFAULT '0',
  `facebook_client_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `facebook_secret_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_gmail` int NOT NULL DEFAULT '0',
  `gmail_client_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gmail_secret_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `facebook_redirect_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gmail_redirect_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_login_information`
--

INSERT INTO `social_login_information` (`id`, `is_facebook`, `facebook_client_id`, `facebook_secret_id`, `is_gmail`, `gmail_client_id`, `gmail_secret_id`, `facebook_redirect_url`, `gmail_redirect_url`, `created_at`, `updated_at`) VALUES
(1, 0, '343rer', 'fsdfa', 0, '334dsfs', 'fdsf343', 'link', 'link', NULL, '2022-06-11 11:16:26');

-- --------------------------------------------------------

--
-- Table structure for table `sslcommerz_payments`
--

CREATE TABLE `sslcommerz_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `store_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_rate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sslcommerz_payments`
--

INSERT INTO `sslcommerz_payments` (`id`, `store_id`, `store_password`, `mode`, `currency_rate`, `country_code`, `currency_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'STORE_ID_REPLACE_ME', 'STORE_PASSWORD_REPLACE_ME', 'sandbox', '80', 'BD', 'BDT', 1, NULL, '2022-11-30 02:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `stripe_payments`
--

CREATE TABLE `stripe_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `stripe_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stripe_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_rate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stripe_payments`
--

INSERT INTO `stripe_payments` (`id`, `status`, `stripe_key`, `stripe_secret`, `created_at`, `updated_at`, `country_code`, `currency_code`, `currency_rate`) VALUES
(1, 1, 'pk_test_REPLACE_ME', 'sk_test_REPLACE_ME', NULL, '2025-09-16 23:51:21', 'US', 'USD', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `verified_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_verified` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `status`, `verified_token`, `is_verified`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', 0, '568771', 0, '2022-09-21 05:03:38', '2022-09-21 05:03:38'),
(2, 'abdur.rohman2003@gmail.com', 0, '458560', 0, '2022-09-21 05:03:47', '2022-09-21 05:03:47'),
(3, 'admin@brandmarrow.com.bd', 0, '298058', 0, '2022-09-21 05:03:59', '2022-09-21 05:03:59'),
(4, 'abdur_rohman2003@yahoo.com', 0, '978651', 0, '2022-09-21 07:06:47', '2022-09-21 07:06:47'),
(5, 'h@h.com', 0, '863244', 0, '2022-09-22 10:18:38', '2022-09-22 10:18:38'),
(6, 'Mollitia rerum omnis', 0, '637112', 0, '2022-09-23 06:01:01', '2022-09-23 06:01:01'),
(7, 'supuhib@mailinator.com', 0, '184076', 0, '2022-09-23 06:01:08', '2022-09-23 06:01:08'),
(8, 'dasd', 0, '948904', 0, '2022-10-31 20:33:02', '2022-10-31 20:33:02');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Shrits', 'shrits', 1, '2022-09-20 05:51:21', '2023-04-06 10:05:03'),
(2, 1, 'Skirt', 'skirt', 1, '2022-09-20 05:51:35', '2023-03-22 07:05:23'),
(3, 1, 'T shirt', 't-shirt', 1, '2022-09-20 06:02:23', '2023-03-22 07:05:36'),
(4, 2, 'Hand Bag', 'hand-bag', 1, '2022-09-20 07:08:14', '2023-03-22 07:05:43'),
(5, 2, 'Mobile Wallets', 'mobile-wallets', 1, '2022-09-20 07:09:17', '2023-03-22 07:05:53'),
(6, 2, 'Purse', 'purse', 1, '2022-09-20 07:09:31', '2023-03-22 07:06:01'),
(7, 10, 'Lipstick', 'lipstick', 1, '2022-09-20 07:11:30', '2023-03-22 07:06:10'),
(8, 10, 'Face Powder', 'face-powder', 1, '2022-09-20 07:11:41', '2023-03-22 07:06:18'),
(9, 10, 'Foundation', 'foundation', 1, '2022-09-20 07:12:00', '2023-03-22 07:06:25');

-- --------------------------------------------------------

--
-- Table structure for table `tawk_chats`
--

CREATE TABLE `tawk_chats` (
  `id` bigint UNSIGNED NOT NULL,
  `chat_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `widget_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tawk_chats`
--

INSERT INTO `tawk_chats` (`id`, `chat_link`, `widget_id`, `property_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'https://tawk.to/chat/612dc781d6e7610a49b2d444/1fedd6l9m', '612dc781d6e7610a49b2d444', '1fedd6l9m', 1, NULL, '2022-11-14 03:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `terms_and_conditions`
--

CREATE TABLE `terms_and_conditions` (
  `id` bigint UNSIGNED NOT NULL,
  `terms_and_condition` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `privacy_policy` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terms_and_conditions`
--

INSERT INTO `terms_and_conditions` (`id`, `terms_and_condition`, `privacy_policy`, `created_at`, `updated_at`) VALUES
(1, '<p><strong>Terms and Conditions</strong></p>\r\n<h1>Welcome to <strong data-start=\"273\" data-end=\"285\">Nooraani</strong>!</h1>\r\n<p data-start=\"262\" data-end=\"422\">Welcome to <strong data-start=\"273\" data-end=\"285\">Nooraani</strong> (&ldquo;we,&rdquo; &ldquo;our,&rdquo; &ldquo;us&rdquo;).<br data-start=\"306\" data-end=\"309\">By communicating with us through WhatsApp or using our services, you agree to the following terms and conditions:</p>\r\n<ol data-start=\"424\" data-end=\"1604\">\r\n<li data-start=\"424\" data-end=\"641\">\r\n<p data-start=\"427\" data-end=\"444\"><strong data-start=\"427\" data-end=\"442\">Service Use</strong></p>\r\n<ul data-start=\"448\" data-end=\"641\">\r\n<li data-start=\"448\" data-end=\"559\">\r\n<p data-start=\"450\" data-end=\"559\">Our WhatsApp communication is for customer support, product inquiries, order confirmations, and promotions.</p>\r\n</li>\r\n<li data-start=\"563\" data-end=\"641\">\r\n<p data-start=\"565\" data-end=\"641\">Please do not use this channel for spam, offensive, or illegal activities.</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"643\" data-end=\"862\">\r\n<p data-start=\"646\" data-end=\"671\"><strong data-start=\"646\" data-end=\"669\">Orders and Payments</strong></p>\r\n<ul data-start=\"675\" data-end=\"862\">\r\n<li data-start=\"675\" data-end=\"774\">\r\n<p data-start=\"677\" data-end=\"774\">Any order or booking made via WhatsApp is considered confirmed only after payment verification.</p>\r\n</li>\r\n<li data-start=\"778\" data-end=\"862\">\r\n<p data-start=\"780\" data-end=\"862\">Prices, availability, and promotions are subject to change without prior notice.</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"864\" data-end=\"1081\">\r\n<p data-start=\"867\" data-end=\"893\"><strong data-start=\"867\" data-end=\"891\">Information Accuracy</strong></p>\r\n<ul data-start=\"897\" data-end=\"1081\">\r\n<li data-start=\"897\" data-end=\"993\">\r\n<p data-start=\"899\" data-end=\"993\">Customers are responsible for providing correct details (e.g., name, address, phone number).</p>\r\n</li>\r\n<li data-start=\"997\" data-end=\"1081\">\r\n<p data-start=\"999\" data-end=\"1081\">We are not liable for errors resulting from incorrect or incomplete information.</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"1083\" data-end=\"1299\">\r\n<p data-start=\"1086\" data-end=\"1113\"><strong data-start=\"1086\" data-end=\"1111\">Communication Consent</strong></p>\r\n<ul data-start=\"1117\" data-end=\"1299\">\r\n<li data-start=\"1117\" data-end=\"1232\">\r\n<p data-start=\"1119\" data-end=\"1232\">By contacting us on WhatsApp, you give consent to receive updates, order notifications, and promotional offers.</p>\r\n</li>\r\n<li data-start=\"1236\" data-end=\"1299\">\r\n<p data-start=\"1238\" data-end=\"1299\">You may stop receiving messages anytime by replying &ldquo;STOP.&rdquo;</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"1301\" data-end=\"1441\">\r\n<p data-start=\"1304\" data-end=\"1319\"><strong data-start=\"1304\" data-end=\"1317\">Liability</strong></p>\r\n<ul data-start=\"1323\" data-end=\"1441\">\r\n<li data-start=\"1323\" data-end=\"1441\">\r\n<p data-start=\"1325\" data-end=\"1441\">Nooraani is not responsible for message delivery delays or errors caused by third-party services (e.g., WhatsApp).</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"1443\" data-end=\"1604\">\r\n<p data-start=\"1446\" data-end=\"1468\"><strong data-start=\"1446\" data-end=\"1466\">Changes to Terms</strong></p>\r\n<ul data-start=\"1472\" data-end=\"1604\">\r\n<li data-start=\"1472\" data-end=\"1604\">\r\n<p data-start=\"1474\" data-end=\"1604\">We may update these Terms and Conditions from time to time. The latest version will be available upon request or on our website.</p>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>', '<h1>Privacy Policy for <strong data-start=\"1684\" data-end=\"1696\">Nooraani</strong>.</h1>\r\n<p data-start=\"1681\" data-end=\"1882\">At <strong data-start=\"1684\" data-end=\"1696\">Nooraani</strong>, we value and protect your privacy. This Privacy Policy describes how we collect, use, and safeguard your information when you communicate with us on WhatsApp or through other channels.</p>\r\n<ol data-start=\"1884\" data-end=\"2886\">\r\n<li data-start=\"1884\" data-end=\"2017\">\r\n<p data-start=\"1887\" data-end=\"1915\"><strong data-start=\"1887\" data-end=\"1913\">Information We Collect</strong></p>\r\n<ul data-start=\"1919\" data-end=\"2017\">\r\n<li data-start=\"1919\" data-end=\"2017\">\r\n<p data-start=\"1921\" data-end=\"2017\">Your name, phone number, address, and any details shared during conversations or transactions.</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"2019\" data-end=\"2224\">\r\n<p data-start=\"2022\" data-end=\"2046\"><strong data-start=\"2022\" data-end=\"2044\">Use of Information</strong></p>\r\n<ul data-start=\"2050\" data-end=\"2224\">\r\n<li data-start=\"2050\" data-end=\"2120\">\r\n<p data-start=\"2052\" data-end=\"2120\">To respond to your messages, process orders, and deliver services.</p>\r\n</li>\r\n<li data-start=\"2124\" data-end=\"2224\">\r\n<p data-start=\"2126\" data-end=\"2224\">To send order updates, service notifications, and promotional messages (only with your consent).</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"2226\" data-end=\"2392\">\r\n<p data-start=\"2229\" data-end=\"2250\"><strong data-start=\"2229\" data-end=\"2248\">Data Protection</strong></p>\r\n<ul data-start=\"2254\" data-end=\"2392\">\r\n<li data-start=\"2254\" data-end=\"2316\">\r\n<p data-start=\"2256\" data-end=\"2316\">We never sell or rent your personal data to third parties.</p>\r\n</li>\r\n<li data-start=\"2320\" data-end=\"2392\">\r\n<p data-start=\"2322\" data-end=\"2392\">Your data is kept secure and used only for service-related purposes.</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"2394\" data-end=\"2558\">\r\n<p data-start=\"2397\" data-end=\"2425\"><strong data-start=\"2397\" data-end=\"2423\">Third-Party Disclaimer</strong></p>\r\n<ul data-start=\"2429\" data-end=\"2558\">\r\n<li data-start=\"2429\" data-end=\"2558\">\r\n<p data-start=\"2431\" data-end=\"2558\">WhatsApp is a third-party platform owned by Meta. We are not responsible for how WhatsApp stores or manages your information.</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"2560\" data-end=\"2718\">\r\n<p data-start=\"2563\" data-end=\"2580\"><strong data-start=\"2563\" data-end=\"2578\">Your Rights</strong></p>\r\n<ul data-start=\"2584\" data-end=\"2718\">\r\n<li data-start=\"2584\" data-end=\"2652\">\r\n<p data-start=\"2586\" data-end=\"2652\">You may request correction or deletion of your data at any time.</p>\r\n</li>\r\n<li data-start=\"2656\" data-end=\"2718\">\r\n<p data-start=\"2658\" data-end=\"2718\">You may opt out of promotional messages by sending &ldquo;STOP.&rdquo;</p>\r\n</li>\r\n</ul>\r\n</li>\r\n<li data-start=\"2720\" data-end=\"2886\">\r\n<p data-start=\"2723\" data-end=\"2743\"><strong data-start=\"2723\" data-end=\"2741\">Policy Updates</strong></p>\r\n<ul data-start=\"2747\" data-end=\"2886\">\r\n<li data-start=\"2747\" data-end=\"2886\">\r\n<p data-start=\"2749\" data-end=\"2886\">We may update this Privacy Policy occasionally. The most recent version will always be available upon request or posted on our website.</p>\r\n</li>\r\n</ul>\r\n</li>\r\n</ol>', '2022-09-20 08:23:13', '2025-10-07 04:31:19');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `designation`, `image`, `rating`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(2, 'John Doe', 'CEO', 'uploads/custom-images/john-doe-20221110031511.jpg', '5', 'As a digital marketing agency our team works day in and day out on websites of all kinds. Some of the most common errors we see are websites not optimized for SEO because of old, boring, or out of date website themes or designs.', 1, NULL, '2022-11-10 20:15:12'),
(3, 'Michael', 'CEO', 'uploads/custom-images/michael-20230323025551.png', '5', 'Almost every imaginable design is possible and customizations are allowed on every level. Some features could make use of better controls. If you know how to operate your mouse, then you are all set to use this pagebuilder.', 1, '2022-07-31 10:57:20', '2023-03-23 08:55:52'),
(5, 'James Robert', 'CEO', 'uploads/custom-images/james-robert-20230323025559.png', '5', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2022-09-25 09:12:27', '2023-03-23 08:56:00'),
(6, 'Mary 	Patricia', 'CEO', 'uploads/custom-images/mary-patricia-20220925031348.png', '5', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English', 1, '2022-09-25 09:13:48', '2022-09-25 09:13:48');

-- --------------------------------------------------------

--
-- Table structure for table `three_column_categories`
--

CREATE TABLE `three_column_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id_one` int NOT NULL DEFAULT '0',
  `sub_category_id_one` int NOT NULL DEFAULT '0',
  `child_category_id_one` int NOT NULL DEFAULT '0',
  `category_id_two` int NOT NULL DEFAULT '0',
  `sub_category_id_two` int NOT NULL DEFAULT '0',
  `child_category_id_two` int NOT NULL DEFAULT '0',
  `category_id_three` int NOT NULL DEFAULT '0',
  `sub_category_id_three` int NOT NULL DEFAULT '0',
  `child_category_id_three` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `three_column_categories`
--

INSERT INTO `three_column_categories` (`id`, `category_id_one`, `sub_category_id_one`, `child_category_id_one`, `category_id_two`, `sub_category_id_two`, `child_category_id_two`, `category_id_three`, `sub_category_id_three`, `child_category_id_three`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, 2, 0, 0, 5, 0, 0, NULL, '2022-02-07 03:59:38');

-- --------------------------------------------------------

--
-- Table structure for table `twilio_sms`
--

CREATE TABLE `twilio_sms` (
  `id` bigint UNSIGNED NOT NULL,
  `account_sid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `twilio_phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `enable_register_sms` int NOT NULL DEFAULT '0',
  `enable_reset_pass_sms` int NOT NULL DEFAULT '0',
  `enable_order_confirmation_sms` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `twilio_sms`
--

INSERT INTO `twilio_sms` (`id`, `account_sid`, `auth_token`, `twilio_phone_number`, `enable_register_sms`, `enable_reset_pass_sms`, `enable_order_confirmation_sms`, `created_at`, `updated_at`) VALUES
(1, 'ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', 'REPLACE_ME', '+10000000000', 1, 1, 1, NULL, '2023-01-11 11:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forget_password_token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `provider_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int DEFAULT '0',
  `state_id` int DEFAULT '0',
  `city_id` int DEFAULT '0',
  `zip_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_vendor` int NOT NULL DEFAULT '0',
  `verify_token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified` int NOT NULL DEFAULT '0',
  `agree_policy` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `forget_password_token`, `status`, `provider_id`, `provider`, `provider_avatar`, `image`, `phone`, `country_id`, `state_id`, `city_id`, `zip_code`, `address`, `is_vendor`, `verify_token`, `email_verified`, `agree_policy`, `created_at`, `updated_at`) VALUES
(4, 'Autumn Decker Dean', 'seller7@gmail.com', NULL, '$2y$10$B3aOUPcMKFQUKR9af/taSei07iAEaiT2TaaWaofuM4cMuspsIffH.', 'Ou5C4kwC0iMZ7K2nAP7m2mZulHnXvIhz5j3VAaWNxboVpCbwFcUVPpPP8DXX', NULL, 1, NULL, NULL, NULL, NULL, '985-458-1254', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-09-21 05:33:41', '2022-09-21 05:39:16'),
(5, 'Jayden Carter', 'user5@gmail.com', NULL, '$2y$10$fLfrcsXAEO4Ppl.RzHEj2ObbdSBKKgy8XC3kPDIjE/Fqp.yRnCqea', NULL, NULL, 1, NULL, NULL, NULL, NULL, '236-451-7458', 0, 0, 0, NULL, 'qww', 0, NULL, 1, 1, '2022-09-21 05:40:21', '2022-09-22 04:19:00'),
(6, 'David Leo', 'user3@gmail.com', NULL, '$2y$10$97EjXmtnALzk8asIK7c92exVWTjNqiVZY0i63WLgTpiltIxcej66a', NULL, NULL, 1, NULL, NULL, NULL, 'uploads/custom-images/nayeem-2022-11-13-10-12-58-9722.jpg', '874-512-4589', 0, 0, 0, NULL, 'catsle', 0, NULL, 1, 1, '2022-09-21 06:48:34', '2022-11-13 15:12:58'),
(7, 'John Doe', 'user@gmail.com', NULL, '$2y$10$slQDXnVvhpogMQB3OyqAeeIEBYNCS7pwN8rVHlYsSDv/nA3tIhEDu', 'Q8ZIbDCY9pwnZCVAIUA3c0YP8B6g4bOfxP9cZprlVxQ94H2rX9Z3Ex8Bpts0', '351184', 1, NULL, NULL, NULL, 'uploads/custom-images/abdullah-mamunn-2023-03-23-12-54-59-5792.jpg', '12345690', 2, 4, 8, NULL, 'Mirpur 11', 0, NULL, 1, 1, '2022-09-21 07:08:27', '2023-03-26 09:12:48'),
(8, 'Harding Rosa', 'seller5@gmail.com', NULL, '$2y$10$.Z7BHjmwrhswRQA1/SDRUubKwZDTtFU8GK9ZGbkxprsMCDWBycSXC', NULL, NULL, 1, NULL, NULL, NULL, NULL, '215-365-6452', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-09-21 07:11:54', '2022-09-21 07:12:14'),
(9, 'Nasim Horne', 'seller4@gmail.com', NULL, '$2y$10$fSYHi6.oqhxsPYKZA1aASOA3YbujGYabkkoE/w/G3kKBoSRqzO2TG', NULL, NULL, 1, NULL, NULL, NULL, NULL, '236-458-7851', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-09-21 07:17:37', '2022-09-21 07:18:01'),
(10, 'Xaviera Barnes', 'seller2@gmail.com', NULL, '$2y$10$W0ffeYoOKWHml0SiziQ7uuB0WjbSkf3hbNuuwwmNi5ZFxD/ybSD4W', NULL, NULL, 1, NULL, NULL, NULL, NULL, '123-452-5662', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-09-21 07:21:41', '2022-09-21 07:22:03'),
(12, 'Kelsey Conrad', 'seller@gmail.com', NULL, '$2y$10$UuvKnwLpoTsQbJlryYW6gOuaOmrAh.xKqprtMDrhiLGlTmFhRVpTm', NULL, NULL, 1, NULL, NULL, NULL, NULL, '321-584-7584', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-09-21 07:26:08', '2022-09-21 07:27:50'),
(19, 'Elias Charles', 'seller1@gmail.com', NULL, '$2y$10$UuvKnwLpoTsQbJlryYW6gOuaOmrAh.xKqprtMDrhiLGlTmFhRVpTm', NULL, NULL, 1, NULL, NULL, NULL, NULL, '362-478-5412', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-10-27 15:07:06', '2022-10-27 15:11:50'),
(30, 'Lucas Benjamin', 'user4@gmail.com', NULL, '$2y$10$ce8fRc1NAK//dp4fOWyTeOb8n5jkw3DBKZ8fnZlogWT4Lamx1rtUm', NULL, '890243', 1, NULL, NULL, NULL, NULL, '122-452-1452', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-11-10 22:50:51', '2022-11-10 22:53:45'),
(31, 'Mohammad Ali', 'msalisapan@gmail.com', NULL, '$2y$10$xGD4/MDwPGXQlCIRDOObH.DyyY9qPtKzTqcTskNWeKpELq/zeYnWa', NULL, '996248', 1, NULL, NULL, NULL, 'uploads/custom-images/mohammad-ali-2022-11-15-10-12-21-8139.jpg', '01624188877', 1, 1, 2, NULL, 'Florida, USA', 0, NULL, 1, 1, '2022-11-15 14:57:56', '2022-11-17 17:06:19'),
(32, 'Ali', 'sapanmsc@gmail.com', NULL, '$2y$10$MQGGPTLnqU9Z21gpMlf9fuWdQTYU98fSxd4pLLKHTCf4l9YT1f/6G', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-11-17 22:06:51', '2022-11-17 22:07:21'),
(34, 'Rafiqul Islam', 'sokeh55414@rubeshi.com', NULL, '$2y$10$/GpwZbcrq17GuXzo4hAxeOTtHHNk55nvhIsX6eRpccAZnq/ZsuAoG', NULL, NULL, 1, NULL, NULL, NULL, NULL, '017777777777', 4, 7, 11, NULL, 'Dhaka,Bangladesh', 0, NULL, 1, 1, '2022-11-30 15:29:50', '2022-11-30 15:51:54'),
(35, 'Abir', 'bipexoh854@kuvasin.com', NULL, '$2y$10$Lwf7VLLCZ1ES/7K18WusWOZHN7Zz5yyjIYHO1Z93Z7ROLV2FwC9o6', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-11-30 17:48:04', '2022-11-30 17:48:49'),
(37, 'Mamun', 'xercurr@gmail.com', NULL, '$2y$10$gEC6k8pVbXNFcDvLHis8NeEO2ojcnAnl6a.CL87vFyVKBBdDNDXFm', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-12-01 23:11:22', '2022-12-01 23:12:16'),
(38, 'abdur', 'abdur.rohman2003@gmail.com', NULL, '$2y$10$Am3JCsTh5bK4Mt.z7YKJ/e2v8m.q20leFLA1LN7OBV29Jf9ebKm.y', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2022-12-02 16:02:42', '2022-12-02 16:03:00'),
(39, 'Shuvo khan', 'sediced375@ceoshub.com', NULL, '$2y$10$20UteaRWi7xUVXQSw1SZwOjVpfBFbakm9Vr6FP6tR9OCMQxKOy32C', NULL, NULL, 1, NULL, NULL, NULL, 'uploads/custom-images/shuvo-khan-2022-12-06-11-24-43-1210.jpg', '01777777777', 2, 4, 8, NULL, 'Dhaka,bangladesh', 0, NULL, 1, 1, '2022-12-06 16:17:23', '2022-12-06 16:24:43'),
(40, 'Rafiqul  Islam', 'lenoya1453@dmonies.com', NULL, '$2y$10$13q/OCDiGBucG5cWrjqAmu/hqQCwygGN8lu9h2Pcdg7cINUC0U4Vi', NULL, NULL, 1, NULL, NULL, NULL, 'uploads/custom-images/rafiqul-islam-2022-12-06-03-32-27-8841.jpg', '01777777777', 2, 5, 9, NULL, 'dhaka,bangladesh', 0, NULL, 1, 1, '2022-12-06 20:31:08', '2022-12-20 21:22:14'),
(41, 'Abrahm Linkon', 'gajec29194@tingn.com', NULL, '$2y$10$AWB7oV1hqatMLYvSoMy1IOd387hfVnNRQCiC7xxxQK4pb.Wb0sRAO', NULL, '820126', 1, NULL, NULL, NULL, NULL, '1236547888', 2, 4, 8, NULL, 'Kolkata', 0, NULL, 1, 1, '2023-01-15 16:40:44', '2023-01-17 13:12:50'),
(42, 'Test Name', 'togote8190@v3dev.com', NULL, '$2y$10$sChkUNHvR5KMFhw4mXgrOeIfpXKiyqLV5nq5a2X5yfqbKeQ9Hn2oS', NULL, '945406', 1, NULL, NULL, NULL, NULL, '123697845', 2, 4, 8, NULL, 'Kolkata', 0, NULL, 1, 1, '2023-01-16 15:36:44', '2023-01-16 22:25:24'),
(43, 'AJOY Sarker', 'ajoy.quomodosoft@gmail.com', NULL, '$2y$10$.8ILCxDgqJu088IFRdDOb.ofLB8.RmXMyzn/V9dWUFY0B.JcbsPyu', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2023-01-21 22:50:05', '2023-01-21 22:50:27'),
(46, 'Suvo khan', 'mawar31950@asoflex.com', NULL, '$2y$10$Qby4OWcU.tJK2/EURSFmouMcqZgAvzwVoa4WbCYfbKE8/K4svnqxW', NULL, NULL, 1, NULL, NULL, NULL, 'uploads/custom-images/suvo-khan-2023-03-23-04-28-47-1729.jpg', '+8801642222090', 0, 0, 0, NULL, 'null', 0, NULL, 1, 1, '2023-03-23 10:08:48', '2023-03-23 10:28:48'),
(47, 'Suvo khan', 'jolod73079@asoflex.com', NULL, '$2y$10$9sgHJJnLA.9ZZpB3YUO09OKuUli1Mfb6B6ZQG4.3HxMd3dMzylfBe', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+8801792166627', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2023-03-23 10:29:37', '2023-03-23 10:29:51'),
(51, 'ibrahim khalil', 'khalil.cmt.bpi@gmail.com', NULL, '$2y$10$DbsQSTucIOTCtHollR70g.dD1gua1fb0Cvh7npoxU1I5tPoDt9yrS', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+8801310118550', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2023-03-23 10:58:48', '2023-03-23 10:59:19'),
(52, 'Ali', 'jerame5720@necktai.com', NULL, '$2y$10$2u0Ti6fwhHln30IiFq8LNOlxIvTL4.mX0I2MQuIK992PFcFopTBsG', NULL, NULL, 0, NULL, NULL, NULL, NULL, '01776837364', 0, 0, 0, NULL, NULL, 0, '322163', 0, 1, '2023-03-26 09:45:28', '2023-03-26 09:45:28'),
(53, 'test test', 'dojot15193@mitigado.com', NULL, '$2y$10$GgQ/2YwwsitfSufslFc7Ye28Z3ftm9d7a5cX7JmfHCb2oCyB7wvFS', NULL, NULL, 0, NULL, NULL, NULL, NULL, '+8801704782586', 0, 0, 0, NULL, NULL, 0, '598893', 0, 1, '2023-04-04 03:54:19', '2023-04-04 03:54:19'),
(54, 'test test', 'pagateh183@mitigado.com', NULL, '$2y$10$jiqlYOvu.1lF0THCdicLk.6ymxlpR2egt1uUw3cGyEh.UXxNHx4Pa', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+8801885208429', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2023-04-04 04:27:47', '2023-04-04 04:32:24'),
(55, 'test2 test2', 'rifaman890@jthoven.com', NULL, '$2y$10$Zz/NP2j7wEH4GXir3EE1N.6XfCWym8W.Gnqlnm/BGF4443Fs0XXKa', NULL, NULL, 0, NULL, NULL, NULL, NULL, '+8801777777777', 0, 0, 0, NULL, NULL, 0, '348401', 0, 1, '2023-04-04 04:39:20', '2023-04-04 04:39:20'),
(56, 'test3 test3', 'cahoyec843@jthoven.com', NULL, '$2y$10$EjKjbh4bwrUT46mD85wDJuu5f7PDr.Kn3IH0FQZ2SDhkwpSwi/fQa', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+8801888888888', 0, 0, 0, NULL, NULL, 0, NULL, 1, 1, '2023-04-04 04:41:36', '2023-04-04 04:41:55'),
(57, 'qusacog@mailinator.com', 'lenij@mailinator.com', NULL, '$2y$10$l8rm5u6/1.YleAB1nnMrieS7AyCkpZ4UcN.FVq.BXc/ob1kdj3T1G', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (256) 165-4855', 5, NULL, 5, NULL, 'sadfsaf', 0, NULL, 0, 0, '2025-08-24 05:27:41', '2025-08-24 05:27:41'),
(58, 'imran', 'sajidimran95@gmail.com', '2025-08-26 10:17:05', '$2y$10$J8gmnDncXOg57qaFn1dTQu3mIxp6lhD.HAbMITyuTBNgTJAqKJ7vi', NULL, NULL, 1, NULL, NULL, NULL, NULL, '01863412271', 0, 0, 0, NULL, NULL, 0, '518891', 1, 1, '2025-08-26 03:50:44', '2025-08-26 04:14:06'),
(59, 'mkhsojib', 'mkhsojib@gmail.com', '2025-08-27 00:17:38', '$2y$10$bgyU3l.nViEtmaCMF6o9t.17VWbmiLHgseisfqfN45hSRkJT.qbqG', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (346) 432-8913', 0, 0, 0, NULL, NULL, 0, NULL, 0, 1, '2025-08-26 23:34:05', '2025-08-27 05:40:25'),
(60, 'Amber Coffey', 'bunorizuke@mailinator.com', NULL, '$2y$10$wQHOukZv.ukHDHK/wBEi9ecsPLcnTY.pgxdMJUJbDvHtXeNoY8qMS', NULL, NULL, 0, NULL, NULL, NULL, NULL, '+1 (788) 762-1384', 0, 0, 0, NULL, NULL, 0, NULL, 0, 1, '2025-08-26 23:34:57', '2025-08-26 23:34:57'),
(61, 'Nissim Garza', 'tyridisil@mailinator.com', '2025-08-26 23:37:07', '$2y$10$JgBPSGMMvDuPhaHX0HpNDOsK0VcNu448L..SoYUxZorfjgTg.ICQu', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (278) 674-5873', 0, 0, 0, NULL, NULL, 0, NULL, 0, 1, '2025-08-26 23:36:55', '2025-08-26 23:37:07'),
(62, 'Shana Richard', 'vezyjej@mailinator.com', '2025-08-30 03:45:32', '$2y$10$gQR/E21ZAa7MzNeQtU4Wfel5kWTwkk7QlDEnrj/vVH7Up8ojTsWLK', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (304) 502-6618', 0, 0, 0, NULL, NULL, 0, NULL, 0, 1, '2025-08-30 03:45:18', '2025-08-30 03:45:32'),
(63, 'Thor Andrews', 'hohuna@mailinator.com', '2025-08-30 03:48:50', '$2y$10$x1YKzAFbY5nOOa5rs.N7IeF9KJA8ZTxxYma8ZyFq4P4K.iciB5BF2', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (348) 323-8701', 0, 0, 0, NULL, NULL, 0, NULL, 0, 1, '2025-08-30 03:48:42', '2025-08-30 03:48:50'),
(64, 'Bree Pitts', 'qogedike@mailinator.com', NULL, '$2y$10$SUb.KBwPviC5YWYxNh7EOO/oLNoZfUT7zasAQxnWYMpL/xhbaA3tO', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 0, 0, '2025-08-30 05:30:02', '2025-08-30 05:30:02'),
(65, 'Chava Phillips', 'wynafolija@mailinator.com', NULL, '$2y$10$AH.3YmaaejlZSme2NvCFeO.9vA9b2JdwMbsGnVLhtBtfPD472KpM.', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 0, 0, '2025-08-30 05:35:07', '2025-08-30 05:35:07'),
(66, 'Nerea Schneider', 'jurytapo@mailinator.com', NULL, '$2y$10$0JYwySNFTqR9nlmhZjoZjeJ//7Vd2XLrvkR9YK4pRs45MPkwWoNzu', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, 0, NULL, 0, 0, '2025-08-30 05:37:07', '2025-08-30 05:37:07'),
(67, 'Guest User', 'soqatet@mailinator.com', NULL, '$2y$10$0mcJ2XxdqWAwoONMvSyrj.18lcNhk80Nl1xBN/d5mfrTwwuYXwwwC', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (299) 171-1671', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-09 00:54:26', '2025-09-09 00:54:26'),
(68, 'Guest User', 'lezyryge@mailinator.com', NULL, '$2y$10$ht0F9bsJoJrGRYMBVMXUEuRRXurXuBcvOp8kBdKlR2UXe6tjhj6ZG', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (875) 318-5512', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-09 01:00:53', '2025-09-09 01:00:53'),
(69, 'Guest User', 'totu@mailinator.com', NULL, '$2y$10$XDGpo8LJD2HbkytfspQ95OzxC2HrbJahVee.b5l0B.VD7dvB8iMPu', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (719) 499-2953', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-09 01:01:12', '2025-09-09 01:01:12'),
(70, 'Guest User', 'kazyza@mailinator.com', NULL, '$2y$10$KEyZr2bA3xNSQh1z3lD0CektpGUOkvZ8UcGYaAfElQnmYxJVSdzv6', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (852) 784-4411', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-09 01:03:38', '2025-09-09 01:03:38'),
(71, 'Guest User', 'zizu@mailinator.com', NULL, '$2y$10$3Sft4aAWZzd2aPXUWitAdurY6TLSL1EaazdMi8pNyfj9AVRkeetEe', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (464) 759-6295', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-09 01:15:50', '2025-09-09 01:15:50'),
(72, 'Guest User', 'xubet@mailinator.com', NULL, '$2y$10$ami9IKv00vokacaqY/RbW.ca.5hxMAv9mp5dXZm1RXtwDRnALSY6O', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (868) 996-7193', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-09 01:25:17', '2025-09-09 01:25:17'),
(73, 'Guest User', 'nysimirele@mailinator.com', NULL, '$2y$10$leQ3pO9CPZ3qTsuULaowLuoSAs2729jcif0vEePyLO.aaelzJZQpG', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (563) 552-9843', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-09 01:48:47', '2025-09-09 01:48:47'),
(74, 'Guest User', 'tygiva@mailinator.com', NULL, '$2y$10$bm3Ip7XcfJn33qHj9vC7RuXQCUdhROiAYaJh6/drRJLq1LHKZqbk2', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (812) 572-5544', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-09 03:03:26', '2025-09-09 03:03:26'),
(75, 'Guest User', 'cubydyge@mailinator.com', NULL, '$2y$10$ZEcpv9YLTClOU2OOGtmccO2aLr9fc9REhq8ElrPFYXQYPG5OC0PA.', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (688) 509-9489', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-15 23:48:33', '2025-09-15 23:48:33'),
(76, 'Troy Head', 'jyfabyhiro@mailinator.com', NULL, '$2y$10$NmJN/V1.VHrV9ZhAkWWW.O7R4SFtvtsI75PofIpY59lDoBwHmPqcW', NULL, NULL, 0, NULL, NULL, NULL, NULL, '+1 (116) 491-6479', 0, 0, 0, NULL, NULL, 0, NULL, 0, 1, '2025-09-20 03:54:53', '2025-09-20 03:54:53'),
(77, 'Guest User', 'gokymajy@mailinator.com', NULL, '$2y$10$b3bRMUyXdpqODwoWfb0eXe6pN.c7sY6Irf/CvHsEQFEMDejl7zuny', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (747) 681-4959', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-23 05:41:24', '2025-09-23 05:41:24'),
(78, 'Guest User', 'vakuxabivy@mailinator.com', NULL, '$2y$10$gzlMlTlTGcajRns2.ylkkOzdV7v5Jo5ulDDXhMojEuQNByH3iJO1m', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (673) 879-9231', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-23 05:42:50', '2025-09-23 05:42:50'),
(79, 'Guest User', 'kefahoqule@mailinator.com', NULL, '$2y$10$AOiWLpv/.QbyzEvuyHcjqez6GGj2d4jXpaawFOOX7MDB7cypRUuWu', NULL, NULL, 1, NULL, NULL, NULL, NULL, '+1 (646) 334-6224', 0, 0, 0, NULL, NULL, 0, NULL, 1, 0, '2025-09-27 00:13:06', '2025-09-27 00:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` double NOT NULL DEFAULT '0',
  `banner_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `open_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closed_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '0',
  `is_featured` int NOT NULL DEFAULT '0',
  `top_rated` int NOT NULL DEFAULT '0',
  `verified_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` int NOT NULL DEFAULT '0',
  `greeting_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `user_id`, `total_amount`, `banner_image`, `logo`, `phone`, `email`, `shop_name`, `slug`, `open_at`, `closed_at`, `address`, `seo_title`, `seo_description`, `status`, `is_featured`, `top_rated`, `verified_token`, `is_verified`, `greeting_msg`, `created_at`, `updated_at`) VALUES
(1, 4, 0, 'uploads/custom-images/seller-banner-2022-09-21-11-58-14-6681.png', 'uploads/custom-images/seller-logo-2022-09-21-11-58-14-9795.png', '01792166627', 'jipof28122@orlydns.com', 'Shopno BD', 'shopno-bd', '10.00AM', '10.00PM', 'Dhaka,Bangladesh', 'Shopno BD', 'Shopno BD', 1, 0, 0, NULL, 0, 'Welcome to Shopno BD', '2022-09-21 05:58:14', '2022-09-21 05:58:32'),
(2, 7, 0, 'uploads/custom-images/seller-banner-2022-09-21-01-10-12-1265.png', 'uploads/custom-images/seller-logo-2022-09-21-01-10-12-2682.png', '01792166627', 'hijigov511@pahed.com', 'Eecoms Shop', 'eecoms-shop', '10.00AM', '10.00PM', 'Dhaka,Bangladesh', 'Eecoms Shop', 'Eecoms Shop', 1, 0, 0, NULL, 0, 'Welcome to Eecoms Shop', '2022-09-21 07:10:12', '2022-09-21 07:10:21'),
(3, 8, 0, 'uploads/custom-images/seller-banner-2022-09-21-01-16-13-9611.png', 'uploads/custom-images/seller-logo-2022-09-21-01-16-13-5847.png', '01792166627', 'fegig12897@ploneix.com', 'Fusion X', 'fusion-x', '10.00AM', '10.00PM', 'Dhaka,Bangladesh', 'Fusion X', 'Fusion X', 1, 0, 0, NULL, 0, 'Welcome to Fusion X', '2022-09-21 07:16:13', '2022-09-21 07:16:18'),
(4, 9, 0, 'uploads/custom-images/seller-banner-2022-09-21-01-19-19-5022.png', 'uploads/custom-images/seller-logo-2022-09-21-01-19-19-3916.png', '01792166627', 'wokij22557@geekjun.com', 'Rikayi Rox', 'rikayi-rox', '10.00AM', '10.00PM', 'Dhaka,Bangladesh', 'Rikayi Rox', 'Rikayi Rox', 1, 0, 0, NULL, 0, 'Welcome to Rikayi Rox', '2022-09-21 07:19:19', '2022-09-21 07:19:24'),
(5, 10, 0, 'uploads/custom-images/seller-banner-2022-09-21-01-22-55-5481.png', 'uploads/custom-images/seller-logo-2022-09-21-01-22-55-6660.png', '01792166627', 'rokayo4006@pahed.com', 'Habbriyi', 'habbriyi', '10.00AM', '10.00PM', 'Dhaka,Bangladesh', 'Habbriyi', 'Habbriyi', 1, 0, 0, NULL, 0, 'Welcome to Habbriyi', '2022-09-21 07:22:55', '2022-09-21 07:23:06'),
(6, 12, 0, 'uploads/custom-images/seller-banner-2022-09-21-01-30-45-9893.png', 'uploads/custom-images/seller-logo-2022-09-21-01-30-46-9044.png', '01792166627', 'tofor67338@geekjun.com', 'Rayhans', 'rayhans', '10.00AM', '10.00PM', 'Dhaka,Bangladesh', 'Rayhans', 'Rayhans', 1, 0, 0, NULL, 0, 'Welcome to Rayhans', '2022-09-21 07:30:46', '2022-09-21 07:30:53'),
(7, 19, 0, 'uploads/custom-images/seller-banner-2022-10-27-11-21-49-7313.png', 'uploads/custom-images/seller-logo-2022-10-27-11-21-49-8956.png', '01792166627', 'cacixi5247@corylan.com', 'mexuvo', 'mexuvo', '10.00AM', '10.00PM', 'Farmgate,Dhaka,Bangladesh', 'mexuvo', 'mexuvo', 1, 0, 0, NULL, 0, 'Welcome to mexuvo', '2022-10-27 15:21:49', '2022-10-27 15:22:14'),
(8, 64, 0, NULL, NULL, '+1 (289) 124-8314', 'nuhixub@mailinator.com', 'Isaiah Garrett', 'isaiah-garrett', '20:31', '06:57', 'Vel nostrum irure pr', NULL, NULL, 1, 0, 0, 'Yq1hm1g2VhqKHNDnTmQwjkPiLAdlUIbKfbiDfoYAhrKX1jIgXx93Pl02SyYyby2nmXJ9tPjYmTQOvQOFPMqcy7xehisteDjMqEQz', 0, NULL, '2025-08-30 05:30:02', '2025-08-30 05:32:12'),
(9, 65, 0, NULL, NULL, '+1 (508) 757-3627', 'zabokigi@mailinator.com', 'Lani Rocha', 'lani-rocha', '14:45', '15:16', 'Eiusmod incidunt im', NULL, NULL, 0, 0, 0, 'rkwWNjeRf4EUDNhuPXfWnB8fUBL5joBIBiFFxwuuVq8YJseeFcdwYy0WOsHw7mkEEANALbgXnUJJtDNJdw1EPez7qm5D8HvymQCF', 0, NULL, '2025-08-30 05:35:07', '2025-08-30 05:35:07'),
(10, 66, 0, NULL, NULL, '+1 (814) 795-8973', 'nymu@mailinator.com', 'Sylvester Chambers', 'sylvester-chambers', '05:11', '22:59', 'Et nulla est sit qui', NULL, NULL, 0, 0, 0, 'tiygIuH4SQiWW0IcxgZJr5gzCV10tonMOwfz7I6R69LU2izf5Kqlr5GRxiYZnlrjagCmD03dz7ADfOOe1zT2YvmEz0R2nEaJkSGC', 0, NULL, '2025-08-30 05:37:07', '2025-08-30 05:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_social_links`
--

CREATE TABLE `vendor_social_links` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` int NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(54, 30, 36, '2022-11-10 23:01:26', '2022-11-10 23:01:26'),
(55, 30, 8, '2022-11-10 23:01:29', '2022-11-10 23:01:29'),
(56, 30, 37, '2022-11-10 23:02:07', '2022-11-10 23:02:07'),
(212, 37, 38, '2022-12-01 23:13:04', '2022-12-01 23:13:04'),
(326, 43, 38, '2023-01-21 22:53:38', '2023-01-21 22:53:38'),
(327, 43, 29, '2023-01-21 23:05:02', '2023-01-21 23:05:02'),
(355, 7, 37, '2023-05-27 10:25:16', '2023-05-27 10:25:16'),
(356, 7, 36, '2023-05-27 10:25:17', '2023-05-27 10:25:17'),
(357, 7, 33, '2023-05-27 10:25:18', '2023-05-27 10:25:18'),
(358, 7, 29, '2023-05-27 10:34:40', '2023-05-27 10:34:40'),
(359, 7, 9, '2023-05-28 04:23:43', '2023-05-28 04:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_amount` double NOT NULL DEFAULT '0',
  `max_amount` double NOT NULL DEFAULT '0',
  `withdraw_charge` double NOT NULL DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdraw_methods`
--

INSERT INTO `withdraw_methods` (`id`, `name`, `min_amount`, `max_amount`, `withdraw_charge`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bank Payment', 500, 1500, 5, '<p>Please provide your Bank Account information :&nbsp;</p><p><span style=\"background-color: transparent;\">Bank Name: Your bank name</span></p><p><span style=\"background-color: transparent;\">Account Number:&nbsp; Your bank account number</span></p><p>Routing Number: Your bank routing number</p><p>Branch: Your bank branch name</p>', 1, '2022-11-10 19:41:15', '2022-11-10 19:41:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `announcement_modals`
--
ALTER TABLE `announcement_modals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_payments`
--
ALTER TABLE `bank_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_images`
--
ALTER TABLE `banner_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `breadcrumb_images`
--
ALTER TABLE `breadcrumb_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `child_categories`
--
ALTER TABLE `child_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `compare_products`
--
ALTER TABLE `compare_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_pages`
--
ALTER TABLE `contact_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cookie_consents`
--
ALTER TABLE `cookie_consents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country_states`
--
ALTER TABLE `country_states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency_countries`
--
ALTER TABLE `currency_countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `custom_pages`
--
ALTER TABLE `custom_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_paginations`
--
ALTER TABLE `custom_paginations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_configurations`
--
ALTER TABLE `email_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `error_pages`
--
ALTER TABLE `error_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facebook_comments`
--
ALTER TABLE `facebook_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facebook_pixels`
--
ALTER TABLE `facebook_pixels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `featured_categories`
--
ALTER TABLE `featured_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flash_sales`
--
ALTER TABLE `flash_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flash_sale_products`
--
ALTER TABLE `flash_sale_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flutterwaves`
--
ALTER TABLE `flutterwaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footers`
--
ALTER TABLE `footers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_links`
--
ALTER TABLE `footer_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_social_links`
--
ALTER TABLE `footer_social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `google_analytics`
--
ALTER TABLE `google_analytics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `google_recaptchas`
--
ALTER TABLE `google_recaptchas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_page_one_visibilities`
--
ALTER TABLE `home_page_one_visibilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instamojo_payments`
--
ALTER TABLE `instamojo_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintainance_texts`
--
ALTER TABLE `maintainance_texts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mega_menu_categories`
--
ALTER TABLE `mega_menu_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mega_menu_sub_categories`
--
ALTER TABLE `mega_menu_sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_visibilities`
--
ALTER TABLE `menu_visibilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_addresses`
--
ALTER TABLE `order_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_product_variants`
--
ALTER TABLE `order_product_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `paymongo_payments`
--
ALTER TABLE `paymongo_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paystack_and_mollies`
--
ALTER TABLE `paystack_and_mollies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `popular_categories`
--
ALTER TABLE `popular_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `popular_posts`
--
ALTER TABLE `popular_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_galleries`
--
ALTER TABLE `product_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reports`
--
ALTER TABLE `product_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_specification_keys`
--
ALTER TABLE `product_specification_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variant_items`
--
ALTER TABLE `product_variant_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pusher_credentails`
--
ALTER TABLE `pusher_credentails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `razorpay_payments`
--
ALTER TABLE `razorpay_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_mail_logs`
--
ALTER TABLE `seller_mail_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_withdraws`
--
ALTER TABLE `seller_withdraws`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seo_settings`
--
ALTER TABLE `seo_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shippings`
--
ALTER TABLE `shippings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shopping_cart_variants`
--
ALTER TABLE `shopping_cart_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_pages`
--
ALTER TABLE `shop_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_templates`
--
ALTER TABLE `sms_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_login_information`
--
ALTER TABLE `social_login_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sslcommerz_payments`
--
ALTER TABLE `sslcommerz_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stripe_payments`
--
ALTER TABLE `stripe_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tawk_chats`
--
ALTER TABLE `tawk_chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `three_column_categories`
--
ALTER TABLE `three_column_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `twilio_sms`
--
ALTER TABLE `twilio_sms`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `vendor_social_links`
--
ALTER TABLE `vendor_social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `announcement_modals`
--
ALTER TABLE `announcement_modals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_payments`
--
ALTER TABLE `bank_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banner_images`
--
ALTER TABLE `banner_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `breadcrumb_images`
--
ALTER TABLE `breadcrumb_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `child_categories`
--
ALTER TABLE `child_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `compare_products`
--
ALTER TABLE `compare_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_pages`
--
ALTER TABLE `contact_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cookie_consents`
--
ALTER TABLE `cookie_consents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `country_states`
--
ALTER TABLE `country_states`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `currency_countries`
--
ALTER TABLE `currency_countries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `custom_pages`
--
ALTER TABLE `custom_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `custom_paginations`
--
ALTER TABLE `custom_paginations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `email_configurations`
--
ALTER TABLE `email_configurations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `error_pages`
--
ALTER TABLE `error_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `facebook_comments`
--
ALTER TABLE `facebook_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facebook_pixels`
--
ALTER TABLE `facebook_pixels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `featured_categories`
--
ALTER TABLE `featured_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `flash_sales`
--
ALTER TABLE `flash_sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `flash_sale_products`
--
ALTER TABLE `flash_sale_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `flutterwaves`
--
ALTER TABLE `flutterwaves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `footers`
--
ALTER TABLE `footers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `footer_links`
--
ALTER TABLE `footer_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `footer_social_links`
--
ALTER TABLE `footer_social_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `google_analytics`
--
ALTER TABLE `google_analytics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `google_recaptchas`
--
ALTER TABLE `google_recaptchas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `home_page_one_visibilities`
--
ALTER TABLE `home_page_one_visibilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `instamojo_payments`
--
ALTER TABLE `instamojo_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `maintainance_texts`
--
ALTER TABLE `maintainance_texts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mega_menu_categories`
--
ALTER TABLE `mega_menu_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mega_menu_sub_categories`
--
ALTER TABLE `mega_menu_sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `menu_visibilities`
--
ALTER TABLE `menu_visibilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `order_addresses`
--
ALTER TABLE `order_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=329;

--
-- AUTO_INCREMENT for table `order_product_variants`
--
ALTER TABLE `order_product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `paymongo_payments`
--
ALTER TABLE `paymongo_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `paystack_and_mollies`
--
ALTER TABLE `paystack_and_mollies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `popular_categories`
--
ALTER TABLE `popular_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `popular_posts`
--
ALTER TABLE `popular_posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `product_galleries`
--
ALTER TABLE `product_galleries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `product_reports`
--
ALTER TABLE `product_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_specification_keys`
--
ALTER TABLE `product_specification_keys`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_variant_items`
--
ALTER TABLE `product_variant_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pusher_credentails`
--
ALTER TABLE `pusher_credentails`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `razorpay_payments`
--
ALTER TABLE `razorpay_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seller_mail_logs`
--
ALTER TABLE `seller_mail_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_withdraws`
--
ALTER TABLE `seller_withdraws`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seo_settings`
--
ALTER TABLE `seo_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=895;

--
-- AUTO_INCREMENT for table `shopping_cart_variants`
--
ALTER TABLE `shopping_cart_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `shop_pages`
--
ALTER TABLE `shop_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sms_templates`
--
ALTER TABLE `sms_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `social_login_information`
--
ALTER TABLE `social_login_information`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sslcommerz_payments`
--
ALTER TABLE `sslcommerz_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stripe_payments`
--
ALTER TABLE `stripe_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tawk_chats`
--
ALTER TABLE `tawk_chats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `three_column_categories`
--
ALTER TABLE `three_column_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `twilio_sms`
--
ALTER TABLE `twilio_sms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `vendor_social_links`
--
ALTER TABLE `vendor_social_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=365;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
