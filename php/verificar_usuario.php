<?php
require_once('./conexion.php');

if (isset($_POST['nombre_user'])) {
    $nombre_user = $_POST['nombre_user'];

    $stmt = $conexion->prepare("SELECT COUNT(*) FROM tbl_usuarios WHERE nombre_user = :nombre_user");
    $stmt->bindParam(':nombre_user', $nombre_user);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    echo json_encode(['exists' => $count > 0]);
}
?> 