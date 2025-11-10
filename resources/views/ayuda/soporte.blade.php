@extends('layouts.usuario')

@section('title', 'Ayuda y Soporte - BusTrak')

@push('styles')
    <style>
        /* Estilos específicos para la página de soporte */
        .content-area {
            background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%) !important;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container-soporte {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            border: none;
            padding: 24px 20px;
            text-align: center;
            color: white;
        }

        .card-header i {
            font-size: 2.2rem;
            margin-bottom: 10px;
            display: block;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .card-header h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .card-body {
            padding: 18px;
            background: white;
        }

        .subtitle {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 12px;
            line-height: 1.3;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.8rem;
            color: #333;
            margin-bottom: 3px;
        }

        label i {
            color: #1976d2;
            margin-right: 6px;
            width: 14px;
            text-align: center;
            font-size: 0.85rem;
        }

        .form-control {
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            height: auto;
        }

        .form-control::placeholder {
            color: #aaa;
            font-size: 0.82rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
        }

        .form-text {
            font-size: 0.7rem;
            color: #999;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .alert {
            border: none;
            border-radius: 8px;
            margin-bottom: 14px;
            font-size: 0.85rem;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-wrapper {
            display: flex;
            gap: 10px;
            margin-top: 14px;
        }

        .btn-primary {
            flex: 1;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-weight: 600;
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.3);
            transition: all 0.3s ease;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-primary i {
            margin-right: 6px;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .container-soporte {
                padding: 10px;
            }
        }
    </style>
@endpush

@section('contenido')
    <div class="container-soporte">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-headset"></i>
                <h3>Ayuda y Soporte</h3>
            </div>

            <div class="card-body">
                <p class="subtitle">
                    <i class="fas fa-info-circle"></i>
                    Completa el formulario y nos pondremos en contacto contigo lo antes posible.
                </p>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('soporte.enviar') }}" method="POST" id="soporteForm">
                    @csrf

                    <div class="form-group">
                        <label for="nombre">
                            <i class="fas fa-user"></i> Nombre Completo
                        </label>
                        <input
                            type="text"
                            class="form-control @error('nombre') is-invalid @enderror"
                            id="nombre"
                            name="nombre"
                            placeholder="Tu nombre completo"
                            value="{{ old('nombre', Auth::user()->nombre_completo ?? '') }}"
                            required>
                        @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="correo">
                            <i class="fas fa-envelope"></i> Correo Electrónico
                        </label>
                        <input
                            type="email"
                            class="form-control @error('correo') is-invalid @enderror"
                            id="correo"
                            name="correo"
                            placeholder="tu@email.com"
                            value="{{ old('correo', Auth::user()->email ?? '') }}"
                            required>
                        @error('correo')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="asunto">
                            <i class="fas fa-tag"></i> Asunto
                        </label>
                        <input
                            type="text"
                            class="form-control @error('asunto') is-invalid @enderror"
                            id="asunto"
                            name="asunto"
                            placeholder="Asunto de tu consulta"
                            value="{{ old('asunto') }}"
                            required>
                        @error('asunto')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="mensaje">
                            <i class="fas fa-comments"></i> Mensaje
                        </label>
                        <textarea
                            class="form-control @error('mensaje') is-invalid @enderror"
                            id="mensaje"
                            name="mensaje"
                            placeholder="Cuéntanos tu problema o duda..."
                            maxlength="1000"
                            rows="4"
                            required>{{ old('mensaje') }}</textarea>
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i>
                            <span id="charCount">0</span>/1000 caracteres
                        </small>
                        @error('mensaje')
                        <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="btn-wrapper">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contador de caracteres
            const mensaje = document.getElementById('mensaje');
            const charCount = document.getElementById('charCount');

            if (mensaje && charCount) {
                // Inicializar contador
                charCount.textContent = mensaje.value.length;

                mensaje.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
            }

            // Manejo del formulario
            const form = document.getElementById('soporteForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
                    }
                });
            }
        });
    </script>
@endpush
