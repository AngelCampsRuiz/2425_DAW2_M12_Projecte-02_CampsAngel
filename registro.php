<?php
session_start();
require_once('./php/conexion.php');

// Verificar sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Historial de Reservas</title>
</head>

<body>
    <!-- Barra de navegación -->
    <div class="container">
        <nav class="navegacion">
            <!-- Sección izquierda con el logo grande y el ícono adicional más pequeño -->
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
                <a href="./registro.php"><img src="./img/lbook.png" alt="Ícono adicional" class="navbar-icon"></a>
            </div>

            <!-- Título en el centro -->
            <div class="navbar-title">
                <h3>Historial de Reservas</h3>
            </div>

            <!-- Icono de logout a la derecha -->
            <div class="navbar-right" style="margin-right: 18px;">
                <a href="./menu.php"><img src="./img/atras.png" alt="Logout" class="navbar-icon"></a>
            </div>

            <div class="navbar-right">
                <a href="./salir.php"><img src="./img/logout.png" alt="Logout" class="navbar-icon"></a>
            </div>
        </nav>
    </div>
    <br>
    <!-- Contenido principal -->
    <div id="historial-container" class="container">
        <h2 id="titulo-historial" class="text-white">Historial de Reservas</h2>

        <!-- Formulario de filtros -->
        <form method="GET" action="registro.php" class="mt-3">
            <!-- Contenedor para los filtros y los botones -->
            <div class="d-flex flex-wrap align-items-center">
                <!-- Filtros (Desplegables) -->
                <div class="me-3">
                    <label for="usuario" class="text-white">Usuario:</label>
                    <select name="usuario" class="form-control form-control-sm" style="height: 40px; width: 200px;">
                        <option value="">Todos</option>
                        <?php
                        $query_usuarios = "SELECT id_usuario, nombre_user FROM tbl_usuarios";
                        $stmt_usuarios = $conexion->query($query_usuarios);
                        while ($usuario = $stmt_usuarios->fetch(PDO::FETCH_ASSOC)) {
                            $selected = isset($_GET['usuario']) && $_GET['usuario'] == $usuario['id_usuario'] ? 'selected' : '';
                            echo "<option value='{$usuario['id_usuario']}' $selected>{$usuario['nombre_user']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="me-3">
                    <label for="sala" class="text-white">Sala:</label>
                    <select name="sala" class="form-control form-control-sm" style="height: 40px; width: 200px;">
                        <option value="">Todas</option>
                        <?php
                        $query_salas = "SELECT id_sala, nombre_sala FROM tbl_salas";
                        $stmt_salas = $conexion->query($query_salas);
                        while ($sala = $stmt_salas->fetch(PDO::FETCH_ASSOC)) {
                            $selected = isset($_GET['sala']) && $_GET['sala'] == $sala['id_sala'] ? 'selected' : '';
                            echo "<option value='{$sala['id_sala']}' $selected>{$sala['nombre_sala']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="me-3">
                    <label for="mesa" class="text-white">Mesa:</label>
                    <select name="mesa" class="form-control form-control-sm" style="height: 40px; width: 200px;">
                        <option value="">Todas</option>
                        <?php
                        $query_mesas = "SELECT id_mesa, numero_mesa FROM tbl_mesas";
                        $stmt_mesas = $conexion->query($query_mesas);
                        while ($mesa = $stmt_mesas->fetch(PDO::FETCH_ASSOC)) {
                            $selected = isset($_GET['mesa']) && $_GET['mesa'] == $mesa['id_mesa'] ? 'selected' : '';
                            echo "<option value='{$mesa['id_mesa']}' $selected>{$mesa['numero_mesa']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="d-flex align-items-center mt-3">
                    <button type="submit" class="btn btn-primary btn-sm me-2" style="height: 40px; width: 200px; margin-top: 10px; margin-right: 10px; margin-bottom: 2px;">Filtrar</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.location.href='registro.php'" style="height: 40px; width: 200px; margin-top: 10px; margin-left: 7px;">Borrar Filtros</button>
                </div>
            </div>
        </form>

        <!-- Variables para los filtros -->
        <?php
        $usuario_filter = isset($_GET['usuario']) && !empty($_GET['usuario']) ? $_GET['usuario'] : '';
        $sala_filter = isset($_GET['sala']) && !empty($_GET['sala']) ? $_GET['sala'] : '';
        $mesa_filter = isset($_GET['mesa']) && !empty($_GET['mesa']) ? $_GET['mesa'] : '';
        ?>

        <!-- Consulta SQL para obtener el historial de reservas -->
        <?php
        $query_historial = "
            SELECT u.nombre_user, s.nombre_sala, m.numero_mesa, 
                   r.hora_inicio AS fecha_inicio,
                   r.hora_fin AS fecha_fin,
                   TIMESTAMPDIFF(MINUTE, r.hora_inicio, r.hora_fin) AS duracion
            FROM tbl_reservas r
            JOIN tbl_mesas m ON r.id_mesa = m.id_mesa
            JOIN tbl_salas s ON m.id_sala = s.id_sala
            LEFT JOIN tbl_usuarios u ON r.id_usuario = u.id_usuario";

        $filters = [];
        if ($usuario_filter) {
            $filters[] = "u.id_usuario = :usuario";
        }
        if ($sala_filter) {
            $filters[] = "s.id_sala = :sala";
        }
        if ($mesa_filter) {
            $filters[] = "m.id_mesa = :mesa";
        }

        if (!empty($filters)) {
            $query_historial .= " WHERE " . implode(" AND ", $filters);
        }

        $stmt_historial = $conexion->prepare($query_historial);

        if ($usuario_filter) {
            $stmt_historial->bindParam(':usuario', $usuario_filter, PDO::PARAM_INT);
        }
        if ($sala_filter) {
            $stmt_historial->bindParam(':sala', $sala_filter, PDO::PARAM_INT);
        }
        if ($mesa_filter) {
            $stmt_historial->bindParam(':mesa', $mesa_filter, PDO::PARAM_INT);
        }

        $stmt_historial->execute();
        ?>

        <!-- Mostrar resultados en tabla -->
        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Usuario</th>
                        <th>Sala</th>
                        <th>Número de Mesa</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Duración (minutos)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($reserva = $stmt_historial->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                        <td>{$reserva['nombre_user']}</td>
                        <td>{$reserva['nombre_sala']}</td>
                        <td>{$reserva['numero_mesa']}</td>
                        <td>{$reserva['fecha_inicio']}</td>
                        <td>{$reserva['fecha_fin']}</td>
                        <td>{$reserva['duracion']}</td>
                    </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="./js/sweetalert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>