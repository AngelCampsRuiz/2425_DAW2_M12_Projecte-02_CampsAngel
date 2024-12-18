<?php
session_start();
require_once('./php/conexion.php');

// Verificar si la variable de sesión 'Usuario' está configurada
if (!isset($_SESSION['Usuario'])) {
    $_SESSION['Usuario'] = 'Invitado'; // Valor por defecto si no está configurada
}
// Verificar si el SweetAlert ya se mostró
if (!isset($_SESSION['sweetalert_mostrado'])) {
    $_SESSION['sweetalert_mostrado'] = false;
}

try {
    $usuario = $_SESSION['usuario'];
    $query_usuario = "SELECT id_usuario FROM tbl_usuarios WHERE nombre_user = :usuario";
    $stmt_usuario = $conexion->prepare($query_usuario);
    $stmt_usuario->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt_usuario->execute();
    $id_usuario = $stmt_usuario->fetchColumn();
    $_SESSION['id_usuario'] = $id_usuario;
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

// Obtener el rol del usuario
try {
    $query_rol = "SELECT rol FROM tbl_usuarios WHERE nombre_user = :usuario";
    $stmt_rol = $conexion->prepare($query_rol);
    $stmt_rol->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt_rol->execute();
    $rol_usuario = $stmt_rol->fetchColumn();
    $_SESSION['rol_usuario'] = $rol_usuario;
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

// Obtener los tipos de salas únicos
try {
    $query_tipos_salas = "SELECT DISTINCT tipo_sala FROM tbl_salas";
    $stmt_tipos_salas = $conexion->prepare($query_tipos_salas);
    $stmt_tipos_salas->execute();
    $tipos_salas = $stmt_tipos_salas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

// Función para buscar la imagen con cualquier extensión
function buscarImagen($nombre) {
    $extensiones = ['jpg', 'jpeg', 'png', 'webp'];
    foreach ($extensiones as $ext) {
        $ruta = "./img/" . str_replace(' ', '_', $nombre) . "." . $ext;
        if (file_exists($ruta)) {
            return $ruta;
        }
    }
    return "./img/default.jpg"; // Imagen por defecto si no se encuentra ninguna
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body data-usuario="<?php echo htmlspecialchars($_SESSION['Usuario'], ENT_QUOTES, 'UTF-8'); ?>" data-sweetalert="<?php echo $_SESSION['sweetalert_mostrado'] ? 'true' : 'false'; ?>">
    <div class="container">
        <nav class="navegacion">
            <!-- Sección izquierda con el logo grande y el ícono adicional más pequeño -->
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
                <a href="./registro.php"><img src="./img/lbook.png" alt="Ícono adicional" class="navbar-icon"></a>
            </div>

            <!-- Título en el centro -->
            <div class="navbar-title">
                <h3>Bienvenido <?php if (isset($_SESSION['usuario'])) {
                                    echo $_SESSION['usuario'];
                                } ?></h3>
            </div>

            <!-- Botón solo para administradores -->
            <?php if ($_SESSION['rol_usuario'] === 'administrador') : ?>
                <div class="navbar-admin">
                    <a href="./admin_panel.php">
                        <img src="./img/admin.png" alt="Admin" style="width: 40px; height: 40px; margin-right: 15px;">
                    </a>
                </div>
            <?php endif; ?>

            <!-- Icono de logout a la derecha -->
            <div class="navbar-right">
                <a href="./salir.php"><img src="./img/logout.png" alt="Logout" class="navbar-icon"></a>
            </div>
        </nav>
    </div>
    <!------------FIN BARRA DE NAVEGACION--------------------->
    <div class="container-menu">
        <section>
            <?php foreach ($tipos_salas as $tipo_sala): ?>
                <a class="image-container" href="./seleccionar_sala.php?categoria=<?php echo urlencode($tipo_sala['tipo_sala']); ?>">
                    <img src="<?php echo buscarImagen($tipo_sala['tipo_sala']); ?>" alt="" id="<?php echo strtolower(str_replace(' ', '_', $tipo_sala['tipo_sala'])); ?>">
                    <div class="text-overlay"><?php echo htmlspecialchars($tipo_sala['tipo_sala']); ?></div>
                </a>
            <?php endforeach; ?>
        </section>
    </div>

    <script src="./js/sweetalert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>