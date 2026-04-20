<<<<<<< HEAD
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
=======
document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const usuario  = document.getElementById('usuario').value.trim();
    const password = document.getElementById('password').value;

    if (!usuario || !password) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor completa todos los campos.',
>>>>>>> d3630acbe642a0cfdbff66d94417b89c7bc20f49
            background: '#0d1528',
            color: '#e2e8f0',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Entendido'
        });
        return;
    }

<<<<<<< HEAD
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
=======
    const formData = new FormData();
    formData.append('usuario', usuario);
    formData.append('password', password);

    try {
        const res  = await fetch('/TICKETUCAD/app/models/login/login.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: `¡Bienvenido, ${data.nombre}!`,
                text: `Rol: ${data.rol}`,
                background: '#0d1528',
                color: '#e2e8f0',
                confirmButtonColor: '#2563eb',
                timer: 1400,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/TICKETUCAD/app/views/pages/padmin.html';
            });
        } else {
            document.getElementById('loginForm').reset();
            Swal.fire({
                icon: 'error',
                title: 'Acceso denegado',
                text: data.message,
                background: '#0d1528',
                color: '#e2e8f0',
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Intentar de nuevo'
            });
        }

    } catch (err) {
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor.',
            background: '#0d1528',
            color: '#e2e8f0',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Cerrar'
>>>>>>> d3630acbe642a0cfdbff66d94417b89c7bc20f49
        });
    }
});

<<<<<<< HEAD
// Toggle contraseña con checkbox
document.getElementById('showPass').addEventListener('change', function () {
    document.getElementById('password').type = this.checked ? 'text' : 'password';
=======
// Toggle contraseña
document.getElementById('togglePass').addEventListener('click', function () {
    const input = document.getElementById('password');
    const icon  = document.getElementById('toggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye-slash';
    }
>>>>>>> d3630acbe642a0cfdbff66d94417b89c7bc20f49
});
