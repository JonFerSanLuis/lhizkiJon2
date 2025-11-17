<?php
require_once __DIR__ . '/../controller/centrosAdmin-controller.php';
?>

<!-- Formulario de búsqueda y filtros -->
<div class="container bg-gradient-purple text-purple p-4 rounded-3">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-building me-2" viewBox="0 0 16 16">
                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                    <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
                </svg>
                Zentro Hezigarriak Kudeatu
            </h4>
        </div>
    </div>    <form action="" method="GET">
        <input type="hidden" name="page" value="centrosAdmin">
        
        <div class="row justify-content-center">
            <div class="col-md-3 mb-3">
                <label for="searchCentro" class="form-label">
                    <i class="fas fa-building me-2"></i>Zentroaren Izena
                </label>
                <input type="text" class="form-control" id="searchCentro" name="buscar_centro" 
                       placeholder="Zentroaren izena" 
                       value="<?= htmlspecialchars($_GET['buscar_centro'] ?? '') ?>">
            </div>
            <div class="col-md-3 mb-3">
                <label for="searchProvincia" class="form-label">
                    <i class="fas fa-map-marker-alt me-2"></i>Probintzia
                </label>
                <select id="searchProvincia" name="probintzia" class="form-select">
                    <option value="">Guztiak</option>
                    <option value="Araba" <?= ($_GET['probintzia'] ?? '') === 'Araba' ? 'selected' : '' ?>>Araba</option>
                    <option value="Bizkaia" <?= ($_GET['probintzia'] ?? '') === 'Bizkaia' ? 'selected' : '' ?>>Bizkaia</option>
                    <option value="Gipuzkoa" <?= ($_GET['probintzia'] ?? '') === 'Gipuzkoa' ? 'selected' : '' ?>>Gipuzkoa</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="searchMunicipio" class="form-label">
                    <i class="fas fa-city me-2"></i>Udalerria
                </label>
                <input type="text" class="form-control" id="searchMunicipio" name="udalerria" 
                       placeholder="Udalerria" 
                       value="<?= htmlspecialchars($_GET['udalerria'] ?? '') ?>">
            </div>
        </div>
        
        <div class="d-flex gap-2 justify-content-center mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Bilatu
            </button>
            <a href="?page=centrosAdmin" class="btn btn-secondary">
                <i class="fas fa-undo me-2"></i>Garbitu
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCentroModal">
                <i class="fas fa-plus me-2"></i>Gehitu
            </button>
        </div>
        
        <?php if (!empty($_GET['buscar_centro']) || !empty($_GET['probintzia']) || !empty($_GET['udalerria'])): ?>
            <div class="mt-3 text-center search-results-info">
                <small>
                    Iragazki aktiboak: 
                    <?php if (!empty($_GET['buscar_centro'])): ?>
                        <span class="badge bg-primary">Bilaketa: "<?= htmlspecialchars($_GET['buscar_centro']) ?>"</span>
                    <?php endif; ?>
                    <?php if (!empty($_GET['probintzia'])): ?>
                        <span class="badge bg-info">Probintzia: "<?= htmlspecialchars($_GET['probintzia']) ?>"</span>
                    <?php endif; ?>
                    <?php if (!empty($_GET['udalerria'])): ?>
                        <span class="badge bg-warning">Udalerria: "<?= htmlspecialchars($_GET['udalerria']) ?>"</span>
                    <?php endif; ?>
                </small>
            </div>
        <?php endif; ?>
    </form>
</div>

