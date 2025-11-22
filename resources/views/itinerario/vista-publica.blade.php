@extends('layouts.layoutuser')

@section('content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-airplane"></i> Itinerario de Viaje</h4>
            </div>
            <div class="card-body">
                <div class="border-start border-primary border-4 ps-3 mb-4">
                    <h5 class="text-primary mb-3">
                        <i class="bi bi-geo-alt-fill"></i> {{ $reserva->viaje->origen->nombre ?? 'N/A' }}
                        <i class="bi bi-arrow-right"></i>
                        {{ $reserva->viaje->destino->nombre ?? 'N/A' }}
                    </h5>

                    <p class="mb-2">
                        <i class="bi bi-calendar3"></i>
                        <strong>Fecha:</strong>
                        {{ $reserva->viaje->fecha_hora_salida ? \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y') : 'N/A' }}
                    </p>

                    <p class="mb-2">
                        <i class="bi bi-clock"></i>
                        <strong>Hora:</strong>
                        {{ $reserva->viaje->fecha_hora_salida ? \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('H:i') : 'N/A' }}
                    </p>

                    <p class="mb-2">
                        <i class="bi bi-person-badge"></i>
                        <strong>Asiento:</strong>
                        {{ $reserva->asiento->numero_asiento ?? 'N/A' }}
                    </p>

                    <p class="mb-2">
                        <i class="bi bi-ticket-detailed"></i>
                        <strong>Código de Reserva:</strong>
                        <span class="badge bg-info">{{ $reserva->codigo_reserva }}</span>
                    </p>

                    <p class="mb-0">
                        <span class="badge bg-{{ $reserva->estado == 'confirmada' ? 'success' : ($reserva->estado == 'pendiente' ? 'warning' : 'danger') }}">
                            {{ strtoupper($reserva->estado) }}
                        </span>
                    </p>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted">
                        <i class="bi bi-info-circle"></i> Este itinerario fue compartido contigo
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
