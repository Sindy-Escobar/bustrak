@extends('layouts.apps')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 style="margin:0; color:#1e63b8; font-weight:600; font-size:2rem;">
                    <i class="fas fa-user-edit me-2"></i>Consultar Usuarios con Filtros
                </h2>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('usuarios.consultar') }}">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Rol</label>
                            <select name="rol" class="form-select">
                                <option value="">Todos los roles</option>
                                <option value="admin" {{ request('rol')=='admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="empleado" {{ request('rol')=='empleado' ? 'selected' : '' }}>Empleado</option>
                                <option value="cliente" {{ request('rol')=='cliente' ? 'selected' : '' }}>Cliente</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="activo" {{ request('estado')=='activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ request('estado')=='inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Area Asignada</label>
                            <input type="text" name="area" class="form-control" placeholder="Ingrese area" value="{{ request('area') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Permiso</label>
                            <input type="text" name="permiso" class="form-control" placeholder="Ingrese permiso" value="{{ request('permiso') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Fecha de Registro</label>
                            <input type="date" name="fecha_registro" class="form-control" value="{{ request('fecha_registro') }}">
                        </div>

                        <div class="col-md-8 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Aplicar Filtros
                            </button>
                            <a href="{{ route('usuarios.consultar') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-1"></i>Limpiar
                            </a>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>DNI</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Area</th>
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
                                <td colspan="8" class="text-center text-muted py-4">
                                    No se encontraron usuarios con los filtros aplicados
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $usuarios->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
