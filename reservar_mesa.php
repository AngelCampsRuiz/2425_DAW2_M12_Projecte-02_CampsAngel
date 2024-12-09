<?php
session_start();
date_default_timezone_set('Europe/Madrid');
require_once('./php/conexion.php');

// Verificación de sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}

$mesa_id = isset($_GET['mesa_id']) ? $_GET['mesa_id'] : 0;
$categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$id_sala = isset($_GET['id_sala']) ? $_GET['id_sala'] : 0;

$mensaje = '';

try {
    if ($mesa_id === 0 || $id_sala === 0) {
        throw new Exception("ID de mesa o sala no válido.");
    }

    $query_mesa = "SELECT * FROM tbl_mesas WHERE id_mesa = :mesa_id";
    $stmt_mesa = $conexion->prepare($query_mesa);
    $stmt_mesa->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
    $stmt_mesa->execute();
    $mesa = $stmt_mesa->fetch(PDO::FETCH_ASSOC);

    if (!$mesa) {
        throw new Exception("No se encontró ninguna mesa con el ID especificado.");
    }

    $usuario = $_SESSION['usuario'];
    $query_usuario = "SELECT id_usuario FROM tbl_usuarios WHERE nombre_user = :usuario";
    $stmt_usuario = $conexion->prepare($query_usuario);
    $stmt_usuario->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt_usuario->execute();
    $id_usuario = $stmt_usuario->fetchColumn();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['reservar'])) {
            $nombre_persona = $_POST['nombre_persona'];
            $fecha_reserva = $_POST['fecha_reserva'];
            $hora_inicio = $_POST['hora_inicio'];
            $hora_fin = $_POST['hora_fin'];

            if (strtotime($hora_fin) <= strtotime($hora_inicio)) {
                throw new Exception("La hora de fin debe ser al menos una hora después de la hora de inicio.");
            }

            $query_reserva = "INSERT INTO tbl_reservas (id_mesa, id_usuario, nombre_persona, hora_inicio, hora_fin, fecha) VALUES (:mesa_id, :id_usuario, :nombre_persona, :hora_inicio, :hora_fin, :fecha)";
            $stmt_reserva = $conexion->prepare($query_reserva);
            $stmt_reserva->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
            $stmt_reserva->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_reserva->bindParam(':nombre_persona', $nombre_persona, PDO::PARAM_STR);
            $stmt_reserva->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
            $stmt_reserva->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
            $stmt_reserva->bindParam(':fecha', $fecha_reserva, PDO::PARAM_STR);
            $stmt_reserva->execute();

            $_SESSION['mensaje'] = "Reserva realizada con éxito.";
            header("Location: reservar_mesa.php?mesa_id=$mesa_id&categoria=$categoria_seleccionada&id_sala=$id_sala");
            exit();
        }

        if (isset($_POST['reserva_id'])) {
            $reserva_id = $_POST['reserva_id'];

            $query_eliminar = "DELETE FROM tbl_reservas WHERE id_reserva = :reserva_id AND id_usuario = :id_usuario";
            $stmt_eliminar = $conexion->prepare($query_eliminar);
            $stmt_eliminar->bindParam(':reserva_id', $reserva_id, PDO::PARAM_INT);
            $stmt_eliminar->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt_eliminar->execute();

            $_SESSION['mensaje'] = "Reserva eliminada con éxito.";
            header("Location: reservar_mesa.php?mesa_id=$mesa_id&categoria=$categoria_seleccionada&id_sala=$id_sala");
            exit();
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}

// Obtener reservas actuales para la mesa
$query_reservas = "SELECT r.id_reserva, r.hora_inicio, r.hora_fin, r.fecha, r.nombre_persona, u.nombre_user, m.numero_mesa 
                   FROM tbl_reservas r
                   JOIN tbl_usuarios u ON r.id_usuario = u.id_usuario
                   JOIN tbl_mesas m ON r.id_mesa = m.id_mesa
                   WHERE r.id_mesa = :mesa_id AND r.fecha >= CURDATE()";
