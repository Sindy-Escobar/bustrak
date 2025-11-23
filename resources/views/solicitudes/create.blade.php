@extends('layouts.layoutadmin')

@section('title', 'Solicitar Constancia de Trabajo')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Solicitud de Constancia de Trabajo
                    </h4>
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('solicitudes.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   id="nombre"
                                   name="nombre"
                                   value="{{ old('nombre') }}"
                                   placeholder="Ingrese el nombre completo"
                                   required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="dni" class="form-label">
                                Número de Identidad (DNI) <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('dni') is-invalid @enderror"
                                   id="dni"
                                   name="dni"
                                   value="{{ old('dni') }}"
                                   placeholder="Ej: 0801-1990-12345"
                                   maxlength="20"
                                   required>
                            @error('dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="motivo" class="form-label">
                                Motivo de la Solicitud <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('motivo') is-invalid @enderror"
                                      id="motivo"
                                      name="motivo"
                                      rows="3"
                                      placeholder="Describa el motivo de la solicitud (mínimo 10 caracteres)"
                                      required>{{ old('motivo') }}</textarea>
                            @error('motivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <span id="motivoCount">0</span>/500 caracteres
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_entrega" class="form-label">
                                Fecha de Entrega Deseada <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('fecha_entrega') is-invalid @enderror"
                                   id="fecha_entrega"
                                   name="fecha_entrega"
                                   value="{{ old('fecha_entrega') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required>
                            @error('fecha_entrega')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">La fecha debe ser posterior a hoy</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Enviar Solicitud
                            </button>
                            <a href="{{ route('solicitudes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('motivo').addEventListener('input', function() {
            document.getElementById('motivoCount').textContent = this.value.length;
        });

        document.getElementById('dni').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9-]/g, '');
        });
    </script>
@endsection
