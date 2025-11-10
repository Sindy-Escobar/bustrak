@extends('layouts.layoutadmin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 style="margin:0; color:#1e63b8; font-weight:600; font-size:2rem;">
                    <i class="fas fa-building me-2"></i>Empresas de Transporte
                </h2>
            </div>

            <div class="card-body">
                <!-- Mensaje de éxito -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-circle-check me-2"></i>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                <!-- Formulario de búsqueda y filtros -->
                <form method="GET" action="{{ route('empresas.index') }}" class="mb-4">
                    <div class="row g-3 mb-3">
                        <!-- Búsqueda -->
                        <div class="col-md-7">
                            <label class="form-label fw-bold">
                                <i class="fas fa-search text-primary me-2"></i>Búsqueda General
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Buscar por nombre"
                                    value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="col-md-5 d-flex align-items-end gap-2">
                            <button class="btn btn-primary flex-fill" type="submit">
                                <i class="fas fa-search me-2"></i>Buscar
                            </button>
                            <button class="btn btn-outline-primary flex-fill" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosAvanzados" aria-expanded="false">
                                <i class="fas fa-sliders-h me-2"></i>Filtros
                            </button>
                            @if(request()->hasAny(['search', 'estado']))
                                <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="fas fa-times me-2"></i>Limpiar
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Filtros avanzados -->
                    <div class="collapse" id="filtrosAvanzados">
                        <div class="card mb-3 bg-light border-primary">
                            <div class="card-header bg-primary bg-opacity-10">
                                <h6 class="mb-0 text-primary">
                                    <i class="fas fa-filter me-2"></i>Filtros Adicionales
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-toggle-on text-success me-2"></i>Estado
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-toggle-on"></i>
                                            </span>
                                            <select name="estado" class="form-select">
                                                <option value="">Todos los estados</option>
                                                <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activas</option>
                                                <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Tabla de Empresas -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-primary">
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>ID</th>
                            <th><i class="fas fa-building me-2"></i>Nombre</th>
                            <th><i class="fas fa-id-card me-2"></i>Número Registro</th>
                            <th><i class="fas fa-envelope me-2"></i>Correo</th>
                            <th><i class="fas fa-phone me-2"></i>Teléfono</th>
                            <th><i class="fas fa-toggle-on me-2"></i>Estado</th>
                            <th class="text-center"><i class="fas fa-cog me-2"></i>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($empresas as $empresa)
                            <tr>
                                <td>{{ $empresa->id }}</td>
                                <td>{{ $empresa->nombre }}</td>
                                <td>{{ $empresa->numero_registro }}</td>
                                <td>{{ $empresa->email }}</td>
                                <td>{{ $empresa->telefono }}</td>
                                <td>
                                    @if($empresa->estado)
                                        <span class="badge bg-success">Activa</span>
                                    @else
                                        <span class="badge bg-danger">Inactiva</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarModal{{ $empresa->id }}">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal de edición -->
                            <div class="modal fade" id="editarModal{{ $empresa->id }}" tabindex="-1" aria-labelledby="editarModalLabel{{ $empresa->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#1e63b8; color:white;">
                                            <h5 class="modal-title" id="editarModalLabel{{ $empresa->id }}">
                                                <i class="fas fa-building me-2"></i>Editar Empresa: {{ $empresa->nombre }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('empresas.update', $empresa->id) }}" method="POST" id="formEditar{{ $empresa->id }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="nombre{{ $empresa->id }}" class="form-label">Nombre <span class="text-danger">*</span></label>
                                                    <input type="text" name="nombre" id="nombre{{ $empresa->id }}" value="{{ old('nombre', $empresa->nombre) }}" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email{{ $empresa->id }}" class="form-label">Correo <span class="text-danger">*</span></label>
                                                    <input type="email" name="email" id="email{{ $empresa->id }}" value="{{ old('email', $empresa->email) }}" class="form-control" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="telefono{{ $empresa->id }}" class="form-label">Teléfono</label>
                                                    <input type="text" name="telefono" id="telefono{{ $empresa->id }}" value="{{ old('telefono', $empresa->telefono) }}" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="estado{{ $empresa->id }}" class="form-label">Estado</label>
                                                    <select name="estado" id="estado{{ $empresa->id }}" class="form-select">
                                                        <option value="1" {{ $empresa->estado_validacion ? 'selected' : '' }}>Activa</option>
                                                        <option value="0" {{ !$empresa->estado_validacion ? 'selected' : '' }}>Inactiva</option>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancelar</button>
                                            <button type="submit" form="formEditar{{ $empresa->id }}" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar Cambios</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-building fa-2x mb-2 d-block"></i>
                                    No se encontraron empresas registradas.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    @if($empresas->count())
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Mostrando {{ $empresas->firstItem() }} - {{ $empresas->lastItem() }} de {{ $empresas->total() }} empresas
                            </div>
                            <div>
                                {{ $empresas->appends(request()->all())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
