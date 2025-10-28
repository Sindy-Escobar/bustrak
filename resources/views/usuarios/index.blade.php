@extends('layouts.PlantillaCRUD')

@section('styles')
    <style>
        /* Cambiar color del botón primario a gradiente morado/azul */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            color: white !important;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Cambiar color del botón de búsqueda */
        .btn-outline-secondary {
            color: #667eea !important;
            border-color: #667eea !important;
        }

        .btn-outline-secondary:hover {
            background: #667eea !important;
            border-color: #667eea !important;
            color: white !important;
        }

        /* Cambiar color del botón info (Ver) a cyan */
        .btn-info {
            background: #00bcd4 !important;
            border-color: #00bcd4 !important;
            color: white !important;
        }

        .btn-info:hover {
            background: #00acc1 !important;
            border-color: #00acc1 !important;
            transform: translateY(-1px);
        }

        /* Estilo para el encabezado de la tabla */
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border: none;
        }

        /* Color de las filas al pasar el mouse */
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05) !important;
        }

        /* Color de alerta de éxito */
        .alert-success {
            background-color: #f0fdf4;
            border-color: #86efac;
            color: #166534;
        }

        /* Estilo del título */
        h2 {
            color: #2d3748;
            font-weight: 700;
        }

        /* Paginación con colores morado/azul */
        .pagination .page-link {
            color: #667eea;
        }

        .pagination .page-link:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
        }
    </style>
@endsection

@section('contenido')
    <div class="container mt-4">

        <!-- BLOQUE DE MENSAJE DE ÉXITO -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="mb-4">Usuarios Registrados</h2>

        <!-- BARRA DE BÚSQUEDA -->
        <form method="GET" action="{{ url('/usuarios') }}" class="mb-4">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Buscar por Nombre Completo, DNI o Email..."
                    value="{{ request('search') }}"
                >
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if(request('search'))
                    <a href="{{ url('/usuarios') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>

        <div class="d-flex mb-3 gap-2">
            <a href="{{ url('/registro') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Registrar Nuevo Usuario
            </a>
            <a href="{{ route('usuarios.consultar') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Consultar Usuarios
            </a>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-primary">
                <i class="fas fa-user-check"></i> Validar Usuarios
            </a>
        </div>



        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>DNI</th>
                <th>Detalles</th>
            </tr>
            </thead>
            <tbody>
            <!-- Aquí usamos $Usuarios (con U mayúscula) para evitar el error de variable indefinida -->
            @if($usuarios->isEmpty() && request('search'))
                <tr>
                    <td colspan="4" class="text-center text-muted">No se encontraron resultados para "{{ request('search') }}".</td>
                </tr>
            @elseif($usuarios->isEmpty())
                <tr>
                    <td colspan="4" class="text-center text-muted">Aún no hay empleados registrados.</td>
                </tr>
            @else
                @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre_completo }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->dni }}</td>

                        <!-- AÑADIMOS EL BOTÓN VER -->
                        <td>
                            <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{route ('usuarios.edit', $usuario->id)}}" class="btn btn-primary btn-sm">
                                <i class ="fas fa-edit"></i> Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>

        <!-- Paginación: Usa $Usuarios -->
        {{ $usuarios->appends(['search' => request('search')])->links() }}
    </div>
@endsection
