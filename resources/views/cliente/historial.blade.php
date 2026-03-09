@extends('layouts.layoutuser')

@section('title', 'Historial de Viajes')

@section('contenido')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Viajes</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('cliente.reembolsos') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-hand-holding-usd me-1"></i> Mis Reembolsos
                </a>
                <a href="{{ route('cliente.reserva.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Nueva Reserva
                </a>
            </div>
        </div>

        <div class="card-body p-0">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 m-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fa-2x"></i>
                        <div><strong class="d-block">¡Excelente!</strong>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 m-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-3 fa-2x"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
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
                    <tbody>
                    @forelse($reservas as $reserva)
                        @php
                            $user = auth()->user();
                            $usuario = \App\Models\Usuario::where('email', $user->email)->first();

                            $comentarioGuardado = null;
                            if ($usuario && $reserva->viaje && $reserva->viaje->empleado_id) {
                                $comentarioGuardado = \App\Models\ComentarioConductor::where('empleado_id', $reserva->viaje->empleado_id)
                                    ->where('usuario_id', $usuario->id)
                                    ->first();
                            }

                            $reembolsoExistente = $reserva->reembolsos()
    ->whereNotIn('estado', ['completado', 'rechazado'])
    ->first();

                            $viajeYaPaso = $reserva->viaje
                                ? \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->isPast()
                                : false;

                          $estaCancelada = $reserva->estado === 'cancelada';
$estaReembolsada = $reserva->estado === 'reembolsada';
                        @endphp

                        <tr class="{{ ($estaCancelada || $estaReembolsada) ? 'table-danger' : '' }}">
                            <td class="text-center">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i') }}</td>
                            <td>{{ $reserva->viaje->origen->nombre ?? '-' }}</td>
                            <td>{{ $reserva->viaje->destino->nombre ?? '-' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</td>
                            <td class="text-center">#{{ $reserva->asiento->numero_asiento ?? 'S/N' }}</td>

                            <td class="text-center">
                                @if($reserva->estado === 'cancelada')
                                    <span class="badge bg-danger">Cancelada</span>
                                @elseif($reserva->estado === 'reembolsada')
                                    <span class="badge" style="background:#1a56db;color:white;">Reembolsada</span>
                                @else
                                    <span class="badge bg-success">Confirmada</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($comentarioGuardado)
                                    <div class="text-warning small mb-1">
                                        @for($i = 1; $i <= $comentarioGuardado->calificacion; $i++) ★ @endfor
                                    </div>
                                    <p class="small mb-0 text-muted" style="font-style: italic; line-height: 1.2;">
                                        "{{ $comentarioGuardado->comentario }}"
                                    </p>
                                @else
                                    <span class="text-muted small">Sin comentario</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group-vertical" role="group">

                                    {{-- CANCELAR BOLETO (solo si no está cancelada y el viaje no ha pasado) --}}
                                    @if(!$estaCancelada && !$estaReembolsada && !$viajeYaPaso)
                                        <a href="{{ route('cliente.reserva.cancelar.form', $reserva->id) }}"
                                           class="btn btn-danger btn-sm mb-1"
                                           title="Cancelar este boleto">
                                            <i class="fas fa-times-circle"></i> Cancelar Boleto
                                        </a>
                                    @endif

                                    {{-- SOLICITAR REEMBOLSO (solo si está cancelada y aún no completó el método) --}}
                                    @if($estaCancelada && !$estaReembolsada)
                                        @if(!$reembolsoExistente)
                                            <a href="{{ route('cliente.reembolso.solicitar', $reserva->id) }}"
                                               class="btn btn-success btn-sm mb-1">
                                                <i class="fas fa-hand-holding-usd"></i> Solicitar Reembolso
                                            </a>
                                        @elseif($reembolsoExistente && $reembolsoExistente->metodo_pago === 'por_definir')
                                            <a href="{{ route('cliente.reembolso.solicitar', $reembolsoExistente->id) }}"
                                               class="btn btn-success btn-sm mb-1">
                                                <i class="fas fa-hand-holding-usd"></i> Solicitar Reembolso
                                            </a>
                                        @else
                                            <span class="badge bg-info text-dark mb-1">
            <i class="fas fa-check"></i> Reembolso solicitado
        </span>
                                        @endif
                                    @endif

                                    {{-- CALIFICAR (solo si el viaje ya pasó y no ha calificado) --}}
                                    @if(!$estaCancelada && $reserva->viaje && $reserva->viaje->empleado_id && !$comentarioGuardado && $viajeYaPaso)
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

                                    {{-- VER COMENTARIOS --}}
                                    @if($reserva->viaje && $reserva->viaje->empleado_id)
                                        <a href="{{ route('comentarios.conductor', $reserva->viaje->empleado_id) }}"
                                           class="btn btn-info btn-sm mb-1"
                                           title="Ver comentarios">
                                            <i class="fas fa-comments"></i> Comentarios
                                        </a>
                                    @endif

                                    {{-- PUNTOS --}}
                                    @if(!$estaCancelada && !$estaReembolsada)
                                        <a href="{{ route('puntos.create', $reserva->id) }}"
                                           class="btn btn-success btn-sm"
                                           title="Registrar puntos">
                                            <i class="fas fa-coins"></i> Puntos
                                        </a>
                                    @endif

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el) })
        });
    </script>
@endsection
