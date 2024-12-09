<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$id_sala = $_GET['id'];

try {
    $conexion->beginTransaction();

    // Eliminar reservas de las mesas de la sala
    $stmt = $conexion->prepare("
        DELETE FROM tbl_reservas 
        WHERE id_mesa IN (SELECT id_mesa FROM tbl_mesas WHERE id_sala = :id)
    ");
    $stmt->bindParam(':id', $id_sala);
    $stmt->execute();

    // Eliminar mesas de la sala
    $stmt = $conexion->prepare("DELETE FROM tbl_mesas WHERE id_sala = :id");
    $stmt->bindParam(':id', $id_sala);
    $stmt->execute();

    // Eliminar la sala
    $stmt = $conexion->prepare("DELETE FROM tbl_salas WHERE id_sala = :id");
    $stmt->bindParam(':id', $id_sala);
    $stmt->execute();

    $conexion->commit();

    header('Location: admin_panel.php?message=Sala eliminada correctamente');
    exit();
} catch (Exception $e) {
    $conexion->rollBack();
    die("Error al eliminar la sala: " . $e->getMessage());
}
?> 