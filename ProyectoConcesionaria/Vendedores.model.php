<?php
include_once("Conexion.php");
include_once("Vendedores.php");
include_once("Cargo.php");
include_once("Cargo.model.php");


class VendedoresModel {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    // Listar todos los usuarios con su cargo
    public function Listar(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("SELECT * FROM vendedores ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $vendedores = new Vendedores();
                $vendedores->setid_sucursal($r->id_vendedor);
                $vendedores->setdni($r->dni);
                $vendedores->setnombre($r->nombre);
                $vendedores->setapellido($r->apellido);
                $vendedores->setdireccion($r->direccion);
                $vendedores->settelefono($r->telefono);
                $vendedores->setcorreo($r->correo);
                $result[] = $vendedores;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar vendedor: " . $e->getMessage());
        }
    }

    // Obtener un usuario por su ID
    public function Obtener(int $id_vendedor): ?Vendedores {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM vendedores WHERE id_vendedor = ?");
            $stm->execute([$id_vendedor]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r) {
                $vendedores = new Vendedores();
                $vendedores->setid_sucursal($r->id_vendedor);
                $vendedores->setdni($r->dni);
                $vendedores->setnombre($r->nombre);
                $vendedores->setapellido($r->apellido);
                $vendedores->setdireccion($r->direccion);
                $vendedores->settelefono($r->telefono);
                $vendedores->setcorreo($r->correo);
                $result[] = $vendedores;
            }

            return null;
        } catch (Exception $e) {
            die("Error al obtener vendedor: " . $e->getMessage());
        }
    }

    // Eliminar un usuario por ID
    public function Eliminar(int $id_vendedor): void {
        try {
            $stm = $this->pdo->prepare("DELETE FROM vendedores WHERE id_vendedor = ?");
            $stm->execute([$id_vendedor]);
        } catch (Exception $e) {
            die("Error al eliminar vendedor: " . $e->getMessage());
        }
    }

    // Actualizar un usuario (con o sin clave)
    public function Actualizar(Vendedores $data): void {
        try {
            $params = [
                $data->getdni(),
                $data->getnombre(),
                $data->getapellido(),
                $data->getdireccion(),
                $data->gettelefono(),
                $data->getcorreo(),
            ];

            $sql = "UPDATE vendedores SET 
                    dni = ?, nombre = ?, apellido = ?, direccion = ?, telefono = ?, correo = ?";

            if (!empty($data->getclave())) {
                $sql .= ", Clave = ?";
                $params[] = $data->getclave();
            }

            $sql .= " WHERE id_vendedor = ?";
            $params[] = $data->getid_vendedor();

            $this->pdo->prepare($sql)->execute($params);
        } catch (Exception $e) {
            die("Error al actualizar vendedor: " . $e->getMessage());
        }
    }


    // Registrar un nuevo usuario
    public function Registrar(Vendedores $data): void {
        try {
            $sql = "
                INSERT INTO vendedores (dni, nombre, apellido, direccion, telefono, correo) 
                VALUES (?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->getdni(),
                $data->getnombre(),
                $data->getapellido(),
                $data->getdireccion(),
                $data->gettelefono(),
                $data->getcorreo() // Debe estar hasheada con password_hash
            ]);
        } catch (Exception $e) {
            die("Error al registrar vendedor: " . $e->getMessage());
        }
    }

    // Verificar correo y clave (retorna true/false)
    public function Verificar(Vendedores $data): bool {
        try {
            $sql = "SELECT Clave FROM vendedores WHERE correo = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$data->getcorreo()]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            return $r && password_verify($data->getclave(), $r->Clave);
        } catch (Exception $e) {
            die("Error al verificar vendedor: " . $e->getMessage());
        }
    }

    // Buscar por nombre, apellido o correo
    public function Buscar(string $termino): array {
        try {
            $result = [];
            $sql = "
                SELECT * FROM vendedores 
                WHERE nombre LIKE :term";
            $stm = $this->pdo->prepare($sql);
            $like = '%' . $termino . '%';
            $stm->bindParam(':term', $like, PDO::PARAM_STR);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $vendedores = new Vendedores();
                $vendedores->setid_sucursal($r->id_vendedor);
                $vendedores->setdni($r->dni);
                $vendedores->setnombre($r->nombre);
                $vendedores->setapellido($r->apellido);
                $vendedores->setdireccion($r->direccion);
                $vendedores->settelefono($r->telefono);
                $vendedores->setcorreo($r->correo);
                $result[] = $vendedores;
            }

            return $result;
        } catch (Exception $e) {
            die("Error al buscar vendedor: " . $e->getMessage());
        }
    }

    
    
}
?>
