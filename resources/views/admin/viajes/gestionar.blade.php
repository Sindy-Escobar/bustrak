@extends('layouts.layoutadmin')

@section('title', 'Gestión de Viajes')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Alertas --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-bus me-2"></i>Gestión de Viajes</h4>
                    </div>

                    <div class="card-body">

                        {{-- Sección: Generar Viajes --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2"><i class="fas fa-plus-circle text-success me-2"></i>Generar Viajes</h5>
                            <p class="text-muted">Crea viajes para todas las rutas y servicios.</p>

                            <form action="{{ route('admin.viajes.generar') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="row g-3 align-items-end">

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Días a generar</label>
                                        <select name="dias" class="form-select" required>
                                            <option value="1">1 día</option>
                                            <option value="2" selected>2 días</option>
                                        </select>
                                    </div>
                                    <div class="col-md-auto">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-magic me-2"></i>Generar Viajes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <hr>

                        {{-- Sección: Limpiar Viajes --}}
                        <div>
                            <h5 class="border-bottom pb-2"><i class="fas fa-trash text-danger me-2"></i>Limpiar Viajes Pasados</h5>
                            <p class="text-muted">Elimina viajes con fechas pasadas que no tienen reservas.</p>

                            <form action="{{ route('admin.viajes.limpiar') }}" method="POST" id="formLimpiarViajes">
                                @csrf

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalConfirmarLimpieza">
                                    <i class="fas fa-broom me-2"></i>Limpiar Viajes Pasados
                                </button>
                            </form>

                            {{-- Modal de confirmación propio (reemplaza el confirm() nativo) --}}
                            <div class="modal fade" id="modalConfirmarLimpieza" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-exclamation-triangle me-2"></i>Confirmar eliminación
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de eliminar los viajes pasados sin reservas?</p>
                                            <p class="text-danger mb-0"><strong>Esta acción no se puede deshacer.</strong></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" form="formLimpiarViajes" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i>Sí, eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        {{-- Información --}}
                        <div class="alert alert-info">
                            <ul class="mb-0">
                                <li><strong>Generar Viajes:</strong> Crea viajes para TODAS las rutas con TODOS los servicios (Urbano, Express, Premium, etc.) en los horarios 6am, 12pm y 6pm.</li>
                                <li><strong>Limpiar:</strong> Elimina solo viajes pasados SIN reservas para mantener la base de datos limpia.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Listado de viajes generados --}}
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>Viajes generados
                        </h4>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-primary fs-6">
                                {{ $viajes->total() }} resultado(s)
                            </span>
                            <a
                                href="{{ route('admin.viajes.exportar-pdf', request()->only(['estado', 'fecha', 'buscar'])) }}"
                                class="btn btn-danger btn-sm"
                                title="Exportar los resultados actuales"
                            >
                                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small">Total registrados</div>
                                    <div class="fs-3 fw-bold">{{ $resumen['total'] }}</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small">Próximos</div>
                                    <div class="fs-3 fw-bold text-success">{{ $resumen['proximos'] }}</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small">Pasados</div>
                                    <div class="fs-3 fw-bold text-secondary">{{ $resumen['pasados'] }}</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small">Activos</div>
                                    <div class="fs-3 fw-bold text-success">{{ $resumen['activos'] }}</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small">Inactivos</div>
                                    <div class="fs-3 fw-bold text-warning">{{ $resumen['inactivos'] }}</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small">Asientos reservados</div>
                                    <div class="fs-3 fw-bold text-primary">{{ $resumen['asientos_reservados'] }}</div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="text-muted small">Cancelados excluidos</div>
                                    <div class="fs-3 fw-bold text-danger">{{ $resumen['asientos_cancelados_excluidos'] }}</div>
                                </div>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('admin.viajes.gestionar') }}" class="row g-3 align-items-end mb-4">
                            <div class="col-lg-3 col-md-6">
                                <label for="estado" class="form-label fw-semibold">Estado</label>
                                <select id="estado" name="estado" class="form-select">
                                    <option value="proximos" @selected($estado === 'proximos')>Próximos</option>
                                    <option value="pasados" @selected($estado === 'pasados')>Pasados</option>
                                    <option value="todos" @selected($estado === 'todos')>Todos</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label for="fecha" class="form-label fw-semibold">Fecha de salida</label>
                                <input id="fecha" type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                            </div>
                            <div class="col-lg-4 col-md-8">
                                <label for="buscar" class="form-label fw-semibold">Ruta, número de bus o placa</label>
                                <input
                                    id="buscar"
                                    type="search"
                                    name="buscar"
                                    class="form-control"
                                    value="{{ $buscar }}"
                                    placeholder="Ej. Tegucigalpa, BUS-01..."
                                >
                            </div>
                            <div class="col-lg-2 col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-search me-1"></i>Filtrar
                                </button>
                                <a href="{{ route('admin.viajes.gestionar') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                                    <i class="fas fa-eraser"></i>
                                </a>
                            </div>
                        </form>

                        @if($viajes->isEmpty())
                            <div class="text-center py-5 border rounded bg-light">
                                <i class="fas fa-route fa-3x text-muted mb-3"></i>
                                <h5>No se encontraron viajes</h5>
                                <p class="text-muted mb-0">
                                    @if(request()->filled('fecha') || request()->filled('buscar') || $estado !== 'proximos')
                                        Cambia o limpia los filtros para ampliar la búsqueda.
                                    @else
                                        Genera viajes con el formulario superior para visualizarlos aquí.
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Ruta</th>
                                            <th>Salida</th>
                                            <th>Bus / Servicio</th>
                                            <th>Conductor</th>
                                            <th>Ocupación</th>
                                            <th>Estado</th>
                                            <th>Disponibilidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($viajes as $viaje)
                                            @php
                                                $esProximo = $viaje->fecha_hora_salida->isFuture();
                                                $capacidad = max((int) $viaje->asientos_totales, 0);
                                                $reservados = (int) ($viaje->asientos_reservados ?? 0);
                                                $canceladosExcluidos = (int) ($viaje->asientos_cancelados_excluidos ?? 0);
                                            @endphp
                                            <tr>
                                                <td class="fw-semibold">#{{ $viaje->id }}</td>
                                                <td>
                                                    <div class="fw-semibold">
                                                        {{ $viaje->origen?->nombre ?? 'Origen no disponible' }}
                                                        <i class="fas fa-arrow-right mx-1 text-muted"></i>
                                                        {{ $viaje->destino?->nombre ?? 'Destino no disponible' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>{{ $viaje->fecha_hora_salida->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ $viaje->fecha_hora_salida->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ $viaje->bus?->numero_bus ?? 'Sin bus' }}</div>
                                                    <small class="text-muted">
                                                        {{ $viaje->bus?->placa ?? 'Sin placa' }}
                                                        @if($viaje->bus?->tipoServicio)
                                                            · {{ $viaje->bus->tipoServicio->nombre }}
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    {{ $viaje->empleado
                                                        ? trim($viaje->empleado->nombre.' '.$viaje->empleado->apellido)
                                                        : 'Sin asignar' }}
                                                </td>
                                                <td>
                                                    <span class="fw-semibold">{{ $reservados }}</span>
                                                    <span class="text-muted">/ {{ $capacidad }}</span>
                                                    @if($canceladosExcluidos > 0)
                                                        <small class="d-block text-danger">
                                                            {{ $canceladosExcluidos }} cancelado(s) excluido(s)
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $esProximo ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $esProximo ? 'Próximo' : 'Finalizado' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $viaje->activo ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $viaje->activo ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                    <form action="{{ route('admin.viajes.cambiar-estado', $viaje) }}" method="POST" class="mt-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="activo" value="{{ $viaje->activo ? 0 : 1 }}">
                                                        <button type="submit" class="btn btn-sm {{ $viaje->activo ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                            {{ $viaje->activo ? 'Desactivar' : 'Activar' }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                                <small class="text-muted">
                                    Mostrando {{ $viajes->firstItem() }}–{{ $viajes->lastItem() }}
                                    de {{ $viajes->total() }} viajes
                                </small>
                                {{ $viajes->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
