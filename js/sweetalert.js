document.addEventListener("DOMContentLoaded", () => {
    const usuario = document.body.getAttribute('data-usuario');
    const sweetalertMostrado = document.body.getAttribute('data-sweetalert') === 'true';
    const mesaSweetalertMostrado = document.body.getAttribute('data-mesa-sweetalert') === 'true';

    if (usuario && !sweetalertMostrado) {
        Swal.fire({
            title: '¡Bienvenido!',
            text: `Hola ${usuario}, bienvenid@ al portal.`,
            icon: 'success',
            confirmButtonText: 'Gracias'
        }).then(() => {
            // Establecer la variable de sesión para indicar que el SweetAlert ya se mostró
            fetch('./php/marcar_sweetalert_mostrado.php');
        });
    }

    if (mesaSweetalertMostrado) {
        Swal.fire({
            title: 'Estado de la Mesa Cambiado',
            text: 'El estado de la mesa ha sido actualizado exitosamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            // Limpiar la variable de sesión para que no se muestre nuevamente
            fetch('./php/limpiar_mesa_sweetalert.php');
        });
    }
}); 