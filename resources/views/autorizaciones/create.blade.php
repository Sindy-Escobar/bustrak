@extends('layouts.layoutuser')

@section('title', 'Autorización para Menor de Edad')

@section('contenido')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="fas fa-child me-2"></i>
                    Autorización de Viaje para Menor de Edad
                </h4>
            </div>

            <div class="card-body">
                {{-- Alerta informativa --}}
                <div class="alert alert-info d-flex align-items-start">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h6 class="alert-heading">Requisitos para menores de edad</h6>
                        <ul class="mb-0">
                            <li>Los menores de 18 años requieren autorización de un tutor mayor de edad</li>
                            <li>El tutor debe estar presente en la terminal al momento del abordaje</li>
                            <li>Se generará un código QR de autorización</li>
                        </ul>
                    </div>
                </div>

                {{-- Información del viaje --}}
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Información del Viaje</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Origen:</strong> {{ $reserva->viaje->origen->nombre ?? '-' }}</p>
                                <p class="mb-1"><strong>Destino:</strong> {{ $reserva->viaje->destino->nombre ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong>Asiento:</strong> #{{ $reserva->asiento->numero_asiento ?? 'S/N' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Formulario --}}
                <form action="{{ route('autorizacion.store', $reserva->id) }}" method="POST">
                    @csrf

                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-child me-2"></i>Datos del Menor
                    </h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">DNI del Menor *</label>
                            <input type="text" name="menor_dni" class="form-control @error('menor_dni') is-invalid @enderror"
                                   value="{{ old('menor_dni') }}" required placeholder="Ej: 12345678">
                            @error('menor_dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de Nacimiento del Menor *</label>
                            <input type="date" name="menor_fecha_nacimiento"
                                   class="form-control @error('menor_fecha_nacimiento') is-invalid @enderror"
                                   value="{{ old('menor_fecha_nacimiento') }}" required
                                   max="{{ date('Y-m-d') }}">
                            @error('menor_fecha_nacimiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3 text-success">
                        <i class="fas fa-user-shield me-2"></i>Datos del Tutor/Responsable
                    </h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre Completo del Tutor *</label>
                            <input type="text" name="tutor_nombre"
                                   class="form-control @error('tutor_nombre') is-invalid @enderror"
                                   value="{{ old('tutor_nombre') }}" required
                                   placeholder="Ej: María González">
                            @error('tutor_nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">DNI del Tutor *</label>
                            <input type="text" name="tutor_dni"
                                   class="form-control @error('tutor_dni') is-invalid @enderror"
                                   value="{{ old('tutor_dni') }}" required
                                   placeholder="Ej: 87654321">
                            @error('tutor_dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email del Tutor *</label>
                            <input type="email" name="tutor_email"
                                   class="form-control @error('tutor_email') is-invalid @enderror"
                                   value="{{ old('tutor_email') }}" required
                                   placeholder="correo@ejemplo.com">
                            @error('tutor_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Se enviará el código de autorización a este correo</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Parentesco *</label>
                            <select name="parentesco" class="form-select @error('parentesco') is-invalid @enderror" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="padre" {{ old('parentesco') == 'padre' ? 'selected' : '' }}>Padre</option>
                                <option value="madre" {{ old('parentesco') == 'madre' ? 'selected' : '' }}>Madre</option>
                                <option value="abuelo" {{ old('parentesco') == 'abuelo' ? 'selected' : '' }}>Abuelo</option>
                                <option value="abuela" {{ old('parentesco') == 'abuela' ? 'selected' : '' }}>Abuela</option>
                                <option value="tio" {{ old('parentesco') == 'tio' ? 'selected' : '' }}>Tío</option>
                                <option value="tia" {{ old('parentesco') == 'tia' ? 'selected' : '' }}>Tía</option>
                                <option value="tutor_legal" {{ old('parentesco') == 'tutor_legal' ? 'selected' : '' }}>Tutor Legal</option>
                            </select>
                            @error('parentesco')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong> El tutor debe presentarse en la terminal con su DNI original al momento del abordaje.
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-check-circle me-2"></i>Generar Autorización
                        </button>
                        <a href="{{ route('cliente.historial') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
