<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$id_usuario = $_GET['id'];

try {
    $conexion->beginTransaction();

    // Eliminar reservas del usuario
    $stmt = $conexion->prepare("DELETE FROM tbl_reservas WHERE id_usuario = :id");
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();

    // Eliminar el usuario
    $stmt = $conexion->prepare("DELETE FROM tbl_usuarios WHERE id_usuario = :id");
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();

    $conexion->commit();

    header('Location: admin_panel.php?message=Usuario eliminado correctamente');
    exit();
} catch (Exception $e) {
    $conexion->rollBack();
    die("Error al eliminar el usuario: " . $e->getMessage());
}
?> 