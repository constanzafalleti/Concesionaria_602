<?php
require_once 'Usuario.php';
require_once 'Usuario.model.php';
require_once 'Cargo.php';
require_once 'Cargo.model.php';

session_start();

// Validación de sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = new Usuario();
$usuariomodel = new UsuarioModel();
$cargo = new Cargo();
$cargomodel = new CargoModel();

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];

    // Entradas básicas con limpieza
    $idUsuario = filter_input(INPUT_POST, 'idUsuario', FILTER_VALIDATE_INT);
    $Nombre = trim($_POST['Nombre'] ?? '');
    $Apellido = trim($_POST['Apellido'] ?? '');
    $Direccion = trim($_POST['Direccion'] ?? '');
    $Correo = filter_input(INPUT_POST, 'Correo', FILTER_VALIDATE_EMAIL);
    $Clave = $_POST['Clave'] ?? '';
    $id_cargo = filter_input(INPUT_POST, 'id_cargo', FILTER_VALIDATE_INT);

    switch ($operacion) {
        case 'actualizar':
            $usuario->setidUsuario($idUsuario);
            $usuario->setNombre($Nombre);
            $usuario->setApellido($Apellido);
            $usuario->setDireccion($Direccion);
            $usuario->setCorreo($Correo);
            $usuario->setid_cargo($id_cargo);
            if (!empty($Clave)) {
                $usuario->setClave(password_hash($Clave, PASSWORD_DEFAULT));
            }

            $usuariomodel->Actualizar($usuario);
            header('Location: UsuarioGUI.php');
            exit();

        case 'registrar':
            $usuario->setNombre($Nombre);
            $usuario->setApellido($Apellido);
            $usuario->setDireccion($Direccion);
            $usuario->setCorreo($Correo);
            $usuario->setClave(password_hash($Clave, PASSWORD_DEFAULT));
            $usuario->setid_cargo($id_cargo);
            
            $usuariomodel->Registrar($usuario);
            header('Location: UsuarioGUI.php');
            exit();

        case 'eliminar':
            $idEliminar = filter_input(INPUT_POST, 'idUsuario', FILTER_VALIDATE_INT);
            $usuariomodel->Eliminar($idEliminar);
            header('Location: UsuarioGUI.php');
            exit();

        case 'editar':
            $idEditar = filter_input(INPUT_POST, 'idUsuario', FILTER_VALIDATE_INT);
            $usuario = $usuariomodel->Obtener($idEditar);
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="css/UsuariosGUI.css">
</head>
<body>
    <h1>Administración de Usuarios</h1>

    <!-- FORMULARIO ADMINISTRACION USUARIOS -->
    <form action="UsuarioGUI.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="operacion" value="<?= $usuario->getidUsuario() > 0 ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="idUsuario" value="<?= $usuario->getidUsuario(); ?>">

        <label>Nombre:</label>
        <input type="text" name="Nombre" required value="<?= htmlspecialchars($usuario->getNombre() ?? ''); ?>"><br>

        <label>Apellido:</label>
        <input type="text" name="Apellido" required value="<?= htmlspecialchars($usuario->getApellido() ?? ''); ?>"><br>

        <label>Dirección:</label>
        <input type="text" name="Direccion" required value="<?= htmlspecialchars($usuario->getDireccion() ?? ''); ?>"><br>

        <label>Correo:</label>
        <input type="email" name="Correo" required value="<?= htmlspecialchars($usuario->getCorreo() ?? ''); ?>"><br>

        <label>Clave:</label>
        <input type="password" name="Clave" <?= $usuario->getidUsuario() < 1 ? 'required' : ''; ?>><br>
        
        <label>Cargo:</label>
        <select name="id_cargo" required>
            <option value="">Seleccione...</option>
            <?php
            $cargoList = ($_SESSION['id_cargo'] < 3) ? $cargomodel->ListarTodos() : $cargomodel->ListarRestringidos();
            foreach ($cargoList as $r):
                $selected = $usuario->getid_cargo() == $r->getid_cargo() ? 'selected' : '';
                echo "<option value='{$r->getid_cargo()}' $selected>" . htmlspecialchars($r->getTipo()) . "</option>";
            endforeach;
            ?>
        </select><br><br>

        <button type="submit">
            <?= $usuario->getidUsuario() > 0 ? 'Actualizar' : 'Registrar'; ?>
        </button>
    </form>

    <!-- Tabla de usuarios -->
    <h2>Listado de Usuarios</h2>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Dirección</th>
            <th>Correo</th>
            <th>Cargo</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($usuariomodel->Listar() as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r->getNombre()); ?></td>
                <td><?= htmlspecialchars($r->getApellido()); ?></td>
                <td><?= htmlspecialchars($r->getDireccion()); ?></td>
                <td><?= htmlspecialchars($r->getCorreo()); ?></td>
                <td><?= htmlspecialchars($r->getTipo()); ?></td>
                <td class="acciones">
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="operacion" value="editar">
                        <input type="hidden" name="idUsuario" value="<?= $r->getidUsuario(); ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este usuario?');">
                        <input type="hidden" name="operacion" value="eliminar">
                        <input type="hidden" name="idUsuario" value="<?= $r->getidUsuario(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
