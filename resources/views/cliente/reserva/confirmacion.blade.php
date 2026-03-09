@extends('layouts.layoutuser')

@section('title', 'Reserva Confirmada')

@section('contenido')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-xl-5">
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white text-center py-3">
                        <h4 class="mb-0">
                            <i class="fas fa-check-circle fa-lg me-2"></i>
                            ¡Reserva Confirmada!
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-success text-center mb-3 py-2" role="alert">
                            <strong>¡Listo!</strong> Tu reserva ha sido guardada.
                        </div>
                        <div class="alert alert-warning text-center mb-4 py-2" role="alert">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Recuerda:</strong> Llega antes de la hora de salida.
                        </div>
                        <div class="row g-3 mb-4 text-center">
                            <div class="col-12">
                                <small class="text-muted d-block">Código de Reserva</small>
                                <h5 class="fw-bold text-primary">{{ $reserva->codigo_reserva }}</h5>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Origen</small>
                                <span class="fw-semibold">{{ $reserva->viaje->origen->nombre }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Destino</small>
                                <span class="fw-semibold">{{ $reserva->viaje->destino->nombre }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Salida</small>
                                <span>{{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m H:i') }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Asiento</small>
                                <span class="fw-bold">#{{ $reserva->asiento->numero_asiento }}</span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block">Tipo de Servicio</small>
                                <span class="fw-semibold">
        <i class="fas fa-bus me-1 text-primary"></i>
        {{ $reserva->tipoServicio->nombre ?? 'No especificado' }}
    </span>
                            </div>
                        </div>
                        <div class="text-center mb-4">
                            <div class="bg-white p-3 d-inline-block rounded shadow-sm border">
                                {!! $qrCode !!}
                            </div>
                            <p class="text-muted mt-2 mb-0"><small>Escanea en la terminal</small></p>

                        </div>
                        {{-- HU14: Confirmación de datos antes de pagar --}}
                        @if($reserva->para_tercero)
                            <div class="alert alert-info text-start mb-3 py-2">
                                <strong><i class="fas fa-user me-1"></i> Boleto generado para:</strong><br>
                                <span>{{ $reserva->tercero_nombre }}</span><br>
                                <small>{{ $reserva->tercero_tipo_doc }} — {{ $reserva->tercero_num_doc }}</small><br>
                                <small>Tel: {{ $reserva->tercero_telefono }}</small>
                            </div>
                        @endif

                        {{-- Botón descargar PDF --}}
                        <div class="d-grid mb-3">
                            <a href="{{ route('cliente.reserva.descargar', $reserva->id) }}"
                               class="btn btn-success">
                                <i class="fas fa-download me-1"></i> Descargar Boleto PDF
                            </a>
                        </div>

                        <div class="d-grid d-md-flex justify-content-center gap-2">
                            <a href="{{ route('cliente.historial') }}" class="btn btn-primary px-4">
                                <i class="fas fa-history me-1"></i> Historial
                            </a>
                            <a href="{{ route('cliente.reserva.create') }}" class="btn btn-outline-success px-4">
                                <i class="fas fa-plus me-1"></i> Nueva
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-center py-2">
                        <small class="text-muted"><i class="fas fa-camera me-1"></i> Toma una captura del código QR</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .barcode svg { width: 350px !important; height: 350px !important; }
        @media (max-width: 576px) {
            .barcode svg { width: 280px !important; height: 280px !important; }
        }
    </style>

    <script>
        localStorage.removeItem('reserva_origen');
        localStorage.removeItem('reserva_destino');
        localStorage.removeItem('reserva_fecha_nac');
        sessionStorage.removeItem('volviendo_de_servicio');
    </script>
@endsection
