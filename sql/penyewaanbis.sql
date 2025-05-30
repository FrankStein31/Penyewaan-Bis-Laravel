/*
SQLyog Enterprise
MySQL - 8.0.30 : Database - penyewaanbis
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`penyewaanbis` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `penyewaanbis`;

/*Table structure for table `armada` */

DROP TABLE IF EXISTS `armada`;

CREATE TABLE `armada` (
  `armada_id` int NOT NULL AUTO_INCREMENT,
  `nama_armada` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`armada_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `armada` */

insert  into `armada`(`armada_id`,`nama_armada`) values 
(1,'A1'),
(2,'B1'),
(5,'A3');

/*Table structure for table `buses` */

DROP TABLE IF EXISTS `buses`;

CREATE TABLE `buses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `armada_id` int DEFAULT NULL,
  `plate_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('long','short') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL,
  `price_per_day` decimal(12,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('tersedia','disewa','maintenance') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tersedia',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `buses_plate_number_unique` (`plate_number`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `buses` */

insert  into `buses`(`id`,`armada_id`,`plate_number`,`type`,`capacity`,`price_per_day`,`description`,`image`,`status`,`is_active`,`created_at`,`updated_at`) values 
(5,1,'AG 1111 BK','long',63,3000000.00,'Jawa','1743744346_WhatsApp Image 2025-03-04 at 10.51.01_6adc6e5f.jpg','disewa',1,'2025-01-03 02:07:53','2025-05-28 15:23:19'),
(6,1,'AG 2222 BK','short',33,2000000.00,'Jawa','1743744364_WhatsApp Image 2025-03-04 at 10.51.01_6d9df54d.jpg','tersedia',1,'2025-01-03 02:08:24','2025-05-19 07:44:08'),
(7,2,'AG 3333 BK','long',63,3000000.00,'Yogya','1743744391_WhatsApp Image 2025-03-04 at 10.50.57_47079891.jpg','disewa',1,'2025-01-03 02:09:02','2025-05-28 15:50:49'),
(8,2,'AG 4444 BK','short',33,2000000.00,'Yogyakarta','1743744406_WhatsApp Image 2025-03-04 at 10.50.58_ee737fc9.jpg','tersedia',1,'2025-01-03 02:09:24','2025-05-26 00:19:23'),
(9,2,'AG 4444 BKA','long',63,12000000.00,'wasdwasd','1748307876_back hitam 2.jpg','tersedia',1,'2025-05-27 08:03:26','2025-05-27 08:04:36');

/*Table structure for table `conductors` */

DROP TABLE IF EXISTS `conductors`;

CREATE TABLE `conductors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('available','on_duty','off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `conductors` */

insert  into `conductors`(`id`,`nik`,`name`,`phone`,`address`,`status`,`photo`,`is_active`,`created_at`,`updated_at`) values 
(1,'12345678','Kernet','111111111','medan','on_duty','1735727156_24164782705.jpg',1,'2025-01-01 03:37:43','2025-05-28 15:23:19'),
(2,'5143112','Agus','2222222222','Kediri','on_duty','1735781129_24164782705.jpg',1,'2025-01-01 10:25:44','2025-05-28 15:50:49'),
(3,'51312312','Netnet','999999999','asfasczccccccccccccc','available','1735786234_24164782705.jpg',1,'2025-01-02 02:50:34','2025-05-26 00:00:22');

/*Table structure for table `drivers` */

DROP TABLE IF EXISTS `drivers`;

CREATE TABLE `drivers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_sim` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_expire` date NOT NULL,
  `status` enum('available','on_duty','off') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drivers_license_number_unique` (`license_number`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `drivers` */

insert  into `drivers`(`id`,`name`,`jenis_sim`,`license_number`,`phone`,`address`,`license_expire`,`status`,`photo`,`is_active`,`created_at`,`updated_at`) values 
(2,'Mail mail','A','123473001','333333333','paer','2025-01-03','on_duty','1735727207_2131730093.jpg',1,'2024-12-31 01:24:16','2025-05-28 15:23:19'),
(3,'Sifaul','B1','7124121317300','4444444444','afsadasdasd','2025-01-03','on_duty','1735781164_2131730071.JPG',1,'2024-12-31 01:47:53','2025-05-28 15:50:49'),
(4,'Mei','C','2331730011','8888888888','aaaaaaaaaaaaaaa\r\nasdasdsa','2026-12-02','available','1735786159_2131730071.JPG',1,'2025-01-02 02:49:19','2025-05-19 07:44:46');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rental_id` bigint unsigned NOT NULL,
  `extension_id` bigint unsigned DEFAULT NULL,
  `payment_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','success','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_code_unique` (`payment_code`),
  KEY `payments_rental_id_foreign` (`rental_id`),
  KEY `fk_payments_extension` (`extension_id`),
  CONSTRAINT `fk_payments_extension` FOREIGN KEY (`extension_id`) REFERENCES `rental_extensions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `payments` */

insert  into `payments`(`id`,`rental_id`,`extension_id`,`payment_code`,`amount`,`payment_method`,`payment_proof`,`status`,`notes`,`created_at`,`updated_at`) values 
(16,17,NULL,'PAY202501130001',2000000.00,'midtrans',NULL,'success',NULL,'2025-01-13 02:21:32','2025-01-13 02:23:00'),
(21,18,NULL,'PAY202501130003',18000000.00,'midtrans',NULL,'success',NULL,'2025-01-13 03:22:29','2025-01-13 03:26:30'),
(49,19,NULL,'PAY202501140003',3000000.00,'midtrans',NULL,'success',NULL,'2025-01-14 01:25:05','2025-01-14 01:27:33'),
(50,20,NULL,'PAY202501140004',8020833.33,'midtrans',NULL,'success',NULL,'2025-01-14 04:19:24','2025-01-14 04:21:56'),
(68,24,NULL,'PAY202503060005',2000000.00,'transfer','payment-proofs/HcCnOWCMtzeJVXSACaXZL4PvpBBKTlUbgUdoD4GE.jpg','success','wasd','2025-03-06 03:48:40','2025-03-06 03:49:27'),
(69,25,NULL,'PAY202503080006',3000000.00,'midtrans',NULL,'success',NULL,'2025-03-08 01:50:01','2025-03-08 01:51:32'),
(70,26,NULL,'PAY202503080007',2000000.00,'midtrans',NULL,'success',NULL,'2025-03-08 04:18:20','2025-03-08 04:19:07'),
(74,26,5,'PAY-EXT-67CBDC2E306D7',4000000.00,'transfer','payment-proofs/WBTA0oykyItki7n3IkoMGsZcXlp6x8NHxBknCA1x.jpg','success',NULL,'2025-03-08 05:57:02','2025-03-08 06:12:49'),
(75,25,6,'PAY-EXT-67CBE1BC7282F',6000000.00,'transfer','payment-proofs/YBMpNghsus2wNXmjyHt2X2kdTnWCuhP0LGFFR6Wo.jpg','success',NULL,'2025-03-08 06:20:44','2025-03-08 06:21:19'),
(76,27,NULL,'PAY202503150010',2000000.00,'midtrans',NULL,'success',NULL,'2025-03-15 01:51:49','2025-03-15 01:59:03'),
(77,29,NULL,'PAY202503210011',2000000.00,'midtrans',NULL,'success',NULL,'2025-03-21 13:11:29','2025-03-21 15:30:32'),
(78,30,NULL,'PAY202503210012',2000000.00,'midtrans',NULL,'success',NULL,'2025-03-21 15:37:12','2025-03-21 15:39:34'),
(79,30,7,'PAY-EXT-67DD273874B06',4000000.00,'transfer','payment-proofs/ofV37a7iMH2opivDqlj7KRjmVxjUk2mGv7CsX1EO.jpg','success',NULL,'2025-03-21 15:45:44','2025-03-21 15:45:59'),
(85,32,NULL,'PAY202504050014',2000000.00,'midtrans',NULL,'success',NULL,'2025-05-19 07:39:48','2025-05-19 07:39:50'),
(96,35,NULL,'PAY202505290015',5875000.00,'midtrans',NULL,'pending',NULL,'2025-05-29 13:58:43','2025-05-29 13:58:43');

/*Table structure for table `ratings` */

DROP TABLE IF EXISTS `ratings`;

CREATE TABLE `ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rental_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `ratable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ratable_id` bigint unsigned NOT NULL,
  `rating` int NOT NULL COMMENT '1-5',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ratings_rental_id_user_id_ratable_id_ratable_type_unique` (`rental_id`,`user_id`,`ratable_id`,`ratable_type`),
  KEY `ratings_user_id_foreign` (`user_id`),
  KEY `ratings_ratable_type_ratable_id_index` (`ratable_type`,`ratable_id`),
  CONSTRAINT `ratings_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `ratings` */

/*Table structure for table `rental_extensions` */

DROP TABLE IF EXISTS `rental_extensions`;

CREATE TABLE `rental_extensions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rental_id` bigint unsigned NOT NULL,
  `additional_days` int NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `additional_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('pending','paid','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `payment_data` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rental_extensions_rental_id_foreign` (`rental_id`),
  CONSTRAINT `rental_extensions_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `rental_extensions` */

insert  into `rental_extensions`(`id`,`rental_id`,`additional_days`,`start_date`,`end_date`,`status`,`notes`,`created_at`,`updated_at`,`additional_price`,`payment_status`,`paid_at`,`payment_data`) values 
(5,26,2,'2025-03-08 11:59:00','2025-03-09 11:59:00','approved',NULL,'2025-03-08 04:59:34','2025-03-08 06:12:49',4000000.00,'paid','2025-03-08 06:12:49',NULL),
(6,25,2,'2025-03-08 13:17:00','2025-03-09 13:17:00','approved',NULL,'2025-03-08 06:17:21','2025-03-08 06:21:19',6000000.00,'paid','2025-03-08 06:21:19',NULL),
(7,30,2,'2025-03-21 15:43:00','2025-03-22 15:43:00','approved',NULL,'2025-03-21 15:44:03','2025-03-21 15:45:59',4000000.00,'paid','2025-03-21 15:45:59',NULL);

/*Table structure for table `rentals` */

DROP TABLE IF EXISTS `rentals`;

CREATE TABLE `rentals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rental_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `bus_id` bigint unsigned NOT NULL,
  `driver_id` bigint unsigned NOT NULL,
  `conductor_id` bigint unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `pickup_location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_days` int NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `rental_package` enum('day','trip') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'day',
  `status` enum('pending','aktif','selesai','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rental_status` enum('pending','confirmed','ongoing','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','partial','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rentals_rental_code_unique` (`rental_code`),
  KEY `rentals_user_id_foreign` (`user_id`),
  KEY `rentals_bus_id_foreign` (`bus_id`),
  KEY `rentals_driver_id_foreign` (`driver_id`),
  KEY `rentals_conductor_id_foreign` (`conductor_id`),
  CONSTRAINT `rentals_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rentals_conductor_id_foreign` FOREIGN KEY (`conductor_id`) REFERENCES `conductors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rentals_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rentals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `rentals` */

insert  into `rentals`(`id`,`rental_code`,`user_id`,`bus_id`,`driver_id`,`conductor_id`,`start_date`,`end_date`,`pickup_location`,`destination`,`total_days`,`total_price`,`rental_package`,`status`,`rental_status`,`payment_status`,`notes`,`created_at`,`updated_at`) values 
(17,'RNT202501030001',1,6,2,1,'2025-01-04 10:30:00','2025-01-05 10:30:00','wasd','qwerty',1,2000000.00,'day','selesai','completed','paid','qwertywasd','2025-01-03 02:29:19','2025-01-13 02:37:38'),
(18,'RNT202501030002',11,7,3,2,'2025-01-05 09:30:00','2025-01-10 09:30:00','qqqqqqqqqqqqqqqqqqqqq','qqqqqqqqqqqqqqqqqqqqq',6,18000000.00,'day','selesai','completed','paid','qqqqqqqqqqqqqqqqqqqqq','2025-01-03 02:31:22','2025-01-13 03:28:20'),
(19,'RNT202501130001',1,5,2,2,'2025-01-14 10:30:00','2025-01-15 10:30:00','qwerty','wasd',1,3000000.00,'day','selesai','completed','paid','zxcvb','2025-01-13 03:31:00','2025-01-14 01:29:19'),
(20,'RNT202501140001',11,8,3,3,'2025-01-14 12:00:00','2025-01-17 12:15:00','asd','asd',4,8020833.33,'day','selesai','completed','paid','asd','2025-01-14 04:18:02','2025-03-06 02:14:35'),
(24,'RNT202503060001',1,6,2,1,'2025-03-06 11:40:00','2025-03-07 11:40:00','wasd','wasd',1,2000000.00,'day','selesai','completed','paid','wasd','2025-03-06 02:38:33','2025-03-08 01:46:42'),
(25,'RNT202503080001',11,5,3,2,'2025-03-08 08:48:00','2025-03-09 13:17:00','wasd','asdaw',3,9000000.00,'day','selesai','completed','paid','wasdasda','2025-03-08 01:48:18','2025-03-08 06:22:43'),
(26,'RNT202503080002',1,8,2,1,'2025-03-08 11:02:00','2025-03-09 11:59:00','rtrtrtr','trtrtr',7,14000000.00,'day','selesai','completed','paid','trtrtr','2025-03-08 04:06:02','2025-03-08 06:13:42'),
(27,'RNT202503150001',12,8,3,1,'2025-03-15 10:00:00','2025-03-16 10:00:00','medan','malang',1,2000000.00,'day','selesai','completed','paid','kuliah','2025-03-15 18:38:48','2025-03-15 09:05:24'),
(28,'RNT202503150002',12,5,3,1,'2025-03-15 11:00:00','2025-03-16 11:00:00','asd','ad',1,3000000.00,'day','dibatalkan','cancelled','unpaid','asd','2025-03-15 09:06:29','2025-03-15 09:13:52'),
(29,'RNT202503210001',12,6,3,1,'2025-03-21 13:30:00','2025-03-22 13:30:00','adas','asdas',1,2000000.00,'day','selesai','completed','paid','asdasda','2025-03-21 12:35:16','2025-03-21 15:31:17'),
(30,'RNT202503210002',12,8,4,2,'2025-03-21 17:30:00','2025-03-22 15:43:00','asdasda','asdsadas',3,6000000.00,'day','selesai','completed','paid','fasdasdas','2025-03-21 15:32:28','2025-03-21 15:46:29'),
(32,'RNT202504050001',12,6,3,1,'2025-05-19 07:39:11','2025-05-20 07:39:17','Malang','Bandung',1,2000000.00,'day','selesai','completed','paid','Tidak Ada','2025-05-19 07:39:26','2025-05-19 07:44:08'),
(34,'RNT202505280001',1,5,2,1,'2025-05-30 15:22:00','2025-05-31 15:22:00','mlg','bandung',2,6000000.00,'trip','aktif','confirmed','unpaid','wasd','2025-05-29 13:53:17','2025-05-29 13:53:19'),
(35,'RNT202505280002',1,7,3,2,'2025-05-30 16:50:00','2025-05-31 15:50:00','aaa','aaa',2,5875000.00,'trip','aktif','confirmed','unpaid','aaa','2025-05-29 13:53:07','2025-05-29 13:53:10');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('3sXei9dANkgyE1ijYRrKn69xU6RI88zClkufhzax',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM1ZQMUZmYkIycEVIVEZmNm44ZmlTb2dTYjd3aGNZTmpVeGZXc243ciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9yZW50YWxzLzM1Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1748422327),
('T9rEjxe4wplplwc5Ba5U3b93WVwzO1sAuR54VcBR',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQjRSRWlKZTM2enpUd0gwM3JiS2JGMDJoTVY3azVQN3FiQlpBVVdhUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZW50YWxzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1748501956);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('owner','admin','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`firstname`,`lastname`,`email`,`role`,`phone`,`avatar`,`address`,`city`,`country`,`postal`,`about`,`email_verified_at`,`password`,`is_active`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Kevin','Kevin','Ardi','frankie.steinlie@gmail.com','customer','08512345678','1743746325_back hitam.jpg','Kediri Kota','Kediri','Indonesia','64212','Developer',NULL,'$2y$12$snLd.kqqLzuLAGMIylAQZOnlpIdu9qOPj4t.bWpMtK4odMBumo7AS',1,NULL,'2024-12-30 01:06:32','2025-04-04 14:38:58'),
(3,'admin','admin',NULL,'2131730071@student.polinema.ac.id','admin',NULL,'1735781319_back hitam 2.jpg',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$LiHuhUDUWqyU6bXobgEm.uggk5rsTq/QGOWEVjvB0cLlwb9svzrnW',1,NULL,'2024-12-30 03:30:32','2025-01-02 01:28:39'),
(9,'owner','owner','steinlie','owner@gmail.com','owner',NULL,'1735781330_back hitam 2.jpg','Jl. Garuda No.3C, Medan','Medan','Indonesia','64212','Owner',NULL,'$2y$12$6jLVNc4NH.i/YGfUn4U7DeMgv/r1GikLYuycBbO.V4C/b4oeV4I2u',1,NULL,'2024-12-30 05:47:55','2025-01-02 01:28:50'),
(11,'masrupin','masrupin','masrupin','frankie.intern24slides@gmail.com','customer','12345','1743746409_back hitam 2.jpg','Pare','Pare','Pare','12123','asdasdasdaf',NULL,'$2y$12$wYTCLpqNOss1be00RtWTiOoEaj6/hzSp5w4d5Red3uD7ntUDtKV0a',1,NULL,'2025-01-01 11:37:07','2025-04-04 14:38:07'),
(12,'fs','Kepin','Kepin','bukudigital41@gmail.com','customer',NULL,'1743746419_back hitam 2.jpg',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$qc.P6HIwl28O/YQJjZ/0Hug8j/v6W3R9tIZFHt.jkLRpEkx7hwZjm',1,NULL,'2025-03-15 01:32:34','2025-04-04 14:38:28');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
