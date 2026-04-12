@extends('layouts.layoutadmin')

@section('title', 'Gestión de Solicitudes de Constancias y Consultas ')

@section('content')
    <div class="d-flex justify-content-center">
        <div style="width: 100%; max-width: 1200px;">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Gestión de Constancias, Consultas y Solicitudes
                    </h4>
                    <a href="{{ route('solicitudes.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i> Nueva Solicitud
                    </a>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form class="mb-4" id="searchForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Buscar por nombre:</label>
                                <input type="text" id="searchInput" class="form-control"
                                       placeholder="Ingrese nombre...">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-primary" id="searchBtn">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>

                    <div>
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Solicitante</th>
                                <th>Email</th>
                                <th>DNI</th>
                                <th>Motivo</th>
                                <th>F. Entrega</th>
                                <th>Estado</th>
                                <th>F. Procesamiento</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($solicitudes->reverse() as $solicitud)
                                <tr>
                                    <td class="fw-bold">{{ $solicitud->id }}</td>
                                    <td>{{ $solicitud->nombre }}</td>
                                    <td>
                                        <small class="text-muted">{{ $solicitud->user->email ?? '-' }}</small>
                                    </td>
                                    <td>{{ $solicitud->dni }}</td>
                                    <td>
                                        <small>
                                            {{ \Illuminate\Support\Str::limit($solicitud->motivo, 15) }}
                                        </small>
                                    </td>
                                    <td>{{ $solicitud->fecha_entrega->format('d/m/Y') }}</td>
                                    <td>
                                        @switch($solicitud->estado)
                                            @case('pendiente')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock"></i> Pendiente
                                                </span>
                                                @break
                                            @case('procesada')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Procesada
                                                </span>
                                                @break
                                            @case('rechazada')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times"></i> Rechazada
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $solicitud->fecha_procesamiento
                                                ? $solicitud->fecha_procesamiento->format('d/m/Y H:i')
                                                : '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($solicitud->estado === 'pendiente')
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('solicitudes.procesar', $solicitud) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="estado" value="procesada">
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                            title="Aprobar"
                                                            onclick="return confirm('¿Aprobar esta solicitud? Se enviará notificación al empleado.')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('solicitudes.procesar', $solicitud) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="estado" value="rechazada">
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Rechazar"
                                                            onclick="return confirm('¿Rechazar esta solicitud?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-muted text-center d-block">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No hay solicitudes registradas</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $solicitudes->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

            {{-- SOLICITUDES DE EMPLEO --}}
            <div class="card shadow-lg border-0 mt-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i>
                        Solicitudes de Empleo
                    </h4>
                </div>
                <div class="card-body">
                    @if($solicitudesEmpleo->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Puesto Deseado</th>
                                    <th>Contacto</th>
                                    <th>Estado</th>
                                    <th>Fecha Envío</th>
                                    <th>CV</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($solicitudesEmpleo as $key => $solicitud)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $solicitud->nombre_completo }}</td>
                                        <td>{{ $solicitud->puesto_deseado }}</td>
                                        <td>{{ $solicitud->contacto }}</td>
                                        <td>
                                            @switch($solicitud->estado)
                                                @case('Pendiente')
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                                    @break
                                                @case('Revisada')
                                                    <span class="badge bg-info">Revisada</span>
                                                    @break
                                                @case('Aceptada')
                                                    <span class="badge bg-success">Aceptada</span>
                                                    @break
                                                @case('Rechazada')
                                                    <span class="badge bg-danger">Rechazada</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($solicitud->cv)
                                                <a href="{{ $solicitud->cv }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-download"></i> Ver CV
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($solicitud->estado === 'Pendiente' || $solicitud->estado === null)
                                                <div class="btn-group">
                                                    <form action="{{ route('solicitud.empleo.aceptar', $solicitud->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                                onclick="return confirm('¿Aceptar esta solicitud?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('solicitud.empleo.rechazar', $solicitud->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('¿Rechazar esta solicitud?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No hay solicitudes de empleo registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- CONSULTAS --}}
        <div class="card shadow-lg border-0 mt-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">
                    <i class="fas fa-headset me-2"></i>
                    Consultas de Usuarios
                </h4>
            </div>
            <div class="card-body">
                @if($consultas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Asunto</th>
                                <th>Mensaje</th>
                                <th>Fecha Envío</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($consultas as $key => $consulta)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $consulta->nombre_completo }}</td>
                                    <td>{{ $consulta->correo }}</td>
                                    <td>{{ $consulta->asunto }}</td>
                                    <td>
                                        <small>{{ \Illuminate\Support\Str::limit($consulta->mensaje, 50) }}</small>
                                    </td>
                                    <td>{{ $consulta->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted">No hay consultas registradas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .table {
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.85rem;
        }
        .btn-group {
            display: flex;
            gap: 0.25rem;
        }
    </style>

    <script>
        function removeTildes(text) {
            return text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const tableRows = document.querySelectorAll('tbody tr');

            function filterTable(searchTerm) {
                const cleanSearchTerm = removeTildes(searchTerm).toLowerCase().trim();
                if (cleanSearchTerm === '') {
                    tableRows.forEach(row => { row.style.display = ''; });
                } else {
                    tableRows.forEach(row => {
                        const nombreCell = row.cells[1] ? row.cells[1].textContent : '';
                        const cleanNombre = removeTildes(nombreCell).toLowerCase();
                        if (cleanNombre.includes(cleanSearchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            }

            searchInput.addEventListener('input', function() {
                filterTable(this.value);
            });

            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                filterTable(searchInput.value);
            });
        });
    </script>
@endsection
