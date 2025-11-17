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

    function mostrarUsuarios($buscar_usuario = '', $id_centro = '', $id_ciclo = '', $pagina = 1, $limite = 5){
        $bd = new AccesoBD();
        $total = $bd->contarUsuarios($buscar_usuario, $id_centro, $id_ciclo);
        $offset = ($pagina - 1) * $limite;
        $usuarios = $bd->obtenerUsuariosFiltrados($buscar_usuario, $id_centro, $id_ciclo, $limite, $offset);
        return ['usuarios' => $usuarios, 'total' => $total, 'pagina' => $pagina, 'limite' => $limite];
    }

    function obtenerCentrosArray(){
        $bd = new AccesoBD();
        return $bd->obtenerCentros();
    }

    function obtenerCiclosArray(){
        $bd = new AccesoBD();
        return $bd->obtenerCiclos();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        $accion = $_POST['action'] ?? '';
        $bd = new AccesoBD();

        switch ($accion) {            case 'editarUsuario':
                $id_usuario = $_POST['id_usuario'] ?? null;
                $nombre = $_POST['nombre'] ?? null;
                $apellidos = $_POST['apellidos'] ?? null;
                $email = $_POST['email'] ?? null;
                $id_centro = $_POST['id_centro'] ?? null;
                $id_ciclo = $_POST['id_ciclo'] ?? null;
                $puntos_totales = $_POST['puntos_totales'] ?? 0;
                $password = $_POST['password'] ?? null;

                if (!$id_usuario || !$nombre || !$email || !$id_centro || !$id_ciclo) {
                    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
                    break;
                }

                $resultado = $bd->editarUsuario($id_usuario, $nombre, $apellidos, $email, $id_centro, $id_ciclo, $puntos_totales, $password);

                if ($resultado) {
                    echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario']);
                }
                break;

            case 'eliminarUsuario':
                $id_usuario = $_POST['id_usuario'] ?? null;

                if (!$id_usuario) {
                    echo json_encode(['success' => false, 'message' => 'Identificador de usuario no válido']);
                    break;
                }

                $resultado = $bd->eliminarUsuario($id_usuario);

                if ($resultado) {
                    echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al eliminar usuario']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
                break;
        }
        exit;
    }
?>