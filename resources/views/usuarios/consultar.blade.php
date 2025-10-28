@extends('layouts.PlantillaCRUD')

@section('styles')
    <style>
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            color: white !important;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border: none;
        }
        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05) !important;
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
            margin-bottom: 4px;
        }
    </style>
@endsection

@section('contenido')
    <div class="container mt-4">
        <h2 class="mb-4">Consultar Usuarios</h2>

        {{-- Formulario de filtros --}}
        <form method="GET" action="{{ route('usuarios.consultar') }}" class="mb-3">

            {{-- Primera fila: los cuatro filtros principales --}}
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
                    <input type="text" name="area" class="form-control" placeholder="Área asignada" value="{{ request('area') }}">
                </div>

                <div class="form-group">
                    <input type="text" name="permiso" class="form-control" placeholder="Permiso" value="{{ request('permiso') }}">
                </div>
            </div>

            {{-- Segunda fila: fecha de registro y botón --}}
            <div class="filters-row" style="align-items: flex-end; margin-top: 10px;">
                <div class="form-group" style="flex:1;">
                    <label>Fecha de Registro</label>
                    <input type="date" name="fecha_registro" class="form-control" value="{{ request('fecha_registro') }}">
                </div>

                <div style="flex:0 0 auto; margin-left: 10px;">
                    <button type="submit" class="btn btn-primary" style="height: 38px;">Filtrar</button>
                </div>
            </div>
        </form>


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
            @forelse($usuarios as $usuario)
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
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No se encontraron usuarios con los filtros aplicados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        {{ $usuarios->appends(request()->all())->links() }}
    </div>
@endsection