<div class="container mt-4 table-responsive bg-gradient-blue p-4 rounded-3">    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="text-blue mb-0">
            <i class="fas fa-list me-2"></i>Zentroen Zerrenda
        </h5>
        <span class="badge bg-info">
            <?php echo $totalCentros; ?> zentro aurkituak
        </span>
    </div>
    
    <div class="table-responsive">
        <table class="table bg-gradient-blue text-white table-sm">
            <thead class="bg-gradient-blue text-blue">
                <tr>
                    <th scope="col" class="text-nowrap"><i class="fas fa-hashtag me-1"></i>ID</th>
                    <th scope="col" class="text-nowrap"><i class="fas fa-building me-1"></i>Zentroaren Izena</th>
                    <th scope="col" class="d-none d-md-table-cell"><i class="fas fa-map-marker-alt me-1"></i>Probintzia</th>
                    <th scope="col" class="d-none d-lg-table-cell"><i class="fas fa-city me-1"></i>Udalerria</th>
                    <th scope="col" class="text-nowrap"><i class="fas fa-user-tie me-1"></i>Irakasle Arduraduna</th>
                    <th scope="col" class="text-nowrap"><i class="fas fa-cogs me-1"></i>Ekintzak</th>
                </tr>
            </thead>            <tbody class="bg-gradient-blue text-blue">
                <?php if (empty($centros)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-info-circle me-2"></i>Ez da zentrorik aurkitu
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($centros as $centro): ?>
                    <tr>
                        <td><?= htmlspecialchars($centro['id_centro']) ?></td>
                        <td><?= htmlspecialchars($centro['nombre_centro']) ?></td>
                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($centro['provincia'] ?? '') ?></td>
                        <td class="d-none d-lg-table-cell"><?= htmlspecialchars($centro['municipio'] ?? '') ?></td>
                        <td><?= htmlspecialchars($centro['profesor_email'] ?? 'Irakaslerik ez') ?></td>                        <td>
                            <ul class="action-list">
                                <li>
                                    <a href="#" data-tip="edit" 
                                       onclick="cargarDatosCentro(<?= $centro['id_centro'] ?>); return false;"
                                       data-bs-toggle="modal" data-bs-target="#editCentroModal"
                                       aria-label="Zentroa editatu">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-tip="delete" 
                                       class="delete-centro" 
                                       data-id="<?= $centro['id_centro'] ?>" 
                                       data-name="<?= htmlspecialchars($centro['nombre_centro'], ENT_QUOTES) ?>"
                                       data-bs-toggle="modal" 
                                       data-bs-target="#deleteCentroModal"
                                       aria-label="Zentroa ezabatu">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>    <div class="panel-footer text-blue">
        <div class="row">
            <div class="col col-sm-6 col-xs-6">
                mostrando <b><?= count($centros) ?></b> de <b><?= $totalCentros ?></b> sarrerak
            </div>
            <div class="col-sm-6 col-xs-6 justify-content-end pe-5 d-flex">
                <?php if ($totalCentros > 10): ?>
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
                <?php endif; ?>
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
          </div>          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="addCentroMunicipio" class="form-label">Udalerria</label>
              <input type="text" class="form-control" id="addCentroMunicipio">
            </div>
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
            </div>          </div>
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
          </div>          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="editCentroMunicipio" class="form-label">Udalerria</label>
              <input type="text" class="form-control" id="editCentroMunicipio">
            </div>
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
              <input type="email" class="form-control" id="editProfesorEmail" required>            </div>
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
    </div>  </div>
</div>

<!-- Modal para eliminar centro -->
<div class="modal fade" id="deleteCentroModal" tabindex="-1" aria-labelledby="deleteCentroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteCentroModalLabel">
          <i class="fas fa-exclamation-triangle me-2"></i>Zentroa Ezabatu
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          <h6><i class="fas fa-warning me-2"></i>KONTUZ! Ekintza hau ezin da desegin</h6>
        </div>
        
        <p class="mb-3">
          Ziur zaude <strong id="deleteCentroName" class="text-danger"></strong> zentroa ezabatu nahi duzula?
        </p>
        
        <div class="card">
          <div class="card-body">
            <h6 class="card-title text-warning">
              <i class="fas fa-info-circle me-2"></i>Ondorengo datuak ezabatuko dira:
            </h6>
            <ul class="list-unstyled mb-0">
              <li><i class="fas fa-building text-primary me-2"></i>Zentroaren informazio guztia</li>
              <li><i class="fas fa-users text-info me-2"></i>Zentro horretako erabiltzaile guztiak (irakasleak eta ikasleak)</li>
              <li><i class="fas fa-chart-bar text-warning me-2"></i>Erabiltzaile horien joko-emaitza guztiak</li>
              <li><i class="fas fa-graduation-cap text-success me-2"></i>Zentro horretako ziklo-esleitapenak</li>
            </ul>
          </div>
        </div>
        
        <div class="mt-3">
          <small class="text-muted">
            <i class="fas fa-clock me-1"></i>
            Operazio hau iraun dezake denbora pixka bat datu-kopuru handiaren arabera.
          </small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Utzi
        </button>
        <button type="button" class="btn btn-danger" id="confirmDeleteCentro">
          <i class="fas fa-trash me-2"></i>Bai, Ezabatu
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="../js/centrosAdmin.js"></script>