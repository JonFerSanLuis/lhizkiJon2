<?php
// Debug: Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../model/AccesoBD.php';

$partidas = [];
$total = 0;
$usuarios = [];
$juegos = [];
$page_num = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$limit = 10;

// Obtener parámetros de búsqueda
$filtros = [];
if (!empty($_GET['buscar_usuario'])) {
    $filtros['buscar_usuario'] = trim($_GET['buscar_usuario']);
}
if (!empty($_GET['id_juego']) && $_GET['id_juego'] !== 'default') {
    $filtros['id_juego'] = (int)$_GET['id_juego'];
}
if (!empty($_GET['completado']) && $_GET['completado'] !== 'default') {
    $filtros['completado'] = (int)$_GET['completado'];
}

$bd = new AccesoBD();

// Obtener datos para los filtros
$usuarios = $bd->obtenerUsuarios();
$juegos = $bd->obtenerJuegos();

// Manejo de eliminación de partida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id_resultado = isset($_POST['id_resultado']) ? (int)$_POST['id_resultado'] : 0;
    
    $isAjax = (isset($_POST['ajax']) && $_POST['ajax'] == '1') || 
              (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    
    if ($id_resultado <= 0) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID baliogabea']);
            exit;
        }
        header('Location: indexAdmin.php?page=partidasAdmin&error=id_ez_dago');
        exit;
    }
    
    $resultado = $bd->eliminarPartida($id_resultado);
    $bd->cerrarConexion();
    
    if ($isAjax) {
        header('Content-Type: application/json');
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Partida ondo ezabatu da']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errorea partida ezabatzean']);
        }
        exit;
    }
    
    if ($resultado) {
        header('Location: indexAdmin.php?page=partidasAdmin&success=partida_ezabatuta');
    } else {
        header('Location: indexAdmin.php?page=partidasAdmin&error=ezabatze_errorea');
    }
    exit;
}

// Obtener partidas con paginación y filtros
$offset = ($page_num - 1) * $limit;
$partidas = $bd->obtenerPartidasAdmin($filtros, $limit, $offset);
$total = $bd->obtenerTotalPartidasAdmin($filtros);

// Debug: Verificar datos
error_log("DEBUG partidasAdmin - Total partidas: " . $total);
error_log("DEBUG partidasAdmin - Partidas obtenidas: " . count($partidas));
error_log("DEBUG partidasAdmin - Filtros: " . print_r($filtros, true));

// Función auxiliar para construir URL de paginación
function construirUrlPaginacion($page) {
    $params = $_GET;
    $params['page_num'] = $page;
    return '?' . http_build_query($params);
}

$bd->cerrarConexion();
?>