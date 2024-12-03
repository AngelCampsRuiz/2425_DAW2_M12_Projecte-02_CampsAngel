<?php
session_start();
require_once('./php/conexion.php');

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
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

<body>
    <div class="container">
        <nav class="navegacion">
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
                <a href="./registro.php"><img src="./img/lbook.png" alt="Ícono adicional" class="navbar-icon"></a>
            </div>

            <div class="navbar-title">
                <h3><?php if (isset($_GET['categoria'])) {
                        echo $_GET['categoria'];
                    } ?></h3>
            </div>

            <div class="navbar-right" style="margin-right: 18px;">
                <a href="./menu.php"><img src="./img/atras.png" alt="Logout" class="navbar-icon"></a>
            </div>

            <div class="navbar-right">
                <a href="./salir.php"><img src="./img/logout.png" alt="Logout" class="navbar-icon"></a>
            </div>
        </nav>
    </div>
    <div class="container-menu">
        <section>
            <?php
            $categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

            try {
                mysqli_autocommit($conexion, false);

                mysqli_begin_transaction($conexion, MYSQLI_TRANS_START_READ_WRITE);

                $query_salas = "SELECT * FROM tbl_salas WHERE tipo_sala = ?";
                $stmt_salas = mysqli_prepare($conexion, $query_salas);

                if (!$stmt_salas) {
                    throw new Exception("Error al preparar la consulta: " . mysqli_error($conexion));
                }

                mysqli_stmt_bind_param($stmt_salas, "s", $categoria_seleccionada);

                if (!mysqli_stmt_execute($stmt_salas)) {
                    throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt_salas));
                }

                $result_salas = mysqli_stmt_get_result($stmt_salas);

                if ($result_salas && mysqli_num_rows($result_salas) > 0) {
                    while ($sala = mysqli_fetch_assoc($result_salas)) {
                        $id_sala = $sala['id_sala'];

                        // Obtener el total de sillas en la sala
                        $query_total_sillas = "
                SELECT SUM(m.numero_sillas) AS total_sillas
                FROM tbl_mesas m
                WHERE m.id_sala = ?";
                        $stmt_total_sillas = mysqli_prepare($conexion, $query_total_sillas);
                        mysqli_stmt_bind_param($stmt_total_sillas, "i", $id_sala);
                        mysqli_stmt_execute($stmt_total_sillas);
                        $result_total_sillas = mysqli_stmt_get_result($stmt_total_sillas);
                        $total_sillas = mysqli_fetch_assoc($result_total_sillas)['total_sillas'];

                        // Obtener las sillas libres
                        $query_sillas_libres = "
                SELECT SUM(m.numero_sillas) AS total_sillas_libres
                FROM tbl_mesas m
                WHERE m.estado = 'libre' AND m.id_sala = ?";
                        $stmt_sillas_libres = mysqli_prepare($conexion, $query_sillas_libres);
                        mysqli_stmt_bind_param($stmt_sillas_libres, "i", $id_sala);
                        mysqli_stmt_execute($stmt_sillas_libres);
                        $result_sillas_libres = mysqli_stmt_get_result($stmt_sillas_libres);
                        $sillas_libres = mysqli_fetch_assoc($result_sillas_libres)['total_sillas_libres'];

                        mysqli_stmt_close($stmt_total_sillas);
                        mysqli_stmt_close($stmt_sillas_libres);

                        // Mostrar la información
                        echo "<a class='image-container' href='./gestionar_mesas.php?categoria=" . urlencode($categoria_seleccionada) . "&id_sala=" . $id_sala . "'>
                    <img src='./img/" . htmlspecialchars($sala['nombre_sala']) . ".jpg' alt='' id='terraza'>
                    <div class='text-overlay'>" . htmlspecialchars($sala['nombre_sala']) . "<br>Sillas libres: " . ($sillas_libres ?? 0) . "/" . ($total_sillas ?? 0)  . "</div>
                </a>";
                    }
                } else {
                    echo "<p>No hay salas disponibles para esta categoría.</p>";
                }

                mysqli_stmt_close($stmt_salas);

                mysqli_commit($conexion);
                mysqli_close($conexion);
            } catch (Exception $e) {
                mysqli_rollback($conexion);
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
            ?>

        </section>
    </div>
</body>

</html>