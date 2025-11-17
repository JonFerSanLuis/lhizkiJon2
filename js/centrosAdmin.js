
/**
 * Gestión de Centros Educativos - JavaScript
 * Funcionalidades: CRUD de centros, modales, validaciones, AJAX
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeCentrosAdmin();
});

/**
 * Inicializa todos los event listeners y configuraciones
 */
function initializeCentrosAdmin() {
    // Event listeners para los modales
    setupModalEventListeners();
    
    // Event listener para mostrar/ocultar campo de contraseña en edición
    setupPasswordToggle();
    
    // Validaciones en tiempo real
    setupFormValidations();
      // Event listeners para eliminación
    setupDeleteEventListeners();
}

/**
 * Configura los event listeners de los modales
 */
function setupModalEventListeners() {
    // Botón para agregar centro
    const btnAddCentro = document.querySelector('[data-bs-target="#addCentroModal"]');
    if (btnAddCentro) {
        btnAddCentro.addEventListener('click', function() {
            clearAddForm();
        });
    }
    
    // Botón guardar en modal agregar
    const btnSaveAdd = document.querySelector('#addCentroModal .btn-success');
    if (btnSaveAdd) {
        btnSaveAdd.addEventListener('click', function() {
            agregarCentro();
        });
    }
    
    // Botón guardar en modal editar
    const btnSaveEdit = document.querySelector('#editCentroModal .btn-warning');
    if (btnSaveEdit) {
        btnSaveEdit.addEventListener('click', function() {
            actualizarCentro();
        });
    }
}

/**
 * Configura el toggle para mostrar/ocultar campo de contraseña
 */
function setupPasswordToggle() {
    const checkboxPassword = document.getElementById('editChangePassword');
    const passwordField = document.getElementById('editPasswordField');
    
    if (checkboxPassword && passwordField) {
        checkboxPassword.addEventListener('change', function() {
            if (this.checked) {
                passwordField.style.display = 'block';
                document.getElementById('editProfesorPassword').required = true;
            } else {
                passwordField.style.display = 'none';
                document.getElementById('editProfesorPassword').required = false;
                document.getElementById('editProfesorPassword').value = '';
            }
        });
    }
}

/**
 * Configura validaciones en tiempo real
 */
function setupFormValidations() {
    // Validación de email en tiempo real
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateEmail(this);
        });
    });
    
    // Validación de campos requeridos
    const requiredInputs = document.querySelectorAll('input[required]');
    requiredInputs.forEach(input => {        input.addEventListener('blur', function() {
            validateRequired(this);
        });
    });
}

/**
 * Configura los event listeners para la eliminación de centros
 */
function setupDeleteEventListeners() {
    // Event listener para los botones de eliminar centro
    document.addEventListener('click', function(event) {
        if (event.target.closest('.delete-centro')) {
            const deleteLink = event.target.closest('.delete-centro');
            const centroId = deleteLink.getAttribute('data-id');
            const centroName = deleteLink.getAttribute('data-name');
            
            // Rellenar el modal con los datos del centro
            document.getElementById('deleteCentroName').textContent = centroName;
            
            // Configurar el botón de confirmación
            const confirmButton = document.getElementById('confirmDeleteCentro');
            confirmButton.onclick = function() {
                eliminarCentro(centroId);
                // Cerrar el modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteCentroModal'));
                modal.hide();
            };
        }
    });
}

/**
 * Valida campo de email
 * @param {HTMLElement} input - Input de email
 */
function validateEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const value = input.value.trim();
    
    if (value && !emailRegex.test(value)) {
        showInputError(input, 'Email formato ez da zuzena');
        return false;
    } else {
        removeInputError(input);
        return true;
    }
}

/**
 * Valida campo requerido
 * @param {HTMLElement} input - Input requerido
 */
function validateRequired(input) {
    if (!input.value.trim()) {
        showInputError(input, 'Eremu hau derrigorrezkoa da');
        return false;
    } else {
        removeInputError(input);
        return true;
    }
}

/**
 * Muestra error en un input
 * @param {HTMLElement} input - Input con error
 * @param {string} message - Mensaje de error
 */
function showInputError(input, message) {
    removeInputError(input); // Limpiar errores previos
    
    input.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
}

/**
 * Remueve error de un input
 * @param {HTMLElement} input - Input
 */
