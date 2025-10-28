<?php
require_once 'Venta.php';
require_once 'Venta.model.php';

session_start();

// Validación de sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

$venta = new Venta();
$ventamodel = new VentaModel();

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];

    // Entradas básicas con limpieza
    $id_ventas   = filter_input(INPUT_POST, 'id_ventas', FILTER_VALIDATE_INT);
    $fechahora   = trim($_POST['fechahora'] ?? '');
    $montototal  = trim($_POST['montototal'] ?? '');
    $mediopago   = trim($_POST['mediopago'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $id_automovil = filter_input(INPUT_POST, 'id_automovil', FILTER_VALIDATE_INT);
    $id_usuario   = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);

    switch ($operacion) {
        case 'actualizar':
            $venta->setid_ventas($id_ventas);
            $venta->setfechahora($fechahora);
            $venta->setmontototal($montototal);
            $venta->setmediopago($mediopago);
            $venta->setdescripcion($descripcion);
            $venta->setid_automovil($id_automovil);
            $venta->setid_usuario($id_usuario);
            $ventamodel->Actualizar($venta);
            header('Location: VentaGUI.php');
            exit();

        case 'registrar':
            $venta->setfechahora($fechahora);
            $venta->setmontototal($montototal);
            $venta->setmediopago($mediopago);
            $venta->setdescripcion($descripcion);
            $venta->setid_automovil($id_automovil);
            $venta->setid_usuario($id_usuario);
            $ventamodel->Registrar($venta);
            header('Location: VentaGUI.php');
            exit();

        case 'eliminar':
            $idEliminar = filter_input(INPUT_POST, 'id_ventas', FILTER_VALIDATE_INT);
            $ventamodel->Eliminar($idEliminar);
            header('Location: VentaGUI.php');
            exit();

        case 'editar':
            $idEditar = filter_input(INPUT_POST, 'id_ventas', FILTER_VALIDATE_INT);
            $venta = $ventamodel->Obtener($idEditar);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Ventas</title>
    <link rel="stylesheet" href="Css/VentaGUI.css">
</head>
<body>
    <h1>Administración de Ventas</h1>

    <!-- FORMULARIO ADMINISTRACION VENTAS -->
    <form action="VentaGUI.php" method="post">
        <input type="hidden" name="operacion" value="<?= $venta->getid_ventas() > 0 ? 'actualizar' : 'registrar'; ?>">
        <input type="hidden" name="id_ventas" value="<?= $venta->getid_ventas(); ?>">

        <label>Fecha:</label>
        <input type="date" name="fechahora" required value="<?= htmlspecialchars($venta->getfechahora() ?? ''); ?>"><br>

        <label>Monto:</label>
        <input type="text" name="montototal" required value="<?= htmlspecialchars($venta->getmontototal() ?? ''); ?>"><br>

        <label>Medio de Pago:</label>
        <input type="text" name="mediopago" required value="<?= htmlspecialchars($venta->getmediopago() ?? ''); ?>"><br>

        <label>Descripcion:</label>
        <input type="text" name="descripcion" required value="<?= htmlspecialchars($venta->getdescripcion() ?? ''); ?>"><br>

        <label>Automovil:</label>
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

        <label>Vendedor: </label>
        <select name="id_cargo" required>
            <option value="">Seleccione...</option>
            <?php
            $cargoList = ($_SESSION['id_cargo'] < 3) ? $usuariomodel->ListarVendedor(): [];
            foreach ($cargoList as $r):
                $selected = $usuario->getid_cargo() == $r->getid_cargo() ? 'selected' : '';
                echo "<option value='{$r->getid_cargo()}' $selected>" . htmlspecialchars($r->getTipo()) . "</option>";
            endforeach;
            ?>
        </select><br><br>

        <label>Cliente:</label>
        <select name="id_cargo" required>
            <option value="">Seleccione...</option>
            <?php
            $cargoList = ($_SESSION['id_cargo'] < 3) ? $usuariomodel->ListarNombre(): [];
            foreach ($cargoList as $r):
                $selected = $usuario->getid_cargo() == $r->getid_cargo() ? 'selected' : '';
                echo "<option value='{$r->getid_cargo()}' $selected>" . htmlspecialchars($r->getTipo()) . "</option>";
            endforeach;
            ?>
        </select><br><br>


        <button type="submit">
            <?= $venta->getid_ventas() > 0 ? 'Actualizar' : 'Registrar'; ?>
        </button>
    </form>

    <!-- Tabla de ventas -->
    <h2>Listado de Ventas</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Medio de Pago</th>
            <th>Descripcion</th>
            <th>ID Automovil</th>
            <th>ID Usuario</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($ventamodel->Listar() as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r->getid_ventas()); ?></td>
                <td><?= htmlspecialchars($r->getfechahora()); ?></td>
                <td><?= htmlspecialchars($r->getmontototal()); ?></td>
                <td><?= htmlspecialchars($r->getmediopago()); ?></td>
                <td><?= htmlspecialchars($r->getdescripcion()); ?></td>
                <td><?= htmlspecialchars($r->getid_automovil()); ?></td>
                <td><?= htmlspecialchars($r->getid_usuario()); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="operacion" value="editar">
                        <input type="hidden" name="id_ventas" value="<?= $r->getid_ventas(); ?>">
                        <button type="submit">Editar</button>
                    </form>
                    <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar esta venta?');">
                        <input type="hidden" name="operacion" value="eliminar">
                        <input type="hidden" name="id_ventas" value="<?= $r->getid_ventas(); ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
