<?php
session_start();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Importación de la fuente 'Roboto' de Google Fonts para darle un estilo de fuente consistente a la página -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./css/style.css">
    <title>Document</title>
</head>

<body id="body">
    <div class="container"> <!-- Contenedor principal de la página -->
        <div class="left-side"> <!-- Sección izquierda del diseño donde se encuentra el logo -->
            <img src="./img/logo.png" alt="Logo"> <!-- Imagen del logo de la empresa o aplicación -->
        </div>
        <!-- Sección derecha donde se encuentra el formulario de inicio de sesión -->
        <div class="right-side">
            <!-- Formulario de inicio de sesión que envía los datos al archivo login.php en el servidor para autenticación -->
            <form id="loginForm" action="./php/login.php" method="post" class="form-login">
                <h2>Iniciar sesión</h2> <!-- Título del formulario -->

                <!-- Campo de entrada para el nombre de usuario -->
                <label for="Usuario">Usuario: </label> <!-- Etiqueta para el campo de usuario -->
                <br><br>
                <input type="text" name="Usuario" id="Usuario" class="form-login-label"> <!-- Campo de entrada para el usuario -->
                <span id="usuarioError" class="error-message"></span> <!-- Mensaje de error que se mostrará en caso de error de usuario -->

                <br><br>
                <!-- Campo de entrada para la contraseña del usuario -->
                <label for="Contra">Contraseña: </label> <!-- Etiqueta para el campo de contraseña -->
                <br><br>
                <input type="password" name="Contra" id="Contra" class="form-login-label"> <!-- Campo de entrada para la contraseña -->
                <span id="contraError" class="error-message"></span> <!-- Mensaje de error que se mostrará en caso de error en la contraseña -->

                <br><br><br>
                <!-- Botón para enviar el formulario e intentar iniciar sesión -->
                <button type="submit" name="btn_iniciar_sesion" class="form-login-button">Iniciar sesion</button>
            </form>
        </div>
    </div>
    <script src="./js/auth.js"></script>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }
    </style>
</body>

</html>