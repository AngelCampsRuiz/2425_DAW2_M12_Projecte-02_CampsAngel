<?php

$dbserver="localhost";
$dbusername="root";
$dbpassword="";
$dbbasedatos="bd_restaurante";
try {
    $conexion = mysqli_connect($dbserver, $dbusername,$dbpassword, $dbbasedatos);
}catch (Exception $e) {
    echo "Error de conexión: ". $e->getMessage();
    die();
}