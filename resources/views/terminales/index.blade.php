@extends('layouts.layoutadmin')

@section('content')

    <div class="container mt-4">

        {{-- ✅ Mensajes de Éxito --}}
        @if (session('success'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-lg border-0">
            {{-- 🔹 Encabezado --}}
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h2 class="mb-0 fw-bold">
                    <i class="fas fa-bus me-2"></i> Gestión de terminales
                </h2>
                <a href="{{ route('terminales.create') }}" class="btn btn-light fw-semibold">
                    <i class="fas fa-plus me-1"></i> Nueva terminal
                </a>
            </div>

            {{-- 🔹 Cuerpo de la tarjeta --}}
            <div class="card-body">

                <h5 class="mb-3 text-secondary fw-semibold">
                    <i class="fas fa-list me-2"></i> Listado de terminales
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-primary">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Contacto</th>
                            <th>Horario</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($terminales as $terminal)
                            <tr>
                                <td class="fw-semibold text-primary">{{ $terminal->codigo }}</td>
                                <td>
                                    {{ $terminal->nombre }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $terminal->descripcion ? Str::limit($terminal->descripcion, 30) : 'Sin descripción' }}
                                    </small>
                                </td>
                                <td>
                                    {{ $terminal->ciudad }},
                                    <strong>{{ $terminal->departamento }}</strong>
                                </td>
                                <td>
                                    <i class="fas fa-phone-alt text-success me-1"></i> {{ $terminal->telefono }}<br>
                                    <small>
                                        <i class="fas fa-envelope text-secondary me-1"></i> {{ $terminal->correo }}
                                    </small>
                                </td>
                                <td>
                                    @if ($terminal->horario_apertura && $terminal->horario_cierre)
                                        {{ \Carbon\Carbon::parse($terminal->horario_apertura)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($terminal->horario_cierre)->format('g:i A') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('terminales.show', $terminal) }}"
                                           class="btn btn-outline-info btn-sm" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('terminales.edit', $terminal) }}"
                                           class="btn btn-outline-warning btn-sm" title="Editar Terminal">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-box-open me-2"></i>
                                    No se encontraron terminales registradas.
                                    <a href="{{ route('terminales.create') }}" class="fw-semibold text-primary">¡Crea la primera!</a>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 🔹 Paginación --}}
                @if ($terminales->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $terminales->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div> {{-- Fin card-body --}}
        </div> {{-- Fin card --}}
    </div> {{-- Fin container --}}

@endsection

