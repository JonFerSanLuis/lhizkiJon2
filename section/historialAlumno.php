<?php
session_start();

//si no hay sesión, fuera
if (!isset($_SESSION['email'])) {
    header('Location: ../Index.php');
    exit();
}

require_once "../model/AccesoBD.php";
require_once "../model/usuario.php";

$usuario = new Usuario();
$accesoBD = new AccesoBD();

//Si no se puede rellenar el usuario, fuera
if (!$accesoBD->rellenarUsuario($usuario, $_SESSION['email'])) {
    session_destroy();
    header('Location: ../Index.php?error=3');
    exit();
}

// --- OBTENER HISTORIAL DE PARTIDAS ---
$idUsuario = $usuario->getIdUsuario();
$historial = $accesoBD->obtenerHistorialPartidas($idUsuario, 15);
$accesoBD->cerrarConexion();

function obtenerTrofeo($aciertos, $completado) {
    if (!$completado) return ['icon' => '🏆', 'color' => 'bronze'];
    if ($aciertos >= 8) return ['icon' => '🏆', 'color' => 'gold'];
    if ($aciertos >= 5) return ['icon' => '🏆', 'color' => 'silver'];
    return ['icon' => '🏆', 'color' => 'bronze'];
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/historialAlumno.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="mobile-container">
    <!-- Nuevo header con gradiente púrpura y botón de retorno -->
    <div class="header-historial">
        <a href="perfilAlumno.php" class="btn-back">
            <i class="bi bi-arrow-left"></i> Itzuli
        </a>
        <h1 class="header-title">Historial</h1>
        <div class="header-subtitle">Klaiseik eta ikasleok</div>
    </div>

    <div class="historial-content">
        <?php if (count($historial) === 0): ?>
            <!-- Mensaje vacío más visual -->
            <div class="empty-state">
                <div class="empty-icon">🎮</div>
                <p class="empty-text">Oraindik ez da jokoarik jokatu</p>
            </div>
        <?php else: ?>
            <!-- Tarjetas de ranking individuales en lugar de tabla -->
            <div class="ranking-section">
                <h2 class="section-title">Klaseen rankinga</h2>
                <div class="ranking-list">
                    <?php foreach ($historial as $index => $j): 
                        $trofeo = obtenerTrofeo($j['aciertos'], $j['completado']);
                        $porcentaje = ($j['aciertos'] > 0) ? round(($j['aciertos'] / ($j['aciertos'] + $j['fallos'])) * 100) : 0;
                    ?>
                    <div class="ranking-card">
                        <div class="ranking-card-left">
                            <div class="trophy-icon trophy-<?= $trofeo['color'] ?>">
                                <?= $trofeo['icon'] ?>
                            </div>
                            <div class="ranking-info">
                                <h3 class="game-title"><?= htmlspecialchars($j['titulo'] ?? '-') ?></h3>
                                <div class="game-meta">
                                    <span class="game-date">
                                        <i class="bi bi-calendar3"></i> <?= htmlspecialchars($j['fecha_realizacion'] ?? '-') ?>
                                    </span>
                                    <?php if ($j['completado']): ?>
                                        <span class="progress-badge">
                                            <i class="bi bi-check-circle-fill"></i> +<?= rand(2, 5) ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="ranking-card-right">
                            <div class="score-value"><?= htmlspecialchars($j['aciertos'] * 100) ?></div>
                            <div class="score-label">puntuak</div>
                        </div>
                    </div>

                    <!-- Detalles expandibles de la partida -->
                    <div class="game-details">
                        <div class="detail-item">
                            <span class="detail-label">Aciertos</span>
                            <span class="detail-value detail-success"><?= htmlspecialchars($j['aciertos'] ?? 0) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Erroreak</span>
                            <span class="detail-value detail-error"><?= htmlspecialchars($j['fallos'] ?? 0) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Denbora</span>
                            <span class="detail-value"><?= htmlspecialchars($j['tiempo_empleado'] ?? '-') ?>s</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Portzentajea</span>
                            <span class="detail-value detail-percentage"><?= $porcentaje ?>%</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include_once "footerAlumno.php"; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/cerrar-sesion.js"></script>
</body>
</html>
