@extends('layouts.layoutadmin')

@section('title', 'Panel Administrativo')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 style="margin:0; color:#1e63b8; font-weight:600; font-size:2rem;">
                    <i class="fas fa-users me-2"></i>Empleados
                </h2>
            </div>

            <div class="card-body">

                {{-- Mensaje de éxito --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-circle-check me-2"></i>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                {{-- Formulario de búsqueda y filtros --}}
                <form method="GET" action="{{ route('empleados.hu5') }}" class="mb-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">
                                <i class="fas fa-search text-primary me-2"></i>Búsqueda General
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre, apellido o cargo" value="{{ request('buscar') }}">
                            </div>
                        </div>
                        <div class="col-md-5 d-flex align-items-end gap-2">
                            <button class="btn btn-primary flex-fill" type="submit">
                                <i class="fas fa-search me-2"></i>Buscar
                            </button>
                            <button class="btn btn-outline-primary flex-fill" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosAvanzados" aria-expanded="false">
                                <i class="fas fa-sliders-h me-2"></i>Filtros
                            </button>
                            @if(request()->hasAny(['buscar','rol','estado','fecha_registro']))
                                <a href="{{ route('empleados.hu5') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="fas fa-times me-2"></i>Limpiar
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Filtros avanzados --}}
                    <div class="collapse" id="filtrosAvanzados">
                        <div class="card mb-3 bg-light border-primary">
                            <div class="card-header bg-primary bg-opacity-10">
                                <h6 class="mb-0 text-primary"><i class="fas fa-filter me-2"></i>Filtros Adicionales</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold"><i class="fas fa-user-tag text-primary me-2"></i>Rol</label>
                                        <select name="rol" class="form-select">
                                            <option value="">Todos</option>
                                            <option value="Administrador" {{ request('rol')=='Administrador' ? 'selected' : '' }}>Administrador</option>
                                            <option value="Empleado" {{ request('rol')=='Empleado' ? 'selected' : '' }}>Empleado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold"><i class="fas fa-toggle-on text-success me-2"></i>Estado</label>
                                        <select name="estado" class="form-select">
                                            <option value="">Todos</option>
                                            <option value="Activo" {{ request('estado')=='Activo' ? 'selected' : '' }}>Activo</option>
                                            <option value="Inactivo" {{ request('estado')=='Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold"><i class="fas fa-calendar-alt text-info me-2"></i>Fecha de Registro</label>
                                        <input type="date" name="fecha_registro" class="form-control" value="{{ request('fecha_registro') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-primary">
                        <tr>
                            <th><i class="fas fa-user text-dark me-2"></i>Nombre</th>
                            <th><i class="fas fa-user text-dark me-2"></i>Apellido</th>
                            <th><i class="fas fa-briefcase text-dark me-2"></i>Cargo</th>
                            <th><i class="fas fa-user-tag text-dark me-2"></i>Rol</th>
                            <th><i class="fas fa-toggle-on text-dark me-2"></i>Estado</th>
                            <th><i class="fas fa-calendar-alt text-dark me-2"></i>Fecha Ingreso</th>
                            <th class="text-center"><i class="fas fa-cogs text-secondary me-2"></i>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->nombre }}</td>
                                <td>{{ $empleado->apellido }}</td>
                                <td>{{ $empleado->cargo }}</td>
                                <td><span class="badge bg-primary">{{ $empleado->rol }}</span></td>
                                <td>
                                    @if($empleado->estado == 'Activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarEmpleadoModal{{ $empleado->id }}">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </button>
                                </td>
                            </tr>


                            <div class="modal fade" id="editarEmpleadoModal{{ $empleado->id }}" tabindex="-1" aria-labelledby="editarEmpleadoLabel{{ $empleado->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#1e63b8; color:white;">
                                            <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Editar Empleado</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3 border-bottom pb-2">
                                                    <h6 class="fw-bold text-dark"><i class="fas fa-id-card me-2"></i>Información Personal</h6>
                                                </div>
                                                <div class="row g-3 mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label"><i class="fas fa-user text-dark me-2"></i>Nombre</label>
                                                        <input type="text" name="nombre" class="form-control form-control-sm" value="{{ $empleado->nombre }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label"><i class="fas fa-user text-dark me-2"></i>Apellido</label>
                                                        <input type="text" name="apellido" class="form-control form-control-sm" value="{{ $empleado->apellido }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label"><i class="fas fa-briefcase text-dark me-2"></i>Cargo</label>
                                                        <input type="text" name="cargo" class="form-control form-control-sm" value="{{ $empleado->cargo }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label"><i class="fas fa-calendar-alt text-dark me-2"></i>Fecha de Ingreso</label>
                                                        <input type="date" name="fecha_ingreso" class="form-control form-control-sm" value="{{ $empleado->fecha_ingreso->format('Y-m-d') }}">
                                                    </div>
                                                </div>

                                                <div class="mb-3 border-bottom pb-2">
                                                    <h6 class="fw-bold text-dark"><i class="fas fa-user-shield me-2"></i>Estado del Usuario</h6>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label"><i class="fas fa-user-tag text-dark me-2"></i>Rol</label>
                                                        <select name="rol" class="form-select form-select-sm" required>
                                                            <option value="Administrador" {{ $empleado->rol=='Administrador' ? 'selected' : '' }}>Administrador</option>
                                                            <option value="Empleado" {{ $empleado->rol=='Empleado' ? 'selected' : '' }}>Empleado</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label"><i class="fas fa-toggle-on text-dark me-2"></i>Estado</label>
                                                        <select name="estado" class="form-select form-select-sm">
                                                            <option value="Activo" {{ $empleado->estado=='Activo' ? 'selected' : '' }}>Activo</option>
                                                            <option value="Inactivo" {{ $empleado->estado=='Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label"><i class="fas fa-camera text-dark me-2"></i>Foto (opcional)</label>
                                                        <input type="file" name="foto" class="form-control form-control-sm">
                                                    </div>
                                                </div>

                                                <div class="mt-4 text-end">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-2"></i>Cancelar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-save me-2"></i>Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-2x mb-2 d-block"></i>No hay empleados registrados
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>


                <div class="mt-4">
                    {{ $empleados->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
