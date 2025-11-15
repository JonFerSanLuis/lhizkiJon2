<?php
//require_once 'config.php';
session_start();
//hola

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="css/stylesAdmin.css">
    <title>LHIZKI-PanelAdmin</title>
</head>
<body class="pt-4">
    <header class="container  rounded-top text-white pt-4  ">
         <div class="d-flex flex-column align-items-start gap-2 ps-3">
            <div class="logo-placeholder  bg-opacity-10 d-flex align-items-center justify-content-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield" viewBox="0 0 16 16">
                    <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                </svg>
                <p class="align-self-center h2">Admin Panela</p>
            </div>
            <div class="row">                   
                <p class=" text-white-50 h5">Sistema kudeaketa</p>
            </div>
        </div>

        <div>
            <nav class="nav nav-pills nav-fill bg-opacity-10 rounded-top p-2">
                <a class="nav-link text-white bg-opacity-10" href="indexAdmin.php?page=homeAdmin">Home</a>
                <a class="nav-link text-white bg-opacity-10" href="indexAdmin.php?page=usuariosAdmin">Usuarioak</a>
                <a class="nav-link text-white bg-opacity-10" href="#">Partidak</a>
                <a class="nav-link text-white bg-opacity-10" href="indexAdmin.php?page=centrosAdmin">Centroak</a>
            </nav>
        </div>


    </header>

    <main class="container mb-4 p-4 bg-white rounded-bottom shadow-sm">
        
       <?php
   

            if (isset($_GET['page'])) {
            include "section/" . $_GET['page'] . ".php";
            } else {
            include "section/homeAdmin.php";
            }


        ?>
    
    </main>
 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>