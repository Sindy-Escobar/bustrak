{{-- placeholder --}}

@section('content')
    <style>
        .page-container {
            padding: 30px;
            max-width: 900px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a56db;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .comprobante-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        .comprobante-header {
            background: #eef4ff;
            padding: 24px 30px;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comprobante-header h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a56db;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .codigo-badge {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a56db;
            background: white;
            border: 2px solid #1a56db;
            padding: 6px 16px;
            border-radius: 8px;
        }

        .comprobante-body {
            padding: 30px;
        }

        .section-title {
            font-weight: 700;
            color: #1a56db;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef4ff;
        }

        .datos-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 25px;
        }

        .dato-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .dato-label {
            font-size: 11px;
            color: #999;
            font-weight: 700;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dato-label i { color: #1a56db; }

        .dato-valor {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pendiente { background: #fff3cd; color: #856404; }
        .badge-procesado { background: #d1ecf1; color: #0c5460; }
        .badge-entregado { background: #d4edda; color: #155724; }
        .badge-completado { background: #cce5ff; color: #004085; }

        .resumen-financiero {
            background: #eef4ff;
            border-left: 4px solid #1a56db;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .fila-resumen {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 10px;
            color: #555;
        }

        .fila-resumen.total {
            border-top: 2px solid #1a56db;
            padding-top: 10px;
            font-weight: 700;
            font-size: 16px;
            color: #1a56db;
            margin-bottom: 0;
        }

        .metodo-info {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-weight: 600;
            color: #856404;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tabla-datos {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }

        .tabla-datos th {
            background: #f5f5f5;
            padding: 10px 14px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
        }

        .tabla-datos td {
            padding: 10px 14px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .notas-box {
            background: #f9f9f9;
            border-left: 4px solid #ccc;
            padding: 14px;
            border-radius: 8px;
            font-size: 13px;
            color: #666;
            margin-bottom: 25px;
        }

        .firmas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .firma-item {
            text-align: center;
        }

        .linea-firma {
            border-top: 1px solid #333;
            margin-bottom: 8px;
            height: 50px;
        }

        .firma-nombre {
            font-size: 12px;
            color: #333;
            font-weight: 700;
        }

        .pie {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 12px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
            padding: 20px 30px;
            border-top: 1px solid #e0e0e0;
            background: #fafafa;
        }

        .btn-volver {
            padding: 10px 24px;
            background: white;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-imprimir {
            padding: 10px 24px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-imprimir:hover { background: #1648b8; }

        @media print {
            .button-group { display: none; }
            body { background: white; }
        }
    </style>

    <div class="page-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div class="page-title">
                <i class="fas fa-file-alt"></i> Comprobante de Reembolso
            </div>
            <a href="{{ route('admin.reembolsos') }}" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="comprobante-card">

            <!-- Header -->
            <div class="comprobante-header">
                <h2><i class="fas fa-file-alt"></i> Comprobante Oficial</h2>
                <div class="codigo-badge">{{ $reembolso->codigo_reembolso }}</div>
            </div>

            <div class="comprobante-body">

                <!-- Info del reembolso -->
                <div class="section-title">
                    <i class="fas fa-info-circle"></i> Información del Reembolso
                </div>
                <div class="datos-grid">
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-hashtag"></i> Código de Cancelación</span>
                        <span class="dato-valor">{{ $reembolso->codigo_cancelacion }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-toggle-on"></i> Estado Actual</span>
                        <span class="dato-valor">
                            <span class="badge badge-{{ $reembolso->estado }}">{{ strtoupper($reembolso->estado) }}</span>
                        </span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-calendar"></i> Fecha de Procesamiento</span>
                        <span class="dato-valor">{{ $reembolso->fecha_procesamiento ? $reembolso->fecha_procesamiento->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-calendar-check"></i> Fecha de Entrega</span>
                        <span class="dato-valor">{{ $reembolso->fecha_entrega ? $reembolso->fecha_entrega->format('d/m/Y H:i') : 'Pendiente' }}</span>
                    </div>
                </div>

                <!-- Info del cliente -->
                <div class="section-title">
                    <i class="fas fa-user"></i> Información del Cliente
                </div>
                <div class="datos-grid">
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-user"></i> Nombre Completo</span>
                        <span class="dato-valor">{{ $reembolso->usuario->name ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-envelope"></i> Email</span>
                        <span class="dato-valor">{{ $reembolso->usuario->email ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-id-card"></i> DNI</span>
                        <span class="dato-valor">{{ $reembolso->usuario->dni ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-phone"></i> Teléfono</span>
                        <span class="dato-valor">{{ $reembolso->usuario->telefono ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Detalles financieros -->
                <div class="section-title">
                    <i class="fas fa-coins"></i> Detalles Financieros
                </div>
                <div class="resumen-financiero">
                    <div class="fila-resumen">
                        <span>Monto Original:</span>
                        <span>L. {{ number_format($reembolso->monto_original, 2) }}</span>
                    </div>
                    <div class="fila-resumen">
                        <span>Monto a Reembolsar:</span>
                        <span>L. {{ number_format($reembolso->monto_reembolso, 2) }}</span>
                    </div>
                    <div class="fila-resumen total">
                        <span>TOTAL A ENTREGAR:</span>
                        <span>L. {{ number_format($reembolso->monto_reembolso, 2) }}</span>
                    </div>
                </div>

                <!-- Método de reembolso -->
                <div class="section-title">
                    <i class="fas fa-wallet"></i> Método de Reembolso
                </div>
                <div class="metodo-info">
                    <i class="fas fa-wallet"></i> {{ strtoupper($reembolso->metodo_pago) }}
                </div>

                @if($reembolso->metodo_pago === 'transferencia')
                    <table class="tabla-datos">
                        <tr><th>Banco</th><td>{{ $reembolso->banco }}</td></tr>
                        <tr><th>Número de Cuenta</th><td>{{ $reembolso->numero_cuenta }}</td></tr>
                        <tr><th>Titular de Cuenta</th><td>{{ $reembolso->titular_cuenta }}</td></tr>
                    </table>
                @elseif($reembolso->metodo_pago === 'cheque')
                    <table class="tabla-datos">
                        <tr><th>Número de Cheque</th><td>{{ $reembolso->numero_cheque }}</td></tr>
                    </table>
                @elseif($reembolso->metodo_pago === 'efectivo')
                    <div class="notas-box">
                        <i class="fas fa-clock"></i> <strong>Horario de Entrega:</strong> 8:00 AM - 5:00 PM<br>
                        <i class="fas fa-id-card"></i> <strong>Se requiere:</strong> Firma y foto de identificación
                    </div>
                @endif

                @if($reembolso->notas)
                    <div class="section-title" style="margin-top: 20px;">
                        <i class="fas fa-sticky-note"></i> Observaciones
                    </div>
                    <div class="notas-box">{{ $reembolso->notas }}</div>
                @endif

                <!-- Auditoría -->
                <div class="section-title" style="margin-top: 20px;">
                    <i class="fas fa-cog"></i> Información de Procesamiento
                </div>
                <div class="datos-grid">
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-user-check"></i> Procesado por</span>
                        <span class="dato-valor">{{ $reembolso->procesadoPor->name ?? 'Sistema' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-user-check"></i> Entregado por</span>
                        <span class="dato-valor">{{ $reembolso->entregadoPor->name ?? 'Pendiente' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-calendar"></i> Fecha de Creación</span>
                        <span class="dato-valor">{{ $reembolso->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label"><i class="fas fa-sync"></i> Última Actualización</span>
                        <span class="dato-valor">{{ $reembolso->updated_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                </div>

                <!-- Firmas -->
                <div class="firmas">
                    <div class="firma-item">
                        <div class="linea-firma"></div>
                        <div class="firma-nombre">Firma del Procesador</div>
                        <small>{{ $reembolso->procesadoPor->name ?? 'Sistema' }}</small>
                    </div>
                    <div class="firma-item">
                        <div class="linea-firma"></div>
                        <div class="firma-nombre">Firma del Beneficiario</div>
                        <small>{{ $reembolso->usuario->name ?? 'Cliente' }}</small>
                    </div>
                </div>

                <!-- Pie -->
                <div class="pie">
                    <p>Este comprobante certifica el procesamiento oficial del reembolso.</p>
                    <p>Generado el: {{ now()->format('d/m/Y H:i:s') }} — Sistema BusTrak © 2026</p>
                </div>

            </div>

            <!-- Botones -->
            <div class="button-group">
                <a href="{{ route('admin.reembolsos') }}" class="btn-volver">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
                <button onclick="window.print()" class="btn-imprimir">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>

        </div>
    </div>

    <script>
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', function() {
                setTimeout(function() { window.print(); }, 500);
            });
        }
    </script>
@endsection
