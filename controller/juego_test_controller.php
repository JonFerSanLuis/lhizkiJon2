<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No autenticado']);
    exit();
}

require_once "../model/AccesoBD.php";
require_once "../model/usuario.php";

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

function obtenerPreguntas($accesoBD, $usuario) {
    header('Content-Type: application/json');

    $familia = 1;

    $sql = "SELECT id_pregunta, termino_castellano, opcion_euskera_1, opcion_euskera_2, opcion_euskera_3, respuesta_correcta
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

function guardarResultado($accesoBD, $usuario) {
    header('Content-Type: application/json');

    $aciertos = (int)($_POST['aciertos'] ?? 0);
    $fallos = (int)($_POST['fallos'] ?? 0);
    $tiempo_empleado = (int)($_POST['tiempo_empleado'] ?? 0);
    $completado = ($aciertos + $fallos >= 10) ? 1 : 0;

    $id_usuario = $usuario->getIdUsuario();
    $id_juego = 2; // ID del juego de Test
    $fecha_actual = date('Y-m-d H:i:s');
    
    // comprueba si el usuario ya ha jugado a este juego en los últimos 7 días
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
    // calcular puntos solo si es la primera vez esta semana
    $puntos_ganados = 0;
    if (!$haJugadoEstaSemana) {
        $puntos_ganados = $aciertos * 100;
    }
    
    $sql = "INSERT INTO resultado (id_usuario, id_juego, aciertos, fallos, tiempo_empleado, fecha_realizacion, completado)
            VALUES ($id_usuario, $id_juego, $aciertos, $fallos, $tiempo_empleado, '$fecha_actual', $completado)";

    $resultado = $accesoBD->lanzarSQL($sql);

    if ($resultado) {
        // actualiza puntos totales del usuario solo si es la primera vez que juega y ha acertado minimo 1 vez
        if (!$haJugadoEstaSemana && $puntos_ganados > 0) {
            $sql_update = "UPDATE usuario SET puntos_totales = puntos_totales + $puntos_ganados WHERE id_usuario = $id_usuario";
            $accesoBD->lanzarSQL($sql_update);
        }

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