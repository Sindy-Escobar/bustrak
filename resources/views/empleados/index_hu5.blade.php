@extends('layouts.layoutadmin')

@section('title', 'Listado de Empleados')

@section('content')
    <div class="container">

        <!-- Título -->
        <div class="header-section text-center mb-4">
            <h2 style="color: #000; font-weight: 700;">
                <i class="fas fa-users"></i> Listado de Empleados
            </h2>
        </div>

        <!-- Filtros -->
        <div class="filter-card">
            <form method="GET" action="{{ route('empleados.hu5') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-600 mb-2"><i class="fas fa-search"></i> Buscar</label>
                        <input type="text" name="buscar" class="form-control" placeholder="Nombre, apellido o cargo"
                               value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-600 mb-2"><i class="fas fa-toggle-on"></i> Estado</label>
                        <select name="estado" class="form-select">
                            <option value="">-- Estado --</option>
                            <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-600 mb-2"><i class="fas fa-user-shield"></i> Rol</label>
                        <select name="rol" class="form-select">
                            <option value="">-- Rol --</option>
                            <option value="Administrador" {{ request('rol') == 'Administrador' ? 'selected' : '' }}>
                                Administrador
                            </option>
                            <option value="Empleado" {{ request('rol') == 'Empleado' ? 'selected' : '' }}>Empleado
                            </option>
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

        <!-- Tabla de empleados -->
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
                                <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                    color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
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
            <div class="empty-state text-center">
                <i class="fas fa-inbox fa-3x text-warning mb-3"></i>
                <h4 style="color:#333;">No hay empleados registrados</h4>
            </div>
        @endif
    </div>
@endsection

@section('styles')
    <style>
        body {
            background: #f5f7fa;
        }

        .header-section h2 {
            text-shadow: none;
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f8f9ff;
        }

        .empty-state {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
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
    </style>
@endsection
