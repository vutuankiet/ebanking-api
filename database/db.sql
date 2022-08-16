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


-- Dumping database structure for quan_ly_diem_thi
CREATE DATABASE IF NOT EXISTS `quan_ly_diem_thi` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `quan_ly_diem_thi`;

-- Dumping structure for table quan_ly_diem_thi.mon_hoc
CREATE TABLE IF NOT EXISTS `mon_hoc` (
  `ma_mon` varchar(128) NOT NULL,
  `ten_mon_hoc` varchar(128) NOT NULL,
  `chi_so_tin` int(11) NOT NULL,
  PRIMARY KEY (`ma_mon`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table quan_ly_diem_thi.mon_hoc: ~0 rows (approximately)
DELETE FROM `mon_hoc`;
/*!40000 ALTER TABLE `mon_hoc` DISABLE KEYS */;
/*!40000 ALTER TABLE `mon_hoc` ENABLE KEYS */;

-- Dumping structure for table quan_ly_diem_thi.mon_hoc_nghanh_hoc
CREATE TABLE IF NOT EXISTS `mon_hoc_nghanh_hoc` (
  `id` int(11) NOT NULL,
  `ma_nganh` varchar(128) NOT NULL,
  `ma_mon` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_mon_hoc_nghanh_hoc_nganh_hoc` (`ma_nganh`),
  KEY `FK_mon_hoc_nghanh_hoc_mon_hoc` (`ma_mon`),
  CONSTRAINT `FK_mon_hoc_nghanh_hoc_mon_hoc` FOREIGN KEY (`ma_mon`) REFERENCES `mon_hoc` (`ma_mon`),
  CONSTRAINT `FK_mon_hoc_nghanh_hoc_nganh_hoc` FOREIGN KEY (`ma_nganh`) REFERENCES `nganh_hoc` (`ma_chuyen_nganh`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table quan_ly_diem_thi.mon_hoc_nghanh_hoc: ~0 rows (approximately)
DELETE FROM `mon_hoc_nghanh_hoc`;
/*!40000 ALTER TABLE `mon_hoc_nghanh_hoc` DISABLE KEYS */;
/*!40000 ALTER TABLE `mon_hoc_nghanh_hoc` ENABLE KEYS */;

-- Dumping structure for table quan_ly_diem_thi.mon_hoc_sinh_vien
CREATE TABLE IF NOT EXISTS `mon_hoc_sinh_vien` (
  `id` int(11) NOT NULL,
  `ma_sv` varchar(128) NOT NULL,
  `ma_mon` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_mon_hoc_sinh_vien_mon_hoc` (`ma_mon`),
  KEY `FK_mon_hoc_sinh_vien_sinh_vien` (`ma_sv`),
  CONSTRAINT `FK_mon_hoc_sinh_vien_mon_hoc` FOREIGN KEY (`ma_mon`) REFERENCES `mon_hoc` (`ma_mon`),
  CONSTRAINT `FK_mon_hoc_sinh_vien_sinh_vien` FOREIGN KEY (`ma_sv`) REFERENCES `sinh_vien` (`ma_sv`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table quan_ly_diem_thi.mon_hoc_sinh_vien: ~0 rows (approximately)
DELETE FROM `mon_hoc_sinh_vien`;
/*!40000 ALTER TABLE `mon_hoc_sinh_vien` DISABLE KEYS */;
/*!40000 ALTER TABLE `mon_hoc_sinh_vien` ENABLE KEYS */;

-- Dumping structure for table quan_ly_diem_thi.nganh_hoc
CREATE TABLE IF NOT EXISTS `nganh_hoc` (
  `ma_chuyen_nganh` varchar(128) NOT NULL,
  `ten_nghanh_hoc` varchar(255) NOT NULL,
  PRIMARY KEY (`ma_chuyen_nganh`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table quan_ly_diem_thi.nganh_hoc: ~0 rows (approximately)
DELETE FROM `nganh_hoc`;
/*!40000 ALTER TABLE `nganh_hoc` DISABLE KEYS */;
/*!40000 ALTER TABLE `nganh_hoc` ENABLE KEYS */;

-- Dumping structure for table quan_ly_diem_thi.sinh_vien
CREATE TABLE IF NOT EXISTS `sinh_vien` (
  `ma_sv` varchar(128) NOT NULL,
  `gioi_tinh` int(11) NOT NULL,
  `tuoi` int(11) NOT NULL,
  `ma_nganh` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`ma_sv`),
  KEY `ma_nganh` (`ma_nganh`),
  CONSTRAINT `sinh_vien_ibfk_1` FOREIGN KEY (`ma_nganh`) REFERENCES `nganh_hoc` (`ma_chuyen_nganh`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table quan_ly_diem_thi.sinh_vien: ~0 rows (approximately)
DELETE FROM `sinh_vien`;
/*!40000 ALTER TABLE `sinh_vien` DISABLE KEYS */;
/*!40000 ALTER TABLE `sinh_vien` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
