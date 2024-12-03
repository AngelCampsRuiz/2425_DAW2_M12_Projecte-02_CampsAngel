<?php
session_start();
date_default_timezone_set('Europe/Madrid');
require_once('./php/conexion.php');

// Verificación de sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}
$id_sala = isset($_GET['id_sala']) ? $_GET['id_sala'] : 0;

try {
    if ($id_sala === 0) {
        throw new Exception("ID de sala no válido.");
    }

    $query_nombre_sala = "SELECT nombre_sala FROM tbl_salas WHERE id_sala = :id_sala";
    $stmt_nombre_sala = $conexion->prepare($query_nombre_sala);
    $stmt_nombre_sala->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt_nombre_sala->execute();
    $nombre_sala = $stmt_nombre_sala->fetchColumn();

    if (!$nombre_sala) {
        throw new Exception("No se encontró ninguna sala con el ID especificado.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
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

<body data-usuario="<?php echo htmlspecialchars($_SESSION['Usuario'], ENT_QUOTES, 'UTF-8'); ?>" data-sweetalert="<?php echo $_SESSION['sweetalert_mostrado'] ? 'true' : 'false'; ?>" data-mesa-sweetalert="<?php echo isset($_SESSION['mesa_sweetalert']) && $_SESSION['mesa_sweetalert'] ? 'true' : 'false'; ?>">
    <div class="container">
        <nav class="navegacion">
            <!-- Sección izquierda con el logo grande y el ícono adicional más pequeño -->
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
                <a href="./registro.php"><img src="./img/lbook.png" alt="Ícono adicional" class="navbar-icon"></a>
            </div>

            <!-- Título en el centro -->
            <div class="navbar-title">
                <h3><?php echo htmlspecialchars($nombre_sala); ?></h3>
            </div>

            <div class="navbar-right" style="margin-right: 18px;">
                <a href="./menu.php"><img src="./img/atras.png" alt="Logout" class="navbar-icon"></a>
            </div>

            <!-- Icono de logout a la derecha -->
            <div class="navbar-right">
                <a href="./salir.php"><img src="./img/logout.png" alt="Logout" class="navbar-icon"></a>
            </div>
        </nav>

        <div class='mesas-container'>
            <?php

            // Inicia la salida de buffer para evitar errores de encabezados ya enviados
            ob_start();
            $conexion->beginTransaction();
            try {
                $usuario = $_SESSION['usuario'];

                $query_usuario = "SELECT id_usuario FROM tbl_usuarios WHERE nombre_user = :usuario";
                $stmt_usuario = $conexion->prepare($query_usuario);
                $stmt_usuario->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                $stmt_usuario->execute();
                $id_usuario = $stmt_usuario->fetchColumn();

                // Verificación de parámetros GET
                if (isset($_GET['categoria']) && isset($_GET['id_sala'])) {
                    $categoria_seleccionada = $_GET['categoria'];
                    $id_sala = $_GET['id_sala'];

                    $query_salas = "SELECT * FROM tbl_salas WHERE tipo_sala = :categoria AND id_sala = :id_sala";
                    $stmt_salas = $conexion->prepare($query_salas);
                    $stmt_salas->bindParam(':categoria', $categoria_seleccionada, PDO::PARAM_STR);
                    $stmt_salas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
                    $stmt_salas->execute();
                    $result_salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

                    if ($result_salas) {
                        $query_mesas = "SELECT * FROM tbl_mesas WHERE id_sala = :id_sala";
                        $stmt_mesas = $conexion->prepare($query_mesas);
                        $stmt_mesas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
                        $stmt_mesas->execute();
                        $result_mesas = $stmt_mesas->fetchAll(PDO::FETCH_ASSOC);

                        if ($result_mesas) {
                            foreach ($result_mesas as $mesa) {
                                $estado_actual = htmlspecialchars($mesa['estado']);
                                $estado_opuesto = $estado_actual === 'libre' ? 'Ocupar' : 'Liberar';

                                // Verificar si la mesa está ocupada y quién la ocupa
                                $mesa_id = $mesa['id_mesa'];
                                $query_ocupacion = "SELECT id_usuario FROM tbl_ocupaciones WHERE id_mesa = :mesa_id AND fecha_fin IS NULL";
                                $stmt_ocupacion = $conexion->prepare($query_ocupacion);
                                $stmt_ocupacion->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
                                $stmt_ocupacion->execute();
                                $id_usuario_ocupante = $stmt_ocupacion->fetchColumn();

                                // Si la mesa está ocupada por el usuario actual, mostrar el botón de liberación
                                $desactivar_boton = ($estado_actual === 'ocupada' && $id_usuario !== $id_usuario_ocupante);

                                echo "
                    <div class='mesa-card'>
                        <h3>Mesa: " . htmlspecialchars($mesa['numero_mesa']) . "</h3>
                        <div class='mesa-image'>
                            <img src='./img/mesas/Mesa_" . htmlspecialchars($mesa['numero_sillas']) . ".png' alt='as layout'>
                        </div>
                        <div class='mesa-info'>
                            <p><strong>Sala:</strong> " . htmlspecialchars($categoria_seleccionada) . "</p>
                            <p><strong>Estado:</strong> <span class='" . ($estado_actual == 'libre' ? 'estado-libre' : 'estado-ocupada') . "'>" . ucfirst($estado_actual) . "</span></p>
                            <p><strong>Sillas:</strong> " . htmlspecialchars($mesa['numero_sillas']) . "</p>
                        </div>
                        <form method='POST' action='gestionar_mesas.php?categoria=$categoria_seleccionada&id_sala=$id_sala'>
                            <input type='hidden' name='mesa_id' value='" . htmlspecialchars($mesa['id_mesa']) . "'>
                            <input type='hidden' name='estado' value='" . $estado_actual . "'>
                            <button type='submit' name='cambiar_estado' class='btn-estado " . ($estado_actual === 'libre' ? 'btn-libre' : 'btn-ocupada') . "' " . ($desactivar_boton ? 'disabled' : '') . ">" . ($estado_opuesto === 'Liberar' && $desactivar_boton ? 'No puedes liberar esta mesa' : $estado_opuesto) . "</button>
                        </form>
                    </div>";
                            }
                        } else {
                            echo "<p>No hay mesas registradas en esta sala.</p>";
                        }
                    } else {
                        echo "<p>No se encontró la sala seleccionada o no corresponde a la categoría.</p>";
                    }
                } else {
                    echo "<p>Faltan parámetros para la selección de sala o categoría.</p>";
                }

                // Manejar el cambio de estado de las mesas
                if (isset($_POST['cambiar_estado'])) {
                    $mesa_id = $_POST['mesa_id'];
                    $estado_nuevo = $_POST['estado'] == 'libre' ? 'ocupada' : 'libre';
                    $fecha_hora = date("Y-m-d H:i:s");

                    $query_update = "UPDATE tbl_mesas SET estado = :estado WHERE id_mesa = :mesa_id";
                    $stmt_update = $conexion->prepare($query_update);
                    $stmt_update->bindParam(':estado', $estado_nuevo, PDO::PARAM_STR);
                    $stmt_update->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
                    $stmt_update->execute();

                    // Si la mesa se ocupa, insertar la ocupación
                    if ($estado_nuevo == 'ocupada') {
                        $query_insert = "INSERT INTO tbl_ocupaciones (id_usuario, id_mesa, fecha_inicio) VALUES (:id_usuario, :mesa_id, :fecha_hora)";
                        $stmt_insert = $conexion->prepare($query_insert);
                        $stmt_insert->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                        $stmt_insert->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
                        $stmt_insert->bindParam(':fecha_hora', $fecha_hora, PDO::PARAM_STR);
                        $stmt_insert->execute();
                    } else {
                        $query_end = "UPDATE tbl_ocupaciones SET fecha_fin = :fecha_hora WHERE id_mesa = :mesa_id AND fecha_fin IS NULL";
                        $stmt_end = $conexion->prepare($query_end);
                        $stmt_end->bindParam(':fecha_hora', $fecha_hora, PDO::PARAM_STR);
                        $stmt_end->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
                        $stmt_end->execute();
                    }

                    // Establecer una variable de sesión para indicar que se debe mostrar el SweetAlert
                    $_SESSION['mesa_sweetalert'] = true;
                }

                $conexion->commit();

                // Redirigir después de cambiar el estado
                if (isset($_POST['cambiar_estado'])) {
                    header("Location: gestionar_mesas.php?categoria=$categoria_seleccionada&id_sala=$id_sala");
                    exit();
                }
                ob_end_flush();
            } catch (Exception $e) {
                $conexion->rollBack();
                echo "Ocurrió un error al procesar la solicitud: " . $e->getMessage();
            }
            ?>

        </div>
        <script src="./js/sweetalert.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>