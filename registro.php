<?php
session_start();
require_once('./php/conexion.php');

// Verificar sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}

// Obtener lista de personas (usuarios) para el select
$query_personas = "SELECT id_usuario, nombre_user FROM tbl_usuarios";
$stmt_personas = $conexion->query($query_personas);
$personas = $stmt_personas->fetchAll(PDO::FETCH_ASSOC);
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
    <style>
        /* Estilos para dispositivos móviles */
        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 12px; /* Reduce el tamaño de la fuente para que quepa mejor */
            }

            table, th, td {
                padding: 2px; /* Reduce el padding para ahorrar espacio */
                border-collapse: collapse; /* Asegura que las celdas estén más juntas */
            }

            th, td {
                text-align: left; /* Alinea el texto a la izquierda para una mejor lectura */
            }

            .form-control {
                width: 100%; /* Ajusta los controles de formulario para que ocupen todo el ancho */
                margin-bottom: 10px; /* Añade un espacio debajo de cada control para evitar que se peguen */
            }

            .btn {
                width: 100%; /* Ajusta los botones para que ocupen todo el ancho */
            }
        }
    </style>
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
                        <?php foreach ($personas as $persona): ?>
                            <option value="<?php echo $persona['id_usuario']; ?>" <?php echo (isset($_GET['usuario']) && $_GET['usuario'] == $persona['id_usuario']) ? 'selected' : ''; ?>>
                                <?php echo $persona['nombre_user']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="me-3">
                    <label for="nombre_persona" class="text-white">Nombre de la Persona:</label>
                    <input type="text" name="nombre_persona" class="form-control form-control-sm" style="height: 40px; width: 200px;" value="<?php echo isset($_GET['nombre_persona']) ? htmlspecialchars($_GET['nombre_persona']) : ''; ?>">
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

                <div class="me-3">
                    <label for="fecha" class="text-white">Fecha:</label>
                    <input type="date" name="fecha" class="form-control form-control-sm" style="height: 40px; width: 200px;" value="<?php echo isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : ''; ?>">
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
        $nombre_persona_filter = isset($_GET['nombre_persona']) && !empty($_GET['nombre_persona']) ? $_GET['nombre_persona'] : '';
        $sala_filter = isset($_GET['sala']) && !empty($_GET['sala']) ? $_GET['sala'] : '';
        $mesa_filter = isset($_GET['mesa']) && !empty($_GET['mesa']) ? $_GET['mesa'] : '';
        $fecha_filter = isset($_GET['fecha']) && !empty($_GET['fecha']) ? $_GET['fecha'] : '';

        // Consulta SQL para obtener el historial de reservas
        $query_historial = "
            SELECT u.nombre_user, s.nombre_sala, m.numero_mesa, 
                   r.nombre_persona, r.fecha, r.hora_inicio AS fecha_inicio,
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
        if ($nombre_persona_filter) {
            $filters[] = "r.nombre_persona LIKE :nombre_persona";
        }
        if ($sala_filter) {
            $filters[] = "s.id_sala = :sala";
        }
        if ($mesa_filter) {
            $filters[] = "m.id_mesa = :mesa";
        }
        if ($fecha_filter) {
            $filters[] = "r.fecha = :fecha";
        }

        if (!empty($filters)) {
            $query_historial .= " WHERE " . implode(" AND ", $filters);
        }

        $stmt_historial = $conexion->prepare($query_historial);

        if ($usuario_filter) {
            $stmt_historial->bindParam(':usuario', $usuario_filter, PDO::PARAM_INT);
        }
        if ($nombre_persona_filter) {
            $nombre_persona_filter = "%$nombre_persona_filter%";
            $stmt_historial->bindParam(':nombre_persona', $nombre_persona_filter, PDO::PARAM_STR);
        }
        if ($sala_filter) {
            $stmt_historial->bindParam(':sala', $sala_filter, PDO::PARAM_INT);
        }
        if ($mesa_filter) {
            $stmt_historial->bindParam(':mesa', $mesa_filter, PDO::PARAM_INT);
        }
        if ($fecha_filter) {
            $stmt_historial->bindParam(':fecha', $fecha_filter, PDO::PARAM_STR);
        }

        $stmt_historial->execute();
        ?>

        <!-- Mostrar resultados en tabla -->
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Sala</th>
                        <th>Número de Mesa</th>
                        <th>Nombre de la Persona</th>
                        <th>Fecha</th>
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
                        <td>{$reserva['nombre_persona']}</td>
                        <td>{$reserva['fecha']}</td>
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