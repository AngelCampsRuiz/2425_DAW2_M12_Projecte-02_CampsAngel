<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$id_usuario = $_GET['id'];
$stmt = $conexion->prepare("SELECT * FROM tbl_usuarios WHERE id_usuario = :id");
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_user = $_POST['nombre_user'];
    $rol = $_POST['rol'];

    $stmt = $conexion->prepare("UPDATE tbl_usuarios SET nombre_user = :nombre_user, rol = :rol WHERE id_usuario = :id");
    $stmt->bindParam(':nombre_user', $nombre_user);
    $stmt->bindParam(':rol', $rol);
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();

    header('Location: admin_panel.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Editar Usuario</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-white">Editar Usuario</h2>
        <form method="POST" action="editar_usuario.php?id=<?php echo $id_usuario; ?>">
            <div class="mb-3">
                <label for="nombre_user" class="form-label text-white">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nombre_user" name="nombre_user" value="<?php echo $usuario['nombre_user']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label text-white">Rol</label>
                <select class="form-control" id="rol" name="rol" required>
                    <option value="camarero" <?php echo $usuario['rol'] == 'camarero' ? 'selected' : ''; ?>>Camarero</option>
                    <option value="gerente" <?php echo $usuario['rol'] == 'gerente' ? 'selected' : ''; ?>>Gerente</option>
                    <option value="mantenimiento" <?php echo $usuario['rol'] == 'mantenimiento' ? 'selected' : ''; ?>>Mantenimiento</option>
                    <option value="administrador" <?php echo $usuario['rol'] == 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html> 