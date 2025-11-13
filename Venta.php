<?php
class Venta {
    private $id_ventas;
    private $fechahora;
    private $montototal;
    private $mediopago;
    private $descripcion;
    private $id_automovil;
    private $id_usuario;
    private $id_vendedor;
    private $nombre;
    private $nombrevendedor;
    private $apellido;

    // GET Y SET
    public function getapellido() {
        return $this->apellido;
    }
    public function setapellido($apellido) {
        $this->apellido= $apellido;
    }
    

    public function getnombrevendedor() {
        return $this->nombrevendedor;
    }
    public function setnombrevendedor($nombrevendedor) {
        $this->nombrevendedor= $nombrevendedor;
    }

    public function getnombre() {
        return $this->nombre;
    }
    public function setnombre($nombre) {
        $this->nombre= $nombre;
    }
    
       public function getid_ventas() {
        return $this->id_ventas;
    }
    public function setid_ventas($id_ventas) {
        $this->id_ventas= $id_ventas;
    }

        public function getid_vendedor() {
        return $this->id_vendedor;
    }
    public function setid_vendedor($id_vendedor) {
        $this->id_vendedor= $id_vendedor;
    }

    public function getfechahora() {
        return $this->fechahora;
    }
    public function setfechahora($fechahora) {
        $this->fechahora = $fechahora;
    }

    public function getmontototal() {
        return $this->montototal;
    }
    public function setmontototal($montototal) {
        $this->montototal = $montototal;
    }

    public function getmediopago() {
        return $this->mediopago;
    }
    public function setmediopago($mediopago) {
        $this->mediopago = $mediopago;
    }

    public function getdescripcion() {
        return $this->descripcion;
    }
    public function setdescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getid_automovil() {
        return $this->id_automovil;
    }
    public function setid_automovil($id_automovil) {
        $this->id_automovil = $id_automovil;
    }

    public function getid_usuario() {
        return $this->id_usuario;
    }
    public function setid_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

   
}
?>
