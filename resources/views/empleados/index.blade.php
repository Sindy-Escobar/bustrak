<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados - BusTrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        h1 {
            color:white;
            text-align:center;
            margin-bottom:20px;
        }
        .employee-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            overflow-y: auto;
            flex: 1 1 auto;
            padding: 5px;
        }
        .employee-card {
            background:white;
            border-radius:15px;
            padding:15px;
            box-shadow:0 10px 25px rgba(0,0,0,0.15);
            text-align:center;
            font-size: 13px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .employee-card:hover {
            transform: translateY(-3px);
        }
        .employee-card h5 { color:#667eea; margin-bottom:5px; font-size:14px; }
        .employee-card p { margin:2px 0; color:#555; }
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
        .top-btn {
            text-align: right;
            margin-bottom: 15px;
        }
        .top-btn a {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            background: #2d9cdb;
            color: #fff;
            text-decoration: none;
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
            <a href="{{ route('empleados.show', $empleado->id) }}" style="text-decoration:none;">
                <div class="employee-card">
                    <h5>{{ $empleado->nombre }} {{ $empleado->apellido }}</h5>
                    <p><strong>Cargo:</strong> {{ $empleado->cargo }}</p>
                    <p><strong>Estado:</strong>
                        <span style="color:{{ $empleado->estado === 'Activo' ? '#2ecc71' : '#7f8c8d' }}">
                            {{ $empleado->estado }}
                        </span>
                    </p>
                </div>
            </a>
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
