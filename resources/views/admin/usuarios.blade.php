@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-users-cog"></i> Gestión de Usuarios
                            </h4>
                            <span class="badge bg-light text-dark">
                            Total: {{ $usuarios->count() }}
                        </span>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Mensajes --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>¡Éxito!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Error:</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Filtros --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-search"></i>
                                </span>
                                    <input type="text"
                                           id="searchInput"
                                           class="form-control"
                                           placeholder="Buscar por nombre o email...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="btn-group w-100" role="group">
                                    <button type="button"
                                            class="btn btn-outline-success active"
                                            onclick="filtrarPorEstado('todos')">
                                        <i class="fas fa-list"></i> Todos
                                    </button>
                                    <button type="button"
                                            class="btn btn-outline-success"
                                            onclick="filtrarPorEstado('activo')">
                                        <i class="fas fa-check-circle"></i> Activos
                                    </button>
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            onclick="filtrarPorEstado('inactivo')">
                                        <i class="fas fa-ban"></i> Inactivos
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla --}}
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle" id="tablaUsuarios">
                                <thead class="table-dark">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th style="width: 120px;" class="text-center">Estado</th>
                                    <th style="width: 140px;">Fecha Registro</th>
                                    <th style="width: 180px;" class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($usuarios as $usuario)
                                    <tr data-estado="{{ $usuario->estado }}" data-nombre="{{ strtolower($usuario->name) }}" data-email="{{ strtolower($usuario->email) }}">
                                        <td><strong>#{{ $usuario->id }}</strong></td>
                                        <td>
                                            <i class="fas fa-user text-secondary me-2"></i>
                                            {{ $usuario->name }}
                                        </td>
                                        <td>
                                            <i class="fas fa-envelope text-secondary me-2"></i>
                                            <small>{{ $usuario->email }}</small>
                                        </td>
                                        <td class="text-center">
                                            @if($usuario->estado === 'activo')
                                                <span class="badge bg-success px-3 py-2">
                                                <i class="fas fa-check-circle"></i> Activo
                                            </span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2">
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $usuario->created_at->format('d/m/Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.usuarios.cambiarEstado', $usuario->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirmarCambio('{{ $usuario->name }}', '{{ $usuario->estado }}');">
                                                @csrf
                                                @method('PATCH')

                                                @if($usuario->estado === 'activo')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-warning"
                                                            title="Inactivar usuario">
                                                        <i class="fas fa-ban"></i> Inactivar
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                            class="btn btn-sm btn-success"
                                                            title="Activar usuario">
                                                        <i class="fas fa-check"></i> Activar
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted mb-0">No hay usuarios registrados en el sistema</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Resumen --}}
                        @if($usuarios->count() > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info mb-0">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <h5 class="mb-0">
                                                    <i class="fas fa-users me-2"></i>
                                                    <strong>{{ $usuarios->count() }}</strong>
                                                </h5>
                                                <small>Total Usuarios</small>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 class="mb-0 text-success">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    <strong>{{ $usuarios->where('estado', 'activo')->count() }}</strong>
                                                </h5>
                                                <small>Activos</small>
                                            </div>
                                            <div class="col-md-4">
                                                <h5 class="mb-0 text-danger">
                                                    <i class="fas fa-ban me-2"></i>
                                                    <strong>{{ $usuarios->where('estado', 'inactivo')->count() }}</strong>
                                                </h5>
                                                <small>Inactivos</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Confirmar cambio de estado
            function confirmarCambio(nombre, estadoActual) {
                const accion = estadoActual === 'activo' ? 'INACTIVAR' : 'ACTIVAR';
                const advertencia = estadoActual === 'activo'
                    ? 'El usuario NO podrá acceder al sistema.'
                    : 'El usuario podrá acceder nuevamente al sistema.';

                return confirm(`¿Estás seguro de ${accion} a "${nombre}"?\n\n${advertencia}`);
            }

            // Búsqueda en tiempo real
            document.getElementById('searchInput')?.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#tablaUsuarios tbody tr');

                rows.forEach(row => {
                    const nombre = row.getAttribute('data-nombre') || '';
                    const email = row.getAttribute('data-email') || '';

                    if (nombre.includes(searchTerm) || email.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Filtrar por estado
            function filtrarPorEstado(estado) {
                const rows = document.querySelectorAll('#tablaUsuarios tbody tr');
                const buttons = document.querySelectorAll('.btn-group button');

                // Actualizar botones activos
                buttons.forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');

                rows.forEach(row => {
                    const estadoUsuario = row.getAttribute('data-estado');

                    if (estado === 'todos' || estadoUsuario === estado) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Auto-ocultar alertas después de 5 segundos
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const alerts = document.querySelectorAll('.alert-dismissible');
                    alerts.forEach(alert => {
                        const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000);
            });
        </script>
    @endpush
@endsection
