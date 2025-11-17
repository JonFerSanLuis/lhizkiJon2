<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/AccesoBD.php';

// --- INICIO DE LÓGICA PARA GESTIÓN DE JUEGOS ---

// Crear una conexión para manejar los juegos
$bd_juegos = new AccesoBD();

// Comprobar si se ha enviado un formulario para cambiar el estado de un juego
if (isset($_POST['accion']) && $_POST['accion'] === 'cambiar_estado_juego') {
    
    // El ID del juego que se está actualizando
    $id_juego_para_cambiar = $_POST['id_juego'];
    
    // 'juego_activo' es el nombre del checkbox.
    // Si el checkbox está marcado, $_POST['juego_activo'] existirá (y tendrá valor "1").
    // Si no está marcado, no existirá.
    
    $nuevo_estado = 2; // 2 = Inactivo (por defecto)
    if (isset($_POST['juego_activo'])) {
        $nuevo_estado = 1; // 1 = Activo
    }
    
    // Actualizar la base de datos
    $bd_juegos->actualizarEstadoJuego($id_juego_para_cambiar, $nuevo_estado);
    
    // Recargar la página para ver el cambio y evitar reenvíos
    // Esto es simple y un principiante lo haría así
    echo "<meta http-equiv='refresh' content='0'>";
}

// Obtener la lista de todos los juegos para mostrarlos
$lista_de_juegos = $bd_juegos->obtenerTodosLosJuegos();

// Cerramos la conexión de juegos (la otra se usa más abajo)
$bd_juegos->cerrarConexion();

