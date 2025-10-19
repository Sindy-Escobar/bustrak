<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados - BusTrak</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; padding:40px; }
        .container { max-width:1200px; margin:auto; }
        h1 { color:white; text-align:center; margin-bottom:30px; }
        .card { background:white; border-radius:20px; padding:20px; margin-bottom:20px; box-shadow:0 15px 40px rgba(0,0,0,0.2); }
        .card h3 { color:#667eea; margin-bottom:10px; }
        .card p { margin-bottom:10px; color:#555; }
        .btn { padding:8px 14px; border:none; border-radius:10px; cursor:pointer; font-weight:600; margin-right:5px; text-decoration:none; display:inline-block; }
        .btn-edit { background:#f1c40f; color:#fff; }
        .btn-toggle { color:#fff; }
        .btn-activate { background:#2ecc71; }
        .btn-deactivate { background:#e74c3c; }
        .top-btn { display:block; text-align:right; margin-bottom:20px; }
        .top-btn a { background:#2d9cdb; color:white; padding:10px 20px; border-radius:10px; text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
<div class="container">
    <h1>Empleados</h1>

    <div class="top-btn">
        <a href="{{ route('empleados.create') }}">Registrar Nuevo</a>
    </div>

    @forelse($empleados as $empleado)
        <div class="card">
            <h3>{{ $empleado->nombre }} {{ $empleado->apellido }}</h3>
            <p><strong>DNI:</strong> {{ $empleado->dni }}</p>
            <p><strong>Cargo:</strong> {{ $empleado->cargo }}</p>
            <p><strong>Fecha de ingreso:</strong> {{ $empleado->fecha_ingreso }}</p>
            <p><strong>Estado:</strong>
                <span style="color:{{ $empleado->estado === 'Activo' ? '#2ecc71' : '#7f8c8d' }};">{{ $empleado->estado }}</span>
            </p>
            <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-edit">Editar</a>
            <form action="{{ route('empleados.toggle', $empleado->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-toggle {{ $empleado->estado === 'Activo' ? 'btn-deactivate' : 'btn-activate' }}">
                    {{ $empleado->estado === 'Activo' ? 'Desactivar' : 'Activar' }}
                </button>
            </form>
        </div>
    @empty
        <p style="color:white; text-align:center;">No hay empleados registrados.</p>
    @endforelse
</div>
</body>
</html>
