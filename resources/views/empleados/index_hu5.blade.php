<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }

        .container {
            max-width: 1200px;
        }

        .header-section {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
            animation: fadeInDown 0.8s ease-out;
        }

        .header-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .header-section p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: fadeInUp 0.8s ease-out 0.1s backwards;
        }

        .filter-card .row {
            gap: 1rem;
        }

        .filter-card input,
        .filter-card select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .filter-card input:focus,
        .filter-card select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }

        .btn-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .table-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .table-responsive {
            border-radius: 15px;
        }

        .table {
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table thead th {
            border: none;
            padding: 1.25rem;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9ff;
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            color: #333;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, #a8a8a8 0%, #d3d3d3 100%) !important;
        }

        .empty-state {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: #333;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .empty-state p {
            color: #666;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding-bottom: 1rem;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 0.25rem;
            border: none;
            background-color: transparent;
            color: #667eea;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .header-section h2 {
                font-size: 1.8rem;
            }

            .filter-card .row {
                flex-direction: column;
            }

            .filter-card input,
            .filter-card select,
            .btn-filter {
                width: 100%;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="header-section">
        <h2><i class="fas fa-users"></i> Listado de Empleados</h2>

    </div>

    <div class="mb-4 text-start">
        <a href="{{ url('/empleados') }}"
           class="btn btn-gradient shadow-sm px-4 py-2 rounded-pill text-white"
           style="background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%); transition: 0.3s;">
            <i class="fas fa-arrow-left me-2"></i> Regresar
        </a>
    </div>

    <style>
        .btn-gradient:hover {
            opacity: 0.9;
            transform: translateX(-3px);
        }
    </style>


    <div class="filter-card">
        <form method="GET" action="{{ route('empleados.hu5') }}">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-600 mb-2"><i class="fas fa-search"></i> Buscar</label>
                    <input type="text" name="buscar" class="form-control" placeholder="Nombre, apellido o cargo" value="{{ request('buscar') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-600 mb-2"><i class="fas fa-toggle-on"></i> Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">-- Estado --</option>
                        <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-600 mb-2"><i class="fas fa-user-shield"></i> Rol</label>
                    <select name="rol" class="form-select">
                        <option value="">-- Rol --</option>
                        <option value="Administrador" {{ request('rol') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="Empleado" {{ request('rol') == 'Empleado' ? 'selected' : '' }}>Empleado</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-filter text-white flex-grow-1">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('empleados.hu5') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>


    @if($empleados->count() > 0)
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-user"></i> Apellido</th>
                        <th><i class="fas fa-briefcase"></i> Cargo</th>
                        <th><i class="fas fa-calendar"></i> Fecha de Ingreso</th>
                        <th><i class="fas fa-info-circle"></i> Estado</th>
                        <th><i class="fas fa-key"></i> Rol</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($empleados as $empleado)
                        <tr>
                            <td>{{ $empleado->nombre }}</td>
                            <td>{{ $empleado->apellido }}</td>
                            <td>{{ $empleado->cargo }}</td>
                            <td>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
                            <td>
                                @if($empleado->estado == 'Activo')
                                    <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Activo
                                            </span>
                                @else
                                    <span class="badge bg-secondary">
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            </span>
                                @endif
                            </td>
                            <td>
                                        <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                            {{ $empleado->rol }}
                                        </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $empleados->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>

        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h4>No hay empleados registrados</h4>

        </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
