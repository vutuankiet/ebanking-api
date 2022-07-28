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
  `token` varchar(50) DEFAULT '',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.account: ~2 rows (approximately)
DELETE FROM `account`;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` (`phone`, `password`, `status`, `state`, `token`, `updated_at`, `created_at`) VALUES
	('01721313155', 'dfdddd', 'Đag hoạt động', 1, '', '2022-07-27 18:28:42', '2022-07-27 18:28:42'),
	('01721313163', 'somepass', 'Đag hoạt động', 1, '', '2022-07-27 18:23:21', '2022-07-27 18:23:21');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.branch: ~0 rows (approximately)
DELETE FROM `branch`;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` (`id_branch`, `name_branch`, `location_branch`, `state`, `created_at`, `updated_at`) VALUES
	(1, 'Q1', 'Q1 HCM VN', 1, '2022-07-27 12:08:43', '2022-07-27 12:08:43');
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

-- Dumping data for table ebanking.card: ~6 rows (approximately)
DELETE FROM `card`;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
/*!40000 ALTER TABLE `card` ENABLE KEYS */;

-- Dumping structure for table ebanking.category_transaction
DROP TABLE IF EXISTS `category_transaction`;
CREATE TABLE IF NOT EXISTS `category_transaction` (
  `id_category_transaction` int(11) NOT NULL AUTO_INCREMENT,
  `name_transaction` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `fee_transaction` int(11) NOT NULL,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_category_transaction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.category_transaction: ~0 rows (approximately)
DELETE FROM `category_transaction`;
/*!40000 ALTER TABLE `category_transaction` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.customer: ~2 rows (approximately)
DELETE FROM `customer`;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`id_person`, `name`, `citizen_identity_card`, `mail`, `phone`, `address`, `age`, `money`, `created_at`, `updated_at`, `id_branch`, `state`) VALUES
	(1, 'hello', '031202407979', 'dan1gkhanh.dev10@gmail.com', '01721313163', 'HCM', 19, 20000, '2022-07-27 18:23:21', '2022-07-27 18:23:21', 1, 1),
	(2, 'Nguyen Van B', '031202407779', 'dan1gkhanh1.dev140@gmail.com', '01721313155', 'HCM', 19, 20000, '2022-07-27 18:28:42', '2022-07-27 18:28:42', 1, 0);
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;

-- Dumping structure for table ebanking.history_transaction
DROP TABLE IF EXISTS `history_transaction`;
CREATE TABLE IF NOT EXISTS `history_transaction` (
  `id_transaction` int(11) NOT NULL AUTO_INCREMENT,
  `id_category_transaction` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `id_bill` varchar(20) DEFAULT NULL,
  `id_customer` int(11) NOT NULL,
  `receiver` int(11) DEFAULT NULL,
  `id_bank` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_transaction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.history_transaction: ~0 rows (approximately)
DELETE FROM `history_transaction`;
/*!40000 ALTER TABLE `history_transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `history_transaction` ENABLE KEYS */;

-- Dumping structure for table ebanking.passbook
DROP TABLE IF EXISTS `passbook`;
CREATE TABLE IF NOT EXISTS `passbook` (
  `id_passbook` int(11) NOT NULL AUTO_INCREMENT,
  `id_customer` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `period` int(1) NOT NULL,
  `interest_rate` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `withdrawaled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_passbook`),
  KEY `id_customer` (`id_customer`),
  CONSTRAINT `passbook_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_person`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.passbook: ~2 rows (approximately)
DELETE FROM `passbook`;
/*!40000 ALTER TABLE `passbook` DISABLE KEYS */;
INSERT INTO `passbook` (`id_passbook`, `id_customer`, `money`, `created_at`, `period`, `interest_rate`, `status`, `description`, `updated_at`, `withdrawaled_at`, `state`) VALUES
	(1, 1, 3, '2022-07-27 18:47:50', 6, 1, 'Chưa được rút', 'Sổ tiết kiệm có kỳ hạn 6 tháng', '2022-07-27 18:47:50', '2023-01-27 11:47:50', 0),
	(2, 1, 2, '2022-07-27 22:40:47', 6, 1, 'Chưa được rút', 'Sổ tiết kiệm có kỳ hạn 6 tháng!', '2022-07-27 22:40:47', '2023-01-27 15:40:47', 1);
/*!40000 ALTER TABLE `passbook` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