function removeInputError(input) {
    input.classList.remove('is-invalid');
    const errorDiv = input.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

/**
 * Limpia el formulario de agregar centro
 */
function clearAddForm() {
    const form = document.getElementById('addCentroForm');
    if (form) {
        form.reset();
        // Limpiar errores de validación
        const invalidInputs = form.querySelectorAll('.is-invalid');
        invalidInputs.forEach(input => removeInputError(input));
    }
}

/**
 * Carga los datos de un centro para edición
 * @param {number} idCentro - ID del centro a editar
 */
function cargarDatosCentro(idCentro) {
    if (!idCentro) {
        showAlert('Error: ID de centro no válido', 'error');
        return;
    }
    
    showLoader(true);
    
    // Timeout de seguridad para asegurar que el loader se oculte
    const safetyTimeout = setTimeout(() => {
        showLoader(false);
    }, 10000); // 10 segundos
    
    // Determinar la ruta correcta del controlador
    const isInSection = window.location.pathname.includes('section/');
    const baseUrl = isInSection ? '../controller/' : 'controller/';
    const url = `${baseUrl}centrosAdmin-controller.php?action=obtener_centro&id=${idCentro}`;
      fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
            }
            
            return response.text();
        })
        .then(text => {
            // Intentar parsear como JSON
            try {
                const data = JSON.parse(text);
                
                if (data.success) {
                    llenarFormularioEdicion(data.centro);
                    
                    const modalElement = document.getElementById('editCentroModal');
                    
                    if (modalElement) {
                        // Ocultar loader ANTES de abrir el modal
                        showLoader(false);
                        clearTimeout(safetyTimeout);
                        
                        // Pequeño delay para asegurar que el loader se oculte
                        setTimeout(() => {
                            const modal = new bootstrap.Modal(modalElement);
                            modal.show();
                        }, 100);
                    } else {
                        showAlert('Error: No se encontró el modal de edición', 'error');
                    }
                } else {
                    showAlert(data.message || 'Ez da zentroaren daturik aurkitu', 'error');
                }            } catch (parseError) {
                showAlert('Error: Respuesta del servidor no válida', 'error');
            }
        })        .catch(error => {
            showAlert('Errore bat gertatu da zentroaren datuak kargatzean: ' + error.message, 'error');
        })
        .finally(() => {
            clearTimeout(safetyTimeout);
            showLoader(false);
        });
}

/**
 * Llena el formulario de edición con los datos del centro
 * @param {Object} centro - Datos del centro
 */
function llenarFormularioEdicion(centro) {
    // Verificar que el objeto centro existe
    if (!centro) {
        showAlert('Error: No se recibieron datos del centro', 'error');
        return;
    }
    
    // Datos del centro
    const elements = {
        editCentroId: document.getElementById('editCentroId'),
        editCentroNombre: document.getElementById('editCentroNombre'),
        editCentroProvincia: document.getElementById('editCentroProvincia'),
        editCentroMunicipio: document.getElementById('editCentroMunicipio'),
        editProfesorNombre: document.getElementById('editProfesorNombre'),
        editProfesorApellidos: document.getElementById('editProfesorApellidos'),
        editProfesorEmail: document.getElementById('editProfesorEmail'),
        editChangePassword: document.getElementById('editChangePassword'),
        editPasswordField: document.getElementById('editPasswordField'),
        editProfesorPassword: document.getElementById('editProfesorPassword')
    };
    
    // Verificar elementos críticos
    const criticalElements = ['editCentroId', 'editCentroNombre', 'editCentroProvincia'];
    const missingElements = criticalElements.filter(key => !elements[key]);
    
    if (missingElements.length > 0) {
        showAlert('Error: Faltan elementos del formulario: ' + missingElements.join(', '), 'error');
        return;
    }
    
    try {
        // Llenar datos del centro
        if (elements.editCentroId) {
            elements.editCentroId.value = centro.id_centro || '';
        }
        if (elements.editCentroNombre) {
            elements.editCentroNombre.value = centro.nombre_centro || '';
        }
        if (elements.editCentroProvincia) {
            elements.editCentroProvincia.value = centro.provincia || '';
        }
        if (elements.editCentroMunicipio) {
            elements.editCentroMunicipio.value = centro.municipio || '';
        }
        
        // Llenar datos del profesor
        if (elements.editProfesorNombre) {
            elements.editProfesorNombre.value = centro.nombre || '';
        }
        if (elements.editProfesorApellidos) {
            elements.editProfesorApellidos.value = centro.apellidos || '';
        }
        if (elements.editProfesorEmail) {
            elements.editProfesorEmail.value = centro.email || '';
        }
        
        // Reset password field
        if (elements.editChangePassword) elements.editChangePassword.checked = false;
        if (elements.editPasswordField) elements.editPasswordField.style.display = 'none';
        if (elements.editProfesorPassword) {
            elements.editProfesorPassword.value = '';
            elements.editProfesorPassword.required = false;
        }
        
        // Limpiar errores previos
        const form = document.getElementById('editCentroForm');
        if (form) {
            const invalidInputs = form.querySelectorAll('.is-invalid');
            invalidInputs.forEach(input => removeInputError(input));
        }
          } catch (error) {
        showAlert('Error al llenar el formulario: ' + error.message, 'error');
    }
}

