<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }

        .header {
            background: #0d2f52;
            color: #fff;
            padding: 18px 24px;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .header p  { font-size: 10px; color: #93c5fd; }

        .resumen {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 8px;
        }
        .resumen-item {
            display: table-cell;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            width: 33%;
        }
        .resumen-item .label { font-size: 9px; text-transform: uppercase; color: #6b7280; font-weight: bold; letter-spacing: 0.5px; }
        .resumen-item .valor { font-size: 20px; font-weight: bold; color: #0d2f52; margin-top: 2px; }

        .usuario-info {
            margin-bottom: 18px;
            font-size: 10px;
            color: #4b5563;
        }
        .usuario-info strong { color: #1e293b; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background: #1a5f9c;
            color: #fff;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr { border-bottom: 1px solid #e2e8f0; }

        tbody td {
            padding: 7px 10px;
            font-size: 10px;
            color: #1e293b;
        }

        .pts-usados { color: #dc2626; font-weight: bold; }
        .saldo-tras { color: #2563eb; font-weight: bold; }

        .badge-completado { color: #15803d; font-weight: bold; }
        .badge-pendiente  { color: #854d0e; font-weight: bold; }

        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>BUSTRAK — Historial de canjes</h1>
    <p>Generado el {{ now()->format('d/m/Y H:i') }} &nbsp;|&nbsp; Usuario: {{ $usuario->name }}</p>
</div>

<div class="usuario-info">
    <strong>Correo:</strong> {{ $usuario->email }} &nbsp;&nbsp;
    <strong>Saldo actual:</strong> {{ $saldoActual }} pts &nbsp;&nbsp;
    <strong>Total canjeados:</strong> {{ $totalPuntosCanjeados }} pts
</div>

<table>
    <thead>
    <tr>
        <th>Fecha y hora</th>
        <th>Beneficio</th>
        <th>Tipo</th>
        <th>Ruta del viaje</th>
        <th>Puntos usados</th>
        <th>Saldo tras canje</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @forelse($canjes as $canje)
        <tr>
            <td>{{ $canje->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $canje->beneficio->nombre ?? '-' }}</td>
            <td>
                @if($canje->beneficio)
                    {{ str_contains(strtolower($canje->beneficio->nombre), 'descuento') ? 'Descuento' : 'Premio' }}
                @else
                    -
                @endif
            </td>
            <td>
                @if($canje->reserva && $canje->reserva->viaje)
                    {{ $canje->reserva->viaje->origen->nombre ?? 'N/A' }} →
                    {{ $canje->reserva->viaje->destino->nombre ?? 'N/A' }}
                @else
                    No vinculado
                @endif
            </td>
            <td class="pts-usados">-{{ $canje->puntos_usados }}</td>
            <td class="saldo-tras">{{ $canje->saldo_tras_canje }}</td>
            <td>
                @if($canje->estado === 'completado')
                    <span class="badge-completado">Completado</span>
                @else
                    <span class="badge-pendiente">Pendiente</span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align:center; color:#9ca3af; padding: 16px;">
                No hay canjes registrados.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="footer">
    BUSTRAK — Documento generado automáticamente. {{ now()->format('d/m/Y') }}
</div>

</body>
</html>
