-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ebanking
DROP DATABASE IF EXISTS `ebanking`;
CREATE DATABASE IF NOT EXISTS `ebanking` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `ebanking`;

-- Dumping structure for table ebanking.account
DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `phone` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Đag hoạt động',
  `state` int(11) DEFAULT '1',
  `token` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.account: ~6 rows (approximately)
DELETE FROM `account`;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` (`phone`, `password`, `status`, `state`, `token`, `updated_at`, `created_at`) VALUES
	('01721333113', 'c29tZXBhc3M=', 'Đag hoạt động', 1, 'MDE3MjEzMzMxMTNzb21lcGFzczIwMjI6MDg6MDc=', NULL, '2022-08-07 13:21:29'),
	('01721333123', 'c29tZXBhc3M=', 'Đag hoạt động', 1, NULL, NULL, '2022-08-07 13:21:01'),
	('01721363123', 'c29tZXBhc3M=', 'Đag hoạt động', 1, NULL, '2022-08-07 13:19:56', '2022-08-07 13:19:56'),
	('01721363163', 'c29tZXBhc3M=', 'Đag hoạt động', 1, '', '2022-08-07 13:03:54', '2022-08-07 13:03:54'),
	('01721433123', 'c29tZXBhc3M=', 'Đag hoạt động', 1, 'MDE3MjE0MzMxMjNzb21lcGFzczIwMjI6MDg6MDc=', NULL, '2022-08-07 13:32:26'),
	('01721433173', 'c29tZXBhc3M=', 'Đag hoạt động', 1, 'JNLhizZIlB25rwBi9lUrY8TPi', NULL, '2022-08-07 13:42:30'),
	('01721439173', 'c29tZXBhc3M=', 'Đag hoạt động', 1, 'sfzyBz9kppXL7kFyTe1qESPPH', NULL, '2022-08-09 16:15:21');
/*!40000 ALTER TABLE `account` ENABLE KEYS */;

-- Dumping structure for table ebanking.branch
DROP TABLE IF EXISTS `branch`;
CREATE TABLE IF NOT EXISTS `branch` (
  `id_branch` int(11) NOT NULL AUTO_INCREMENT,
  `name_branch` varchar(50) NOT NULL,
  `location_branch` varchar(100) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_branch`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.branch: ~3 rows (approximately)
DELETE FROM `branch`;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` (`id_branch`, `name_branch`, `location_branch`, `state`, `created_at`, `updated_at`) VALUES
	(1, 'Q1', 'Q1 HCM VN', 1, '2022-07-27 12:08:43', NULL),
	(2, 'Q10', 'Q10 HCM VN', 1, '2022-08-03 16:17:54', NULL),
	(3, 'Q11', 'Q11 HCM VN', 1, '2022-08-03 16:28:41', NULL);
/*!40000 ALTER TABLE `branch` ENABLE KEYS */;

-- Dumping structure for table ebanking.card
DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `id_card` varchar(20) NOT NULL,
  `pin` varchar(6) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expired_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) DEFAULT '1',
  `id_customer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_card`),
  KEY `card` (`id_customer`),
  CONSTRAINT `card_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_person`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.card: ~2 rows (approximately)
DELETE FROM `card`;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
INSERT INTO `card` (`id_card`, `pin`, `status`, `created_at`, `updated_at`, `expired_at`, `state`, `id_customer`) VALUES
	('wGpg5ptG39', '123357', 'Đã kích hoạt', '2022-08-07 14:56:05', NULL, '2022-08-07 14:56:05', 1, 9),
	('YfE44YHg94', '123357', 'Đã kích hoạt', '2022-08-07 13:47:00', NULL, '2022-08-07 13:47:00', 1, 9);
/*!40000 ALTER TABLE `card` ENABLE KEYS */;

-- Dumping structure for table ebanking.category_passbook
DROP TABLE IF EXISTS `category_passbook`;
CREATE TABLE IF NOT EXISTS `category_passbook` (
  `id_category_passbook` int(11) NOT NULL AUTO_INCREMENT,
  `name_passbook` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` int(11) NOT NULL,
  `interest_rate` int(11) NOT NULL,
  `description` int(11) NOT NULL,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_category_passbook`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ebanking.category_passbook: ~0 rows (approximately)
DELETE FROM `category_passbook`;
/*!40000 ALTER TABLE `category_passbook` DISABLE KEYS */;
INSERT INTO `category_passbook` (`id_category_passbook`, `name_passbook`, `period`, `interest_rate`, `description`, `state`) VALUES
	(3, 'so tiet kiem', 1, 2, 3, 1);
/*!40000 ALTER TABLE `category_passbook` ENABLE KEYS */;

-- Dumping structure for table ebanking.category_transaction
DROP TABLE IF EXISTS `category_transaction`;
CREATE TABLE IF NOT EXISTS `category_transaction` (
  `id_category_transaction` int(11) NOT NULL AUTO_INCREMENT,
  `name_transaction` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee_transaction` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_category_transaction`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ebanking.category_transaction: ~5 rows (approximately)
DELETE FROM `category_transaction`;
/*!40000 ALTER TABLE `category_transaction` DISABLE KEYS */;
INSERT INTO `category_transaction` (`id_category_transaction`, `name_transaction`, `description`, `fee_transaction`, `state`) VALUES
	(1, 'Ngân hàng', 'Gửi tiền', 0, 1),
	(2, 'Ngân hàng', 'Rút tiền', 0, 1),
	(3, 'Nội bộ', 'Chuyển tiền', 200, 1),
	(4, 'Liên ngân hàng', 'Chuyển tiền', 2000, 1),
	(5, 'Thanh toán hóa đơn', 'Thanh toán', 500, 1);
/*!40000 ALTER TABLE `category_transaction` ENABLE KEYS */;

-- Dumping structure for table ebanking.customer
DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id_person` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `citizen_identity_card` varchar(20) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(200) NOT NULL,
  `age` int(11) NOT NULL,
  `money` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_branch` int(11) NOT NULL,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_person`),
  UNIQUE KEY `mail` (`mail`),
  UNIQUE KEY `citizen_identity_card` (`citizen_identity_card`),
  UNIQUE KEY `phone` (`phone`),
  KEY `id_branch` (`id_branch`),
  KEY `customer` (`phone`) USING BTREE,
  CONSTRAINT `customer_ibfk_4` FOREIGN KEY (`id_branch`) REFERENCES `branch` (`id_branch`),
  CONSTRAINT `customer_ibfk_5` FOREIGN KEY (`phone`) REFERENCES `account` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.customer: ~6 rows (approximately)
