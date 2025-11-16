document.addEventListener('DOMContentLoaded', function() {
  const editModalElement = document.getElementById('editUserModal');
  const deleteModalElement = document.getElementById('deleteUserModal');
  const editForm = document.getElementById('editUserForm');
  const centroSelect = document.getElementById('editUserCentro');
  const cicloSelect = document.getElementById('editUserCiclo');
  const deleteUserName = document.getElementById('deleteUserName');
  const controllerUrl = window.usuariosAdmin && window.usuariosAdmin.controllerUrl ? window.usuariosAdmin.controllerUrl : '../controller/usuariosAdmin-controller.php';
  const editModal = editModalElement && typeof bootstrap !== 'undefined' ? new bootstrap.Modal(editModalElement) : null;
  const deleteModal = deleteModalElement && typeof bootstrap !== 'undefined' ? new bootstrap.Modal(deleteModalElement) : null;
  let deleteTargetId = null;
  let deleteTargetRow = null;
  let editingRow = null;

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
      document.getElementById('editUserId').value = btn.dataset.id || '';
      document.getElementById('editUserName').value = (cells[0]?.textContent || '').trim();
      document.getElementById('editUserSurnames').value = (cells[1]?.textContent || '').trim();
      document.getElementById('editUserEmail').value = (cells[2]?.textContent || '').trim();
      document.getElementById('editUserPuntos').value = row.dataset.puntos ? row.dataset.puntos : (cells[5]?.textContent || '').trim();

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
      }

      const userId = document.getElementById('editUserId').value;
      const nombre = document.getElementById('editUserName').value.trim();
      const apellidos = document.getElementById('editUserSurnames').value.trim();
      const email = document.getElementById('editUserEmail').value.trim();
      const idCentro = centroSelect ? centroSelect.value : '';
      const idCiclo = cicloSelect ? cicloSelect.value : '';
      const puntos = document.getElementById('editUserPuntos').value.trim();

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

      fetch(controllerUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          console.log('Respuesta cruda del servidor:', data);
          if (data.success) {
            alert('Erabiltzailea eguneratu da!');
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
          } else {
            alert('Errorea: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Errorea gertatu da');
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
            alert('Erabiltzailea ezabatu da!');
            if (deleteModal) {
              deleteModal.hide();
            }
            if (deleteTargetRow) {
              deleteTargetRow.remove();
            }
          } else {
            alert('Errorea: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Errorea gertatu da');
        });
    });
  }

  if (deleteModalElement) {
    deleteModalElement.addEventListener('hidden.bs.modal', function() {
      deleteTargetId = null;
      deleteTargetRow = null;
      if (deleteUserName) {
        deleteUserName.textContent = '';
      }
    });
  }

  if (editModalElement) {
    editModalElement.addEventListener('hidden.bs.modal', function() {
      editingRow = null;
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
});
