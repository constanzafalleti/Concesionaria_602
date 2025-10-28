<?php
require_once 'Producto.php';
require_once 'Producto.model.php';

session_start();

// Validación de sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: Login.php");
    exit();
}

$producto = new Producto();
$productoModel = new ProductoModel();


// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];

    // Entradas básicas con limpieza
    $id_automovil = filter_input(INPUT_POST, 'id_automovil', FILTER_VALIDATE_INT);
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['marca'] ?? '');
    $direccion = trim($_POST['precio'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    switch ($operacion) {
        case 'actualizar':
            $producto->setid_automovil($id_automovil);
            $producto->setnombre($nombre);
            $producto->setmarca($marca);
            $producto->setprecio($precio);
            $producto->setdescripcion($descripciom);
            $producto->setstock($stock);
            
            $productoModel->Actualizar($producto);
            header('Location: ProductoGUI.php');
            exit();

        case 'registrar':
            $producto->setid_automovil($id_automovil);
            $producto->setnombre($nombre);
            $producto->setmarca($marca);
            $producto->setprecio($precio);
            $producto->setdescripcion($descripciom);
            $producto->setstock($stock);
            
            $productoModel->Registrar($producto);
            header('Location: ProductoGUI.php');
            exit();

        case 'eliminar':
            $idEliminar = filter_input(INPUT_POST, 'id_automovil', FILTER_VALIDATE_INT);
            $productoModel->Eliminar($idEliminar);
            header('Location: ProductoGUI.php');
            exit();

        case 'editar':
            $idEditar = filter_input(INPUT_POST, 'id_automovil', FILTER_VALIDATE_INT);
            $producto = $productoModel->Obtener($idEditar);
            break;
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="Css/ProductoGUI.css">
</head>
<body>
    <h1>Administración de Productos</h1>

    <!-- FORMULARIO ADMINISTRACION PRODUCTOS -->
     <form action="ProductoGUI.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="operacion" value="<?= $producto->getid_automovil() > 0 ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="id_automovil" value="<?= $producto->getid_automovil(); ?>">

        <label>Nombre del Auto:</label>
        <input type="text" name="nombre" required value="<?= htmlspecialchars($producto->getnombre() ?? ''); ?>"><br>

        <label>Marca:</label>
        <input type="text" name="marca" required value="<?= htmlspecialchars($producto->getmarca() ?? ''); ?>"><br>

        <label>Precio:</label>
        <input type="text" name="precio" required value="<?= htmlspecialchars($producto->getprecio() ?? ''); ?>"><br>

        <label>Descripcion:</label>
        <input type="text" name="descripcion" required value="<?= htmlspecialchars($producto->getdescripcion() ?? ''); ?>"><br>

        <label>Stock:</label>
        <input type="text" name="stock" required value="<?= htmlspecialchars($producto->getstock() ?? ''); ?>"><br>


        <button type="submit">
            <?= $producto->getid_automovil() > 0 ? 'Actualizar' : 'Registrar'; ?>
        </button>
    </form>

    <!-- Tabla de productos -->
    <h2>Listado de Productos</h2>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Descripcion</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productoModel->Listar() as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r->getnombre()); ?></td>
                <td><?= htmlspecialchars($r->getmarca()); ?></td>
                <td><?= htmlspecialchars($r->getprecio()); ?></td>
                <td><?= htmlspecialchars($r->getstock()); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="operacion" value="editar">
                        <input type="hidden" name="id_automovil" value="<?= $r->getid_automovil(); ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este producto?');">
                        <input type="hidden" name="operacion" value="eliminar">
                        <input type="hidden" name="id_automovil" value="<?= $r->getid_automovil(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

