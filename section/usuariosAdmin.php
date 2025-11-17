<?php
require_once __DIR__ . '/../controller/usuariosAdmin-controller.php';

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$scriptDir = rtrim($scriptDir, '/');
$basePath = preg_replace('#/section$#', '', $scriptDir);
$controllerUrl = ($basePath !== '' ? $basePath : '') . '/controller/usuariosAdmin-controller.php';
$controllerUrl = '/' . ltrim(preg_replace('#//+#', '/', $controllerUrl), '/');

$buscar_usuario = $_GET['buscar_usuario'] ?? '';
$id_centro = $_GET['id_centro'] ?? 'default';
$id_ciclo = $_GET['id_ciclo'] ?? 'default';
$pagina = (int)($_GET['pagina'] ?? 1);
$limite = 5;

$result = mostrarUsuarios($buscar_usuario, $id_centro, $id_ciclo, $pagina, $limite);
$usuarios = $result['usuarios'];
$total = $result['total'];
$total_paginas = ceil($total / $limite);
$centros = obtenerCentrosArray();
$ciclos = obtenerCiclosArray();
$start = ($pagina - 1) * $limite + 1;
$end = min($pagina * $limite, $total);
?>


<!-- Formulario de búsqueda y filtros -->
<div class="container bg-gradient-purple text-purple p-4 rounded-3">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people me-2" viewBox="0 0 16 16">
                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                </svg>
                Erabiltzaileak Kudeatu
            </h4>        </div>
    </div>    <form action="" method="GET">
        <input type="hidden" name="page" value="usuariosAdmin">
        
        <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
                <label for="searchUser" class="form-label">
                    <i class="fas fa-user me-2"></i>Erabiltzailea Bilatu
                </label>
                <input type="text" class="form-control" id="searchUser" name="buscar_usuario" 
                       placeholder="Erabiltzailearen izena edo emaila..." 
                       value="<?= htmlspecialchars($buscar_usuario) ?>">
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="seleccionarCentro" class="form-label">
                    <i class="fas fa-building me-2"></i>Zentroa
                </label>
                <select name="id_centro" id="seleccionarCentro" class="form-select">
                    <option value="default" <?= $id_centro == 'default' ? 'selected' : '' ?>>Zentro guztiak</option>
                    <?php foreach($centros as $centro): ?>
                        <option value="<?= $centro['id_centro'] ?>" <?= $id_centro == $centro['id_centro'] ? 'selected' : '' ?>><?= htmlspecialchars($centro['nombre_centro']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="seleccionarCiclo" class="form-label">
                    <i class="fas fa-graduation-cap me-2"></i>Zikloa
                </label>
                <select name="id_ciclo" id="seleccionarCiclo" class="form-select">
                    <option value="default" <?= $id_ciclo == 'default' ? 'selected' : '' ?>>Ziklo guztiak</option>
                    <?php foreach($ciclos as $ciclo): ?>
                        <option value="<?= $ciclo['id_ciclo'] ?>" <?= $id_ciclo == $ciclo['id_ciclo'] ? 'selected' : '' ?>><?= htmlspecialchars($ciclo['nombre_ciclo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="d-flex gap-2 justify-content-center mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Bilatu
            </button>
            <a href="?page=usuariosAdmin" class="btn btn-secondary">
                <i class="fas fa-undo me-2"></i>Garbitu
            </a>
        </div>
        
        <?php if (!empty($buscar_usuario) || (!empty($id_centro) && $id_centro !== 'default') || (!empty($id_ciclo) && $id_ciclo !== 'default')): ?>
            <div class="mt-3 text-center search-results-info">
                <small>
                    Iragazki aktiboak: 
                    <?php if (!empty($buscar_usuario)): ?>
                        <span class="badge bg-primary">Bilaketa: "<?= htmlspecialchars($buscar_usuario) ?>"</span>
                    <?php endif; ?>
                    <?php if (!empty($id_centro) && $id_centro !== 'default'): ?>
                        <span class="badge bg-info">Zentroa aukeratuta</span>
                    <?php endif; ?>
                    <?php if (!empty($id_ciclo) && $id_ciclo !== 'default'): ?>
                        <span class="badge bg-warning">Zikloa aukeratuta</span>
                    <?php endif; ?>
                </small>
            </div>
        <?php endif; ?>
    </form>
</div>

<div class="container mt-4 table-responsive bg-gradient-blue p-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-blue mb-0">
            <i class="fas fa-list me-2"></i>Erabiltzaileen Zerrenda
        </h5>
        <span class="badge bg-info">
            <?= $start ?>-<?= $end ?> erabiltzaileak aurkitu<?= $total != 1 ? 'ak' : 'a' ?>
        </span>
    </div>
      <div class="table-responsive">
        <table class="table bg-gradient-blue text-white table-sm">
            <thead class="bg-gradient-blue text-blue">
                <tr>
                    <th scope="col" class="text-nowrap"><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th scope="col" class="text-nowrap"><i class="fas fa-user me-1"></i>Izena</th>
                    <th scope="col" class="d-none d-md-table-cell"><i class="fas fa-user me-1"></i>Apellidoak</th>
                    <th scope="col" class="d-none d-lg-table-cell"><i class="fas fa-envelope me-1"></i>Email</th>
                    <th scope="col" class="text-nowrap"><i class="fas fa-building me-1"></i>Zentroa</th>
                    <th scope="col" class="d-none d-xl-table-cell"><i class="fas fa-graduation-cap me-1"></i>Zikloa</th>
                    <th scope="col" class="text-nowrap"><i class="fas fa-star me-1"></i>Puntuak</th>
                    <th scope="col" class="text-nowrap"><i class="fas fa-cogs me-1"></i>Ekintzak</th>
                </tr>
            </thead>
            <tbody class="bg-gradient-blue text-blue">
                <?php
                    if (is_array($usuarios) && count($usuarios) > 0):
                        foreach($usuarios as $usuario){
                            $idUsuario = (int)($usuario['id_usuario'] ?? 0);
                            $nombre = htmlspecialchars((string)($usuario['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $apellidos = htmlspecialchars((string)($usuario['apellidos'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $email = htmlspecialchars((string)($usuario['email'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $nombreCentro = htmlspecialchars((string)($usuario['nombre_centro'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $nombreCiclo = htmlspecialchars((string)($usuario['nombre_ciclo'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $puntos = htmlspecialchars((string)($usuario['puntos_totales'] ?? '0'), ENT_QUOTES, 'UTF-8');
                            $centroId = htmlspecialchars((string)($usuario['id_centro'] ?? ''), ENT_QUOTES, 'UTF-8');
                            $cicloId = htmlspecialchars((string)($usuario['id_ciclo'] ?? ''), ENT_QUOTES, 'UTF-8');                            echo '<tr data-user-id="' . $idUsuario . '" data-centro-id="' . $centroId . '" data-ciclo-id="' . $cicloId . '" data-puntos="' . $puntos . '">';
                            echo '<th scope="row">' . $idUsuario . '</th>';
                            echo '<td>' . $nombre . '</td>';
                            echo '<td class="d-none d-md-table-cell">' . $apellidos . '</td>';
                            echo '<td class="d-none d-lg-table-cell">' . $email . '</td>';
                            echo '<td>' . $nombreCentro . '</td>';
                            echo '<td class="d-none d-xl-table-cell">' . $nombreCiclo . '</td>';
                            echo '<td><span class="badge bg-info">' . $puntos . '</span></td>';
                            echo '<td>
                                    <ul class="action-list">
                                        <li><a href="#" data-tip="edit" class="edit-user" data-id="' . $idUsuario . '" aria-label="Erabiltzailea editatu"><i class="fa fa-edit"></i></a></li>
                                        <li><a href="#" data-tip="delete" class="delete-user" data-id="' . $idUsuario . '" aria-label="Erabiltzailea ezabatu"><i class="fa fa-trash"></i></a></li>
                                    </ul>
                                </td>';
                            echo '</tr>';
                        }                    else:
                        echo '<tr>
                                <td colspan="8" class="text-center">
                                    Ez dago erabiltzailerik erregistratuta
                                </td>
                              </tr>';
                    endif;
                 ?>
                
            </tbody>    
        </table>
    </div><div class="panel-footer text-blue">
        <div class="row">
            <div class="col col-sm-6 col-xs-6">
                <b><?php echo $total; ?></b> erabiltzaileak guztira
            </div>
            <div class="col-sm-6 col-xs-6 justify-content-end pe-5 d-flex">
                <ul class="pagination hidden-xs pull-right">
                    <?php
                        $query_params = $_GET;
                        unset($query_params['pagina']);
                        $base_url = '?' . http_build_query($query_params) . '&pagina=';
                        if ($pagina > 1) {
                            echo '<li><a href="' . $base_url . ($pagina - 1) . '"><</a></li>';
                        } else {
                            echo '<li class="disabled"><a href="#"><</a></li>';
                        }
                        for ($i = 1; $i <= $total_paginas; $i++) {
                            $active = $i == $pagina ? 'active' : '';
                            echo '<li class="' . $active . '"><a href="' . $base_url . $i . '">' . $i . '</a></li>';
                        }
                        if ($pagina < $total_paginas) {
                            echo '<li><a href="' . $base_url . ($pagina + 1) . '">></a></li>';
                        } else {
                            echo '<li class="disabled"><a href="#">></a></li>';
                        }
                    ?>
                </ul>
                <ul class="pagination visible-xs pull-right">
                    <li><a href="#"><</a></li>
                    <li><a href="#">></a></li>
                </ul>
            </div>
        </div>
    </div>
                
</div>

<!-- Modal para editar usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Erabiltzailea editatu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" novalidate>
                    <input type="hidden" id="editUserId" name="id_usuario">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editUserName" class="form-label">Izena</label>
                            <input type="text" class="form-control" id="editUserName" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editUserSurnames" class="form-label">Apellidoak</label>
                            <input type="text" class="form-control" id="editUserSurnames" name="apellidos">
                        </div>
                        <div class="col-md-6">
                            <label for="editUserEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editUserEmail" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editUserPuntos" class="form-label">Puntu Totala</label>
                            <input type="number" class="form-control" id="editUserPuntos" name="puntos_totales" min="0">
                        </div>
                        <div class="col-md-6">
                            <label for="editUserCentro" class="form-label">Esleitutako zentroa</label>
                            <select class="form-select" id="editUserCentro" name="id_centro" required>
                                <option value="" disabled>Hautatu zentroa</option>
                                <?php mostrarOpcionesCentros(); ?>
                            </select>
                        </div>                        <div class="col-md-6">
                            <label for="editUserCiclo" class="form-label">Zikloa</label>
                            <select class="form-select" id="editUserCiclo" name="id_ciclo" required>
                                <option value="" disabled>Hautatu zikloa</option>
                                <?php mostrarOpcionesCiclos(); ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="editUserPassword" class="form-label">Pasahitz berria (aukerakoa)</label>
                            <input type="password" class="form-control" id="editUserPassword" name="password" placeholder="Hutsik utzi aldatu nahi ez baduzu">
                            <div class="form-text">Pasahitza aldatu nahi ez baduzu, eremua hutsik utzi</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Itxi</button>
                    <button type="button" class="btn btn-primary" id="guardarCambios">Gorde</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para eliminar usuario -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Erabiltzailea ezabatu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Ziur zaude <strong id="deleteUserName"></strong> erabiltzailea ezabatu nahi duzula?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Utzi</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteUser">Ezabatu</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts: primero Bootstrap, luego tu JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    window.usuariosAdmin = window.usuariosAdmin || {};
    window.usuariosAdmin.controllerUrl = '<?php echo htmlspecialchars($controllerUrl, ENT_QUOTES, 'UTF-8'); ?>';
</script>
<script src="./js/usuariosAdmin.js"></script>