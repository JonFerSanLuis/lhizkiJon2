<?php
    require_once __DIR__ .'/../model/AccesoBD.php';

    function mostrarOpcionesCentros(){
        $bd = new AccesoBD();
        $centros = $bd->obtenerCentros();

        foreach($centros as $centro){
            echo '<option value="' . $centro['id_centro'] . '">' . htmlspecialchars($centro['nombre_centro']) . '</option>';
        }
    }

    function mostrarOpcionesCiclos(){
        $bd = new AccesoBD();
        $ciclos = $bd->obtenerCiclos();

        foreach($ciclos as $ciclo){
            echo '<option value="' . $ciclo['id_ciclo'] . '">' . htmlspecialchars($ciclo['nombre_ciclo']) . '</option>';
        }
    }

    function mostrarUsuarios(){
        $bd = new AccesoBD();
        $usuarios = $bd->obtenerUsuarios();

        return $usuarios;
    }

    function editarUsuario(){
        $bd = new AccesoBD();
        $resultado = $bd->editarUsuario($id_usuario, $nombre, $apellidos, $email, $id_centro, $id_ciclo, $puntos_totales);

        return $resultado;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'editarUsuario') {
        header('Content-Type: application/json');
        $id_usuario = $_POST['id_usuario'] ?? null;
        $nombre = $_POST['nombre'] ?? null;
        $apellidos = $_POST['apellidos'] ?? null;
        $email = $_POST['email'] ?? null;
        $id_centro = $_POST['id_centro'] ?? null;
        $id_ciclo = $_POST['id_ciclo'] ?? null;
        $puntos_totales = $_POST['puntos_totales'] ?? 0;
        
        if (!$id_usuario || !$nombre || !$email || !$id_centro || !$id_ciclo) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
            exit;
        }
        
        $bd = new AccesoBD();
        $resultado = $bd->editarUsuario($id_usuario, $nombre, $apellidos, $email, $id_centro, $id_ciclo, $puntos_totales);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario']);
        }
    } else {
        //echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    }

//hola



?>