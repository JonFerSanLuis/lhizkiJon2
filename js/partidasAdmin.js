// partidasAdmin.js - Funcionalidad para la administración de partidas

document.addEventListener('DOMContentLoaded', function() {
    console.log('partidasAdmin.js - DOM loaded');
    
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

    // Funcionalidad de eliminación de partidas
    initializeDeleteFunctionality();
    
    // Elementos del DOM
    const searchForm = document.querySelector('form[action=""]');
    const searchInput = document.getElementById('searchUsuario');
    const juegoSelect = document.getElementById('seleccionarJuego');
    const completadoSelect = document.getElementById('seleccionarCompletado');
    
    // Búsqueda automática al escribir (con delay)
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    searchForm.submit();
                }
            }, 500);
        });
    }
    
    // Auto-submit al cambiar filtros
    if (juegoSelect) {
        juegoSelect.addEventListener('change', function() {
            searchForm.submit();
        });
    }
    
    if (completadoSelect) {
        completadoSelect.addEventListener('change', function() {
            searchForm.submit();
        });
    }
      // Delegación de eventos para botones dinámicos en la tabla
    const tableBody = document.querySelector('tbody');
    if (tableBody) {
        tableBody.addEventListener('click', function(e) {
            // Botones de eliminar
            if (e.target.closest('[data-tip="delete"]')) {
                e.preventDefault();
                const button = e.target.closest('[data-tip="delete"]');
                const partidaId = button.getAttribute('data-id');
                mostrarModalEliminar(partidaId);
            }
        });    }
      
    // Event listener para tecla ESC (simplificado)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('BorrarPartidaModal');
            if (modal && modal.classList.contains('show')) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        }
    });
});

/**
 * Inicializar funcionalidad de eliminación
 */
function initializeDeleteFunctionality() {
    const confirmDeleteBtn = document.getElementById('confirmDeletePartidaBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            const partidaId = document.getElementById('deletePartidaId').value;
            eliminarPartida(partidaId);
        });
    }
}

/**
 * Mostrar modal de confirmación de eliminación
 */
function mostrarModalEliminar(partidaId) {
    const modalElement = document.getElementById('BorrarPartidaModal');
    const modal = new bootstrap.Modal(modalElement);
    
    document.getElementById('deletePartidaId').value = partidaId;
    modal.show();
}

/**
 * Eliminar partida vía AJAX
 */
function eliminarPartida(partidaId) {
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id_resultado', partidaId);
    formData.append('ajax', '1');

    fetch('./controller/partidasAdmin-controller.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar el modal exactamente igual que en centrosAdmin
            const modalElement = document.getElementById('BorrarPartidaModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();
            
            // Mostrar mensaje de éxito
            mostrarAlerta('success', data.message || 'Partida ondo ezabatu da');
            
            // Recargar la página después de un breve delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Mostrar mensaje de error
            mostrarAlerta('danger', data.message || 'Errorea partida ezabatzean');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarAlerta('danger', 'Errorea zerbitzariarekin konexioa egitean');
    });
}

/**
 * Mostrar alerta temporal
 */
function mostrarAlerta(tipo, mensaje) {
    const alertHtml = `
        <div class="alert alert-${tipo} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999;" role="alert">
            <i class="fa fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-ocultar después de 5 segundos
    const alerts = document.querySelectorAll('.alert-dismissible');
    const lastAlert = alerts[alerts.length - 1];
    
    setTimeout(() => {
        if (lastAlert.parentNode) {
            const bsAlert = new bootstrap.Alert(lastAlert);
            bsAlert.close();
        }
    }, 5000);
}

// Exponer funciones globalmente para debugging
window.mostrarModalEliminar = mostrarModalEliminar;