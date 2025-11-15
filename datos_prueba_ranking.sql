-- Insertar más ciclos formativos
INSERT INTO ciclo_formativo (nombre_ciclo, familia_profesional, id_centro, fecha_alta)
VALUES
('Desarrollo de Aplicaciones Multiplataforma', 'Informática', 1, NOW()),
('Electricidad y Electrónica', 'Electricidad', 1, NOW());

-- Insertar más usuarios con diferentes puntos para el ranking
INSERT INTO usuario (nombre, apellidos, email, password, id_rol, id_centro, id_ciclo, puntos_totales, fecha_registro, activo, ultimo_acceso)
VALUES
('Jon', 'Azpeitia', 'jon.azpeitia@example.com', '$2y$10$abcdefghijklmnopqrstuv', 1, 1, 2, 1580, NOW(), true, NOW()),
('Leire', 'Mendiazabal', 'leire.mendi@example.com', '$2y$10$abcdefghijklmnopqrstuv', 1, 1, 2, 1420, NOW(), true, NOW()),
('Ander', 'Urrutia', 'ander.urrutia@example.com', '$2y$10$abcdefghijklmnopqrstuv', 1, 1, 1, 1380, NOW(), true, NOW()),
('Miren', 'Garitano', 'miren.garitano@example.com', '$2y$10$abcdefghijklmnopqrstuv', 1, 1, 1, 1245, NOW(), true, NOW()),
('Aitor', 'Larrauri', 'aitor.larrauri@example.com', '$2y$10$abcdefghijklmnopqrstuv', 1, 1, 3, 1050, NOW(), true, NOW());

-- Actualizar los puntos de Ane para que aparezca en el ranking
UPDATE usuario SET puntos_totales = 1180 WHERE email = 'ane.etxeberria@example.com';
