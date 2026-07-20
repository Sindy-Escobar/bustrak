<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listado de Terminales</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0A3D62; /* Azul elegante */
            border-bottom: 2px solid #0A3D62;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #0A3D62; /* Azul fuerte */
            color: white;
            padding: 8px;
            border: 1px solid #0A3D62;
            font-size: 12px;
        }

        td {
            border: 1px solid #0A3D62;
            padding: 6px;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #EAF2F8; /* Azul claro */
        }

        .small {
            font-size: 10px;
        }
    </style>
</head>

<body>

<h2>Listado de Terminales</h2>

<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Ubicación</th>
            <th>Buses asignados</th>
            <th>Capacidad</th>
            <th>Contacto</th>
            <th>Horario</th>
            <th>Última actualización</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($terminales as $t)
        <tr>
            <td>{{ $t->codigo }}</td>

            <td>{{ $t->nombre }}</td>

            <td>
                <strong>Depto:</strong> {{ $t->departamento }} <br>
                <strong>Municipio:</strong> {{ $t->municipio }} <br>
                <strong>Dirección:</strong> {{ $t->direccion }}
            </td>

            <td>
                @forelse($t->buses as $bus)
                    {{ $bus->numero_bus }} ({{ $bus->placa }})@if(!$loop->last), @endif
                @empty
                    Sin buses asignados
                @endforelse
            </td>

            <td>{{ number_format((int) $t->capacidad_total) }} pasajeros</td>

            <td>
                <strong>Tel:</strong> {{ $t->telefono }} <br>
                <strong>Correo:</strong> {{ $t->correo }}
            </td>

            <td>
                {{ $t->horario_apertura }} - {{ $t->horario_cierre }}
            </td>

            <td class="small">
                {{ $t->updated_at->format('d/m/Y H:i') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
