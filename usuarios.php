<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol'] !== 'administrador') {
    header("Location: index.php?error=acceso_denegado");
    exit();
}

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_usuario'])) {
        // Crear usuario
        $nombre = $_POST['nombre'];
        $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
        $rol = $_POST['rol'];

        $sql = "INSERT INTO tbl_usuarios (nombre_user, contrasena, rol) VALUES (:nombre, :contrasena, :rol)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->bindParam(':rol', $rol);
        $stmt->execute();
    }
    // Similarmente, implementar editar y eliminar
}

// Obtener lista de usuarios
$sql = "SELECT * FROM tbl_usuarios";
$stmt = $conexion->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1>Gestión de Usuarios</h1>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <select name="rol">
            <option value="camarero">Camarero</option>
            <option value="gerente">Gerente</option>
            <option value="mantenimiento">Mantenimiento</option>
            <option value="administrador">Administrador</option>
        </select>
        <button type="submit" name="crear_usuario">Crear Usuario</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['nombre_user']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                    <td>
                        <!-- Botones para editar y eliminar -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html> 