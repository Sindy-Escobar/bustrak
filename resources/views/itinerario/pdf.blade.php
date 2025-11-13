<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Itinerario de Viajes - {{ $usuario->nombre_completo }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #00b7ff; padding-bottom: 15px; }
        .header h1 { color: #00b7ff; margin: 0; font-size: 24px; }
        .info-usuario { background-color: #f0f8ff; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .reserva { border: 1px solid #ddd; border-left: 4px solid #00b7ff; padding: 15px; margin-bottom: 15px; page-break-inside: avoid; }
        .reserva h3 { color: #00b7ff; margin-top: 0; }
        .detalle { display: table; width: 100%; margin-bottom: 8px; }
        .detalle-label { display: table-cell; width: 40%; font-weight: bold; color: #555; }
        .detalle-valor { display: table-cell; width: 60%; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; color: white; }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .qr-code { text-align: center; margin: 10px 0; }
        .qr-code img { width: 100px; height: 100px; }
    </style>
</head>
<body>
<div class="header">
    <h1>BUSTRAK - Itinerario de Viajes</h1>
    <p>Sistema de Gestión de Transporte</p>
</div>

<div class="info-usuario">
    <h3 style="color: #00b7ff; margin-top: 0;">Información del Pasajero</h3>
    <p><strong>Nombre:</strong> {{ $usuario->nombre_completo }}</p>
    <p><strong>DNI:</strong> {{ $usuario->dni }}</p>
    <p><strong>Email:</strong> {{ $usuario->email }}</p>
    <p><strong>Teléfono:</strong> {{ $usuario->telefono }}</p>
    <p><strong>Fecha de generación:</strong> {{ $fecha_generacion->format('d/m/Y H:i:s') }}</p>
</div>

<h3 style="color: #00b7ff;">Total de Reservas: {{ $reservas->count() }}</h3>

@forelse($reservas as $reserva)
    <div class="reserva">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <h3>{{ $reserva->viaje->origen->nombre ?? 'Origen' }} → {{ $reserva->viaje->destino->nombre ?? 'Destino' }}</h3>
            <span class="badge {{ $reserva->estado == 'confirmado' ? 'badge-success' : ($reserva->estado == 'pendiente' ? 'badge-warning' : 'badge-danger') }}">
                {{ strtoupper($reserva->estado) }}
            </span>
        </div>

        <div class="detalle">
            <span class="detalle-label">Origen:</span>
            <span class="detalle-valor">{{ $reserva->viaje->origen->nombre ?? 'N/A' }}</span>
        </div>
        <div class="detalle">
            <span class="detalle-label">Destino:</span>
            <span class="detalle-valor">{{ $reserva->viaje->destino->nombre ?? 'N/A' }}</span>
        </div>
        <div class="detalle">
            <span class="detalle-label">Fecha de Salida:</span>
            <span class="detalle-valor">
                {{ $reserva->viaje->fecha_hora_salida ? \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y') : 'N/A' }}
            </span>
        </div>
        <div class="detalle">
            <span class="detalle-label">Hora de Salida:</span>
            <span class="detalle-valor">
                {{ $reserva->viaje->fecha_hora_salida ? \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('H:i') : 'N/A' }}
            </span>
        </div>
        <div class="detalle">
            <span class="detalle-label">Asiento:</span>
            <span class="detalle-valor">{{ $reserva->asiento->numero_asiento ?? 'N/A' }}</span>
        </div>
        <div class="detalle">
            <span class="detalle-label">Número de Reserva:</span>
            <span class="detalle-valor">{{ $reserva->id }}</span>
        </div>

        <div class="qr-code">
            <img src="data:image/png;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($reserva->codigo_reserva)) }}" alt="QR">
            <p style="font-size: 10px; margin-top: 5px;">Presenta este código QR al abordar</p>
        </div>
    </div>
@empty
    <p style="text-align: center; color: #999; padding: 40px;">No hay reservas para mostrar en este itinerario.</p>
@endforelse

<div class="footer">
    <p><strong>BUSTRAK</strong> - Sistema de Gestión de Reserva</p>
    <p>Este documento es solo para fines informativos. Conserve su código QR para el abordaje.</p>
    <p>Para soporte o consultas, contacte: soporte@bustrak.com</p>
</div>
</body>
</html>
