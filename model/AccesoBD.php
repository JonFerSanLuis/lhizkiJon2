<?php

class AccesoBD
{
    const RUTA = "localhost";
    const BD = "lhizki";
    const USER = "lhizadmin"; //cambiar si teneis otro usuario en la base de datos o crear este mismo usuario
    const PASS = "adminlhizki2024"; //cambiar si teneis otra contraseña en la base de datos o crear esta misma contraseña

    public $conexion;

    function __construct()
    {
        $this->conectar();
    }

    function conectar()
    {
        $this->conexion = mysqli_connect(self::RUTA, self::USER, self::PASS, self::BD)
            or die("Error al establecer la conexión: " . mysqli_connect_error());
    }

    function cerrarConexion()
    {
        if ($this->conexion) {
            mysqli_close($this->conexion);
            $this->conexion = null;
        }
    }

    function lanzarSQL($SQL)
    {
        $trimmed = ltrim($SQL);
        $tipoSQL = strtoupper(substr($trimmed, 0, 6));

        $result = mysqli_query($this->conexion, $SQL);

        if ($result === false) {
            trigger_error('MySQL error: ' . mysqli_error($this->conexion) . ' — Query: ' . $SQL, E_USER_WARNING);
            return false;
        }

        return $result;
    }    //------------------- FUNCION INICIAR SESION ------------------//
    
    function login( $email, $password ){
        $email = mysqli_real_escape_string($this->conexion, trim($email));
        $password = trim($password);

        // Primero obtener el hash almacenado en la base de datos
        $result = mysqli_query($this->conexion, 'SELECT password FROM usuario WHERE email = "' . $email . '"');
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hash_almacenado = $row['password'];
            
            // Verificar la contraseña usando password_verify
            if (password_verify($password, $hash_almacenado)) {
                // Si la contraseña es correcta, actualizar la fecha de último acceso
                $fecha_actual = date('Y-m-d H:i:s');
                mysqli_query($this->conexion, 'UPDATE usuario SET ultimo_acceso = "' . $fecha_actual . '" WHERE email = "' . $email . '"');
                return true;
            }
        }

