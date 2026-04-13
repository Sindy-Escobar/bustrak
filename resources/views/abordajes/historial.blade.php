@extends('layouts.layoutadmin')

@section('title', 'Historial de Abordajes')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0" style="color:#1e63b8; font-weight:600; font-size:1.8rem;">
                    <i class="fas fa-history me-2"></i>Historial de Abordajes
                </h2>
                <a href="{{ route('abordajes.checkin') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver al Check-in
                </a>
            </div>

            <div class="card-body">
                @if($reservas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Pasajero</th>
                                <th>Ruta</th>
                                <th>Código Reserva</th>
                                <th>Servicio</th>
                                <th>Fecha Abordaje</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reservas as $key => $reserva)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $reserva->user->nombre_completo ?? $reserva->user->name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $reserva->viaje->origen->nombre ?? 'N/A' }}
                                        → {{ $reserva->viaje->destino->nombre ?? 'N/A' }}
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $reserva->codigo_reserva }}</span></td>
                                    <td>{{ $reserva->tipoServicio->nombre ?? 'N/A' }}</td>
                                    <td>
                                        {{ $reserva->fecha_abordaje
                                            ? \Carbon\Carbon::parse($reserva->fecha_abordaje)->format('d/m/Y H:i')
                                            : '-' }}
                                    </td>
                                    <td>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Abordado
                                            </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $reservas->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted">No hay abordajes registrados aún.</p>
                    </div>
                @endif

                <div class="mt-4">
                </div>
            </div>
        </div>
    </div>
@endsection
