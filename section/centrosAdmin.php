<?php
require_once __DIR__ . '/../controller/usuariosAdmin-controller.php';
?>

<!-- Formulario de búsqueda y filtros -->
<div class="container bg-gradient-purple text-purple p-4 rounded-3">    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-building me-2" viewBox="0 0 16 16">
                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
                </svg>
                Zentro Hezigarriak Kudeatu
            </h4>
        </div>
    </div>      <form action="">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label for="searchCentro" class="form-label">Zentroaren Izena</label>
                <input type="text" class="form-control" id="searchCentro" placeholder="Zentroaren izena">
            </div>
            <div class="col-md-3 mb-3">
                <label for="searchProvincia" class="form-label">Probintzia</label>
                <select id="searchProvincia" class="form-select">
                    <option value="">Guztiak</option>
                    <option value="Araba">Araba</option>
                    <option value="Bizkaia">Bizkaia</option>
                    <option value="Gipuzkoa">Gipuzkoa</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="searchMunicipio" class="form-label">Udalerria</label>
                <input type="text" class="form-control" id="searchMunicipio" placeholder="Udalerria">
            </div>
            <div class="col-md-3 mb-3 d-flex align-items-end gap-2">
                <button type="button" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search me-1" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>    
                    Bilatu
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCentroModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle me-1" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    Gehitu
                </button>
            </div>
        </div>
    </form>
</div>

<div class="container mt-4 table-responsive bg-gradient-blue p-4 rounded-3">
    <div class="">
        <table class="table bg-gradient-blue text-white">
            <thead class="bg-gradient-blue text-blue">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Zentroaren Izena</th>
                    <th scope="col">Probintzia</th>
                    <th scope="col">Udalerria</th>  
                    <th scope="col">Irakasle Arduraduna</th>
                    
                    <th scope="col">Ekintzak</th>
                </tr>
            </thead>
            <tbody class="bg-gradient-blue text-blue">
                <!-- Ejemplo de datos de centros -->                <tr>
                    <td>1</td>
                    <td>Egibide</td>
                    <td>Araba </td>
                    <td>Vitoria-Gasteiz</td>
                    <td>jon.perez@egibide.org</td>
                    
                    <td>
                        <button class="btn  btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editCentroModal">
                           <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>IES Miguel de Unamuno</td>
                    <td>Bizkaia</td>
                    <td>Bilbao</td>
                    
                    <td>maria.garcia@unamuno.edu</td>
                    
                    <td>
                         <button class="btn  btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editCentroModal">
                           <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Politecnico Easo</td>
                    <td>Gipuzkoa</td>
                    <td>Donostia</td>
                    <td>ander.etxeberria@easo.eus</td>
                    
                    <td>
                        <button class="btn  btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editCentroModal">
                           <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>    
        </table>
    </div>
    <div class="panel-footer text-blue">
        <div class="row">
            <div class="col col-sm-6 col-xs-6">showing <b>3</b> out of <b>15</b> entries</div>
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

<!-- Modal para añadir centro -->
<div class="modal fade" id="addCentroModal" tabindex="-1" aria-labelledby="addCentroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCentroModalLabel">Zentro Berria Gehitu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCentroForm">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="addCentroNombre" class="form-label">Zentroaren Izena <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="addCentroNombre" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="addCentroProvincia" class="form-label">Probintzia <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="addCentroProvincia" required>
            </div>
          </div>
          <div class="row">
            
            <div class="col-md-6 mb-3">
              <label for="addCentroMunicipio" class="form-label">Udalerria</label>
              <input type="tel" class="form-control" id="addCentroMunicipio" >
            </div>
          </div>
          <div class="mb-3">
            <label for="addCentroDireccion" class="form-label">Helbidea</label>
            <textarea class="form-control" id="addCentroDireccion" rows="2" placeholder="Zentroaren helbide osoa"></textarea>
          </div>
          
          <hr>
          <h6 class="mb-3">Irakasle Arduradunaren Datuak</h6>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="addProfesorNombre" class="form-label">Irakaslearen Izena <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="addProfesorNombre" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="addProfesorApellidos" class="form-label">Abizenak <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="addProfesorApellidos" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="addProfesorEmail" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="addProfesorEmail" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="addProfesorPassword" class="form-label">Pasahitza <span class="text-danger">*</span></label>
              <input type="password" class="form-control" id="addProfesorPassword" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="addProfesorEspecialidad" class="form-label">Espezialitatea</label>
            <input type="text" class="form-control" id="addProfesorEspecialidad" placeholder="Adib: Informatika, Mekanika...">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Utzi</button>
        <button type="button" class="btn btn-success">Zentroa eta Irakaslea Gorde</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para editar centro -->
<div class="modal fade" id="editCentroModal" tabindex="-1" aria-labelledby="editCentroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCentroModalLabel">Zentroa Editatu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editCentroForm">
          <input type="hidden" id="editCentroId">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="editCentroNombre" class="form-label">Zentroaren Izena <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editCentroNombre" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="editCentroProvincia" class="form-label">Probintzia <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editCentroProvincia" required>
            </div>
          </div>
          <div class="row">
            
            <div class="col-md-6 mb-3">
              <label for="editCentroMunicipio" class="form-label">Udalerria</label>
              <input type="tel" class="form-control" id="editCentroMunicipio" >
            </div>
          </div>
          <div class="mb-3">
            <label for="editCentroDireccion" class="form-label">Helbidea</label>
            <textarea class="form-control" id="editCentroDireccion" rows="2" placeholder="Zentroaren helbide osoa"></textarea>
          </div>
          
          <hr>
          <h6 class="mb-3">Irakasle Arduradunaren Datuak</h6>
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="editProfesorNombre" class="form-label">Irakaslearen Izena <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editProfesorNombre" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="editProfesorApellidos" class="form-label">Abizenak <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="editProfesorApellidos" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="editProfesorEmail" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="editProfesorEmail" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="editProfesorEspecialidad" class="form-label">Espezialitatea</label>
              <input type="text" class="form-control" id="editProfesorEspecialidad" placeholder="Adib: Informatika, Mekanika...">
            </div>
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="editChangePassword">
              <label class="form-check-label" for="editChangePassword">
                Pasahitza aldatu
              </label>
            </div>
          </div>
          <div class="mb-3" id="editPasswordField" style="display: none;">
            <label for="editProfesorPassword" class="form-label">Pasahitz berria</label>
            <input type="password" class="form-control" id="editProfesorPassword">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Utzi</button>
        <button type="button" class="btn btn-warning">Aldaketak Gorde</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="./js/centrosAdmin.js"></script>