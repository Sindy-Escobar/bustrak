@extends('layouts.layoutuser')

@section('title', 'Registro de Usuario')

@section('contenido')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h2 class="mb-0" style="color:#1e63b8; font-weight:600; font-size:1.8rem;">
                    Registro de Usuario
                </h2>
                <p class="mt-2 mb-0">Completa el formulario para crear tu cuenta</p>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00b7ff" class="bi bi-check-circle me-2" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM7 11l-3-3 1-1 2 2 4-4 1 1-5 5z"/>
                        </svg>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ff4d4f" class="bi bi-exclamation-triangle me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1 1 0 0 1 1.036 0l6.857 3.95A1 1 0 0 1 16 6.382v7.236a1 1 0 0 1-.525.894l-6.857 3.95a1 1 0 0 1-1.036 0L1.525 14.512A1 1 0 0 1 1 13.618V6.382a1 1 0 0 1 .525-.894l6.857-3.95zM8 5c-.535 0-.954.462-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 5zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                        </svg>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ url('/registro') }}" class="needs-validation" novalidate id="registroForm">
                    @csrf

                    <!-- Nombre Completo -->
                    <div class="mb-3">
                        <label class="form-label">Nombre Completo *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00b7ff" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="nombre_completo"
                                id="nombre_completo"
                                class="form-control @error('nombre_completo') is-invalid @enderror"
                                value="{{ old('nombre_completo') }}"
                                pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+"
                                minlength="3"
                                maxlength="100"
                                required
                            >
                            <div class="invalid-feedback">Por favor ingresa tu nombre completo (solo letras, mínimo 3 caracteres).</div>
                            @error('nombre_completo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- DNI -->
                    <div class="mb-3">
                        <label class="form-label">DNI *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00b7ff" class="bi bi-card-text" viewBox="0 0 16 16">
                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                    <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="dni"
                                id="dni"
                                class="form-control @error('dni') is-invalid @enderror"
                                value="{{ old('dni') }}"
                                pattern="[0-9]{13}"
                                maxlength="13"
                                title="El DNI debe contener exactamente 13 dígitos"
                                required
                            >
                            <div class="invalid-feedback">Por favor ingresa un DNI válido (13 dígitos numéricos).</div>
                            @error('dni')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00b7ff" class="bi bi-envelope" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217l-8 4.8-8-4.8V4zm0 1.383V12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5.383l-8 4.8-8-4.8z"/>
                                </svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                maxlength="100"
                                required
                            >
                            <div class="invalid-feedback">Por favor ingresa un correo electrónico válido.</div>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="mb-3">
                        <label class="form-label">Teléfono *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00b7ff" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547c.52-.13.971-.014 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611L10.74 15c-.838.838-2.32-.288-5.322-3.322C2.083 8.36 1.055 6.88 1.879 6.052l.836-.836c.731-.73 1.956-.653 2.61.163l1.944 2.158z"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="telefono"
                                id="telefono"
                                class="form-control @error('telefono') is-invalid @enderror"
                                value="{{ old('telefono') }}"
                                pattern="[0-9]{8}"
                                maxlength="8"
                                title="El teléfono debe contener 8 dígitos"
                                required
                            >
                            <div class="invalid-feedback">Por favor ingresa un teléfono válido (8 dígitos numéricos).</div>
                            @error('telefono')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label class="form-label">Contraseña *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00b7ff" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                            </span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                minlength="8"
                                required
                            >
                            <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Mínimo 8 caracteres</small>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="mb-3">
                        <label class="form-label">Confirmar Contraseña *</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#00b7ff" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                            </span>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                minlength="8"
                                required
                            >
                            <div class="invalid-feedback">Las contraseñas deben coincidir.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ffffff" class="bi bi-person-check-fill me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            </svg>
                            Registrarse
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            ¿Ya tienes cuenta?
                            <a href="{{ url('/login') }}" class="text-decoration-none" style="color:#00b7ff;">Inicia sesión aquí</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validación de formulario Bootstrap
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()

        // Permitir solo letras en Nombre Completo
        document.getElementById('nombre_completo').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });

        // Permitir solo números en DNI
        document.getElementById('dni').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if(this.value.length > 13) {
                this.value = this.value.slice(0, 13);
            }
        });

        // Permitir solo números en Teléfono
        document.getElementById('telefono').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if(this.value.length > 8) {
                this.value = this.value.slice(0, 8);
            }
        });

        // Validar que las contraseñas coincidan
        document.getElementById('registroForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            if(password !== passwordConfirm) {
                e.preventDefault();
                e.stopPropagation();
                document.getElementById('password_confirmation').setCustomValidity('Las contraseñas no coinciden');
                this.classList.add('was-validated');
            } else {
                document.getElementById('password_confirmation').setCustomValidity('');
            }
        });

        // Limpiar validación personalizada al escribir
        document.getElementById('password_confirmation').addEventListener('input', function() {
            this.setCustomValidity('');
        });
    </script>
@endsection