/**
 * Agrega un nuevo centro
 */
function agregarCentro() {
    const form = document.getElementById('addCentroForm');
    
    // Validar formulario
    if (!validateForm(form)) {
        return;
    }
    
    // Recopilar datos
    const formData = new FormData();
    formData.append('action', 'agregar_centro');
    formData.append('nombre', document.getElementById('addCentroNombre').value.trim());
    formData.append('provincia', document.getElementById('addCentroProvincia').value.trim());
    formData.append('municipio', document.getElementById('addCentroMunicipio').value.trim());    formData.append('profesor_nombre', document.getElementById('addProfesorNombre').value.trim());
    formData.append('profesor_apellidos', document.getElementById('addProfesorApellidos').value.trim());
    formData.append('profesor_email', document.getElementById('addProfesorEmail').value.trim());
    formData.append('profesor_password', document.getElementById('addProfesorPassword').value);
    
    showLoader(true);
    
    // Determinar la ruta correcta del controlador
    const baseUrl = window.location.pathname.includes('section/') ? '../controller/' : 'controller/';
    const url = `${baseUrl}centrosAdmin-controller.php`;
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Zentroa behar bezala sortu da', 'success');
            
            // Cerrar modal y recargar página
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCentroModal'));
            modal.hide();
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert(data.message || 'Errore bat gertatu da zentroa sortzerakoan', 'error');
        }
    })    .catch(error => {
        showAlert('Errore bat gertatu da zentroa sortzerakoan', 'error');
    })
    .finally(() => {
        showLoader(false);
    });
}

/**
 * Actualiza un centro existente
 */
function actualizarCentro() {
    const form = document.getElementById('editCentroForm');
    
    // Validar formulario
    if (!validateForm(form)) {
        return;
    }
    
    // Recopilar datos
    const formData = new FormData();
    formData.append('action', 'actualizar_centro');
    formData.append('id', document.getElementById('editCentroId').value);
    formData.append('nombre', document.getElementById('editCentroNombre').value.trim());
    formData.append('provincia', document.getElementById('editCentroProvincia').value.trim());
    formData.append('municipio', document.getElementById('editCentroMunicipio').value.trim());
    formData.append('profesor_nombre', document.getElementById('editProfesorNombre').value.trim());
    formData.append('profesor_apellidos', document.getElementById('editProfesorApellidos').value.trim());
    formData.append('profesor_email', document.getElementById('editProfesorEmail').value.trim());
    
    // Solo enviar nueva contraseña si se marcó el checkbox
    if (document.getElementById('editChangePassword').checked) {
        formData.append('nueva_password', document.getElementById('editProfesorPassword').value);
    }
    
    showLoader(true);
    
    // Determinar la ruta correcta del controlador
    const baseUrl = window.location.pathname.includes('section/') ? '../controller/' : 'controller/';
    const url = `${baseUrl}centrosAdmin-controller.php`;
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Zentroa behar bezala eguneratu da', 'success');
            
            // Cerrar modal y recargar página
            const modal = bootstrap.Modal.getInstance(document.getElementById('editCentroModal'));
            modal.hide();
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert(data.message || 'Errore bat gertatu da zentroa eguneratzean', 'error');
        }
    })    .catch(error => {
        showAlert('Errore bat gertatu da zentroa eguneratzean', 'error');
    })
    .finally(() => {
        showLoader(false);
    });
}

