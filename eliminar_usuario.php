<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$id_usuario = $_GET['id'];
$stmt = $conexion->prepare("DELETE FROM tbl_usuarios WHERE id_usuario = :id");
$stmt->bindParam(':id', $id_usuario);
$stmt->execute();

header('Location: admin_panel.php');
exit();
?> 