<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Viajes</title>
    <style>
        @page { margin: 22px; }
        body { font-family: DejaVu Sans, sans-serif; color: #263238; font-size: 10px; }
        h1 { color: #0d47a1; font-size: 20px; margin: 0 0 5px; text-align: center; }
        .meta { color: #546e7a; margin-bottom: 14px; text-align: center; }
        .filtros { background: #eaf2fb; border-left: 4px solid #0d47a1; padding: 8px 12px; margin-bottom: 12px; }
        .filtros span { margin-right: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #0d47a1; color: #fff; padding: 7px 5px; text-align: left; }
        td { border: 1px solid #cfd8dc; padding: 6px 5px; vertical-align: middle; }
        tr:nth-child(even) { background: #f5f8fa; }
        .center { text-align: center; }
        .proximo { color: #1b5e20; font-weight: bold; }
        .finalizado { color: #546e7a; font-weight: bold; }
        .excluidos { color: #b71c1c; font-size: 8px; }
        .vacio { text-align: center; padding: 24px; color: #607d8b; }
    </style>
</head>
<body>
    <h1>Reporte de Viajes</h1>
    <div class="meta">
        Generado el {{ now()->format('d/m/Y h:i A') }} · {{ $viajes->count() }} resultado(s)
    </div>

    <div class="filtros">
        <span><strong>Estado:</strong> {{ ucfirst($filtrosReporte['estado']) }}</span>
        <span><strong>Fecha:</strong> {{ $filtrosReporte['fecha'] ? \Carbon\Carbon::parse($filtrosReporte['fecha'])->format('d/m/Y') : 'Todas' }}</span>
        <span><strong>Búsqueda:</strong> {{ $filtrosReporte['buscar'] ?: 'Sin filtro' }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Ruta</th>
                <th>Fecha y hora</th>
                <th>Bus</th>
                <th>Servicio</th>
                <th>Conductor</th>
                <th class="center">Ocupación</th>
                <th class="center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($viajes as $viaje)
                @php
                    $reservados = (int) ($viaje->asientos_reservados ?? 0);
                    $excluidos = (int) ($viaje->asientos_cancelados_excluidos ?? 0);
                    $esProximo = $viaje->fecha_hora_salida->isFuture();
                @endphp
                <tr>
                    <td>#{{ $viaje->id }}</td>
                    <td>
                        {{ $viaje->origen?->nombre ?? 'N/D' }} →
                        {{ $viaje->destino?->nombre ?? 'N/D' }}
                    </td>
                    <td>{{ $viaje->fecha_hora_salida->format('d/m/Y h:i A') }}</td>
                    <td>
                        {{ $viaje->bus?->numero_bus ?? 'Sin bus' }}<br>
                        {{ $viaje->bus?->placa ?? 'Sin placa' }}
                    </td>
                    <td>{{ $viaje->bus?->tipoServicio?->nombre ?? 'N/D' }}</td>
                    <td>
                        {{ $viaje->empleado
                            ? trim($viaje->empleado->nombre.' '.$viaje->empleado->apellido)
                            : 'Sin asignar' }}
                    </td>
                    <td class="center">
                        {{ $reservados }} / {{ (int) $viaje->asientos_totales }}
                        @if($excluidos > 0)
                            <div class="excluidos">{{ $excluidos }} cancelado(s) excluido(s)</div>
                        @endif
                    </td>
                    <td class="center {{ $esProximo ? 'proximo' : 'finalizado' }}">
                        {{ $esProximo ? 'Próximo' : 'Finalizado' }}<br>
                        {{ $viaje->activo ? 'Activo' : 'Inactivo' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="vacio">No hay viajes que coincidan con los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
