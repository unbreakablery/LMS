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
  `status` enum('0','1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0: booking, 1: booked/approved, 2: rejected, 3: cancelled, 4: returned',
  `booking_qnt` int(10) unsigned NOT NULL DEFAULT 1 COMMENT 'equipment booking quantity',
  `booking_start` date NOT NULL COMMENT 'booking period start date',
  `booking_end` date NOT NULL COMMENT 'booking period end date',
  `staff` int(11) DEFAULT NULL COMMENT 'user id dealing with booking',
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categories` */

LOCK TABLES `categories` WRITE;

insert  into `categories`(`id`,`cat_name`,`p_cat`,`created_at`,`updated_at`) values 
(1,'All',0,'2021-09-20 08:11:27','2021-09-20 08:11:27'),
(2,'Computer',1,'2021-09-20 08:14:59','2021-09-20 08:14:59'),
(3,'Desktop',2,'2021-09-20 08:15:13','2021-09-20 08:15:13'),
(4,'Laptop',2,'2021-09-20 08:15:20','2021-09-20 08:15:20'),
(5,'Dell',3,'2021-09-20 08:15:35','2021-09-20 08:15:35'),
(6,'HP',4,'2021-09-20 08:15:42','2021-09-20 08:15:42'),
(7,'Eelectronics',1,'2021-09-20 08:15:54','2021-09-20 08:15:54'),
(8,'Oscilloscope',7,'2021-09-20 08:18:55','2021-09-20 08:18:55'),
(9,'Chemistry',1,'2021-09-20 09:32:27','2021-09-20 09:32:27'),
(10,'Beakers',9,'2021-09-20 09:34:30','2021-09-20 09:34:30'),
(11,'Flasks',9,'2021-09-20 09:35:49','2021-09-20 09:35:49'),
(12,'Tubes',9,'2021-09-20 09:36:43','2021-09-20 09:36:43'),
(13,'Watch Glasses',9,'2021-09-20 09:37:14','2021-09-20 09:37:14'),
(14,'Crucibles',9,'2021-09-20 09:38:11','2021-09-20 09:38:11'),
(15,'Funnels',9,'2021-09-20 09:39:15','2021-09-20 09:39:15'),
(17,'Geographics',1,'2021-09-21 17:58:42','2021-09-21 17:58:42');

UNLOCK TABLES;

/*Table structure for table `equipment` */

DROP TABLE IF EXISTS `equipment`;

CREATE TABLE `equipment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `equ_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'equipment registration code',
  `equ_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'equipment name',
  `equ_desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'equipment description/spec',
  `equ_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'equipment image',
  `equ_total_qnt` int(10) unsigned NOT NULL DEFAULT 100 COMMENT 'equipment total quantity',
  `equ_current_qnt` int(10) unsigned NOT NULL DEFAULT 100 COMMENT 'equipment current quantity',
  `equ_status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0: unbookable, 1: bookable',
  `cat_id` int(11) DEFAULT NULL COMMENT 'category id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipment_equ_code_unique` (`equ_code`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `equipment` */

LOCK TABLES `equipment` WRITE;

insert  into `equipment`(`id`,`equ_code`,`equ_name`,`equ_desc`,`equ_image`,`equ_total_qnt`,`equ_current_qnt`,`equ_status`,`cat_id`,`created_at`,`updated_at`) values 
(1,'EQ0021','HP 20200','Core i-7 7543\r\nDDR3 16GB\r\nHDD 2TB',NULL,100,100,'1',6,'2021-09-20 08:17:30','2021-09-20 09:47:57'),
(2,'EQ0022','HP 6400','Core i-6 6400\r\nDDR3 16GB\r\nHDD 2TB',NULL,100,100,'1',6,'2021-09-20 08:18:30','2021-09-20 09:48:05'),
(3,'EQ0023','NOVA 483-929','Screen size\r\nOscilloscope display screens have improved immeasurably over the last few years. Screen sizes have increased significantly and the definition is very much better.\r\n\r\nUsing modern screens it is possible to see a lot of definition in the waveform, and this could reveal issues that may not have been visible on older scopes.',NULL,100,100,'1',8,'2021-09-20 08:21:53','2021-09-20 09:50:26'),
(4,'EQ0010','Siglent SDS1104X-E 100Mhz digital oscilloscope 4 channels standard decoder','Brand Name	Siglent\r\nColor	Grey\r\nDisplay Type	TFT -LCD\r\nEan	0764560085678 , 8011529266756\r\nGlobal Trade Identification Number	00764560085678\r\nHeight	42.0 centimeters\r\nIncluded Components	SDS1104X-E, Quick Start, Calibration Certificate, 4 passive probes, USB cable','EQ0010.jpg',100,100,'1',8,'2021-09-20 08:26:01','2021-09-21 18:35:04'),
(5,'EQ0024','Rigol DS1054Z Digital Oscilloscopes - Bandwidth: 50 MHz, Channels: 4 Serial Decode Included','Body Style	Benchtop\r\nBrand Name	Rigol\r\nColor	Grey\r\nEan	0190203037252 , 4631123823632 , 0789164314795 , 0190073585655\r\nItem Weight	6.60 pounds\r\nMaterial	Plastic\r\nModel Number	DS1054Z\r\nNumber of Items	1\r\nPart Number	DS1054Z\r\nPower Source Type	corded-electric\r\nUNSPSC Code	41110000\r\nUPC	190073585655 , 190203037252 , 789164314795','EQ0024.jpg',100,100,'1',8,'2021-09-20 08:27:15','2021-09-24 11:30:14'),
(6,'EQ0005','Beaker 20-80','Dia 20-80mm','EQ0005.webp',100,100,'1',10,'2021-09-20 09:40:29','2021-10-04 20:14:04'),
(7,'EQ0004','Flask 30-100','Top DIa. 30mm\r\nBottom Dia. 100mm','EQ0004.webp',100,100,'1',11,'2021-09-20 09:41:49','2021-09-24 11:29:27'),
(8,'EQ0006','Crucible 50','Dia. 50mm','EQ0006.webp',100,100,'1',14,'2021-09-20 09:43:01','2021-09-24 08:56:40'),
(9,'EQ0007','Watch Glasses','R - 50mm\r\nH - 20mm','EQ0007.webp',100,100,'1',13,'2021-09-20 09:45:16','2021-09-20 09:45:16'),
(10,'EQ0008','Tube 33993','Dia. 10mm','EQ0008.webp',100,100,'1',12,'2021-09-20 09:46:12','2021-09-21 18:35:08'),
(11,'EQ0009','Funnel 100-5','WR-100mm\r\nTR-5mm','EQ0009.webp',100,100,'1',15,'2021-09-20 09:47:35','2021-09-20 09:47:35'),
(13,'EQ0002','Graduated cylinders','This is a primary measuring tool for the volume of a liquid. There are several markings up and down the length of the container with specific increments. Graduated cylinders come in many sizes. The smaller they are in diameter, the more specific the volume measurements will be.\r\n\r\nWhen reading the volume from a graduated cylinder, you will notice that the liquid seems to have an indentation. The liquid around the edges will be higher than the liquid in the center, sloping down like the sides of a trampoline when someone is standing in the middle. This is called the meniscus. Line the lowest point of the meniscus up with the nearest marking, keeping the cylinder level to properly read the volume.','EQ0002.webp',100,100,'1',1,'2021-10-04 06:37:59','2021-10-05 04:45:52'),
(14,'EQ0003','Pipettes','There are a large variety of pipettes designed to accomplish specific goals. However, they are all for measuring an exact volume of liquid and placing it into another container.','EQ0003.webp',100,100,'1',1,'2021-10-04 15:45:42','2021-10-05 05:24:49');

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(8,'2021_09_07_043701_create_notifications_table',1),
(13,'2021_10_04_052937_add_equ_total_qnt_to_equipment_table',2),
(14,'2021_10_04_055648_add_equ_current_qnt_to_equipment_table',2),
(15,'2021_10_04_194526_add_booking_qnt_to_bookings_table',3);

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
  `status` enum('0','1','2','3','4','5','6') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0: booking, 1: booked/approved, 2: rejected, 3: cancelled, 4: deleted, 5: returned, 6: changed booking info',
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`first_name`,`last_name`,`email`,`email_verified_at`,`password`,`role`,`active`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Benaris','Hajduk','labadmin2021@gmail.com',NULL,'$2y$10$ez/i6fMqonKrIfRgTQoiBOO.WmIABA/mrNL3TCuRYd4RjaMjaogpO','1',1,NULL,'2021-09-20 08:11:27','2021-09-21 17:51:00'),
(2,'Anthony','Edward','unbreakablery+1@gmail.com',NULL,'$2y$10$oAFaqgy5egcXf2go48PsBOiehWmoZ.LMLHBq7NFj.T8uJQkipRjdS','3',1,NULL,'2021-09-20 08:12:21','2021-09-21 17:54:28'),
(3,'Christopher2','Horn2','unbreakablery+2@gmail.com',NULL,'$2y$10$cAI8Z1pbBTHnxRBNFMhDsOv7v6/WdfAwSpkwvDn/lzAvDBWKwjMoi','3',1,NULL,'2021-09-20 08:13:49','2021-09-21 17:54:48');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
