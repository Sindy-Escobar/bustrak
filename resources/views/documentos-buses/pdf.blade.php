<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Documentos de Buses</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #0A3D62;
            margin-bottom: 10px;
            font-size: 22px;
            border-bottom: 2px solid #0A3D62;
            padding-bottom: 5px;
        }

        .estadisticas-box {
            background: #EAF2FB;
            border-left: 4px solid #0A3D62;
            padding: 10px 15px;
            margin-bottom: 20px;
        }

        .estadisticas-box p {
            margin: 3px 0;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #0A3D62;
            color: white;
            padding: 8px;
            font-size: 13px;
            border: 1px solid #0A3D62;
        }

        td {
            padding: 6px;
            border: 1px solid #A5B1C2;
            font-size: 12px;
        }

        tr:nth-child(even) {
            background: #F2F6FA;
        }

        .estado-vigente {
            color: #1B9C1A;
            font-weight: bold;
        }

        .estado-por-vencer {
            color: #E1A500;
            font-weight: bold;
        }

        .estado-vencido {
            color: #C0392B;
            font-weight: bold;
        }
    </style>
</head>

<body>

<h2>Reporte de Documentos de Buses</h2>

<div class="estadisticas-box">
    <p><strong>Total documentos:</strong> {{ $estadisticas['total'] }}</p>
    <p><strong>Vigentes:</strong> {{ $estadisticas['vigentes'] }}</p>
    <p><strong>Por vencer:</strong> {{ $estadisticas['por_vencer'] }}</p>
    <p><strong>Vencidos:</strong> {{ $estadisticas['vencidos'] }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Bus</th>
            <th>Tipo Documento</th>
            <th>N° Documento</th>
            <th>Fecha Emisión</th>
            <th>Fecha Vencimiento</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($documentos as $doc)
        <tr>
            <td>{{ $doc->bus->codigo_bus ?? 'N/A' }}</td>
            <td>{{ $doc->tipo_documento_nombre }}</td>
            <td>{{ $doc->numero_documento }}</td>
            <td>{{ $doc->fecha_emision->format('d/m/Y') }}</td>
            <td>{{ $doc->fecha_vencimiento->format('d/m/Y') }}</td>

            <td class="
                @if($doc->estado == 'vigente') estado-vigente
                @elseif($doc->estado == 'por_vencer') estado-por-vencer
                @else estado-vencido
                @endif
            ">
                {{ ucfirst($doc->estado) }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
