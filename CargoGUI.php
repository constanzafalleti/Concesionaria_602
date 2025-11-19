<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="Css/ProductoGUI.css">
</head>
<body>

<div class="container">
    <h1>Administración de Productos</h1>

    <!-- FORMULARIO ADMINISTRACION PRODUCTOS -->
    <form action="ProductoGUI.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="operacion" value="<?= $producto->getid_automovil() > 0 ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="id_automovil" value="<?= $producto->getid_automovil(); ?>">

        <label>Nombre del Auto:</label>
        <input type="text" name="nombre" required value="<?= htmlspecialchars($producto->getnombre() ?? ''); ?>">

        <label>Marca:</label>
        <input type="text" name="marca" required value="<?= htmlspecialchars($producto->getmarca() ?? ''); ?>">

        <label>Precio:</label>
        <input type="text" name="precio" required value="<?= htmlspecialchars($producto->getprecio() ?? ''); ?>">

        <label>Descripción:</label>
        <input type="text" name="descripcion" required value="<?= htmlspecialchars($producto->getdescripcion() ?? ''); ?>">

        <label>Stock:</label>
        <input type="text" name="stock" required value="<?= htmlspecialchars($producto->getstock() ?? ''); ?>">

        <button type="submit">
            <?= $producto->getid_automovil() > 0 ? 'Actualizar' : 'Registrar'; ?>
        </button>
    </form>

    <!-- TABLA DE PRODUCTOS -->
    <h2>Listado de Productos</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Descripción</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($productoModel->Listar() as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r->getnombre()); ?></td>
                <td><?= htmlspecialchars($r->getmarca()); ?></td>
                <td><?= htmlspecialchars($r->getprecio()); ?></td>
                <td><?= htmlspecialchars($r->getdescripcion()); ?></td>
                <td><?= htmlspecialchars($r->getstock()); ?></td>

                <td>
                    <form method="post">
                        <input type="hidden" name="operacion" value="editar">
                        <input type="hidden" name="id_automovil" value="<?= $r->getid_automovil(); ?>">
                        <button type="submit">Editar</button>
                    </form>

                    <form method="post" onsubmit="return confirm('¿Desea eliminar este producto?');">
                        <input type="hidden" name="operacion" value="eliminar">
                        <input type="hidden" name="id_automovil" value="<?= $r->getid_automovil(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
</div>

</body>
</html>