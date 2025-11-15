<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Index.php');
    exit();
}

require_once "../model/AccesoBD.php";

$accesoBD = new AccesoBD();

// Obtener datos del formulario
$nombre = $_POST['reg-name'] ?? '';
$apellidos = $_POST['reg-apellidos'] ?? '';
$email = $_POST['reg-email'] ?? '';
$password = $_POST['reg-password'] ?? '';
$confirm_password = $_POST['reg-confirm-password'] ?? '';
$id_centro = $_POST['reg-centro'] ?? '';
$id_ciclo = $_POST['reg-ciclo'] ?? '';

// ROL PREDEFINIDO: Obtener automáticamente el ID del rol "Estudiante"
$id_rol = 1;
// Validar contraseñas
if ($password !== $confirm_password) {
    echo 'alert("Las contraseñas no coinciden");';
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

// Preparar datos para el registro
$datosUsuario = [
    'nombre' => $nombre,
    'apellidos' => $apellidos,
    'email' => $email,
    'password' => $hash,
    'id_rol' => $id_rol,
    'id_centro' => $id_centro,
    'id_ciclo' => $id_ciclo
];

// Usar AccesoBD para registrar el usuario
$resultado = $accesoBD->registrarUsuario($datosUsuario);
$accesoBD->cerrarConexion();

if ($resultado['exito']) {
    // Registro exitoso
    //mostrar mensaje flash en login
    $_SESSION['registro_exitoso'] = true;
    header('Location: ../Index.php');
    exit();
} else {
    // Error en el registro - mapear mensajes a códigos de error
    $codigoError = 'registro_fallido';
    
    switch ($resultado['mensaje']) {
        case 'El email ya está registrado':
            $codigoError = 'email_existe';
            break;
        case 'Centro educativo no válido':
            $codigoError = 'centro_invalido';
            break;
        case 'Ciclo formativo no válido para el centro seleccionado':
            $codigoError = 'ciclo_invalido';
            break;
    }
    
    header("Location: ../Index.php?error=$codigoError");
    exit();
}
?>