        return false;
    }
    
    //------------------- FUNCION CERRAR SESION ------------------//
    function logout(){
        $this->conexion = null;
        session_destroy();
    }

    //------------------- OBTENER NOMBRE USUARIO ------------------//
    function getNombreUsuario( $email ){
        $email = mysqli_real_escape_string($this->conexion, trim($email));
        $result = mysqli_query($this->conexion,'SELECT nombre FROM usuario WHERE email = "' . $email . '"');
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['nombre'];
        }
        
        return null; // Si no se encuentra el usuario
    }    //------------------- RELLENAR OBJETO USUARIO CON DATOS DE BD ------------------//
    function rellenarUsuario($usuario, $email) {
        $email = mysqli_real_escape_string($this->conexion, trim($email));
        $result = mysqli_query($this->conexion, 'SELECT * FROM usuario WHERE email = "' . $email . '"');
        
        if ($result && mysqli_num_rows($result) > 0) {
            $datos = mysqli_fetch_assoc($result);
            $usuario->llenarDatos($datos);
            return true;
        }
        
        return false;
    }

    //------------------- OBTENER LISTAS PARA FOREIGN KEYS ------------------//
    
    /**
     * Obtener todos los roles disponibles
     * @return array - Array de roles con id_rol y nombre_rol
     */
    function obtenerRoles() {
        $result = mysqli_query($this->conexion, 'SELECT id_rol, nombre_rol FROM rol ORDER BY nombre_rol');
        $roles = array();
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $roles[] = $row;
            }
        }
        
        return $roles;
    }
    
    /**
     * Obtener todos los centros educativos disponibles
     * @return array - Array de centros con id_centro y nombre_centro
     */
    function obtenerCentros() {
        $result = mysqli_query($this->conexion, 'SELECT id_centro, nombre_centro FROM centro_educativo ORDER BY nombre_centro');
        $centros = array();
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $centros[] = $row;
            }
        }
        
        return $centros;
    }
    
    /**
     * Obtener todos los ciclos formativos disponibles
     * @return array - Array de ciclos con id_ciclo y nombre_ciclo
     */
    function obtenerCiclos() {
        $result = mysqli_query($this->conexion, 'SELECT id_ciclo, nombre_ciclo, familia_profesional FROM ciclo_formativo ORDER BY nombre_ciclo');
        $ciclos = array();
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ciclos[] = $row;
            }
        }
        
        return $ciclos;
    }
    
    /**
     * Obtener ciclos formativos filtrados por centro
     * @param int $id_centro - ID del centro educativo
     * @return array - Array de ciclos del centro específico
     */
    function obtenerCiclosPorCentro($id_centro) {
        $id_centro = (int)$id_centro;
        $result = mysqli_query($this->conexion, "SELECT id_ciclo, nombre_ciclo, familia_profesional FROM ciclo_formativo WHERE id_centro = $id_centro ORDER BY nombre_ciclo");
        $ciclos = array();
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ciclos[] = $row;
            }
        }
        
        return $ciclos;
    }

    //------------------- MÉTODOS PARA REGISTRO DE USUARIOS ------------------//
        
    /**
     * Verificar si un email ya existe en la base de datos
     * @param string $email - Email a verificar
     * @return bool - true si existe, false si no existe
     */
    function emailExiste($email) {
        $email = mysqli_real_escape_string($this->conexion, trim($email));
        $result = mysqli_query($this->conexion, "SELECT id_usuario FROM usuario WHERE email = '$email'");
        
        return $result && mysqli_num_rows($result) > 0;
    }
    
    /**
     * Verificar si un ID de rol es válido
     * @param int $id_rol - ID del rol a verificar
     * @return bool - true si es válido, false si no
     */
    function rolValido($id_rol) {
        $id_rol = (int)$id_rol;
        $result = mysqli_query($this->conexion, "SELECT id_rol FROM rol WHERE id_rol = $id_rol");
        
        return $result && mysqli_num_rows($result) > 0;
    }
    
    /**
     * Verificar si un ID de centro es válido
     * @param int $id_centro - ID del centro a verificar
     * @return bool - true si es válido, false si no
     */
    function centroValido($id_centro) {
        $id_centro = (int)$id_centro;
        $result = mysqli_query($this->conexion, "SELECT id_centro FROM centro_educativo WHERE id_centro = $id_centro");
        
        return $result && mysqli_num_rows($result) > 0;
    }
    
    /**
     * Verificar si un ID de ciclo es válido y pertenece al centro especificado
     * @param int $id_ciclo - ID del ciclo a verificar
     * @param int $id_centro - ID del centro al que debe pertenecer
     * @return bool - true si es válido, false si no
     */
    function cicloValidoParaCentro($id_ciclo, $id_centro) {
        $id_ciclo = (int)$id_ciclo;
        $id_centro = (int)$id_centro;
        $result = mysqli_query($this->conexion, "SELECT id_ciclo FROM ciclo_formativo WHERE id_ciclo = $id_ciclo AND id_centro = $id_centro");
        
        return $result && mysqli_num_rows($result) > 0;
    }
    
    /**
     * Registrar un nuevo usuario en la base de datos
     * @param array $datosUsuario - Array con los datos del usuario
     * @return array - Array con 'exito' (bool) y 'mensaje' (string)
     */
    function registrarUsuario($datosUsuario) {
        // Escapar datos para evitar SQL injection
        $nombre = mysqli_real_escape_string($this->conexion, trim($datosUsuario['nombre']));
        $apellidos = mysqli_real_escape_string($this->conexion, trim($datosUsuario['apellidos'] ?? ''));
        $email = mysqli_real_escape_string($this->conexion, trim($datosUsuario['email']));
        $password = mysqli_real_escape_string($this->conexion, trim($datosUsuario['password'])); // TODO: Implementar hash
        $id_centro = (int)$datosUsuario['id_centro'];
        $id_ciclo = (int)$datosUsuario['id_ciclo'];
        $fecha_actual = date('Y-m-d H:i:s');
        
        if (!$this->centroValido($id_centro)) {
            return ['exito' => false, 'mensaje' => 'Centro educativo no válido'];
        }
        
        if (!$this->cicloValidoParaCentro($id_ciclo, $id_centro)) {
            return ['exito' => false, 'mensaje' => 'Ciclo formativo no válido para el centro seleccionado'];
        }
        
        if ($this->emailExiste($email)) {
            return ['exito' => false, 'mensaje' => 'El email ya está registrado'];
        }
        
        // Insertar nuevo usuario
        $sql = "INSERT INTO usuario (nombre, apellidos, email, password, id_rol, id_centro, id_ciclo, puntos_totales, fecha_registro, activo) 
                VALUES ('$nombre', '$apellidos', '$email', '$password', 1, $id_centro, $id_ciclo, 0, '$fecha_actual', 1)";
        
        $resultado = mysqli_query($this->conexion, $sql);
        
        if ($resultado) {
            return ['exito' => true, 'mensaje' => 'Usuario registrado correctamente', 'id_usuario' => mysqli_insert_id($this->conexion)];
        } else {
            error_log("Error al registrar usuario: " . mysqli_error($this->conexion));
            return ['exito' => false, 'mensaje' => 'Error interno del servidor'];
        }
    }

    function NombreCiclo($id_ciclo) {
        $id_ciclo = (int)$id_ciclo;
        $result = mysqli_query($this->conexion, "SELECT nombre_ciclo FROM ciclo_formativo WHERE id_ciclo = $id_ciclo");

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['nombre_ciclo'];
        }

        return null; // Si no se encuentra el ciclo
    }

    //funcion para obtener los usuarios para la tablade admin
    function obtenerUsuarios(){
        $result = mysqli_query($this->conexion, "SELECT u.id_usuario, u.nombre, u.apellidos, u.email, u.id_centro, u.id_ciclo, c.nombre_centro, cf.nombre_ciclo, u.puntos_totales 
        FROM usuario u
        LEFT JOIN centro_educativo c ON u.id_centro = c.id_centro
        LEFT JOIN ciclo_formativo cf ON u.id_ciclo = cf.id_ciclo
        ORDER BY u.id_usuario ASC");

        $usuarios = array();

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $usuarios[] = $row;
            }
        }

        return $usuarios;
    }

    function editarUsuario($id_usuario, $nombre, $apellidos, $email, $id_centro, $id_ciclo, $puntos_totales) {
        $id_usuario = (int)$id_usuario;
        $nombre = mysqli_real_escape_string($this->conexion, trim($nombre));
        $apellidos = mysqli_real_escape_string($this->conexion, trim($apellidos));
        $email = mysqli_real_escape_string($this->conexion, trim($email));
        $id_centro = (int)$id_centro;
        $id_ciclo = (int)$id_ciclo;
        $puntos_totales = (int)$puntos_totales;

        $sql = "UPDATE usuario 
                SET nombre = '$nombre', 
                    apellidos = '$apellidos', 
                    email = '$email', 
                    id_centro = $id_centro, 
                    id_ciclo = $id_ciclo, 
                    puntos_totales = $puntos_totales 
                WHERE id_usuario = $id_usuario";

        $resultado = mysqli_query($this->conexion, $sql);

        return $resultado !== false;
    }

    function eliminarUsuario($id_usuario) {
        $id_usuario = (int)$id_usuario;

        $sql = "DELETE FROM usuario WHERE id_usuario = $id_usuario";

        $resultado = mysqli_query($this->conexion, $sql);

        return $resultado !== false;
    }

    
   
        //------------------- MÉTODOS PARA OBTENER EL HISTORIAL DE PARTIDAS DE UN USUARIO------------------//
    public function obtenerHistorialPartidas($idUsuario, $limite = 15) {
        //Preparo la consulta para sacar los resultados del usuario, junto con el título del juego
        $idUsuario = (int)$idUsuario;
        $limite = (int)$limite;
        $sql = "SELECT r.*, j.titulo FROM resultado r 
        LEFT JOIN juego j ON r.id_juego = j.id_juego 
        WHERE r.id_usuario = $idUsuario 
        ORDER BY r.fecha_realizacion 
        DESC LIMIT $limite";

        $result = $this->lanzarSQL($sql);
        $historial = array();
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $historial[] = $row;
            }
        }
        return $historial; //devuelvo el array (puede estar vacío)
    }




    //------------------- MÉTODOS PARA RANKING ------------------//

    //hago  el metodo de obtenerRankingCiclos: 
    //sumo los puntos de los alumnos por cada ciclo, cuento cuántos alumnos hay y los ordeno por puntos (de mayor a menor)
    function obtenerRankingCiclos() {
        $sql = "SELECT
                    c.nombre_ciclo,
                    SUM(u.puntos_totales) as total_puntos,
                    COUNT(u.id_usuario) as num_alumnos
                FROM ciclo_formativo c
                LEFT JOIN usuario u ON c.id_ciclo = u.id_ciclo
                GROUP BY c.id_ciclo, c.nombre_ciclo
                ORDER BY total_puntos DESC";

        $result = mysqli_query($this->conexion, $sql);
        $ranking = array();

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ranking[] = $row;
            }
        }

        return $ranking;
    }

    //hago  el metodo de obtenerRankingAlumnos: 
    //obtengo la lista de alumnos con sus puntos y el ciclo al que pertenecen, ordenando por puntos (de más a menos)
    function obtenerRankingAlumnos() {
        $sql = "SELECT
                    u.nombre,
                    u.apellidos,
                    u.puntos_totales,
                    c.nombre_ciclo
                FROM usuario u
                LEFT JOIN ciclo_formativo c ON u.id_ciclo = c.id_ciclo
                WHERE u.id_rol = 1
                ORDER BY u.puntos_totales DESC";

        $result = mysqli_query($this->conexion, $sql);
        $ranking = array();

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ranking[] = $row;
            }
        }

        return $ranking;
    }

    //hago  el metodo de obtenerEstadisticasRanking: 
    // devuelvo estadísticas simples para el ranking (total de ciclos y total de alumnos)
    function obtenerEstadisticasRanking() {
        $stats = array(
            'total_clases' => 0,
            'total_alumnos' => 0
        );

        $result = mysqli_query($this->conexion, "SELECT COUNT(*) as total FROM ciclo_formativo");
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $stats['total_clases'] = $row['total'];
        }

        $result = mysqli_query($this->conexion, "SELECT COUNT(*) as total FROM usuario WHERE id_rol = 1");
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $stats['total_alumnos'] = $row['total'];
        }

        return $stats;
    }


    function obtenerAlumnosPorCentro($id_centro) {
        $alumnos = [];
        $id_centro = (int)$id_centro;
        $sql = "SELECT nombre, apellidos, email FROM usuario WHERE id_rol = 1 AND id_centro = $id_centro";
        $result = mysqli_query($this->conexion, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $alumnos[] = $row;
            }
        }
        return $alumnos;
    }
    
    /**
     * Devuelve estadísticas para el dashboard del profesor de un centro
     * @param int $id_centro
     * @return array
     */
    function obtenerStatsCentro($id_centro) {
        $id_centro = (int)$id_centro;
        $stats = [
            'num_alumnos' => 0,
            'porcentaje_participacion' => 0,
            'media_participacion' => 0,
            'num_completados' => 0
        ];
        //numero de alumnos
        $sql_alumnos = "SELECT COUNT(*) as total FROM usuario WHERE id_rol = 1 AND id_centro = $id_centro";
        $res_alumnos = $this->lanzarSQL($sql_alumnos);
        if ($res_alumnos && mysqli_num_rows($res_alumnos) > 0) {
            $row = mysqli_fetch_assoc($res_alumnos);
            $stats['num_alumnos'] = $row['total'];
        }
        //porcentaje de participacion dependiendo del numero de alumnos que han jugado al menos una vez
        $sql_part = "SELECT COUNT(DISTINCT r.id_usuario) as participantes FROM resultado r JOIN usuario u ON r.id_usuario = u.id_usuario WHERE u.id_rol = 1 AND u.id_centro = $id_centro";
        $res_part = $this->lanzarSQL($sql_part);
        if ($res_part && mysqli_num_rows($res_part) > 0) {
            $row = mysqli_fetch_assoc($res_part);
            $stats['porcentaje_participacion'] = $stats['num_alumnos'] > 0 ? round(($row['participantes'] / $stats['num_alumnos']) * 100) : 0;
        }
        //media de participación dependiendo del número de juegos jugados por usuario
        $sql_media = "SELECT AVG(num_juegos) as media FROM (SELECT COUNT(*) as num_juegos FROM resultado r JOIN usuario u ON r.id_usuario = u.id_usuario WHERE u.id_rol = 1 AND u.id_centro = $id_centro GROUP BY r.id_usuario) as sub";
        $res_media = $this->lanzarSQL($sql_media);
        if ($res_media && mysqli_num_rows($res_media) > 0) {
            $row = mysqli_fetch_assoc($res_media);
            $stats['media_participacion'] = $row['media'] ? round($row['media']) : 0;
        }
        //numero de juegos completados
        $sql_completados = "SELECT COUNT(*) as completados FROM resultado r JOIN usuario u ON r.id_usuario = u.id_usuario WHERE r.completado = 1 AND u.id_rol = 1 AND u.id_centro = $id_centro";
        $res_completados = $this->lanzarSQL($sql_completados);
        if ($res_completados && mysqli_num_rows($res_completados) > 0) {
            $row = mysqli_fetch_assoc($res_completados);
            $stats['num_completados'] = $row['completados'];
        }
        return $stats;
    }

    
    // funciones para que el profesor active los juegos
    function obtenerTodosLosJuegos() {
        $juegos = array();
        // 1=activo 2=inactivo
        $sql = "SELECT id_juego, titulo, descripcion, id_estado FROM juego";
        
        $result = mysqli_query($this->conexion, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $juegos[] = $row;
            }
        }
        return $juegos;
    }

    function actualizarEstadoJuego($id_juego, $id_estado) {
        $id_juego = (int)$id_juego;
        $id_estado = (int)$id_estado;
        
        $sql = "UPDATE juego SET id_estado = $id_estado WHERE id_juego = $id_juego";
        
        $resultado = mysqli_query($this->conexion, $sql);
        return $resultado !== false;
    }

    function estaJuegoActivo($id_juego) {
        $id_juego = (int)$id_juego;
        
        $sql = "SELECT id_estado FROM juego WHERE id_juego = $id_juego";
        
        $result = mysqli_query($this->conexion, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // El juego está activo solo si id_estado es 1
            return $row['id_estado'] == 1;
        }
        
        // Si no se encuentra el juego o está inactivo, devuelve false
        return false;
    }
}