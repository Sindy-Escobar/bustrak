@extends('layouts.layoutuser')

@section('contenido')
    <div class="container-fluid py-4">
        {{-- Header de éxito --}}
        @if($reserva->es_menor)
            @php
                // Determinar país del pasajero
                $paisPasajero = $reserva->para_tercero
                    ? $reserva->tercero_pais
                    : ($reserva->user->pais ?? 'Honduras');

                $esHondureno = strtolower(trim($paisPasajero)) === 'honduras';
                $esMenorExtranjero = !$esHondureno;
            @endphp

            @if($esMenorExtranjero)
                {{-- Menor extranjero - Mensaje de ÉXITO --}}
                <div class="alert alert-success border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                        <div>
                            <strong class="text-success">Autorización confirmada:</strong>
                            La autorización del tutor legal ha sido registrada correctamente. El menor puede viajar sin restricciones.
                        </div>
                    </div>
                </div>
            @else
                {{-- Menor hondureño --}}
                <div class="alert alert-info border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <strong>Menor de edad hondureño:</strong>
                            No requiere autorización adicional para viajar.
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                    <i class="fas fa-check-circle fa-2x text-white"></i>
                    <div>
                        <h5 class="fw-bold text-white mb-0">¡Reserva Confirmada!</h5>
                        <small class="text-white" style="opacity: 0.9;">Tu reserva ha sido procesada exitosamente</small>
                    </div>
                </div>
            </div>
        </div>

        {{--  BARRA DE PROGRESO - PASO 4 --}}
        @include('components.progress-stepper', ['step' => 4])

        {{-- Alerta importante --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="alert alert-warning border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <strong>Importante:</strong> Si no llega antes de la hora de salida, su boleto será cancelado automáticamente.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detalles del viaje --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header" style="background-color: #3b82f6; color: white;">
                        <h5 class="mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>Detalles de tu Reserva
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        {{-- Código de reserva destacado --}}
                        <div class="text-center mb-4 p-4" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 10px;">
                            <small class="text-muted d-block mb-2">Código de Reserva</small>
                            <h2 class="fw-bold mb-0" style="color: #3b82f6; letter-spacing: 2px;">
                                {{ $reserva->codigo_reserva }}
                            </h2>
                        </div>

                        {{-- Información del viaje --}}
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <small class="text-muted d-block">Origen</small>
                                <span class="fw-semibold">{{ $reserva->viaje->origen->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Destino</small>
                                <span class="fw-semibold">{{ $reserva->viaje->destino->nombre ?? 'N/A' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Fecha y Hora de Salida</small>
                                <span class="fw-semibold">
                                    <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                    {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}
                                </span>
                            </div>

                            <div class="col-6">
                                <small class="text-muted d-block">Cantidad y numero de asientos reservados</small>
                                @if($reserva->asiento_id)
                                    {{-- Reserva antigua con asiento único --}}
                                    <span class="fw-bold text-success">
                                        <i class="fas fa-chair me-1"></i>#{{ $reserva->asiento->numero_asiento }}
                                    </span>
                                @else
                                    {{-- Reserva nueva con cantidad --}}
                                    <span class="fw-bold text-success">
                                        <i class="fas fa-chair me-1"></i>{{ $reserva->cantidad_asientos }}
                                        {{ $reserva->cantidad_asientos == 1 ? 'asiento' : 'asientos' }}
                                    </span>
                                    @php
                                        $asientosReservados = \App\Models\Asiento::where('reserva_id', $reserva->id)
                                            ->orderBy('numero_asiento')
                                            ->pluck('numero_asiento');
                                    @endphp
                                    @if($asientosReservados->isNotEmpty())
                                        <br>
                                        <small class="text-muted">
                                            Números: #{{ $asientosReservados->implode(', #') }}
                                        </small>
                                    @endif
                                @endif
                            </div>

                            <div class="col-12">
                                <small class="text-muted d-block">Tipo de Servicio</small>
                                <span class="fw-semibold">
                                    <i class="fas fa-bus me-1 text-primary"></i>
                                    {{ $reserva->tipoServicio->nombre ?? 'No especificado' }}
                                </span>
                            </div>

                            {{-- Servicios adicionales --}}
                            @if($reserva->serviciosAdicionales->isNotEmpty())
                                <div class="col-12">
                                    <small class="text-muted d-block mb-2">Servicios Adicionales</small>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($reserva->serviciosAdicionales as $servicio)
                                            <span class="badge bg-info">
                                                <i class="{{ $servicio->icono }} me-1"></i>
                                                {{ $servicio->nombre }} (x{{ $servicio->pivot->cantidad }})
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ✅ TOTAL A PAGAR --}}
                        <div class="mt-4 p-4 rounded" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #3b82f6;">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h5 class="fw-bold mb-3" style="color: #3b82f6;">
                                        <i class="fas fa-calculator me-2"></i>Resumen de Pago
                                    </h5>
                                </div>

                                @php
                                    // Calcular precio base de asientos
                                    $tarifaBase = $reserva->tipoServicio->tarifa_base ?? 0;
                                    $cantidadAsientos = $reserva->cantidad_asientos ?? 1;
                                    $subtotalAsientos = $tarifaBase * $cantidadAsientos;

                                    // Calcular total de servicios adicionales
                                    $totalServicios = $reserva->serviciosAdicionales->sum(function($servicio) {
                                        return $servicio->pivot->precio_unitario * $servicio->pivot->cantidad;
                                    });

                                    // Total general
                                    $totalGeneral = $subtotalAsientos + $totalServicios;
                                @endphp

                                {{-- Desglose --}}
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Asientos ({{ $cantidadAsientos }})</small>
                                    <span class="fw-semibold">
                                        L. {{ number_format($tarifaBase, 2) }} x {{ $cantidadAsientos }} =
                                        <strong style="color: #3b82f6;">L. {{ number_format($subtotalAsientos, 2) }}</strong>
                                    </span>
                                </div>

                                @if($totalServicios > 0)
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Servicios Adicionales</small>
                                        <span class="fw-semibold" style="color: #3b82f6;">
                                            L. {{ number_format($totalServicios, 2) }}
                                        </span>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <hr class="my-2">
                                </div>

                                {{-- Total --}}
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="fw-bold mb-0" style="color: #3b82f6;">
                                            <i class="fas fa-money-bill-wave me-2"></i>Total a Pagar:
                                        </h4>
                                        <h3 class="fw-bold mb-0" style="color: #3b82f6;">
                                            L. {{ number_format($totalGeneral, 2) }}
                                        </h3>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>Precio incluye impuestos
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- ✅ ESTADO DE AUTORIZACIÓN --}}
                        @if($reserva->es_menor)
                            @php
                                // Determinar el país del pasajero
                                $paisPasajero = $reserva->para_tercero
                                    ? $reserva->tercero_pais
                                    : ($reserva->user->pais ?? 'Honduras');

                                $esHondureno = strtolower(trim($paisPasajero)) === 'honduras';
                                $necesitaAutorizacion = !$esHondureno;
                            @endphp

                            @if($necesitaAutorizacion)
                                {{-- Menor extranjero - Autorización CONFIRMADA --}}
                                <div class="mt-4 p-4 rounded border-2 border-success" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                        <div class="flex-grow-1">
                                            <h5 class="fw-bold text-success mb-2">
                                                <i class="fas fa-shield-alt me-2"></i>Autorización Confirmada
                                            </h5>
                                            <p class="mb-0">
                                                <strong>Estado:</strong> Aprobada ✓<br>
                                                <strong>Tipo:</strong> Autorización de tutor legal para menor extranjero<br>
                                                <strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}
                                            </p>
                                            <small class="text-success d-block mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                La autorización del tutor legal ha sido registrada correctamente. El menor puede viajar sin restricciones.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Menor hondureño - No requiere autorización --}}
                                <div class="mt-4 p-4 rounded border-2 border-info" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-info-circle fa-2x text-info"></i>
                                        <div>
                                            <h6 class="fw-bold text-info mb-1">Menor de Edad Hondureño</h6>
                                            <p class="mb-0 small">No requiere autorización adicional para viajar dentro del país.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Código QR --}}
                        <div class="text-center p-4 bg-light rounded mt-4">
                            <p class="text-muted mb-3">
                                <i class="fas fa-qrcode me-2"></i>
                                <strong>Escanea este código al abordar</strong>
                            </p>
                            <div class="d-inline-block p-3 bg-white rounded shadow-sm">
                                {!! $qrCode !!}
                            </div>
                            <p class="text-muted small mt-3 mb-0">
                                Guarda este código o toma una captura de pantalla
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between gap-3">
                    <div class="d-flex gap-3">
                        <a href="{{ route('cliente.reserva.create') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Nueva Reserva
                        </a>
                        <a href="{{ route('cliente.reserva.descargar', $reserva->id) }}" class="btn btn-success btn-lg">
                            <i class="fas fa-download me-2"></i>Descargar Boleto PDF
                        </a>
                    </div>
                    <a href="{{ route('cliente.historial') }}" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); border: none;">
                        <i class="fas fa-history me-2"></i>Ver Mis Reservas
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Modal QR (opcional - se muestra automáticamente) --}}
    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background-color: #3b82f6; color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-qrcode me-2"></i>Código QR de Reserva
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        {!! $qrCode !!}
                    </div>
                    <p class="mb-2"><strong>Código:</strong> {{ $reserva->codigo_reserva }}</p>
                    <p class="text-muted small mb-0">Presenta este código al abordar el bus</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mostrar modal automáticamente
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('qrModal'));
            myModal.show();

            // Limpiar datos guardados
            localStorage.removeItem('reserva_origen');
            localStorage.removeItem('reserva_destino');
            localStorage.removeItem('reserva_fecha_nac');
        });
    </script>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
@endsection
