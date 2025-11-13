<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['id_cargo'] != 1) {
    header('Location: Login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <link rel="stylesheet" href="css/paneladmin.css">
</head>
<body>
    <div>
        <aside>
            <div>
                
        <main>
            <h1>Panel de Administración</h1>
            <p>Este es el panel de control para administradores.</p>
        </main>
                <p> Bienvenido: </p><span><?= htmlspecialchars($_SESSION['Nombre']); ?></span>
                
            </div>
            <nav>
                <ul>
                    <li><a href="UsuarioGUI.php"> Administracion de Usuarios </a></li>
                    <li><a href="CargoGUI.php"> Administracion de Cargos </a></li>
                    <li><a href="ProductoGUI.php"> Administracion de Productos </a></li>
                    <li><a href="VentaGUI.php"> Administracionn de Ventas </a></li>
                     <li><a href="VendedoresGUI.php"> Administracionn de Vendedores </a></li>
                    <li><a href="Cerrar_Sesion.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </aside>

    </div>
</body>
</html>

