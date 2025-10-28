<?php
include_once("Conexion.php");
include_once("Producto.php");

class ProductoModel {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    // Listar todos los productos
    public function Listar(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("
                SELECT p.id_automovil, p.nombre, p.marca, p.precio, p.descripcion, p.stock 
                FROM producto p 
            ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $producto = new Producto();
                $producto->setid_automovil($r->id_automovil);
                $producto->setnombre($r->nombre);
                $producto->setmarca($r->marca);
                $producto->setprecio($r->precio);
                $producto->setdescripcion($r->descripcion);
                $producto->setstock($r->stock);
                $result[] = $producto;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar productos: " . $e->getMessage());
        }
    }

    public function ListarAutomovil(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("
                SELECT p.id_automovil, p.nombre, p.marca, p.precio, p.descripcion, p.stock 
                FROM producto p 
            ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $producto = new Producto();
                $producto->setid_automovil($r->id_automovil);
                $producto->setnombre($r->nombre);
                $producto->setmarca($r->marca);
                $producto->setprecio($r->precio);
                $producto->setdescripcion($r->descripcion);
                $producto->setstock($r->stock);
                $result[] = $producto;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar productos: " . $e->getMessage());
        }
    }

    // Obtener un producto por su ID
    public function Obtener(int $id_automovil): ?Producto {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM producto WHERE id_automovil = ?");
            $stm->execute([$id_automovil]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r) {
                $producto = new Producto();
                $producto->setid_automovil($r->id_automovil);
                $producto->setnombre($r->nombre);
                $producto->setmarca($r->marca);
                $producto->setprecio($r->precio);
                $producto->setdescripcion($r->descripcion);
                $producto->setstock($r->stock);
                return $producto;
            }

            return null;
        } catch (Exception $e) {
            die("Error al obtener productos: " . $e->getMessage());
        }
    }

    // Eliminar un producto por ID
    public function Eliminar(int $id_automovil): void {
        try {
            $stm = $this->pdo->prepare("DELETE FROM producto WHERE id_automovil = ?");
            $stm->execute([$id_automovil]);
        } catch (Exception $e) {
            die("Error al eliminar producto: " . $e->getMessage());
        }
    }

    // Actualizar un producto (con o sin clave)
    public function Actualizar(Producto $data): void {
        try {
            $params = [
                $data->getnombre(),
                $data->getmarca(),
                $data->getprecio(),
                $data->getdescripcion(),
                $data->getstock(),
            ];

            $sql = "UPDATE producto SET 
                    nombre = ?, marca = ?, precio = ?, descripcion = ?, stock = ?";
 

            $sql .= " WHERE id_automovil = ?";
            $params[] = $data->getid_automovil();

            $this->pdo->prepare($sql)->execute($params);
        } catch (Exception $e) {
            die("Error al actualizar producto: " . $e->getMessage());
        }
    }


    // Registrar un nuevo producto
    public function Registrar(Producto $data): void {
        try {
            $sql = "
                INSERT INTO producto (nombre, marca, precio, descripcion, stock) 
                VALUES (?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->getnombre(),
                $data->getmarca(),
                $data->getprecio(),
                $data->getdescripcion(),
                $data->getstock(),
            ]);
        } catch (Exception $e) {
            die("Error al registrar producto: " . $e->getMessage());
        }
    }


    // Buscar por nombre o marca
    public function Buscar(string $termino): array {
        try {
            $result = [];
            $sql = "
                SELECT * FROM producto 
                WHERE nombre LIKE :term OR marca LIKE :term ";
            $stm = $this->pdo->prepare($sql);
            $like = '%' . $termino . '%';
            $stm->bindParam(':term', $like, PDO::PARAM_STR);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $producto = new Producto();
                $producto->setid_automovil($r->id_automovil);
                $producto->setnombre($r->nombre);
                $producto->setmarca($r->marca);
                $producto->setprecio($r->precio);
                $producto->setdescripcion($r->descripcion);
                $producto->setstock($r->stock);
                $result[] = $producto;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al buscar producto: " . $e->getMessage());
        }
    }
/*
    // Login (retorna objeto Producto o null)
    public function Login(Producto $data): ?Producto {
    try {
        $sql = "
            SELECT p.id_automovil, p.nombre, p.marca, p.precio, p.descripcion,  
                   p.stock
            FROM producto p
            WHERE p.nombre = ? OR p.marca = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$data->getnombre()]);
        $r = $stm->fetch(PDO::FETCH_OBJ);

        if ($r && password_verify($data->getclave(), $r->Clave)) {
            $producto = new Producto();
            $producto->setidauto($r->id_auto);
            $producto->setnombre($r->nombre);
            $producto->setmarca($r->marca);
            $producto->setprecio($r->precio);
            $producto->setdescripcion($r->descripcion);
            $producto->setstock($r->stock);
            return $producto;
        }

        return null;
    } catch (Exception $e) {
        die("Error al hacer login: " . $e->getMessage());
    }
}*/
}
?>
