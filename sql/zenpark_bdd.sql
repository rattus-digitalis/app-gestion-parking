-- MySQL dump 10.13  Distrib 8.4.5, for Linux (x86_64)
--
-- Host: localhost    Database: zenpark
-- ------------------------------------------------------
-- Server version	8.4.5

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cars` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `marque` varchar(100) DEFAULT NULL,
  `modele` varchar(100) DEFAULT NULL,
  `immatriculation` varchar(50) DEFAULT NULL,
  `couleur` varchar(50) DEFAULT NULL,
  `type` enum('voiture','moto') DEFAULT 'voiture',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` VALUES (1,5,'audi','r8','acab','bleu','voiture');
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parking`
--

DROP TABLE IF EXISTS `parking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `parking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_place` varchar(10) NOT NULL,
  `etage` int DEFAULT '0',
  `type_place` enum('standard','handicap','electrique','moto') NOT NULL DEFAULT 'standard',
  `statut` enum('libre','occupe','reserve','maintenance') NOT NULL DEFAULT 'libre',
  `disponible_depuis` datetime DEFAULT NULL,
  `date_maj` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `derniere_reservation_id` int DEFAULT NULL,
  `commentaire` text,
  `actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_place` (`numero_place`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parking`
--

LOCK TABLES `parking` WRITE;
/*!40000 ALTER TABLE `parking` DISABLE KEYS */;
INSERT INTO `parking` VALUES (1,'A1',0,'standard','libre','2025-05-17 08:00:00','2025-05-18 05:00:17',NULL,'RAS',1),(2,'A2',0,'standard','occupe',NULL,'2025-05-18 05:00:17',NULL,'Voiture prsente',1),(3,'A3',0,'electrique','libre','2025-05-17 06:30:00','2025-05-18 05:00:17',NULL,'Chargeur dispo',1),(4,'B1',1,'moto','reserve',NULL,'2025-05-18 05:00:17',NULL,'Rservation  14h',1),(5,'B2',1,'handicap','libre','2025-05-17 09:15:00','2025-05-18 05:00:17',NULL,'Proche ascenseur',1),(6,'001',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(7,'002',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(8,'003',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(9,'004',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(10,'005',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(11,'006',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(12,'007',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(13,'008',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(14,'009',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(15,'010',0,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(16,'011',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(17,'012',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(18,'013',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(19,'014',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(20,'015',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(21,'016',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(22,'017',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(23,'018',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(24,'019',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(25,'020',1,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(26,'021',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(27,'022',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(28,'023',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(29,'024',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(30,'025',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(31,'026',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(32,'027',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(33,'028',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(34,'029',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(35,'030',2,'standard','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(36,'031',0,'handicap','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(37,'032',0,'handicap','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(38,'033',0,'handicap','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(39,'034',0,'handicap','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(40,'035',0,'handicap','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(41,'036',1,'electrique','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(42,'037',1,'electrique','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(43,'038',1,'electrique','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(44,'039',1,'electrique','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(45,'040',1,'electrique','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(46,'041',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(47,'042',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(48,'043',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(49,'044',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(50,'045',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(51,'046',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(52,'047',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(53,'048',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(54,'049',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1),(55,'050',2,'moto','libre',NULL,'2025-05-26 13:34:49',NULL,NULL,1);
/*!40000 ALTER TABLE `parking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `parking_id` int NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `status` enum('confirmed','cancelled','pending') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `car_id` int DEFAULT NULL,
  `paid` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `parking_id` (`parking_id`),
  KEY `car_id` (`car_id`),
  CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`parking_id`) REFERENCES `parking` (`id`),
  CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (1,5,5,'2025-05-28 14:00:00','2025-05-29 14:00:00','cancelled','2025-05-26 09:03:33','2025-05-26 09:06:28',1,0),(2,5,5,'2025-05-28 14:00:00','2025-05-29 14:00:00','cancelled','2025-05-26 09:30:26','2025-05-26 09:38:26',1,0),(3,5,4,'2025-05-30 14:00:00','2025-06-01 17:00:00','cancelled','2025-05-26 09:38:54','2025-05-26 11:48:59',NULL,0),(4,5,1,'2025-05-28 14:00:00','2025-05-31 14:00:00','cancelled','2025-05-26 09:51:12','2025-05-26 11:54:23',1,0);
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarifs`
--

DROP TABLE IF EXISTS `tarifs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarifs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL,
  `heure` float DEFAULT '0',
  `jour` float DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarifs`
--

LOCK TABLES `tarifs` WRITE;
/*!40000 ALTER TABLE `tarifs` DISABLE KEYS */;
INSERT INTO `tarifs` VALUES (1,'voiture',8,48),(2,'moto',4,32);
/*!40000 ALTER TABLE `tarifs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `numero_telephone` varchar(20) DEFAULT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` enum('utilisateur','admin') DEFAULT 'utilisateur',
  `statut` enum('actif','inactif','banni') DEFAULT 'actif',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `phone_verified` tinyint(1) DEFAULT '0',
  `password` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('offline','online') DEFAULT 'offline',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'test','test','test@hotmail.fr','0606060606',0,'$2y$10$HG2VQj5Cr7vZGgZwCMgayO7ohkE5msvEpvUfho6jKNNImeIqvwr7C','2025-05-26 06:48:56','user','online'),(6,'test','test','testtest@hotmail.fr','0648078747',0,'$2y$10$B3UCGwhtmWCzmtXEvkJNLujou5aXc9nBeepXUgNTtCmZ3Ro.wnpga','2025-05-26 07:20:35','user','offline'),(9,'root','root','root@hotmail.fr','0648078747',0,'$2y$10$GWEQFn/WRR/8QDVpGjK7peKwS41Y3RA/7FmbOZ9O0WBbo8N7kRVx6','2025-05-26 07:43:25','admin','online');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-26 13:35:52
