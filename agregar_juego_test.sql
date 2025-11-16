-- Insertar nuevo juego tipo TEST
INSERT INTO `juego` (`id_juego`, `titulo`, `descripcion`, `semana_numero`, `anio`, `fecha_inicio`, `fecha_fin`, `id_estado`, `tiempo_limite`, `num_preguntas`) VALUES
(2, 'Test Hitza', 'Joko honetan hiru aukeretatik egokiena aukeratu behar duzu', NULL, NULL, NOW(), NULL, 2, NULL, 10);

-- Insertar estado inactivo si no existe
INSERT IGNORE INTO `estado_juego` (`id_estado`, `nombre_estado`) VALUES (2, 'Inactivo');
