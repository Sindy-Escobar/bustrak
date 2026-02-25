@extends('layouts.layoutadmin')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-lg border-0 rounded-0 w-100">

            <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#ffffff;">
                <h2 style="margin:0; color:#1e63b8; font-weight:600; font-size:2rem;">
                    <i class="fas fa-concierge-bell me-2"></i>Servicios de: {{ $terminal->nombre }}
                </h2>
                <a href="{{ route('terminales.index') }}" class="btn btn-secondary fw-semibold">
                    <i class="fas fa-arrow-left me-1"></i>Volver a Terminales
                </a>
            </div>

            <div class="card-body" style="min-height: 70vh;">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-circle-check me-2"></i>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="mb-3">
                    <button class="btn btn-primary" type="button" onclick="toggleFormulario()">
                        <i class="fas fa-plus me-2"></i>Agregar Nuevo Servicio
                    </button>
                </div>

                    <div class="collapse mb-4" id="formAgregarServicio" style="display:none !important;">
                    <div class="card border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h6 class="mb-0 text-primary"><i class="fas fa-plus me-2"></i>Nuevo Servicio</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('terminales.servicios.store', $terminal) }}" method="POST">
                                @csrf
                                <input type="hidden" name="icono" value="fas fa-concierge-bell">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold">Nombre del Servicio</label>
                                        <input type="text" name="nombre"
                                               class="form-control @error('nombre') is-invalid @enderror"
                                               placeholder="Ej: Cafetería, Sanitarios..."
                                               maxlength="100" required value="{{ old('nombre') }}">
                                        @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold">Descripción (opcional)</label>
                                        <input type="text" name="descripcion"
                                               class="form-control @error('descripcion') is-invalid @enderror"
                                               placeholder="Breve descripción del servicio..."
                                               maxlength="500" value="{{ old('descripcion') }}">
                                        @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-plus me-1"></i>Agregar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-responsive w-100">
                    <table class="table table-hover table-bordered w-100 align-middle text-center">
                        <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th><i class="fas fa-icons me-1"></i>Icono</th>
                            <th><i class="fas fa-concierge-bell me-1"></i>Nombre</th>
                            <th><i class="fas fa-info-circle me-1"></i>Descripción</th>
                            <th><i class="fas fa-cog me-1"></i>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($servicios as $servicio)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><i class="{{ $servicio->icono }} fa-lg text-primary"></i></td>
                                <td><strong>{{ $servicio->nombre }}</strong></td>
                                <td>{{ $servicio->descripcion ?? 'Sin descripción' }}</td>
                                <td>
                                    <form action="{{ route('terminales.servicios.destroy', [$terminal, $servicio]) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este servicio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash me-1"></i>Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                                    No hay servicios disponibles en esta estación.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $servicios->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Forzar que el collapse esté cerrado al entrar
            const collapseEl = document.getElementById('formAgregarServicio');
            collapseEl.classList.remove('show');

            @if($errors->any())
            // Solo abrir si hay errores de validación
            new bootstrap.Collapse(collapseEl, { show: true });
            @endif
        });
    </script>
    <script>
        function toggleFormulario() {
            const form = document.getElementById('formAgregarServicio');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('formAgregarServicio').style.display = 'none';
        });
    </script>
@endsection
