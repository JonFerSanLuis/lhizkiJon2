<?php
require_once __DIR__ . '/../model/AccesoBD.php';

class CentrosAdminController {
    private $bd;
    
    public function __construct() {
        $this->bd = new AccesoBD();
    }    public function obtenerCentros($buscar_centro = '', $probintzia = '', $udalerria = '') {
        // Consulta con GROUP BY para evitar duplicados - solo el primer profesor por centro
        $sql = "SELECT 
                    ce.id_centro,
                    ce.nombre_centro,
                    ce.provincia,
                    ce.municipio,
                    MIN(u.email) as profesor_email,
                    CONCAT(IFNULL(MIN(u.nombre), ''), ' ', IFNULL(MIN(u.apellidos), '')) as profesor_nombre
                FROM centro_educativo ce
                LEFT JOIN usuario u ON ce.id_centro = u.id_centro AND u.id_rol = 2 AND u.activo = 1";
        
        // Solo añadir WHERE si hay filtros
        $whereConditions = [];
        
        if (!empty($buscar_centro)) {
            $buscar_centro_safe = mysqli_real_escape_string($this->bd->conexion, $buscar_centro);
            $whereConditions[] = "ce.nombre_centro LIKE '%{$buscar_centro_safe}%'";
        }
        
        if (!empty($probintzia)) {
            $probintzia_safe = mysqli_real_escape_string($this->bd->conexion, $probintzia);
            $whereConditions[] = "ce.provincia = '{$probintzia_safe}'";
        }
        
        if (!empty($udalerria)) {
            $udalerria_safe = mysqli_real_escape_string($this->bd->conexion, $udalerria);
            $whereConditions[] = "ce.municipio LIKE '%{$udalerria_safe}%'";
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        $sql .= " GROUP BY ce.id_centro, ce.nombre_centro, ce.provincia, ce.municipio";
        $sql .= " ORDER BY ce.nombre_centro ASC";
        
        try {
            $result = $this->bd->lanzarSQL($sql);
            
            if (!$result) {
                // Si hay error, devolver array vacío en lugar de fallar
                return [];
            }
            
            $centros = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $centros[] = $row;
            }
            
            return $centros;
        } catch (Exception $e) {
            // En caso de error, devolver array vacío
            return [];
        }
    }
      public function contarCentros($buscar_centro = '', $probintzia = '', $udalerria = '') {
        $sql = "SELECT COUNT(*) as total FROM centro_educativo ce";
        
        // Solo añadir WHERE si hay filtros
        $whereConditions = [];
        
        if (!empty($buscar_centro)) {
            $buscar_centro_safe = mysqli_real_escape_string($this->bd->conexion, $buscar_centro);
            $whereConditions[] = "ce.nombre_centro LIKE '%{$buscar_centro_safe}%'";
        }
        
        if (!empty($probintzia)) {
            $probintzia_safe = mysqli_real_escape_string($this->bd->conexion, $probintzia);
            $whereConditions[] = "ce.provincia = '{$probintzia_safe}'";
        }
        
        if (!empty($udalerria)) {
            $udalerria_safe = mysqli_real_escape_string($this->bd->conexion, $udalerria);
            $whereConditions[] = "ce.municipio LIKE '%{$udalerria_safe}%'";
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        try {
            $result = $this->bd->lanzarSQL($sql);
            
            if (!$result) {
                return 0;
            }
            
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        } catch (Exception $e) {
            return 0;
        }
    }    public function obtenerCentroPorId($id_centro) {
        error_log("=== OBTENER CENTRO POR ID ===");
        error_log("ID recibido: " . $id_centro . " (tipo: " . gettype($id_centro) . ")");
          $sql = "SELECT 
                    ce.id_centro,
                    ce.nombre_centro,
                    ce.provincia,
                    ce.municipio,
                    u.id_usuario,
                    u.nombre,
                    u.apellidos,
                    u.email
                FROM centro_educativo ce
                LEFT JOIN usuario u ON ce.id_centro = u.id_centro AND u.id_rol = 2 AND u.activo = 1
                WHERE ce.id_centro = " . intval($id_centro) . "
                LIMIT 1";
        
        try {
            error_log("SQL Query: " . $sql);
            
            // Verificar estado de la conexión
            if (!$this->bd || !$this->bd->conexion) {
                error_log("ERROR: No hay conexión a la base de datos");
                return false;
            }
            
            error_log("Conexión BD OK, ejecutando query...");
            $result = $this->bd->lanzarSQL($sql);
            
            if (!$result) {
                $error = mysqli_error($this->bd->conexion);
                error_log("Error SQL en obtenerCentroPorId: " . $error);
                return false;
            }
            
            error_log("Query ejecutado correctamente, obteniendo resultado...");
            $centro = mysqli_fetch_assoc($result);
            error_log("Resultado crudo de la consulta: " . json_encode($centro));
            
            // Verificar si se encontró el centro
            if ($centro && !empty($centro['id_centro'])) {
                error_log("✅ Centro obtenido exitosamente para ID: " . $id_centro);
                error_log("Datos del centro: " . json_encode($centro));
                return $centro;
            } else {
                error_log("❌ Centro no encontrado para ID: " . $id_centro);
                
                // Verificar si existe algún centro en la base de datos
                $checkSql = "SELECT COUNT(*) as total FROM centro_educativo";
                $checkResult = $this->bd->lanzarSQL($checkSql);
                $totalCentros = 0;
                if ($checkResult) {
                    $row = mysqli_fetch_assoc($checkResult);
                    $totalCentros = $row['total'];
                }
                error_log("Total de centros en BD: " . $totalCentros);
                
                if ($totalCentros == 0) {
                    error_log("⚠️ No hay centros en la base de datos");
                } else {
                    // Verificar si existe este centro específico
                    $existeSql = "SELECT id_centro FROM centro_educativo WHERE id_centro = " . intval($id_centro);
                    $existeResult = $this->bd->lanzarSQL($existeSql);
                    if ($existeResult) {
                        $existeCentro = mysqli_fetch_assoc($existeResult);
                        if ($existeCentro) {
                            error_log("✅ El centro con ID $id_centro SÍ existe en centro_educativo");
                            
                            // Verificar usuarios
                            $usuariosSql = "SELECT COUNT(*) as total FROM usuario WHERE id_centro = " . intval($id_centro) . " AND id_rol = 2 AND activo = 1";
                            $usuariosResult = $this->bd->lanzarSQL($usuariosSql);
                            if ($usuariosResult) {
                                $usuariosRow = mysqli_fetch_assoc($usuariosResult);
                                error_log("Usuarios encontrados para este centro (rol 2, activos): " . $usuariosRow['total']);
                            }
                        } else {
                            error_log("❌ El centro con ID $id_centro NO existe en centro_educativo");
                        }
                    }
                }
                
                return false;
            }
        } catch (Exception $e) {
            error_log("Excepción en obtenerCentroPorId: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
      public function eliminarCentro($id_centro) {
        try {
            // Verificar si el centro existe
            $sqlCheckCentro = "SELECT COUNT(*) as total FROM centro_educativo WHERE id_centro = " . intval($id_centro);
            $resultCentro = $this->bd->lanzarSQL($sqlCheckCentro);
            $rowCentro = mysqli_fetch_assoc($resultCentro);
            
            if ($rowCentro['total'] == 0) {
                return ['success' => false, 'message' => 'Zentroa ez da aurkitu.'];
            }
              // Contar usuarios asociados al centro para información
            $sqlCountUsers = "SELECT COUNT(*) as total FROM usuario WHERE id_centro = " . intval($id_centro);
            $resultCount = $this->bd->lanzarSQL($sqlCountUsers);
            $rowCount = mysqli_fetch_assoc($resultCount);
            $totalUsuarios = $rowCount['total'];
            
            // Contar resultados asociados para información
            $sqlCountResults = "SELECT COUNT(*) as total FROM resultado r 
                               INNER JOIN usuario u ON r.id_usuario = u.id_usuario 
                               WHERE u.id_centro = " . intval($id_centro);
            $resultCountResults = $this->bd->lanzarSQL($sqlCountResults);
            $rowCountResults = mysqli_fetch_assoc($resultCountResults);
            $totalResultados = $rowCountResults['total'];
            
            error_log("Eliminando centro ID: " . $id_centro . " con " . $totalUsuarios . " usuarios y " . $totalResultados . " resultados asociados");
            
            // Primero eliminar todos los resultados de los usuarios del centro
            if ($totalResultados > 0) {
                $sqlDeleteResults = "DELETE r FROM resultado r 
                                   INNER JOIN usuario u ON r.id_usuario = u.id_usuario 
                                   WHERE u.id_centro = " . intval($id_centro);
                $resultDeleteResults = $this->bd->lanzarSQL($sqlDeleteResults);
                
                if (!$resultDeleteResults) {
                    $error = mysqli_error($this->bd->conexion);
                    error_log("Error al eliminar resultados de usuarios del centro: " . $error);
                    return ['success' => false, 'message' => 'Errore bat gertatu da zentroaren emaitzak ezabatzerakoan: ' . $error];
                }
                
                error_log("✅ " . $totalResultados . " resultados eliminados del centro ID: " . $id_centro);
            }
            
            // Luego eliminar todos los usuarios asociados al centro
            if ($totalUsuarios > 0) {
                $sqlDeleteUsers = "DELETE FROM usuario WHERE id_centro = " . intval($id_centro);
                $resultDeleteUsers = $this->bd->lanzarSQL($sqlDeleteUsers);
                
                if (!$resultDeleteUsers) {
                    $error = mysqli_error($this->bd->conexion);
                    error_log("Error al eliminar usuarios del centro: " . $error);
                    return ['success' => false, 'message' => 'Errore bat gertatu da zentroaren erabiltzaileak ezabatzerakoan: ' . $error];
                }
                
                error_log("✅ " . $totalUsuarios . " usuarios eliminados del centro ID: " . $id_centro);
            }
            
            // Luego eliminar el centro
            $sqlDeleteCentro = "DELETE FROM centro_educativo WHERE id_centro = " . intval($id_centro);
            $resultDeleteCentro = $this->bd->lanzarSQL($sqlDeleteCentro);
              if ($resultDeleteCentro) {
                error_log("✅ Centro eliminado exitosamente ID: " . $id_centro);
                
                // Construir mensaje informativo
                $mensaje = "Zentroa behar bezala ezabatu da.";
                if ($totalUsuarios > 0 || $totalResultados > 0) {
                    $parts = [];
                    if ($totalUsuarios > 0) {
                        $parts[] = "{$totalUsuarios} erabiltzaile";
                    }
                    if ($totalResultados > 0) {
                        $parts[] = "{$totalResultados} emaitza";
                    }
                    $mensaje = "Zentroa eta " . implode(', ', $parts) . " behar bezala ezabatu dira.";
                }
                
                return ['success' => true, 'message' => $mensaje];
            } else {
                $error = mysqli_error($this->bd->conexion);
                error_log("Error al eliminar centro: " . $error);
                return ['success' => false, 'message' => 'Errore bat gertatu da zentroa ezabatzerakoan: ' . $error];
            }
        } catch (Exception $e) {
            error_log("Excepción en eliminarCentro: " . $e->getMessage());
            return ['success' => false, 'message' => 'Errore bat gertatu da: ' . $e->getMessage()];
        }
    }
    
    public function actualizarCentro($id_centro, $nombre, $provincia, $municipio, $profesor_nombre, $profesor_apellidos, $profesor_email, $nueva_password = '') {
        try {
            // Actualizar datos del centro
            $nombre_safe = mysqli_real_escape_string($this->bd->conexion, $nombre);
            $provincia_safe = mysqli_real_escape_string($this->bd->conexion, $provincia);
            $municipio_safe = mysqli_real_escape_string($this->bd->conexion, $municipio);
            
            $sqlCentro = "UPDATE centro_educativo SET 
                         nombre_centro = '{$nombre_safe}',
                         provincia = '{$provincia_safe}',
                         municipio = '{$municipio_safe}'
                         WHERE id_centro = " . intval($id_centro);
            
            $result1 = $this->bd->lanzarSQL($sqlCentro);
            
            // Actualizar datos del profesor si existe
            if (!empty($profesor_email)) {
                $profesor_nombre_safe = mysqli_real_escape_string($this->bd->conexion, $profesor_nombre);
                $profesor_apellidos_safe = mysqli_real_escape_string($this->bd->conexion, $profesor_apellidos);
                $profesor_email_safe = mysqli_real_escape_string($this->bd->conexion, $profesor_email);
                
                $sqlProfesor = "UPDATE usuario SET 
                               nombre = '{$profesor_nombre_safe}',
                               apellidos = '{$profesor_apellidos_safe}',
                               email = '{$profesor_email_safe}'";
                
                if (!empty($nueva_password)) {
                    $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
                    $sqlProfesor .= ", password = '{$password_hash}'";
                }
                
                $sqlProfesor .= " WHERE id_centro = " . intval($id_centro) . " AND id_rol = 2 AND activo = 1 LIMIT 1";
                
                $result2 = $this->bd->lanzarSQL($sqlProfesor);
            }
            
            return ['success' => true, 'message' => 'Zentroa behar bezala eguneratu da.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Errore bat gertatu da: ' . $e->getMessage()];
        }
    }
    
    public function agregarCentro($nombre, $provincia, $municipio, $profesor_nombre, $profesor_apellidos, $profesor_email, $profesor_password) {
        try {
            // Verificar si ya existe un centro con ese nombre
            $nombre_safe = mysqli_real_escape_string($this->bd->conexion, $nombre);
            $sqlCheck = "SELECT COUNT(*) as total FROM centro_educativo WHERE nombre_centro = '{$nombre_safe}'";
            $result = $this->bd->lanzarSQL($sqlCheck);
            $row = mysqli_fetch_assoc($result);
              if ($row['total'] > 0) {
                return ['success' => false, 'message' => 'Izen horrekin zentro bat dago jadanik.'];
            }
            
            // Verificar si ya existe un usuario con ese email
            $profesor_email_safe_check = mysqli_real_escape_string($this->bd->conexion, $profesor_email);
            $sqlCheckEmail = "SELECT COUNT(*) as total FROM usuario WHERE email = '{$profesor_email_safe_check}'";
            $resultEmail = $this->bd->lanzarSQL($sqlCheckEmail);
            $rowEmail = mysqli_fetch_assoc($resultEmail);
            
            if ($rowEmail['total'] > 0) {
                return ['success' => false, 'message' => 'Email horrekin erabiltzaile bat dago jadanik.'];
            }
            
            // Insertar centro
            $provincia_safe = mysqli_real_escape_string($this->bd->conexion, $provincia);
            $municipio_safe = mysqli_real_escape_string($this->bd->conexion, $municipio);
            
            $sqlCentro = "INSERT INTO centro_educativo (nombre_centro, provincia, municipio, fecha_alta) 
                         VALUES ('{$nombre_safe}', '{$provincia_safe}', '{$municipio_safe}', NOW())";
            
            $result1 = $this->bd->lanzarSQL($sqlCentro);
            
            if (!$result1) {
                return ['success' => false, 'message' => 'Errore bat gertatu da zentroa sortzerakoan.'];
            }
            
            // Obtener el ID del centro recién creado
            $id_centro = mysqli_insert_id($this->bd->conexion);
              // Insertar profesor
            $profesor_nombre_safe = mysqli_real_escape_string($this->bd->conexion, $profesor_nombre);
            $profesor_apellidos_safe = mysqli_real_escape_string($this->bd->conexion, $profesor_apellidos);
            $profesor_email_safe = mysqli_real_escape_string($this->bd->conexion, $profesor_email);
            $password_hash = password_hash($profesor_password, PASSWORD_DEFAULT);
            
            $sqlProfesor = "INSERT INTO usuario (nombre, apellidos, email, password, id_rol, id_centro, puntos_totales, activo, fecha_registro) 
                           VALUES ('{$profesor_nombre_safe}', '{$profesor_apellidos_safe}', '{$profesor_email_safe}', 
                                  '{$password_hash}', 2, {$id_centro}, 0, 1, NOW())";
              $result2 = $this->bd->lanzarSQL($sqlProfesor);
            
            if (!$result2) {
                // Log del error SQL para debugging
                $error = mysqli_error($this->bd->conexion);
                error_log("Error al insertar profesor: " . $error);
                error_log("SQL que falló: " . $sqlProfesor);
                
                // Si falla la creación del profesor, eliminar el centro
                $this->bd->lanzarSQL("DELETE FROM centro_educativo WHERE id_centro = {$id_centro}");                return ['success' => false, 'message' => 'Errore bat gertatu da irakaslea sortzerakoan: ' . $error];
            }
            
            // Log de éxito
            error_log("✅ Centro y profesor creados exitosamente. Centro ID: " . $id_centro);
            
            return ['success' => true, 'message' => 'Zentroa eta irakaslea behar bezala sortu dira.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Errore bat gertatu da: ' . $e->getMessage()];
        }
    }
    
    public function __destruct() {
        if ($this->bd) {
            $this->bd->cerrarConexion();
        }
    }
}

// Usar el controller
try {
    $centrosController = new CentrosAdminController();
    
    // Obtener parámetros de filtro
    $buscar_centro = $_GET['buscar_centro'] ?? '';
    $probintzia = $_GET['probintzia'] ?? '';
    $udalerria = $_GET['udalerria'] ?? '';
    
    // Obtener datos
    $centros = $centrosController->obtenerCentros($buscar_centro, $probintzia, $udalerria);
    $totalCentros = $centrosController->contarCentros($buscar_centro, $probintzia, $udalerria);
    
} catch (Exception $e) {
    // En caso de error, usar datos vacíos
    $centros = [];
    $totalCentros = 0;
}

// Manejar peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['action'])) {
    // Solo procesar AJAX, no la carga normal de la página
    $action = $_POST['action'] ?? $_GET['action'] ?? '';
    
    if (!empty($action)) {
        header('Content-Type: application/json');
        
        // Usar la misma instancia del controller para AJAX
        if (!isset($centrosController)) {
            $centrosController = new CentrosAdminController();
        }
        
        switch ($action) {
            case 'obtener_centro':
                $id_centro = intval($_GET['id'] ?? 0);
                error_log("Recibida petición obtener_centro con ID: " . $id_centro);
                
                if ($id_centro > 0) {
                    $centro = $centrosController->obtenerCentroPorId($id_centro);
                    if ($centro) {
                        error_log("Centro encontrado y devuelto exitosamente");
                        echo json_encode(['success' => true, 'centro' => $centro]);
                    } else {
                        error_log("Centro no encontrado para ID: " . $id_centro);
                        echo json_encode(['success' => false, 'message' => 'Ez da zentroa aurkitu.']);
                    }
                } else {
                    error_log("ID de centro inválido: " . $id_centro);
                    echo json_encode(['success' => false, 'message' => 'ID baliogabea.']);
                }
                exit;
                
            case 'eliminar_centro':
                $id_centro = intval($_POST['id'] ?? 0);
                if ($id_centro > 0) {
                    $resultado = $centrosController->eliminarCentro($id_centro);
                    echo json_encode($resultado);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ID baliogabea.']);
                }
                exit;
                
            case 'agregar_centro':
                $nombre = $_POST['nombre'] ?? '';
                $provincia = $_POST['provincia'] ?? '';
                $municipio = $_POST['municipio'] ?? '';
                $profesor_nombre = $_POST['profesor_nombre'] ?? '';
                $profesor_apellidos = $_POST['profesor_apellidos'] ?? '';                $profesor_email = $_POST['profesor_email'] ?? '';
                $profesor_password = $_POST['profesor_password'] ?? '';
                
                if (!empty($nombre) && !empty($provincia) && !empty($profesor_nombre) && 
                    !empty($profesor_apellidos) && !empty($profesor_email) && !empty($profesor_password)) {
                      $resultado = $centrosController->agregarCentro(
                        $nombre, $provincia, $municipio, 
                        $profesor_nombre, $profesor_apellidos, $profesor_email, 
                        $profesor_password
                    );
                    echo json_encode($resultado);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Derrigorrezko eremuak falta dira.']);
                }
                exit;
                
            case 'actualizar_centro':
                $id_centro = intval($_POST['id'] ?? 0);
                $nombre = $_POST['nombre'] ?? '';
                $provincia = $_POST['provincia'] ?? '';
                $municipio = $_POST['municipio'] ?? '';
                $profesor_nombre = $_POST['profesor_nombre'] ?? '';
                $profesor_apellidos = $_POST['profesor_apellidos'] ?? '';
                $profesor_email = $_POST['profesor_email'] ?? '';
                $nueva_password = $_POST['nueva_password'] ?? '';
                  if ($id_centro > 0 && !empty($nombre) && !empty($provincia) && 
                    !empty($profesor_nombre) && !empty($profesor_apellidos) && !empty($profesor_email)) {
                    
                    $resultado = $centrosController->actualizarCentro(
                        $id_centro, $nombre, $provincia, $municipio, 
                        $profesor_nombre, $profesor_apellidos, $profesor_email, $nueva_password
                    );
                    echo json_encode($resultado);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Derrigorrezko eremuak falta dira.']);
                }
                exit;
        }
    }
}
?>
