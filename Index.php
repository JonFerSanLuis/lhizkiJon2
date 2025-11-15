<?php
session_start();

// Cargar listas para el formulario de registro
require_once "model/AccesoBD.php";
$accesoBD = new AccesoBD();
$centros = $accesoBD->obtenerCentros();
$accesoBD->cerrarConexion();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <title>LHIZKI</title>
    <link rel="icon" type="image/png" href="./img/logoH1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/loading-animation.css">
</head>
<body class="m-0 p-0">   

    <!-- Overlay de cara -->
    <div id="loading-overlay" class="loading-overlay">
        <div class="loading-content">
            <div class="loading-logo">
                <img src="./img/logoLHIZKI3.png" alt="LHIZKI Logo">
            </div>
            <h2 class="loading-title"></h2>
            <div class="loading-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
            </div>
            <div class="loading-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
            <p class="loading-message">Itxaron mesedez...</p>
        </div>
    </div>

    <header class="d-flex flex-column justify-content-center align-items-center w-100 px-3 px-md-4 px-lg-5" style="height: 30vh;">
        <div id="logo" class="p-3 rounded">
            <img src="./img/logoLHIZKI3.png" alt="logo LHIZKI" width="250" height="auto">
        </div>
        <h3 class="text-center fs-6 fs-md-4 fs-lg-3 mb-5">Hitz teknikoak euskeraz</h3>    
    </header>    
    
    <main class="d-flex justify-content-center align-items-center w-100 py-2" style="min-height: 12vh;">
        <div class="container">
            <div class="row justify-content-center">
              <div class="col-12 col-md-8 col-lg-6">            
                <div class="d-flex gap-3 justify-content-center">
                    <div class="button-container">
                        <button class="btn btn-custom flex-fill rounded-pill fs-6" type="button" data-button="sartu">
                            Sartu
                        </button>
                        <button class="btn btn-custom flex-fill rounded-pill fs-6" type="button" data-button="erregistratu">
                            Erregistratu
                        </button>
                        <div class="button-slider"></div>
                    </div>
                </div>
              </div>
            </div>        
        </div>
    </main>
      <!-- Contenedor para las secciones superpuestas -->
    <div class="form-sections-container">        
        <section id="login-section" class="mt-4 d-flex flex-column align-items-center"> 
            <form method="POST" action="./controller/login-controller.php" class="w-100 d-flex flex-column align-items-center">
                <div class="form-inputs-container w-100">
                    <label for="email" class="d-block text-start mb-2"><strong>Email</strong></label>
                    <input type="email" name="email" id="email" class="form-control mb-3" placeholder="Sartu zure emaila" required>

                    <label for="password" class="d-block text-start mb-2"><strong>Pasahitza</strong></label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="*********" required>        
                </div>

                <button class="mt-5 btn btn-success form-button" type="submit">
                    <p class="mb-0">Sartu</p>
                </button>
                <?php
                //mensaje flash de registro exitoso
                if (isset($_SESSION['registro_exitoso']) && $_SESSION['registro_exitoso']) {
                    echo '<div class="alert alert-success text-center mt-3">Erabiltzailea ondo erregistratu da! Orain saioa hasi dezakezu.</div>';
                    unset($_SESSION['registro_exitoso']);
                }
                //mensaje flash de error de login
                if (isset($_GET['error']) && $_GET['error'] === 'login') {
                    echo '<div class="alert alert-danger text-center mt-3">Erabiltzailea edo pasahitza ez da zuzena.</div>';
                }
                ?>
            </form>

            <!-- Aqui le llevaria a un formulario de recuperacion de contraseña -->
            <a href="#" class="text-decoration-none color-green mt-4"><strong>Pasahitza ahaztu duzu?</strong></a>
        </section>        <!-- Aqui comienza la seccion de registro -->
        <section id="register-section" class="mt-4 mb-4 d-flex flex-column align-items-center">
            <form method="POST" action="./controller/registro-controller.php" class="w-100 d-flex flex-column align-items-center">
                <div class="form-inputs-container w-100">
                    <label for="reg-name" class="d-block text-start mb-2"><strong>Izena</strong></label>
                    <input type="text" name="reg-name" id="reg-name" class="form-control mb-3" placeholder="Sartu zure izena" required>

                    <label for="reg-apellidos" class="d-block text-start mb-2"><strong>Abizenak</strong></label>
                    <input type="text" name="reg-apellidos" id="reg-apellidos" class="form-control mb-3" placeholder="Sartu zure abizenak">

                    <label for="reg-email" class="d-block text-start mb-2"><strong>Email</strong></label>
                    <input type="email" name="reg-email" id="reg-email" class="form-control mb-3" placeholder="Sartu zure emaila" required>

                    <label for="reg-password" class="d-block text-start mb-2"><strong>Pasahitza</strong></label>
                    <input type="password" name="reg-password" id="reg-password" class="form-control mb-3" placeholder="*********" required>

                    <label for="reg-confirm-password" class="d-block text-start mb-2"><strong>Errepikatu pasahitza</strong></label>
                    <input type="password" name="reg-confirm-password" id="reg-confirm-password" class="form-control mb-3" placeholder="*********" required>



                    <!-- FOREIGN KEY: CENTRO EDUCATIVO -->
                    <label for="reg-centro" class="d-block text-start mb-2"><strong>Ikastetxea</strong></label>
                    <select name="reg-centro" id="reg-centro" class="form-control mb-3" required>
                        <option value="">Aukeratu ikastetxea...</option>
                        <?php foreach ($centros as $centro): ?>
                            <option value="<?php echo $centro['id_centro']; ?>">
                                <?php echo htmlspecialchars($centro['nombre_centro']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>                    
                    <!-- FOREIGN KEY: CICLO FORMATIVO -->
                    <label for="reg-ciclo" class="d-block text-start mb-2"><strong>Ziklo formatibua</strong></label>
                    <select name="reg-ciclo" id="reg-ciclo" class="form-control mb-3" required>
                        <option value="">Lehenengo ikastetxea aukeratu...</option>
                    </select>
                </div>

                <button class="mt-3 btn btn-success form-button mb-5" type="submit">
                    <p class="mb-0">Erregistratu</p>
                </button>
            </form>
        </section></div>

    <script src="./js/Index1.js"></script>
    <script src="./js/registro.js"></script>

</body>
</html>