$stmt_reservas = $conexion->prepare($query_reservas);
$stmt_reservas->bindParam(':mesa_id', $mesa_id, PDO::PARAM_INT);
$stmt_reservas->execute();
$reservas = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);
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
    <style>
    </style>
</head>

<body>
    <div class="container">
        <nav class="navegacion">
            <div class="navbar-left">
                <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
                <a href="./registro.php"><img src="./img/lbook.png" alt="Ícono adicional" class="navbar-icon"></a>
            </div>

            <div class="navbar-title">
                <h3>Reservar Mesa <?php echo htmlspecialchars($mesa['numero_mesa']); ?></h3>
            </div>

            <div class="navbar-right" style="margin-right: 18px;">
                <a href="./gestionar_mesas.php?categoria=<?php echo $categoria_seleccionada; ?>&id_sala=<?php echo $id_sala; ?>"><img src="./img/atras.png" alt="Volver" class="navbar-icon"></a>
            </div>

            <div class="navbar-right">
                <a href="./salir.php"><img src="./img/logout.png" alt="Logout" class="navbar-icon"></a>
            </div>
        </nav>

        <div class='reserva-container'>
            <form method='POST' action='reservar_mesa.php?mesa_id=<?php echo $mesa_id; ?>&categoria=<?php echo $categoria_seleccionada; ?>&id_sala=<?php echo $id_sala; ?>'>
                <div class='form-container'>
                    <div class='mb-3'>
                        <label for='nombre_persona' class='form-label'>Nombre de la Persona:</label>
                        <input type='text' class='form-control' name='nombre_persona' id='nombre_persona' required>
                    </div>
                    <div class='mb-3'>
                        <label for='fecha_reserva' class='form-label'>Fecha de Reserva:</label>
                        <input type='date' class='form-control' name='fecha_reserva' id='fecha_reserva' required>
                    </div>
                    <div class='mb-3'>
                        <label for='hora_inicio' class='form-label'>Hora de inicio:</label>
                        <select class='form-control' name='hora_inicio' id='hora_inicio' required>
                            <?php for ($i = 0; $i < 24; $i++): ?>
                                <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; ?>">
                                    <?php echo str_pad($i, 2, '0', STR_PAD_LEFT) . ':00'; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class='mb-3'>
                        <label for='hora_fin' class='form-label'>Hora de fin:</label>
                        <input type='time' class='form-control' name='hora_fin' id='hora_fin' required readonly>
                    </div>
                    <button type='submit' name='reservar' class='btn btn-primary'>Reservar</button>
                </div>
            </form>
            <hr>
            <h5 class="form-label">Reservas Actuales</h5>
            <table>
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Mesa</th>
                        <th>Fecha</th>
                        <th>Hora de Inicio</th>
                        <th>Hora de Fin</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($reservas as $reserva) {
                        echo "<tr>
                                <td>{$reserva['nombre_user']}</td>
                                <td>{$reserva['nombre_persona']}</td>
                                <td>{$reserva['numero_mesa']}</td>
                                <td>{$reserva['fecha']}</td>
                                <td>{$reserva['hora_inicio']}</td>
                                <td>{$reserva['hora_fin']}</td>
                                <td>
                                    <form method='POST' style='display:inline;' action='reservar_mesa.php?mesa_id=$mesa_id&categoria=$categoria_seleccionada&id_sala=$id_sala'>
                                        <input type='hidden' name='reserva_id' value='{$reserva['id_reserva']}'>
                                        <button type='button' class='btn btn-danger btn-sm cancelar-reserva'>Cancelar</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="reservas-data" style="display: none;"><?php echo json_encode($reservas); ?></div>
    <div id="mensaje-data" style="display: none;"><?php echo htmlspecialchars($mensaje); ?></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/validacion_reservas.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmación al cancelar una reserva
            document.querySelectorAll('.cancelar-reserva').forEach(button => {
                button.addEventListener('click', function(event) {
                    const form = this.closest('form');
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, cancelar',
                        cancelButtonText: 'No, mantener'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Enviar el formulario si se confirma
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>