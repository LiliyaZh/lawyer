-- MySQL dump 10.13  Distrib 8.0.35, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: lawyer1
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `clients_of_company`
--

DROP TABLE IF EXISTS `clients_of_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients_of_company` (
  `key_of_client` int(11) NOT NULL AUTO_INCREMENT,
  `name_of_client` varchar(35) NOT NULL,
  `surname_of_client` varchar(35) NOT NULL,
  `telephone_of_client` varchar(50) NOT NULL,
  `email_of_client` varchar(50) NOT NULL,
  `password_of_client` varchar(128) NOT NULL,
  PRIMARY KEY (`key_of_client`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients_of_company`
--

LOCK TABLES `clients_of_company` WRITE;
/*!40000 ALTER TABLE `clients_of_company` DISABLE KEYS */;
INSERT INTO `clients_of_company` VALUES (1,'Виктор','Иванов','+7(567) 123-45-67','viktor.ivanov@yandex.ru','$2y$10$nyKjgAPifjtL.Oz6aUH6SOFl/S8hCkeELAKh5d441CxKqBbHr8NE6');
/*!40000 ALTER TABLE `clients_of_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedbacks_of_clients`
--

DROP TABLE IF EXISTS `feedbacks_of_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedbacks_of_clients` (
  `key_of_feedback` int(11) NOT NULL AUTO_INCREMENT,
  `key_of_client` int(11) NOT NULL,
  `message_of_feedback` text NOT NULL,
  `date_time_of_feedback` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`key_of_feedback`),
  KEY `R_45` (`key_of_client`),
  CONSTRAINT `R_45` FOREIGN KEY (`key_of_client`) REFERENCES `clients_of_company` (`key_of_client`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedbacks_of_clients`
--

LOCK TABLES `feedbacks_of_clients` WRITE;
/*!40000 ALTER TABLE `feedbacks_of_clients` DISABLE KEYS */;
INSERT INTO `feedbacks_of_clients` VALUES (1,1,'Тестовое сообщение № 1','2024-09-29 15:36:38'),(2,1,'Тестовое сообщение № 2','2024-09-29 15:37:12'),(3,1,'Тестовое сообщение № 3','2024-09-29 15:38:07'),(4,1,'Тестовое сообщение № 4','2024-09-29 15:39:23'),(5,1,'Тестовое сообщение № 5','2024-09-29 15:40:45'),(6,1,'Тестовое сообщение № 6','2024-09-29 15:41:57');
/*!40000 ALTER TABLE `feedbacks_of_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files_of_requests`
--

DROP TABLE IF EXISTS `files_of_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `files_of_requests` (
  `key_of_file` int(11) NOT NULL AUTO_INCREMENT,
  `name_of_file` varchar(255) NOT NULL,
  `server_name_of_file` varchar(32) NOT NULL,
  `size_of_file` int(11) NOT NULL,
  `date_time_upload_of_file` timestamp NOT NULL DEFAULT current_timestamp(),
  `key_of_client` int(11) DEFAULT NULL,
  `key_of_worker` int(11) DEFAULT NULL,
  `key_of_request` int(11) NOT NULL,
  PRIMARY KEY (`key_of_file`),
  KEY `R_40` (`key_of_client`),
  KEY `R_41` (`key_of_worker`),
  KEY `R_43` (`key_of_request`),
  CONSTRAINT `R_40` FOREIGN KEY (`key_of_client`) REFERENCES `clients_of_company` (`key_of_client`),
  CONSTRAINT `R_41` FOREIGN KEY (`key_of_worker`) REFERENCES `workers_in_company` (`key_of_worker`),
  CONSTRAINT `R_43` FOREIGN KEY (`key_of_request`) REFERENCES `requests_of_clients` (`key_of_request`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files_of_requests`
--

LOCK TABLES `files_of_requests` WRITE;
/*!40000 ALTER TABLE `files_of_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `files_of_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history_of_requests`
--

DROP TABLE IF EXISTS `history_of_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `history_of_requests` (
  `key_of_history` int(11) NOT NULL AUTO_INCREMENT,
  `date_time_of_history` timestamp NOT NULL DEFAULT current_timestamp(),
  `key_of_status` int(11) NOT NULL,
  `key_of_request` int(11) NOT NULL,
  PRIMARY KEY (`key_of_history`),
  KEY `R_35` (`key_of_status`),
  KEY `R_36` (`key_of_request`),
  CONSTRAINT `R_35` FOREIGN KEY (`key_of_status`) REFERENCES `statuses_of_requests` (`key_of_status`),
  CONSTRAINT `R_36` FOREIGN KEY (`key_of_request`) REFERENCES `requests_of_clients` (`key_of_request`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history_of_requests`
--

LOCK TABLES `history_of_requests` WRITE;
/*!40000 ALTER TABLE `history_of_requests` DISABLE KEYS */;
INSERT INTO `history_of_requests` VALUES (1,'2024-07-07 20:26:08',1,2),(2,'2024-07-08 14:55:10',1,3),(3,'2024-07-08 06:01:33',2,2),(4,'2024-07-08 09:03:23',3,2),(5,'2024-07-08 12:02:36',4,2),(6,'2024-09-29 17:51:45',1,6);
/*!40000 ALTER TABLE `history_of_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages_in_requests`
--

DROP TABLE IF EXISTS `messages_in_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages_in_requests` (
  `key_of_message` int(11) NOT NULL AUTO_INCREMENT,
  `date_time_of_message` timestamp NOT NULL DEFAULT current_timestamp(),
  `text_of_message` text NOT NULL,
  `key_of_client` int(11) DEFAULT NULL,
  `key_of_worker` int(11) DEFAULT NULL,
  `key_of_request` int(11) NOT NULL,
  PRIMARY KEY (`key_of_message`),
  KEY `R_38` (`key_of_client`),
  KEY `R_39` (`key_of_worker`),
  KEY `R_42` (`key_of_request`),
  CONSTRAINT `R_38` FOREIGN KEY (`key_of_client`) REFERENCES `clients_of_company` (`key_of_client`),
  CONSTRAINT `R_39` FOREIGN KEY (`key_of_worker`) REFERENCES `workers_in_company` (`key_of_worker`),
  CONSTRAINT `R_42` FOREIGN KEY (`key_of_request`) REFERENCES `requests_of_clients` (`key_of_request`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages_in_requests`
--

LOCK TABLES `messages_in_requests` WRITE;
/*!40000 ALTER TABLE `messages_in_requests` DISABLE KEYS */;
INSERT INTO `messages_in_requests` VALUES (1,'2024-07-07 20:26:08','Прошу оказать помощь в вопросе наследования квартиры',1,NULL,2),(2,'2024-07-08 14:55:10','Проблема при суде по ДТП',1,NULL,3),(3,'2024-07-08 06:35:18','Я вас наберу в 12 часов и проконсультирую',NULL,2,2),(4,'2024-07-08 11:15:11','Большое Вам спасибо за консультацию',1,NULL,2),(5,'2024-07-09 11:32:12','Не за что',NULL,2,3),(6,'2024-07-09 14:32:56','Обращайтесь',NULL,2,3),(9,'2024-09-29 17:51:45','Нужна помощь по ДТП',1,NULL,6);
/*!40000 ALTER TABLE `messages_in_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests_of_clients`
--

DROP TABLE IF EXISTS `requests_of_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests_of_clients` (
  `key_of_request` int(11) NOT NULL AUTO_INCREMENT,
  `date_time_of_request` timestamp NOT NULL DEFAULT current_timestamp(),
  `key_of_type` int(11) DEFAULT NULL,
  `key_of_status` int(11) NOT NULL DEFAULT 1,
  `key_of_client` int(11) NOT NULL,
  `key_of_worker` int(11) DEFAULT NULL,
  `comment_of_request` text DEFAULT NULL,
  PRIMARY KEY (`key_of_request`),
  KEY `R_33` (`key_of_type`),
  KEY `R_34` (`key_of_status`),
  KEY `R_37` (`key_of_client`),
  KEY `R_44` (`key_of_worker`),
  CONSTRAINT `R_33` FOREIGN KEY (`key_of_type`) REFERENCES `types_of_requests` (`key_of_type`),
  CONSTRAINT `R_34` FOREIGN KEY (`key_of_status`) REFERENCES `statuses_of_requests` (`key_of_status`),
  CONSTRAINT `R_37` FOREIGN KEY (`key_of_client`) REFERENCES `clients_of_company` (`key_of_client`),
  CONSTRAINT `R_44` FOREIGN KEY (`key_of_worker`) REFERENCES `workers_in_company` (`key_of_worker`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests_of_clients`
--

LOCK TABLES `requests_of_clients` WRITE;
/*!40000 ALTER TABLE `requests_of_clients` DISABLE KEYS */;
INSERT INTO `requests_of_clients` VALUES (2,'2024-07-07 20:26:08',1,4,1,2,'Оказана консультация по вопросу наследования квартиры отца'),(3,'2024-07-08 14:55:10',1,1,1,3,NULL),(6,'2024-09-29 17:51:45',NULL,1,1,NULL,NULL);
/*!40000 ALTER TABLE `requests_of_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statuses_of_requests`
--

DROP TABLE IF EXISTS `statuses_of_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `statuses_of_requests` (
  `key_of_status` int(11) NOT NULL AUTO_INCREMENT,
  `name_of_status` varchar(80) NOT NULL,
  PRIMARY KEY (`key_of_status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statuses_of_requests`
--

LOCK TABLES `statuses_of_requests` WRITE;
/*!40000 ALTER TABLE `statuses_of_requests` DISABLE KEYS */;
INSERT INTO `statuses_of_requests` VALUES (1,'Заявка зарегистрирована'),(2,'Заявка делегирована юристконсульту'),(3,'Оказание юридической помощи'),(4,'Согласование результатов'),(5,'Помощь оказана');
/*!40000 ALTER TABLE `statuses_of_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `types_of_requests`
--

DROP TABLE IF EXISTS `types_of_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `types_of_requests` (
  `key_of_type` int(11) NOT NULL AUTO_INCREMENT,
  `name_of_type` varchar(200) NOT NULL,
  PRIMARY KEY (`key_of_type`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types_of_requests`
--

LOCK TABLES `types_of_requests` WRITE;
/*!40000 ALTER TABLE `types_of_requests` DISABLE KEYS */;
INSERT INTO `types_of_requests` VALUES (1,'Консультация'),(2,'Представительство в суде'),(3,'Составление документов'),(4,'Оспаривание штрафов'),(5,'Защита прав потребителей'),(6,'Трудовые споры'),(7,'Жилищные вопросы'),(8,'Семейные вопросы'),(9,'Защита прав и свобод человека'),(10,'Социальные выплаты и льготы');
/*!40000 ALTER TABLE `types_of_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workers_in_company`
--

DROP TABLE IF EXISTS `workers_in_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `workers_in_company` (
  `key_of_worker` int(11) NOT NULL AUTO_INCREMENT,
  `name_of_worker` varchar(35) NOT NULL,
  `surname_of_worker` varchar(35) NOT NULL,
  `information_of_worker` text NOT NULL,
  `telephone_of_worker` varchar(50) NOT NULL,
  `email_of_worker` varchar(50) NOT NULL,
  `password_of_worker` varchar(128) NOT NULL,
  `type_of_worker` int(11) DEFAULT 3,
  PRIMARY KEY (`key_of_worker`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workers_in_company`
--

LOCK TABLES `workers_in_company` WRITE;
/*!40000 ALTER TABLE `workers_in_company` DISABLE KEYS */;
INSERT INTO `workers_in_company` VALUES (1,'Андрей','Гундаров','Администратор','+7(678) 345-12-34','andrew.gundarov@yandex.ru','$2y$10$nyKjgAPifjtL.Oz6aUH6SOFl/S8hCkeELAKh5d441CxKqBbHr8NE6',1),(2,'Михаил','Велесов','Юристконсульт','+7(678) 345-12-35','mikle.velesov@yandex.ru','$2y$10$nyKjgAPifjtL.Oz6aUH6SOFl/S8hCkeELAKh5d441CxKqBbHr8NE6',3),(3,'Валентин','Обрушкин','Юристконсульт','+7(678) 345-12-36','val.obrushkin@yandex.ru','$2y$10$nyKjgAPifjtL.Oz6aUH6SOFl/S8hCkeELAKh5d441CxKqBbHr8NE6',3),(4,'Сергей','Липоедов','Делопроизводитель','+7(678) 345-12-37','serg.lipoedov@yandex.ru','$2y$10$nyKjgAPifjtL.Oz6aUH6SOFl/S8hCkeELAKh5d441CxKqBbHr8NE6',2);
/*!40000 ALTER TABLE `workers_in_company` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-30  7:59:01
