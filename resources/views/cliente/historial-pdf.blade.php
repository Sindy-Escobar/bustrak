<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Viajes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

        /* ── ENCABEZADO ── */
        .header-top {
            background-color: #0f172a;
            padding: 0;
            height: 5px;
        }
        .header-blue-bar {
            background-color: #1a56db;
            height: 5px;
        }
        .header-body {
            background-color: #0f172a;
            padding: 14px 24px 14px 24px;
        }
        .header-table {
            width: 100%;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .brand-box {
            background-color: #1a56db;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            text-align: center;
            padding-top: 8px;
            font-size: 18px;
            font-weight: 900;
            color: white;
        }
        .brand-name {
            font-size: 20px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 3px;
            padding-left: 10px;
        }
        .brand-sub {
            font-size: 8px;
            color: #64748b;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding-left: 10px;
        }
        .doc-title {
            font-size: 14px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 1px;
            text-align: right;
        }
        .doc-date {
            font-size: 9px;
            color: #475569;
            text-align: right;
            margin-top: 3px;
        }
        .header-line {
            height: 2px;
            background-color: #1a56db;
            margin: 0;
        }

        /* ── USUARIO ── */
        .user-table {
            width: calc(100% - 48px);
            margin: 14px 24px;
            border-collapse: collapse;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .user-left {
            background-color: #1a56db;
            color: white;
            padding: 12px 16px;
            width: 160px;
            vertical-align: top;
        }
        .user-avatar {
            background-color: rgba(255,255,255,0.2);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            text-align: center;
            padding-top: 7px;
            font-size: 16px;
            font-weight: 900;
            color: white;
            margin-bottom: 8px;
        }
        .user-label { font-size: 8px; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; }
        .user-name  { font-size: 13px; font-weight: 800; margin-top: 2px; }
        .user-right {
            background-color: #f8faff;
            padding: 12px 18px;
            vertical-align: middle;
        }
        .user-fields-table { width: 100%; border-collapse: collapse; }
        .user-fields-table td { padding: 0 20px 0 0; vertical-align: top; }
        .uf-label { font-size: 8px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .uf-value { font-size: 11px; font-weight: 700; color: #1e293b; margin-top: 2px; }

        /* ── ESTADÍSTICAS ── */
        .stats-wrap { margin: 0 24px 14px; }
        .stats-label { font-size: 8px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px; }
        .stats-table { width: 100%; border-collapse: separate; border-spacing: 6px 0; }
        .stats-table td { width: 20%; padding: 8px 10px; border-radius: 6px; vertical-align: top; }

        .s-total   { background-color: #eff6ff; border-left: 3px solid #1a56db; }
        .s-confirm { background-color: #f0fdf4; border-left: 3px solid #059669; }
        .s-cancel  { background-color: #fff1f2; border-left: 3px solid #e11d48; }
        .s-reemb   { background-color: #faf5ff; border-left: 3px solid #7c3aed; }
        .s-gasto   { background-color: #fffbeb; border-left: 3px solid #d97706; }

        .s-num { font-size: 22px; font-weight: 900; line-height: 1; }
        .s-total .s-num   { color: #1a56db; }
        .s-confirm .s-num { color: #059669; }
        .s-cancel .s-num  { color: #e11d48; }
        .s-reemb .s-num   { color: #7c3aed; }
        .s-gasto .s-num   { color: #d97706; font-size: 14px; }
        .s-lbl { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px; }

        /* ── TABLA ── */
        .table-wrap { margin: 0 24px; }
        .table-top-bar {
            background-color: #0f172a;
            color: white;
            padding: 7px 12px;
            border-radius: 6px 6px 0 0;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .table-top-bar span {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #1a56db;
            border-radius: 50%;
            margin-right: 6px;
            vertical-align: middle;
        }
        table.main { width: 100%; border-collapse: collapse; font-size: 10px; }
        table.main thead tr { background-color: #1e293b; color: white; }
        table.main thead th {
            padding: 7px 9px;
            text-align: left;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-right: 1px solid #334155;
        }
        table.main thead th:last-child { border-right: none; }
        table.main tbody tr.even { background-color: #f8faff; }
        table.main tbody tr.odd  { background-color: #ffffff; }
        table.main tbody td {
            padding: 6px 9px;
            vertical-align: middle;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #f1f5f9;
        }
        table.main tbody td:last-child { border-right: none; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 8px;
            font-weight: 800;
        }
        .b-confirmada  { background-color: #dcfce7; color: #166534; }
        .b-cancelada   { background-color: #fee2e2; color: #991b1b; }
        .b-reembolsada { background-color: #ede9fe; color: #5b21b6; }
        .b-otro        { background-color: #f1f5f9; color: #475569; }

        .codigo {
            font-family: monospace;
            font-size: 9px;
            color: #475569;
            background-color: #f1f5f9;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .monto { font-weight: 800; color: #1a56db; font-size: 11px; }

        .table-bottom-bar {
            background-color: #f8faff;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 6px 6px;
            padding: 7px 12px;
            text-align: right;
        }
        .total-lbl { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .total-val { font-size: 14px; font-weight: 900; color: #1a56db; }

        /* ── PIE ── */
        .footer-wrap { margin-top: 20px; }
        .footer-accent { height: 3px; background-color: #1a56db; }
        .footer-body {
            background-color: #0f172a;
            padding: 9px 24px;
        }
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-table td { vertical-align: middle; padding: 0; }
        .footer-logo { font-size: 11px; font-weight: 900; color: #ffffff; letter-spacing: 2px; }
        .footer-divider { display: inline-block; width: 1px; height: 14px; background-color: #334155; margin: 0 8px; vertical-align: middle; }
        .footer-sub { font-size: 8px; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
        .footer-center { font-size: 8px; color: #475569; text-align: center; line-height: 1.6; }
        .footer-right { text-align: right; font-size: 8px; color: #475569; }
    </style>
</head>
<body>

{{-- ENCABEZADO --}}
<div class="header-blue-bar"></div>
<div class="header-body">
    <table class="header-table">
        <tr>
            <td width="42">
                <div class="brand-box">B</div>
            </td>
            <td>
                <div class="brand-name">BUSTRAK</div>
                <div class="brand-sub">Sistema de Gestión de Transporte</div>
            </td>
            <td>
                <div class="doc-title">HISTORIAL DE VIAJES</div>
                <div class="doc-date">Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i') }} hrs</div>
            </td>
        </tr>
    </table>
</div>
<div class="header-line"></div>

{{-- USUARIO --}}
<table class="user-table">
    <tr>
        <td class="user-left" width="160">
            <div class="user-avatar">{{ strtoupper(substr($usuario->name, 0, 1)) }}</div>
            <div class="user-label">Cliente</div>
            <div class="user-name">{{ $usuario->name }}</div>
        </td>
        <td class="user-right">
            <table class="user-fields-table">
                <tr>
                    <td>
                        <div class="uf-label">Correo electrónico</div>
                        <div class="uf-value">{{ $usuario->email }}</div>
                    </td>
                    <td>
                        <div class="uf-label">DNI</div>
                        <div class="uf-value">{{ $usuario->dni ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="uf-label">Teléfono</div>
                        <div class="uf-value">{{ $usuario->telefono ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="uf-label">Miembro desde</div>
                        <div class="uf-value">{{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y') }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- ESTADÍSTICAS --}}
<div class="stats-wrap">
    <div class="stats-label">Resumen general</div>
    <table class="stats-table">
        <tr>
            <td class="s-total">
                <div class="s-num">{{ $totalViajes }}</div>
                <div class="s-lbl">Total viajes</div>
            </td>
            <td class="s-confirm">
                <div class="s-num">{{ $confirmadas }}</div>
                <div class="s-lbl">Confirmados</div>
            </td>
            <td class="s-cancel">
                <div class="s-num">{{ $canceladas }}</div>
                <div class="s-lbl">Cancelados</div>
            </td>
            <td class="s-reemb">
                <div class="s-num">{{ $reembolsadas }}</div>
                <div class="s-lbl">Reembolsados</div>
            </td>
            <td class="s-gasto">
                <div class="s-num">L. {{ number_format($totalGastado, 2) }}</div>
                <div class="s-lbl">Total gastado</div>
            </td>
        </tr>
    </table>
</div>

{{-- TABLA --}}
<div class="table-wrap">
    <div class="table-top-bar"><span></span>Detalle de reservas</div>

    @if($reservas->isEmpty())
        <p style="text-align:center;padding:20px;color:#94a3b8;font-style:italic;">No hay reservas registradas.</p>
    @else
        <table class="main">
            <thead>
            <tr>
                <th>Código</th>
                <th>Fecha Reserva</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Fecha Salida</th>
                <th>Tipo Servicio</th>
                <th style="text-align:center;">Cant. Asientos</th>
                <th style="text-align:center;">Estado</th>
                <th style="text-align:right;">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reservas as $i => $reserva)
                <tr class="{{ $i % 2 === 0 ? 'odd' : 'even' }}">
                    <td><span class="codigo">{{ $reserva->codigo_reserva }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</td>
                    <td>{{ $reserva->viaje->origen->nombre ?? '-' }}</td>
                    <td>{{ $reserva->viaje->destino->nombre ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</td>
                    <td>{{ $reserva->tipoServicio->nombre ?? '-' }}</td>
                    <td style="text-align:center;">{{ $reserva->cantidad_asientos ?? 1 }}</td>
                    <td style="text-align:center;">
                        @if($reserva->estado === 'confirmada')
                            <span class="badge b-confirmada">Confirmada</span>
                        @elseif($reserva->estado === 'cancelada')
                            <span class="badge b-cancelada">Cancelada</span>
                        @elseif($reserva->estado === 'reembolsada')
                            <span class="badge b-reembolsada">Reembolsada</span>
                        @else
                            <span class="badge b-otro">{{ ucfirst($reserva->estado) }}</span>
                        @endif
                    </td>
                    <td style="text-align:right;" class="monto">L. {{ number_format($reserva->total_a_pagar, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="table-bottom-bar">
            <span class="total-lbl">Total gastado &nbsp;</span>
            <span class="total-val">L. {{ number_format($totalGastado, 2) }}</span>
        </div>
    @endif
</div>

{{-- PIE --}}
<div class="footer-wrap">
    <div class="footer-accent"></div>
    <div class="footer-body">
        <table class="footer-table">
            <tr>
                <td width="200">
                    <span class="footer-logo">BUSTRAK</span>
                    <span class="footer-divider"></span>
                    <span class="footer-sub">Sis. de Gestión</span>
                </td>
                <td>
                    <div class="footer-center">
                        Documento confidencial — uso personal exclusivo<br>
                        {{ $usuario->name }} · {{ $usuario->email }}
                    </div>
                </td>
                <td width="180">
                    <div class="footer-right">
                        Documento oficial · {{ now()->format('d/m/Y H:i') }} hrs
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>
