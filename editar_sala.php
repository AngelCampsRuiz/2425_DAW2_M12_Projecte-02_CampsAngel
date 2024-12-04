<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$id_sala = $_GET['id'];
$stmt = $conexion->prepare("SELECT * FROM tbl_salas WHERE id_sala = :id");
$stmt->bindParam(':id', $id_sala);
$stmt->execute();
$sala = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sala = $_POST['nombre_sala'];
    $tipo_sala = $_POST['tipo_sala'];
    $capacidad = $_POST['capacidad'];

    $stmt = $conexion->prepare("UPDATE tbl_salas SET nombre_sala = :nombre_sala, tipo_sala = :tipo_sala, capacidad = :capacidad WHERE id_sala = :id");
    $stmt->bindParam(':nombre_sala', $nombre_sala);
    $stmt->bindParam(':tipo_sala', $tipo_sala);
    $stmt->bindParam(':capacidad', $capacidad);
    $stmt->bindParam(':id', $id_sala);
    $stmt->execute();

    header('Location: admin_panel.php?message=Sala editada correctamente');
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
    <title>Editar Sala</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-white">Editar Sala</h2>
        <form method="POST" action="editar_sala.php?id=<?php echo $id_sala; ?>">
            <div class="mb-3">
                <label for="nombre_sala" class="form-label text-white">Nombre de Sala</label>
                <input type="text" class="form-control" id="nombre_sala" name="nombre_sala" value="<?php echo $sala['nombre_sala']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo_sala" class="form-label text-white">Tipo de Sala</label>
                <input type="text" class="form-control" id="tipo_sala" name="tipo_sala" value="<?php echo $sala['tipo_sala']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacidad" class="form-label text-white">Capacidad</label>
                <input type="number" class="form-control" id="capacidad" name="capacidad" value="<?php echo $sala['capacidad']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</body>
</html> 