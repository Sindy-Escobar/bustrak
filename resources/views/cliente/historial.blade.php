@extends('layouts.layoutuser')

@section('title', 'Historial de Viajes')

@section('contenido')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Viajes</h4>
            <a href="{{ route('cliente.reserva.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus me-1"></i> Nueva Reserva
            </a>
        </div>

        <div class="card-body p-0">
            {{-- Alerta de Éxito --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 m-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fa-2x"></i>
                        <div>
                            <strong class="d-block">¡Excelente!</strong>
                            {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    {{-- Cabecera de la tabla --}}
                    <thead class="table-dark">
                    <tr>
                        <th class="text-center">Fecha Reserva</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th class="text-center">Salida</th>
                        <th class="text-center">Asiento</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Mi Opinión</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                    </thead>

                    {{-- Cuerpo de la tabla --}}
                    <tbody>
                    @forelse($reservas as $reserva)
                        @php
                            // Obtener el usuario de la tabla 'usuarios'
                            $user = auth()->user();
                            $usuario = \App\Models\Usuario::where('email', $user->email)->first();

                            // Buscar si ya calificó
                            $comentarioGuardado = null;
                            if($usuario) {
                                $comentarioGuardado = \App\Models\ComentarioConductor::where('empleado_id', $reserva->viaje->empleado_id)
                                    ->where('usuario_id', $usuario->id)
                                    ->first();
                            }
                        @endphp

                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i') }}</td>
                            <td>{{ $reserva->viaje->origen->nombre ?? '-' }}</td>
                            <td>{{ $reserva->viaje->destino->nombre ?? '-' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">#{{ $reserva->asiento->numero_asiento ?? 'S/N' }}</td>
                            <td class="text-center"><span class="badge bg-success">Confirmada</span></td>

                            {{-- Mi Opinión --}}
                            <td class="text-center">
                                @if($comentarioGuardado)
                                    <div class="text-warning small mb-1">
                                        @for($i=1; $i<=$comentarioGuardado->calificacion; $i++) ★ @endfor
                                    </div>
                                    <p class="small mb-0 text-muted" style="font-style: italic; line-height: 1.2;">
                                        "{{ $comentarioGuardado->comentario }}"
                                    </p>
                                @else
                                    <span class="text-muted small">Sin comentario</span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-center">
                                <div class="btn-group-vertical" role="group">
                                    {{-- Botón CALIFICAR (solo si no ha calificado) --}}
                                    @if($reserva->viaje && $reserva->viaje->empleado_id && !$comentarioGuardado)
                                        <a href="{{ route('calificar.chofer', $reserva->viaje->empleado_id) }}"
                                           class="btn btn-warning btn-sm mb-1"
                                           title="Calificar Conductor">
                                            <i class="fas fa-star"></i> Calificar
                                        </a>
                                    @elseif($comentarioGuardado)
                                        <span class="badge bg-success mb-1">
                                            <i class="fas fa-check"></i> Calificado
                                        </span>
                                    @endif

                                    {{-- NUEVO: Botón VER COMENTARIOS DE OTROS (HU4) --}}
                                    @if($reserva->viaje && $reserva->viaje->empleado_id)
                                        <a href="{{ route('comentarios.conductor', $reserva->viaje->empleado_id) }}"
                                           class="btn btn-info btn-sm mb-1"
                                           title="Ver comentarios de otros pasajeros">
                                            <i class="fas fa-comments"></i> Comentarios
                                        </a>
                                    @endif

                                    {{-- Botón PUNTOS --}}
                                    <a href="{{ route('puntos.create', $reserva->id) }}"
                                       class="btn btn-success btn-sm"
                                       title="Registrar puntos">
                                        <i class="fas fa-coins"></i> Puntos
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                No hay reservas registradas
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($reservas->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Mostrando {{ $reservas->firstItem() }} - {{ $reservas->lastItem() }} de {{ $reservas->total() }}
                    </small>
                    {{ $reservas->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Script para activar los tooltips de Bootstrap --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
@endsection
