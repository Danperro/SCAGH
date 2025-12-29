-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: scagh
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acceso`
--

DROP TABLE IF EXISTS `acceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `acceso` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rol_id` bigint(20) unsigned NOT NULL,
  `menu_id` bigint(20) unsigned NOT NULL,
  `permiso_id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acceso_rol_id_foreign` (`rol_id`),
  KEY `acceso_menu_id_foreign` (`menu_id`),
  KEY `acceso_permiso_id_foreign` (`permiso_id`),
  CONSTRAINT `acceso_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`),
  CONSTRAINT `acceso_permiso_id_foreign` FOREIGN KEY (`permiso_id`) REFERENCES `permiso` (`id`),
  CONSTRAINT `acceso_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acceso`
--

LOCK TABLES `acceso` WRITE;
/*!40000 ALTER TABLE `acceso` DISABLE KEYS */;
/*!40000 ALTER TABLE `acceso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asistencia`
--

DROP TABLE IF EXISTS `asistencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `horario_curso_docente_id` bigint(20) unsigned NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `hora_registro` time NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asistencia_horario_curso_docente_id_foreign` (`horario_curso_docente_id`),
  CONSTRAINT `asistencia_horario_curso_docente_id_foreign` FOREIGN KEY (`horario_curso_docente_id`) REFERENCES `horario_curso_docente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia`
--

LOCK TABLES `asistencia` WRITE;
/*!40000 ALTER TABLE `asistencia` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asistencia_estudiante`
--

DROP TABLE IF EXISTS `asistencia_estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia_estudiante` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asistencia_id` bigint(20) unsigned NOT NULL,
  `estudiante_id` bigint(20) unsigned NOT NULL,
  `tipo_asistencia_id` bigint(20) unsigned NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asistencia_estudiante_asistencia_id_foreign` (`asistencia_id`),
  KEY `asistencia_estudiante_estudiante_id_foreign` (`estudiante_id`),
  KEY `asistencia_estudiante_tipo_asistencia_id_foreign` (`tipo_asistencia_id`),
  CONSTRAINT `asistencia_estudiante_asistencia_id_foreign` FOREIGN KEY (`asistencia_id`) REFERENCES `asistencia` (`id`),
  CONSTRAINT `asistencia_estudiante_estudiante_id_foreign` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id`),
  CONSTRAINT `asistencia_estudiante_tipo_asistencia_id_foreign` FOREIGN KEY (`tipo_asistencia_id`) REFERENCES `catalogo` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia_estudiante`
--

