    // Añadir funcionalidad al botón de confirmar cierre de sesión
const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', function() {
            window.location.href = 'controller/logout-controller.php';
        });
    }