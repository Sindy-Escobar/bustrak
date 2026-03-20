@extends('layouts.layoutuser')

@section('contenido')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-11">
                <div class="alert alert-primary border-0 shadow-sm mb-0 py-3"
                     style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-bus fa-2x text-white"></i>
                        <div class="text-start">
                            <h4 class="fw-bold text-white mb-1">Viajes Disponibles</h4>
                            <p class="text-white mb-0" style="opacity: 0.9;">
                                Selecciona el viaje que prefieras
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--  BARRA DE PROGRESO - PASO 2 --}}
        @include('components.progress-stepper', ['step' => 2])

        <div class="row justify-content-center">
            <div class="col-lg-11">
                @if($viajes->isEmpty())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No hay viajes disponibles para esta ruta en este momento.
                    </div>
                @else
                    {{--  TABLA HORIZONTAL --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead style="background-color: #f8fafc;">
                                    <tr>
                                        <th class="px-4 py-3">
                                            <i class="fas fa-route me-2 text-primary"></i>Ruta
                                        </th>
                                        <th class="px-4 py-3">
                                            <i class="fas fa-bus me-2 text-primary"></i>Bus
                                        </th>
                                        <th class="px-4 py-3">
                                            <i class="fas fa-calendar-alt me-2 text-primary"></i>Fecha
                                        </th>
                                        <th class="px-4 py-3">
                                            <i class="fas fa-clock me-2 text-primary"></i>Salida
                                        </th>
                                        <th class="px-4 py-3 text-center">
                                            <i class="fas fa-chair me-2 text-primary"></i>Asientos disponibles
                                        </th>
                                        <th class="px-4 py-3 text-center">Acción</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($viajes as $viaje)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold" style="color: #3b82f6;">
                                                    {{ $viaje->origen->nombre }} → {{ $viaje->destino->nombre }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                    <span class="text-muted">
                                                        #{{ $viaje->bus->numero_bus ?? 'N/A' }}
                                                    </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ \Carbon\Carbon::parse($viaje->fecha_hora_salida)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                    <span class="fw-semibold">
                                                        {{ \Carbon\Carbon::parse($viaje->fecha_hora_salida)->format('H:i') }}
                                                    </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                    <span class="badge bg-success px-3 py-2">
                                                        {{ $viaje->asientos_count }}
                                                    </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="{{ route('cliente.reserva.asientos', $viaje->id) }}"
                                                   class="btn btn-sm text-white px-4"
                                                   style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); border: none;">
                                                    <i class="fas fa-arrow-right me-2"></i>Continuar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: #f0f9ff;
            transition: background-color 0.2s ease;
        }

        .table thead th {
            border-bottom: 2px solid #e5e7eb;
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
        }

        .table tbody td {
            border-bottom: 1px solid #f3f4f6;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }
    </style>
@endsection
