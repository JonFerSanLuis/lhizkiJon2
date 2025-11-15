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
            <div class="progress-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="progress-text">Aste honetan aurreratua</span>
                    <span class="progress-percentage">75%</span>
                </div>
                <div class="progress custom-progress">
                    <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </header>

        <div class="missions-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="missions-title">Misioak</h2>
                <button class="missions-icon-btn">
                    <i class="bi bi-plus-circle"></i>
                </button>
            </div>
            <div class="mission-item">
                <i class="bi bi-check-circle-fill"></i>
                <span>Irakasketa Frogak</span>
            </div>
            <div class="mission-item">
                <i class="bi bi-circle"></i>
                <span>Parte-hartzea</span>
            </div>
            <button class="start-game-btn" onclick="location.href='juegoAdivinaHitza.php'">Hasi jolasa</button>
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
                <div class="feature-subtitle">Azken 15 jakoak</div><!--Se tienen que mover estos estilos a la hoja de estilos lo dejo como nota-->
                <a href="historialAlumno.php" class="stretched-link" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;"></a>
            </div>

            <div class="feature-card">
                <div class="feature-icon document">
                    <i class="bi bi-file-text-fill"></i>
                </div>
                <div class="feature-title">Glosarioak</div>
                <div class="feature-subtitle">Zure hitz-zerrenda</div>
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
    <!-- Este script es para el inicio -->
    <script src="../js/inicioScript.js"></script>
    <!-- Para confirmar el cierre de sesion -->
    <script src="../js/cerrar-sesion.js"></script>
</body>
</html>
