document.addEventListener('DOMContentLoaded', function() {
  const editModalElement = document.getElementById('editUserModal');
  const deleteModalElement = document.getElementById('deleteUserModal');
  const editForm = document.getElementById('editUserForm');
  const centroSelect = document.getElementById('editUserCentro');
  const cicloSelect = document.getElementById('editUserCiclo');
  const deleteUserName = document.getElementById('deleteUserName');
  const controllerUrl = window.usuariosAdmin && window.usuariosAdmin.controllerUrl ? window.usuariosAdmin.controllerUrl : '../controller/usuariosAdmin-controller.php';
  const editModal = editModalElement && typeof bootstrap !== 'undefined' ? bootstrap.Modal.getOrCreateInstance(editModalElement) : null;
  const deleteModal = deleteModalElement && typeof bootstrap !== 'undefined' ? bootstrap.Modal.getOrCreateInstance(deleteModalElement) : null;
  let deleteTargetId = null;
  let deleteTargetRow = null;
  let editingRow = null;

  // Función para mostrar notificaciones toast
  function showToast(type, message) {
    const toastElement = document.getElementById(type === 'success' ? 'successToast' : 'errorToast');
    const messageElement = document.getElementById(type === 'success' ? 'successMessage' : 'errorMessage');
    
    if (toastElement && messageElement) {
      messageElement.textContent = message;
      const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
      toast.show();
    }
  }

  document.querySelectorAll('.edit-user').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      if (!editModal) {
        return;
      }

      const row = btn.closest('tr');
      if (!row) {
        return;
      }

      editingRow = row;

      const cells = row.querySelectorAll('td');
      document.getElementById('editUserId').value = btn.dataset.id || '';      document.getElementById('editUserName').value = (cells[0]?.textContent || '').trim();
      document.getElementById('editUserSurnames').value = (cells[1]?.textContent || '').trim();
      document.getElementById('editUserEmail').value = (cells[2]?.textContent || '').trim();
      document.getElementById('editUserPuntos').value = row.dataset.puntos ? row.dataset.puntos : (cells[5]?.textContent || '').trim();
      document.getElementById('editUserPassword').value = ''; // Clear password field

      if (centroSelect) {
        setSelectValue(centroSelect, row.dataset.centroId || '');
      }

      if (cicloSelect) {
        setSelectValue(cicloSelect, row.dataset.cicloId || '');
      }

      editModal.show();
    });
  });

  const guardarCambiosBtn = document.getElementById('guardarCambios');
  if (guardarCambiosBtn) {
    guardarCambiosBtn.addEventListener('click', function() {
      if (!editForm) {
        return;
      }      const userId = document.getElementById('editUserId').value;
      const nombre = document.getElementById('editUserName').value.trim();
      const apellidos = document.getElementById('editUserSurnames').value.trim();
      const email = document.getElementById('editUserEmail').value.trim();
      const idCentro = centroSelect ? centroSelect.value : '';
      const idCiclo = cicloSelect ? cicloSelect.value : '';
      const puntos = document.getElementById('editUserPuntos').value.trim();
      const password = document.getElementById('editUserPassword').value.trim();

      if (!nombre || !email || !idCentro || !idCiclo) {
        alert('Mesedez, bete eremu derrigorrezkoak');
        return;
      }

      const formData = new FormData();
      formData.append('action', 'editarUsuario');
      formData.append('id_usuario', userId);
      formData.append('nombre', nombre);
      formData.append('apellidos', apellidos);
      formData.append('email', email);
      formData.append('id_centro', idCentro);
      formData.append('id_ciclo', idCiclo);
      formData.append('puntos_totales', puntos);
      if (password) {
        formData.append('password', password);
      }

      fetch(controllerUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          console.log('Respuesta cruda del servidor:', data);
          if (data.success) {
            if (editModal) {
              editModal.hide();
            }
            if (editingRow) {
              updateRowAfterEdit(editingRow, {
                nombre,
                apellidos,
                email,
                idCentro,
                idCiclo,
                puntos,
                centroNombre: getSelectedOptionText(centroSelect),
                cicloNombre: getSelectedOptionText(cicloSelect)
              });
            }
            showToast('success', 'Erabiltzailea eguneratu da!');
          } else {
            showToast('error', 'Errorea: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showToast('error', 'Errorea gertatu da zerbitzariarekin konexioa egitean');
        });
    });
  }

  document.querySelectorAll('.delete-user').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      if (!deleteModal) {
        return;
      }

      const row = btn.closest('tr');
      const cells = row ? row.querySelectorAll('td') : [];
      const nombre = (cells[0]?.textContent || '').trim();
      const apellidos = (cells[1]?.textContent || '').trim();

      deleteTargetId = btn.dataset.id || null;
      deleteTargetRow = row || null;

      if (deleteUserName) {
        const fullName = (nombre + ' ' + apellidos).trim();
        deleteUserName.textContent = fullName || nombre;
      }

      deleteModal.show();
    });
  });
  const confirmDeleteBtn = document.getElementById('confirmDeleteUser');
  if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
      if (!deleteTargetId) {
        return;
      }

      // Guardar referencias locales antes de que se limpien
      const rowToDelete = deleteTargetRow;
      
      const formData = new FormData();
      formData.append('action', 'eliminarUsuario');
      formData.append('id_usuario', deleteTargetId);

      fetch(controllerUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Ocultar el modal primero
            if (deleteModal) {
              deleteModal.hide();
            }
            // Esperar a que el modal se cierre completamente
            setTimeout(() => {
              // Eliminar la fila de la tabla usando la referencia local
              if (rowToDelete) {
                rowToDelete.remove();
              }
              // Mostrar mensaje de éxito
              showToast('success', 'Erabiltzailea ezabatu da!');
            }, 300);
          } else {
            // Error al eliminar - NO eliminar la fila, solo mostrar error
            if (deleteModal) {
              deleteModal.hide();
            }
            setTimeout(() => {
              showToast('error', 'Errorea ezabatzean: ' + data.message);
            }, 300);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          // En caso de error de red, cerrar modal pero NO eliminar la fila
          if (deleteModal) {
            deleteModal.hide();
          }
          setTimeout(() => {
            showToast('error', 'Errorea gertatu da zerbitzariarekin konexioa egitean');
          }, 300);
        });
    });
  }

  // Función para limpiar el estado del modal de eliminación
  function cleanupDeleteModal() {
    deleteTargetId = null;
    deleteTargetRow = null;
    if (deleteUserName) {
      deleteUserName.textContent = '';
    }
    forceCleanBackdrops();
  }
  if (deleteModalElement) {
    // Evento cuando el modal se oculta completamente
    deleteModalElement.addEventListener('hidden.bs.modal', function() {
      cleanupDeleteModal();
    });

    // Evento cuando se hace clic en cancelar o cerrar
    deleteModalElement.addEventListener('hide.bs.modal', function(e) {
      // Si no es el botón de confirmar, limpiar inmediatamente
      if (e.target === deleteModalElement) {
        cleanupDeleteModal();
      }
    });
  }

  // Agregar event listener específico para el botón cancelar del modal de eliminar
  const deleteDismissButtons = deleteModalElement ? deleteModalElement.querySelectorAll('[data-bs-dismiss="modal"]') : [];
  if (deleteDismissButtons.length) {
    deleteDismissButtons.forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (deleteModal) {
          deleteModal.hide();
        }
        cleanupDeleteModal();
      });
    });
  }
  if (editModalElement) {
    editModalElement.addEventListener('hidden.bs.modal', function() {
      editingRow = null;
      // Limpiar el formulario
      if (editForm) {
        editForm.reset();
      }
      // Limpiar campo de contraseña específicamente
      const passwordField = document.getElementById('editUserPassword');
      if (passwordField) {
        passwordField.value = '';
      }
      forceCleanBackdrops();
    });
  }

  // Agregar event listener específico para el botón cancelar del modal de editar
  const editDismissButtons = editModalElement ? editModalElement.querySelectorAll('[data-bs-dismiss="modal"]') : [];
  if (editDismissButtons.length) {
    editDismissButtons.forEach(function(btn) {
      btn.addEventListener('click', function() {
        if (editModal) {
          editModal.hide();
        }
      });
    });
  }

  function setSelectValue(selectElement, value) {
    if (!selectElement) {
      return;
    }

    const options = Array.from(selectElement.options);
    const match = options.find(option => option.value === value);

    if (match) {
      selectElement.value = value;
    } else if (options.length) {
      selectElement.selectedIndex = 0;
    }
  }

  function getSelectedOptionText(selectElement) {
    if (!selectElement) {
      return '';
    }

    const option = selectElement.options[selectElement.selectedIndex];
    return option ? option.textContent.trim() : '';
  }

  function updateRowAfterEdit(row, data) {
    const cells = row.querySelectorAll('td');
    if (!cells.length) {
      return;
    }

    cells[0].textContent = data.nombre;
    cells[1].textContent = data.apellidos;
    cells[2].textContent = data.email;
    if (data.centroNombre) {
      cells[3].textContent = data.centroNombre;
    }
    if (data.cicloNombre) {
      cells[4].textContent = data.cicloNombre;
    }
    cells[5].textContent = data.puntos;

    row.dataset.centroId = data.idCentro;
    row.dataset.cicloId = data.idCiclo;
    row.dataset.puntos = data.puntos;
  }

  // Función global para limpiar backdrops residuales
  function forceCleanBackdrops() {
    // Eliminar todos los backdrops
    document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
      backdrop.remove();
    });
    
    // Restaurar body
    document.body.classList.remove('modal-open');
    document.body.style.paddingRight = '';
    document.body.style.overflow = '';
  }

  // Event listener global para detectar clics en backdrops
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('modal-backdrop')) {
      forceCleanBackdrops();
    }
  });

  // Event listener para la tecla ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      forceCleanBackdrops();
    }
  });

  document.addEventListener('hidden.bs.modal', function() {
    forceCleanBackdrops();
  });
});
