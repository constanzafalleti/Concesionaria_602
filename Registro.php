<?php
require_once 'Usuario.php';
require_once 'Usuario.model.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['Nombre'] ?? '');
    $apellido = trim($_POST['Apellido'] ?? '');
    $direccion = trim($_POST['Direccion'] ?? '');
    $correo = filter_input(INPUT_POST, 'Correo', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['Clave'] ?? '';

    if ($nombre && $apellido && $direccion && $correo && $clave) {
        $usuarioModel = new UsuarioModel();
        $usuario = new Usuario();

        $usuario->setnombre($nombre);
        $usuario->setapellido($apellido);
        $usuario->setdireccion($direccion);
        $usuario->setcorreo($correo);
        $usuario->setclave(password_hash($clave, PASSWORD_DEFAULT));
        $usuario->setid_cargo(2); // Cliente

        $usuarioModel->Registrar($usuario);
        $exito = "Registro exitoso. Ahora puedes <a href='login.php'>iniciar sesión</a>.";
    } else {
        $error = "Todos los campos son obligatorios y deben ser válidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="Css/Registro.css">
</head>
<body>
    <h1>Registro de Usuario</h1>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($exito): ?>
        <p style="color:green;"><?= $exito; ?></p>
    <?php else: ?>
        <form method="post" action="Registro.php">
            <label>Nombre:</label>
            <input type="text" name="Nombre" required><br>
            <label>Apellido:</label>
            <input type="text" name="Apellido" required><br>
            <label>Dirección:</label>
            <input type="text" name="Direccion" required><br>
            <label>Correo:</label>
            <input type="email" name="Correo" required><br>
            <label>Clave:</label>
            <input type="password" name="Clave" required><br>
            <button type="submit">Registrarse</button>
        </form>
    <?php endif; ?>
    <p>¿Ya tienes cuenta? <a href="Login.php">Inicia sesión aquí</a></p>
</body>
</html>
