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

                <a href="{{ route('cliente.historial.exportar-pdf') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-file-pdf me-1"></i> Exportar PDF
                </a>

                <a href="{{ route('cliente.reserva.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i> Nueva Reserva
                </a>
            </div>
        </div>

        <div class="card-body p-0">

            {{-- MENSAJES --}}
            @if(session('success'))
                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger m-3">
                    {{ session('error') }}
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
                        <th class="text-center">Cant.Asientos</th>
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
                                ->whereNotIn('estado', ['rechazado'])
                                ->first();

                            $viajeYaPaso = $reserva->viaje
                                ? \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->isPast()
                                : false;

                            $estaCancelada = $reserva->estado === 'cancelada';
                            $estaReembolsada = $reserva->estado === 'reembolsada';
                        @endphp

                        <tr class="{{ ($estaCancelada || $estaReembolsada) ? 'table-danger' : '' }}">

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i') }}
                            </td>

                            <td>{{ $reserva->viaje->origen->nombre ?? '-' }}</td>
                            <td>{{ $reserva->viaje->destino->nombre ?? '-' }}</td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}
                            </td>

                            <td class="text-center">
                                {{ $reserva->cantidad_asientos ?? 1 }}
                            </td>

                            <td class="text-center">
                                @if($reserva->estado === 'cancelada')
                                    <span class="badge bg-danger">Cancelada</span>
                                @elseif($reserva->estado === 'reembolsada')
                                    <span class="badge bg-primary">Reembolsada</span>
                                @else
                                    <span class="badge bg-success">Confirmada</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($comentarioGuardado)
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= $comentarioGuardado->calificacion; $i++) ★ @endfor
                                    </div>
                                    <small class="text-muted">
                                        "{{ $comentarioGuardado->comentario }}"
                                    </small>
                                @else
                                    <span class="text-muted small">Sin comentario</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group-vertical" role="group">

                                    {{-- CANCELAR --}}
                                    @if(!$estaCancelada && !$estaReembolsada && !$viajeYaPaso)
                                        <a href="{{ route('cliente.reserva.cancelar.form', $reserva->id) }}"
                                           class="btn btn-danger btn-sm mb-1">
                                            Cancelar
                                        </a>
                                    @endif

                                    {{-- REEMBOLSO --}}
                                    @if($estaCancelada && !$estaReembolsada && !$viajeYaPaso)
                                        <a href="{{ route('cliente.reembolso.solicitar', $reserva->id) }}"
                                           class="btn btn-success btn-sm mb-1">
                                            Reembolso
                                        </a>
                                    @endif

                                    {{-- CALIFICAR --}}
                                    @if(!$estaCancelada && !$estaReembolsada && $reserva->viaje && $reserva->viaje->empleado_id && !$comentarioGuardado)
                                        <a href="{{ route('calificar.chofer', $reserva->viaje->empleado_id) }}"
                                           class="btn btn-warning btn-sm mb-1">
                                            Calificar
                                        </a>
                                    @elseif($comentarioGuardado)
                                        <span class="badge bg-success mb-1">✔</span>
                                    @endif

                                    {{-- COMENTARIOS --}}
                                    @if($reserva->viaje && $reserva->viaje->empleado_id)
                                        <a href="{{ route('comentarios.conductor', $reserva->viaje->empleado_id) }}"
                                           class="btn btn-info btn-sm mb-1">
                                            Comentarios
                                        </a>
                                    @endif

                                    {{-- PUNTOS --}}
                                    @if(!$estaCancelada && !$estaReembolsada)
                                        <a href="{{ route('puntos.create', $reserva->id) }}"
                                           class="btn btn-success btn-sm mb-1">
                                            Puntos
                                        </a>
                                    @endif

                                    {{--  ELIMINAR (NUEVO) --}}
                                    @if($estaCancelada || $estaReembolsada)
                                        <form action="{{ route('cliente.reserva.eliminar', $reserva->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Eliminar esta reserva del historial?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-dark btn-sm">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No hay reservas registradas
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
