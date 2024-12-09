// Función para validar el nombre de la sala
function validarNombreSala(nombreSala) {
    const regex = /^[A-Za-z0-9]{3,}$/;
    if (!nombreSala) {
        return "El nombre de la sala no puede estar vacío.";
    }
    if (!regex.test(nombreSala)) {
        return "El nombre de la sala debe tener al menos 3 caracteres y solo puede contener letras y números.";
    }
    return null;
}

// Función para validar el tipo de sala
function validarTipoSala(tipoSala) {
    const regex = /^[A-Za-z0-9]{3,}$/;
    if (!tipoSala) {
        return "El tipo de sala no puede estar vacío.";
    }
    if (!regex.test(tipoSala)) {
        return "El tipo de sala debe tener al menos 3 caracteres y solo puede contener letras y números.";
    }
    return null;
}

// Función para validar la capacidad
function validarCapacidad(capacidad) {
    const regex = /^[1-9][0-9]*$/;
    if (!capacidad) {
        return "La capacidad no puede estar vacía.";
    }
    if (!regex.test(capacidad)) {
        return "La capacidad debe ser un número mayor a 0.";
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

    const nombreSala = document.getElementById('nombre_sala').value;
    const tipoSala = document.getElementById('tipo_sala').value;
    const capacidad = document.getElementById('capacidad').value;

    const errorNombreSala = validarNombreSala(nombreSala);
    const errorTipoSala = validarTipoSala(tipoSala);
    const errorCapacidad = validarCapacidad(capacidad);

    if (errorNombreSala) {
        mostrarError(errorNombreSala, document.getElementById('nombre_sala'));
    }
    if (errorTipoSala) {
        mostrarError(errorTipoSala, document.getElementById('tipo_sala'));
    }
    if (errorCapacidad) {
        mostrarError(errorCapacidad, document.getElementById('capacidad'));
    }
}

// Asignar la función de validación al evento mouseover de los inputs
document.getElementById('nombre_sala').addEventListener('mouseover', validarCampos);
document.getElementById('tipo_sala').addEventListener('mouseover', validarCampos);
document.getElementById('capacidad').addEventListener('mouseover', validarCampos); 