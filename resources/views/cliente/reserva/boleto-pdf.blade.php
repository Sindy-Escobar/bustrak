<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #eef2f7; padding: 30px; }
        .page { width: 520px; margin: 0 auto; }
        .ticket-header { background: #1346a0; border-radius: 14px 14px 0 0; padding: 24px 30px 20px; text-align: center; }
        .brand { font-size: 28px; font-weight: 900; color: #ffffff; letter-spacing: 2px; }
        .brand-accent { color: #5cb3ff; }
        .subtitle { font-size: 9px; letter-spacing: 4px; text-transform: uppercase; color: rgba(255,255,255,0.55); margin-top: 2px; margin-bottom: 12px; }
        .pill { display: inline-block; background: #27ae60; color: #fff; font-size: 9px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; padding: 4px 18px; border-radius: 30px; }
        .ruta-wrap { background: #ffffff; padding: 20px 30px 16px; border-left: 1px solid #dce6f5; border-right: 1px solid #dce6f5; }
        .ruta-inner { background: #f0f6ff; border: 1px solid #d0e2ff; border-radius: 10px; padding: 14px 20px; }
        .ruta-table { width: 100%; border-collapse: collapse; }
        .ruta-ciudad { font-size: 19px; font-weight: 900; color: #1346a0; }
        .ruta-label { font-size: 8px; text-transform: uppercase; letter-spacing: 1.5px; color: #9ab0cc; margin-top: 3px; }
        .ruta-mid { text-align: center; padding: 0 8px; }
        .ticket-divider { background: #eef2f7; border-left: 1px solid #dce6f5; border-right: 1px solid #dce6f5; padding: 0 20px; }
        .divider-inner { border-top: 2px dashed #c8d8ee; }
        .info-wrap { background: #ffffff; padding: 16px 30px; border-left: 1px solid #dce6f5; border-right: 1px solid #dce6f5; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-td { width: 50%; padding: 5px; vertical-align: top; }
        .info-box { background: #f7faff; border: 1px solid #dce8f8; border-radius: 8px; padding: 10px 12px; }
        .info-lbl { font-size: 7.5px; text-transform: uppercase; letter-spacing: 1.2px; color: #9ab0cc; font-weight: bold; margin-bottom: 4px; }
        .info-val { font-size: 13px; font-weight: bold; color: #1a2a45; }
        .pasajero-wrap { background: #ffffff; padding: 4px 30px 14px; border-left: 1px solid #dce6f5; border-right: 1px solid #dce6f5; }
        .pasajero-box { border: 1.5px solid #f0c040; border-left: 5px solid #f0a500; border-radius: 8px; background: #fffdf0; padding: 12px 16px; }
        .pasajero-tag { font-size: 7.5px; text-transform: uppercase; letter-spacing: 1.5px; color: #c08000; font-weight: bold; margin-bottom: 6px; }
        .pasajero-nombre { font-size: 15px; font-weight: 900; color: #1a2a45; margin-bottom: 4px; }
        .pasajero-dato { font-size: 11px; color: #667; margin-top: 2px; }
        .comprador-wrap { background: #ffffff; padding: 0 30px 14px; border-left: 1px solid #dce6f5; border-right: 1px solid #dce6f5; }
        .comprador-box { background: #f4f8ff; border: 1px solid #d0e2f8; border-radius: 8px; padding: 9px 14px; font-size: 10.5px; color: #556; }
        .comprador-b { color: #1346a0; font-weight: bold; }
        .codigo-wrap { background: #ffffff; padding: 0 30px 20px; border-left: 1px solid #dce6f5; border-right: 1px solid #dce6f5; }
        .codigo-box { background: #1346a0; border-radius: 10px; padding: 16px 20px; text-align: center; }
        .codigo-lbl { font-size: 8px; text-transform: uppercase; letter-spacing: 2.5px; color: rgba(255,255,255,0.55); margin-bottom: 6px; }
        .codigo-text { font-size: 16px; font-weight: 900; color: #fff; letter-spacing: 2px; word-break: break-all; }
        .ticket-footer { background: #f0f4fa; border: 1px solid #dce6f5; border-top: none; border-radius: 0 0 14px 14px; padding: 12px 20px; text-align: center; }
        .footer-txt { font-size: 9.5px; color: #aab8cc; }
        .footer-b { color: #1346a0; font-weight: bold; }
    </style>
</head>
<body>
<div class="page">

    <div class="ticket-header">
        <div class="brand">Bus<span class="brand-accent">Trak</span></div>
        <div class="subtitle">Boleto Oficial de Viaje</div>
        <span class="pill">{{ $reserva->estado }}</span>
    </div>

    <div class="ruta-wrap">
        <div class="ruta-inner">
            <table class="ruta-table">
                <tr>
                    <td style="text-align:left; width:42%;">
                        <div class="ruta-ciudad">{{ $reserva->viaje->origen->nombre }}</div>
                        <div class="ruta-label">Origen</div>
                    </td>
                    <td class="ruta-mid" style="width:16%;">
                        <div style="font-size:16px; color:#5cb3ff; font-weight:900;">&gt;&gt;</div>
                        <div style="font-size:8px; color:#9ab0cc; letter-spacing:1px; margin-top:4px;">VIAJE</div>
                    </td>
                    <td style="text-align:right; width:42%;">
                        <div class="ruta-ciudad">{{ $reserva->viaje->destino->nombre }}</div>
                        <div class="ruta-label">Destino</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="ticket-divider"><div class="divider-inner"></div></div>

    <div class="info-wrap">
        <table class="info-table">
            <tr>
                <td class="info-td">
                    <div class="info-box">
                        <div class="info-lbl">Fecha y Hora de Salida</div>
                        <div class="info-val">{{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</div>
                    </div>
                </td>
                <td class="info-td">
                    <div class="info-box">
                        <div class="info-lbl">Numero de Asiento</div>
                        <div class="info-val"># {{ $reserva->asiento->numero_asiento }}</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="info-td">
                    <div class="info-box">
                        <div class="info-lbl">Tipo de Servicio</div>
                        <div class="info-val">{{ $reserva->tipoServicio->nombre ?? 'No especificado' }}</div>
                    </div>
                </td>
                <td class="info-td">
                    <div class="info-box">
                        <div class="info-lbl">Fecha de Reserva</div>
                        <div class="info-val">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="pasajero-wrap">
        <div class="pasajero-box">
            <div class="pasajero-tag">{{ $reserva->para_tercero ? 'Pasajero — Reservado por otra persona' : 'Pasajero' }}</div>
            @if($reserva->para_tercero)
                <div class="pasajero-nombre">{{ $reserva->tercero_nombre }}</div>
                <div class="pasajero-dato">{{ $reserva->tercero_tipo_doc }} - {{ $reserva->tercero_num_doc }}</div>
                @if($reserva->tercero_telefono)
                    <div class="pasajero-dato">Tel: {{ $reserva->tercero_telefono }}</div>
                @endif
            @else
                <div class="pasajero-nombre">{{ $reserva->user->name }}</div>
                <div class="pasajero-dato">{{ $reserva->user->email }}</div>
            @endif
        </div>
    </div>

    @if($reserva->para_tercero)
        <div class="comprador-wrap">
            <div class="comprador-box">
                <span class="comprador-b">Comprado por:</span> {{ $reserva->user->name }} - {{ $reserva->user->email }}
            </div>
        </div>
    @endif

    <div class="codigo-wrap">
        <div class="codigo-box">
            <div class="codigo-lbl">Codigo de Reserva</div>
            <div class="codigo-text">{{ $reserva->codigo_reserva }}</div>
        </div>
    </div>

    <div class="ticket-footer">
        <div class="footer-txt">
            Presenta este boleto en la terminal . <span class="footer-b">BusTrak</span> . Generado el {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

</div>
</body>
</html>
