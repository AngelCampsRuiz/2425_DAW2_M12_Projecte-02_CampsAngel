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
    $nombre_sala = $_POST['nombre_sala'];
    $tipo_sala = $_POST['tipo_sala'];
    $capacidad = $_POST['capacidad'];

    // Verificar si el nombre de sala ya existe
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM tbl_salas WHERE nombre_sala = :nombre_sala");
    $stmt->bindParam(':nombre_sala', $nombre_sala);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $error = "El nombre de la sala ya existe.";
    }

    // Si no hay errores, insertar la sala
    if (empty($error)) {
        // Manejo de la carga de imágenes
        $target_dir = "./img/";

        // Obtener la extensión del archivo
        $tipo_sala_extension = pathinfo($_FILES["tipo_sala_image"]["name"], PATHINFO_EXTENSION);
        $nombre_sala_extension = pathinfo($_FILES["nombre_sala_image"]["name"], PATHINFO_EXTENSION);

        // Renombrar las imágenes según los inputs
        $tipo_sala_image_name = str_replace(' ', '_', $tipo_sala) . "." . $tipo_sala_extension;
        $nombre_sala_image_name = str_replace(' ', '_', $nombre_sala) . "." . $nombre_sala_extension;

        $tipo_sala_image = $target_dir . $tipo_sala_image_name;
        $nombre_sala_image = $target_dir . $nombre_sala_image_name;

        // Mover las imágenes a la carpeta de destino
        if (move_uploaded_file($_FILES["tipo_sala_image"]["tmp_name"], $tipo_sala_image)) {
            echo "Imagen de tipo de sala guardada correctamente.";
        } else {
            echo "Error al guardar la imagen de tipo de sala.";
        }

        if (move_uploaded_file($_FILES["nombre_sala_image"]["tmp_name"], $nombre_sala_image)) {
            echo "Imagen de nombre de sala guardada correctamente.";
        } else {
            echo "Error al guardar la imagen de nombre de sala.";
        }

        // Insertar en la base de datos
        $stmt = $conexion->prepare("INSERT INTO tbl_salas (nombre_sala, tipo_sala, capacidad) VALUES (:nombre_sala, :tipo_sala, :capacidad)");
        $stmt->bindParam(':nombre_sala', $nombre_sala);
        $stmt->bindParam(':tipo_sala', $tipo_sala);
        $stmt->bindParam(':capacidad', $capacidad);
        $stmt->execute();

        header('Location: admin_panel.php?message=Sala añadida correctamente');
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
    <title>Añadir Sala</title>
</head>

<body>
    <div class="container">
        <nav class="navegacion">
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
            </div>
            <div class="navbar-title">
                <h3>Añadir Sala</h3>
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
        <form method="POST" action="añadir_sala.php" enctype="multipart/form-data" class="flex-form">
            <div class="mb-3">
                <label for="nombre_sala" class="form-label text-white">Nombre de Sala</label>
                <input type="text" class="form-control small-input" id="nombre_sala" name="nombre_sala" required>
                <div id="nombre_sala_error" class="error-message" style="color: red;"></div>
            </div>
            <div class="mb-3">
                <label for="nombre_sala_image" class="form-label text-white">Imagen de Nombre de Sala</label>
                <input type="file" class="form-control small-input" id="nombre_sala_image" name="nombre_sala_image" required>
            </div>
            <div class="mb-3">
                <label for="tipo_sala" class="form-label text-white">Tipo de Sala</label>
                <input type="text" class="form-control small-input" id="tipo_sala" name="tipo_sala" required>
                <div id="tipo_sala_error" class="error-message" style="color: red;"></div>
            </div>
            <div class="mb-3">
                <label for="tipo_sala_image" class="form-label text-white">Imagen de Tipo de Sala</label>
                <input type="file" class="form-control small-input" id="tipo_sala_image" name="tipo_sala_image" required>
            </div>
            <div class="mb-3">
                <label for="capacidad" class="form-label text-white">Capacidad</label>
                <input type="number" class="form-control small-input" id="capacidad" name="capacidad" required>
                <div id="capacidad_error" class="error-message" style="color: red;"></div>
            </div>
            <button type="submit" class="btn btn-primary custom-btn">Añadir</button>
        </form>
    </div>
    <script src="./js/validacion_sala.js"></script>
</body>

</html>