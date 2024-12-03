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
                $conexion->beginTransaction();

                $query_salas = "SELECT * FROM tbl_salas WHERE tipo_sala = :categoria";
                $stmt_salas = $conexion->prepare($query_salas);
                $stmt_salas->bindParam(':categoria', $categoria_seleccionada, PDO::PARAM_STR);
                $stmt_salas->execute();
                $result_salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

                if ($result_salas) {
                    foreach ($result_salas as $sala) {
                        $id_sala = $sala['id_sala'];

                        // Obtener el total de sillas en la sala
                        $query_total_sillas = "
                SELECT SUM(m.numero_sillas) AS total_sillas
                FROM tbl_mesas m
                WHERE m.id_sala = :id_sala";
                        $stmt_total_sillas = $conexion->prepare($query_total_sillas);
                        $stmt_total_sillas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
                        $stmt_total_sillas->execute();
                        $total_sillas = $stmt_total_sillas->fetchColumn();

                        // Obtener las sillas libres
                        $query_sillas_libres = "
                SELECT SUM(m.numero_sillas) AS total_sillas_libres
                FROM tbl_mesas m
                WHERE m.estado = 'libre' AND m.id_sala = :id_sala";
                        $stmt_sillas_libres = $conexion->prepare($query_sillas_libres);
                        $stmt_sillas_libres->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
                        $stmt_sillas_libres->execute();
                        $sillas_libres = $stmt_sillas_libres->fetchColumn();

                        echo "<a class='image-container' href='./gestionar_mesas.php?categoria=" . urlencode($categoria_seleccionada) . "&id_sala=" . $id_sala . "'>
                    <img src='./img/" . htmlspecialchars($sala['nombre_sala']) . ".jpg' alt='' id='terraza'>
                    <div class='text-overlay'>" . htmlspecialchars($sala['nombre_sala']) . "<br>Sillas libres: " . ($sillas_libres ?? 0) . "/" . ($total_sillas ?? 0)  . "</div>
                </a>";
                    }
                } else {
                    echo "<p>No hay salas disponibles para esta categoría.</p>";
                }

                $conexion->commit();
            } catch (Exception $e) {
                $conexion->rollBack();
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
            ?>

        </section>
    </div>
</body>

</html>