

<?php
session_start();
require_once 'Usuario.php';
require_once 'Usuario.model.php';

if (!isset($_SESSION['idUsuario']) || $_SESSION['id_cargo'] != 2) {
    header('Location: Login.php');
    exit();
}

$usuarioModel = new UsuarioModel();
$usuario = $usuarioModel->Obtener($_SESSION['idUsuario']);
$mensaje = '';

// Guardar las fotos
define('UPLOAD_DIR', 'uploads/perfiles/');

// Crear carpeta si no existe
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $direccion = trim($_POST['direccion']);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['clave'] ?? '';

    if ($nombre && $apellido && $direccion && $correo) {
        $usuario->setnombre($nombre);
        $usuario->setapellido($apellido);
        $usuario->setdireccion($direccion);
        $usuario->setcorreo($correo);

        if (!empty($clave)) {
            $usuario->setclave(password_hash($clave, PASSWORD_DEFAULT));
        }

        $usuarioModel->Actualizar($usuario);
        $_SESSION['nombre'] = $usuario->getnombre();
        $mensaje = $mensaje ? $mensaje : 'Datos actualizados correctamente.';
    } else {
        $mensaje = 'Todos los campos excepto la contraseña obligatorios.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="Css/perfil.css">
</head>
<body>
    <h1>Mi Perfil - Cliente</h1>

    <?php if ($mensaje): ?>
        <p style="color:<?= strpos($mensaje, 'Error') !== false ? 'red' : 'green'; ?>">
            <?= htmlspecialchars($mensaje); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="Perfil.php" enctype="multipart/form-data">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required value="<?= htmlspecialchars($usuario->getnombre()); ?>"><br>

        <label>Apellido:</label><br>
        <input type="text" name="apellido" required value="<?= htmlspecialchars($usuario->getapellido()); ?>"><br>

        <label>Dirección:</label><br>
        <input type="text" name="direccion" required value="<?= htmlspecialchars($usuario->getdireccion()); ?>"><br>

        <label>Correo:</label><br>
        <input type="email" name="correo" required value="<?= htmlspecialchars($usuario->getcorreo()); ?>"><br>

        <label>Contraseña (dejar vacío para no cambiar):</label><br>
        <input type="password" name="clave"><br>

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="Cliente.php">← Volver al panel</a> |
    <a href="Cerrar_Sesion.php">Cerrar sesión</a>
</body>
</html>


