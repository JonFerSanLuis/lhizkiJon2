<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['email'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autenticado']);
    exit();
}

require_once "../model/AccesoBD.php";
require_once "../model/usuario.php";

// Obtener datos del usuario
$usuario = new Usuario();
$accesoBD = new AccesoBD();

if (!$accesoBD->rellenarUsuario($usuario, $_SESSION['email'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Usuario no encontrado']);
    exit();
}

$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

switch ($accion) {
    case 'obtener_preguntas':
        obtenerPreguntas($accesoBD, $usuario);
        break;
    
    case 'guardar_resultado':
        guardarResultado($accesoBD, $usuario);
        break;
    
    default:
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Acción no válida']);
        break;
}

$accesoBD->cerrarConexion();

/**
 * Obtener preguntas aleatorias para el juego
 */
function obtenerPreguntas($accesoBD, $usuario) {
    header('Content-Type: application/json');
    
    // Obtener el ciclo del usuario para filtrar preguntas
    $id_ciclo = $usuario->getIdCiclo();
    
    // Por ahora, obtenemos preguntas de familia 1 (Informática)
    // En el futuro, esto puede vincularse con el ciclo
    $familia = 1;
    
    $sql = "SELECT id_pregunta, termino_castellano, opcion_euskera_1 as respuesta_correcta 
            FROM pregunta 
            WHERE familia = $familia AND activa = 1 
            ORDER BY RAND() 
            LIMIT 10";
    
    $result = $accesoBD->lanzarSQL($sql);
    $preguntas = array();
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $preguntas[] = $row;
        }
    }
    
    echo json_encode([
        'success' => true,
        'preguntas' => $preguntas
    ]);
}

/**
 * Guardar resultado de la partida
 */
function guardarResultado($accesoBD, $usuario) {
    header('Content-Type: application/json');
    
    // Obtener datos del POST
    $aciertos = (int)($_POST['aciertos'] ?? 0);
    $fallos = (int)($_POST['fallos'] ?? 0);
    $tiempo_empleado = (int)($_POST['tiempo_empleado'] ?? 0);
    $completado = ($aciertos + $fallos >= 10) ? 1 : 0;
    
    $id_usuario = $usuario->getIdUsuario();
    $id_juego = 1; // ID de juego genérico para este tipo de partida
    $fecha_actual = date('Y-m-d H:i:s');
    
    // Calcular puntos (100 puntos por acierto)
    $puntos_ganados = $aciertos * 100;
    
    // Insertar resultado
    $sql = "INSERT INTO resultado (id_usuario, id_juego, aciertos, fallos, tiempo_empleado, fecha_realizacion, completado) 
            VALUES ($id_usuario, $id_juego, $aciertos, $fallos, $tiempo_empleado, '$fecha_actual', $completado)";
    
    $resultado = $accesoBD->lanzarSQL($sql);
    
    if ($resultado) {
        // Actualizar puntos totales del usuario
        $sql_update = "UPDATE usuario SET puntos_totales = puntos_totales + $puntos_ganados WHERE id_usuario = $id_usuario";
        $accesoBD->lanzarSQL($sql_update);
        
        echo json_encode([
            'success' => true,
            'puntos_ganados' => $puntos_ganados,
            'mensaje' => 'Emaitza gorde da!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Errorea emaitza gordetzean'
        ]);
    }
}
?>