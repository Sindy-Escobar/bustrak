@extends('layouts.layoutuser')

@section('contenido')
    <div class="container-fluid py-4">

        {{-- Header con estado --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                @if($pago->estado === 'aprobado')
                    <div class="header-card-success">
                        <div class="header-content">
                            <div class="header-icon-wrapper">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h2 class="header-title"><i class="fas fa-check-circle me-2"></i>¡Metodo de pago registrado!</h2>
                                <p class="header-subtitle">Tu pago ha sido procesado exitosamente</p>
                            </div>
                        </div>
                        <div class="header-decoration"></div>
                    </div>
                @else
                    <div class="header-card-blue">
                        <div class="header-content">
                            <div class="header-icon-wrapper">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h2 class="header-title"><i class="fas fa-clock me-2"></i>¡Metodo de pago registrado!</h2>
                                <p class="header-subtitle">Tu pago está pendiente de confirmación</p>
                            </div>
                        </div>
                        <div class="header-decoration"></div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Card Principal --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Factura de Pago
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        {{-- Código de transacción destacado --}}
                        <div class="transaction-code-box mb-4">
                            <div class="text-center">
                                <small class="text-muted d-block mb-2"><i class="fas fa-barcode me-1"></i>Código de Transacción</small>
                                <h2 class="transaction-code mb-3">
                                    {{ $pago->codigo_transaccion }}
                                </h2>
                                <span class="badge-status {{ $pago->estado === 'aprobado' ? 'badge-success' : ($pago->estado === 'rechazado' ? 'badge-danger' : 'badge-warning') }}">
                                    <i class="fas {{ $pago->estado === 'aprobado' ? 'fa-check-circle' : ($pago->estado === 'rechazado' ? 'fa-times-circle' : 'fa-clock') }} me-2"></i>
                                    {{ strtoupper($pago->estado) }}
                                </span>
                            </div>
                        </div>

                        {{-- Información del Pago --}}
                        <div class="info-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-wallet me-2"></i>Información del Pago
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <small class="info-label"><i class="fas fa-credit-card me-1"></i>Método de Pago</small>
                                        <div class="info-value">
                                            @switch($pago->metodo_pago)
                                                @case('efectivo')
                                                    <i class="fas fa-money-bill-wave text-success me-2"></i>Efectivo
                                                    @break
                                                @case('tarjeta_credito')
                                                    <i class="fas fa-credit-card text-primary me-2"></i>Tarjeta de Crédito/Debito
                                                    @break
                                                @case('tarjeta_debito')
                                                    <i class="fas fa-credit-card text-info me-2"></i>Tarjeta de Débito
                                                    @break
                                                @case('transferencia')
                                                    <i class="fas fa-university text-primary me-2"></i>Transferencia Bancaria
                                                    @break
                                                @case('terminal')
                                                    <i class="fas fa-cash-register text-warning me-2"></i>Terminal
                                                    @break
                                                @default
                                                    {{ ucfirst($pago->metodo_pago) }}
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <small class="info-label"><i class="fas fa-calendar me-1"></i>Fecha de Pago</small>
                                        <div class="info-value">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                                @if($pago->numero_tarjeta_ultimos4)
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <small class="info-label"><i class="fas fa-credit-card me-1"></i>Tarjeta</small>
                                            <div class="info-value">
                                                <i class="fas fa-credit-card text-primary me-2"></i>
                                                **** **** **** {{ $pago->numero_tarjeta_ultimos4 }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($pago->referencia_bancaria)
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <small class="info-label"><i class="fas fa-hashtag me-1"></i>Referencia</small>
                                            <div class="info-value">
                                                <i class="fas fa-hashtag text-primary me-2"></i>
                                                {{ $pago->referencia_bancaria }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        {{-- Información de la Reserva --}}
                        <div class="info-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-ticket-alt me-2"></i>Detalles de la Reserva
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <small class="info-label"><i class="fas fa-qrcode me-1"></i>Código de Reserva</small>
                                        <div class="info-value text-primary fw-bold">
                                            {{ $pago->reserva->codigo_reserva }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <small class="info-label"><i class="fas fa-bus me-1"></i>Tipo de Servicio</small>
                                        <div class="info-value">
                                            <span class="badge bg-info">{{ $pago->reserva->tipoServicio->nombre ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <small class="info-label"><i class="fas fa-route me-1"></i>Ruta</small>
                                        <div class="info-value">
                                            <i class="fas fa-map-marker-alt text-success me-1"></i>
                                            {{ $pago->reserva->viaje->origen->nombre ?? 'N/A' }}
                                            <i class="fas fa-arrow-right mx-2 text-muted" style="font-size: 0.8rem;"></i>
                                            <i class="fas fa-flag-checkered text-danger me-1"></i>
                                            {{ $pago->reserva->viaje->destino->nombre ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <small class="info-label"><i class="fas fa-calendar-day me-1"></i>Fecha de Salida</small>
                                        <div class="info-value">
                                            <i class="fas fa-calendar text-primary me-2"></i>
                                            {{ \Carbon\Carbon::parse($pago->reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <small class="info-label"><i class="fas fa-chair me-1"></i>Cantidad de asientos</small>
                                        <div class="info-value">
                                            <span class="badge bg-primary">
                                                {{ $pago->reserva->cantidad_asientos }}
                                                {{ $pago->reserva->cantidad_asientos == 1 ? 'asiento' : 'asientos' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- RESUMEN DE PAGO COMPLETO --}}
                        <div class="info-section mb-4">
                            <h6 class="section-title">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Resumen de Pago
                            </h6>

                            @php
                                $tarifaBase = $pago->reserva->tipoServicio->tarifa_base ?? 0;
                                $cantidadAsientos = $pago->reserva->cantidad_asientos ?? 1;
                                $subtotalAsientos = $tarifaBase * $cantidadAsientos;
                                $serviciosAdicionales = $pago->reserva->serviciosAdicionales;
                                $totalServicios = $serviciosAdicionales->sum(function($s) {
                                    return $s->pivot->precio_unitario * $s->pivot->cantidad;
                                });
                            @endphp

                            {{-- Detalles de asientos --}}
                            <div class="payment-summary-box mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">
                                        <i class="fas fa-chair me-2"></i>
                                        {{ $cantidadAsientos }} {{ $cantidadAsientos == 1 ? 'Asiento' : 'Asientos' }}
                                    </span>
                                    <span class="fw-semibold">L. {{ number_format($tarifaBase, 2) }} c/u</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Subtotal Asientos:</span>
                                    <strong>L. {{ number_format($subtotalAsientos, 2) }}</strong>
                                </div>
                            </div>

                            {{-- Servicios adicionales --}}
                            @if($serviciosAdicionales->count() > 0)
                                <div class="payment-summary-box mb-3">
                                    <div class="mb-2">
                                        <strong class="text-primary">
                                            <i class="fas fa-plus-circle me-2"></i>Servicios Adicionales:
                                        </strong>
                                    </div>
                                    @foreach($serviciosAdicionales as $servicio)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">
                                                <i class="fas fa-check-circle text-success me-1" style="font-size: 0.8rem;"></i>
                                                {{ $servicio->nombre }} (x{{ $servicio->pivot->cantidad }})
                                            </span>
                                            <span class="text-muted">
                                                L. {{ number_format($servicio->pivot->precio_unitario, 2) }} c/u
                                            </span>
                                        </div>
                                    @endforeach
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted">Subtotal Servicios:</span>
                                        <strong>L. {{ number_format($totalServicios, 2) }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr>

                        {{-- Monto Total - AZUL CLARO --}}
                        <div class="total-box-light-blue">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold" style="color: #3b82f6;">
                                    <i class="fas fa-dollar-sign me-2"></i>Total a Pagar:
                                </h5>
                                <h3 class="mb-0 fw-bold" style="color: #3b82f6;">
                                    L. {{ number_format($pago->monto, 2) }}
                                </h3>
                            </div>
                        </div>

                        {{-- Mensajes según estado --}}
                        @if($pago->estado === 'pendiente')
                            <div class="alert alert-warning mt-4 d-flex align-items-start">
                                <i class="fas fa-clock me-3" style="font-size: 1.5rem; margin-top: 2px;"></i>
                                <div>
                                    <strong>Pago Pendiente de Confirmación</strong>
                                    <p class="mb-0 mt-2">
                                        @switch($pago->metodo_pago)
                                            @case('efectivo')
                                                Deberás pagar el monto total en efectivo al abordar el bus. Por favor, lleva el monto exacto para realizar tu viaje.
                                                @break
                                            @case('terminal')
                                                Dirígete a nuestra terminal para completar el pago con tu tarjeta. Presenta tu código de reserva: <strong>{{ $pago->reserva->codigo_reserva }}</strong>
                                                @break
                                            @case('transferencia')
                                                Puedes mostrar tu comprobante de pago al abordar el bus. Presenta tu codigo de reserva.
                                                @break
                                            @default
                                                Puedes mostrar tu comprobante de pago al abordar el bus. Presenta tu codigo de reserva.
                                        @endswitch
                                    </p>
                                </div>
                            </div>
                        @elseif($pago->estado === 'aprobado')
                            <div class="alert alert-success mt-4 d-flex align-items-start">
                                <i class="fas fa-check-circle me-3" style="font-size: 1.5rem; margin-top: 2px;"></i>
                                <div>
                                    <strong>¡Método de pago Confirmado!</strong>
                                    <p class="mb-0 mt-2">
                                        Tu selección ha sido procesada exitosamente. Tu reserva está confirmada y lista para el viaje.
                                        Puedes acercarte a una de nuestras ventanillas en la terminal para realizar tu pago o puedes mostrar tu comprobante al momento de abordar.
                                        Puedes descargar tu boleto desde tu historial de reservas.
                                    </p>
                                </div>
                            </div>
                        @elseif($pago->estado === 'rechazado')
                            <div class="alert alert-danger mt-4 d-flex align-items-start">
                                <i class="fas fa-times-circle me-3" style="font-size: 1.5rem; margin-top: 2px;"></i>
                                <div>
                                    <strong>Pago Rechazado</strong>
                                    <p class="mb-0 mt-2">
                                        {{ $pago->observaciones ?? 'Tu pago no pudo ser procesado. Por favor, intenta nuevamente con otro método de pago o contacta con soporte.' }}
                                    </p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('cliente.historial') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-history me-2"></i>Ver Mis Reservas
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('cliente.reserva.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>Nueva Reserva
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Headers */
        .header-card-success {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
            position: relative;
            overflow: hidden;
        }

        /* Header AZUL CLARO para "Pago Registrado" */
        .header-card-blue {
            background: linear-gradient(135deg, #60a5fa 0%, #93c5fd 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(96, 165, 250, 0.3);
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
            font-size: 2.5rem;
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

        /* Transaction Code Box - AZUL */
        .transaction-code-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 3px dashed #3b82f6;
            border-radius: 15px;
            padding: 2rem;
        }

        .transaction-code {
            font-family: 'Courier New', monospace;
            color: #1e40af;
            font-weight: 700;
            letter-spacing: 3px;
            font-size: 1.75rem;
        }

        .badge-status {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .badge-success {
            background: #10b981;
            color: white;
        }

        /* Badge AMARILLO solo para PENDIENTE */
        .badge-warning {
            background: #f59e0b;
            color: white;
        }

        .badge-danger {
            background: #ef4444;
            color: white;
        }

        /* Info Sections */
        .info-section {
            padding: 1rem 0;
        }

        .section-title {
            color: #3b82f6;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .info-item {
            margin-bottom: 0.5rem;
        }

        .info-label {
            display: block;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 600;
            color: #1e293b;
            font-size: 1rem;
        }

        /* Payment Summary Box */
        .payment-summary-box {
            background: #f9fafb;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #3b82f6;
        }

        /* Total Box - AZUL CLARO */
        .total-box-light-blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 3px solid #60a5fa;
            border-radius: 12px;
            padding: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .transaction-code {
                font-size: 1.25rem;
                letter-spacing: 1px;
            }

            .header-title {
                font-size: 1.5rem;
            }

            .header-icon-wrapper {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }
        }
    </style>
@endsection
