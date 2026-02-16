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

                {{-- Código QR --}}
                <div class="text-center mb-4">
                    <h5 class="text-muted mb-3">Código de Autorización</h5>
                    <div class="bg-light p-4 rounded d-inline-block">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($autorizacion->codigo_autorizacion) }}"
                             alt="Código QR"
                             class="img-fluid">
                    </div>
                    <h3 class="text-primary mt-3 font-monospace">{{ $autorizacion->codigo_autorizacion }}</h3>
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
                        <li>Presenta este código QR en la terminal al momento del abordaje</li>
                        <li>El tutor debe presentarse con su DNI original</li>
                        <li>Guarda este código en tu celular o imprímelo</li>
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
