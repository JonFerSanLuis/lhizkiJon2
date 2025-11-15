<?php

require_once "AccesoBD.php";

class Usuario {
    public $idUsuario;
    public $nombre;
    public $apellidos;
    public $email;
    public $password;
    public $id_rol;
    public $id_centro;
    public $id_ciclo;
    public $puntos_totales; 
    public $fecha_registro;
    public $activo;
    public $ultimo_acceso;
    public $ciclo;

    public function __construct() {
        $this->idUsuario = null;
        $this->nombre = null;
        $this->email = null;
        $this->password = null;
        $this->id_rol = null;
        $this->id_centro = null;
        $this->id_ciclo = null;
        $this->puntos_totales = null;
        $this->fecha_registro = null;
        $this->activo = null;
        $this->ultimo_acceso = null;
        $this->ciclo = null;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getNombre() {
      return $this->nombre;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getIdRol() {
        return $this->id_rol;
    }

    public function getIdCentro() {
        return $this->id_centro;
    }

    public function getIdCiclo() {
        return $this->id_ciclo;
    }

    public function getPuntosTotales() {
        return $this->puntos_totales;
    }

    public function getFechaRegistro() {
        return $this->fecha_registro;
    }

    public function getActivo() {
        return $this->activo;
    }    public function getUltimoAcceso() {
        return $this->ultimo_acceso;
    }

    public function getCiclo() {
        return $this->ciclo;
    }    //------------------- MÉTODO PARA RELLENAR DATOS DESDE BD ------------------//
    public function llenarDatos($datos) {
        $this->idUsuario = $datos['id_usuario'] ?? null;  // Corregido: era 'idUsuario'
        $this->nombre = $datos['nombre'] ?? null;
        $this->apellidos = $datos['apellidos'] ?? null;
        $this->email = $datos['email'] ?? null;
        $this->password = $datos['password'] ?? null;
        $this->id_rol = $datos['id_rol'] ?? null;
        $this->id_centro = $datos['id_centro'] ?? null;
        $this->id_ciclo = $datos['id_ciclo'] ?? null;
        $this->puntos_totales = $datos['puntos_totales'] ?? null;
        $this->fecha_registro = $datos['fecha_registro'] ?? null;
        $this->activo = $datos['activo'] ?? null;
        $this->ultimo_acceso = $datos['ultimo_acceso'] ?? null;
        
        // Obtener el nombre del ciclo automáticamente si tenemos id_ciclo
        if ($this->id_ciclo) {
            $accesoBD = new AccesoBD();
            $this->ciclo = $accesoBD->NombreCiclo($this->id_ciclo);
            $accesoBD->cerrarConexion();
        }
    }

    //------------------- MÉTODO PARA OBTENER EL NOMBRE DEL CICLO ------------------//

     public function getNombreCiclo($id_ciclo) {
        $accesoBD = new AccesoBD();
        $nombreCiclo = $accesoBD->NombreCiclo($id_ciclo);
        $accesoBD->cerrarConexion();
        return $nombreCiclo;    
     }
}
?>
