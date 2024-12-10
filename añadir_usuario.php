<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_user = $_POST['nombre_user'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];

    // Verificar si el nombre de usuario ya existe
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM tbl_usuarios WHERE nombre_user = :nombre_user");
    $stmt->bindParam(':nombre_user', $nombre_user);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $error = "El nombre de usuario ya existe.";
    }

    // Si no hay errores, insertar el usuario
    if (empty($error)) {
        $contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $conexion->prepare("INSERT INTO tbl_usuarios (nombre_user, contrasena, rol) VALUES (:nombre_user, :contrasena, :rol)");
        $stmt->bindParam(':nombre_user', $nombre_user);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();

        header('Location: admin_panel.php?message=Usuario añadido correctamente');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Añadir Usuario</title>
</head>

<body>
    <div class="container">
        <nav class="navegacion">
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
            </div>
            <div class="navbar-title">
                <h3>Añadir Usuario</h3>
            </div>
            <div class="navbar-right" style="margin-right: 18px;">
                <a href="./admin_panel.php"><img src="./img/atras.png" alt="Logout" class="navbar-icon"></a>
            </div>
            <div class="navbar-right">
                <a href="./salir.php"><img src="./img/logout.png" alt="Logout" class="navbar-icon"></a>
            </div>
        </nav>
    </div>

    <div class="container crud-container">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="añadir_usuario.php" class="flex-form">
            <div class="mb-3">
                <label for="nombre_user" class="form-label text-white">Nombre de Usuario</label>
                <input type="text" class="form-control custom-input" id="nombre_user" name="nombre_user" required>
                <div id="nombre_user_error" class="error-message" style="color: red;"></div>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label text-white">Contraseña</label>
                <input type="password" class="form-control custom-input" id="contrasena" name="contrasena" required>
                <div id="contrasena_error" class="error-message" style="color: red;"></div>
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label text-white">Rol</label>
                <select class="form-control custom-input" id="rol" name="rol" required>
                    <option value="camarero">Camarero</option>
                    <option value="gerente">Gerente</option>
                    <option value="mantenimiento">Mantenimiento</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary custom-btn">Añadir</button>
        </form>
    </div>
</body>
<script src="./js/validacion_usuario.js"></script>
</html>