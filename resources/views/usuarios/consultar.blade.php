@extends('layouts.PlantillaCRUD')

@section('styles')
    <style>
        /* Mantener estilos consistentes con index */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            color: white !important;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-outline-secondary {
            color: #667eea !important;
            border-color: #667eea !important;
        }
        .btn-outline-secondary:hover {
            background: #667eea !important;
            border-color: #667eea !important;
            color: white !important;
        }
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
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border: none;
        }
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05) !important;
        }
        .alert-success {
            background-color: #f0fdf4;
            border-color: #86efac;
            color: #166534;
        }
        h2 {
            color: #2d3748;
            font-weight: 700;
        }
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
        .filters select, .filters input {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .filters-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        .filters-row .form-group {
            flex: 1 1 150px;
            display: flex;
            flex-direction: column;
        }
        .filters-row .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }
    </style>
@endsection

@section('contenido')
    <div class="container mt-4">

        <h2 class="mb-4">Consultar Usuarios</h2>

        {{-- Filtros avanzados --}}
        <form method="GET" action="{{ route('usuarios.consultar') }}" class="mb-3">
            {{-- Fila 1: Rol, Estado, Área, Permiso --}}
            <div class="filters-row">
                <div class="form-group">
                    <select name="rol" class="form-control">
                        <option value="">Seleccione Rol</option>
                        <option value="admin" {{ request('rol')=='admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="empleado" {{ request('rol')=='empleado' ? 'selected' : '' }}>Empleado</option>
                        <option value="cliente" {{ request('rol')=='cliente' ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="estado" class="form-control">
                        <option value="">Seleccione Estado</option>
                        <option value="activo" {{ request('estado')=='activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="area" class="form-control" value="{{ request('area') }}" placeholder="Área asignada">
                </div>
                <div class="form-group">
                    <input type="text" name="permiso" class="form-control" value="{{ request('permiso') }}" placeholder="Permiso">
                </div>
            </div>

            {{-- Fila 2: Calendarios + Botón Filtrar --}}
            <div class="filters-row" style="align-items: flex-end;">
                <div class="form-group">
                    <label>Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="form-group">
                    <label>Fecha Final</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>
                <div style="flex:0 0 auto; margin-left:10px;">
                    <button type="submit" class="btn btn-primary" style="height: 38px;">Filtrar</button>
                </div>
            </div>
        </form>

        {{-- Mensaje de error --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Tabla de resultados --}}
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>DNI</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Área</th>
                <th>Permiso</th>
                <th>Fecha Registro</th>
            </tr>
            </thead>
            <tbody>
            @if($usuarios->isEmpty())
                <tr>
                    <td colspan="9" class="text-center text-muted">No se encontraron usuarios con los filtros aplicados.</td>
                </tr>
            @else
                @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre_completo }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->dni }}</td>
                        <td>{{ $usuario->rol ?? '-' }}</td>
                        <td>{{ $usuario->estado ?? 'Activo' }}</td>
                        <td>{{ $usuario->area ?? '-' }}</td>
                        <td>{{ $usuario->permiso ?? '-' }}</td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>

                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>

        {{-- Paginación --}}
        {{ $usuarios->appends(request()->all())->links() }}
    </div>
@endsection
