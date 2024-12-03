document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error) {
        if (error === 'contrasena_incorrecta' || error === 'usuario_no_encontrado') {
            document.getElementById('usuarioError').textContent = 'Usuario o contraseña incorrecto';
            document.getElementById('contraError').textContent = 'Usuario o contraseña incorrecto';
        } else if (error === 'campos_vacios') {
            document.getElementById('usuarioError').textContent = 'Por favor, complete todos los campos';
            document.getElementById('contraError').textContent = 'Por favor, complete todos los campos';
        }
    }
});
