<?php
class Usuario {
    private $idUsuario;
    private $Nombre;
    private $Apellido;
    private $Direccion;
    private $Correo;
    private $Clave;
    private $id_cargo;
    private $tipo; // este viene de la tabla Cargo

    // GETTERS & SETTERS
    public function getidUsuario() {
        return $this->idUsuario;
    }
    public function setidUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getNombre() {
        return $this->Nombre;
    }
    public function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    public function getApellido() {
        return $this->Apellido;
    }
    public function setApellido($Apellido) {
        $this->Apellido = $Apellido;
    }

    public function getDireccion() {
        return $this->Direccion;
    }
    public function setDireccion($Direccion) {
        $this->Direccion = $Direccion;
    }

    public function getCorreo() {
        return $this->Correo;
    }
    public function setCorreo($Correo) {
        $this->Correo = $Correo;
    }

    public function getClave() {
        return $this->Clave;
    }
    public function setClave($Clave) {
        $this->Clave = $Clave;
    }

    public function getid_cargo() {
        return $this->id_cargo;
    }
    public function setid_cargo($id_cargo) {
        $this->id_cargo = $id_cargo;
    }

    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
}
