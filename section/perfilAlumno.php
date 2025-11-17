<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['email'])) {
    // Redirigir al login si no hay sesión
    header('Location: ../Index.php');
    exit();
}

// Cargar datos del usuario desde la base de datos
require_once "../model/AccesoBD.php";
require_once "../model/usuario.php";

$usuario = new Usuario();
$accesoBD = new AccesoBD();

if (!$accesoBD->rellenarUsuario($usuario, $_SESSION['email'])) {
    // Si no se pueden cargar los datos, cerrar sesión y redirigir
    session_destroy();
    header('Location: ../Index.php?error=3');
    exit();
}

// Comprobar estado de los juegos
$juego_adivina_hitza_ID = 1;
$juego_test_hitza_ID = 2;
$juego_adivina_activo = $accesoBD->estaJuegoActivo($juego_adivina_hitza_ID);
$juego_test_activo = $accesoBD->estaJuegoActivo($juego_test_hitza_ID);

$accesoBD->cerrarConexion();
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($usuario->getNombre() ?? ''); ?> Home</title>
    <link rel="icon" type="image/png" href="../img/logoH1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/inicioStyles.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
    <div class="mobile-container">
        <header class="app-header">            
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="greeting">Kaixo</div>
                    <h1 class="user-name"><?php echo htmlspecialchars($usuario->getNombre() ?? ''); ?></h1>
                </div>
                <button class="notification-btn">
                    <i class="bi bi-bell-fill"></i>
                </button>
            </div>

        </header>

        <div class="missions-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="missions-title">Misioak</h2>
                <button class="missions-icon-btn">
                    <i class="bi bi-plus-circle"></i>
                </button>
            </div>
            <?php if ($juego_adivina_activo): ?>
                <button class="start-game-btn mb-2" onclick="location.href='juegoAdivinaHitza.php'">
                    Hasi Adivina Hitza
                </button>
            <?php else: ?>
                <button class="start-game-btn mb-2" disabled style="background-color: #e0e0e0; color: #9e9e9e; cursor: not-allowed; opacity: 0.7;">
                    Adivina Hitza ez dago erabilgarri
                </button>
            <?php endif; ?>

            <?php if ($juego_test_activo): ?>
                <button class="start-game-btn" onclick="location.href='juegoTestHitza.php'">
                    Hasi Test Hitza
                </button>
            <?php else: ?>
                <button class="start-game-btn" disabled style="background-color: #e0e0e0; color: #9e9e9e; cursor: not-allowed; opacity: 0.7;">
                    Test Hitza ez dago erabilgarri
                </button>
            <?php endif; ?>
            </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon trophy">
                    <i class="bi bi-trophy-fill"></i>
                </div>                <div class="stat-label">Puntuak</div>
                <div class="stat-value"><?php echo $usuario->getPuntosTotales() ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon chart">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-label">Maila</div>
                <div class="stat-value"><?php echo htmlspecialchars($usuario->getCiclo() ?? '' ); ?></div>
            </div>
        </div>

        <div class="feature-cards">
            <div class="feature-card">
                <div class="feature-icon bulb">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
                <div class="feature-title">Markez rankinga</div>
                <div class="feature-subtitle">3 porroan zaudez</div>
            </div>

            <div class="feature-card">
                <div class="feature-icon clock">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="feature-title">Jakoen historala</div>
                <div class="feature-subtitle">Azken 15 jakoak</div><a href="historialAlumno.php" class="stretched-link" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;"></a>
            </div>

            <div class="feature-card">
                <div class="feature-icon document">
                    <i class="bi bi-file-text-fill"></i>
                </div>
                <div class="feature-title">Glosarioak</div>
                <div class="feature-subtitle">Zure hitz-zerrenda</div>
                <a href="glosario.php" class="stretched-link" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;"></a>
            </div>

            <div class="feature-card">
                <div class="feature-icon book">
                    <i class="bi bi-book-fill"></i>
                </div>
                <div class="feature-title">Materialak</div>
                <div class="feature-subtitle">Ikasteko baliabideak</div>
            </div>
        </div>
    </div>
    <?php include_once "footerAlumno.php"; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/inicioScript.js"></script>
    <script src="../js/cerrar-sesion.js"></script>
</body>
</html>