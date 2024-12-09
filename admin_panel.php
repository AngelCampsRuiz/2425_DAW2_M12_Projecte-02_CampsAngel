<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

// Funciones para manejar los CRUDs
function obtenerUsuarios($conexion) {
    $stmt = $conexion->query("SELECT * FROM tbl_usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerSalas($conexion) {
    $stmt = $conexion->query("SELECT * FROM tbl_salas");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Aquí puedes añadir funciones para añadir, editar y eliminar usuarios y salas

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Panel de Administración</title>
    <style>
        .crud-container {
            margin-top: 0 !important; /* Elimina el margen superior */
            padding-top: 0 !important; /* Elimina el relleno superior si es necesario */
            margin-bottom: 0 !important; /* Elimina el margen inferior */
        }
    </style>
</head>

<body>
    <?php if (isset($_GET['message'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '<?php echo htmlspecialchars($_GET['message']); ?>',
            confirmButtonText: 'OK'
        });
    </script>
    <?php endif; ?>

    <div class="container">
        <nav class="navegacion">
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
            </div>
            <div class="navbar-title">
                <h3>Panel de Administración</h3>
            </div>
            <div class="navbar-right">
                <a href="./salir.php"><img src="./img/logout.png" alt="Logout" class="navbar-icon"></a>
            </div>
        </nav>
    </div>

    <div class="container crud-container">
        <h2 class="text-white">Gestión de Usuarios</h2>
        <!-- Tabla de usuarios -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (obtenerUsuarios($conexion) as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['id_usuario']; ?></td>
                    <td><?php echo $usuario['nombre_user']; ?></td>
                    <td><?php echo $usuario['rol']; ?></td>
                    <td>
                        <!-- Botones para editar y eliminar -->
                        <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="añadir_usuario.php" class="btn btn-primary btn-sm mb-4">Añadir Usuario</a>
    </div>

    <div class="container crud-container">
        <h2 class="text-white">Gestión de Recursos</h2>
        <!-- Tabla de salas -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de Sala</th>
                    <th>Tipo de Sala</th>
                    <th>Capacidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (obtenerSalas($conexion) as $sala): ?>
                <tr>
                    <td><?php echo $sala['id_sala']; ?></td>
                    <td><?php echo $sala['nombre_sala']; ?></td>
                    <td><?php echo $sala['tipo_sala']; ?></td>
                    <td><?php echo $sala['capacidad']; ?></td>
                    <td>
                        <!-- Botones para editar y eliminar -->
                        <a href="editar_sala.php?id=<?php echo $sala['id_sala']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_sala.php?id=<?php echo $sala['id_sala']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="añadir_sala.php" class="btn btn-primary btn-sm">Añadir Sala</a>
    </div>

    <div class="container crud-container">
        <h2 class="text-white">Gestión de Mesas</h2>
        <!-- Tabla de mesas -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Mesa</th>
                    <th>Sala</th>
                    <th>Número de Sillas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conexion->query("SELECT m.id_mesa, m.numero_mesa, s.nombre_sala, m.numero_sillas FROM tbl_mesas m JOIN tbl_salas s ON m.id_sala = s.id_sala");
                while ($mesa = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $mesa['id_mesa']; ?></td>
                    <td><?php echo $mesa['numero_mesa']; ?></td>
                    <td><?php echo $mesa['nombre_sala']; ?></td>
                    <td><?php echo $mesa['numero_sillas']; ?></td>
                    <td>
                        <a href="editar_mesa.php?id=<?php echo $mesa['id_mesa']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_mesa.php?id=<?php echo $mesa['id_mesa']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="añadir_mesa.php" class="btn btn-primary btn-sm">Añadir Mesa</a>
    </div>

    <script src="./js/sweetalert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>
