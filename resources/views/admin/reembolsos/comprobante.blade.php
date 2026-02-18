@extends('layouts.layoutadmin')

@section('content')
    <style>
        .comprobante-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .comprobante-documento {
            background: white;
            border: 2px solid #1e63b8;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-family: 'Arial', sans-serif;
        }

        .encabezado {
            text-align: center;
            border-bottom: 3px solid #1e63b8;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .encabezado h1 {
            margin: 0;
            color: #1e63b8;
            font-size: 24px;
            font-weight: 700;
        }

        .encabezado p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }

        .numero-comprobante {
            text-align: center;
            background: #f0f7ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .numero-comprobante strong {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .numero-comprobante .codigo {
            font-size: 20px;
            color: #1e63b8;
            font-weight: 700;
            font-family: 'Courier New', monospace;
        }

        .seccion {
            margin-bottom: 25px;
        }

        .seccion-titulo {
            background: #1e63b8;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .datos-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }

        .dato-item {
            display: flex;
            flex-direction: column;
        }

        .dato-label {
            font-size: 12px;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .dato-valor {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }

        .dato-valor.monto {
            color: #1e63b8;
            font-size: 18px;
            font-weight: 700;
        }

        .tabla-datos {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .tabla-datos th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
            color: #333;
        }

        .tabla-datos td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .tabla-datos tr:nth-child(even) {
            background: #f9f9f9;
        }

        .resumen-financiero {
            background: #f0f7ff;
            border-left: 4px solid #1e63b8;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .fila-resumen {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .fila-resumen.total {
            border-top: 2px solid #1e63b8;
            padding-top: 12px;
            font-weight: 700;
            font-size: 16px;
            color: #1e63b8;
        }

        .metodo-info {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }

        .metodo-info strong {
            color: #856404;
        }

        .estado-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            margin: 10px 0;
        }

        .estado-badge.pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .estado-badge.procesado {
            background: #d1ecf1;
            color: #0c5460;
        }

        .estado-badge.entregado {
            background: #d4edda;
            color: #155724;
        }

        .estado-badge.completado {
            background: #cce5ff;
            color: #004085;
        }

        .notas {
            background: #f9f9f9;
            border-left: 4px solid #ccc;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }

        .pie-comprobante {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #999;
            font-size: 12px;
        }

        .firmas {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
        }

        .firma-item {
            text-align: center;
        }

        .linea-firma {
            border-top: 1px solid #333;
            margin-bottom: 5px;
            height: 50px;
        }

        .firma-nombre {
            font-size: 12px;
            color: #333;
            font-weight: 600;
        }

        .botones-accion {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .btn-print {
            background: #5cb3ff;
            color: white;
        }

        .btn-print:hover {
            background: #3d97f0;
        }

        .btn-back {
            background: #ccc;
            color: #333;
        }

        .btn-back:hover {
            background: #aaa;
        }

        @media print {
            .botones-accion {
                display: none;
            }

            body {
                background: white;
            }

            .comprobante-documento {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }

        @media (max-width: 768px) {
            .datos-grid {
                grid-template-columns: 1fr;
            }

            .firmas {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .comprobante-documento {
                padding: 20px;
            }
        }
    </style>

    <div class="comprobante-container">
        <div class="comprobante-documento">
            <!-- Encabezado -->
            <div class="encabezado">
                <h1>📄 COMPROBANTE DE REEMBOLSO</h1>
                <p>Sistema de Gestión BusTrak</p>
            </div>

            <!-- Número de Comprobante -->
            <div class="numero-comprobante">
                <strong>CÓDIGO DE REEMBOLSO</strong>
                <div class="codigo">{{ $reembolso->codigo_reembolso }}</div>
            </div>

            <!-- Sección: Información del Reembolso -->
            <div class="seccion">
                <div class="seccion-titulo">INFORMACIÓN DEL REEMBOLSO</div>
                <div class="datos-grid">
                    <div class="dato-item">
                        <span class="dato-label">Código de Cancelación</span>
                        <span class="dato-valor">{{ $reembolso->codigo_cancelacion }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">Estado Actual</span>
                        <span class="dato-valor">
                            <span class="estado-badge estado-badge-{{ $reembolso->estado }}">
                                {{ strtoupper($reembolso->estado) }}
                            </span>
                        </span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">Fecha de Procesamiento</span>
                        <span class="dato-valor">{{ $reembolso->fecha_procesamiento ? $reembolso->fecha_procesamiento->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">Fecha de Entrega</span>
                        <span class="dato-valor">{{ $reembolso->fecha_entrega ? $reembolso->fecha_entrega->format('d/m/Y H:i') : 'Pendiente' }}</span>
                    </div>
                </div>
            </div>

            <!-- Sección: Información del Cliente -->
            <div class="seccion">
                <div class="seccion-titulo">INFORMACIÓN DEL CLIENTE</div>
                <div class="datos-grid">
                    <div class="dato-item">
                        <span class="dato-label">Nombre Completo</span>
                        <span class="dato-valor">{{ $reembolso->usuario->name ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">Email</span>
                        <span class="dato-valor">{{ $reembolso->usuario->email ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">DNI</span>
                        <span class="dato-valor">{{ $reembolso->usuario->dni ?? 'N/A' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">Teléfono</span>
                        <span class="dato-valor">{{ $reembolso->usuario->telefono ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Sección: Detalles Financieros -->
            <div class="seccion">
                <div class="seccion-titulo">DETALLES FINANCIEROS</div>
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
            </div>

            <!-- Sección: Método de Reembolso -->
            <div class="seccion">
                <div class="seccion-titulo">MÉTODO DE REEMBOLSO</div>
                <div class="metodo-info">
                    <strong>{{ strtoupper($reembolso->metodo_pago) }}</strong>
                </div>

                @if($reembolso->metodo_pago === 'transferencia')
                    <table class="tabla-datos">
                        <tr>
                            <th>Banco</th>
                            <td>{{ $reembolso->banco }}</td>
                        </tr>
                        <tr>
                            <th>Número de Cuenta</th>
                            <td>{{ $reembolso->numero_cuenta }}</td>
                        </tr>
                        <tr>
                            <th>Titular de Cuenta</th>
                            <td>{{ $reembolso->titular_cuenta }}</td>
                        </tr>
                    </table>
                @elseif($reembolso->metodo_pago === 'cheque')
                    <table class="tabla-datos">
                        <tr>
                            <th>Número de Cheque</th>
                            <td>{{ $reembolso->numero_cheque }}</td>
                        </tr>
                    </table>
                @elseif($reembolso->metodo_pago === 'efectivo')
                    <div class="metodo-info">
                        ⏰ <strong>Horario de Entrega:</strong> 8:00 AM - 5:00 PM<br>
                        📸 <strong>Se requiere:</strong> Firma y foto de identificación
                    </div>
                @endif
            </div>

            <!-- Sección: Notas -->
            @if($reembolso->notas)
                <div class="seccion">
                    <div class="seccion-titulo">OBSERVACIONES</div>
                    <div class="notas">
                        {{ $reembolso->notas }}
                    </div>
                </div>
            @endif

            <!-- Sección: Auditoría -->
            <div class="seccion">
                <div class="seccion-titulo">INFORMACIÓN DE PROCESAMIENTO</div>
                <div class="datos-grid">
                    <div class="dato-item">
                        <span class="dato-label">Procesado por</span>
                        <span class="dato-valor">{{ $reembolso->procesadoPor->name ?? 'Sistema' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">Entregado por</span>
                        <span class="dato-valor">{{ $reembolso->entregadoPor->name ?? 'Pendiente' }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">Fecha de Creación</span>
                        <span class="dato-valor">{{ $reembolso->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="dato-item">
                        <span class="dato-label">Última Actualización</span>
                        <span class="dato-valor">{{ $reembolso->updated_at->format('d/m/Y H:i:s') }}</span>
                    </div>
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

            <!-- Pie de Comprobante -->
            <div class="pie-comprobante">
                <p>Este comprobante es un documento oficial que certifica el procesamiento del reembolso.</p>
                <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
                <p>Sistema BusTrak © 2026 - Todos los derechos reservados</p>
            </div>

            <!-- Botones de Acción -->
            <div class="botones-accion">
                <button onclick="window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Imprimir Comprobante
                </button>
                <a href="{{ route('admin.reembolsos') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-print si viene de crear reembolso
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            });
        }
    </script>
@endsection
