<?php
include_once("Conexion.php");
include_once("Venta.php");
require_once 'Usuario.php';
require_once 'Usuario.model.php';
require_once 'Producto.php';
require_once 'Producto.model.php';


class VentaModel{
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    // Listar todas las ventas
    public function Listar(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("
                SELECT v.id_ventas, v.fechahora, v.montototal, v.mediopago, v.descripcion, v.id_automovil, v.idUsuario 
                FROM ventas v
                INNER JOIN producto p ON v.id_automovil = p.id_automovil
                INNER JOIN usuarios u ON v.idUsuario = u.idUsuario
               
            ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $venta = new Venta();
                $venta->setid_ventas($r->id_ventas);
                $venta->setfechahora($r->fechahora);
                $venta->setmontototal($r->montototal);
                $venta->setmediopago($r->mediopago);
                $venta->setdescripcion($r->descripcion);
                $venta->setid_automovil($r->id_automovil);
                $venta->setidUsuario($r->idUsuario);

                $result[] = $venta;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar ventas: " . $e->getMessage());
        }
    }

    // Obtener una venta por su ID
    public function Obtener(int $id_ventas): ?Venta {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM ventas WHERE id_ventas = ?");
            $stm->execute([$id_ventas]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r) {
                $venta = new Venta();
                $venta->setid_ventas($r->id_ventas);
                $venta->setfechahora($r->fechahora);
                $venta->setmontototal($r->montototal);
                $venta->setmediopago($r->mediopago);
                $venta->setdescripcion($r->descripcion);
                $venta->setid_automovil($r->id_automovil);
               $venta->setidUsuario($r->idUsuario);

                return $venta;
            }

            return null;
        } catch (Exception $e) {
            die("Error al obtener venta: " . $e->getMessage());
        }
    }

    // Eliminar una venta por ID
    public function Eliminar(int $id_ventas): void {
        try {
            $stm = $this->pdo->prepare("DELETE FROM ventas WHERE id_ventas = ?");
            $stm->execute([$id_ventas]);
        } catch (Exception $e) {
            die("Error al eliminar venta: " . $e->getMessage());
        }
    }

    // Actualizar una venta
    public function Actualizar(Venta $data): void {
        try {
            $sql = "UPDATE ventas SET 
                        fechahora = ?, montototal = ?, mediopago = ?, descripcion = ?,
                        id_automovil = ?, idUsuario = ?
                    WHERE id_ventas = ?";
            $params = [
                $data->getfechahora(),
                $data->getmontototal(),
                $data->getmediopago(),
                $data->getdescripcion(),
                $data->getid_automovil(),
                $data->getidUsuario(),
                $data->getid_ventas()
            ];
            $this->pdo->prepare($sql)->execute($params);
        } catch (Exception $e) {
            die("Error al actualizar venta: " . $e->getMessage());
        }
    }

    // Registrar una nueva venta
    public function Registrar(Venta $data): void {
        try {
            $sql = "
                INSERT INTO ventas (fechahora, montototal, mediopago, descripcion, id_automovil, idUsuario, id_vendedor) 
                VALUES ( ?, ?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->getfechahora(),
                $data->getmontototal(),
                $data->getmediopago(),
                $data->getdescripcion(),
                $data->getid_automovil(),
                $data->getidUsuario(),
                $data->getid_vendedor(),

            ]);
        } catch (Exception $e) {
            die("Error al registrar la venta: " . $e->getMessage());
        }
    }

    // Buscar ventas por fecha o medio de pago
    public function Buscar(string $termino): array {
        try {
            $result = [];
            $sql = "
                SELECT * FROM ventas 
                WHERE fechahora LIKE :term OR mediopago LIKE :term ";
            $stm = $this->pdo->prepare($sql);
            $like = '%' . $termino . '%';
            $stm->bindParam(':term', $like, PDO::PARAM_STR);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $venta = new Venta();
                $venta->setid_ventas($r->id_ventas);
                $venta->setfechahora($r->fechahora);
                $venta->setmontototal($r->montototal);
                $venta->setmediopago($r->mediopago);
                $venta->setdescripcion($r->descripcion);
                $venta->setid_automovil($r->id_automovil);
                $venta->setidUsuario($r->idUsuario);

                $result[] = $venta;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al buscar la venta: " . $e->getMessage());
        }
    }
}
?>
