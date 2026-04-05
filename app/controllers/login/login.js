// ── Credenciales de prueba (reemplazar con llamada al backend) ──
const USUARIOS_PRUEBA = [
    { usuario: 'admin',   password: 'admin123',  rol: 'admin'     },
    { usuario: 'agente1', password: 'agente123', rol: 'agente_it' }
];

document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const rol      = document.getElementById('rol').value;
    const usuario  = document.getElementById('usuario').value.trim();
    const password = document.getElementById('password').value;

    // Validación de campos vacíos
    if (!rol || !usuario || !password) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor completa todos los campos antes de continuar.',
            background: '#0d1528',
            color: '#e2e8f0',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Verificar credenciales de prueba
    const match = USUARIOS_PRUEBA.find(u =>
        u.usuario === usuario &&
        u.password === password &&
        u.rol === rol
    );

    // Limpiar formulario
    document.getElementById('loginForm').reset();

    if (match) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                text: `Sesión iniciada como ${usuario}`,
                background: '#0d1528',
                color: '#e2e8f0',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Continuar',
                timer: 1200,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/TICKETUCAD/panel-administrador';
            });
        } else {
            window.location.href = '/TICKETUCAD/panel-administrador';
        }
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Credenciales incorrectas',
            text: 'El usuario, contraseña o rol no son válidos.',
            background: '#0d1528',
            color: '#e2e8f0',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Intentar de nuevo'
        });
    }
});

// Toggle contraseña con checkbox
document.getElementById('showPass').addEventListener('change', function () {
    document.getElementById('password').type = this.checked ? 'text' : 'password';
});
