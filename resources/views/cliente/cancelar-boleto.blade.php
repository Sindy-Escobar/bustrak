@extends('layouts.layoutuser')

@section('title', 'Cancelar Boleto')

@section('contenido')
    <style>
        .cancelar-wrapper {
            max-width: 720px;
            margin: 0 auto;
            padding: 10px 0 40px;
        }
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 28px;
        }
        .breadcrumb-nav a { color: #1a56db; text-decoration: none; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .alerta-cancelacion {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-left: 4px solid #f59e0b;
            border-radius: 10px;
            padding: 16px 20px;
            display: flex;
            gap: 14px;
            align-items: flex-start;
            margin-bottom: 24px;
        }
        .alerta-cancelacion i { color: #d97706; font-size: 1.3rem; margin-top: 2px; }
        .alerta-cancelacion strong { display: block; color: #92400e; margin-bottom: 4px; }
        .alerta-cancelacion p { margin: 0; font-size: 0.9rem; color: #78350f; }
        .card-cancelar {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-cancelar .card-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            padding: 22px 28px;
            border: none;
        }
        .card-cancelar .card-header h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-cancelar .card-body { padding: 28px; }
        .viaje-resumen {
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }
        .viaje-resumen h6 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 14px;
            font-weight: 600;
        }
        .viaje-ruta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .ciudad-tag {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 700;
            font-size: 1rem;
            color: #111827;
        }
        .flecha-ruta {
            color: #dc2626;
            font-size: 1.2rem;
            flex: 1;
            text-align: center;
        }
        .viaje-detalles {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        .detalle-item label {
            display: block;
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detalle-item span {
            font-weight: 600;
            color: #111827;
            font-size: 0.95rem;
        }
        .badge-estado {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-confirmada { background: #dcfce7; color: #166534; }
        .monto-reembolso {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .monto-icono {
            width: 48px;
            height: 48px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .monto-icono i { color: #dc2626; font-size: 1.3rem; }
        .monto-info label {
            display: block;
            font-size: 0.8rem;
            color: #991b1b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .monto-valor {
            font-size: 1.6rem;
            font-weight: 800;
            color: #dc2626;
        }
        .monto-nota {
            font-size: 0.8rem;
            color: #991b1b;
            margin-top: 2px;
        }
        .politica-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .politica-box h6 {
            font-size: 0.85rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 10px;
        }
        .politica-box ul { margin: 0; padding-left: 18px; }
        .politica-box ul li { font-size: 0.85rem; color: #4b5563; margin-bottom: 6px; }
        .btn-cancelar-confirmar {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s;
            width: 100%;
        }
        .btn-cancelar-confirmar:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(220,38,38,0.4);
            color: #fff;
        }
        .btn-volver {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            color: #6b7280;
            background: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-volver:hover { background: #f9fafb; color: #374151; border-color: #d1d5db; }
        @media (max-width: 576px) {
            .viaje-detalles { grid-template-columns: 1fr 1fr; }
            .viaje-ruta { flex-direction: column; }
        }
    </style>

    <div class="cancelar-wrapper">

        <nav class="breadcrumb-nav">
            <a href="{{ route('cliente.historial') }}"><i class="fas fa-home me-1"></i>Mis Reservas</a>
            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span>Cancelar Boleto</span>
        </nav>

        <div class="alerta-cancelacion">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>¿Estás seguro de que deseas cancelar?</strong>
                <p>Esta acción no se puede deshacer. Al cancelar tu boleto, podrás solicitar un reembolso según nuestra política vigente.</p>
            </div>
        </div>

        <div class="card-cancelar card">
            <div class="card-header">
                <h4><i class="fas fa-times-circle"></i> Cancelar Boleto</h4>
            </div>
            <div class="card-body">

                <div class="viaje-resumen">
                    <h6><i class="fas fa-ticket-alt me-2"></i>Detalle del Boleto</h6>
                    <div class="viaje-ruta">
                        <div class="ciudad-tag">{{ $reserva->viaje->origen->nombre ?? 'Origen' }}</div>
                        <div class="flecha-ruta"><i class="fas fa-long-arrow-alt-right"></i></div>
                        <div class="ciudad-tag">{{ $reserva->viaje->destino->nombre ?? 'Destino' }}</div>
                    </div>
                    <div class="viaje-detalles">
                        <div class="detalle-item">
                            <label>Código Reserva</label>
                            <span>#{{ $reserva->codigo_reserva }}</span>
                        </div>
                        <div class="detalle-item">
                            <label>Fecha de Salida</label>
                            <span>{{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="detalle-item">
                            <label>Asiento</label>
                            <span>#{{ $reserva->asiento->numero_asiento ?? 'S/N' }}</span>
                        </div>
                        <div class="detalle-item">
                            <label>Fecha Reserva</label>
                            <span>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</span>
                        </div>
                        <div class="detalle-item">
                            <label>Estado</label>
                            <span class="badge-estado badge-confirmada">Confirmada</span>
                        </div>
                        @if($reserva->para_tercero && $reserva->tercero_nombre)
                            <div class="detalle-item">
                                <label>Pasajero</label>
                                <span>{{ $reserva->tercero_nombre }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="politica-box">
                    <h6><i class="fas fa-shield-alt me-2 text-primary"></i>Política de Cancelación</h6>
                    <ul>
                        <li>Cancelaciones con <strong>más de 24 horas</strong> antes del viaje: reembolso del <strong>100%</strong>.</li>
                        <li>Cancelaciones entre <strong>12 y 24 horas</strong>: reembolso del <strong>50%</strong>.</li>
                        <li>Cancelaciones con <strong>menos de 12 horas</strong>: <strong>sin reembolso</strong>.</li>
                        <li>El reembolso se procesa en un plazo de <strong>3 a 5 días hábiles</strong>.</li>
                    </ul>
                </div>

                @php
                    $precioViaje = $reserva->viaje->precio ?? 0;
                    $ahora = \Carbon\Carbon::now();
                    $salida = \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida);
                    $horasRestantes = $ahora->diffInHours($salida, false);
                    if ($horasRestantes > 24) {
                        $porcentaje = 100;
                        $montoReembolso = $precioViaje;
                    } elseif ($horasRestantes >= 12) {
                        $porcentaje = 50;
                        $montoReembolso = $precioViaje * 0.5;
                    } else {
                        $porcentaje = 0;
                        $montoReembolso = 0;
                    }
                @endphp

                <div class="monto-reembolso">
                    <div class="monto-icono">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="monto-info">
                        <label>Reembolso estimado ({{ $porcentaje }}%)</label>
                        <div class="monto-valor">L. {{ number_format($montoReembolso, 2) }}</div>
                        <div class="monto-nota">Precio original: L. {{ number_format($precioViaje, 2) }}</div>
                    </div>
                </div>

                <form action="{{ route('cliente.reserva.cancelar', $reserva->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="form-label">Motivo de cancelación <span class="text-danger">*</span></label>
                        <select name="motivo_cancelacion" class="form-select" required>
                            <option value="" disabled selected>Selecciona un motivo...</option>
                            <option value="cambio_planes">Cambio de planes</option>
                            <option value="emergencia_personal">Emergencia personal</option>
                            <option value="problemas_salud">Problemas de salud</option>
                            <option value="error_reserva">Error en la reserva</option>
                            <option value="otro">Otro motivo</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Comentario adicional <span class="text-muted">(opcional)</span></label>
                        <textarea name="comentario_cancelacion" class="form-control" rows="3"
                                  placeholder="Cuéntanos más sobre tu motivo de cancelación..."></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="confirmarCancelacion" required>
                        <label class="form-check-label" for="confirmarCancelacion" style="font-size: 0.9rem;">
                            Entiendo que esta acción es <strong>irreversible</strong> y acepto la política de cancelación.
                        </label>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('cliente.historial') }}" class="btn-volver">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn-cancelar-confirmar">
                            <i class="fas fa-times-circle me-2"></i>Confirmar Cancelación
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
