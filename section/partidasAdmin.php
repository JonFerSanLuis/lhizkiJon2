<?php
require_once __DIR__ . '/../controller/partidasAdmin-controller.php';

// Mostrar mensajes de éxito o error
if (isset($_GET['success'])) {
    $mensaje = '';
    switch ($_GET['success']) {
        case 'partida_ezabatuta':
            $mensaje = 'Partida ondo ezabatu da';
            break;
        default:
            $mensaje = 'Eragiketa ondo burutu da';
    }
    echo '<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;" role="alert">
            <i class="fa fa-check-circle me-2"></i>' . htmlspecialchars($mensaje) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}

if (isset($_GET['error'])) {
    $mensaje = '';
    switch ($_GET['error']) {
        case 'id_ez_dago':
            $mensaje = 'Partida ID ez da aurkitu';
            break;
        case 'ezabatze_errorea':
            $mensaje = 'Errorea partida ezabatzean';
            break;
        default:
            $mensaje = 'Errore bat gertatu da';
    }
    
    echo '<div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i>' . $mensaje . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}
?>

<!-- Formulario de búsqueda y filtros -->
<div class="container bg-gradient-purple text-purple p-4 rounded-3">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-trophy me-2" viewBox="0 0 16 16">
                    <path d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5q0 .807-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33 33 0 0 1 2.5.5m.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935m10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935M3.504 1q.01.775.056 1.469c.13 2.028.457 3.546.87 4.667C5.294 9.48 6.484 10 7 10a.5.5 0 0 1 .5.5v2.61a1 1 0 0 1-.757.97l-1.426.356a.5.5 0 0 0-.179.085L4.5 15h7l-.638-.479a.5.5 0 0 0-.18-.085l-1.425-.356a1 1 0 0 1-.757-.97V10.5A.5.5 0 0 1 9 10c.516 0 1.706-.52 2.57-2.864.413-1.12.74-2.64.87-4.667q.045-.694.056-1.469z"/>
                </svg>
                Partidak Kudeatu
            </h4>
        </div>
    </div>    
    <form action="" method="GET">
        <input type="hidden" name="page" value="partidasAdmin">
          <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
                <label for="searchUsuario" class="form-label">
                    <i class="fas fa-user me-2"></i>Erabiltzailea Bilatu
                </label>
                <input type="text" class="form-control" id="searchUsuario" name="buscar_usuario" 
                       placeholder="Erabiltzailearen izena edo emaila..." 
                       value="<?= htmlspecialchars($_GET['buscar_usuario'] ?? '') ?>">
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="seleccionarJuego" class="form-label">
                    <i class="fas fa-gamepad me-2"></i>Jokoa
                </label>
                <select name="id_juego" id="seleccionarJuego" class="form-select">
                    <option value="default">Joko guztiak</option>
                    <?php foreach($juegos as $juego): ?>
                        <option value="<?= $juego['id_juego'] ?>" 
                            <?= (isset($_GET['id_juego']) && $_GET['id_juego'] == $juego['id_juego']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($juego['titulo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3 mb-3">
                <label for="seleccionarCompletado" class="form-label">
                    <i class="fas fa-check-circle me-2"></i>Egoera
                </label>
                <select name="completado" id="seleccionarCompletado" class="form-select">
                    <option value="default">Guztiak</option>
                    <option value="1" <?= (isset($_GET['completado']) && $_GET['completado'] == '1') ? 'selected' : '' ?>>
                        Osatuta
                    </option>
                    <option value="0" <?= (isset($_GET['completado']) && $_GET['completado'] == '0') ? 'selected' : '' ?>>
                        Hasi gabe / Bukatu gabe
                    </option>
                </select>
            </div>
        </div>
        
        <div class="d-flex gap-2 justify-content-center mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Bilatu
            </button>
            <a href="?page=partidasAdmin" class="btn btn-secondary">
                <i class="fas fa-undo me-2"></i>Garbitu
            </a>
        </div>
        
        <?php if (!empty($_GET['buscar_usuario']) || !empty($_GET['id_juego']) || isset($_GET['completado'])): ?>
            <div class="mt-3 text-center search-results-info">
                <small>
                    Iragazki aktiboak: 
                    <?php if (!empty($_GET['buscar_usuario'])): ?>
                        <span class="badge bg-primary">Bilaketa: "<?= htmlspecialchars($_GET['buscar_usuario']) ?>"</span>
                    <?php endif; ?>
                    <?php if (!empty($_GET['id_juego']) && $_GET['id_juego'] !== 'default'): ?>
                        <?php 
                        $juego_nombre = '';
                        foreach($juegos as $juego) {
                            if($juego['id_juego'] == $_GET['id_juego']) {
                                $juego_nombre = $juego['titulo'];
                                break;
                            }
                        }
                        ?>
                        <span class="badge bg-info">Jokoa: <?= htmlspecialchars($juego_nombre) ?></span>
                    <?php endif; ?>
                    <?php if (isset($_GET['completado']) && $_GET['completado'] !== 'default'): ?>
                        <span class="badge bg-warning">
                            Egoera: <?= $_GET['completado'] == '1' ? 'Osatuta' : 'Hasi gabe / Bukatu gabe' ?>
                        </span>
                    <?php endif; ?>
                </small>
            </div>
        <?php endif; ?>
    </form>
</div>

<!-- Tabla de partidas -->
<div class="container mt-4 table-responsive bg-gradient-blue p-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-blue mb-0">
            <i class="fas fa-list me-2"></i>Partiden Zerrenda
        </h5>
        <span class="badge bg-info">
            <?= $total ?> partida aurkitu<?= $total != 1 ? 'ak' : 'a' ?>
        </span>
    </div>
    
    <div class="">
        <table class="table bg-gradient-blue text-white">
            <thead class="bg-gradient-blue text-blue">
                <tr>
                    <th scope="col"><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th scope="col"><i class="fas fa-user me-1"></i>Erabiltzailea</th>
                    <th scope="col"><i class="fas fa-gamepad me-1"></i>Jokoa</th>
                    <th scope="col"><i class="fas fa-check me-1"></i>Asmatzeak</th>
                    <th scope="col"><i class="fas fa-times me-1"></i>Akatsak</th>
                    <th scope="col"><i class="fas fa-clock me-1"></i>Denbora (s)</th>
                    <th scope="col"><i class="fas fa-calendar me-1"></i>Data</th>
                    <th scope="col"><i class="fas fa-toggle-on me-1"></i>Egoera</th>
                    <th scope="col"><i class="fas fa-cogs me-1"></i>Ekintzak</th>
                </tr>
            </thead>
            <tbody class="bg-gradient-blue text-blue">
                <?php if (count($partidas) > 0): ?>
                    <?php foreach ($partidas as $partida): ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($partida['id_resultado']); ?></th>
                            <td><?php echo htmlspecialchars($partida['usuario_nombre'] . ' ' . ($partida['usuario_apellidos'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars($partida['juego_titulo'] ?? 'Ez dago esleituta'); ?></td>
                            <td><span class="badge bg-success"><?php echo htmlspecialchars($partida['aciertos']); ?></span></td>
                            <td><span class="badge bg-danger"><?php echo htmlspecialchars($partida['fallos']); ?></span></td>
                            <td><?php echo htmlspecialchars($partida['tiempo_empleado'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($partida['fecha_realizacion'] ? date('Y-m-d H:i', strtotime($partida['fecha_realizacion'])) : 'N/A'); ?></td>
                            <td>
                                <span class="badge <?= $partida['completado'] ? 'bg-success' : 'bg-warning' ?>">
                                    <?= $partida['completado'] ? 'Osatuta' : 'Bukatu gabe' ?>
                                </span>
                            </td>                            <td>
                                <ul class="action-list">
                                    <li>
                                        <a href="#" class="delete-partida-btn" data-tip="delete" data-id="<?= $partida['id_resultado'] ?>" 
                                           aria-label="Partida ezabatu">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">
                            <?php if (!empty($_GET['buscar_usuario']) || !empty($_GET['id_juego']) || isset($_GET['completado'])): ?>
                                Ez da partidarik aurkitu zehaztutako bilaketa irizpideekin.
                                <br><a href="?page=partidasAdmin" class="btn btn-link">Partida guztiak erakutsi</a>
                            <?php else: ?>
                                Ez dago partidarik erregistratuta
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>            
            </tbody>    
        </table>
    </div>
    <div class="panel-footer text-blue">
        <div class="row">
            <div class="col col-sm-6 col-xs-6">
                <b><?php echo count($partidas); ?></b>-tik <b><?php echo $total; ?></b> sarrera daude orrialde honetan
                <?php if (!empty($_GET['buscar_usuario']) || !empty($_GET['id_juego']) || isset($_GET['completado'])): ?>
                    (iragazita)
                <?php endif; ?>
            </div>
            <div class="col-sm-6 col-xs-6 justify-content-end pe-5 d-flex">
                <?php
                $total_pages = ceil($total / $limit);
                if ($total_pages > 1) {
                    echo '<ul class="pagination hidden-xs pull-right">';
                    // Previous
                    $prev_page = $page_num - 1;
                    $disabled_prev = ($page_num == 1) ? ' class="disabled"' : '';
                    echo '<li' . $disabled_prev . '><a href="' . construirUrlPaginacion($prev_page) . '">←</a></li>';
                    
                    // Pages
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active = ($i == $page_num) ? ' class="active"' : '';
                        echo '<li' . $active . '><a href="' . construirUrlPaginacion($i) . '">' . $i . '</a></li>';
                    }
                    
                    // Next
                    $next_page = $page_num + 1;
                    $disabled_next = ($page_num == $total_pages) ? ' class="disabled"' : '';
                    echo '<li' . $disabled_next . '><a href="' . construirUrlPaginacion($next_page) . '">→</a></li>';
                    echo '</ul>';
                    
                    // Small-screen controls
                    $disabled_start = ($page_num == 1) ? ' class="disabled"' : '';
                    $disabled_end = ($page_num == $total_pages) ? ' class="disabled"' : '';
                    echo '<ul class="pagination visible-xs pull-right">';
                    echo '<li' . $disabled_start . '><a href="' . construirUrlPaginacion(1) . '">Start</a></li>';
                    echo '<li' . $disabled_end . '><a href="' . construirUrlPaginacion($total_pages) . '">End</a></li>';
                    echo '</ul>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Confirmar borrado de partida (nuevo) -->
<div class="modal fade" id="confirmDeleteResultModal" tabindex="-1" aria-labelledby="confirmDeleteResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteResultModalLabel">Ezabatu partida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Itxi"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Ziur zaude partida hau ezabatu nahi duzula?</p>
                <small class="text-danger d-block">Ekintza atzeraezina da.</small>
                <input type="hidden" id="deleteResultId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Utzi</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteResultBtn">Ezabatu</button>
            </div>
        </div>
    </div>
 </div>

<!-- Script específico de partidasAdmin -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="./js/partidasAdmin.js"></script>