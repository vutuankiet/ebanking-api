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
  `status` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.account: ~1 rows (approximately)
DELETE FROM `account`;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` (`phone`, `password`, `status`, `created_at`, `updated_at`, `state`) VALUES
	('0123456789', '12345678Aa', 'Đang hoạt động', '2022-07-24 18:14:39', NULL, 1);
/*!40000 ALTER TABLE `account` ENABLE KEYS */;

-- Dumping structure for table ebanking.branch
DROP TABLE IF EXISTS `branch`;
CREATE TABLE IF NOT EXISTS `branch` (
  `id_branch` int(11) NOT NULL AUTO_INCREMENT,
  `name_branch` varchar(50) NOT NULL,
  `location_branch` varchar(100) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_branch`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.branch: ~7 rows (approximately)
DELETE FROM `branch`;
/*!40000 ALTER TABLE `branch` DISABLE KEYS */;
INSERT INTO `branch` (`id_branch`, `name_branch`, `location_branch`, `state`) VALUES
	(1, 'Q9 ', 'Q9 HCM VN', 0),
	(2, 'Q8', 'Q8 HCM VN', 0),
	(3, 'Q1', 'Q1 HCM VN', 1),
	(4, 'Q5', 'Q5 HCM VN', 1),
	(5, 'Q10', 'Q10 HN VN', 1),
	(6, 'Q11', 'Q10 HN VN', 1),
	(7, 'Q12', 'Q12Q12 HN VN', 1);
/*!40000 ALTER TABLE `branch` ENABLE KEYS */;

-- Dumping structure for table ebanking.card
DROP TABLE IF EXISTS `card`;
CREATE TABLE IF NOT EXISTS `card` (
  `id_card` varchar(20) NOT NULL,
  `pin` varchar(6) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `expired_at` datetime NOT NULL,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_card`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.card: ~1 rows (approximately)
DELETE FROM `card`;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
INSERT INTO `card` (`id_card`, `pin`, `status`, `created_at`, `updated_at`, `expired_at`, `state`) VALUES
	('7564389873', '123456', 'Đã kích hoạt', '2022-07-24 18:12:15', NULL, '2025-07-24 23:12:15', 1);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.category_transaction: ~5 rows (approximately)
DELETE FROM `category_transaction`;
/*!40000 ALTER TABLE `category_transaction` DISABLE KEYS */;
INSERT INTO `category_transaction` (`id_category_transaction`, `name_transaction`, `description`, `fee_transaction`, `state`) VALUES
	(1, 'Gửi tiền', 'Gửi tiền vào tài khoản', 0, 1),
	(2, 'Rút tiền ', 'Rút tiền từ tải khoản', 1000, 1),
	(3, 'Chuyển tiền nội bộ', 'Chuyển tiền cho tài khoản khác cùng ngân hàng', 1000, 1),
	(4, 'Chuyển tiền liên ngân hàng', 'Chuyển tiền liên ngân hàng cho tài khoản khác', 2000, 1),
	(5, 'Thanh toán hóa đơn', 'Thanh toán hóa đơn điện, nước, internet', 500, 1);
/*!40000 ALTER TABLE `category_transaction` ENABLE KEYS */;

-- Dumping structure for table ebanking.customer
DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id_person` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `citizen_identity_card` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `age` int(11) NOT NULL,
  `money` bigint(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `id_card` varchar(20) NOT NULL,
  `id_branch` int(11) NOT NULL,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_person`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `mail` (`mail`),
  UNIQUE KEY `citizen_identity_card` (`citizen_identity_card`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.customer: ~1 rows (approximately)
DELETE FROM `customer`;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` (`id_person`, `name`, `citizen_identity_card`, `phone`, `mail`, `address`, `age`, `money`, `created_at`, `updated_at`, `id_card`, `id_branch`, `state`) VALUES
	(1, 'Nguyễn Văn A', '65474487509764637485', '0123456789', 'nguyenvana@gmail.com', 'HCM VN', 23, 0, '2022-07-24 18:07:17', NULL, '7564389873', 1, 1);
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
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
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
  `created_at` datetime NOT NULL,
  `period` int(1) NOT NULL,
  `interest_rate` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `withdrawaled_at` datetime DEFAULT NULL,
  `state` int(11) DEFAULT '1',
  PRIMARY KEY (`id_passbook`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table ebanking.passbook: ~1 rows (approximately)
DELETE FROM `passbook`;
/*!40000 ALTER TABLE `passbook` DISABLE KEYS */;
INSERT INTO `passbook` (`id_passbook`, `id_customer`, `money`, `created_at`, `period`, `interest_rate`, `status`, `description`, `updated_at`, `withdrawaled_at`, `state`) VALUES
	(1, 1, 1, '2022-07-24 18:18:26', 6, 1, 'Chưa được rút', 'Sổ tiết kiệm có kỳ hạn 6 tháng ', NULL, NULL, 1);
/*!40000 ALTER TABLE `passbook` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
