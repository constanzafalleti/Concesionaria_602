<?php
class Vendedores {
    private $id_vendedor;
    private $dni;
    private $nombre;
    private $apellido;
    private $direccion;
    private $telefono;
    private $correo;

    // GET Y SET (Observadores y Modificadores)
    public function getid_vendedor() {
        return $this->id_vendedor;
    }
    public function setid_vendedor($id_vendedor) {
        $this->id_vendedor = $id_vendedor;
    }

    public function getdni() {
        return $this->dni;
    }
    public function setdni($dni) {
        $this->dni = $dni;
    }

    public function getnombre() {
        return $this->nombre;
    }
    public function setnombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getapellido() {
        return $this->apellido;
    }
    public function setapellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getdireccion() {
        return $this->direccion;
    }
    public function setdireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function gettelefono() {
        return $this->telefono;
    }
    public function settelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getcorreo() {
        return $this->correo;
    }
    public function setcorreo($correo) {
        $this->correo = $correo;
    }
}
?>
