<?php
  require_once("../model/AccesoBD.php");
  require_once("../model/usuario.php");  // Añadido para usar la clase Usuario
  $email = $_POST["email"];
  $password = $_POST["password"];

  $accesoBD = new AccesoBD();
  $resultado = $accesoBD->login($email, $password);

if ($resultado) {
    session_start();
    $_SESSION["email"] = $email;
    $_SESSION["password"] = $password;

    $nombre = $accesoBD->getNombreUsuario($email);

    // Crear instancia de Usuario y rellenar datos
    $usuario = new Usuario();
    $accesoBD->rellenarUsuario($usuario, $email);
    $rol = $usuario->getIdRol() ?? 1;
    
    // Set user_role based on rol
    if ($rol == 3) {
        $_SESSION["user_role"] = "admin";
    } elseif ($rol == 1 || $rol == 2) {
        $_SESSION["user_role"] = "user";
    } else {
        $_SESSION["user_role"] = "guest";
    }
    
    // Guardar id_centro en la sesión (asegura el nombre correcto del método)
    $_SESSION["id_centro"] = $usuario->getIdcentro();

    //rol 1 = alumno
    //rol 2 = profesor
    //rol 3 = admin
    
    // Verificar que se obtuvo el nombre correctamente
    if ($nombre) {
        $_SESSION["nombre"] = $nombre;
        // Redirigir a la página principal para que muestre el estado de la sesión
        switch ($rol) {
            case 1:
                header("Location:../section/perfilAlumno.php");
                break;
            case 2:
                header("Location:../section/perfilProfesor.php");
                break;
            case 3:
                header("Location:../indexAdmin.php");
                break;
            default:
                header("Location:../Index.php");
                break;
        }
        exit();
    } else {
        // Error al obtener el nombre del usuario
        header("Location: ../Index.php?error=2");
        exit();
    }
} else {
    // Redirigir de vuelta indicando error de login (usuario o contraseña mal)
    header("Location: ../Index.php?error=login");
    exit();
}
$accesoBD->cerrarConexion();  // Añadido para cerrar la conexión
?>