<?php
require_once 'Vendedores.php';
require_once 'Vendedores.model.php';
require_once 'Cargo.php';
require_once 'Cargo.model.php';

session_start();

// Validación de sesión
if (!isset($_SESSION['id_vendedor'])) {
    header("Location: login.php");
    exit();
}

$vendedores = new Vendedores();
$vendedoresmodel = new VendedoresModel();
$cargo = new Cargo();
$cargomodel = new CargoModel();

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];

    // Entradas básicas con limpieza
    $id_vendedor = filter_input(INPUT_POST, 'id_vendedor', FILTER_VALIDATE_INT);
    $dni = trim($_POST['dni'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = filter_input(INPUT_POST, 'Correo', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['Clave'] ?? '';

    switch ($operacion) {
        case 'actualizar':
            $vendedores->setid_vendedor($id_vendedor);
            $vendedores->setdni($dni);
            $vendedores->setnombre($nombre);
            $vendedores->setapellido($apellido);
            $vendedores->setdireccion($direccion);
            $vendedores->settelefono($telefono);
            $vendedores->setcorreo($correo);
            if (!empty($clave)) {
                $vendedores->setclave(password_hash($clave, PASSWORD_DEFAULT));
            }
            $vendedoresmodel->Actualizar($vendedores);
            header('Location: VendedoresGUI.php');
            exit();

        case 'registrar':
            $vendedores->setid_vendedor($id_vendedor);
            $vendedores->setdni($dni);
            $vendedores->setnombre($nombre);
            $vendedores->setapellido($apellido);
            $vendedores->setdireccion($direccion);
            $vendedores->settelefono($telefono);
            $vendedores->setcorreo($correo);
            $vendedores->setclave(password_hash($clave, PASSWORD_DEFAULT));
            //  
            $vendedoresmodel->Registrar($vendedores);
            header('Location: VendedoresGUI.php');
            exit();

        case 'eliminar':
            $idEliminar = filter_input(INPUT_POST, 'id_vendedor', FILTER_VALIDATE_INT);
            $vendedormodel->Eliminar($idEliminar);
            header('Location: VendedoresGUI.php');
            exit();

        case 'editar':
            $idEditar = filter_input(INPUT_POST, 'id_vendedor', FILTER_VALIDATE_INT);
            $vendedores = $vendedoresmodel->Obtener($idEditar);
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Vendedores</title>
    <link rel="stylesheet" href="Css/VendedoresGUI.css">
</head>
<body>
    <h1>Administración de Vendedores</h1>

    <!-- FORMULARIO ADMINISTRACION USUARIOS -->
     <form action="VendedoresGUI.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="operacion" value="<?= $vendedores->getid_vendedor() > 0 ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="id_vendedor" value="<?= $vendedores->getid_vendedor(); ?>">

        <label>DNI:</label>
        <input type="text" name="dni" required value="<?= htmlspecialchars($vendedores->getdni() ?? ''); ?>"><br>

        <label>Nombre:</label>
        <input type="text" name="nombre" required value="<?= htmlspecialchars($vendedores->getnombre() ?? ''); ?>"><br>

        <label>Apellido:</label>
        <input type="text" name="apellido" required value="<?= htmlspecialchars($vendedores->getapellido() ?? ''); ?>"><br>

        <label>Dirección:</label>
        <input type="text" name="direccion" required value="<?= htmlspecialchars($vendedores->getdireccion() ?? ''); ?>"><br>

        <label>Telefono:</label>
        <input type="text" name="telefono" required value="<?= htmlspecialchars($vendedores->gettelefono() ?? ''); ?>"><br>

        <label>Correo:</label>
        <input type="text" name="correo" required value="<?= htmlspecialchars($vendedores->getcorreo() ?? ''); ?>"><br>


        <label>Clave:</label>
        <input type="password" name="Clave" <?= $vendedores->getid_vendedor() < 1 ? 'required' : ''; ?>><br>
        <br>

        
        <button type="submit">
            <?= $vendedores->getid_vendedor() > 0 ? 'Actualizar' : 'Registrar'; ?>
        </button>
    </form>

    <!-- Tabla de sucursales -->
    <h2>Listado de Vendedores</h2>
    <table border="1">
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Dirección</th>
            <th>Telefono</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($vendedoresmodel->Listar() as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r->getdni()); ?></td>
                <td><?= htmlspecialchars($r->getnombre()); ?></td>
                <td><?= htmlspecialchars($r->getapellido()); ?></td>
                <td><?= htmlspecialchars($r->getdireccion()); ?></td>
                <td><?= htmlspecialchars($r->gettelefono()); ?></td>
                <td><?= htmlspecialchars($r->getcorreo()); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="operacion" value="editar">
                        <input type="hidden" name="id_vendedor" value="<?= $r->getid_vendedor(); ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este vendedor?');">
                        <input type="hidden" name="operacion" value="eliminar">
                        <input type="hidden" name="id_vendedor" value="<?= $r->getid_vendedor(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

