-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.2.0 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para lhizki
DROP DATABASE IF EXISTS `lhizki`;
CREATE DATABASE IF NOT EXISTS `lhizki` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `lhizki`;

-- Volcando estructura para tabla lhizki.centro_educativo
CREATE TABLE IF NOT EXISTS `centro_educativo` (
  `id_centro` int NOT NULL AUTO_INCREMENT,
  `nombre_centro` varchar(100) NOT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  PRIMARY KEY (`id_centro`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.centro_educativo: ~0 rows (aproximadamente)
INSERT INTO `centro_educativo` (`id_centro`, `nombre_centro`, `municipio`, `provincia`, `fecha_alta`) VALUES
	(1, 'IES LHizki', 'Bilbao', 'Bizkaia', '2025-11-05 09:38:13');

-- Volcando estructura para tabla lhizki.ciclo_formativo
CREATE TABLE IF NOT EXISTS `ciclo_formativo` (
  `id_ciclo` int NOT NULL AUTO_INCREMENT,
  `nombre_ciclo` varchar(100) NOT NULL,
  `familia_profesional` varchar(100) DEFAULT NULL,
  `id_centro` int DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  PRIMARY KEY (`id_ciclo`),
  KEY `id_centro` (`id_centro`),
  CONSTRAINT `ciclo_formativo_ibfk_1` FOREIGN KEY (`id_centro`) REFERENCES `centro_educativo` (`id_centro`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.ciclo_formativo: ~0 rows (aproximadamente)
INSERT INTO `ciclo_formativo` (`id_ciclo`, `nombre_ciclo`, `familia_profesional`, `id_centro`, `fecha_alta`) VALUES
	(1, 'Desarrollo de Aplicaciones Web', 'Informática', 1, '2025-11-05 09:38:14');

-- Volcando estructura para tabla lhizki.estado_juego
CREATE TABLE IF NOT EXISTS `estado_juego` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(50) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.estado_juego: ~0 rows (aproximadamente)
INSERT INTO `estado_juego` (`id_estado`, `nombre_estado`) VALUES
	(1, 'Activo');

-- Volcando estructura para tabla lhizki.glosario
CREATE TABLE IF NOT EXISTS `glosario` (
  `id_termino` int NOT NULL AUTO_INCREMENT,
  `id_juego` int DEFAULT NULL,
  `termino_euskera` varchar(255) NOT NULL,
  `termino_castellano` varchar(255) NOT NULL,
  `definicion` text,
  `fuente` varchar(255) DEFAULT NULL,
  `fecha_alta` datetime DEFAULT NULL,
  PRIMARY KEY (`id_termino`),
  KEY `id_juego` (`id_juego`),
  CONSTRAINT `glosario_ibfk_1` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.glosario: ~0 rows (aproximadamente)

-- Volcando estructura para tabla lhizki.juego
CREATE TABLE IF NOT EXISTS `juego` (
  `id_juego` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text,
  `semana_numero` int DEFAULT NULL,
  `anio` int DEFAULT NULL,
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `tiempo_limite` int DEFAULT NULL,
  `num_preguntas` int DEFAULT NULL,
  PRIMARY KEY (`id_juego`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `juego_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado_juego` (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.juego: ~1 rows (aproximadamente)
INSERT INTO `juego` (`id_juego`, `titulo`, `descripcion`, `semana_numero`, `anio`, `fecha_inicio`, `fecha_fin`, `id_estado`, `tiempo_limite`, `num_preguntas`) VALUES
	(1, 'Adivina Hitza', 'Joko honetan euskerazko hitzak asmatu behar dituzu', NULL, NULL, '2025-11-11 12:36:10', NULL, 1, NULL, 10);

-- Volcando estructura para tabla lhizki.juego_ciclo
CREATE TABLE IF NOT EXISTS `juego_ciclo` (
  `id_juego` int NOT NULL,
  `id_ciclo` int NOT NULL,
  PRIMARY KEY (`id_juego`,`id_ciclo`),
  KEY `id_ciclo` (`id_ciclo`),
  CONSTRAINT `juego_ciclo_ibfk_1` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`),
  CONSTRAINT `juego_ciclo_ibfk_2` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclo_formativo` (`id_ciclo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.juego_ciclo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla lhizki.pregunta
CREATE TABLE IF NOT EXISTS `pregunta` (
  `id_pregunta` int NOT NULL AUTO_INCREMENT,
  `familia` int NOT NULL,
  `termino_castellano` varchar(200) NOT NULL,
  `opcion_euskera_1` varchar(200) NOT NULL,
  `opcion_euskera_2` varchar(200) NOT NULL,
  `opcion_euskera_3` varchar(200) NOT NULL,
  `respuesta_correcta` int NOT NULL,
  `fecha_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  `activa` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_pregunta`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.pregunta: ~15 rows (aproximadamente)
INSERT INTO `pregunta` (`id_pregunta`, `familia`, `termino_castellano`, `opcion_euskera_1`, `opcion_euskera_2`, `opcion_euskera_3`, `respuesta_correcta`, `fecha_alta`, `activa`) VALUES
	(1, 1, 'ordenador', 'ordenagailua', 'konputagailua', 'makinagailua', 2, '2025-11-11 12:36:10', 1),
	(2, 1, 'teclado', 'teklatua', 'idazmakina', 'sakagailua', 1, '2025-11-11 12:36:10', 1),
	(3, 1, 'ratón', 'sagua', 'xagua', 'klikagailua', 1, '2025-11-11 12:36:10', 1),
	(4, 1, 'pantalla', 'pantaila', 'ikusgailua', 'monitore', 1, '2025-11-11 12:36:10', 1),
	(5, 1, 'archivo', 'artxiboa', 'fitxategia', 'dokumentua', 2, '2025-11-11 12:36:10', 1),
	(6, 1, 'carpeta', 'karpeta', 'kaxeta', 'bilduma', 1, '2025-11-11 12:36:10', 1),
	(7, 1, 'impresora', 'inprimagailua', 'printerra', 'kopiagailua', 1, '2025-11-11 12:36:10', 1),
	(8, 1, 'disco duro', 'disko gogorra', 'gordetzailea', 'memoriagailua', 1, '2025-11-11 12:36:10', 1),
	(9, 1, 'memoria', 'memoria', 'gogoragailua', 'oroimena', 1, '2025-11-11 12:36:10', 1),
	(10, 1, 'programa', 'programa', 'aplikazioa', 'softwarea', 1, '2025-11-11 12:36:10', 1),
	(11, 1, 'internet', 'internet', 'sarea', 'konexioa', 1, '2025-11-11 12:36:10', 1),
	(12, 1, 'correo', 'posta', 'emaila', 'mezua', 1, '2025-11-11 12:36:10', 1),
	(13, 1, 'contraseña', 'pasahitza', 'kodea', 'gakoa', 1, '2025-11-11 12:36:10', 1),
	(14, 1, 'servidor', 'zerbitzaria', 'servidorea', 'ostaria', 1, '2025-11-11 12:36:10', 1),
	(15, 1, 'aplicación', 'aplikazioa', 'programa', 'softwarea', 1, '2025-11-11 12:36:10', 1);

-- Volcando estructura para tabla lhizki.profesor
CREATE TABLE IF NOT EXISTS `profesor` (
  `id_profesor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_centro` int NOT NULL,
  `id_ciclo` int NOT NULL,
  `fecha_alta` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_profesor`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `profesor_unico_por_ciclo` (`id_ciclo`),
  KEY `profesor_ibfk_1` (`id_centro`),
  CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`id_centro`) REFERENCES `centro_educativo` (`id_centro`),
  CONSTRAINT `profesor_ibfk_2` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclo_formativo` (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.profesor: ~0 rows (aproximadamente)
INSERT INTO `profesor` (`id_profesor`, `nombre`, `apellidos`, `email`, `password`, `id_centro`, `id_ciclo`, `fecha_alta`) VALUES
	(1, 'Mikel', 'Zubizarreta', 'mikel.zubi@example.com', '12345', 1, 1, '2025-11-05 09:38:14');

-- Volcando estructura para tabla lhizki.resultado
CREATE TABLE IF NOT EXISTS `resultado` (
  `id_resultado` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int DEFAULT NULL,
  `id_juego` int DEFAULT NULL,
  `aciertos` int DEFAULT '0',
  `fallos` int DEFAULT '0',
  `tiempo_empleado` int DEFAULT NULL,
  `fecha_realizacion` datetime DEFAULT NULL,
  `completado` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_resultado`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_juego` (`id_juego`),
  CONSTRAINT `resultado_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `resultado_ibfk_2` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.resultado: ~0 rows (aproximadamente)
INSERT INTO `resultado` (`id_resultado`, `id_usuario`, `id_juego`, `aciertos`, `fallos`, `tiempo_empleado`, `fecha_realizacion`, `completado`) VALUES
	(1, 3, 1, 9, 1, 124, '2025-11-12 08:18:18', 1),
	(2, 3, 1, 2, 3, 37, '2025-11-12 09:48:33', 0),
	(3, 3, 1, 0, 3, 10, '2025-11-12 11:02:30', 0);

-- Volcando estructura para tabla lhizki.rol
CREATE TABLE IF NOT EXISTS `rol` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.rol: ~3 rows (aproximadamente)
INSERT INTO `rol` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
	(1, 'Alumno', 'Usuario con rol de estudiante'),
	(2, 'Profesor', 'Usuario con rol de profesor'),
	(3, 'Admin', 'Administrador');

-- Volcando estructura para tabla lhizki.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int DEFAULT NULL,
  `id_centro` int DEFAULT NULL,
  `id_ciclo` int DEFAULT NULL,
  `puntos_totales` int DEFAULT '0',
  `fecha_registro` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `ultimo_acceso` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`),
  KEY `id_rol` (`id_rol`),
  KEY `id_centro` (`id_centro`),
  KEY `id_ciclo` (`id_ciclo`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`),
  CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_centro`) REFERENCES `centro_educativo` (`id_centro`),
  CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclo_formativo` (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla lhizki.usuario: ~2 rows (aproximadamente)
INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellidos`, `email`, `password`, `id_rol`, `id_centro`, `id_ciclo`, `puntos_totales`, `fecha_registro`, `activo`, `ultimo_acceso`) VALUES
	(2, 'e', 'e', 'e@gmail.com', '$2y$10$PdCJBW8NID/Rmf.lRddvk.J0seDsa4iFCdl6Q9OfSlxglj0GTz8.u', 1, 1, 1, 0, '2025-11-05 10:33:40', 1, '2025-11-05 10:38:09'),
	(3, 'Erlantz', 'Erlantz', 'e@e', '$2y$10$cCPVHEl.JbkjHb6/aytT.OW.1Vuy1f1XzWXXltR/Vgz2CiooxP6wy', 1, 1, 1, 1100, '2025-11-11 12:37:11', 1, '2025-11-12 11:01:23');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
