@extends('layouts.PlantillaCRUD')

@section('styles')
    <style>
        /* Estilos CSS que ya tenías */
        .card-header.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        .card.shadow-lg {
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2) !important;
        }
        .form-label.text-muted {
            color: #667eea !important;
        }
        .border-bottom {
            border-bottom-color: rgba(102, 126, 234, 0.2) !important;
        }
        /* Color para el botón de Volver */
        .btn-secondary {
            background: #667eea !important;
            border-color: #667eea !important;
            color: white !important;
        }
        .btn-secondary:hover {
            background: #5568d3 !important;
            border-color: #5568d3 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4) !important;
        }
        .card-footer.bg-light {
            background: #f7fafc !important;
        }
    </style>
@endsection

@section('contenido')
    <div class="container mt-5">

        {{-- Mensaje de éxito/error al volver de otra acción --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white p-4 rounded-top">
                <h3 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i> Detalles Completos del Usuario
                </h3>
            </div>
            <div class="card-body p-5">

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Nombre Completo</label>
                        <p class="fs-5 border-bottom pb-1">{{ $usuario->nombre_completo }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Correo Electrónico</label>
                        <p class="fs-5 border-bottom pb-1">{{ $usuario->email }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">DNI</label>
                        <p class="fs-5 border-bottom pb-1">{{ $usuario->dni }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Teléfono</label>
                        <p class="fs-5 border-bottom pb-1">{{ $usuario->telefono ?? 'N/A' }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Fecha de Registro</label>
                        <p class="fs-5 border-bottom pb-1">
                            {{-- Manejo seguro de la fecha --}}
                            @if($usuario->created_at)
                                {{ $usuario->created_at->format('d/m/Y H:i:s') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted">Última Actualización</label>
                        <p class="fs-5 border-bottom pb-1">
                            {{-- Manejo seguro de la fecha --}}
                            @if($usuario->updated_at)
                                {{ $usuario->updated_at->format('d/m/Y H:i:s') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

            </div>

            {{-- SECCIÓN CORREGIDA: Solo botón Volver --}}
            <div class="card-footer bg-light border-0 text-end p-4">
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                </a>
            </div>
            {{-- FIN DE SECCIÓN CORREGIDA --}}

        </div>
    </div>
@endsection
