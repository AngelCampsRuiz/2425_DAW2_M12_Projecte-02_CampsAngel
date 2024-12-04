<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario es administrador
if ($_SESSION['rol_usuario'] !== 'administrador') {
    header('Location: ./menu.php');
    exit();
}

$id_sala = $_GET['id'];
$stmt = $conexion->prepare("DELETE FROM tbl_salas WHERE id_sala = :id");
$stmt->bindParam(':id', $id_sala);
$stmt->execute();

header('Location: admin_panel.php');
exit();
?> 