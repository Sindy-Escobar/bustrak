<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Itinerario de Viajes - {{ $usuario->nombre_completo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #00b7ff;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #00b7ff;
            margin: 0;
            font-size: 24px;
        }
        .info-usuario {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-usuario p {
            margin: 5px 0;
        }
        .reserva {
            border: 1px solid #ddd;
            border-left: 4px solid #00b7ff;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .reserva h3 {
            color: #00b7ff;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .detalle {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .detalle-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            color: #555;
        }
        .detalle-valor {
            display: table-cell;
            width: 60%;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
        .badge-info { background-color: #17a2b8; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>
<!-- Encabezado -->
<div class="header">
    <h1><i class="fa-solid fa-bus"></i> BUSTRAK - Itinerario de Viajes</h1>
    <p>Sistema de Gestión de Transporte</p>
</div>

<!-- Información del usuario -->
<div class="info-usuario">
    <h3 style="color: #00b7ff; margin-top: 0;">Información del Pasajero</h3>
    <p><strong>Nombre:</strong> {{ $usuario->nombre_completo }}</p>
    <p><strong>DNI:</strong> {{ $usuario->dni }}</p>
    <p><strong>Email:</strong> {{ $usuario->email }}</p>
    <p><strong>Teléfono:</strong> {{ $usuario->telefono }}</p>
    <p><strong>Fecha de generación:</strong> {{ $fecha_generacion->format('d/m/Y H:i:s') }}</p>
</div>

<!-- Total de reservas -->
<h3 style="color: #00b7ff;">Total de Reservas: {{ $reservas->count() }}</h3>

<!-- Lista de reservas -->
@forelse($reservas as $reserva)
    <div class="reserva">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <h3>{{ $reserva->viaje->ruta->nombre ?? 'Ruta' }}</h3>
            <span class="badge
                    @if($reserva->estado == 'confirmado') badge-success
                    @elseif($reserva->estado == 'pendiente') badge-warning
                    @elseif($reserva->estado == 'cancelado') badge-danger
                    @else badge-info
                    @endif
                ">
                    {{ strtoupper($reserva->estado) }}
                </span>
        </div>

        <div class="detalle">
            <span class="detalle-label"><i class="fa-solid fa-location-dot"></i>  Origen:</span>
            <span class="detalle-valor">{{ $reserva->viaje->ruta->origen ?? 'N/A' }}</span>
        </div>

        <div class="detalle">
            <span class="detalle-label"><i class="fa-solid fa-map-marker-alt"></i> Destino:</span>
            <span class="detalle-valor">{{ $reserva->viaje->ruta->destino ?? 'N/A' }}</span>
        </div>

        <div class="detalle">
            <span class="detalle-label"><i class="fa-solid fa-calendar-days"></i> Fecha de Salida:</span>
            <span class="detalle-valor">
                    {{ $reserva->viaje->fecha_salida ? \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y') : 'N/A' }}
                </span>
        </div>

        <div class="detalle">
            <span class="detalle-label"><i class="fa-solid fa-clock"></i> Hora de Salida:</span>
            <span class="detalle-valor">{{ $reserva->viaje->hora_salida ?? 'N/A' }}</span>
        </div>

        <div class="detalle">
            <span class="detalle-label"><i class="fa-solid fa-chair"></i> Asiento:</span>
            <span class="detalle-valor">{{ $reserva->asiento ?? 'N/A' }}</span>
        </div>

        <div class="detalle">
            <span class="detalle-label"><i class="fa-solid fa-ticket"></i> Número de Reserva:</span>
            <span class="detalle-valor">{{ $reserva->id }}</span>
        </div>

        <div class="detalle">
            <span class="detalle-label"><i class="fa-solid fa-qrcode"></i> Código QR:</span>
            <span class="detalle-valor">{{ $reserva->codigo_qr }}</span>
        </div>

        <div class="qr-code">
            <img src="data:image/png;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($reserva->codigo_qr)) }}"
                 alt="Código QR">
            <p style="font-size: 10px; margin-top: 5px;">Presenta este código QR al abordar</p>
        </div>
    </div>
@empty
    <p style="text-align: center; color: #999; padding: 40px;">
        No hay reservas para mostrar en este itinerario.
    </p>
@endforelse

<!-- Pie de página -->
<div class="footer">
    <p><strong>BUSTRAK</strong> - Sistema de Gestión de Reserva</p>
    <p>Este documento es solo para fines informativos. Conserve su código QR para el abordaje.</p>
    <p>Para soporte o consultas, contacte: soporte@bustrak.com</p>
</div>
</body>
</html>
