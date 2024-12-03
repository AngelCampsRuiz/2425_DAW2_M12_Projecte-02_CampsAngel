<?php
require_once('conexion.php');
session_start();
if (isset($_POST['btn_iniciar_sesion'])  && !empty($_POST['Usuario']) && !empty($_POST['Contra'])) {
    $contra = isset($_POST['Contra']) ? mysqli_real_escape_string($conexion, htmlspecialchars($_POST['Contra'])) : '';
    $usuario = isset($_POST['Usuario']) ? mysqli_real_escape_string($conexion, htmlspecialchars($_POST['Usuario'])) : '';
    $_SESSION['usuario'] = $usuario;
    try {
        mysqli_autocommit($conexion, false);
        mysqli_begin_transaction($conexion, MYSQLI_TRANS_START_READ_WRITE);

        $sql = "SELECT nombre_user, contrasena FROM tbl_usuarios WHERE nombre_user = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($usuario_db = mysqli_fetch_assoc($resultado)) {
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

        mysqli_stmt_close($stmt);
        mysqli_commit($conexion);
    } catch (Exception $e) {
        mysqli_rollback($conexion);
        echo "Se produjo un error: " . htmlspecialchars($e->getMessage());
    }
} else {
    header('Location: ../index.php?error=campos_vacios');
}