<?php
require_once __DIR__ . '/../controller/usuariosAdmin-controller.php';
?>
<div class="container bg-gradient-purple text-purple p-4 rounded-3 ">
    
    <form action="">
        <div class="mb-3">
            <label for="searchUser" class="form-label">Usuarioa bilatu</label>
            <input type="text" class="form-control" id="searchUser" placeholder="Ingresa el nombre o correo del usuario">
        </div>
        <div class="mb-3">
            <label for="seleccionarCentro" class="form-label">Zentro hautatu</label>
            <select name="centros" id="seleccionarCentro" class="form-select">
                <option value="default">Default</option>
                <?php mostrarOpcionesCentros(); ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="seleccionarCiclo" class="form-label">Zikloa hautatu</label>
            <select name="centros" id="seleccionarCiclo" class="form-select">
                <option value="default">Default</option>
                <?php mostrarOpcionesCiclos(); ?>
            </select>
        </div>
        <button type="#" class="btn btn-primary align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search pb-1" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
            </svg>    
            Buscar
        </button>
    </form>

</div>

<div class="container mt-4 table-responsive bg-gradient-blue  p-4 rounded-3 ">
    <div class="">
        <table class="table   bg-gradient-blue text-white">
            <thead class="bg-gradient-blue text-blue">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Izena</th>
                    <th scope="col">Apellidoak</th>
                    <th scope="col">Email</th>
                    <th scope="col">Esleitutako zentroa</th>
                    <th scope="col">Zikloa</th>
                    <th scope="col">Puntu Totala</th>
                    <th scope="col">Ekintzak</th>
                </tr>
            </thead>
            <tbody class="bg-gradient-blue text-blue">
                <?php
                    $usuarios = mostrarUsuarios();
                    foreach($usuarios as $usuario){
                        echo '<tr>';
                        echo '<th scope="row">' . htmlspecialchars($usuario['id_usuario']) . '</th>';
                        echo '<td>' . htmlspecialchars($usuario['nombre']) . '</td>'; 
                        echo '<td>' . htmlspecialchars($usuario['apellidos']) . '</td>'; 
                        echo '<td>' . htmlspecialchars($usuario['email']) . '</td>';
                        echo '<td>' . htmlspecialchars($usuario['nombre_centro']) . '</td>';
                        echo '<td>' . htmlspecialchars($usuario['nombre_ciclo']) . '</td>';
                        echo '<td>' . htmlspecialchars($usuario['puntos_totales']) . '</td>';
                        echo '<td>
                                <ul class="action-list">
                                    <li><a href="#" data-tip="edit" class="edit-user" data-id="' . htmlspecialchars($usuario['id_usuario']) . '"><i class="fa fa-edit"></i></a></li>
                                    <li><a href="#" data-tip="delete" data-id="' . htmlspecialchars($usuario['id_usuario']) . '"><i class="fa fa-trash"></i></a></li>
                                </ul>
                            </td>';
                        echo '</tr>';
                    }
                 ?>
                
            </tbody>    
        </table>
    </div>
    <div class="panel-footer text-blue">
        <div class="row">
            <div class="col col-sm-6 col-xs-6">showing <b>5</b> out of <b>25</b> entries</div>
            <div class="col-sm-6 col-xs-6 justify-content-end pe-5 d-flex">
                <ul class="pagination hidden-xs pull-right">
                    <li><a href="#"><</a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#">></a></li>
                </ul>
                <ul class="pagination visible-xs pull-right">
                    <li><a href="#"><</a></li>
                    <li><a href="#">></a></li>
                </ul>
            </div>
        </div>
    </div>
                
</div>

<!-- Modal para editar usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm">
          <input type="hidden" id="editUserId"> 
          <div class="mb-3">
            <label for="editUserName" class="form-label">Izena</label>
            <input type="text" class="form-control" id="editUserName">
          </div>
          <div class="mb-3">
            <label for="editUserName" class="form-label">Apellidoak</label>
            <input type="text" class="form-control" id="editUserSurnames">
          </div>
          <div class="mb-3">
            <label for="editUserEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editUserEmail">
          </div>
          <div class="mb-3">
            <label for="editUserCentro" class="form-label">Esleitutako zentroa</label>
            <input type="text" class="form-control" id="editUserCentro">
          </div>
          <div class="mb-3">
            <label for="editUserCiclo" class="form-label">Zikloa</label>
            <input type="text" class="form-control" id="editUserCiclo">
          </div>
          <div class="mb-3">
            <label for="editUserPuntos" class="form-label">Puntu Totala</label>
            <input type="number" class="form-control" id="editUserPuntos">
          </div>
        </form>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Itxi</button>
          <button type="button" class="btn btn-primary" id="guardarCambios">Gorde</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts: primero Bootstrap, luego tu JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="./js/usuariosAdmin.js"></script>