-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.24-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para happy_pets_db
CREATE DATABASE IF NOT EXISTS `happy_pets_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `happy_pets_db`;

-- Volcando estructura para tabla happy_pets_db.medicines
CREATE TABLE IF NOT EXISTS `medicines` (
  `id_medicine` int(11) NOT NULL,
  `medicine_name` varchar(60) NOT NULL,
  `type` varchar(20) NOT NULL,
  `concentration` varchar(30) NOT NULL,
  PRIMARY KEY (`id_medicine`) USING BTREE,
  UNIQUE KEY `MEDICINES_PK` (`id_medicine`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.owner
CREATE TABLE IF NOT EXISTS `owner` (
  `id_owner` int(12) NOT NULL,
  `name` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(60) NOT NULL,
  PRIMARY KEY (`id_owner`) USING BTREE,
  UNIQUE KEY `OWNER_PK` (`id_owner`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.pet
CREATE TABLE IF NOT EXISTS `pet` (
  `id_pet` int(11) NOT NULL AUTO_INCREMENT,
  `id_owner` varchar(12) NOT NULL,
  `name_pet` varchar(60) NOT NULL,
  `color` varchar(60) NOT NULL,
  `species` varchar(60) NOT NULL,
  `breed` varchar(60) NOT NULL,
  PRIMARY KEY (`id_pet`),
  UNIQUE KEY `PET_PK` (`id_pet`),
  KEY `OWN_FK` (`id_owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.state
CREATE TABLE IF NOT EXISTS `state` (
  `id_state` int(11) NOT NULL,
  `state_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id_state`) USING BTREE,
  UNIQUE KEY `STATE_PK` (`id_state`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.state_relation
CREATE TABLE IF NOT EXISTS `state_relation` (
  `id_visit` int(11) NOT NULL,
  `id_state` int(11) NOT NULL,
  PRIMARY KEY (`id_visit`,`id_state`) USING BTREE,
  UNIQUE KEY `STATE_RELATION_PK` (`id_visit`,`id_state`) USING BTREE,
  KEY `STATE_RELATION2_FK` (`id_state`) USING BTREE,
  KEY `STATE_RELATION_FK` (`id_visit`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.tip_user
CREATE TABLE IF NOT EXISTS `tip_user` (
  `id_tip_user` int(3) NOT NULL AUTO_INCREMENT,
  `tip_user` varchar(30) NOT NULL,
  PRIMARY KEY (`id_tip_user`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.user
CREATE TABLE IF NOT EXISTS `user` (
  `cedula` int(11) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `user` varchar(10) NOT NULL,
  `password` varchar(15) NOT NULL,
  `id_tip_user` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.veterinarian
CREATE TABLE IF NOT EXISTS `veterinarian` (
  `id_vet` int(12) NOT NULL AUTO_INCREMENT,
  `name_vet` varchar(60) NOT NULL,
  `lastname_vet` varchar(60) NOT NULL,
  `telephone_vet` varchar(20) NOT NULL,
  `address_vet` varchar(100) NOT NULL,
  `professional_lic` varchar(20) NOT NULL,
  PRIMARY KEY (`id_vet`),
  UNIQUE KEY `VETERINARIAN_PK` (`id_vet`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.vet_visit
CREATE TABLE IF NOT EXISTS `vet_visit` (
  `id_visit` int(11) NOT NULL AUTO_INCREMENT,
  `id_vet` int(11) NOT NULL DEFAULT 0,
  `id_pet` int(11) NOT NULL,
  `temperature` float NOT NULL,
  `weight` float NOT NULL,
  `breathing_freq` float NOT NULL,
  `heart_rate` float NOT NULL,
  `visit_date` date NOT NULL,
  `recomendations` varchar(500) NOT NULL,
  PRIMARY KEY (`id_visit`) USING BTREE,
  UNIQUE KEY `VET_VISIT_PK` (`id_visit`) USING BTREE,
  KEY `MAKES_FK` (`id_vet`) USING BTREE,
  KEY `TREATED_IN_FK` (`id_pet`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla happy_pets_db.visit_prescription
CREATE TABLE IF NOT EXISTS `visit_prescription` (
  `id_prescription` int(11) NOT NULL,
  `id_visit` int(11) NOT NULL,
  `id_medicine` int(11) NOT NULL,
  `medicine_dosage` varchar(60) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id_prescription`) USING BTREE,
  UNIQUE KEY `VISIT_PRESCRIPTION_PK` (`id_prescription`) USING BTREE,
  KEY `MAY_HAVE_FK` (`id_visit`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
