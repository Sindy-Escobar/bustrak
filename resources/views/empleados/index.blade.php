<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados - BusTrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            margin: 0;
            padding: 20px 0;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .employee-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            padding: 5px 0;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .top-btn a.text-link {
            color: #000; /* negro */
            text-decoration: none;
            font-size: 18px; /* más grande */
            font-weight: 600;
        }
        .top-btn a.text-link i {
            margin-right: 8px;
            font-size: 20px; /* ícono más grande */
        }
    </style>
</head>
<body>
<div class="container">
    <h1 style="color:black; text-align:center; margin-bottom:10px;">Lista de Empleados</h1>

    <div style="color:white; text-align:center; margin-bottom:20px;">
        <span style="margin-right:20px;"><strong>Activos:</strong> {{ $total_activos }}</span>
        <span style="margin-right:20px;"><strong>Inactivos:</strong> {{ $total_inactivos }}</span>
        <span><strong>Empleados Registrados:</strong> {{ $total_empleados }}</span>
    </div>

    <div class="top-btn">
        <!-- Izquierda: Buscar Empleado -->
        <div>
            <a href="{{ route('empleados.hu5') }}" class="text-link">
                <i class="fas fa-search"></i> Buscar Empleado
            </a>
        </div>

        <!-- Derecha: Registrar Nuevo -->
        <div>
            <a href="{{ route('empleados.create') }}" class="btn btn-primary">Registrar Nuevo</a>
        </div>
    </div>

    <div class="employee-grid">
        @forelse($empleados as $empleado)
            <a href="{{ route('empleados.show', $empleado->id) }}" style="text-decoration:none;">
                <div class="employee-card">
                    @if($empleado->foto && file_exists(storage_path('app/public/'.$empleado->foto)))
                        <img src="{{ asset('storage/'.$empleado->foto) }}" alt="Foto de {{ $empleado->nombre }}" style="width:100px; height:100px; object-fit:cover; border-radius:50%; margin-bottom:10px;">
                    @else
                        <img src="https://via.placeholder.com/100?text=Sin+Foto" alt="Sin foto" style="width:100px; height:100px; object-fit:cover; border-radius:50%; margin-bottom:10px;">
                    @endif
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
