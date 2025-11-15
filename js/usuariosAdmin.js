document.addEventListener('DOMContentLoaded', function() {

  const editButtons = document.querySelectorAll('.edit-user');
  editButtons.forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();

      const userId = btn.getAttribute('data-id');
      const row = btn.closest('tr');
      const name = row.querySelectorAll('td')[0].textContent;
      const surnames = row.querySelectorAll('td')[1].textContent;
      const email = row.querySelectorAll('td')[2].textContent;
      const centro = row.querySelectorAll('td')[3].textContent;
      const ciclo = row.querySelectorAll('td')[4].textContent;
      const puntos = row.querySelectorAll('td')[5].textContent;
      document.getElementById('editUserId').value = userId;
      document.getElementById('editUserName').value = name;
      document.getElementById('editUserSurnames').value = surnames;
      document.getElementById('editUserEmail').value = email;
      document.getElementById('editUserCentro').value = centro;
      document.getElementById('editUserCiclo').value = ciclo;
      document.getElementById('editUserPuntos').value = puntos;
      var modal = new bootstrap.Modal(document.getElementById('editUserModal'));
      modal.show();
    });
  });

   document.getElementById('guardarCambios').addEventListener('click', function() {
        // Obtener todos los valores del formulario
        const userId = document.getElementById('editUserId').value;
        const nombre = document.getElementById('editUserName').value;
        const apellidos = document.getElementById('editUserSurnames').value;
        const email = document.getElementById('editUserEmail').value;
        const idCentro = document.getElementById('editUserCentro').value;
        const idCiclo = document.getElementById('editUserCiclo').value;
        const puntos = document.getElementById('editUserPuntos').value;
        
        // Validar que todos los campos estén llenos
        if (!nombre || !email || !idCentro || !idCiclo) {
            alert('Mesedez, bete eremu guztiak');
            return;
        }
        
        // Crear objeto FormData para enviar al servidor
        const formData = new FormData();
        formData.append('id_usuario', userId);
        formData.append('nombre', nombre);
        formData.append('apellidos', apellidos);
        formData.append('email', email);
        formData.append('id_centro', idCentro);
        formData.append('id_ciclo', idCiclo);
        formData.append('puntos_totales', puntos);
        
        // Enviar datos al servidor
        fetch('../controller/usuariosAdmin-controller.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log('Respuesta cruda del servidor:', data);
            if (data.success) {
                alert('Erabiltzailea eguneratu da!');
                // Cerrar el modal
                bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                // Recargar la página para ver los cambios
                location.reload();
            } else {
                alert('Errorea: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errorea gertatu da');
        });
    });





  
});
