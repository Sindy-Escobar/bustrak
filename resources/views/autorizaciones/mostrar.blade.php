@extends('layouts.layoutuser')

@section('title', 'Autorización Generada')

@section('contenido')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    Autorización Generada Exitosamente
                </h4>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ✅ NUEVO: Código QR de Reserva para Check-in (MÁS IMPORTANTE) --}}
                <div class="text-center mb-4">
                    <div class="alert alert-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>IMPORTANTE:</strong> Este es el código QR que debes presentar en la terminal para el check-in/abordaje.
                    </div>
                    <h5 class="text-primary mb-3 fw-bold">
                        <i class="fas fa-qrcode me-2"></i>Código QR para Check-in/Abordaje
                    </h5>
                    <div class="bg-light p-4 rounded d-inline-block border border-primary border-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode($reserva->codigo_reserva) }}"
                             alt="Código QR Reserva"
                             class="img-fluid">
                    </div>
                    <h3 class="text-primary mt-3 font-monospace fw-bold">{{ $reserva->codigo_reserva }}</h3>
                    <p class="text-muted">Código de Reserva</p>
                </div>

                <hr class="my-4">

                {{-- Código QR de Autorización (Secundario) --}}
                <div class="text-center mb-4">
                    <h6 class="text-muted mb-3">Código de Autorización de Menor</h6>
                    <div class="bg-light p-3 rounded d-inline-block">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($autorizacion->codigo_autorizacion) }}"
                             alt="Código QR Autorización"
                             class="img-fluid">
                    </div>
                    <p class="text-muted small mt-2 mb-0">{{ $autorizacion->codigo_autorizacion }}</p>
                    <p class="text-muted small">(Para validación adicional de autorización)</p>
                </div>

                <hr>

                {{-- Información de la autorización --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Datos del Menor</h6>
                        <p class="mb-1"><strong>DNI:</strong> {{ $autorizacion->menor_dni }}</p>
                        <p class="mb-1"><strong>Fecha Nacimiento:</strong> {{ $autorizacion->menor_fecha_nacimiento->format('d/m/Y') }}</p>
                        <p class="mb-1"><strong>Edad:</strong> {{ \Carbon\Carbon::parse($autorizacion->menor_fecha_nacimiento)->age }} años</p>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Datos del Tutor</h6>
                        <p class="mb-1"><strong>Nombre:</strong> {{ $autorizacion->tutor_nombre }}</p>
                        <p class="mb-1"><strong>DNI:</strong> {{ $autorizacion->tutor_dni }}</p>
                        <p class="mb-1"><strong>Parentesco:</strong> {{ ucfirst(str_replace('_', ' ', $autorizacion->parentesco)) }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $autorizacion->tutor_email }}</p>
                    </div>
                </div>

                <hr>

                {{-- Información del viaje --}}
                <h6 class="text-muted mb-3">Información del Viaje</h6>
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Origen:</strong> {{ $reserva->viaje->origen->nombre ?? '-' }}</p>
                                <p class="mb-1"><strong>Destino:</strong> {{ $reserva->viaje->destino->nombre ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong>Asiento:</strong> #{{ $reserva->asiento->numero_asiento ?? 'S/N' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Instrucciones --}}
                <div class="alert alert-warning">
                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Instrucciones Importantes</h6>
                    <ul class="mb-0">
                        <li><strong>Presenta el CÓDIGO DE RESERVA</strong> (el QR grande azul) en la terminal al momento del abordaje</li>
                        <li>El tutor debe presentarse con su DNI original</li>
                        <li>Guarda ambos códigos en tu celular o imprímelos</li>
                        <li>Se ha enviado una copia a: <strong>{{ $autorizacion->tutor_email }}</strong></li>
                    </ul>
                </div>

                {{-- Botones --}}
                <div class="d-grid gap-2">
                    <button onclick="window.print()" class="btn btn-primary btn-lg">
                        <i class="fas fa-print me-2"></i>Imprimir Autorización
                    </button>
                    <a href="{{ route('cliente.historial') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Historial
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .btn, .alert-warning { display: none; }
        }
    </style>
@endsection
