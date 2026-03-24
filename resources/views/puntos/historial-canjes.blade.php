@extends('layouts.layoutuser')

@section('title', 'Historial de Canjes')

@section('contenido')
    <style>
        .hc-page {
            min-height: 100vh;
            background: #f0f4f8;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hc-inner { width: 100%; max-width: 960px; }

        .hc-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a3a5c;
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }

        /* Resumen */
        .resumen-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .resumen-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.1rem 1.25rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .resumen-card .label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #9ca3af;
        }

        .resumen-card .valor {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1a3a5c;
            line-height: 1;
        }

        .resumen-card .valor.azul { color: #2563eb; }
        .resumen-card .valor.verde { color: #16a34a; }
        .resumen-card .valor.rojo { color: #dc2626; }

        /* Filtros */
        .filtros-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .filtros-titulo {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 0.9rem;
        }

        .filtros-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 0.75rem;
            align-items: end;
        }

        .filtro-group label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #4b5563;
            display: block;
            margin-bottom: 4px;
        }

        .filtro-group select,
        .filtro-group input[type="date"] {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.45rem 0.7rem;
            font-size: 0.85rem;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border 0.15s;
        }

        .filtro-group select:focus,
        .filtro-group input[type="date"]:focus {
            border-color: #3b82f6;
            background: #fff;
        }

        .filtros-acciones {
            display: flex;
            gap: 0.5rem;
            align-items: end;
        }

        .btn-filtrar {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.1rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s;
        }

        .btn-filtrar:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(37,99,235,0.3); }

        .btn-limpiar {
            background: #f1f5f9;
            color: #64748b;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 0.9rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.15s;
        }

        .btn-limpiar:hover { background: #e2e8f0; color: #374151; }

        /* Botón exportar */
        .btn-exportar {
            background: #fff;
            color: #16a34a;
            border: 1.5px solid #16a34a;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-exportar:hover { background: #f0fdf4; color: #15803d; }

        /* Tabla */
        .tabla-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .tabla-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .tabla-header-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
        }

        table.hc-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.hc-table thead tr {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        table.hc-table thead th {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #9ca3af;
            padding: 0.65rem 1.25rem;
            text-align: left;
            white-space: nowrap;
        }

        table.hc-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.1s;
        }

        table.hc-table tbody tr:last-child { border-bottom: none; }
        table.hc-table tbody tr:hover { background: #f8fafc; }

        table.hc-table tbody td {
            font-size: 0.88rem;
            color: #1e293b;
            padding: 0.85rem 1.25rem;
            vertical-align: middle;
        }

        .badge-estado {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .badge-completado { background: #dcfce7; color: #15803d; }
        .badge-pendiente  { background: #fef9c3; color: #854d0e; }

        .pts-usados {
            font-weight: 700;
            color: #dc2626;
        }

        .saldo-tras {
            font-weight: 700;
            color: #2563eb;
        }

        .ruta-cell {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .ruta-arrow { color: #9ca3af; font-size: 0.75rem; }

        .beneficio-nombre { font-weight: 600; color: #1e293b; }
        .beneficio-tipo {
            display: inline-block;
            font-size: 0.7rem;
            background: #eff6ff;
            color: #1d4ed8;
            border-radius: 4px;
            padding: 1px 6px;
            margin-top: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .empty-state .icon { font-size: 2.5rem; margin-bottom: 0.5rem; }

        /* Paginación */
        .paginacion { display: flex; justify-content: center; }
        .paginacion .pagination { gap: 4px; }

        .alert { border-radius: 12px; border: none; margin-bottom: 1rem; }

        @media (max-width: 768px) {
            .resumen-grid { grid-template-columns: 1fr 1fr; }
            .filtros-grid { grid-template-columns: 1fr 1fr; }
            table.hc-table thead th:nth-child(5),
            table.hc-table tbody td:nth-child(5) { display: none; }
        }

        @media (max-width: 500px) {
            .resumen-grid { grid-template-columns: 1fr; }
            .filtros-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="hc-page">
        <div class="hc-inner">

            <h3 class="hc-title">Historial de canjes</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Tarjetas resumen --}}
            <div class="resumen-grid">
                <div class="resumen-card">
                    <span class="label">Saldo actual</span>
                    <span class="valor azul">{{ $saldoActual }}</span>
                    <span style="font-size:0.75rem; color:#6b7280;">puntos disponibles</span>
                </div>
                <div class="resumen-card">
                    <span class="label">Total canjeados</span>
                    <span class="valor rojo">{{ $totalPuntosCanjeados }}</span>
                    <span style="font-size:0.75rem; color:#6b7280;">puntos utilizados</span>
                </div>
                <div class="resumen-card">
                    <span class="label">Canjes realizados</span>
                    <span class="valor verde">{{ $totalCanjes }}</span>
                    <span style="font-size:0.75rem; color:#6b7280;">en total</span>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="filtros-card">
                <p class="filtros-titulo">Filtrar canjes</p>
                <form method="GET" action="{{ route('puntos.historial-canjes') }}">
                    <div class="filtros-grid">
                        <div class="filtro-group">
                            <label>Desde</label>
                            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="filtro-group">
                            <label>Hasta</label>
                            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="filtro-group">
                            <label>Tipo de beneficio</label>
                            <select name="tipo">
                                <option value="">Todos</option>
                                <option value="descuento" {{ request('tipo') === 'descuento' ? 'selected' : '' }}>Descuento</option>
                                <option value="premio"    {{ request('tipo') === 'premio'    ? 'selected' : '' }}>Premio</option>
                            </select>
                        </div>
                        <div class="filtro-group">
                            <label>Estado</label>
                            <select name="estado">
                                <option value="">Todos</option>
                                <option value="completado" {{ request('estado') === 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="pendiente"  {{ request('estado') === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                            </select>
                        </div>
                    </div>
                    <div style="display:flex; gap:0.5rem; margin-top:0.9rem; align-items:center; flex-wrap:wrap;">
                        <button type="submit" class="btn-filtrar">Aplicar filtros</button>
                        <a href="{{ route('puntos.historial-canjes') }}" class="btn-limpiar">Limpiar</a>
                        <a href="{{ route('puntos.exportar-pdf') }}?{{ http_build_query(request()->only(['fecha_desde','fecha_hasta','tipo','estado'])) }}"
                           class="btn-exportar">
                            ↓ Exportar PDF
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="tabla-card">
                <div class="tabla-header">
            <span class="tabla-header-title">
                {{ $canjes->total() }} {{ $canjes->total() === 1 ? 'resultado' : 'resultados' }}
            </span>
                    <span style="font-size:0.8rem; color:#9ca3af;">Ordenado: más reciente primero</span>
                </div>

                @if($canjes->count() > 0)
                    <table class="hc-table">
                        <thead>
                        <tr>
                            <th>Fecha y hora</th>
                            <th>Beneficio</th>
                            <th>Ruta del viaje</th>
                            <th>Puntos usados</th>
                            <th>Saldo tras canje</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($canjes as $canje)
                            <tr>
                                <td style="white-space:nowrap; color:#4b5563;">
                                    {{ $canje->created_at->format('d/m/Y') }}<br>
                                    <span style="font-size:0.78rem; color:#9ca3af;">{{ $canje->created_at->format('H:i') }}</span>
                                </td>
                                <td>
                                    <span class="beneficio-nombre">{{ $canje->beneficio->nombre ?? '-' }}</span><br>
                                    @if($canje->beneficio)
                                        @php
                                            $tipo = str_contains(strtolower($canje->beneficio->nombre), 'descuento') ? 'Descuento' : 'Premio';
                                        @endphp
                                        <span class="beneficio-tipo">{{ $tipo }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($canje->reserva && $canje->reserva->viaje)
                                        <span class="ruta-cell">
                                {{ $canje->reserva->viaje->origen->nombre ?? 'N/A' }}
                                <span class="ruta-arrow">→</span>
                                {{ $canje->reserva->viaje->destino->nombre ?? 'N/A' }}
                            </span>
                                    @else
                                        <span style="color:#9ca3af; font-size:0.82rem;">No vinculado</span>
                                    @endif
                                </td>
                                <td><span class="pts-usados">-{{ $canje->puntos_usados }}</span></td>
                                <td><span class="saldo-tras">{{ $canje->saldo_tras_canje }}</span></td>
                                <td>
                                    @if($canje->estado === 'completado')
                                        <span class="badge-estado badge-completado">✓ Completado</span>
                                    @else
                                        <span class="badge-estado badge-pendiente">⏳ Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @if($canjes->hasPages())
                        <div style="padding: 1rem 1.25rem; border-top: 1px solid #f1f5f9;" class="paginacion">
                            {{ $canjes->links() }}
                        </div>
                    @endif

                @else
                    <div class="empty-state">
                        <div class="icon">🎫</div>
                        <p>No se encontraron canjes con los filtros seleccionados.</p>
                        <a href="{{ route('puntos.historial-canjes') }}" class="btn-limpiar" style="display:inline-block; margin-top:0.5rem;">Ver todos</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
