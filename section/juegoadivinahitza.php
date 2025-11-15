<?php
session_start();

//Verificar si el usuario está autenticado
if (!isset($_SESSION['email'])) {
    header('Location: ../Index.php');
    exit();
}

require_once "../model/AccesoBD.php";
require_once "../model/usuario.php";

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
    <title>Adivina la Palabra - LHIZKI</title>
    <link rel="icon" type="image/png" href="../img/logoH1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/juego_css.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
    <div class="mobile-container">
        <!-- Header del juego -->
        <header class="game-header">
            <div class="header-content">
                <button class="btn-back" onclick="location.href='perfilAlumno.php'">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <h1 class="game-title">Adivina Hitza</h1>
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

        <!-- Pantalla de carga inicial -->
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

        <!-- Pantalla del juego -->
        <div id="gameScreen" class="game-screen">
            <div class="game-content">
                <div class="question-card">
                    <div class="question-label">Zelan esaten da hitz hau euskeraz?</div>
                    <div class="question-word" id="questionWord">cargando...</div>
                </div>

                <div class="answer-section">
                    <label for="answerInput" class="answer-label">Zure erantzuna:</label>
                    <input 
                        type="text" 
                        id="answerInput" 
                        class="answer-input" 
                        placeholder="Idatzi euskerazko hitza..."
                        autocomplete="off"
                    >
                    <button id="submitBtn" class="btn-submit">
                        <i class="bi bi-check-circle"></i>
                        Egiaztatu
                    </button>
                </div>

                <!-- Feedback visual -->
                <div id="feedbackMessage" class="feedback-message"></div>
            </div>
        </div>

        <!-- Pantalla de resultados -->
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
    <script src="../js/juego_javascript.js"></script>
    <script src="../js/cerrar-sesion.js"></script>
</body>
</html>