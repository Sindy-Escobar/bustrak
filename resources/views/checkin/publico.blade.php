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
        <p class="text-muted">Ingresa el código de tu reserva para validar el abordaje</p>
    </div>

    <div class="card shadow-sm mx-auto" style="max-width:500px;">
        <div class="card-body p-4">
            <div id="alertContainer"></div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                <input type="text" id="codigoInput" class="form-control form-control-lg"
                       placeholder="Ej: RES_69d9848d05ada">
                <button class="btn btn-primary" onclick="validarCodigo()">
                    <i class="fas fa-search me-1"></i>Validar
                </button>
            </div>

            <div id="datosPasajero" style="display:none;"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Si viene con ?codigo= en la URL, validar automáticamente
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const codigo = params.get('codigo');
        if (codigo) {
            document.getElementById('codigoInput').value = codigo;
            validarCodigo();
        }
    });

    let reservaActual = null;

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
            body: JSON.stringify({ reserva_id: reservaActual.reserva_id, codigo_qr: reservaActual.codigo_qr })
        })
            .then(res => res.json())
            .then(data => {
                mostrarAlerta(data.message, data.tipo_alerta);
                if (data.success) {
                    document.getElementById('datosPasajero').style.display = 'none';
                    document.getElementById('codigoInput').value = '';
                    reservaActual = null;
                }
            })
            .catch(() => mostrarAlerta('Error al confirmar', 'danger'));
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

        const div = document.getElementById('datosPasajero');
        div.innerHTML = html;
        div.style.display = 'block';
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
