<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusTrak - Empresas de Buses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .bus-trak-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: none;
            margin: 2rem 0;
        }

        .bus-trak-header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 2rem;
        }

        .bus-trak-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .bus-trak-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }

        .content-section {
            padding: 2rem;
        }

        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .table-custom thead {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
        }

        .search-box {
            background: #ecf0f1;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .badge-active {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-inactive {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .btn-search {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .pagination .page-link {
            color: #2c3e50;
            border-color: #3498db;
        }

        .pagination .page-item.active .page-link {
            background: #3498db;
            border-color: #3498db;
            color: white;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="bus-trak-card">
                <!-- Header -->
                <div class="bus-trak-header">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <h1 class="bus-trak-title">BusTrak</h1>
                            <p class="bus-trak-subtitle">Consulta de Empresas de Transporte Registradas</p>
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="content-section">
                    <!-- Barra de Búsqueda y Filtros -->
                    <div class="search-box">
                        <form action="{{ route('empresas.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                               placeholder="Buscar por nombre o número de registro..."
                                               value="{{ request('search') }}">
                                        <button class="btn btn-search" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select name="estado" class="form-control" onchange="this.form.submit()">
                                        <option value="">Todos los estados</option>
                                        <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activas</option>
                                        <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivas</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-refresh"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabla de Empresas -->
                    <div class="table-responsive">
                        <table class="table table-hover table-custom">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Número de Registro</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($empresas as $empresa)
                                <tr>
                                    <td>{{ $empresa->id }}</td>
                                    <td>{{ $empresa->nombre }}</td>
                                    <td>{{ $empresa->numero_registro }}</td>
                                    <td>{{ $empresa->correo }}</td>
                                    <td>{{ $empresa->telefono }}</td>
                                    <td>
                                        @if($empresa->estado)
                                            <span class="badge badge-active">Activa</span>
                                        @else
                                            <span class="badge badge-inactive">Inactiva</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No se encontraron empresas registradas.</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Mostrando {{ $empresas->firstItem() }} - {{ $empresas->lastItem() }} de {{ $empresas->total() }} empresas
                        </div>
                        <div>
                            {{ $empresas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
