// centrosAdmin.js - Funcionalidad para la administración de centros educativos

document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos del DOM
    const searchInput = document.getElementById('searchCentro');
    const searchProvincia = document.getElementById('searchProvincia');
    const searchMunicipio = document.getElementById('searchMunicipio');
    const searchButton = document.querySelector('.btn-primary');
    const addCentroButton = document.querySelector('.btn-success');
    
    // Funcionalidad de búsqueda
    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            buscarCentros();
        });
    }    // Búsqueda en tiempo real
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Debounce para evitar demasiadas búsquedas
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(buscarCentros, 300);
        });
    }
    
    // Filtro por provincia
    if (searchProvincia) {
        searchProvincia.addEventListener('change', function() {
            buscarCentros();
        });
    }
    
    // Búsqueda por municipio
    if (searchMunicipio) {
        searchMunicipio.addEventListener('input', function() {
            // Debounce para evitar demasiadas búsquedas
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(buscarCentros, 300);
        });
    }
      // Delegación de eventos para botones dinámicos en la tabla
    const tableBody = document.querySelector('tbody');
    if (tableBody) {
        tableBody.addEventListener('click', function(e) {
            // Botones de editar (detectar por data-bs-target="#editCentroModal")
            if (e.target.closest('[data-bs-target="#editCentroModal"]')) {
                const row = e.target.closest('tr');
                cargarDatosCentro(row);
            }
            
            // Botones de eliminar (detectar por clase btn-danger)
            if (e.target.closest('.btn-danger')) {
                const row = e.target.closest('tr');
                confirmarEliminacion(row);
            }
        });
    }
    
    // Modal de agregar centro
    const addModal = document.getElementById('addCentroModal');
    if (addModal) {
        addModal.addEventListener('shown.bs.modal', function() {
            document.getElementById('addCentroNombre').focus();
        });
    }
    
    // Botón de guardar nuevo centro
    const saveButton = document.querySelector('#addCentroModal .btn-success');
    if (saveButton) {
        saveButton.addEventListener('click', function() {
            guardarNuevoCentro();
        });
    }
    
    // Botón de guardar cambios
    const updateButton = document.querySelector('#editCentroModal .btn-warning');
    if (updateButton) {
        updateButton.addEventListener('click', function() {
            guardarCambiosCentro();
        });
    }
    
    // Funcionalidad para mostrar/ocultar el campo de contraseña en el modal de edición
    const editChangePasswordCheckbox = document.getElementById('editChangePassword');
    if (editChangePasswordCheckbox) {
        editChangePasswordCheckbox.addEventListener('change', function() {
            const passwordField = document.getElementById('editPasswordField');
            if (this.checked) {
                passwordField.style.display = 'block';
            } else {
                passwordField.style.display = 'none';
                document.getElementById('editProfesorPassword').value = '';
            }
        });
    }
});

// Función para buscar centros
function buscarCentros() {
    const searchTerm = document.getElementById('searchCentro').value;
    const searchProvinciaValue = document.getElementById('searchProvincia').value;
    const searchMunicipioValue = document.getElementById('searchMunicipio').value;
    
    
    
    // Aquí iría la llamada AJAX al servidor
    // Por ahora solo mostramos en consola
    filtrarTabla(searchTerm, searchProvinciaValue, searchMunicipioValue);
}

