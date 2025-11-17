<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../Index.php');
    exit();
}

require_once "../model/AccesoBD.php";

// obtiene el glosario
$accesoBD = new AccesoBD();
$glosario = $accesoBD->obtenerGlosario();
$accesoBD->cerrarConexion();
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glosarioa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/historialAlumno.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="mobile-container">
    <div class="header-historial">
        <a href="perfilAlumno.php" class="btn-back">
            <i class="bi bi-arrow-left"></i> Itzuli
        </a>
        <h1 class="header-title">Glosarioa</h1>
        <div class="header-subtitle">Hitz zerrenda</div>
    </div>

    <div class="historial-content">
        <?php if (count($glosario) === 0): ?>
            <div class="empty-state">
                <p class="empty-text">Oraindik ez da hitzik aurkitu</p>
            </div>
        <?php else: ?>
            <div class="ranking-section">
                <h2 class="section-title">Termino guztiak (A-Z)</h2>
                <ul class="list-group">
                    <?php foreach ($glosario as $termino): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold"><?php echo htmlspecialchars($termino['termino_euskera']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($termino['termino_castellano']); ?></small>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include_once "footerAlumno.php"; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/cerrar-sesion.js"></script>
</body>
</html>