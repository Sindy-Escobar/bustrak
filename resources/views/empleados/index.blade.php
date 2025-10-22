<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados - BusTrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0; padding: 0;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            height: 100vh;
            overflow: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        h1 {
            color:white;
            text-align:center;
            margin-bottom:20px;
            flex: 0 0 auto;
        }

        .top-btn {
            text-align:right;
            margin-bottom:15px;
            flex: 0 0 auto;
        }

        .top-btn a {
            background:#2d9cdb;
            color:white;
            padding:10px 20px;
            border-radius:10px;
            text-decoration:none;
            font-weight:600;
        }

        .employee-grid {
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            padding: 5px;
        }

        .employee-card {
            background:white;
            border-radius:15px;
            padding:10px;
            box-shadow:0 10px 25px rgba(0,0,0,0.15);
            text-align:center;
            font-size: 13px;
        }

        .employee-card h5 { color:#667eea; margin-bottom:5px; font-size:14px; }
        .employee-card p { margin:2px 0; color:#555; }

        .btn { padding:5px 8px; border:none; border-radius:8px; font-weight:600; margin:3px; font-size:11px; cursor:pointer; }
        .btn-edit { background:#f1c40f; color:#fff; }
        .btn-toggle { color:#fff; }
        .btn-activate { background:#2ecc71; }
        .btn-deactivate { background:#e74c3c; }

        .pagination {
            justify-content: center;
            margin-top: 10px;
        }
        .pagination .page-item .page-link {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
        }
        .pagination .page-item.active .page-link {
            background: rgba(255,255,255,0.5);
            color: white;
        }
        .pagination .page-item .page-link:hover {
            background: rgba(255,255,255,0.4);
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Empleados</h1>

    <div class="top-btn">
        <a href="{{ route('empleados.create') }}">Registrar Nuevo</a>
    </div>

    <div class="employee-grid">
        @forelse($empleados as $empleado)
            <div class="employee-card">
                <h5>{{ $empleado->nombre }} {{ $empleado->apellido }}</h5>
                <p><strong>DNI:</strong> {{ $empleado->dni }}</p>
                <p><strong>Cargo:</strong> {{ $empleado->cargo }}</p>
                <p><strong>Rol:</strong> {{ $empleado->rol }}</p> <!-- <- agregado -->
                <p><strong>Fecha ingreso:</strong> {{ $empleado->fecha_ingreso }}</p>
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

    <div class="pagination-container">
        {{ $empleados->links('pagination::bootstrap-5') }}
    </div>
</div>
</body>
</html>
