<?php
require_once 'Conexion.php';
require_once 'Cargo.php';

class CargoModel {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    // Listar todos los cargos
    public function ListarTodos(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("SELECT * FROM cargo");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $cargo = new Cargo();
                $cargo->setid_cargo($r->id_cargo);
                $cargo->settipo($r->tipo);
                $result[] = $cargo;
            }

            return $result;
        } catch (Exception $e) {
            die("Error al listar cargos: " . $e->getMessage());
        }
    }

    // Listar solo cargos restringidos (ejemplo: id >= 2)
    public function ListarRestringidos(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("SELECT * FROM cargo WHERE id_cargo >= 2");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $cargo = new Cargo();
                $cargo->setid_cargo($r->id_cargo);
                $cargo->settipo($r->tipo);
                $result[] = $cargo;
            }

            return $result;
        } catch (Exception $e) {
            die("Error al listar cargos restringidos: " . $e->getMessage());
        }
    }

    // Obtener un cargo por ID (opcional)
    public function Obtener(int $id_cargo): ?Cargo {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM cargo WHERE id_cargo = ?");
            $stm->execute([$id_cargo]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r) {
                $cargo = new Cargo();
                $cargo->setid_cargo($r->id_cargo);
                $cargo->settipo($r->tipo);
                return $cargo;
            }

            return null;
        } catch (Exception $e) {
            die("Error al obtener cargo: " . $e->getMessage());
        }
    }

    // Eliminar un Cargo por ID
    public function Eliminar(int $id_cargo): void {
        try {
            $stm = $this->pdo->prepare("DELETE FROM cargo WHERE id_cargo = ?");
            $stm->execute([$id_cargo]);
        } catch (Exception $e) {
            die("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    // Actualizar un cargo (con o sin clave)
    public function Actualizar(Cargo $data): void {
        try {
            $params = [
                $data->gettipo(),
            ];

            $sql = "UPDATE cargo SET 
                    tipo = ? ";
            $sql .= " WHERE id_cargo = ?";
            $params[] = $data->getid_cargo();

            $this->pdo->prepare($sql)->execute($params);
        } catch (Exception $e) {
            die("Error al actualizar usuario: " . $e->getMessage());
        }
    }


    // Registrar un nuevo usuario
    public function Registrar(Cargo $data): void {
        try {
            $sql = "
                INSERT INTO cargo (tipo) 
                VALUES (?)";
            $this->pdo->prepare($sql)->execute([
                $data->gettipo()
            ]);
        } catch (Exception $e) {
            die("Error al registrar usuario: " . $e->getMessage());
        }
    }

    /*Verificar cargo (retorna true/false)
    public function Verificar(Usuario $data): bool {
        try {
            $sql = "SELECT Clave FROM usuarios WHERE Correo = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$data->getcorreo()]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            return $r && password_verify($data->getclave(), $r->Clave);
        } catch (Exception $e) {
            die("Error al verificar usuario: " . $e->getMessage());
        }
    }
    */

    // Buscar por nombre, apellido o correo
    public function Buscar(string $termino): array {
        try {
            $result = [];
            $sql = "
                SELECT * FROM cargo 
                WHERE tipo LIKE :term";
            $stm = $this->pdo->prepare($sql);
            $like = '%' . $termino . '%';
            $stm->bindParam(':term', $like, PDO::PARAM_STR);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $cargo = new Cargo();
                $cargo->setid_cargo($r->id_cargo);
                $cargo->settipo($r->tipo);
                $result[] = $cargo;
            }

            return $result;
        } catch (Exception $e) {
            die("Error al buscar usuario: " . $e->getMessage());
        }
    }
}
?>
