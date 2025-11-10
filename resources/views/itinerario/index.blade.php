@extends('layouts.layoutuser')

@section('title', 'Itinerario de Viajes')

@section('contenido')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0" style="color:#1e63b8;">
                    <i class="fas fa-route me-2"></i>Itinerario de Viajes
                </h2>
                <a href="{{ route('itinerario.pdf') }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf me-2"></i>Descargar PDF
                </a>
            </div>

            <div class="card-body">
                <h5 class="mb-4"><i class="fas fa-user me-2"></i>Pasajero: {{ $usuario->nombre_completo }}</h5>

                @forelse($reservas as $reserva)
                    <div class="border-start border-4 border-primary p-3 mb-3 rounded shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-bus me-2 text-primary"></i>
                                {{ $reserva->viaje->ruta->nombre ?? 'Ruta no disponible' }}
                            </h5>
                            <span class="badge
                            @if($reserva->estado == 'confirmado') bg-success
                            @elseif($reserva->estado == 'pendiente') bg-warning text-dark
                            @elseif($reserva->estado == 'cancelado') bg-danger
                            @else bg-info
                            @endif">
                            {{ strtoupper($reserva->estado) }}
                        </span>
                        </div>

                        <hr>

                        <p><i class="fas fa-map-marker-alt me-2 text-primary"></i><strong>Origen:</strong> {{ $reserva->viaje->ruta->origen ?? 'N/A' }}</p>
                        <p><i class="fas fa-flag-checkered me-2 text-primary"></i><strong>Destino:</strong> {{ $reserva->viaje->ruta->destino ?? 'N/A' }}</p>
                        <p><i class="fas fa-calendar-alt me-2 text-primary"></i><strong>Fecha:</strong> {{ $reserva->viaje->fecha_salida ?? 'N/A' }}</p>
                        <p><i class="fas fa-clock me-2 text-primary"></i><strong>Hora:</strong> {{ $reserva->viaje->hora_salida ?? 'N/A' }}</p>
                        <p><i class="fas fa-chair me-2 text-primary"></i><strong>Asiento:</strong> {{ $reserva->asiento ?? 'N/A' }}</p>
                        <p><i class="fas fa-ticket-alt me-2 text-primary"></i><strong>Código QR:</strong> {{ $reserva->codigo_qr }}</p>

                        <div class="text-end">
                            <a href="{{ route('itinerario.compartir', $reserva->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-share-alt me-1"></i>Compartir
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>No tienes reservas registradas aún.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
