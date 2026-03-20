<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boleto - {{ $reserva->codigo_reserva }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 15mm 20mm;  /* Superior/inferior: 15mm, Izquierda/derecha: 20mm */
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            padding: 0 10px;  /* Padding adicional interno */
        }

        .container {
            width: 100%;
            padding: 0;
        }

        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .logo-section {
            margin-bottom: 15px;
        }

        .logo-section img {
            width: 250px;
            height: auto;
            display: block;
            margin: 0 auto 10px auto;
            background: white;
            padding: 10px;
            border-radius: 10px;
        }

        .logo-section h1 {
            font-size: 28px;
            margin: 0;
            font-weight: bold;
            letter-spacing: 3px;
        }

        .header h2 {
            font-size: 18px;
            margin: 10px 0 5px 0;
            font-weight: normal;
        }

        .header p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        .codigo-reserva {
            background: #eff6ff;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        .codigo-reserva .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .codigo-reserva .codigo {
            font-size: 22px;
            font-weight: bold;
            color: #3b82f6;
            letter-spacing: 2px;
        }

        .section {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;  /* Evita que se corte entre páginas */
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #3b82f6;
            text-transform: uppercase;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #666;
            padding: 5px 10px 5px 0;
            width: 40%;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
        }

        .resumen-pago {
            background: #f0fdf4;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            page-break-inside: avoid;  /* Evita que se corte entre páginas */
        }

        .resumen-pago .title {
            font-size: 14px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 10px;
        }

        .pago-row {
            display: table;
            width: 100%;
            padding: 5px 0;
        }

        .pago-label {
            display: table-cell;
            width: 70%;
        }

        .pago-valor {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }

        .total {
            border-top: 2px solid #10b981;
            margin-top: 10px;
            padding-top: 10px;
            font-size: 16px;
        }

        .total .pago-label {
            font-size: 16px;
            font-weight: bold;
            color: #10b981;
        }

        .total .pago-valor {
            font-size: 18px;
            color: #10b981;
        }

        .qr-section {
            text-align: center;
            padding: 20px;
            background: white;
            border: 2px dashed #3b82f6;
            border-radius: 8px;
            margin-top: 20px;
            page-break-inside: avoid;  /* Evita que se corte entre páginas */
        }

        .qr-section svg {
            display: block;
            margin: 0 auto;
        }

        .qr-section p {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            background: #3b82f6;
            color: white;
            border-radius: 5px;
            font-size: 10px;
            margin: 2px;
        }

        .alert {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 12px;
            margin: 15px 0;
        }

        .alert-success {
            background: #d1fae5;
            border-color: #10b981;
        }

        .alert-info {
            background: #dbeafe;
            border-color: #3b82f6;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    {{-- Header con Logo --}}
    <div class="header">
        <div class="logo-section">
            <img src="{{ public_path('Imagenes/bustrak-logo.png') }}" alt="BusTrak Logo">
            <h1>BUSTRAK</h1>
        </div>
        <h2>BOLETO DE VIAJE</h2>
        <p>Confirmación de Reserva</p>
    </div>

    {{-- Código de Reserva --}}
    <div class="codigo-reserva">
        <div class="label">Código de Reserva</div>
        <div class="codigo">{{ $reserva->codigo_reserva }}</div>
    </div>

    {{-- Alerta de autorización si aplica --}}
    @if($reserva->es_menor)
        @php
            $paisPasajero = $reserva->para_tercero
                ? $reserva->tercero_pais
                : ($reserva->user->pais ?? 'Honduras');
            $esHondureno = strtolower(trim($paisPasajero)) === 'honduras';
        @endphp

        @if(!$esHondureno)
            <div class="alert alert-success">
                <strong>Autorización Confirmada:</strong> La autorización del tutor legal ha sido registrada correctamente.
            </div>
        @else
            <div class="alert alert-info">
                <strong>Menor hondureño:</strong> No requiere autorización adicional.
            </div>
        @endif
    @endif

    {{-- Información del Viaje --}}
    <div class="section">
        <div class="section-title">Información del Viaje</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Origen:</div>
                <div class="info-value">{{ $reserva->viaje->origen->nombre ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Destino:</div>
                <div class="info-value">{{ $reserva->viaje->destino->nombre ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha y Hora de Salida:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipo de Servicio:</div>
                <div class="info-value">{{ $reserva->tipoServicio->nombre ?? 'No especificado' }}</div>
            </div>
        </div>
    </div>

    {{-- Información de Asientos --}}
    <div class="section">
        <div class="section-title">Asientos Reservados</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Cantidad de asientos:</div>
                <div class="info-value">{{ $reserva->cantidad_asientos }} {{ $reserva->cantidad_asientos == 1 ? 'asiento' : 'asientos' }}</div>
            </div>
            @php
                $asientosReservados = \App\Models\Asiento::where('reserva_id', $reserva->id)
                    ->orderBy('numero_asiento')
                    ->pluck('numero_asiento');
            @endphp
            @if($asientosReservados->isNotEmpty())
                <div class="info-row">
                    <div class="info-label">Números de asiento:</div>
                    <div class="info-value">#{{ $asientosReservados->implode(', #') }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Información de quien realizó la reserva --}}
    <div class="section">
        <div class="section-title">Usuario que realizó la Reserva</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value">{{ $reserva->user->nombre_completo ?? $reserva->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $reserva->user->email }}</div>
            </div>
            @if($reserva->user->telefono)
                <div class="info-row">
                    <div class="info-label">Teléfono:</div>
                    <div class="info-value">{{ $reserva->user->telefono }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Información del Pasajero --}}
    <div class="section">
        <div class="section-title">Información del Pasajero</div>
        <div class="info-grid">
            @if($reserva->para_tercero)
                <div class="info-row">
                    <div class="info-label">Tipo:</div>
                    <div class="info-value" style="color: #f59e0b; font-weight: bold;">Reserva para Tercero</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nombre completo:</div>
                    <div class="info-value">{{ $reserva->tercero_nombre }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Documento:</div>
                    <div class="info-value">{{ $reserva->tercero_tipo_doc }}: {{ $reserva->tercero_num_doc }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">País:</div>
                    <div class="info-value">{{ $reserva->tercero_pais }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Teléfono:</div>
                    <div class="info-value">{{ $reserva->tercero_telefono }}</div>
                </div>
                @if($reserva->tercero_email)
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $reserva->tercero_email }}</div>
                    </div>
                @endif
            @else
                <div class="info-row">
                    <div class="info-label">Tipo:</div>
                    <div class="info-value" style="color: #10b981; font-weight: bold;">Viaja el Usuario Titular</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nombre completo:</div>
                    <div class="info-value">{{ $reserva->user->nombre_completo ?? $reserva->user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $reserva->user->email }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Servicios Adicionales --}}
    @if($reserva->serviciosAdicionales->isNotEmpty())
        <div class="section">
            <div class="section-title">Servicios Adicionales</div>
            <div style="padding: 5px 0;">
                @foreach($reserva->serviciosAdicionales as $servicio)
                    <span class="badge">{{ $servicio->nombre }} (x{{ $servicio->pivot->cantidad }})</span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Resumen de Pago --}}
    <div class="resumen-pago">
        <div class="title">Resumen de Pago</div>

        @php
            $tarifaBase = $reserva->tipoServicio->tarifa_base ?? 0;
            $cantidadAsientos = $reserva->cantidad_asientos ?? 1;
            $subtotalAsientos = $tarifaBase * $cantidadAsientos;

            $totalServicios = $reserva->serviciosAdicionales->sum(function($servicio) {
                return $servicio->pivot->precio_unitario * $servicio->pivot->cantidad;
            });

            $totalGeneral = $subtotalAsientos + $totalServicios;
        @endphp

        <div class="pago-row">
            <div class="pago-label">Asientos ({{ $cantidadAsientos }}) - L. {{ number_format($tarifaBase, 2) }} c/u</div>
            <div class="pago-valor">L. {{ number_format($subtotalAsientos, 2) }}</div>
        </div>

        @if($totalServicios > 0)
            <div class="pago-row">
                <div class="pago-label">Servicios adicionales</div>
                <div class="pago-valor">L. {{ number_format($totalServicios, 2) }}</div>
            </div>
        @endif

        <div class="pago-row total">
            <div class="pago-label">TOTAL A PAGAR</div>
            <div class="pago-valor">L. {{ number_format($totalGeneral, 2) }}</div>
        </div>
    </div>

    {{-- Código QR --}}
    <div class="qr-section">
        <p><strong>Presenta este código al abordar el bus</strong></p>
        {!! DNS2D::getBarcodeHTML($reserva->codigo_reserva, 'QRCODE', 4, 4) !!}
        <p style="margin-top: 10px; font-size: 10px;">Código: {{ $reserva->codigo_reserva }}</p>
    </div>

    {{-- Importante --}}
    <div class="alert" style="margin-top: 20px;">
        <strong>IMPORTANTE:</strong> Debe presentarse 15 minutos antes de la hora de salida. Si no llega a tiempo, su boleto será cancelado automáticamente.
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p><strong>BUSTRAK</strong> - Sistema de Reservas de Autobuses</p>
        <p>Fecha de emisión: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Este documento es válido como comprobante de reserva</p>
    </div>
</div>
</body>
</html>
