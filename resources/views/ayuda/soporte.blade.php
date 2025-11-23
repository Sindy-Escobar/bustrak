@extends('layouts.layoutuser')

@section('contenido')
    <style>
        /* ===== CONTENEDOR PRINCIPAL ===== */
        .soporte-wrapper {
            min-height: calc(100vh - 56px);
            background: #f5f7fa;
            padding: 40px 20px;
        }

        .container-soporte {
            max-width: 900px;
            margin: 0 auto;
        }

        /* ===== HEADER DE PÁGINA ===== */
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 0;
            color: #1976d2;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .page-title i {
            font-size: 2rem;
            color: #1976d2;
        }

        /* ===== CARD DE FORMULARIO ===== */
        .card-soporte {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-body-soporte {
            padding: 40px;
        }

        /* ===== MENSAJE INFORMATIVO ===== */
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #1976d2;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1565c0;
        }

        .info-box i {
            font-size: 1.3rem;
            color: #1976d2;
            flex-shrink: 0;
        }

        .info-box p {
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* ===== FORMULARIO ===== */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .form-label i {
            color: #1976d2;
            width: 18px;
            text-align: center;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e7ef;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control::placeholder {
            color: #95a5a6;
        }

        .form-control:focus {
            outline: none;
            border-color: #1976d2;
            background: #f8fbff;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        .form-control.is-invalid {
            border-color: #e74c3c;
        }

        .form-control.is-invalid:focus {
            border-color: #e74c3c;
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 140px;
            line-height: 1.6;
        }

        .form-text {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-text i {
            font-size: 0.75rem;
        }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .invalid-feedback::before {
            content: "\f06a";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
        }

        /* ===== BOTÓN DE ENVÍO ===== */
        .btn-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-submit {
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            background: #1976d2;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.25);
        }

        .btn-submit:hover {
            background: #1565c0;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(25, 118, 210, 0.35);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit i {
            font-size: 1.1rem;
        }

        /* ===== ALERTAS ===== */
        .alert-custom {
            border: none;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 0.95rem;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-success i {
            color: #28a745;
            font-size: 1.2rem;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-danger i {
            color: #dc3545;
            font-size: 1.2rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .soporte-wrapper {
                padding: 20px 15px;
            }

            .page-header {
                padding: 20px;
                margin-bottom: 20px;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .page-title i {
                font-size: 1.6rem;
            }

            .card-body-soporte {
                padding: 25px 20px;
            }

            .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.3rem;
                gap: 10px;
            }

            .form-control {
                padding: 12px 14px;
                font-size: 0.9rem;
            }

            .btn-submit {
                padding: 12px 24px;
                font-size: 0.95rem;
            }
        }
    </style>

    <div class="soporte-wrapper">
        <div class="container-soporte">
            <!-- Header de Página -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-headset"></i>
                    Ayuda y Soporte
                </h1>
            </div>

            <!-- Card de Formulario -->
            <div class="card-soporte">
                <div class="card-body-soporte">
                    <!-- Mensaje Informativo -->
                    <div class="info-box">
                        <i class="fas fa-info-circle"></i>
                        <p>Completa el formulario y nos pondremos en contacto contigo lo antes posible.</p>
                    </div>

                    <form id="soporteForm" method="POST" action="{{ route('soporte.enviar') }}">
                        @csrf

                        {{-- Mensaje de éxito --}}
                        @if(session('success'))
                            <div class="alert-custom alert-success">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        {{-- Mensaje de error --}}
                        @if(session('error'))
                            <div class="alert-custom alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-user"></i> Nombre Completo
                            </label>
                            <input
                                type="text"
                                class="form-control @error('nombre') is-invalid @enderror"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre', auth()->user()->name ?? '') }}"
                                placeholder="Tu nombre completo"
                                required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="correo" class="form-label">
                                <i class="fas fa-envelope"></i> Correo Electrónico
                            </label>
                            <input
                                type="email"
                                class="form-control @error('correo') is-invalid @enderror"
                                id="correo"
                                name="correo"
                                value="{{ old('correo', auth()->user()->email ?? '') }}"
                                placeholder="tu@email.com"
                                required>
                            @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="asunto" class="form-label">
                                <i class="fas fa-tag"></i> Asunto
                            </label>
                            <input
                                type="text"
                                class="form-control @error('asunto') is-invalid @enderror"
                                id="asunto"
                                name="asunto"
                                value="{{ old('asunto') }}"
                                placeholder="Asunto de tu consulta"
                                required>
                            @error('asunto')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="mensaje" class="form-label">
                                <i class="fas fa-comments"></i> Mensaje
                            </label>
                            <textarea
                                class="form-control @error('mensaje') is-invalid @enderror"
                                id="mensaje"
                                name="mensaje"
                                placeholder="Cuéntanos tu problema o duda en detalle..."
                                maxlength="1000"
                                required>{{ old('mensaje') }}</textarea>
                            <small class="form-text">
                                <i class="fas fa-info-circle"></i> <span id="char-counter">Máximo 1000 caracteres</span>
                            </small>
                            @error('mensaje')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="btn-wrapper">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane"></i> Enviar Consulta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Contador de caracteres
        document.addEventListener('DOMContentLoaded', function() {
            const mensajeTextarea = document.getElementById('mensaje');
            const charCounter = document.getElementById('char-counter');

            if (mensajeTextarea && charCounter) {
                mensajeTextarea.addEventListener('input', function() {
                    const length = this.value.length;
                    const maxLength = this.getAttribute('maxlength');
                    charCounter.textContent = `${length}/${maxLength} caracteres`;
                });
            }
        });
    </script>
@endsection
