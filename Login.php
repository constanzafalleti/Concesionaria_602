<?php
session_start();
require_once 'Usuario.php';
require_once 'Usuario.model.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Correo = filter_input(INPUT_POST, 'Correo', FILTER_VALIDATE_EMAIL);
    $Clave = $_POST['Clave'] ?? '';

    if ($Correo && $Clave) {
        $usuarioModel = new UsuarioModel();
        $usuario = new Usuario();
        $usuario->setCorreo($Correo);
        $usuario->setClave($Clave);

        $usuarioValido = $usuarioModel->Login($usuario);

        if ($usuarioValido) {
            $_SESSION['idUsuario'] = $usuarioValido->getidUsuario();
            $_SESSION['Nombre'] = $usuarioValido->getNombre();
            $_SESSION['id_cargo'] = $usuarioValido->getid_cargo();

            // SEGUN EL CARGO QUE OCUPA
            switch ($_SESSION['id_cargo']) {
                case 1:
                    header("Location: Administrador.php");
                    break;
                case 2:
                    header("Location: Cliente.php");
                    break;
                default:
                    header("Location: UsuarioGUI.php");
            }
            exit();
        } else {
            $error = "Correo o clave incorrectos.";
        }
    } else {
        $error = "Por favor, ingrese un correo válido y su clave.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="Css/Login.css">
</head>
<body>
    
    <div>
        <div>
            <h1>Iniciar Sesión</h1>
                <?php if ($error): ?>
                    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
                <?php endif; ?>
            <form method="post" action="Login.php">
                <label>Correo:</label>
                <input type="email" name="Correo" required><br>
                <label>Clave:</label>
                <input type="password" name="Clave" required><br>
                <button type="submit">Ingresar</button>
            </form>
        </div>
    </div>
    
    <p>¿No tienes cuenta? <a href="Registro.php">Regístrate aquí</a></p>
</body>
</html>
