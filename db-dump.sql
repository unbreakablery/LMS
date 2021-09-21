/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 10.4.17-MariaDB : Database - lab
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `bookings` */

DROP TABLE IF EXISTS `bookings`;

CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `equ_id` int(11) NOT NULL COMMENT 'equipment id',
  `booking_user` int(11) NOT NULL COMMENT 'user id in booking',
  `booking_date` date NOT NULL COMMENT 'booking date',
  `status` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0: booking, 1: booked/approved, 2: rejected, 3: cancelled',
  `booking_start` date NOT NULL COMMENT 'booking period start date',
  `booking_end` date NOT NULL COMMENT 'booking period end date',
  `staff` int(11) NOT NULL COMMENT 'user id dealing with booking',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `bookings` */

LOCK TABLES `bookings` WRITE;

UNLOCK TABLES;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'category name',
  `p_cat` int(11) NOT NULL COMMENT 'parent category id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categories` */

LOCK TABLES `categories` WRITE;

insert  into `categories`(`id`,`cat_name`,`p_cat`,`created_at`,`updated_at`) values 
(1,'All',0,'2021-09-09 22:05:30',NULL),
(2,'Electronics',1,'2021-09-09 22:08:40',NULL),
(3,'Computer',1,'2021-09-09 22:09:29',NULL),
(4,'Desktop',3,'2021-09-09 22:09:50','2021-09-12 01:31:27'),
(5,'Laptop',3,'2021-09-09 22:10:06','2021-09-12 01:31:33'),
(6,'Oscilloscope',2,'2021-09-09 22:11:18',NULL),
(8,'HP',5,'2021-09-12 06:49:42',NULL),
(12,'Dell',4,'2021-09-12 01:59:30','2021-09-12 01:59:30'),
(14,'Sony',5,'2021-09-13 08:37:37','2021-09-13 08:37:37'),
(15,'IBM',5,'2021-09-13 08:37:48','2021-09-13 08:37:48'),
(16,'Chemistry',1,'2021-09-13 13:13:53','2021-09-13 13:13:53');

UNLOCK TABLES;

/*Table structure for table `equipment` */

DROP TABLE IF EXISTS `equipment`;

CREATE TABLE `equipment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `equ_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'equipment registration code',
  `equ_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'equipment name',
  `equ_desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'equipment description/spec',
  `equ_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'equipment image',
  `equ_status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0: available, 1: booking, 2:pickup(booked)',
  `cat_id` int(11) DEFAULT NULL COMMENT 'category id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipment_equ_code_unique` (`equ_code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `equipment` */

LOCK TABLES `equipment` WRITE;

insert  into `equipment`(`id`,`equ_code`,`equ_name`,`equ_desc`,`equ_image`,`equ_status`,`cat_id`,`created_at`,`updated_at`) values 
(1,'EQ0001','DELL 1019','CORE i-7 6400\r\nDDR3 16GB\r\nHDD 2TB','EQ0001.png','1',12,'2021-09-14 13:38:01','2021-09-14 13:38:01'),
(3,'EQ0015','WOW','EPROM\r\nTEST','EQ0015.png','2',14,'2021-09-14 13:42:40','2021-09-14 13:42:40'),
(5,'EQ0009','IBM RT-02','REPLAY',NULL,'1',5,'2021-09-14 22:48:29','2021-09-14 22:48:32');

UNLOCK TABLES;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

LOCK TABLES `failed_jobs` WRITE;

UNLOCK TABLES;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

LOCK TABLES `migrations` WRITE;

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2021_09_07_043349_create_categories_table',1),
(5,'2021_09_07_043552_create_equipment_table',1),
(6,'2021_09_07_043616_create_bookings_table',1),
(7,'2021_09_07_043639_create_trackings_table',1),
(8,'2021_09_07_043701_create_notifications_table',1);

UNLOCK TABLES;

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(11) NOT NULL DEFAULT 1 COMMENT '1: from superadmin(broadcast), other: from specific staff',
  `receiver` int(11) NOT NULL DEFAULT 0 COMMENT '0: to all students(broadcast), other: to specific student',
  `msg` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'notification content',
  `status` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0: sent, 1: read, 2: broadcast(in case user_id is 0)',
  `read_time` timestamp NULL DEFAULT NULL COMMENT 'read time stamp',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notifications` */

LOCK TABLES `notifications` WRITE;

UNLOCK TABLES;

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

LOCK TABLES `password_resets` WRITE;

UNLOCK TABLES;

/*Table structure for table `trackings` */

DROP TABLE IF EXISTS `trackings`;

CREATE TABLE `trackings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL COMMENT 'booking id',
  `tracking_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'tracking timestamp',
  `staff` int(11) NOT NULL COMMENT 'user id dealing with booking',
  `status` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0: booking, 1: booked/approved, 2: rejected, 3: cancelled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `trackings` */

LOCK TABLES `trackings` WRITE;

UNLOCK TABLES;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '3' COMMENT '0: disabled, 1: superadmin, 2: staff, 3: student',
  `active` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: active/approved, 1: inactive/reject',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`first_name`,`last_name`,`email`,`email_verified_at`,`password`,`role`,`active`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Anthony','Edward','labadmin2021@gmail.com',NULL,'$2y$10$08AXu2mEtmV66RfXAEKC0emJe.It3lME.0BMyTBDDe4MU/1EUOkCO','1',1,NULL,NULL,'2021-09-07 11:57:09'),
(2,'Jessica','Gassner','aaa.bbb@gmail.com',NULL,'$2y$10$9pcUjz3xwnRpVz3ytXBKf.EV//osDbWD5vUPBCBD7hQWtukYvcmXi','3',1,NULL,'2021-09-07 07:14:02','2021-09-07 14:06:09'),
(4,'Mike','Dole','mike.dole@gmail.com',NULL,'$2y$10$nbakgDkJqN5fFIg8mm5yFe1pYsTi8/BZIBa83Bp.x5PTpUvSFRP36','2',1,NULL,'2021-09-07 13:37:51','2021-09-07 14:06:49');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
