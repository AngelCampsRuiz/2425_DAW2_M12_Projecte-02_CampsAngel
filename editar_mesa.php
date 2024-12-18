<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$id_mesa = $_GET['id'];
$stmt = $conexion->prepare("SELECT * FROM tbl_mesas WHERE id_mesa = :id");
$stmt->bindParam(':id', $id_mesa);
$stmt->execute();
$mesa = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_mesa = $_POST['numero_mesa'];
    $id_sala = $_POST['id_sala'];
    $numero_sillas = $_POST['numero_sillas'];

    // Verificar si el número de mesa ya existe para otra mesa
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM tbl_mesas WHERE numero_mesa = :numero_mesa AND id_mesa != :id");
    $stmt->bindParam(':numero_mesa', $numero_mesa);
    $stmt->bindParam(':id', $id_mesa);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $error = "El número de mesa ya existe.";
    }

    // Si no hay errores, actualizar la mesa
    if (empty($error)) {
        $stmt = $conexion->prepare("UPDATE tbl_mesas SET numero_mesa = :numero_mesa, id_sala = :id_sala, numero_sillas = :numero_sillas WHERE id_mesa = :id");
        $stmt->bindParam(':numero_mesa', $numero_mesa);
        $stmt->bindParam(':id_sala', $id_sala);
        $stmt->bindParam(':numero_sillas', $numero_sillas);
        $stmt->bindParam(':id', $id_mesa);
        $stmt->execute();

        header('Location: admin_panel.php?message=Mesa editada correctamente');
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
    <title>Editar Mesa</title>
</head>

<body>
    <div class="container">
        <nav class="navegacion">
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
            </div>
            <div class="navbar-title">
                <h3>Editar Mesa</h3>
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
        <form method="POST" action="editar_mesa.php?id=<?php echo $id_mesa; ?>" class="flex-form">
            <div class="mb-3">
                <label for="numero_mesa" class="form-label text-white">Número de Mesa</label>
                <input type="number" class="form-control custom-input" id="numero_mesa" name="numero_mesa" value="<?php echo $mesa['numero_mesa']; ?>" required>
                <div id="numero_mesa_error" class="error-message" style="color: red;"></div>
            </div>
            <div class="mb-3">
                <label for="id_sala" class="form-label text-white">Sala</label>
                <select class="form-control custom-input" id="id_sala" name="id_sala" required>
                    <?php
                    $stmt = $conexion->query("SELECT id_sala, nombre_sala FROM tbl_salas");
                    while ($sala = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = $sala['id_sala'] == $mesa['id_sala'] ? 'selected' : '';
                        echo "<option value='{$sala['id_sala']}' $selected>{$sala['nombre_sala']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="numero_sillas" class="form-label text-white">Número de Sillas</label>
                <input type="number" class="form-control custom-input" id="numero_sillas" name="numero_sillas" value="<?php echo $mesa['numero_sillas']; ?>" required>
                <div id="numero_sillas_error" class="error-message" style="color: red;"></div>
            </div>
            <button type="submit" class="btn btn-primary custom-btn">Guardar</button>
        </form>
    </div>
    <script src="./js/validacion_mesa.js"></script>
</body>

</html>