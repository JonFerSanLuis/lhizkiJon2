<?php
header('Content-Type: application/json');

if (!isset($_GET['id_centro']) || empty($_GET['id_centro'])) {
    echo json_encode([]);
    exit();
}

require_once "../model/AccesoBD.php";

$id_centro = (int)$_GET['id_centro'];
$accesoBD = new AccesoBD();
$ciclos = $accesoBD->obtenerCiclosPorCentro($id_centro);
$accesoBD->cerrarConexion();

echo json_encode($ciclos);
?>
