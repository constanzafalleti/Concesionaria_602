<?php
include_once("Conexion.php");
include_once("Usuario.php");
include_once("Cargo.php");
include_once("Cargo.model.php");

class UsuarioModel {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    // Listar todos los usuarios con su cargo
    public function Listar(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("
                SELECT u.idUsuario, u.Nombre, u.Apellido, u.Direccion, u.Correo, c.tipo 
                FROM usuarios u 
                INNER JOIN cargo c ON u.id_cargo = c.id_cargo
            ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $usuario = new Usuario();
                $usuario->setidUsuario($r->idUsuario);
                $usuario->setNombre($r->Nombre);
                $usuario->setApellido($r->Apellido);
                $usuario->setDireccion($r->Direccion);
                $usuario->setCorreo($r->Correo);
                $usuario->setTipo($r->tipo);
                $result[] = $usuario;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar usuarios: " . $e->getMessage());
        }
    }

    // Listar los vendedores
    public function ListarVendedor(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("
                SELECT Nombre, Apellido FROM usuarios WHERE id_cargo = 3
            ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $usuario = new Usuario();
                $usuario->setNombre($r->Nombre);
                $usuario->setApellido($r->Apellido);
                
                $result[] = $usuario;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar usuarios: " . $e->getMessage());
        }
    }

    // Listar los clientes
    public function ListarClientes(): array {
        try {
            $result = [];
            $stm = $this->pdo->prepare("
                SELECT idUsuario, Nombre FROM usuarios WHERE id_cargo= 2
            ");
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $usuario = new Usuario();
                $usuario->setidUsuario($r->idUsuario);
                $usuario->setNombre($r->Nombre);
                
                
                $result[] = $usuario;
            }
            return $result;
        } catch (Exception $e) {
            die("Error al listar usuarios: " . $e->getMessage());
        }
    }

    // Obtener un usuario por su ID
    public function Obtener(int $idUsuario): ?Usuario {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM usuarios WHERE idUsuario = ?");
            $stm->execute([$idUsuario]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r) {
                $usuario = new Usuario();
                $usuario->setidUsuario($r->idUsuario);
                $usuario->setNombre($r->Nombre);
                $usuario->setApellido($r->Apellido);
                $usuario->setDireccion($r->Direccion);
                $usuario->setCorreo($r->Correo);
                $usuario->setClave($r->Clave);
                $usuario->setid_cargo($r->id_cargo);
                return $usuario;
            }

            return null;
        } catch (Exception $e) {
            die("Error al obtener usuario: " . $e->getMessage());
        }
    }

    // Eliminar un usuario por ID
    public function Eliminar(int $idUsuario): void {
        try {
            $stm = $this->pdo->prepare("DELETE FROM usuarios WHERE idUsuario = ?");
            $stm->execute([$idUsuario]);
        } catch (Exception $e) {
            die("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    // Actualizar un usuario (con o sin clave)
    public function Actualizar(Usuario $data): void {
        try {
            $params = [
                $data->getNombre(),
                $data->getApellido(),
                $data->getDireccion(),
                $data->getCorreo(),
                $data->getid_cargo(),
            ];

            $sql = "UPDATE usuarios SET 
                    Nombre = ?, Apellido = ?, Direccion = ?, Correo = ?, id_cargo = ?";

            if (!empty($data->getClave())) {
                $sql .= ", Clave = ?";
                $params[] = $data->getClave();
            }

            $sql .= " WHERE idUsuario = ?";
            $params[] = $data->getidUsuario();

            $this->pdo->prepare($sql)->execute($params);
        } catch (Exception $e) {
            die("Error al actualizar usuario: " . $e->getMessage());
        }
    }

    // Registrar un nuevo usuario
    public function Registrar(Usuario $data): void {
        try {
            $sql = "
                INSERT INTO usuarios (Nombre, Apellido, Direccion, Correo, Clave, id_cargo) 
                VALUES (?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->getNombre(),
                $data->getApellido(),
                $data->getDireccion(),
                $data->getCorreo(),
                $data->getClave(), // Debe estar hasheada con password_hash
                $data->getid_cargo()
            ]);
        } catch (Exception $e) {
            die("Error al registrar usuario: " . $e->getMessage());
        }
    }

    // Verificar correo y clave (retorna true/false)
    public function Verificar(Usuario $data): bool {
        try {
            $sql = "SELECT Clave FROM usuarios WHERE Correo = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$data->getCorreo()]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            return $r && password_verify($data->getClave(), $r->Clave);
        } catch (Exception $e) {
            die("Error al verificar usuario: " . $e->getMessage());
        }
    }

    // Buscar por nombre, apellido o correo
    public function Buscar(string $termino): array {
        try {
            $result = [];
            $sql = "
                SELECT * FROM usuarios 
                WHERE Nombre LIKE :term OR Apellido LIKE :term OR Correo LIKE :term";
            $stm = $this->pdo->prepare($sql);
            $like = '%' . $termino . '%';
            $stm->bindParam(':term', $like, PDO::PARAM_STR);
            $stm->execute();

            foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
                $usuario = new Usuario();
                $usuario->setidUsuario($r->idUsuario);
                $usuario->setNombre($r->Nombre);
                $usuario->setApellido($r->Apellido);
                $usuario->setCorreo($r->Correo);
                $usuario->setDireccion($r->Direccion);
                $usuario->setid_cargo($r->id_cargo);
                $result[] = $usuario;
            }

            return $result;
        } catch (Exception $e) {
            die("Error al buscar usuario: " . $e->getMessage());
        }
    }

    // Login (retorna objeto Usuario o null)
    public function Login(Usuario $data): ?Usuario {
        try {
            $sql = "
                SELECT u.idUsuario, u.Nombre, u.Apellido, u.Direccion, u.Correo, u.Clave, 
                       u.id_cargo, c.tipo
                FROM usuarios u
                INNER JOIN cargo c ON u.id_cargo = c.id_cargo
                WHERE u.Correo = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$data->getCorreo()]);
            $r = $stm->fetch(PDO::FETCH_OBJ);

            if ($r && password_verify($data->getClave(), $r->Clave)) {
                $user = new Usuario();
                $user->setidUsuario($r->idUsuario);
                $user->setNombre($r->Nombre);
                $user->setApellido($r->Apellido);
                $user->setDireccion($r->Direccion);
                $user->setCorreo($r->Correo);
                $user->setid_cargo($r->id_cargo);
                $user->setTipo($r->tipo);
                return $user;
            }

            return null;
        } catch (Exception $e) {
            die("Error al hacer login: " . $e->getMessage());
        }
    }
}
?>
