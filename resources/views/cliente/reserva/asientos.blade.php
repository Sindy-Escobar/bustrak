@extends('layouts.layoutuser')

@section('contenido')
    <div class="container-fluid py-4">

        {{-- ENCABEZADO CON DISEÑO ORIGINAL --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-11">
                <div class="header-card">
                    <div class="header-content">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div>
                            <h2 class="header-title">Detalles de tu Reserva</h2>
                            <p class="header-subtitle">
                                Completa la información y selecciona servicios adicionales
                            </p>
                        </div>
                    </div>
                    <div class="header-decoration"></div>
                </div>
            </div>
        </div>

        {{-- BARRA DE PROGRESO - PASO 3 --}}
        @include('components.progress-stepper', ['step' => 3])

        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('cliente.reserva.store') }}" method="POST" id="reservaForm">
                            @csrf
                            <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">

                            {{-- Información del viaje --}}
                            <div class="mb-4 p-3 bg-light rounded">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-bus me-2"></i>Información del Viaje
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Origen</small>
                                        <strong>{{ $viaje->origen->nombre }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Destino</small>
                                        <strong>{{ $viaje->destino->nombre }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Fecha y Hora</small>
                                        <strong>{{ \Carbon\Carbon::parse($viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Tipo de Servicio</small>
                                        <strong>{{ $tipoServicio->nombre }}</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Cantidad de asientos --}}
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-calculator me-2"></i>¿Cuántos asientos necesitas?
                                </h6>
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <label for="cantidad_asientos" class="form-label">Cantidad de Asientos</label>
                                        <input type="number"
                                               class="form-control form-control-lg"
                                               id="cantidad_asientos"
                                               name="cantidad_asientos"
                                               min="1"
                                               max="{{ $asientosDisponibles }}"
                                               value="{{ old('cantidad_asientos', 1) }}"
                                               required>
                                        <small class="text-muted">Disponibles: {{ $asientosDisponibles }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- Resumen de pago --}}
                                        <div class="card border-primary">
                                            <div class="card-body">
                                                <h6 class="text-primary mb-3">Resumen de Pago</h6>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Precio por asiento:</span>
                                                    <strong>L. <span id="tarifaBase">{{ number_format($tipoServicio->tarifa_base, 2) }}</span></strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Cantidad:</span>
                                                    <strong><span id="displayCantidad">1</span></strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Subtotal asientos:</span>
                                                    <strong class="text-primary">L. <span id="subtotalAsientos">{{ number_format($tipoServicio->tarifa_base, 2) }}</span></strong>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Servicios adicionales:</span>
                                                    <strong class="text-primary">L. <span id="totalServicios">0.00</span></strong>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="mb-0">Total:</h5>
                                                    <h5 class="mb-0 text-success">L. <span id="totalFinal">{{ number_format($tipoServicio->tarifa_base, 2) }}</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Servicios adicionales --}}
                            @if($serviciosAdicionales->isNotEmpty())
                                <div class="mb-4">
                                    <h6 class="text-primary fw-bold mb-3">
                                        <i class="fas fa-plus-circle me-2"></i>Servicios Adicionales
                                    </h6>
                                    <div class="row g-3">
                                        @foreach($serviciosAdicionales as $servicio)
                                            <div class="col-md-6">
                                                <div class="card h-100 servicio-card" data-servicio-id="{{ $servicio->id }}" data-precio="{{ $servicio->precio }}">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-start gap-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input servicio-checkbox"
                                                                       type="checkbox"
                                                                       name="servicios_adicionales[]"
                                                                       value="{{ $servicio->id }}"
                                                                       id="servicio_{{ $servicio->id }}">
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <label class="form-check-label w-100" for="servicio_{{ $servicio->id }}">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div>
                                                                            <i class="{{ $servicio->icono }} me-2 text-primary"></i>
                                                                            <strong>{{ $servicio->nombre }}</strong>
                                                                        </div>
                                                                        <span class="badge bg-primary">L. {{ number_format($servicio->precio, 2) }}</span>
                                                                    </div>
                                                                    <small class="text-muted d-block mt-1">{{ $servicio->descripcion }}</small>
                                                                </label>

                                                                {{-- Campo de cantidad --}}
                                                                <div class="mt-3 cantidad-container" style="display: none;">
                                                                    <label class="form-label small">¿Para cuántas personas?</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <button class="btn btn-outline-secondary btn-minus" type="button">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                        <input type="number"
                                                                               class="form-control text-center servicio-cantidad"
                                                                               name="servicio_cantidad[{{ $servicio->id }}]"
                                                                               min="1"
                                                                               value="1"
                                                                               max="20">
                                                                        <button class="btn btn-outline-secondary btn-plus" type="button">
                                                                            <i class="fas fa-plus"></i>
                                                                        </button>
                                                                        <span class="input-group-text">personas</span>
                                                                    </div>
                                                                    <small class="text-muted">Subtotal: L. <span class="subtotal-servicio">{{ number_format($servicio->precio, 2) }}</span></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Información del pasajero --}}
                            <div class="mb-4">
                                <h6 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-user me-2"></i>¿Para quién es la reserva?
                                </h6>
                                <div class="btn-group w-100 mb-3" role="group">
                                    <input type="radio" class="btn-check" name="para_tercero" id="para_mi" value="0" checked>
                                    <label class="btn btn-outline-primary" for="para_mi">
                                        <i class="fas fa-user me-2"></i>Para mí
                                    </label>

                                    <input type="radio" class="btn-check" name="para_tercero" id="para_tercero" value="1">
                                    <label class="btn btn-outline-primary" for="para_tercero">
                                        <i class="fas fa-users me-2"></i>Para otra persona
                                    </label>
                                </div>

                                {{-- Campos para tercero --}}
                                <div id="campos_tercero" style="display: none;">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <h6 class="text-primary mb-3">Datos del pasajero</h6>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="tercero_nombre" id="tercero_nombre">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="tercero_apellido1" id="tercero_apellido1">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Segundo Apellido</label>
                                                    <input type="text" class="form-control" name="tercero_apellido2">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="tercero_fecha_nacimiento" id="tercero_fecha_nacimiento">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">País <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="tercero_pais" id="tercero_pais">
                                                        <option value="Honduras" selected>Honduras</option>
                                                        <option value="Guatemala">Guatemala</option>
                                                        <option value="El Salvador">El Salvador</option>
                                                        <option value="Nicaragua">Nicaragua</option>
                                                        <option value="Costa Rica">Costa Rica</option>
                                                        <option value="Panamá">Panamá</option>
                                                        <option value="México">México</option>
                                                        <option value="Estados Unidos">Estados Unidos</option>
                                                        <option value="Otro">Otro</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="tercero_tipo_doc" id="tercero_tipo_doc">
                                                        <option value="DNI">DNI / Identidad</option>
                                                        <option value="Pasaporte">Pasaporte</option>
                                                        <option value="Otro">Otro</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="tercero_num_doc" id="tercero_num_doc">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                                                    <input type="tel" class="form-control" name="tercero_telefono" id="tercero_telefono">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Email (Opcional)</label>
                                                    <input type="email" class="form-control" name="tercero_email" id="tercero_email" placeholder="correo@ejemplo.com">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{--  SECCIÓN DE AUTORIZACIÓN PARA MENORES EXTRANJEROS --}}
                                    <div id="autorizacion-section" class="mt-3" style="display:none;">
                                        <div class="alert alert-warning border-warning">
                                            <h6 class="alert-heading">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Autorización Requerida
                                            </h6>
                                            <p class="mb-2">
                                                El pasajero es menor de edad extranjero. Se requiere autorización del tutor legal.
                                            </p>
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="autorizacion_menor"
                                                       id="autorizacion_menor"
                                                       value="1">
                                                <label class="form-check-label fw-bold" for="autorizacion_menor">
                                                    Confirmar la autorizacion del menor.
                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                La autorización será registrada automáticamente al confirmar la reserva.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="d-flex justify-content-between gap-3">
                                <a href="{{ route('cliente.reserva.viajes') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Volver a Viajes Disponibles
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check me-2"></i>Confirmar Reserva
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tarifaBase = {{ $tipoServicio->tarifa_base }};
            const cantidadInput = document.getElementById('cantidad_asientos');
            const paraMiRadio = document.getElementById('para_mi');
            const paraTerceroRadio = document.getElementById('para_tercero');
            const camposTercero = document.getElementById('campos_tercero');

            //  Elementos para validación de autorización
            const fechaInput = document.getElementById('tercero_fecha_nacimiento');
            const paisInput = document.getElementById('tercero_pais');
            const autorizacionSection = document.getElementById('autorizacion-section');

            //  Función para calcular edad
            function calcularEdad(fecha) {
                const hoy = new Date();
                const nacimiento = new Date(fecha);
                let edad = hoy.getFullYear() - nacimiento.getFullYear();
                const m = hoy.getMonth() - nacimiento.getMonth();
                if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
                    edad--;
                }
                return edad;
            }

            //  Función para validar si necesita autorización
            function validarAutorizacion() {
                if (!fechaInput.value || !paisInput.value) {
                    autorizacionSection.style.display = 'none';
                    return;
                }

                const edad = calcularEdad(fechaInput.value);
                const esMenor = edad < 18;
                const esExtranjero = paisInput.value.trim().toLowerCase() !== 'honduras';

                autorizacionSection.style.display = (esMenor && esExtranjero) ? 'block' : 'none';
            }

            //  Eventos para validación de autorización
            if (fechaInput) fechaInput.addEventListener('change', validarAutorizacion);
            if (paisInput) paisInput.addEventListener('change', validarAutorizacion);

            // Mostrar/ocultar campos de tercero
            paraMiRadio.addEventListener('change', () => {
                camposTercero.style.display = 'none';
                autorizacionSection.style.display = 'none'; // Ocultar autorización
                // Remover required de campos de tercero
                document.querySelectorAll('#campos_tercero input[required]').forEach(input => {
                    input.removeAttribute('required');
                });
            });

            paraTerceroRadio.addEventListener('change', () => {
                camposTercero.style.display = 'block';
                // Agregar required a campos obligatorios
                ['tercero_nombre', 'tercero_apellido1', 'tercero_fecha_nacimiento', 'tercero_pais',
                    'tercero_tipo_doc', 'tercero_num_doc', 'tercero_telefono', 'tercero_email'].forEach(id => {
                    document.getElementById(id).setAttribute('required', 'required');
                });
            });

            // Manejo de servicios adicionales
            const servicioCards = document.querySelectorAll('.servicio-card');

            servicioCards.forEach(card => {
                const checkbox = card.querySelector('.servicio-checkbox');
                const cantidadContainer = card.querySelector('.cantidad-container');
                const cantidadInput = card.querySelector('.servicio-cantidad');
                const btnMinus = card.querySelector('.btn-minus');
                const btnPlus = card.querySelector('.btn-plus');
                const subtotalSpan = card.querySelector('.subtotal-servicio');
                const precio = parseFloat(card.dataset.precio);

                // Mostrar/ocultar campo de cantidad
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        cantidadContainer.style.display = 'block';
                        cantidadInput.value = 1;
                    } else {
                        cantidadContainer.style.display = 'none';
                        cantidadInput.value = 1;
                    }
                    calcularTotal();
                });

                // Botones +/-
                btnMinus.addEventListener('click', () => {
                    let val = parseInt(cantidadInput.value);
                    if (val > 1) {
                        cantidadInput.value = val - 1;
                        actualizarSubtotal();
                        calcularTotal();
                    }
                });

                btnPlus.addEventListener('click', () => {
                    let val = parseInt(cantidadInput.value);
                    if (val < 20) {
                        cantidadInput.value = val + 1;
                        actualizarSubtotal();
                        calcularTotal();
                    }
                });

                cantidadInput.addEventListener('input', () => {
                    actualizarSubtotal();
                    calcularTotal();
                });

                function actualizarSubtotal() {
                    const cantidad = parseInt(cantidadInput.value) || 1;
                    const subtotal = precio * cantidad;
                    subtotalSpan.textContent = subtotal.toFixed(2);
                }
            });

            // Calcular total general
            cantidadInput.addEventListener('input', calcularTotal);

            function calcularTotal() {
                const cantidad = parseInt(cantidadInput.value) || 1;
                const subtotalAsientos = tarifaBase * cantidad;

                // Calcular total de servicios
                let totalServicios = 0;
                servicioCards.forEach(card => {
                    const checkbox = card.querySelector('.servicio-checkbox');
                    if (checkbox.checked) {
                        const precio = parseFloat(card.dataset.precio);
                        const cantidadServicio = parseInt(card.querySelector('.servicio-cantidad').value) || 1;
                        totalServicios += precio * cantidadServicio;
                    }
                });

                const totalFinal = subtotalAsientos + totalServicios;

                // Actualizar UI
                document.getElementById('displayCantidad').textContent = cantidad;
                document.getElementById('subtotalAsientos').textContent = subtotalAsientos.toFixed(2);
                document.getElementById('totalServicios').textContent = totalServicios.toFixed(2);
                document.getElementById('totalFinal').textContent = totalFinal.toFixed(2);
            }

            // Calcular al cargar
            calcularTotal();
        });
    </script>

    <style>
        /* ===== HEADER CARD STYLES ===== */
        .header-card {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
            position: relative;
            overflow: hidden;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .header-icon-wrapper {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            backdrop-filter: blur(10px);
        }

        .header-title {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.75rem;
        }

        .header-subtitle {
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
            font-size: 0.95rem;
        }

        .header-decoration {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        /* ===== SERVICIOS STYLES ===== */
        .servicio-card {
            transition: all 0.3s;
        }
        .servicio-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .servicio-checkbox:checked ~ div .form-check-label {
            color: #0d6efd;
        }
    </style>
@endsection
