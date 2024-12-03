<?php

$dbserver = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbbasedatos = "bd_restauranteIndividual";

try {
    $conexion = new PDO("mysql:host=$dbserver;dbname=$dbbasedatos;charset=utf8", $dbusername, $dbpassword);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    die();
}