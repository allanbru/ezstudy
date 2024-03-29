-- MariaDB dump 10.18  Distrib 10.4.17-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: cms
-- ------------------------------------------------------
-- Server version	5.5.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` int(11) NOT NULL,
  `front` varchar(100) NOT NULL,
  `back` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` VALUES (18,3,'18','18','2021-03-02 02:10:13','2021-03-02 02:10:13'),(19,3,'19','19','2021-03-02 02:10:23','2021-03-02 02:10:23'),(21,3,'Frente','Verso','2021-03-02 02:20:10','2021-03-02 02:20:10'),(22,3,'Carro','Moto','2021-03-02 02:23:15','2021-03-02 02:23:15'),(23,3,'Gato','de Nariz','2021-03-02 02:23:23','2021-03-02 02:23:23');
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,1,'B├ísico','fas fa-archway','2021-03-01 22:54:43','2021-03-01 23:12:02'),(2,1,'Intermedi├írio','fab fa-adn','2021-03-01 23:11:51','2021-03-01 23:11:51'),(3,1,'Avan├ºado','fas fa-at','2021-03-01 23:19:59','2021-03-01 23:19:59'),(5,2,'Gatovski','fas fa-location-arrow','2021-03-01 23:25:48','2021-03-01 23:25:48'),(6,1,'Avan├ºado II','fas fa-arrow-right','2021-03-02 02:21:15','2021-03-02 02:21:15');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `body` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Primeira P├ígina','primeira-pagina','<p>Ol&aacute; mundo...</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<table style=\"border-collapse: collapse; width: 99.8201%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 24.6847%;\">a</td>\r\n<td style=\"width: 24.6847%;\">&nbsp;</td>\r\n<td style=\"width: 24.6847%;\">&nbsp;</td>\r\n<td style=\"width: 24.7748%;\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 24.6847%;\">b</td>\r\n<td style=\"width: 24.6847%;\">&nbsp;</td>\r\n<td style=\"width: 24.6847%;\">&nbsp;</td>\r\n<td style=\"width: 24.7748%;\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 24.6847%;\">c</td>\r\n<td style=\"width: 24.6847%;\">&nbsp;</td>\r\n<td style=\"width: 24.6847%;\">&nbsp;</td>\r\n<td style=\"width: 24.7748%;\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 24.6847%;\">d</td>\r\n<td style=\"width: 24.6847%;\">&nbsp;</td>\r\n<td style=\"width: 24.6847%;\">&nbsp;</td>\r\n<td style=\"width: 24.7748%;\">&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>','2021-03-01 18:17:41','2021-03-01 18:45:43'),(2,'Segunda P├ígina','segunda-pagina','<p><img src=\"http://localhost:8000/media/images/1551614618684.png\" alt=\"\" width=\"393\" height=\"254\" /></p>\r\n<p>Segunda P&aacute;gina</p>','2021-03-01 18:35:41','2021-03-01 20:11:27'),(4,'Sobre','sobre','<p>Conte&uacute;do da se&ccedil;&atilde;o Sobre</p>','2021-03-01 21:59:23','2021-03-01 21:59:23');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `content` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'title','EZStudy',NULL,'2021-03-02 02:24:49'),(2,'subtitle','Aprender nunca foi t├úo f├ícil! Aproveite ao m├íximo o estudo ativo!',NULL,'2021-03-02 02:24:49'),(3,'email','allan@allanbr.net',NULL,'2021-03-02 02:24:49'),(4,'bgcolor','#e1ff00',NULL,'2021-03-02 02:24:49'),(5,'textcolor','#39e7fe',NULL,'2021-03-02 02:24:49'),(6,'facebook','http://fb.me/allanbru','2021-03-01 18:35:05','2021-03-02 02:24:49'),(7,'twitter','#','2021-03-01 18:35:21','2021-03-02 02:24:49'),(8,'instagram','https://www.instagram.com/allanbrunstein/','2021-03-01 18:35:36','2021-03-02 02:24:49'),(9,'about','http://localhost:8000/sobre','2021-03-01 18:54:53','2021-03-02 02:24:49'),(10,'termsofuse','#','2021-03-01 18:54:55','2021-03-02 02:24:49'),(11,'privacypolicy','#','2021-03-01 18:54:56','2021-03-02 02:24:49');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `access` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Allan Brunstein','allan5@globo.com',NULL,'$2y$10$enRCOT/uc91S/b.Zm1.uEuwZOp.kye9BNVbHMrbWFjsVKJiS1KMpC','mC6uePGaMi6FUf1VEaKKJZaqBOEfkyjKVd3zxe1oSdF2KNmWxU0taOa37Yr6',1,'2021-02-28 23:02:39','2021-03-01 00:37:08'),(2,'Gato de Nariz','abcdef297@gmail.com',NULL,'$2y$10$mAz8cmkIkNZrN3Nr7LSxTeN/ltrHsdNmwzouuE/BeeFjAm0B0nviO',NULL,0,'2021-02-28 23:53:56','2021-02-28 23:53:56'),(4,'Usuario 3','abc@def.com',NULL,'$2y$10$WQ6e1zXWShOaLNOWhJnWRuRwyaGNpm1Gl5BVTJ7OazrMyOh0SsvsS',NULL,0,'2021-03-01 05:09:38','2021-03-01 05:14:24');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) DEFAULT NULL,
  `page` varchar(100) DEFAULT NULL,
  `date_access` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `visitors`
--

LOCK TABLES `visitors` WRITE;
/*!40000 ALTER TABLE `visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `visitors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-03-01 20:27:43
