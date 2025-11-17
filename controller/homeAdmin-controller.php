<?php
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en producción
ini_set('log_errors', 1);
error_log("=== Inicio de homeAdmin-controller ===");

session_start();
header('Content-Type: application/json');

// Ajustar ruta según la ubicación del archivo
$ruta_base = dirname(__DIR__); // Obtiene el directorio padre
error_log("Ruta base: " . $ruta_base);
require_once $ruta_base . '/model/AccesoBD.php';

try {
    error_log("Intentando crear instancia de AccesoBD");
    $bd = new AccesoBD();
      // Verificar conexión
    if (!$bd->conexion) {
        throw new Exception('No se pudo establecer la conexión con la base de datos');
    }
    
    error_log("Conexión establecida correctamente");
    
    // 1. Contar total de usuarios
    error_log("Ejecutando consulta de usuarios");
    $sqlTotalUsuarios = "SELECT COUNT(*) as total FROM usuario WHERE activo = 1";
    $resultUsuarios = $bd->lanzarSQL($sqlTotalUsuarios);
    
    if (!$resultUsuarios) {
        throw new Exception('Error al consultar usuarios');
    }
    
    $totalUsuarios = mysqli_fetch_assoc($resultUsuarios)['total'];
    
    // 2. Contar total de juegos
    $sqlTotalJuegos = "SELECT COUNT(*) as total FROM juego";
    $resultJuegos = $bd->lanzarSQL($sqlTotalJuegos);
    
    if (!$resultJuegos) {
        throw new Exception('Error al consultar juegos');
    }
    
    $totalJuegos = mysqli_fetch_assoc($resultJuegos)['total'];
    
    // 3. Calcular porcentaje de participación (partidas completadas)
    $sqlParticipacion = "SELECT 
        COUNT(*) as total_partidas,
        SUM(CASE WHEN completado = 1 THEN 1 ELSE 0 END) as partidas_completadas
        FROM resultado";
    $resultParticipacion = $bd->lanzarSQL($sqlParticipacion);
    
    if (!$resultParticipacion) {
        throw new Exception('Error al consultar participación');
    }
    
    $dataParticipacion = mysqli_fetch_assoc($resultParticipacion);
    $porcentajeParticipacion = $dataParticipacion['total_partidas'] > 0 
        ? round(($dataParticipacion['partidas_completadas'] / $dataParticipacion['total_partidas']) * 100) 
        : 0;
    
    // 4. Contar usuarios con rol Ikasle (estudiantes) - id_rol = 1
    $sqlIkasle = "SELECT COUNT(*) as total FROM usuario WHERE id_rol = 1 AND activo = 1";
    $resultIkasle = $bd->lanzarSQL($sqlIkasle);
    
    if (!$resultIkasle) {
        throw new Exception('Error al consultar ikasle');
    }
    
    $totalIkasle = mysqli_fetch_assoc($resultIkasle)['total'];
    
    // 5. Usuarios por rol
    $sqlRoles = "SELECT 
        r.nombre_rol,
        COUNT(u.id_usuario) as total
        FROM rol r
        LEFT JOIN usuario u ON r.id_rol = u.id_rol AND u.activo = 1
        GROUP BY r.id_rol, r.nombre_rol
        ORDER BY total DESC";
    $resultRoles = $bd->lanzarSQL($sqlRoles);
    
    if (!$resultRoles) {
        throw new Exception('Error al consultar roles');
    }
    
    $usuariosPorRol = [];
    while ($row = mysqli_fetch_assoc($resultRoles)) {
        $usuariosPorRol[] = $row;
    }
    
    // 6. Participación por centro educativo
    $sqlCentros = "SELECT 
        ce.nombre_centro as centro,
        COUNT(DISTINCT u.id_usuario) as total_usuarios,
        COUNT(r.id_resultado) as total_partidas,
        SUM(CASE WHEN r.completado = 1 THEN 1 ELSE 0 END) as partidas_completadas,
        CASE 
            WHEN COUNT(r.id_resultado) > 0 
            THEN ROUND((SUM(CASE WHEN r.completado = 1 THEN 1 ELSE 0 END) / COUNT(r.id_resultado)) * 100)
            ELSE 0 
        END as porcentaje_participacion
        FROM centro_educativo ce
        LEFT JOIN usuario u ON ce.id_centro = u.id_centro AND u.activo = 1
        LEFT JOIN resultado r ON u.id_usuario = r.id_usuario
        GROUP BY ce.id_centro, ce.nombre_centro
        HAVING total_partidas > 0
        ORDER BY porcentaje_participacion DESC
        LIMIT 5";
    $resultCentros = $bd->lanzarSQL($sqlCentros);
    
    if (!$resultCentros) {
        throw new Exception('Error al consultar centros');
    }
    
    $participacionCentros = [];
    while ($row = mysqli_fetch_assoc($resultCentros)) {
        $participacionCentros[] = $row;
    }
      $bd->cerrarConexion();
    
    error_log("Preparando respuesta con datos: " . json_encode([
        'totalUsuarios' => $totalUsuarios,
        'totalJuegos' => $totalJuegos,
        'porcentajeParticipacion' => $porcentajeParticipacion,
        'totalIkasle' => $totalIkasle
    ]));
    
    // Preparar respuesta
    $response = [
        'success' => true,
        'data' => [
            'totalUsuarios' => (int)$totalUsuarios,
            'totalJuegos' => (int)$totalJuegos,
            'porcentajeParticipacion' => (int)$porcentajeParticipacion,
            'totalIkasle' => (int)$totalIkasle,
            'usuariosPorRol' => $usuariosPorRol,
            'participacionCentros' => $participacionCentros
        ]
    ];
    
    error_log("Enviando respuesta exitosa");
    echo json_encode($response);
    
} catch (Exception $e) {
    // Log del error
    error_log("Error en homeAdmin-controller: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener estadísticas: ' . $e->getMessage(),
        'details' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?>