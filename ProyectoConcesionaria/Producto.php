<?php
class Producto {
    private $id_automovil;
    private $nombre;
    private $marca;
    private $precio;
    private $descripcion;
    private $stock;
   

    
    // GET Y SET (Observadores y Modificadores)
    public function getid_automovil() {
        return $this->id_automovil;
    }

    public function setid_automovil($id_automovil) {
        $this->id_automovil= $id_automovil;
    }

    public function getnombre() {
        return $this->nombre;
    }

    public function setnombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getmarca() {
        return $this->marca;
    }

    public function setmarca($marca) {
        $this->marca = $marca;
    }

    public function getprecio() {
        return $this->precio;
    }

    public function setprecio($precio) {
        $this->precio = $precio;
    }

    public function getdescripcion() {
        return $this->descripcion;
    }

    public function setdescripcion($descripcion) {
        $this->correo = $descripcion;
    }

    public function getstock() {
        return $this->stock;
    }

    public function setstock($stock) {
        $this->stock = $stock;
    }

}
?>
