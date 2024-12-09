document.addEventListener('DOMContentLoaded', function() {
    const fechaReservaInput = document.getElementById('fecha_reserva');
    const horaInicioInput = document.getElementById('hora_inicio');
    const horaFinInput = document.getElementById('hora_fin');

    // Deshabilitar fechas pasadas
    const today = new Date().toISOString().split('T')[0];
    fechaReservaInput.setAttribute('min', today);

    // Deshabilitar horas pasadas para la fecha actual
    fechaReservaInput.addEventListener('change', function() {
        const selectedDate = new Date(fechaReservaInput.value);
        const now = new Date();
        if (selectedDate.toDateString() === now.toDateString()) {
            const currentHour = now.getHours();
            for (let i = 0; i < 24; i++) {
                const option = horaInicioInput.querySelector(`option[value="${String(i).padStart(2, '0')}:00"]`);
                if (option) {
                    option.disabled = i <= currentHour;
                }
            }
        } else {
            for (let i = 0; i < 24; i++) {
                const option = horaInicioInput.querySelector(`option[value="${String(i).padStart(2, '0')}:00"]`);
                if (option) {
                    option.disabled = false;
                }
            }
        }
    });

    // Deshabilitar horas ya reservadas
    const reservas = JSON.parse(document.getElementById('reservas-data').textContent);
    fechaReservaInput.addEventListener('change', function() {
        const selectedDate = fechaReservaInput.value;
        reservas.forEach(reserva => {
            if (reserva.fecha === selectedDate) {
                const horaInicio = parseInt(reserva.hora_inicio.split(':')[0], 10);
                const option = horaInicioInput.querySelector(`option[value="${String(horaInicio).padStart(2, '0')}:00"]`);
                if (option) {
                    option.disabled = true;
                }
            }
        });
    });

    // Inicializar la validación de horas al cargar la página
    fechaReservaInput.dispatchEvent(new Event('change'));

    // Validación de hora de fin
    horaInicioInput.addEventListener('change', function() {
        const horaInicio = new Date(`1970-01-01T${horaInicioInput.value}:00`);
        const horaMaxFin = new Date(horaInicio.getTime() + 60 * 60 * 1000); // 1 hora después

        const horaMaxFinStr = horaMaxFin.toTimeString().slice(0, 5);
        horaFinInput.min = horaInicioInput.value;
        horaFinInput.value = horaMaxFinStr;
    });

    const mensaje = document.getElementById('mensaje-data').textContent;
    if (mensaje) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: mensaje,
            confirmButtonText: 'Aceptar'
        });
    }
}); 