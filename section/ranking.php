<?php
session_start();

//hago el chequeo de sesión para que solo usuarios logueados puedan ver el ranking
if (!isset($_SESSION['email'])) {
    header('Location: ../Index.php');
    exit();
}

require_once "../model/AccesoBD.php";

//hago la instancia de la clase de acceso a BD y obtengo los datos para los rankings y estadísticas
$accesoBD = new AccesoBD();
$rankingCiclos = $accesoBD->obtenerRankingCiclos(); //hago el ranking de ciclos
$rankingAlumnos = $accesoBD->obtenerRankingAlumnos(); //hago el ranking de alumnos
$estadisticas = $accesoBD->obtenerEstadisticasRanking(); //hago las estadísticas generales
$accesoBD->cerrarConexion();

//hago la función para poner medallas a los 3 primeros puestos
function obtenerMedalla($posicion) {
    if ($posicion === 0) return '🥇';
    if ($posicion === 1) return '🥈';
    if ($posicion === 2) return '🥉';
    return '';
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rankings - LHIZKI</title>
    <link rel="icon" type="image/png" href="../img/logoH1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/ranking.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
    <div class="ranking-container">
        <header class="ranking-header">
            <button class="back-btn" onclick="location.href='perfilAlumno.php'">
                <i class="bi bi-arrow-left"></i>
                <span>Itzuli</span>
            </button>
            <h1>Rankings</h1>
            <p>Klaseak eta ikasleek</p>

            <div class="ranking-stats">
                <div class="stat-box">
                    <div class="label">Guzira klaseak</div>
                    <div class="value"><?php echo $estadisticas['total_clases']; ?></div>
                </div>
                <div class="stat-box">
                    <div class="label">Ikasle aktiboak</div>
                    <div class="value"><?php echo $estadisticas['total_alumnos']; ?></div>
                </div>
            </div>
        </header>

        <div class="tabs-container">
            <button class="tab-btn active" data-tab="klaseak">Klaseak</button>
            <button class="tab-btn" data-tab="ikasleok">Ikasleak</button>
        </div>

        <div id="klaseak" class="tab-content active">
            <div class="ranking-section">
                <div class="section-title">Klaseen rankinga</div>

                <?php 
                //hago el if para comprobar si hay datos de ranking de ciclos
                if (count($rankingCiclos) > 0): ?>
                    <?php 
                    //hago el foreach para mostrar cada ciclo en el ranking
                    foreach  ($rankingCiclos as $index => $ciclo): ?> 
                        <div class="ranking-card">
                            <div class="medal-icon">
                                <?php echo obtenerMedalla($index); ?>
                            </div>
                            <div class="ranking-info">
                                <div class="ranking-name"><?php echo htmlspecialchars($ciclo['nombre_ciclo']); ?></div>
                                <div class="ranking-detail">
                                    <?php echo $ciclo['num_alumnos']; ?> <?php echo $ciclo['num_alumnos'] == 1 ? 'ikasle' : 'ikasleak'; ?>
                                </div>
                            </div>
                            <div class="ranking-points">
                                <div class="points-value"><?php echo $ciclo['total_puntos']; ?></div>
                                <div class="points-label">puntuak</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!--hago el else para mostrar mensaje si no hay datos-->
                    <div class="ranking-card">
                        <div class="ranking-info">
                            <div class="ranking-name">Ez dago daturik</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="ikasleok" class="tab-content">
            <div class="ranking-section">
                <div class="section-title">Ikasle onenask</div>

                <?php 
                //hago el if para comprobar si hay datos de ranking de alumnos
                if (count($rankingAlumnos) > 0): ?>
                    <?php 
                    //hago el foreach para mostrar cada alumno en el ranking
                    foreach ($rankingAlumnos as $index => $alumno): ?>
                        <div class="ranking-card">
                            <div class="medal-icon">
                                <?php echo obtenerMedalla($index); ?>
                            </div>
                            <div class="ranking-info">
                                <div class="ranking-name">
                                    <?php echo htmlspecialchars($alumno['nombre']); ?>
                                    <?php //hago el if para mostrar apellidos si existen
                                    if ($alumno['apellidos']): ?>
                                        <?php echo htmlspecialchars($alumno['apellidos']); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="ranking-detail"><?php echo htmlspecialchars($alumno['nombre_ciclo'] ?? 'Ziklorik gabe'); ?></div>
                            </div>
                            <div class="ranking-points">
                                <div class="points-value"><?php echo $alumno['puntos_totales']; ?></div>
                                <div class="points-label">puntuak</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!--hago el else para mostrar mensaje si no hay datos-->
                    <div class="ranking-card">
                        <div class="ranking-info">
                            <div class="ranking-name">Ez dago daturik</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include_once "footerAlumno.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/ranking.js"></script>
    <script src="../js/cerrar-sesion.js"></script>
    
</body>
</html>
