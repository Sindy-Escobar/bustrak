<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de Empleados - BusTrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar con gradiente exacto */
        .navbar-bustrak {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Contenedor para logo y menú (izquierda) */
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .navbar-brand-bustrak {
            color: white !important;
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .nav-link-bustrak {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-link-bustrak:hover,
        .nav-link-bustrak.active {
            color: white !important;
            background-color: rgba(255,255,255,0.2);
        }

        .btn-login {
            background-color: rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 5px;
            border: 1px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: rgba(255,255,255,0.3);
            color: white;
        }

        /* Contenido principal */
        .main-content {
            padding: 20px;
            margin-top: 0;
        }

        .header-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .main-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 2rem;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .search-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
        }

        .table-custom {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table-custom thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table-custom th {
            font-weight: 600;
            border: none;
            padding: 15px 12px;
        }

        .table-custom td {
            padding: 12px;
            vertical-align: middle;
            border-color: #ecf0f1;
        }

        .badge-validado {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-no-validado {
            background: linear-gradient(135deg, #e67e22, #d35400);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .btn-validar {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-invalidar {
            background: linear-gradient(135deg, #e67e22, #d35400);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-filtro {
            background: #ecf0f1;
            border: 2px solid #bdc3c7;
            color: #2c3e50;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 600;
            margin: 0 0.2rem;
        }

        .btn-filtro.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .pagination .page-link {
            color: #667eea;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-bustrak {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }

            .navbar-left {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                gap: 15px;
            }
        }
    </style>
</head>
<body>
<!-- Navbar Superior con gradiente exacto -->
<nav class="navbar-bustrak">
    <!-- Logo BusTrak y Menú de Navegación juntos a la izquierda -->
    <div class="navbar-left">
        <!-- Logo BusTrak -->
        <div class="navbar-brand-bustrak">
            BusTrak
        </div>

        <!-- Menú de Navegación -->
        <ul class="nav-links">
            <li>
                <a class="nav-link-bustrak" href="{{ route('login') }}">
                    Inicio
                </a>
            </li>
            <li>
                <a class="nav-link-bustrak active" href="#">
                    Validación Empleados
                </a>
            </li>
            <li>
                <a class="nav-link-bustrak" href="{{ route('terminales.index') }}">
                    Terminales
                </a>
            </li>
        </ul>
    </div>

    <!-- Botón Cerrar Sesión FUNCIONAL -->
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn-login">
            Cerrar Sesión
        </button>
    </form>
</nav>

<!-- Contenido Principal -->
<div class="container main-content">
    <!-- Header Section -->
    <div class="header-section">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h1 class="main-title">Validación de Empleados</h1>
            </div>
        </div>
    </div>

    <!-- Filtros Section -->
    <div class="search-section">
        <div class="row">
            <div class="col-md-12">
                <label class="form-label mb-3">Filtrar por estado de validación:</label>
                <div class="d-flex justify-content-center">
                    <div class="btn-group" role="group">
                        <a href="{{ request()->fullUrlWithQuery(['filtro' => 'todos']) }}"
                           class="btn btn-filtro {{ (request('filtro') ?? 'todos') == 'todos' ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i>Todos los Empleados
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filtro' => 'validados']) }}"
                           class="btn btn-filtro {{ request('filtro') == 'validados' ? 'active' : '' }}">
                            <i class="fas fa-user-check me-2"></i>Empleados Validados
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filtro' => 'no-validados']) }}"
                           class="btn btn-filtro {{ request('filtro') == 'no-validados' ? 'active' : '' }}">
                            <i class="fas fa-user-clock me-2"></i>Pendientes de Validar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Results Section -->
    <div class="search-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">
                <i class="fas fa-list me-2"></i>Lista de Empleados
            </h3>
            <span class="text-muted">
                @if(isset($empleados) && $empleados->count() > 0)
                    Mostrando {{ $empleados->firstItem() }} - {{ $empleados->lastItem() }} de {{ $empleados->total() }} empleados
                @endif
            </span>
        </div>

        @if(isset($empleados) && $empleados->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Departamento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($empleados as $empleado)
                        <tr>
                            <td><strong>{{ $empleado->id }}</strong></td>
                            <td>{{ $empleado->nombre }}</td>
                            <td>{{ $empleado->email }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $empleado->departamento->nombre ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if($empleado->validado)
                                    <span class="badge badge-validado">
                                        <i class="fas fa-check-circle me-1"></i>Validado
                                    </span>
                                @else
                                    <span class="badge badge-no-validado">
                                        <i class="fas fa-clock me-1"></i>No Validado
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($empleado->validado)
                                    <form action="{{ route('empleados.invalidar', $empleado->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-invalidar"
                                                onclick="return confirm('¿Está seguro de invalidar a {{ $empleado->nombre }}?')">
                                            <i class="fas fa-times me-1"></i>Invalidar
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('empleados.validar', $empleado->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-validar"
                                                onclick="return confirm('¿Está seguro de validar a {{ $empleado->nombre }}?')">
                                            <i class="fas fa-check me-1"></i>Validar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    {{ $empleados->links() }}
                </nav>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron empleados</h4>
                <p class="text-muted">No hay empleados que coincidan con el filtro aplicado</p>
            </div>
        @endif
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-ocultar alertas después de 5 segundos
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
</body>
</html>
