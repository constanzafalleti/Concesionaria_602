<?php
class Venta {
    private $id_ventas;
    private $fechahora;
    private $montototal;
    private $mediopago;
    private $descripcion;
    private $id_automovil;
    private $id_usuario;

    // GET Y SET
    public function getid_ventas() {
        return $this->id_ventas;
    }
    public function setid_ventas($id_ventas) {
        $this->id_ventas= $id_ventas;
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
