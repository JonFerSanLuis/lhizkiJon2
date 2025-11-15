    <div id="CerrarSesion">
        <div class="modal fade" id="CerrarSesionModal" tabindex="-1" aria-labelledby="CerrarSesionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="CerrarSesionModalLabel">Confirmar Cierre de Sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas cerrar sesión?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmLogoutBtn">Cerrar Sesión</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="mobile-footer d-flex justify-content-around align-items-center d-md-none fixed-bottom">
        <button type="button" class="footer-btn active" aria-label="Hasiera" onclick="location.href='perfilAlumno.php'">
            <i class="bi bi-house-fill"></i>
            <span class="btn-label">Hasiera</span>
        </button>
        <button type="button" class="footer-btn" aria-label="Ranking" onclick="location.href='ranking.php'">
            <i class="bi bi-bar-chart-fill"></i>
            <span class="btn-label">Ranking</span>
        </button>
        <button type="button" class="footer-btn" aria-label="Irten" data-bs-toggle="modal" data-bs-target="#CerrarSesionModal">
            <i class="bi bi-box-arrow-right"></i>
            <span class="btn-label">Irten</span>
        </button>
    </footer>