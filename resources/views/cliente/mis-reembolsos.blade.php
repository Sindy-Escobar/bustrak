@extends('layouts.layoutuser')

@section('title', 'Mis Reembolsos')

@section('contenido')
    <style>
        .reembolsos-wrapper {
            max-width: 960px;
            margin: 0 auto;
            padding: 10px 0 40px;
        }
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .stat-icono {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .si-pendiente { background: #fef3c7; color: #d97706; }
        .si-procesado  { background: #dbeafe; color: #1d4ed8; }
        .si-entregado  { background: #d1fae5; color: #059669; }
        .si-total      { background: #f3e8ff; color: #7c3aed; }
        .stat-info label { display: block; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-info span  { font-size: 1.4rem; font-weight: 800; color: #111827; }
        .card-reembolsos {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-reembolsos .card-header {
            background: linear-gradient(135deg, #1a56db 0%, #1e40af 100%);
            padding: 20px 28px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-reembolsos .card-header h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-reembolsos .card-header a {
            background: rgba(255,255,255,0.2);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.85rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }
        .card-reembolsos .card-header a:hover { background: rgba(255,255,255,0.3); }
        .reembolso-item {
            padding: 20px 28px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: background 0.15s;
        }
        .reembolso-item:hover { background: #fafafa; }
        .reembolso-item:last-child { border-bottom: none; }
        .codigo-badge {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 14px;
            font-weight: 800;
            font-size: 0.85rem;
            color: #1a56db;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 4px;
        }
        .reembolso-fecha { font-size: 0.75rem; color: #6b7280; }
        .reembolso-info { flex: 1; }
        .reembolso-info .ruta {
            font-weight: 600;
            color: #111827;
            font-size: 0.95rem;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .reembolso-info .meta {
            font-size: 0.82rem;
            color: #6b7280;
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }
        .meta-item { display: flex; align-items: center; gap: 4px; }
        .reembolso-monto { text-align: right; flex-shrink: 0; }
        .monto-numero { font-size: 1.3rem; font-weight: 800; color: #059669; display: block; }
        .monto-metodo {
            font-size: 0.78rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: flex-end;
            margin-top: 2px;
        }
        .badge-estado {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .estado-pendiente  { background: #fef3c7; color: #92400e; }
        .estado-procesado  { background: #dbeafe; color: #1e40af; }
        .estado-entregado  { background: #d1fae5; color: #065f46; }
        .estado-completado { background: #e0e7ff; color: #3730a3; }
        .progreso-reembolso { display: flex; align-items: center; gap: 4px; margin-top: 8px; }
        .prog-paso { height: 4px; flex: 1; border-radius: 4px; background: #e5e7eb; }
        .prog-paso.activo { background: #059669; }
        .prog-paso.rechazado { background: #ef4444; }
        .empty-state { text-align: center; padding: 60px 20px; color: #6b7280; }
        .empty-state i { font-size: 3rem; margin-bottom: 16px; opacity: 0.4; }
        .empty-state h5 { color: #374151; margin-bottom: 8px; }
        .empty-state a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #1a56db;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 12px;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .reembolso-item { flex-direction: column; align-items: flex-start; }
            .reembolso-monto { text-align: left; width: 100%; }
        }
    </style>

    <div class="reembolsos-wrapper">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-check-circle fa-lg"></i>
                    <div><strong>¡Listo!</strong> {{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $pendientes = $reembolsos->where('estado', 'pendiente')->where('metodo_pago', '!=', 'por_definir')->count();
            $procesados = $reembolsos->where('estado', 'procesado')->count();
            $entregados = $reembolsos->whereIn('estado', ['entregado','completado'])->count();
            $totalMonto = $reembolsos->sum('monto_reembolso');
        @endphp

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icono si-pendiente"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <label>Pendientes</label>
                    <span>{{ $pendientes }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icono si-procesado"><i class="fas fa-cog"></i></div>
                <div class="stat-info">
                    <label>Procesados</label>
                    <span>{{ $procesados }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icono si-entregado"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <label>Entregados</label>
                    <span>{{ $entregados }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icono si-total"><i class="fas fa-coins"></i></div>
                <div class="stat-info">
                    <label>Total</label>
                    <span style="font-size: 1.1rem;">L. {{ number_format($totalMonto, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="card-reembolsos card">
            <div class="card-header">
                <h4><i class="fas fa-hand-holding-usd"></i> Mis Reembolsos</h4>
                <a href="{{ route('cliente.historial') }}">
                    <i class="fas fa-arrow-left"></i> Mis Reservas
                </a>
            </div>

            <div class="card-body p-0">
                @forelse($reembolsos as $r)
                    @php
                        $pasos = ['pendiente' => 1, 'procesado' => 2, 'entregado' => 3, 'completado' => 4, 'rechazado' => 0];
                        $pasoActual = $pasos[$r->estado] ?? 1;
                        $etiquetas  = ['Rechazado', 'Pendiente', 'Procesando', 'Listo para entrega', 'Completado'];
                    @endphp
                    <div class="reembolso-item">
                        <div style="flex-shrink: 0; text-align: center;">
                            <span class="codigo-badge">{{ $r->codigo_reembolso }}</span>
                            <span class="reembolso-fecha">{{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y') }}</span>
                        </div>

                        <div class="reembolso-info">
                            <div class="ruta">
                                {{ $r->reserva->viaje->origen->nombre ?? 'Origen' }}
                                <i class="fas fa-arrow-right" style="font-size: 0.8rem; color: #9ca3af;"></i>
                                {{ $r->reserva->viaje->destino->nombre ?? 'Destino' }}
                            </div>
                            <div class="meta">
                                <span class="meta-item"><i class="fas fa-ticket-alt"></i> #{{ $r->codigo_cancelacion }}</span>
                                @if($r->metodo_pago && $r->metodo_pago !== 'por_definir')
                                    <span class="meta-item"><i class="fas fa-credit-card"></i> {{ ucfirst($r->metodo_pago) }}</span>
                                @endif
                                @if($r->fecha_procesamiento)
                                    <span class="meta-item"><i class="fas fa-calendar-check"></i> {{ \Carbon\Carbon::parse($r->fecha_procesamiento)->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            <div class="progreso-reembolso">
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="prog-paso {{ $r->estado === 'rechazado' ? 'rechazado' : ($i <= $pasoActual ? 'activo' : '') }}"></div>
                                @endfor
                            </div>
                            <div style="font-size: 0.75rem; color: #6b7280; margin-top: 4px;">{{ $etiquetas[$pasoActual] ?? '' }}</div>
                        </div>

                        <div>
                            @switch($r->estado)
                                @case('pendiente')
                                    <span class="badge-estado estado-pendiente"><i class="fas fa-clock"></i> Pendiente</span>
                                    @break
                                @case('procesado')
                                    <span class="badge-estado estado-procesado"><i class="fas fa-cog"></i> Procesando</span>
                                    @break
                                @case('entregado')
                                    <span class="badge-estado estado-entregado"><i class="fas fa-check"></i> Listo</span>
                                    @break
                                @case('completado')
                                    <span class="badge-estado estado-completado"><i class="fas fa-check-double"></i> Completado</span>
                                    @break
                                @case('rechazado')
                                    <span class="badge-estado" style="background:#fee2e2;color:#991b1b;"><i class="fas fa-times"></i> Rechazado</span>
                                    @break
                            @endswitch
                        </div>

                        <div class="reembolso-monto">
                            <span class="monto-numero">L. {{ number_format($r->monto_reembolso, 2) }}</span>
                            <div class="monto-metodo">
                                <i class="fas fa-info-circle"></i>
                                {{ ucfirst($r->metodo_pago ?? 'Por definir') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-hand-holding-usd d-block"></i>
                        <h5>Sin reembolsos</h5>
                        <p>Aún no tienes solicitudes de reembolso registradas.</p>
                        <a href="{{ route('cliente.historial') }}">
                            <i class="fas fa-ticket-alt"></i> Ver mis reservas
                        </a>
                    </div>
                @endforelse
            </div>

            @if(method_exists($reembolsos, 'hasPages') && $reembolsos->hasPages())
                <div class="card-footer bg-light px-4">
                    {{ $reembolsos->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