// --- FIN DE LÓGICA PARA GESTIÓN DE JUEGOS ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Irakasleen Panela - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/perfilProfesor.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="header-section">
            
            <button type="button" class="btn btn-outline-light" 
                    style="position: absolute; top: 40px; right: 50px;" 
                    data-bs-toggle="modal" 
                    data-bs-target="#CerrarSesionModal">
                <i class="bi bi-box-arrow-right"></i> Irten
            </button>
            <h1>Irakasleen Panela</h1>
            <p>Ikasleen jarraitzea</p>
        </div>

        <?php
        $id_centro = $_SESSION['id_centro'] ?? null;
        $bd = new AccesoBD(); // Esta es la conexión original del archivo
        $stats = [
            'num_alumnos' => 0,
            'porcentaje_participacion' => 0,
            'media_participacion' => 0,
            'num_completados' => 0
        ];
        if ($id_centro) {
            $stats = $bd->obtenerStatsCentro($id_centro);
        }
        ?>
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-4">
                <div class="stats-card blue">
                    <i class="bi bi-people-fill"></i>
                    <div class="number"><?= $stats['num_alumnos'] ?></div>
                    <div class="label">Ikasleek</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="stats-card purple">
                    <i class="bi bi-bar-chart-fill"></i>
                    <div class="number"><?= $stats['porcentaje_participacion'] ?>%</div>
                    <div class="label">Parted.</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="stats-card yellow">
                    <i class="bi bi-check-circle-fill"></i>
                    <div class="number"><?= $stats['num_completados'] ?></div>
                    <div class="label">Osatuta</div>
                </div>
            </div>
        </div>


        <div class="content-section mb-4">
            <div class="section-title">
                <i class="bi bi-controller"></i>
                Jokoak Kudeatu (Gestionar Juegos)
            </div>
            
            <?php if (count($lista_de_juegos) > 0): ?>
                <div class="list-group">
                    <?php foreach ($lista_de_juegos as $juego): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?= htmlspecialchars($juego['titulo']) ?></h5>
                                <small><?= htmlspecialchars($juego['descripcion'] ?? 'Sin descripción') ?></small>
                            </div>
                            
                            <form method="POST" action="perfilPRofesor.php" style="margin: 0;">
                                <input type="hidden" name="accion" value="cambiar_estado_juego">
                                <input type="hidden" name="id_juego" value="<?= $juego['id_juego'] ?>">
                                
                                <div class="form-check form-switch fs-4">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           role="switch" 
                                           id="juego_<?= $juego['id_juego'] ?>"
                                           name="juego_activo"  value="1" <?php if ($juego['id_estado'] == 1) echo 'checked'; // Marcar si es 1 (Activo) ?>
                                           onchange="this.form.submit()"> <label class="form-check-label" for="juego_<?= $juego['id_juego'] ?>"></label>
                                </div>
                            </form>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Ez dago jokoarik sisteman (No hay juegos en el sistema).</div>
            <?php endif; ?>
        </div>
        <div class="content-section">
            <div class="section-title">
                <i class="bi bi-people"></i>
                Zure ikasleak
            </div>
            <?php
            // $id_centro ya está definido arriba
            $alumnos = [];
            if ($id_centro) {
                // $bd ya está instanciada arriba
                $alumnos = $bd->obtenerAlumnosPorCentro($id_centro);
            } else {
                echo "<div class='alert alert-warning'>Ez da aurkitu zure zentroa.</div>";
            }
            ?>
            
             <?php if (count($alumnos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Izena</th>
                                <th>Abizena</th>
                                <th>Emaila</th>
                                <th>Akzioak</th> </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $alumno): ?>
                                <tr>
                                    <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                                    <td><?= htmlspecialchars($alumno['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($alumno['email']) ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Aukerak
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editarModal<?= md5($alumno['email']) ?>">
                                                        <i class="bi bi-pencil"></i> Editatu
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal<?= md5($alumno['email']) ?>">
                                                        <i class="bi bi-trash"></i> Ezabatu
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="modal fade" id="editarModal<?= md5($alumno['email']) ?>" tabindex="-1" aria-labelledby="editarLabel<?= md5($alumno['email']) ?>" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <form method="post" action="">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="editarLabel<?= md5($alumno['email']) ?>">Editatu erabiltzailea</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <input type="hidden" name="edit_email" value="<?= htmlspecialchars($alumno['email']) ?>">
                                                  <div class="mb-3">
                                                    <label class="form-label">Izena</label>
                                                    <input type="text" class="form-control" name="edit_nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" required>
                                                  </div>
                                                  <div class="mb-3">
                                                    <label class="form-label">Abizena</label>
                                                    <input type="text" class="form-control" name="edit_apellidos" value="<?= htmlspecialchars($alumno['apellidos']) ?>" required>
                                                  </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Itxi</button>
                                                  <button type="submit" class="btn btn-primary" name="editar_usuario">Gorde</button>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="modal fade" id="eliminarModal<?= md5($alumno['email']) ?>" tabindex="-1" aria-labelledby="eliminarLabel<?= md5($alumno['email']) ?>" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <form method="post" action="">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="eliminarLabel<?= md5($alumno['email']) ?>">Ezabatu erabiltzailea</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <input type="hidden" name="delete_email" value="<?= htmlspecialchars($alumno['email']) ?>">
                                                  Ziur zaude erabiltzailea ezabatu nahi duzula?
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Utzi</button>
                                                  <button type="submit" class="btn btn-danger" name="eliminar_usuario">Ezabatu</button>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($id_centro): ?>
                <div class="alert alert-info">Ez dago ikaslerik zure zentroan.</div>
            <?php endif; ?>
        </div>
    </div>

    <div id="CerrarSesion">
        <div class="modal fade" id="CerrarSesionModal" tabindex="-1" aria-labelledby="CerrarSesionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="CerrarSesionModalLabel">Confirmar Cierre de Sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas cerrar sesión?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Cerrar Sesión</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="../js/cerrar-sesion.js"></script>

</body>
</html>

<?php
// ... (Procesamiento de edición y eliminación de alumnos se mantiene igual) ...
// Procesar edición
if (isset($_POST['editar_usuario'])) {
    $edit_email = $_POST['edit_email'] ?? '';
    $edit_nombre = $_POST['edit_nombre'] ?? '';
    $edit_apellidos = $_POST['edit_apellidos'] ?? '';
    if ($edit_email && $edit_nombre && $edit_apellidos) {
        $bd = new AccesoBD();
        $sql = "UPDATE usuario SET nombre = '" . mysqli_real_escape_string($bd->conexion, $edit_nombre) . "', apellidos = '" . mysqli_real_escape_string($bd->conexion, $edit_apellidos) . "' WHERE email = '" . mysqli_real_escape_string($bd->conexion, $edit_email) . "'";
        $bd->lanzarSQL($sql);
        echo '<div class="alert alert-success">Erabiltzailea eguneratuta!</div>';
        // Refrescar la página para ver los cambios
        echo '<meta http-equiv="refresh" content="1">';
    }
}
// Procesar eliminación
if (isset($_POST['eliminar_usuario'])) {
    $delete_email = $_POST['delete_email'] ?? '';
    if ($delete_email) {
        $bd = new AccesoBD();
        $sql = "DELETE FROM usuario WHERE email = '" . mysqli_real_escape_string($bd->conexion, $delete_email) . "'";
        $bd->lanzarSQL($sql);
        echo '<div class="alert alert-danger">Erabiltzailea ezabatuta!</div>';
        // Refrescar la página para ver los cambios
        echo '<meta http-equiv="refresh" content="1">';
    }
}
?>