/**
 * Elimina un centro
 * @param {number} idCentro - ID del centro a eliminar
 */
function eliminarCentro(idCentro) {
    const formData = new FormData();
    formData.append('action', 'eliminar_centro');
    formData.append('id', idCentro);
    
    showLoader(true);
    
    // Determinar la ruta correcta del controlador
    const baseUrl = window.location.pathname.includes('section/') ? '../controller/' : 'controller/';
    const url = `${baseUrl}centrosAdmin-controller.php`;
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message || 'Zentroa behar bezala ezabatu da', 'success');
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert(data.message || 'Errore bat gertatu da zentroa ezabatzerakoan', 'error');
        }
    })    .catch(error => {
        showAlert('Errore bat gertatu da zentroa ezabatzerakoan', 'error');
    })
    .finally(() => {
        showLoader(false);
    });
}

/**
 * Valida un formulario completo
 * @param {HTMLFormElement} form - Formulario a validar
 * @returns {boolean} - True si el formulario es válido
 */
function validateForm(form) {
    let isValid = true;
    
    // Validar campos requeridos
    const requiredInputs = form.querySelectorAll('input[required]');
    requiredInputs.forEach(input => {
        if (!validateRequired(input)) {
            isValid = false;
        }
    });
    
    // Validar emails
    const emailInputs = form.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        if (input.value && !validateEmail(input)) {
            isValid = false;
        }
    });
    
    // Validaciones específicas
    const password = form.querySelector('input[type="password"]');
    if (password && password.required && password.value.length < 6) {
        showInputError(password, 'Pasahitzak gutxienez 6 karaktere izan behar ditu');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Muestra un mensaje de alerta
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo de alerta (success, error, warning, info)
 */
function showAlert(message, type = 'info') {
    // Crear elemento de alerta
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${getBootstrapAlertType(type)} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    alertDiv.innerHTML = `
        <i class="fas ${getAlertIcon(type)} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Añadir al DOM
    document.body.appendChild(alertDiv);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

/**
 * Convierte tipo de alerta a clase Bootstrap
 * @param {string} type - Tipo de alerta
 * @returns {string} - Clase Bootstrap correspondiente
 */
function getBootstrapAlertType(type) {
    switch (type) {
        case 'success': return 'success';
        case 'error': return 'danger';
        case 'warning': return 'warning';
        case 'info': return 'info';
        default: return 'info';
    }
}

/**
 * Obtiene icono para el tipo de alerta
 * @param {string} type - Tipo de alerta
 * @returns {string} - Clase del icono
 */
function getAlertIcon(type) {
    switch (type) {
        case 'success': return 'fa-check-circle';
        case 'error': return 'fa-exclamation-triangle';
        case 'warning': return 'fa-exclamation-circle';
        case 'info': return 'fa-info-circle';
        default: return 'fa-info-circle';
    }
}

/**
 * Muestra/oculta loader
 * @param {boolean} show - Mostrar o ocultar loader
 */
function showLoader(show) {
    let loader = document.getElementById('globalLoader');
    
    if (show) {
        if (!loader) {
            // Crear loader si no existe
            loader = document.createElement('div');
            loader.id = 'globalLoader';
            loader.className = 'position-fixed d-flex justify-content-center align-items-center';
            loader.style.cssText = `
                top: 0; left: 0; width: 100%; height: 100%; 
                background-color: rgba(0,0,0,0.5); z-index: 1040;
            `;
            loader.innerHTML = `
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Kargatzen...</span>
                </div>
            `;
            document.body.appendChild(loader);
        }
        loader.style.display = 'flex';
    } else {
        if (loader) {
            loader.style.display = 'none';
            // Opcional: remover el loader del DOM después de ocultarlo
            setTimeout(() => {
                if (loader && loader.parentNode) {
                    loader.remove();
                }
            }, 100);
        }
    }
}

/**
 * Inicializa tooltips (si están disponibles)
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tip]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        }
    });
}

// Inicializar tooltips cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
});

// Exponer funciones globales necesarias
window.cargarDatosCentro = cargarDatosCentro;
window.eliminarCentro = eliminarCentro;