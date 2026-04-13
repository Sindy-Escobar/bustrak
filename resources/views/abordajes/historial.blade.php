@extends('layouts.layoutadmin')

@section('title', 'Escanear Código QR - Abordaje')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h2 class="mb-0" style="color:#1e63b8; font-weight:600; font-size:1.8rem;">
                    <i class="fas fa-qrcode me-2"></i>Check-in con Código QR
                </h2>
                <p class="mt-2 mb-0">Escanea el código QR del pasajero para confirmar su abordaje</p>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Escanear Código QR</h5>
                            </div>
                            <div class="card-body text-center">
                                <div id="reader" style="width: 100%;"></div>

                                <div class="mt-3">
                                    <p class="text-muted">O ingresa el código manualmente:</p>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-keyboard"></i>
                                        </span>
                                        <input type="text" id="codigoManual" class="form-control" placeholder="Ingrese código QR">
                                        <button class="btn btn-primary" onclick="validarCodigoManual()">
                                            <i class="fas fa-check me-1"></i>Validar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div id="areaDatos" style="display:none;">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Datos del Pasajero</h5>
                                </div>
                                <div class="card-body">
                                    <div id="datosPasajero"></div>

                                    <div class="mt-3">
                                        <label class="form-label fw-bold">Observaciones (Opcional)</label>
                                        <textarea id="observaciones" class="form-control" rows="3" placeholder="Agregar observaciones..." maxlength="500"></textarea>
                                    </div>

                                    <div class="d-grid gap-2 mt-4">
                                        <button class="btn btn-success btn-lg" onclick="confirmarAbordaje()">
                                            <i class="fas fa-check-circle me-2"></i>Confirmar Abordaje
                                        </button>
                                        <button class="btn btn-outline-secondary" onclick="cancelarValidacion()">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="areaAlertas"></div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('abordajes.historial.list') }}" class="btn btn-outline-primary">
                        <i class="fas fa-history me-2"></i>Ver Historial de Abordajes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let reservaActual = null;
        let html5QrCode = null;
        let isScanning = false;

        document.addEventListener('DOMContentLoaded', function() {
            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanError)
                .then(() => { isScanning = true; })
                .catch(err => {
                    console.error("Error:", err);
                    mostrarAlerta('No se pudo acceder a la cámara. Use el ingreso manual.', 'warning');
                });
        });

        function onScanSuccess(decodedText) {
            if (!isScanning) return;
            isScanning = false;
            html5QrCode.pause(true);
            validarCodigo(decodedText);
        }

        function onScanError(error) {}

        function validarCodigoManual() {
            const codigo = document.getElementById('codigoManual').value.trim();
            if (!codigo) {
                mostrarAlerta('Por favor ingrese un código QR', 'warning');
                return;
            }
            if (isScanning && html5QrCode) {
                html5QrCode.pause(true);
                isScanning = false;
            }
            validarCodigo(codigo);
        }

        function validarCodigo(codigoQR) {
            mostrarAlerta('Validando código...', 'info');

            fetch('{{ route("abordajes.validar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ codigo_qr: codigoQR })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        reservaActual = data.datos;
                        mostrarDatosPasajero(data.datos);
                        mostrarAlerta(data.message, data.tipo_alerta);
                    } else {
                        mostrarAlerta(data.message, data.tipo_alerta);
                        reanudarEscaner();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al validar el código QR', 'error');
                    reanudarEscaner();
                });
        }

        function reanudarEscaner() {
            if (html5QrCode && !isScanning) {
                setTimeout(() => {
                    html5QrCode.resume();
                    isScanning = true;
                }, 2000);
            }
        }

        function mostrarDatosPasajero(datos) {
            let html = '';

            if (datos.autorizacion_menor) {
                const m = datos.autorizacion_menor;
                html += '<div class="alert alert-warning mb-3">';
                html += '<h6><i class="fas fa-child me-2"></i>MENOR ' + (m.es_extranjero ? 'EXTRANJERO' : 'HONDUREÑO') + '</h6><hr>';
                html += '<p class="mb-1"><strong>DNI:</strong> ' + (m.menor_dni || 'N/A') + '</p>';
                html += '<p class="mb-1"><strong>Edad:</strong> ' + m.menor_edad + ' años</p>';
                if (m.es_extranjero) {
                    html += '<hr><h6 class="text-success"><i class="fas fa-check-circle me-2"></i>Autorización Válida</h6>';
                    html += '<p class="mb-1"><strong>Tutor:</strong> ' + m.tutor_nombre + '</p>';
                    html += '<p class="mb-1"><strong>Código:</strong> <span class="badge bg-success">' + m.codigo_autorizacion + '</span></p>';
                    html += '<hr><p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-1"></i><strong>VERIFICAR DNI del tutor</strong></p>';
                } else {
                    html += '<hr><p class="text-info mb-0"><i class="fas fa-info-circle me-1"></i>No requiere autorización</p>';
                }
                html += '</div>';
            }

            if (datos.pasajero.para_tercero) {
                html += '<div class="mb-3"><div class="alert alert-info mb-2"><strong><i class="fas fa-user-friends me-2"></i>Reserva para Tercero</strong></div>';
                html += '<h6 class="text-primary">Usuario que Reservó</h6>';
                html += '<p class="mb-1"><strong>Nombre:</strong> ' + datos.pasajero.usuario_nombre + '</p>';
                html += '<hr><h6 class="text-primary">Pasajero que Viaja</h6>';
                html += '<p class="mb-1"><strong>Nombre:</strong> ' + datos.pasajero.tercero_nombre + '</p>';
                html += '<p class="mb-1"><strong>Doc:</strong> ' + datos.pasajero.tercero_tipo_doc + ': ' + datos.pasajero.tercero_num_doc + '</p>';
                html += '<p class="mb-1"><strong>País:</strong> ' + datos.pasajero.tercero_pais + '</p></div>';
            } else {
                html += '<div class="mb-3"><h6 class="text-primary"><i class="fas fa-user me-2"></i>Pasajero</h6>';
                html += '<p class="mb-1"><strong>Nombre:</strong> ' + datos.pasajero.nombre_completo + '</p>';
                html += '<p class="mb-1"><strong>DNI:</strong> ' + (datos.pasajero.dni || 'N/A') + '</p>';
                html += '<p class="mb-1"><strong>Email:</strong> ' + datos.pasajero.email + '</p></div>';
            }

            html += '<hr><div class="mb-3"><h6 class="text-primary"><i class="fas fa-bus me-2"></i>Viaje</h6>';
            html += '<p class="mb-1"><strong>Ruta:</strong> ' + datos.viaje.ruta + '</p>';
            html += '<p class="mb-1"><strong>Fecha:</strong> ' + datos.viaje.fecha_salida + '</p>';
            html += '<p class="mb-1"><strong>Servicio:</strong> <span class="badge bg-info text-dark">' + (datos.viaje.tipo_servicio || 'N/A') + '</span></p>';

            if (datos.viaje.asientos && datos.viaje.asientos.length > 0) {
                const nums = datos.viaje.asientos.join(', #');
                html += '<p class="mb-1"><strong>Asientos:</strong> <span class="badge bg-primary">' + datos.viaje.asientos.length + '</span> #' + nums + '</p>';
            }
            html += '</div>';

            if (datos.servicios_adicionales && datos.servicios_adicionales.length > 0) {
                html += '<hr><div class="mb-3"><h6 class="text-primary">Servicios Adicionales</h6>';
                datos.servicios_adicionales.forEach(s => {
                    html += '<span class="badge bg-success me-1">' + s.nombre + ' (x' + s.cantidad + ')</span>';
                });
                html += '</div>';
            }

            html += '<div class="alert alert-info mt-3"><i class="fas fa-info-circle me-2"></i><strong>Código:</strong> ' + datos.codigo_qr + '</div>';

            document.getElementById('datosPasajero').innerHTML = html;
            document.getElementById('areaDatos').style.display = 'block';
        }

        function confirmarAbordaje() {
            if (!reservaActual) {
                mostrarAlerta('No hay datos de reserva', 'error');
                return;
            }

            const obs = document.getElementById('observaciones').value;
            mostrarAlerta('Confirmando...', 'info');

            fetch('{{ route("abordajes.confirmar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    reserva_id: reservaActual.reserva_id,
                    codigo_qr: reservaActual.codigo_qr,
                    observaciones: obs
                })
            })
                .then(response => response.json())
                .then(data => {
                    mostrarAlerta(data.message, data.tipo_alerta);
                    if (data.success) {
                        setTimeout(() => {
                            cancelarValidacion();
                            reanudarEscaner();
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al confirmar', 'error');
                });
        }

        function cancelarValidacion() {
            reservaActual = null;
            document.getElementById('areaDatos').style.display = 'none';
            document.getElementById('codigoManual').value = '';
            document.getElementById('observaciones').value = '';
            document.getElementById('areaAlertas').innerHTML = '';
            reanudarEscaner();
        }

        function mostrarAlerta(mensaje, tipo) {
            const iconos = { 'success': 'check-circle', 'error': 'exclamation-circle', 'warning': 'exclamation-triangle', 'info': 'info-circle' };
            const colores = { 'success': 'success', 'error': 'danger', 'warning': 'warning', 'info': 'info' };

            const html = '<div class="alert alert-' + colores[tipo] + ' alert-dismissible fade show"><i class="fas fa-' + iconos[tipo] + ' me-2"></i>' + mensaje + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';

            document.getElementById('areaAlertas').innerHTML = html;
        }

        window.addEventListener('beforeunload', function() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop();
            }
        });
    </script>
@endsection