// Función para filtrar la tabla localmente (mientras no hay backend)
function filtrarTabla(searchTerm, searchProvincia, searchMunicipio) {
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const nombre = row.cells[1].textContent.toLowerCase();
        const provincia = row.cells[2].textContent.toLowerCase();
        const municipio = row.cells[3].textContent.toLowerCase();
        
        const matchesNombre = !searchTerm || 
            nombre.includes(searchTerm.toLowerCase());
            
        const matchesProvincia = !searchProvincia || 
            provincia.includes(searchProvincia.toLowerCase());
            
        const matchesMunicipio = !searchMunicipio || 
            municipio.includes(searchMunicipio.toLowerCase());
        
        if (matchesNombre && matchesProvincia && matchesMunicipio) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Función para cargar datos en el modal de edición
function cargarDatosCentro(row) {
    const cells = row.cells;
      // Cargar datos básicos del centro
    document.getElementById('editCentroId').value = cells[0].textContent.trim();
    document.getElementById('editCentroNombre').value = cells[1].textContent.trim();
    document.getElementById('editCentroProvincia').value = cells[2].textContent.trim();
    document.getElementById('editCentroMunicipio').value = cells[3].textContent.trim();
    
    // Cargar email del profesor (ahora la celda 4 contiene el email)
    const profesorEmail = cells[4].textContent.trim();
    document.getElementById('editProfesorEmail').value = profesorEmail;
    
    // Limpiar campos de nombre y apellidos (se llenarían desde la base de datos en una implementación real)
    document.getElementById('editProfesorNombre').value = '';
    document.getElementById('editProfesorApellidos').value = '';
    document.getElementById('editProfesorEspecialidad').value = '';
    document.getElementById('editCentroDireccion').value = '';
    
    // Resetear checkbox de contraseña
    const changePasswordCheckbox = document.getElementById('editChangePassword');
    if (changePasswordCheckbox) {
        changePasswordCheckbox.checked = false;
        const passwordField = document.getElementById('editPasswordField');
        if (passwordField) {
            passwordField.style.display = 'none';
        }
        const passwordInput = document.getElementById('editProfesorPassword');
        if (passwordInput) {
            passwordInput.value = '';
        }
    }   
}

// Función para confirmar eliminación
function confirmarEliminacion(row) {
    const nombreCentro = row.cells[1].textContent;
    
    if (confirm(`¿Estás seguro de que quieres eliminar el centro "${nombreCentro}"?`)) {
        eliminarCentro(row);
    }
}

// Función para eliminar centro
function eliminarCentro(row) {
    const id = row.cells[0].textContent;
    
    
    // Aquí iría la llamada AJAX para eliminar
    // Por ahora solo removemos la fila
    row.remove();
    
    // Mostrar mensaje de éxito
    mostrarMensaje('Centro eliminado correctamente', 'success');
}

// Función para guardar nuevo centro
/*function guardarNuevoCentro() {
    const formData = {
        nombre: document.getElementById('addCentroNombre').value,
        ubicacion: document.getElementById('addCentroUbicacion').value,
        tipo: document.getElementById('addCentroTipo').value,
        telefono: document.getElementById('addCentroTelefono').value,
        direccion: document.getElementById('addCentroDireccion').value,
        profesor: {
            nombre: document.getElementById('addProfesorNombre').value,
            apellidos: document.getElementById('addProfesorApellidos').value,
            email: document.getElementById('addProfesorEmail').value,
            password: document.getElementById('addProfesorPassword').value,
            especialidad: document.getElementById('addProfesorEspecialidad').value
        }
    };
    
    // Validación básica
    if (!formData.nombre || !formData.ubicacion || !formData.tipo || 
        !formData.profesor.nombre || !formData.profesor.apellidos || 
        !formData.profesor.email || !formData.profesor.password) {
        mostrarMensaje('Por favor, completa todos los campos obligatorios', 'error');
        return;
    }
    
    // Validar email
    if (!validarEmail(formData.profesor.email)) {
        mostrarMensaje('Por favor, ingresa un email válido', 'error');
        return;
    }
    
    console.log('Guardando nuevo centro:', formData);
    
    // Aquí iría la llamada AJAX al servidor
    // Simular éxito por ahora
    setTimeout(() => {
        agregarFilaTabla(formData);
        limpiarFormulario('addCentroForm');
        bootstrap.Modal.getInstance(document.getElementById('addCentroModal')).hide();
        mostrarMensaje('Centro y profesor creados correctamente', 'success');
    }, 500);
}*/

// Función para guardar cambios del centro
/*function guardarCambiosCentro() {
    const formData = {
        id: document.getElementById('editCentroId').value,
        nombre: document.getElementById('editCentroNombre').value,
        ubicacion: document.getElementById('editCentroUbicacion').value,
        tipo: document.getElementById('editCentroTipo').value,
        telefono: document.getElementById('editCentroTelefono').value,
        direccion: document.getElementById('editCentroDireccion').value,
        profesor: {
            nombre: document.getElementById('editProfesorNombre').value,
            apellidos: document.getElementById('editProfesorApellidos').value,
            email: document.getElementById('editProfesorEmail').value,
            especialidad: document.getElementById('editProfesorEspecialidad').value
        }
    };
    
    // Validación básica
    if (!formData.nombre || !formData.ubicacion || !formData.tipo || 
        !formData.profesor.nombre || !formData.profesor.apellidos || 
        !formData.profesor.email) {
        mostrarMensaje('Por favor, completa todos los campos obligatorios', 'error');
        return;
    }
    
    // Si se quiere cambiar la contraseña
    const changePassword = document.getElementById('editChangePassword').checked;
    if (changePassword) {
        formData.profesor.password = document.getElementById('editProfesorPassword').value;
        if (!formData.profesor.password) {
            mostrarMensaje('Por favor, ingresa la nueva contraseña', 'error');
            return;
        }
    }
    
    console.log('Guardando cambios del centro:', formData);
    
    // Aquí iría la llamada AJAX al servidor
    // Simular éxito por ahora
    setTimeout(() => {
        actualizarFilaTabla(formData);
        bootstrap.Modal.getInstance(document.getElementById('editCentroModal')).hide();
        mostrarMensaje('Centro actualizado correctamente', 'success');
    }, 500);
}*/

// Función para agregar nueva fila a la tabla
function agregarFilaTabla(data) {
    const tbody = document.querySelector('tbody');
    const newRow = document.createElement('tr');
    
    // Generar ID ficticio
    const newId = tbody.children.length + 1;      newRow.innerHTML = `
        <td>${newId}</td>
        <td>${data.nombre}</td>
        <td>${data.provincia}</td>
        <td>${data.municipio}</td>
        <td>${data.profesor.email}</td>
        <td>
            <button class="btn btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editCentroModal">
                <i class="fa fa-edit"></i>
            </button>
            <button class="btn btn-danger btn-sm">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;
      tbody.appendChild(newRow);
    
    // Los event listeners se manejan automáticamente por delegación de eventos
}

// Función para actualizar fila existente
function actualizarFilaTabla(data) {
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (row.cells[0].textContent === data.id) {
            row.cells[1].textContent = data.nombre;
            row.cells[2].textContent = data.provincia;
            row.cells[3].textContent = data.municipio;
            row.cells[4].textContent = data.profesor.email;
        }
    });
}

// Función para limpiar formulario
function limpiarFormulario(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
    }
}

// Función para mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
    // Crear elemento de alerta
    const alert = document.createElement('div');
    alert.className = `alert alert-${tipo === 'error' ? 'danger' : 'success'} alert-dismissible fade show position-fixed`;
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

// Función para validar email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Función para capitalizar primera letra
function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
