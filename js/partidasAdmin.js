// partidasAdmin.js - Funcionalidad para la administración de partidas (versión simplificada y robusta)

(function() {
    let confirmDeleteModal = null;

    document.addEventListener('DOMContentLoaded', function() {
        console.log('partidasAdmin.js - DOM loaded');

        // Instancia única del modal (sin limpiezas manuales)
        const modalElement = document.getElementById('confirmDeleteResultModal');
        if (modalElement && typeof bootstrap !== 'undefined') {
            confirmDeleteModal = new bootstrap.Modal(modalElement, { keyboard: true, backdrop: true });

            // Forzar cierre fiable al pulsar cualquier botón con dismiss
            const dismissBtns = modalElement.querySelectorAll('[data-bs-dismiss="modal"]');
            dismissBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (confirmDeleteModal) confirmDeleteModal.hide();
                    setTimeout(cleanModalState, 50);
                });
            });

            // Asegurar limpieza cuando el modal se haya ocultado
            modalElement.addEventListener('hidden.bs.modal', () => {
                cleanModalState();
            });
        }

        // Auto-ocultar alertas después de 5 segundos
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert.parentNode) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });

        // Confirmar eliminación
        const confirmDeleteBtn = document.getElementById('confirmDeleteResultBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function() {
                const partidaId = document.getElementById('deleteResultId').value;
                eliminarPartida(partidaId);
            });
        }

        // Delegación: abrir modal desde icono papelera
        const tableBody = document.querySelector('tbody');
        if (tableBody) {
            tableBody.addEventListener('click', function(e) {
                const button = e.target.closest('.delete-partida-btn');
                if (button) {
                    e.preventDefault();
                    const partidaId = button.getAttribute('data-id');
                    mostrarModalEliminar(partidaId);
                }
            });
        }

        // Búsqueda y filtros
        const searchForm = document.querySelector('form[action=""]');
        const searchInput = document.getElementById('searchUsuario');
        const juegoSelect = document.getElementById('seleccionarJuego');
        const completadoSelect = document.getElementById('seleccionarCompletado');
        let searchTimeout;
        if (searchInput && searchForm) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        searchForm.submit();
                    }
                }, 500);
            });
        }
        if (juegoSelect && searchForm) {
            juegoSelect.addEventListener('change', function() { searchForm.submit(); });
        }
        if (completadoSelect && searchForm) {
            completadoSelect.addEventListener('change', function() { searchForm.submit(); });
        }
    });

    function mostrarModalEliminar(partidaId) {
        const idField = document.getElementById('deleteResultId');
        if (idField) idField.value = partidaId;
        if (confirmDeleteModal) confirmDeleteModal.show();
    }

    function eliminarPartida(partidaId) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id_resultado', partidaId);
        formData.append('ajax', '1');

        fetch('./controller/partidasAdmin-controller.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (confirmDeleteModal) confirmDeleteModal.hide();
                    mostrarAlerta('success', data.message || 'Partida ondo ezabatu da');
                    setTimeout(() => { window.location.reload(); }, 1000);
                } else {
                    mostrarAlerta('danger', data.message || 'Errorea partida ezabatzean');
                }
            })
            .catch(err => {
                console.error('Error:', err);
                mostrarAlerta('danger', 'Errorea zerbitzariarekin konexioa egitean');
            });
    }

    function mostrarAlerta(tipo, mensaje) {
        const alertHtml = `
            <div class="alert alert-${tipo} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999;" role="alert">
                <i class="fa fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        const alerts = document.querySelectorAll('.alert-dismissible');
        const lastAlert = alerts[alerts.length - 1];
        setTimeout(() => {
            if (lastAlert && lastAlert.parentNode) { new bootstrap.Alert(lastAlert).close(); }
        }, 5000);
    }

    function cleanModalState() {
        // Limpieza defensiva por si el backdrop quedara: elimina backdrops y restablece el body
        try {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('paddingRight');
            document.body.style.removeProperty('overflow');
        } catch (e) { /* noop */ }
    }

    // Exponer para depuración manual si hiciera falta
    window.mostrarModalEliminar = mostrarModalEliminar;
})();