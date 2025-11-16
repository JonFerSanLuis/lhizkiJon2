<?php
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../Index.php');
    exit();
}

require_once "../model/AccesoBD.php";
require_once "../model/usuario.php";

$accesoBD_comprobar_juego = new AccesoBD();
$juego_test_hitza_ID = 2;

if (!$accesoBD_comprobar_juego->estaJuegoActivo($juego_test_hitza_ID)) {
    $accesoBD_comprobar_juego->cerrarConexion();
    header('Location: perfilAlumno.php');
    exit();
}

$accesoBD_comprobar_juego->cerrarConexion();

$usuario = new Usuario();
$accesoBD = new AccesoBD();

if (!$accesoBD->rellenarUsuario($usuario, $_SESSION['email'])) {
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
    <title>Test Hitza - LHIZKI</title>
    <link rel="icon" type="image/png" href="../img/logoH1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/juego_css.css">
    <link rel="stylesheet" href="../css/footer.css">
    <style>
        .option-btn {
            width: 100%;
            padding: 16px;
            margin-bottom: 12px;
            background: white;
            color: #3c3c3c;
            border: 3px solid #e5e5e5;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .option-btn:hover:not(:disabled) {
            border-color: #1cb0f6;
            background: #f0f9ff;
            transform: translateY(-2px);
        }
        .option-btn:disabled {
            cursor: not-allowed;
        }
        .option-btn.correct {
            border-color: #58cc02;
            background: #f0fdf4;
            color: #065f46;
        }
        .option-btn.incorrect {
            border-color: #ff4b4b;
            background: #fef2f2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="mobile-container">
        <header class="game-header">
            <div class="header-content">
                <button class="btn-back" onclick="location.href='perfilAlumno.php'">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <h1 class="game-title">Test Hitza</h1>
                <div class="lives-container">
                    <i class="bi bi-heart-fill life-icon active"></i>
                    <i class="bi bi-heart-fill life-icon active"></i>
                    <i class="bi bi-heart-fill life-icon active"></i>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-bar-custom">
                    <div class="progress-fill" id="progressBar"></div>
                </div>
                <span class="progress-text" id="progressText">0/10</span>
            </div>
        </header>

        <div id="loadingScreen" class="game-screen active">
            <div class="loading-content">
                <div class="loading-spinner">
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                </div>
                <h2 class="loading-title">Galderak prestatzen...</h2>
                <p class="loading-subtitle">Itxaron mesedez</p>
            </div>
        </div>

        <div id="gameScreen" class="game-screen">
            <div class="game-content">
                <div class="question-card">
                    <div class="question-label">Zelan esaten da hitz hau euskeraz?</div>
                    <div class="question-word" id="questionWord">cargando...</div>
                </div>

                <div class="answer-section">
                    <div id="optionsContainer"></div>
                </div>
            </div>
        </div>

        <div id="resultsScreen" class="game-screen">
            <div class="results-content">
                <div class="results-icon" id="resultsIcon">🏆</div>
                <h2 class="results-title" id="resultsTitle">Zorionak!</h2>
                <div class="results-stats">
                    <div class="stat-item">
                        <div class="stat-value" id="correctAnswers">0</div>
                        <div class="stat-label">Zuzenak</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="wrongAnswers">0</div>
                        <div class="stat-label">Okerrak</div>
                    </div>
                    <div class="stat-item stat-highlight">
                        <div class="stat-value" id="totalPoints">0</div>
                        <div class="stat-label">Puntuak</div>
                    </div>
                </div>
                <div class="results-actions">
                    <button class="btn-primary" onclick="restartGame()">
                        <i class="bi bi-arrow-clockwise"></i>
                        Berriro jokatu
                    </button>
                    <button class="btn-secondary" onclick="location.href='perfilAlumno.php'">
                        <i class="bi bi-house"></i>
                        Hasierara itzuli
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "footerAlumno.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/juego_test.js"></script>
    <script src="../js/cerrar-sesion.js"></script>
</body>
</html>
