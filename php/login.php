<?php
require_once('conexion.php');
session_start();
if (isset($_POST['btn_iniciar_sesion']) && !empty($_POST['Usuario']) && !empty($_POST['Contra'])) {
    $contra = htmlspecialchars($_POST['Contra']);
    $usuario = htmlspecialchars($_POST['Usuario']);
    $_SESSION['usuario'] = $usuario;
    try {
        $conexion->beginTransaction();

        $sql = "SELECT nombre_user, contrasena FROM tbl_usuarios WHERE nombre_user = :usuario";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario_db) {
            if (password_verify($contra, $usuario_db['contrasena'])) {
                $_SESSION['Usuario'] = $usuario;
                header("Location: ../menu.php");
                exit();
            } else {
                header('Location:../index.php?error=contrasena_incorrecta');
            }
        } else {
            header('Location:../index.php?error=usuario_no_encontrado');
        }

        $conexion->commit();
    } catch (PDOException $e) {
        $conexion->rollBack();
        echo "Se produjo un error: " . htmlspecialchars($e->getMessage());
    }
} else {
    header('Location: ../index.php?error=campos_vacios');
}