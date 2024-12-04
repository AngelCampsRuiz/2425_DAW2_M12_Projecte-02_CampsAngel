<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$id_mesa = $_GET['id'];

try {
    $conexion->beginTransaction();

    // Eliminar ocupaciones o reservas de la mesa
    $stmt = $conexion->prepare("DELETE FROM tbl_ocupaciones WHERE id_mesa = :id");
    $stmt->bindParam(':id', $id_mesa);
    $stmt->execute();

    // Eliminar la mesa
    $stmt = $conexion->prepare("DELETE FROM tbl_mesas WHERE id_mesa = :id");
    $stmt->bindParam(':id', $id_mesa);
    $stmt->execute();

    $conexion->commit();

    header('Location: admin_panel.php?message=Mesa eliminada correctamente');
    exit();
} catch (Exception $e) {
    $conexion->rollBack();
    die("Error al eliminar la mesa: " . $e->getMessage());
}
?> 