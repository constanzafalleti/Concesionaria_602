<?php
class Cargo {
    private $id_cargo;
    private $tipo;

    // GET Y SET (Observadores y Modificadores)
    public function getid_cargo() {
        return $this->id_cargo;
    }

    public function setid_cargo($id_cargo) {
        $this->id_cargo = $id_cargo;
    }

    public function gettipo() {
        return $this->tipo;
    }

    public function settipo($tipo) {
        $this->tipo = $tipo;
    }
}
?>
