<?php
session_start();
$message = $_SESSION['message'] ?? 'Operación realizada con éxito';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Éxito</title>
</head>
<body>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '<?php echo $message; ?>',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'admin_panel.php';
        });
    </script>
</body>
</html> 