DELETE FROM `customer`;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`id_person`, `name`, `citizen_identity_card`, `mail`, `phone`, `address`, `age`, `money`, `created_at`, `updated_at`, `id_branch`, `state`) VALUES
	(6, 'Nguyen Van A', '031202406979', 'dan1gkha6h.dev10@gmail.com', '01721363163', 'HCM', 19, 20000, '2022-08-07 13:03:54', NULL, 1, 1),
	(7, 'Nguyen Van A', '031202402979', 'dangkha6h.dev10@gmail.com', '01721363123', 'HCM', 19, 20000, '2022-08-07 13:19:56', NULL, 1, 1),
	(8, 'Nguyen Van A', '031202403979', 'dangkha6h.dev130@gmail.com', '01721333123', 'HCM', 19, 20000, '2022-08-07 13:21:01', NULL, 1, 1),
	(9, 'Nguyen Van A', '031102403979', 'dangkha6h.dev1310@gmail.com', '01721333113', 'HCM', 19, 20000, '2022-08-07 13:21:29', NULL, 1, 1),
	(10, 'Nguyen Van A', '032142403979', 'dangkha6h.dev132140@gmail.com', '01721433123', 'HCM', 19, 20000, '2022-08-07 13:32:26', NULL, 1, 1),
	(11, 'Nguyen Van A', '039142403979', 'dangkha6h.dev1321470@gmail.com', '01721433173', 'HCM', 19, 20000, '2022-08-07 13:42:30', NULL, 1, 1),
	(12, 'Nguyen Van A', '039992403979', 'dangkha6h.dep921470@gmail.com', '01721439173', 'HCM', 19, 20000, '2022-08-09 16:15:21', NULL, 1, 1);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Dumping structure for table ebanking.history_transaction
DROP TABLE IF EXISTS `history_transaction`;
CREATE TABLE IF NOT EXISTS `history_transaction` (
  `id_transaction` int(11) NOT NULL AUTO_INCREMENT,
  `money` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_category_transaction` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_transaction`),
  KEY `id_customer` (`from`),
  KEY `id_category_transaction` (`id_category_transaction`),
  CONSTRAINT `FK_history_transaction_category_transaction` FOREIGN KEY (`id_category_transaction`) REFERENCES `category_transaction` (`id_category_transaction`),
  CONSTRAINT `history_transaction_ibfk_2` FOREIGN KEY (`from`) REFERENCES `customer` (`id_person`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.history_transaction: ~3 rows (approximately)
DELETE FROM `history_transaction`;
/*!40000 ALTER TABLE `history_transaction` DISABLE KEYS */;
INSERT INTO `history_transaction` (`id_transaction`, `money`, `from`, `status`, `created_at`, `updated_at`, `id_category_transaction`, `state`) VALUES
	(15, 10, 9, 'Thành Công', '2022-08-07 17:31:01', NULL, 1, 1),
	(16, 10, 9, 'Thành Công', '2022-08-07 17:31:04', NULL, 2, 1),
	(17, 10, 9, 'Thành Công', '2022-08-07 17:33:37', NULL, 2, 1);
/*!40000 ALTER TABLE `history_transaction` ENABLE KEYS */;

-- Dumping structure for table ebanking.passbook
DROP TABLE IF EXISTS `passbook`;
CREATE TABLE IF NOT EXISTS `passbook` (
  `id_passbook` int(11) NOT NULL AUTO_INCREMENT,
  `id_customer` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `id_category_passbook` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_passbook`),
  KEY `id_customer` (`id_customer`),
  KEY `id_ca` (`id_category_passbook`),
  CONSTRAINT `passbook_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_person`),
  CONSTRAINT `passbook_ibfk_2` FOREIGN KEY (`id_category_passbook`) REFERENCES `category_passbook` (`id_category_passbook`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.passbook: ~0 rows (approximately)
DELETE FROM `passbook`;
/*!40000 ALTER TABLE `passbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `passbook` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
