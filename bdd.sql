-- MySQL dump 10.13  Distrib 8.0.26, for Linux (x86_64)
--
-- Host: localhost    Database: bdd
-- ------------------------------------------------------
-- Server version	8.0.26-0ubuntu0.20.04.2

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
-- Table structure for table `en_cours`
--

DROP TABLE IF EXISTS `en_cours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `en_cours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `users` int NOT NULL,
  `exercice_python` int DEFAULT NULL,
  `exercice_java` int DEFAULT NULL,
  `exercice_c` int DEFAULT NULL,
  `token` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_encours` (`users`),
  KEY `fk_exo_encours` (`exercice_python`),
  KEY `fk_java` (`exercice_java`),
  CONSTRAINT `fk_exo_encours` FOREIGN KEY (`exercice_python`) REFERENCES `exercice_python` (`id`),
  CONSTRAINT `fk_java` FOREIGN KEY (`exercice_java`) REFERENCES `exercice_java` (`id`),
  CONSTRAINT `fk_users_encours` FOREIGN KEY (`users`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=616 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `en_cours`
--

LOCK TABLES `en_cours` WRITE;
/*!40000 ALTER TABLE `en_cours` DISABLE KEYS */;
/*!40000 ALTER TABLE `en_cours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exercice_c`
--

DROP TABLE IF EXISTS `exercice_c`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exercice_c` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` int NOT NULL,
  `enonce` mediumtext NOT NULL,
  `reponse` mediumtext NOT NULL,
  `titre` mediumtext NOT NULL,
  `ajout_script` mediumtext NOT NULL,
  `remote` mediumtext NOT NULL,
  `flag` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exercice_c`
--

LOCK TABLES `exercice_c` WRITE;
/*!40000 ALTER TABLE `exercice_c` DISABLE KEYS */;
/*!40000 ALTER TABLE `exercice_c` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exercice_java`
--

DROP TABLE IF EXISTS `exercice_java`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exercice_java` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` int NOT NULL,
  `enonce` mediumtext NOT NULL,
  `reponse` mediumtext NOT NULL,
  `titre` mediumtext NOT NULL,
  `ajout_script` mediumtext NOT NULL,
  `remote` mediumtext NOT NULL,
  `flag` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exercice_java`
--

LOCK TABLES `exercice_java` WRITE;
/*!40000 ALTER TABLE `exercice_java` DISABLE KEYS */;
/*!40000 ALTER TABLE `exercice_java` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exercice_python`
--

DROP TABLE IF EXISTS `exercice_python`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exercice_python` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` int NOT NULL,
  `enonce` mediumtext NOT NULL,
  `reponse` mediumtext NOT NULL,
  `titre` varchar(150) DEFAULT NULL,
  `ajout_script` mediumtext NOT NULL,
  `remote` varchar(2000) NOT NULL,
  `flag` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exercice_python`
--

LOCK TABLES `exercice_python` WRITE;
/*!40000 ALTER TABLE `exercice_python` DISABLE KEYS */;
/*!40000 ALTER TABLE `exercice_python` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `solved_python` varchar(1000) DEFAULT NULL,
  `isAdmin` int NOT NULL DEFAULT '0',
  `isBan` int NOT NULL DEFAULT '0',
  `reasonBan` varchar(1000) NOT NULL,
  `solved_java` varchar(10000) NOT NULL,
  `solved_c` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
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

-- Dump completed on 2021-09-28 18:41:30
