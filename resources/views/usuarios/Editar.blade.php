@extends('layouts.PlantillaCRUD')

@section('styles')
    <style>
        .edit-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15);
        }

        .edit-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px 15px 0 0;
            padding: 2rem;
            color: white;
        }

        .edit-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .edit-body {
            padding: 2.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .alert-success-custom {
            background: #f0fdf4;
            border: 2px solid #86efac;
            color: #166534;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success-custom i {
            font-size: 1.25rem;
        }

        .divider {
            border: none;
            border-top: 2px solid #e2e8f0;
            margin: 2rem 0;
        }

        .info-text {
            background: #eff6ff;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 8px;
            color: #1e40af;
            margin-bottom: 1.5rem;
        }

        .info-text i {
            margin-right: 0.5rem;
        }

        .btn-save {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .btn-cancel {
            background: white;
            border: 2px solid #e2e8f0;
            color: #4a5568;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-cancel:hover {
            border-color: #667eea;
            color: #667eea;
            background: #f7fafc;
        }

        .password-section {
            background: #fafafa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1.5rem;
        }

        .password-section h5 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
@endsection

@section('contenido')
    <div class="container mt-5 mb-5">
        @if (session('success'))
            <div class="alert-success-custom">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="edit-card">
            <div class="edit-header">
                <h1>
                    <i class="fas fa-user-edit"></i>
                    Editar Usuario: {{ $usuario->nombre_completo }}
                </h1>
            </div>

            <div class="edit-body">
                <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- NOMBRE COMPLETO -->
                        <div class="col-md-6">
                            <label for="nombre_completo" class="form-label">
                                <i class="fas fa-user me-1"></i> Nombre Completo
                            </label>
                            <input
                                type="text"
                                name="nombre_completo"
                                id="nombre_completo"
                                value="{{ old('nombre_completo', $usuario->nombre_completo) }}"
                                class="form-control @error('nombre_completo') is-invalid @enderror"
                                required
                            >
                            @error('nombre_completo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DNI -->
                        <div class="col-md-6">
                            <label for="dni" class="form-label">
                                <i class="fas fa-id-card me-1"></i> DNI
                            </label>
                            <input
                                type="text"
                                name="dni"
                                id="dni"
                                value="{{ old('dni', $usuario->dni) }}"
                                class="form-control @error('dni') is-invalid @enderror"
                                required
                            >
                            @error('dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i> Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email', $usuario->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TELÉFONO -->
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">
                                <i class="fas fa-phone me-1"></i> Teléfono
                            </label>
                            <input
                                type="text"
                                name="telefono"
                                id="telefono"
                                value="{{ old('telefono', $usuario->telefono) }}"
                                class="form-control @error('telefono') is-invalid @enderror"
                                required
                            >
                            @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- SECCIÓN DE CONTRASEÑA -->
                    <div class="password-section">
                        <h5>
                            <i class="fas fa-key"></i>
                            Cambiar Contraseña (Opcional)
                        </h5>

                        <div class="info-text">
                            <i class="fas fa-info-circle"></i>
                            Solo llena los campos de contraseña si deseas cambiarla.
                        </div>

                        <div class="row g-4">
                            <!-- PASSWORD -->
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> Nueva Contraseña
                                </label>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                >
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- PASSWORD CONFIRMATION -->
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i> Confirmar Nueva Contraseña
                                </label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                >
                            </div>
                        </div>
                    </div>

                    <hr class="divider">

                    <!-- BOTONES -->
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('usuarios.show', $usuario) }}" class="btn-cancel">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


