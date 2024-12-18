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
        /* Estilos para dispositivos móviles */
        @media (max-width: 768px) {
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px; /* Reducir tamaño de fuente */
            }

            table th, table td {
                padding: 4px; /* Reducir padding */
                text-align: left;
            }

            .btn {
                display: block;
                width: 100%;
                margin-bottom: 5px; /* Espacio entre botones */
                font-size: 10px; /* Reducir tamaño de fuente de los botones */
                padding: 4px; /* Reducir padding de los botones */
            }
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
                        <button data-url="eliminar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-danger btn-sm eliminar-item">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="añadir_usuario.php" class="btn btn-primary btn-sm mb-4">Añadir Usuario</a>
    </div>

    <div class="container crud-container">
        <h2 class="text-white">Gestión de Salas</h2>
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
                        <button data-url="eliminar_sala.php?id=<?php echo $sala['id_sala']; ?>" class="btn btn-danger btn-sm eliminar-item">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
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
                        <button data-url="eliminar_mesa.php?id=<?php echo $mesa['id_mesa']; ?>" class="btn btn-danger btn-sm eliminar-item">Eliminar</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="añadir_mesa.php" class="btn btn-primary btn-sm">Añadir Mesa</a>
    </div>

    <script src="./js/sweetalert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.eliminar-item').forEach(button => {
                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'No, cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>
