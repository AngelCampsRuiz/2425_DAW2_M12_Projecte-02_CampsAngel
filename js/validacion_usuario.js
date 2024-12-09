// Función para validar el nombre de usuario
function validarNombreUsuario(nombreUsuario) {
    const regex = /^[A-Za-z]{3,}$/;
    if (!nombreUsuario) {
        return "El nombre de usuario no puede estar vacío.";
    }
    if (!regex.test(nombreUsuario)) {
        return "El nombre de usuario debe contener solo letras y tener al menos 3 caracteres.";
    }
    return null;
}

// Función para validar la contraseña
function validarContrasena(contrasena) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{3,}$/;
    if (!contrasena) {
        return "La contraseña no puede estar vacía.";
    }
    if (!regex.test(contrasena)) {
        return "La contraseña debe tener al menos 3 caracteres, incluyendo una minúscula, una mayúscula y un número.";
    }
    return null;
}

// Función para mostrar mensajes de error
function mostrarError(mensaje, elemento) {
    const errorDiv = elemento.nextElementSibling;
    errorDiv.textContent = mensaje;
}

// Función para limpiar mensajes de error
function limpiarErrores() {
    const errores = document.querySelectorAll('.error-message');
    errores.forEach(error => error.textContent = '');
}

// Función global para validar los campos
function validarCampos() {
    limpiarErrores();

    const nombreUsuario = document.getElementById('nombre_user').value;
    const contrasena = document.getElementById('contrasena').value;

    const errorNombreUsuario = validarNombreUsuario(nombreUsuario);
    const errorContrasena = validarContrasena(contrasena);

    if (errorNombreUsuario) {
        mostrarError(errorNombreUsuario, document.getElementById('nombre_user'));
    }
    if (errorContrasena) {
        mostrarError(errorContrasena, document.getElementById('contrasena'));
    }
}

// Asignar la función de validación al evento mouseover de los inputs
document.getElementById('nombre_user').addEventListener('mouseover', validarCampos);
document.getElementById('contrasena').addEventListener('mouseover', validarCampos);