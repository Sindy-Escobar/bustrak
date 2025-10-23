<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Empleado - BusTrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 40px 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            padding: 30px 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .header {
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }

        h2 {
            color: #4a4a4a;
            margin: 0;
        }

        .info p {
            font-size: 15px;
            color: #333;
            margin-bottom: 6px;
        }

        .info strong {
            color: #555;
        }

        .estado-activo { color: #2ecc71; font-weight: 600; }
        .estado-inactivo { color: #e74c3c; font-weight: 600; }

        .btn {
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 14px;
            margin: 5px;
        }

        .btn-warning {
            background: #f1c40f;
            border: none;
            color: #333;
        }

        .btn-danger {
            background: #e74c3c;
            border: none;
        }

        .btn-secondary {
            background: #95a5a6;
            border: none;
        }

        .text-center {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        @if($empleado->foto && file_exists(storage_path('app/public/'.$empleado->foto)))
            <img src="{{ asset('storage/'.$empleado->foto) }}" class="profile-img" alt="Foto del empleado">
        @else
            <img src="https://via.placeholder.com/100?text=Sin+Foto" class="profile-img" alt="Sin foto">
        @endif

        <div>
            <h2>{{ $empleado->nombre }} {{ $empleado->apellido }}</h2>
            <p class="text-muted mb-0">{{ $empleado->cargo }}</p>
        </div>
    </div>

    <div class="info">
        <p><strong>Rol:</strong> {{ $empleado->rol }}</p>
        <p><strong>DNI:</strong> {{ $empleado->dni }}</p>
        <p><strong>Fecha ingreso:</strong> {{ $empleado->fecha_ingreso }}</p>
        <p><strong>Estado:</strong>
            <span class="{{ $empleado->estado === 'Activo' ? 'estado-activo' : 'estado-inactivo' }}">
                {{ $empleado->estado }}
            </span>
        </p>
    </div>

    <div class="text-center">
        <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-warning">Editar</a>

        @if($empleado->estado === 'Activo')
            <form action="{{ route('empleados.desactivar', $empleado->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-danger">Desactivar</button>
            </form>
        @else
            <form action="{{ route('empleados.activar', $empleado->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success">Activar</button>
            </form>
        @endif

        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Volver al listado</a>
    </div>
</div>
</body>
</html>
