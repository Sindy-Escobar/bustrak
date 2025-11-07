@extends('layouts.apps')

@section('title', 'Registrar Empresa')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Fondo sólido azul claro */
        body {
            background-color: #e0e7ff; /* azul muy claro */
        }

        main.container-main {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 180px);
            padding: 40px 20px;
        }

        /* Tarjeta del formulario */
        .form-card {
            background-color: #ffffff; /* blanco sólido */
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.08);
            padding: 2.5rem 3rem;
            width: 100%;
            max-width: 600px;
            position: relative;
        }

        .form-card h2 {
            text-align: center;
            font-weight: 800;
            color: #1e3a8a; /* azul oscuro */
            margin-bottom: 1rem;
        }

        .form-card p {
            text-align: center;
            color: #475569;
            margin-bottom: 2rem;
        }

        .input-group-text {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #1e40af;
            border-right: none;
            border-radius: 8px 0 0 8px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
            border: 1px solid #cbd5e1;
            padding: 12px 14px;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 6px rgba(59,130,246,0.3);
        }

        /* Botón principal con tono sólido */
        .btn-primary {
            background-color: #1e40af !important; /* azul sólido */
            border: none !important;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px 28px;
            transition: all 0.3s ease;
            color: #fff !important;
        }

        .btn-primary:hover {
            background-color: #1d4ed8 !important; /* azul más claro al pasar el mouse */
            box-shadow: 0 6px 16px rgba(37,99,235,0.25);
        }

        /* Alertas */
        .alert-success {
            background-color: #e0f2fe;
            border: none;
            color: #1e40af;
            border-radius: 10px;
        }

        .alert-danger {
            background-color: #fee2e2;
            border: none;
            color: #991b1b;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 2rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="form-card">
        <h2><i class="fas fa-building me-2"></i>Registro de Empresa de Buses</h2>
        <p>Completa los datos para registrar una nueva empresa de transporte</p>

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <form action="{{ route('empresa.form') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nombre de la empresa *</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Propietario *</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        <input type="text" name="propietario" class="form-control" value="{{ old('propietario') }}" required>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Teléfono *</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text"
                               name="telefono"
                               class="form-control"
                               value="{{ old('telefono') }}"
                               pattern="[839][0-9]{7}"
                               title="El número debe comenzar con 8, 3 o 9 y tener 8 dígitos"
                               required>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Dirección *</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar Empresa
                </button>
            </div>
        </form>
    </div>
@endsection

