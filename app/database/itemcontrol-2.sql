-- MariaDB dump 10.19  Distrib 10.4.34-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: localhost    Database: itemcontrol
-- ------------------------------------------------------
-- Server version	10.4.34-MariaDB-1:10.4.34+maria~ubu2004

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
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (4,'Aseo'),(2,'Bebidas'),(6,'Dulces'),(3,'Enlatados'),(5,'Higiene'),(1,'Lácteos');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario_solicitudes`
--

DROP TABLE IF EXISTS `inventario_solicitudes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventario_solicitudes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `precio` decimal(10,2) DEFAULT NULL,
  `creado_por` varchar(50) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario_solicitudes`
--

LOCK TABLES `inventario_solicitudes` WRITE;
/*!40000 ALTER TABLE `inventario_solicitudes` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventario_solicitudes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `precio` decimal(10,2) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (7,'PIlas','latas','2025-05-04','Enlatados',123,450.40,'2025-06-04 23:34:46');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('gerente','abarrotero') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = activo, 0 = inactivo',
  `codigo_recuperacion` varchar(6) DEFAULT NULL,
  `expiracion_codigo` datetime DEFAULT NULL,
  `mfa_codigo` varchar(6) DEFAULT NULL,
  `mfa_expiracion` datetime DEFAULT NULL,
  `mfa_intentos` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (9,'admin','carreonriojas+admin@gmail.com','$2y$10$/ndGjcWyFAKsc5MUkcvgwenYuBGC/jMt6yR2kiKdo2GGRdcR88TYi','gerente',1,NULL,NULL,NULL,NULL,0),(10,'vendedor','vendedor@tienda.com','$2y$10$lXSZg/S.5R.MKtBBrDvyzulW14pnuo37J6nOslNAK5pYg5/zVvBXa','abarrotero',1,NULL,NULL,NULL,NULL,0),(14,'brau','brau@tienda.com','$2y$10$nxmO5eiRayl.q1mnD1fpt.D/AYlAGPw88G.vvVrF6yNGKeY4p7lrW','abarrotero',0,NULL,NULL,NULL,NULL,0),(15,'Rogelio ESTUCHE','Rogelio ESTUCHE@tienda.com','$2y$10$2UYgXjA85chmE/lGm2hg8.yFAcR/9hT2qehLzv5cl30/Lb6iufLC.','gerente',0,NULL,NULL,NULL,NULL,0),(16,'rogelio','rogelio@tienda.com','$2y$10$bxq.uglB4g9.WTWLs/63luEDSdBWeQwKZXAdtWZoCviVYQxSRBHFy','abarrotero',1,NULL,NULL,NULL,NULL,0),(17,'Keyla','carreonriojas@gmail.com','$2y$10$Swb0n1hDEs84EZBP7k0Yneoi3WmV1JcAqlbR6/IUIl7BV29fd8Ub2','gerente',1,'282733','2025-12-01 04:13:32',NULL,NULL,0),(18,'braulio','riojas68bccr@gmail.com','$2y$10$URSnfdaN.XYgJz/yd93PGeK7iTN4Fn2Ix0K.ynFGYK4nGfS/fN2Ly','abarrotero',1,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-01  5:40:39
