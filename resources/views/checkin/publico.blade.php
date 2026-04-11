<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check-in Bustrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background:#f5f7fa;">
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 style="color:#1e63b8; font-weight:700;">
            <i class="fas fa-qrcode me-2"></i>Check-in Bustrak
        </h2>
        <p class="text-muted">Ingresa tus credenciales para acceder</p>
    </div>

    <div class="card shadow-sm mx-auto" style="max-width:500px;">
        <div class="card-body p-4">
            <div id="alertContainer"></div>

            {{-- PASO 1: Login con DNI y PIN --}}
            <div id="loginForm">
                <h5 class="mb-3"><i class="fas fa-lock me-2"></i>Acceso Conductor</h5>
                <div class="mb-3">
                    <label class="form-label fw-bold">DNI</label>
                    <input type="text" id="dniInput" class="form-control" placeholder="Ingresa tu DNI">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">PIN</label>
                    <input type="password" id="pinInput" class="form-control" placeholder="Ingresa tu PIN">
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary btn-lg" onclick="verificarPin()">
                        <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                    </button>
                </div>
            </div>

            {{-- PASO 2: Validar QR (oculto hasta verificar PIN) --}}
            <div id="checkinForm" style="display:none;">
                <div class="alert alert-success mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    Bienvenido, <strong id="nombreEmpleado"></strong>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                    <input type="text" id="codigoInput" class="form-control form-control-lg"
                           placeholder="Ej: RES_69d9848d05ada">
                    <button class="btn btn-primary" onclick="validarCodigo()">
                        <i class="fas fa-search me-1"></i>Validar
                    </button>
                </div>

                <div id="datosPasajero"></div>

                <div class="mt-3">
                    <button class="btn btn-outline-danger btn-sm" onclick="cerrarSesion()">
                        <i class="fas fa-sign-out-alt me-1"></i>Cerrar sesión
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let reservaActual = null;
    let empleadoActual = null;

    // Si viene con ?codigo= en la URL, guardar para después del login
    const params = new URLSearchParams(window.location.search);
    const codigoUrl = params.get('codigo');

    function verificarPin() {
        const dni = document.getElementById('dniInput').value.trim();
        const pin = document.getElementById('pinInput').value.trim();

        if (!dni || !pin) {
            mostrarAlerta('Por favor ingresa tu DNI y PIN', 'warning');
            return;
        }

        fetch('{{ route("checkin.verificar.pin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ dni, pin })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    empleadoActual = data.empleado;
                    document.getElementById('loginForm').style.display = 'none';
                    document.getElementById('checkinForm').style.display = 'block';
                    document.getElementById('nombreEmpleado').textContent = data.empleado.nombre;

                    // Si vino con código en la URL, validar automáticamente
                    if (codigoUrl) {
                        document.getElementById('codigoInput').value = codigoUrl;
                        validarCodigo();
                    }
                } else {
                    mostrarAlerta(data.message, 'danger');
                }
            })
            .catch(() => mostrarAlerta('Error de conexión', 'danger'));
    }

    function validarCodigo() {
        const codigo = document.getElementById('codigoInput').value.trim();
        if (!codigo) {
            mostrarAlerta('Por favor ingresa un código de reserva', 'warning');
            return;
        }

        mostrarAlerta('Validando...', 'info');

        fetch('{{ route("checkin.validar.publico") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ codigo_qr: codigo })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    reservaActual = data.datos;
                    mostrarDatos(data.datos);
                    mostrarAlerta(data.message, data.tipo_alerta);
                } else {
                    mostrarAlerta(data.message, 'danger');
                }
            })
            .catch(() => mostrarAlerta('Error de conexión', 'danger'));
    }

    function confirmarAbordaje() {
        if (!reservaActual) return;

        fetch('{{ route("checkin.confirmar.publico") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                reserva_id: reservaActual.reserva_id,
                codigo_qr: reservaActual.codigo_qr
            })
        })
            .then(res => res.json())
            .then(data => {
                mostrarAlerta(data.message, data.tipo_alerta);
                if (data.success) {
                    document.getElementById('datosPasajero').innerHTML = '';
                    document.getElementById('codigoInput').value = '';
                    reservaActual = null;
                }
            })
            .catch(() => mostrarAlerta('Error al confirmar', 'danger'));
    }

    function cerrarSesion() {
        empleadoActual = null;
        reservaActual = null;
        document.getElementById('loginForm').style.display = 'block';
        document.getElementById('checkinForm').style.display = 'none';
        document.getElementById('dniInput').value = '';
        document.getElementById('pinInput').value = '';
        document.getElementById('datosPasajero').innerHTML = '';
        document.getElementById('alertContainer').innerHTML = '';
    }

    function mostrarDatos(datos) {
        const viaje = datos.viaje;
        const pasajero = datos.pasajero;

        let html = `
            <hr>
            <h6 class="text-primary"><i class="fas fa-user me-2"></i>Pasajero</h6>
            <p class="mb-1"><strong>Nombre:</strong> ${pasajero.nombre_completo}</p>
            <p class="mb-1"><strong>Email:</strong> ${pasajero.email}</p>
            <hr>
            <h6 class="text-primary"><i class="fas fa-bus me-2"></i>Viaje</h6>
            <p class="mb-1"><strong>Ruta:</strong> ${viaje.ruta}</p>
            <p class="mb-1"><strong>Fecha:</strong> ${viaje.fecha_salida}</p>
            <p class="mb-1"><strong>Servicio:</strong> ${viaje.tipo_servicio}</p>
            <hr>
            <div class="d-grid">
                <button class="btn btn-success btn-lg" onclick="confirmarAbordaje()">
                    <i class="fas fa-check-circle me-2"></i>Confirmar Abordaje
                </button>
            </div>
        `;

        document.getElementById('datosPasajero').innerHTML = html;
    }

    function mostrarAlerta(mensaje, tipo) {
        const colores = { success: 'success', error: 'danger', warning: 'warning', info: 'info', danger: 'danger' };
        document.getElementById('alertContainer').innerHTML = `
            <div class="alert alert-${colores[tipo] || 'info'} alert-dismissible fade show">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
    }
</script>
</body>
</html>
