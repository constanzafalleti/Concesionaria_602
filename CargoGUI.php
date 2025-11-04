<?php
require_once 'Cargo.php';
require_once 'Cargo.model.php';
session_start();

// Validación de sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit();
}

$cargoModel = new CargoModel();
$cargo = new Cargo(); // Objeto para el formulario

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operacion'])) {
    $operacion = $_POST['operacion'];
    $id_cargo = filter_input(INPUT_POST, 'id_cargo', FILTER_VALIDATE_INT);
    $tipo = trim($_POST['tipo'] ?? '');

    switch ($operacion) {
        case 'actualizar':
           
                $cargo->setid_cargo($id_cargo);
                $cargo->settipo($tipo);
                $cargoModel->Actualizar($cargo);
                header('Location: CargoGUI.php');
                exit();
            break;

        case 'registrar':
                $cargo->settipo($tipo);
                $cargoModel->Registrar($cargo);
                header('Location: CargoGUI.php');
                exit();
            break;

        case 'eliminar':
            $idEliminar = filter_input(INPUT_POST, 'id_cargo', FILTER_VALIDATE_INT);
            if ($idEliminar) {
                $cargoModel->Eliminar($idEliminar);
                header('Location: CargoGUI.php');
                exit();
            }
            break;

        case 'editar':
            $idEditar = filter_input(INPUT_POST, 'id_cargo', FILTER_VALIDATE_INT);
            if ($idEditar) {
                $cargo = $cargoModel->Obtener($idEditar);
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Cargos</title>
    <link rel="stylesheet" href="Css/Crud.css">
</head>
<body>

    <div class="container">
        <h1>Administración de Cargos</h1>
        <div class="form-container">
    
            <!-- FORMULARIO ADMINISTRACION CARGOS -->
            <form action="CargoGUI.php" method="post">
                <input type="hidden" name="operacion" value="<?= ($cargo->getid_cargo() > 0) ? 'actualizar' : 'registrar'; ?>">
                <input type="hidden" name="id_cargo" value="<?= htmlspecialchars($cargo->getid_cargo()); ?>">

                <label for="name">Cargo:</label>
                <input type="text" name="tipo" required value="<?= htmlspecialchars($cargo->gettipo() ?? ''); ?>"><br>

                <button type="submit">
                    <?= ($cargo->getid_cargo() > 0) ? 'Actualizar' : 'Registrar'; ?>
                </button>
            </form>
        </div>

        <div class="list-container">
            <!-- Tabla de Cargos -->
            <h2>Listado de Cargos</h2>
            <table border="1">
                <tr>
                    <th>Cargo</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($cargoModel->ListarTodos() as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r->gettipo()); ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="operacion" value="editar">
                                <input type="hidden" name="id_cargo" value="<?= $r->getid_cargo(); ?>">
                                <button type="submit">Editar</button>
                            </form>
                            <form method="post" style="display:inline;" onsubmit="return confirm('¿Desea eliminar este cargo?');">
                                <input type="hidden" name="operacion" value="eliminar">
                                <input type="hidden" name="id_cargo" value="<?= $r->getid_cargo(); ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
