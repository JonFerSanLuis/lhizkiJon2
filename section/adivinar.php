<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adivinar - LHIZKI</title>
    <link rel="icon" type="image/x-icon" href="../fotos/logoH.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/adivinar1.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body class="game-container">    
    <!-- Header with lives counter -->
    <header class="container-fluid py-4 shadow">
        <div class="row justify-content-end">
            <div class="col-auto">                
                <div class="lives-container">
                    <h5 class="text-dark mb-3 text-center">
                        Bizitzak
                    </h5>                    
                    <div class="d-flex justify-content-center gap-2" id="lives-display">
                        <i class="fas fa-heart fs-3 life-heart filled"></i>
                        <i class="fas fa-heart fs-3 life-heart filled"></i>
                        <i class="fas fa-heart fs-3 life-heart filled"></i>
                        <i class="fas fa-heart fs-3 life-heart filled"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <footer class="mobile-footer d-flex justify-content-around align-items-center d-md-none fixed-bottom">
      <button type="button" class="footer-btn active" aria-label="Inicio" onclick="location.href='../Index.php'">
        <i class="bi bi-house-fill"></i>
        <span class="btn-label">Inicio</span>
      </button>
      <button type="button" class="footer-btn" aria-label="Estadísticas" onclick="alert('Función de estadísticas próximamente')">
        <i class="bi bi-bar-chart-fill"></i>
        <span class="btn-label">Ranking</span>
      </button>
      <button type="button" class="footer-btn" aria-label="Perfil" onclick="location.href='perfilAlumno.php'">
        <i class="bi bi-person-circle"></i>
        <span class="btn-label">Perfil</span>
      </button>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Java Script/adivinar.js"></script>
</body>
</html>