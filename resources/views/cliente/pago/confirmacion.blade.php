@extends('layouts.layoutuser')

@section('contenido')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(16,185,129,0.2); position: relative; overflow: hidden;">
                    <div style="display: flex; align-items: center; gap: 1.5rem; position: relative; z-index: 2;">
                        <div style="width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h2 style="color: white; font-weight: 700; margin: 0; font-size: 1.75rem;">
                                @if($pago->estado === 'aprobado')
                                    ¡Pago Confirmado!
                                @else
                                    Pago Registrado
                                @endif
                            </h2>
                            <p style="color: rgba(255,255,255,0.95); margin: 0;">
                                @if($pago->estado === 'aprobado')
                                    Tu pago ha sido procesado exitosamente
                                @else
                                    Tu pago está pendiente de confirmación
                                @endif
                            </p>
                        </div>
                    </div>
                    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header" style="background-color: #10b981; color: white;">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Comprobante de Pago
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        {{-- Código de transacción --}}
                        <div class="text-center mb-4 p-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 10px; border: 2px solid #10b981;">
                            <small class="text-muted d-block mb-1">Código de Transacción</small>
                            <h3 class="fw-bold mb-1" style="color: #10b981; letter-spacing: 2px;">
                                {{ $pago->codigo_transaccion }}
                            </h3>
                            <span class="badge {{ $pago->estado === 'aprobado' ? 'bg-success' : ($pago->estado === 'rechazado' ? 'bg-danger' : 'bg-warning text-dark') }} px-3 py-2">
                                <i class="fas {{ $pago->estado === 'aprobado' ? 'fa-check-circle' : ($pago->estado === 'rechazado' ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                                {{ ucfirst($pago->estado) }}
                            </span>
                        </div>

                        {{-- Info del pago --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Método de Pago</small>
                                <span class="fw-semibold">
                                    @switch($pago->metodo_pago)
                                        @case('efectivo') <i class="fas fa-money-bill-wave me-1 text-success"></i> Efectivo @break
                                        @case('tarjeta_credito') <i class="fas fa-credit-card me-1 text-primary"></i> Tarjeta de Crédito @break
                                        @case('tarjeta_debito') <i class="fas fa-credit-card me-1 text-info"></i> Tarjeta de Débito @break
                                        @case('transferencia') <i class="fas fa-university me-1 text-primary"></i> Transferencia Bancaria @break
                                        @case('terminal') <i class="fas fa-cash-register me-1 text-warning"></i> Pago en Terminal @break
                                        @default {{ ucfirst($pago->metodo_pago) }}
                                    @endswitch
                                </span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Fecha de Pago</small>
                                <span class="fw-semibold">
                                    <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                    {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            @if($pago->numero_tarjeta_ultimos4)
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Tarjeta</small>
                                    <span class="fw-semibold">**** **** **** {{ $pago->numero_tarjeta_ultimos4 }}</span>
                                </div>
                            @endif
                        </div>

                        <hr>

                        {{-- Info de la reserva --}}
                        <h6 class="fw-bold mb-3" style="color: #3b82f6;">
                            <i class="fas fa-ticket-alt me-2"></i>Detalles de la Reserva
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Código de Reserva</small>
                                <span class="fw-bold" style="color: #3b82f6;">{{ $pago->reserva->codigo_reserva }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Ruta</small>
                                <span class="fw-semibold">
                                    {{ $pago->reserva->viaje->origen->nombre ?? 'N/A' }}
                                    <i class="fas fa-arrow-right mx-1 text-muted" style="font-size: 0.8rem;"></i>
                                    {{ $pago->reserva->viaje->destino->nombre ?? 'N/A' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Fecha de Salida</small>
                                <span class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($pago->reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Tipo de Servicio</small>
                                <span class="fw-semibold">{{ $pago->reserva->tipoServicio->nombre ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <hr>

                        {{-- Total --}}
                        <div class="p-4 rounded" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #10b981;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold mb-0" style="color: #10b981;">
                                    <i class="fas fa-money-bill-wave me-2"></i>Total Pagado:
                                </h5>
                                <h3 class="fw-bold mb-0" style="color: #10b981;">
                                    L. {{ number_format($pago->monto, 2) }}
                                </h3>
                            </div>
                        </div>

                        {{-- Mensaje según estado --}}
                        @if($pago->estado === 'pendiente')
                            <div class="alert alert-warning mt-4">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Pago Pendiente:</strong>
                                @switch($pago->metodo_pago)
                                    @case('efectivo') Deberás pagar al abordar el bus. @break
                                    @case('terminal') Dirígete a la terminal para completar el pago. @break
                                    @case('transferencia') Tu transferencia está siendo verificada. Te notificaremos cuando sea aprobada. @break
                                    @default Tu pago está pendiente de confirmación.
                                @endswitch
                            </div>
                        @elseif($pago->estado === 'rechazado')
                            <div class="alert alert-danger mt-4">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>Pago Rechazado:</strong> {{ $pago->observaciones ?? 'Tu pago no pudo ser procesado. Por favor intenta nuevamente.' }}
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between gap-3 mt-4">
                    <a href="{{ route('cliente.historial') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-history me-2"></i>Ver Mis Reservas
                    </a>
                    <a href="{{ route('cliente.reserva.create') }}" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); border: none;">
                        <i class="fas fa-plus me-2"></i>Nueva Reserva
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
