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
    
    // --- INICIO DE LA NUEVA LÓGICA ---
    
    // 1. Comprobar si el usuario ya ha jugado a este juego en los últimos 7 días
    $sql_check = "SELECT COUNT(*) as total 
                  FROM resultado 
                  WHERE id_usuario = $id_usuario 
                  AND id_juego = $id_juego 
                  AND fecha_realizacion >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                  
    $res_check = $accesoBD->lanzarSQL($sql_check);
    $haJugadoEstaSemana = false;
    
    if ($res_check && mysqli_num_rows($res_check) > 0) {
        $fila_check = mysqli_fetch_assoc($res_check);
        if ($fila_check['total'] > 0) {
            $haJugadoEstaSemana = true;
        }
    }

    // 2. Calcular puntos solo si es la primera vez esta semana
    $puntos_ganados = 0;
    if (!$haJugadoEstaSemana) {
        $puntos_ganados = $aciertos * 100;
    }
    
    // --- FIN DE LA NUEVA LÓGICA ---
    
    // Insertar resultado (esto se hace siempre, para el historial)
    $sql = "INSERT INTO resultado (id_usuario, id_juego, aciertos, fallos, tiempo_empleado, fecha_realizacion, completado) 
            VALUES ($id_usuario, $id_juego, $aciertos, $fallos, $tiempo_empleado, '$fecha_actual', $completado)";
    
    $resultado = $accesoBD->lanzarSQL($sql);
    
    if ($resultado) {
        // 3. Actualizar puntos totales del usuario SOLO si es la primera vez y ganó puntos
        if (!$haJugadoEstaSemana && $puntos_ganados > 0) {
            $sql_update = "UPDATE usuario SET puntos_totales = puntos_totales + $puntos_ganados WHERE id_usuario = $id_usuario";
            $accesoBD->lanzarSQL($sql_update);
        }
        
        echo json_encode([
            'success' => true,
            'puntos_ganados' => $puntos_ganados, // El JS recibirá 0 si ya ha jugado
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