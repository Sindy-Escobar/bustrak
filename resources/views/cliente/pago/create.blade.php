@extends('layouts.layoutuser')

@section('contenido')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="header-card">
                    <div class="header-content">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <h2 class="header-title">Confirmar Método de Pago</h2>
                            <p class="header-subtitle">Selecciona cómo deseas pagar tu reserva</p>
                        </div>
                    </div>
                    <div class="header-decoration"></div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4">

                    {{-- Columna izquierda: Métodos de pago --}}
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="text-primary mb-4">
                                    <i class="fas fa-wallet me-2"></i>Selecciona tu Método de Pago
                                </h5>

                                <form action="{{ route('cliente.pago.store', $reserva->id) }}" method="POST" id="pagoForm">
                                    @csrf

                                    @php
                                        $tipoServicioNombre = strtolower($reserva->tipoServicio->nombre ?? '');
                                        $esRegularOInterurbano = str_contains($tipoServicioNombre, 'regular') ||
                                                                str_contains($tipoServicioNombre, 'interurbano');
                                    @endphp

                                    {{-- Métodos de pago simplificados --}}
                                    <div class="payment-methods mb-4">

                                        @if($esRegularOInterurbano)
                                            {{-- Solo Efectivo para Regular/Interurbano --}}
                                            <div class="alert alert-info mb-4">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Servicio {{ $reserva->tipoServicio->nombre }}:</strong>
                                                Solo se acepta pago en efectivo al abordar el bus.
                                            </div>

                                            <div class="payment-option-simple">
                                                <input type="radio" name="metodo_pago" value="efectivo" id="efectivo" checked required>
                                                <label for="efectivo">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </div>
                                                    <div class="payment-info">
                                                        <span class="payment-title">Efectivo</span>
                                                        <small class="payment-desc">Pagar al abordar el bus</small>
                                                    </div>
                                                    <div class="payment-check">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        @else
                                            {{-- Tres métodos principales --}}

                                            {{-- Tarjeta (Visa/Mastercard) --}}
                                            <div class="payment-option-simple">
                                                <input type="radio" name="metodo_pago" value="tarjeta_credito" id="tarjeta" required>
                                                <label for="tarjeta">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-credit-card"></i>
                                                    </div>
                                                    <div class="payment-info">
                                                        <span class="payment-title">Tarjeta de Crédito/Débito</span>
                                                        <small class="payment-desc">Visa, Mastercard - Aprobación inmediata</small>
                                                    </div>
                                                    <div class="payment-check">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </label>
                                            </div>

                                            {{-- Transferencia Bancaria --}}
                                            <div class="payment-option-simple">
                                                <input type="radio" name="metodo_pago" value="transferencia" id="transferencia">
                                                <label for="transferencia">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-university"></i>
                                                    </div>
                                                    <div class="payment-info">
                                                        <span class="payment-title">Transferencia Bancaria</span>
                                                        <small class="payment-desc">Requiere confirmación del banco</small>
                                                    </div>
                                                    <div class="payment-check">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </label>
                                            </div>

                                            {{-- Efectivo --}}
                                            <div class="payment-option-simple">
                                                <input type="radio" name="metodo_pago" value="efectivo" id="efectivo">
                                                <label for="efectivo">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </div>
                                                    <div class="payment-info">
                                                        <span class="payment-title">Efectivo</span>
                                                        <small class="payment-desc">Pagar al abordar el bus</small>
                                                    </div>
                                                    <div class="payment-check">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                </label>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Información adicional según método seleccionado --}}
                                    <div id="info-metodo" class="alert" style="display: none;"></div>

                                    {{-- Botones de acción --}}
                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Confirmar Pago
                                        </button>
                                        <a href="{{ route('cliente.historial') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>Cancelar
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Información de seguridad --}}
                        <div class="card border-0 shadow-sm mt-3" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-shield-alt text-primary" style="font-size: 1.5rem;"></i>
                                    <div>
                                        <small class="text-muted d-block fw-bold">Pago 100% Seguro</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Columna derecha: Resumen --}}
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                            <div class="card-body p-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-receipt me-2"></i>Resumen de Reserva
                                </h5>

                                <div class="mb-3">
                                    <small class="text-muted">Código de Reserva</small>
                                    <h6 class="text-primary fw-bold">{{ $reserva->codigo_reserva }}</h6>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Ruta</small>
                                    <p class="mb-0 fw-semibold">
                                        <i class="fas fa-map-marker-alt text-success me-1"></i>
                                        {{ $reserva->viaje->origen->nombre }}
                                        <br>
                                        <i class="fas fa-arrow-down text-muted mx-2" style="font-size: 0.8rem;"></i>
                                        <br>
                                        <i class="fas fa-flag-checkered text-danger me-1"></i>
                                        {{ $reserva->viaje->destino->nombre }}
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Fecha y Hora</small>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y') }}
                                        <br>
                                        <i class="fas fa-clock text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('H:i') }}
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Asientos</small>
                                    <p class="mb-0">
                                        <span class="badge bg-primary">
                                            {{ $reserva->cantidad_asientos }} {{ $reserva->cantidad_asientos == 1 ? 'asiento' : 'asientos' }}
                                        </span>
                                    </p>
                                </div>

                                <hr>

                                @php
                                    $tarifaBase = $reserva->tipoServicio->tarifa_base ?? 0;
                                    $subtotal = $tarifaBase * $reserva->cantidad_asientos;
                                    $servicios = $reserva->serviciosAdicionales->sum(function($s) {
                                        return $s->pivot->precio_unitario * $s->pivot->cantidad;
                                    });
                                    $total = $subtotal + $servicios;
                                @endphp

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal asientos:</span>
                                    <strong>L. {{ number_format($subtotal, 2) }}</strong>
                                </div>

                                @if($servicios > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Servicios adicionales:</span>
                                        <strong>L. {{ number_format($servicios, 2) }}</strong>
                                    </div>
                                @endif

                                <hr>

                                <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                                    <h5 class="mb-0 fw-bold" style="color: #1e40af;">Total a Pagar:</h5>
                                    <h3 class="mb-0 fw-bold" style="color: #1e40af;">L. {{ number_format($total, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metodosPago = document.querySelectorAll('input[name="metodo_pago"]');
            const infoMetodo = document.getElementById('info-metodo');

            if (metodosPago.length === 0) return;

            // Información para cada método - TODOS EN AZUL (alert-info)
            const mensajes = {
                'tarjeta_credito': {
                    clase: 'alert-info',
                    icono: 'fa-credit-card',
                    texto: '<strong>Tarjeta de Crédito/Débito:</strong> Tu pago será procesado de forma segura. Aceptamos Visa y Mastercard. La aprobación es inmediata.'
                },
                'transferencia': {
                    clase: 'alert-info',
                    icono: 'fa-university',
                    texto: '<strong>Transferencia Bancaria:</strong> Realiza tu transferencia a nuestra cuenta. Tu reserva será confirmada una vez verifiquemos el pago (1-2 horas hábiles).'
                },
                'efectivo': {
                    clase: 'alert-info',
                    icono: 'fa-money-bill-wave',
                    texto: '<strong>Pago en Efectivo:</strong> Pagarás el monto total al abordar el bus. Por favor, lleva el monto exacto para agilizar tu ingreso.'
                }
            };

            function mostrarInfo(metodo) {
                if (mensajes[metodo]) {
                    infoMetodo.className = 'alert ' + mensajes[metodo].clase;
                    infoMetodo.innerHTML = '<i class="fas ' + mensajes[metodo].icono + ' me-2"></i>' + mensajes[metodo].texto;
                    infoMetodo.style.display = 'block';
                } else {
                    infoMetodo.style.display = 'none';
                }
            }

            metodosPago.forEach(metodo => {
                metodo.addEventListener('change', function() {
                    mostrarInfo(this.value);
                });
            });

            // Mostrar info del método pre-seleccionado automáticamente
            const seleccionado = document.querySelector('input[name="metodo_pago"]:checked');
            if (seleccionado) {
                mostrarInfo(seleccionado.value);
            }

            // Si hay solo un método (efectivo), mostrarlo también
            if (metodosPago.length === 1) {
                mostrarInfo(metodosPago[0].value);
            }
        });
    </script>

    <style>
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

        /* Opciones de pago simplificadas */
        .payment-option-simple {
            margin-bottom: 15px;
        }

        .payment-option-simple input[type="radio"] {
            display: none;
        }

        .payment-option-simple label {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px 25px;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .payment-option-simple label:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }

        .payment-option-simple input[type="radio"]:checked + label {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .payment-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .payment-icon i {
            font-size: 24px;
            color: white;
        }

        .payment-info {
            flex: 1;
        }

        .payment-title {
            display: block;
            font-weight: 600;
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .payment-desc {
            display: block;
            color: #64748b;
            font-size: 0.875rem;
        }

        .payment-check {
            width: 30px;
            height: 30px;
            border: 2px solid #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .payment-check i {
            font-size: 16px;
            color: #e5e7eb;
            transition: all 0.3s ease;
        }

        .payment-option-simple input[type="radio"]:checked + label .payment-check {
            border-color: #3b82f6;
            background: #3b82f6;
        }

        .payment-option-simple input[type="radio"]:checked + label .payment-check i {
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-option-simple label {
                padding: 15px 20px;
            }

            .payment-icon {
                width: 45px;
                height: 45px;
            }

            .payment-icon i {
                font-size: 20px;
            }

            .payment-title {
                font-size: 1rem;
            }
        }
    </style>
@endsection