LOCK TABLES `asistencia_estudiante` WRITE;
/*!40000 ALTER TABLE `asistencia_estudiante` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistencia_estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carrera`
--

DROP TABLE IF EXISTS `carrera`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrera` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `facultad_id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `ciclos_total` tinyint(3) unsigned NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carrera_nombre_unique` (`nombre`),
  KEY `carrera_facultad_id_foreign` (`facultad_id`),
  CONSTRAINT `carrera_facultad_id_foreign` FOREIGN KEY (`facultad_id`) REFERENCES `catalogo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrera`
--

LOCK TABLES `carrera` WRITE;
/*!40000 ALTER TABLE `carrera` DISABLE KEYS */;
INSERT INTO `carrera` VALUES (1,11,'CARRERA PROFESIONAL DE EDUCACIÓN INICIAL BILINGÜE',12,1,NULL,NULL,NULL,NULL,NULL,NULL),(2,11,'CARRERA PROFESIONAL DE EDUCACIÓN PRIMARIA BILINGÜE',12,1,NULL,NULL,NULL,NULL,NULL,NULL),(3,12,'CARRERA PROFESIONAL DE INGENIERÍA AGROFORESTAL ACUICOLA',12,1,NULL,NULL,NULL,NULL,NULL,NULL),(4,12,'CARRERA PROFESIONAL DE INGENIERÍA AGROINDUSTRIAL',12,1,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `carrera` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo`
--

DROP TABLE IF EXISTS `catalogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `padre_id` bigint(20) unsigned DEFAULT NULL,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalogo_padre_id_foreign` (`padre_id`),
  CONSTRAINT `catalogo_padre_id_foreign` FOREIGN KEY (`padre_id`) REFERENCES `catalogo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo`
--

LOCK TABLES `catalogo` WRITE;
/*!40000 ALTER TABLE `catalogo` DISABLE KEYS */;
INSERT INTO `catalogo` VALUES (1,'AREA',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'CUC',1,1,NULL,NULL,NULL,NULL,NULL,NULL),(3,'SEMANA',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'FACULTAD',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,'LUNES',1,3,NULL,NULL,NULL,NULL,NULL,NULL),(6,'MARTES',1,3,NULL,NULL,NULL,NULL,NULL,NULL),(7,'MIERCOLES',1,3,NULL,NULL,NULL,NULL,NULL,NULL),(8,'JUEVES',1,3,NULL,NULL,NULL,NULL,NULL,NULL),(9,'VIERNES',1,3,NULL,NULL,NULL,NULL,NULL,NULL),(10,'SABADO',1,3,NULL,NULL,NULL,NULL,NULL,NULL),(11,'FACULTAD DE EDUCACIÓN INTERCULTURAL Y HUMANIDADES',1,4,NULL,NULL,NULL,NULL,NULL,NULL),(12,'FACULTAD DE INGENIERÍA Y CIENCIAS AMBIENTALES',1,4,NULL,NULL,NULL,NULL,NULL,NULL),(13,'CICLO',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,'I',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(15,'II',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(16,'III',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(17,'IV',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(18,'V',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(19,'VI',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(20,'VII',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(21,'VIII',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(22,'IX',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(23,'X',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(24,'XI',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(25,'XII',1,13,NULL,NULL,NULL,NULL,NULL,NULL),(26,'ESPECIALIDAD',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,'INGENIERÍA DE SISTEMAS',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(28,'INGENIERÍA AMBIENTAL',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(29,'INGENIERÍA CIVIL',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(30,'INGENIERÍA INDUSTRIAL',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(31,'INGENIERÍA MECÁNICA',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(32,'INGENIERÍA ELECTRÓNICA',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(33,'INGENIERÍA DE SOFTWARE',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(34,'CIENCIAS AMBIENTALES',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(35,'BIOLOGÍA',1,26,NULL,NULL,NULL,NULL,NULL,NULL),(36,'GRUPO',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(37,'A',1,36,NULL,NULL,NULL,NULL,NULL,NULL),(38,'B',1,36,NULL,NULL,NULL,NULL,NULL,NULL),(39,'TIPOASISTENCIA',1,NULL,'2025-12-23 09:44:40',NULL,NULL,NULL,'2025-12-23 19:44:40','2025-12-23 19:44:40'),(40,'ASISTIO',1,39,'2025-12-23 09:44:40',NULL,NULL,NULL,'2025-12-23 19:44:40','2025-12-23 19:44:40'),(41,'AUSENTE',1,39,'2025-12-23 09:44:40',NULL,NULL,NULL,'2025-12-23 19:44:40','2025-12-23 19:44:40'),(42,'JUSTIFICADO',1,39,'2025-12-23 09:44:40',NULL,NULL,NULL,'2025-12-23 19:44:40','2025-12-23 19:44:40');
/*!40000 ALTER TABLE `catalogo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `curso`
--

DROP TABLE IF EXISTS `curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `curso` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `carrera_id` bigint(20) unsigned NOT NULL,
  `ciclo_id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `curso_codigo_unique` (`codigo`),
  KEY `curso_carrera_id_foreign` (`carrera_id`),
  KEY `curso_ciclo_id_foreign` (`ciclo_id`),
  CONSTRAINT `curso_carrera_id_foreign` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`),
  CONSTRAINT `curso_ciclo_id_foreign` FOREIGN KEY (`ciclo_id`) REFERENCES `catalogo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso`
--

LOCK TABLES `curso` WRITE;
/*!40000 ALTER TABLE `curso` DISABLE KEYS */;
INSERT INTO `curso` VALUES (1,1,14,'COMUNICACIÓN INICIAL','EDU101',1,NULL,NULL,NULL,NULL,NULL,NULL),(2,1,15,'DESARROLLO DEL LENGUAJE','EDU102',1,NULL,NULL,NULL,NULL,NULL,NULL),(3,2,14,'MATEMÁTICA PARA PRIMARIA','EDU201',1,NULL,NULL,NULL,NULL,NULL,NULL),(4,2,15,'CIENCIAS NATURALES PARA PRIMARIA','EDU202',1,NULL,NULL,NULL,NULL,NULL,NULL),(5,3,14,'BOTÁNICA GENERAL','ING301',1,NULL,NULL,NULL,NULL,NULL,NULL),(6,3,15,'SUELOS Y FERTILIDAD','ING302',1,NULL,NULL,NULL,NULL,NULL,NULL),(7,4,14,'QUÍMICA GENERAL','ING401',1,NULL,NULL,NULL,NULL,NULL,NULL),(8,4,15,'PROCESOS AGROINDUSTRIALES I','ING402',1,NULL,NULL,NULL,NULL,NULL,NULL),(9,4,16,'MICROBIOLOGÍA INDUSTRIAL','ING403',1,NULL,NULL,NULL,NULL,NULL,NULL),(10,3,16,'MANEJO DE CUENCAS','ING303',1,NULL,NULL,NULL,NULL,NULL,NULL),(17,1,22,'PRACTICAS PRE PROFESIONALES II','PP2',1,NULL,NULL,'2025-12-24 15:00:12',1,NULL,'2025-12-25 01:00:12'),(18,4,14,'INGENIERIA AGROINDUSTRIAL','IAA100',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(19,4,14,'MATEMATICA BASICA','IAA101',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(20,4,15,'ESTADISTICA','IAA200',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(21,4,15,'ESTADISTICA PARA INGENIERIA','IAA201',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(22,4,16,'BIOTECNOLOGIA AGROINDUSTRIAL','IAA301',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(23,4,16,'METODOS ESTADISTICOS','IAA302',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(24,4,16,'GESTION DE LA CALIDAD E INOCUIDAD','IAA303',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(25,4,14,'AUTOCAD','IAA102',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(26,4,22,'TESIS I','IAA901',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(27,4,23,'TESIS II','IAA902',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(28,2,14,'LENGUA ORIGINARIA','EPB101',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(29,2,15,'TIC II','EPB201',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:22','2025-12-25 00:57:22'),(30,2,22,'TESIS I','EPB901',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:23','2025-12-25 00:57:23'),(31,1,15,'SOFTWARE EDUCATIVO','EIB202',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:23','2025-12-25 00:57:23'),(32,1,14,'TIC I','EIB102',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:23','2025-12-25 00:57:23'),(33,1,15,'TIC II','EIB201',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:23','2025-12-25 00:57:23'),(34,1,14,'PERU EN LA SOCIEDAD DE CONOCIMIENTO','EIB101',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:23','2025-12-25 00:57:23'),(35,1,15,'TALLER CUC','EIB203',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:23','2025-12-25 00:57:23'),(36,1,22,'TESIS I','EIB901',1,NULL,NULL,NULL,NULL,'2025-12-25 00:57:23','2025-12-25 00:57:23');
/*!40000 ALTER TABLE `curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docente`
--

DROP TABLE IF EXISTS `docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `docente` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint(20) unsigned NOT NULL,
  `especialidad_id` bigint(20) unsigned NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `docente_persona_id_foreign` (`persona_id`),
  KEY `docente_especialidad_id_foreign` (`especialidad_id`),
  CONSTRAINT `docente_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `catalogo` (`id`),
  CONSTRAINT `docente_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docente`
--

LOCK TABLES `docente` WRITE;
/*!40000 ALTER TABLE `docente` DISABLE KEYS */;
INSERT INTO `docente` VALUES (1,1,35,1,NULL,NULL,'2025-12-28 12:49:19',1,NULL,'2025-12-28 17:49:19'),(2,2,35,1,NULL,NULL,'2025-12-28 12:49:19',1,NULL,'2025-12-28 17:49:19');
/*!40000 ALTER TABLE `docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docente_curso`
--

DROP TABLE IF EXISTS `docente_curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `docente_curso` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `curso_id` bigint(20) unsigned NOT NULL,
  `docente_id` bigint(20) unsigned NOT NULL,
  `semestre_id` bigint(20) unsigned NOT NULL,
  `grupo_id` bigint(20) unsigned NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `docente_curso_unique` (`curso_id`,`docente_id`,`semestre_id`,`grupo_id`),
  KEY `docente_curso_docente_id_foreign` (`docente_id`),
  KEY `docente_curso_semestre_id_foreign` (`semestre_id`),
  KEY `docente_curso_grupo_id_foreign` (`grupo_id`),
  CONSTRAINT `docente_curso_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`id`),
  CONSTRAINT `docente_curso_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docente` (`id`),
  CONSTRAINT `docente_curso_grupo_id_foreign` FOREIGN KEY (`grupo_id`) REFERENCES `catalogo` (`id`),
  CONSTRAINT `docente_curso_semestre_id_foreign` FOREIGN KEY (`semestre_id`) REFERENCES `semestre` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docente_curso`
--

LOCK TABLES `docente_curso` WRITE;
/*!40000 ALTER TABLE `docente_curso` DISABLE KEYS */;
INSERT INTO `docente_curso` VALUES (1,1,1,2,37,1,NULL,NULL,NULL,NULL,NULL,NULL),(2,2,1,2,37,1,NULL,NULL,NULL,NULL,NULL,NULL),(3,17,1,2,38,1,NULL,NULL,NULL,NULL,NULL,NULL),(4,4,1,2,37,1,NULL,NULL,NULL,NULL,NULL,NULL),(5,3,1,2,38,1,NULL,NULL,'2025-12-28 19:33:42',1,NULL,'2025-12-29 00:33:42'),(6,3,1,2,37,1,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `docente_curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiante`
--

DROP TABLE IF EXISTS `estudiante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiante` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint(20) unsigned NOT NULL,
  `carrera_id` bigint(20) unsigned NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `estudiante_codigo_unique` (`codigo`),
  KEY `estudiante_persona_id_foreign` (`persona_id`),
  KEY `estudiante_carrera_id_foreign` (`carrera_id`),
  CONSTRAINT `estudiante_carrera_id_foreign` FOREIGN KEY (`carrera_id`) REFERENCES `carrera` (`id`),
  CONSTRAINT `estudiante_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiante`
--

LOCK TABLES `estudiante` WRITE;
/*!40000 ALTER TABLE `estudiante` DISABLE KEYS */;
INSERT INTO `estudiante` VALUES (1,3,1,'0002211296',1,'2025-12-10 16:02:25',NULL,NULL,NULL,'2025-12-11 07:02:25','2025-12-11 07:02:25'),(2,4,3,'0002211598',1,'2025-12-10 17:17:49',NULL,'2025-12-10 17:58:14',NULL,'2025-12-11 08:17:49','2025-12-11 08:58:14');
/*!40000 ALTER TABLE `estudiante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiante_curso_docente`
--

DROP TABLE IF EXISTS `estudiante_curso_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiante_curso_docente` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estudiante_id` bigint(20) unsigned NOT NULL,
  `docente_curso_id` bigint(20) unsigned NOT NULL,
  `semestre_id` bigint(20) unsigned NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `estudiante_curso_docente_estudiante_id_foreign` (`estudiante_id`),
  KEY `estudiante_curso_docente_docente_curso_id_foreign` (`docente_curso_id`),
  KEY `estudiante_curso_docente_semestre_id_foreign` (`semestre_id`),
  CONSTRAINT `estudiante_curso_docente_docente_curso_id_foreign` FOREIGN KEY (`docente_curso_id`) REFERENCES `docente_curso` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estudiante_curso_docente_estudiante_id_foreign` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id`) ON DELETE CASCADE,
  CONSTRAINT `estudiante_curso_docente_semestre_id_foreign` FOREIGN KEY (`semestre_id`) REFERENCES `semestre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiante_curso_docente`
--

LOCK TABLES `estudiante_curso_docente` WRITE;
/*!40000 ALTER TABLE `estudiante_curso_docente` DISABLE KEYS */;
/*!40000 ALTER TABLE `estudiante_curso_docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horario`
--

DROP TABLE IF EXISTS `horario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `laboratorio_id` bigint(20) unsigned NOT NULL,
  `semestre_id` bigint(20) unsigned NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `horario_laboratorio_id_foreign` (`laboratorio_id`),
  KEY `horario_semestre_id_foreign` (`semestre_id`),
  CONSTRAINT `horario_laboratorio_id_foreign` FOREIGN KEY (`laboratorio_id`) REFERENCES `laboratorio` (`id`),
  CONSTRAINT `horario_semestre_id_foreign` FOREIGN KEY (`semestre_id`) REFERENCES `semestre` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horario`
--

LOCK TABLES `horario` WRITE;
/*!40000 ALTER TABLE `horario` DISABLE KEYS */;
INSERT INTO `horario` VALUES (1,'HORARIO DEL LAB-01 DEL SEMESTRE 2025-II',1,2,1,'2025-12-10 18:58:16',NULL,NULL,NULL,'2025-12-11 09:58:16','2025-12-11 09:58:16'),(2,'HORARIO DEL LAB-02 DEL SEMESTRE 2025-II',2,2,1,'2025-12-10 18:58:21',NULL,NULL,NULL,'2025-12-11 09:58:21','2025-12-11 09:58:21');
/*!40000 ALTER TABLE `horario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horario_curso_docente`
--

DROP TABLE IF EXISTS `horario_curso_docente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horario_curso_docente` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `horario_id` bigint(20) unsigned NOT NULL,
  `docente_curso_id` bigint(20) unsigned NOT NULL,
  `semana_id` bigint(20) unsigned NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `horario_curso_docente_horario_id_foreign` (`horario_id`),
  KEY `horario_curso_docente_docente_curso_id_foreign` (`docente_curso_id`),
  KEY `horario_curso_docente_semana_id_foreign` (`semana_id`),
  CONSTRAINT `horario_curso_docente_docente_curso_id_foreign` FOREIGN KEY (`docente_curso_id`) REFERENCES `docente_curso` (`id`),
  CONSTRAINT `horario_curso_docente_horario_id_foreign` FOREIGN KEY (`horario_id`) REFERENCES `horario` (`id`),
  CONSTRAINT `horario_curso_docente_semana_id_foreign` FOREIGN KEY (`semana_id`) REFERENCES `catalogo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horario_curso_docente`
--

LOCK TABLES `horario_curso_docente` WRITE;
/*!40000 ALTER TABLE `horario_curso_docente` DISABLE KEYS */;
INSERT INTO `horario_curso_docente` VALUES (2,1,1,5,'04:04:00','05:05:00',1,'2025-12-10 21:04:04',NULL,NULL,NULL,'2025-12-11 12:04:04','2025-12-11 12:04:04'),(3,1,2,5,'20:45:00','17:42:00',1,'2025-12-10 22:43:07',NULL,NULL,NULL,'2025-12-11 13:43:07','2025-12-11 13:43:07'),(4,1,3,5,'02:11:00','04:02:00',1,'2025-12-10 23:22:27',NULL,'2025-12-11 04:29:58',NULL,'2025-12-11 14:22:27','2025-12-11 19:29:58'),(5,2,3,5,'02:11:00','04:04:00',1,'2025-12-10 23:29:42',NULL,NULL,NULL,'2025-12-11 14:29:42','2025-12-11 14:29:42'),(6,1,1,5,'05:06:00','06:50:00',1,'2025-12-10 23:54:25',NULL,NULL,NULL,'2025-12-11 14:54:25','2025-12-11 14:54:25'),(7,1,6,5,'08:08:00','09:09:00',1,'2025-12-11 00:17:35',NULL,NULL,NULL,'2025-12-11 15:17:35','2025-12-11 15:17:35');
/*!40000 ALTER TABLE `horario_curso_docente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laboratorio`
--

DROP TABLE IF EXISTS `laboratorio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `laboratorio` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `area_id` bigint(20) unsigned NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laboratorio_nombre_unique` (`nombre`),
  KEY `laboratorio_area_id_foreign` (`area_id`),
  CONSTRAINT `laboratorio_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `catalogo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laboratorio`
--

LOCK TABLES `laboratorio` WRITE;
/*!40000 ALTER TABLE `laboratorio` DISABLE KEYS */;
INSERT INTO `laboratorio` VALUES (1,2,'LAB-01',1,NULL,NULL,NULL,NULL,NULL,NULL),(2,2,'LAB-02',1,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `laboratorio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_11_27_163818_create_catalogo_table',1),(5,'2025_11_27_172400_create_rol_table',1),(6,'2025_11_27_172410_create_menu_table',1),(7,'2025_11_27_172417_create_permiso_table',1),(8,'2025_11_27_172429_create_acceso_table',1),(9,'2025_11_27_182201_create_persona_table',1),(10,'2025_11_27_190343_create_usuario_table',1),(11,'2025_11_27_190545_create_docente_table',1),(12,'2025_11_27_190657_create_carrera_table',1),(13,'2025_11_27_190658_create_estudiante_table',1),(14,'2025_11_27_192736_create_curso_table',1),(15,'2025_11_27_194242_create_semestre_table',1),(16,'2025_11_27_200534_create_docente_curso_table',1),(17,'2025_11_27_201620_create_laboratorio_table',1),(18,'2025_11_27_201629_create_horario_table',1),(19,'2025_11_27_203311_create_horario_curso_docente_table',1),(20,'2025_11_27_204422_create_asistencia_table',1),(21,'2025_11_27_204738_create_asistencia_estudiante_table',1),(22,'2025_12_10_034314_create_estudiante_curso_docente_table',1),(23,'2025_12_18_205737_create_password_reset_tokens_table',1),(24,'2025_12_25_143555_create_usuario_rol_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permiso` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  `apellido_paterno` varchar(40) NOT NULL,
  `apellido_materno` varchar(40) DEFAULT NULL,
  `dni` varchar(20) NOT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `correo` varchar(120) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `persona_dni_unique` (`dni`),
  UNIQUE KEY `persona_correo_unique` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'JUAN','JIMENEZ','DEZA','79985454','987456321','juang@gmail.com','1998-06-08',1,NULL,NULL,NULL,NULL,NULL,NULL),(2,'GABRIEL','ROJAS','TAPIA','57412369','985478963','gab@gmail.com','1999-02-17',1,NULL,NULL,NULL,NULL,NULL,NULL),(3,'ALBERT','NAVARRO','MALLMA','74726606','936159542','dan@gmail.com','2004-09-15',1,'2025-12-10 16:02:25',NULL,NULL,NULL,'2025-12-11 07:02:25','2025-12-11 07:02:25'),(4,'BRAYAN','ROJAS','DIAZ','74787541','985745142','bry@gmail.com','2000-02-20',1,'2025-12-10 17:17:49',NULL,NULL,NULL,'2025-12-11 08:17:49','2025-12-11 08:17:49');
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'ADMINISTRADOR',1,NULL,NULL,NULL,NULL,NULL,NULL),(2,'DOCENTE',1,NULL,NULL,NULL,NULL,NULL,NULL),(3,'ESTUDIANTE',1,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `semestre`
--

DROP TABLE IF EXISTS `semestre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `semestre` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `semestre`
--

LOCK TABLES `semestre` WRITE;
/*!40000 ALTER TABLE `semestre` DISABLE KEYS */;
INSERT INTO `semestre` VALUES (1,'2025-I','2025-01-01 00:00:00','2025-06-30 00:00:00',1,NULL,NULL,NULL,NULL,NULL,NULL),(2,'2025-II','2025-07-01 00:00:00','2025-12-31 00:00:00',1,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `semestre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('UUpwAJXhE2NS75aBAuA8V2igFgwdgICgxJ2LnlR7',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaml0a3JzbHRZVGFvNFZCM0JFS3JMUWpsWVhrY0llTmhwTXJ4Tm1INSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL0RvY2VudGVzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9Eb2NlbnRlcyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1766968424);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `persona_id` bigint(20) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(250) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_username_unique` (`username`),
  KEY `usuario_persona_id_foreign` (`persona_id`),
  CONSTRAINT `usuario_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,3,'dannn','$2y$12$nenDpczZCnMvmV3pEOOoWeSDSBu/w0JrsRPU1GT24qSWTwIhOJYpW','dannavarro350@gmail.com','3tzxnVjo76z5kKJL33KpcPAZAPK1f7BGb3ZGv5PYXP6lJp4sgw5Yu8xMGmDb',1,'2025-12-25 15:17:20',NULL,'2025-12-25 15:30:15',1,'2025-12-25 20:17:20','2025-12-25 20:17:20');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_rol`
--

DROP TABLE IF EXISTS `usuario_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_rol` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `rol_id` bigint(20) unsigned NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_cr` datetime DEFAULT NULL,
  `usuario_cr` bigint(20) unsigned DEFAULT NULL,
  `fecha_md` datetime DEFAULT NULL,
  `usuario_md` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_rol_usuario_id_foreign` (`usuario_id`),
  KEY `usuario_rol_rol_id_foreign` (`rol_id`),
  CONSTRAINT `usuario_rol_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usuario_rol_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_rol`
--

LOCK TABLES `usuario_rol` WRITE;
/*!40000 ALTER TABLE `usuario_rol` DISABLE KEYS */;
INSERT INTO `usuario_rol` VALUES (1,1,1,1,'2025-12-25 15:17:38',NULL,NULL,NULL,'2025-12-25 20:17:38','2025-12-25 20:17:38'),(2,1,2,1,NULL,NULL,NULL,NULL,NULL,NULL),(3,1,3,1,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `usuario_rol` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-29  8:27:43
