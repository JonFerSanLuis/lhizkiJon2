<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/AccesoBD.php';
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
            <h1>Irakasleen Panela</h1>
            <p>Ikasleen jarraitzea</p>
        </div>

        <?php
        $id_centro = $_SESSION['id_centro'] ?? null;
        $bd = new AccesoBD();
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
            <div class="col-lg-3 col-md-6">
                <div class="stats-card blue">
                    <i class="bi bi-people-fill"></i>
                    <div class="number"><?= $stats['num_alumnos'] ?></div>
                    <div class="label">Ikasleek</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card purple">
                    <i class="bi bi-bar-chart-fill"></i>
                    <div class="number"><?= $stats['porcentaje_participacion'] ?>%</div>
                    <div class="label">Parted.</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card yellow">
                    <i class="bi bi-check-circle-fill"></i>
                    <div class="number"><?= $stats['num_completados'] ?></div>
                    <div class="label">Osatuta</div>
                </div>
            </div>
        </div>

        <!-- Lista de alumnos -->
        <div class="content-section">
            <div class="section-title">
                <i class="bi bi-people"></i>
                Zure ikasleak
            </div>
            <?php
            $id_centro = $_SESSION['id_centro'] ?? null;
            $alumnos = [];
            if ($id_centro) {
                $bd = new AccesoBD();
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
                                <th>Akzioak</th> <!-- Nueva columna para acciones -->
                            </tr>
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
                                        <!-- Modal Editar -->
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
                                        <!-- Modal Eliminar -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
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