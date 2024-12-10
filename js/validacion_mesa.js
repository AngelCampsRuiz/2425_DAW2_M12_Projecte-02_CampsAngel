// Función para validar el número de mesa
function validarNumeroMesa(numeroMesa) {
    const regex = /^[1-9][0-9]*$/;
    if (!numeroMesa) {
        return "El número de mesa no puede estar vacío.";
    }
    if (!regex.test(numeroMesa)) {
        return "El número de mesa debe ser un número mayor a 0.";
    }
    return null;
}

// Función para validar el número de sillas
function validarNumeroSillas(numeroSillas) {
    const regex = /^[1-9][0-9]*$/;
    if (!numeroSillas) {
        return "El número de sillas no puede estar vacío.";
    }
    if (!regex.test(numeroSillas)) {
        return "El número de sillas debe ser un número mayor a 0.";
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

    const numeroMesa = document.getElementById('numero_mesa').value;
    const numeroSillas = document.getElementById('numero_sillas').value;

    const errorNumeroMesa = validarNumeroMesa(numeroMesa);
    const errorNumeroSillas = validarNumeroSillas(numeroSillas);

    if (errorNumeroMesa) {
        mostrarError(errorNumeroMesa, document.getElementById('numero_mesa'));
    }
    if (errorNumeroSillas) {
        mostrarError(errorNumeroSillas, document.getElementById('numero_sillas'));
    }
}

// Función para permitir solo números en los inputs
function permitirSoloNumeros(event) {
    const charCode = event.which ? event.which : event.keyCode;
    if (charCode < 48 || charCode > 57) {
        event.preventDefault();
    }
}

// Asignar la función de validación al evento mouseover de los inputs
document.getElementById('numero_mesa').addEventListener('mouseover', validarCampos);
document.getElementById('numero_sillas').addEventListener('mouseover', validarCampos);

// Asignar la función para permitir solo números al evento keypress de los inputs
document.getElementById('numero_mesa').addEventListener('keypress', permitirSoloNumeros);
document.getElementById('numero_sillas').addEventListener('keypress', permitirSoloNumeros); 