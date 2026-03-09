@extends('layouts.layoutuser')

@section('contenido')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11"> {{--  Cambiado de col-lg-8 a col-lg-11 (más ancho) --}}

                {{-- Alerta de éxito --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header" style="background-color: #1e3a8a; color: white;"> {{--  Azul oscuro --}}
                        <h4 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Reportar Incidente
                        </h4>
                    </div>

                    <div class="card-body">

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>¿Tuviste algún problema durante tu viaje?</strong>
                            <p class="mb-0 mt-2 small">Cuéntanos qué sucedió para que podamos mejorar nuestro servicio.</p>
                        </div>

                        <form action="{{ route('incidentes.store') }}" method="POST" id="formIncidente">
                            @csrf

                            {{-- Seleccionar viaje --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-bus me-2"></i>¿A qué viaje se refiere? (Opcional)
                                </label>
                                <select name="reserva_id" class="form-select">
                                    <option value="">-- No relacionado a un viaje específico --</option>
                                    @foreach($reservas as $reserva)
                                        <option value="{{ $reserva->id }}">
                                            {{ $reserva->viaje->origen->nombre ?? 'N/A' }} → {{ $reserva->viaje->destino->nombre ?? 'N/A' }}
                                            | {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y') }}
                                            | Código: {{ $reserva->codigo_reserva }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Seleccione el viaje donde ocurrió el problema</small>
                            </div>

                            {{-- Tipo de incidente --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-list me-2 text-danger"></i>¿Qué tipo de problema fue? *
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-check border rounded p-3 h-100 {{ old('tipo_incidente') == 'retraso' ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                                            <input class="form-check-input" type="radio" name="tipo_incidente" value="retraso" id="retraso" {{ old('tipo_incidente') == 'retraso' ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="retraso">
                                                <i class="fas fa-clock text-info me-2"></i><strong>Retraso</strong>
                                                <small class="text-muted d-block">El bus llegó tarde o se retrasó en ruta</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check border rounded p-3 h-100 {{ old('tipo_incidente') == 'mal_servicio' ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                                            <input class="form-check-input" type="radio" name="tipo_incidente" value="mal_servicio" id="mal_servicio" {{ old('tipo_incidente') == 'mal_servicio' ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="mal_servicio">
                                                <i class="fas fa-frown text-danger me-2"></i><strong>Mal Servicio</strong>
                                                <small class="text-muted d-block">Atención deficiente del personal</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check border rounded p-3 h-100 {{ old('tipo_incidente') == 'bus_sucio' ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                                            <input class="form-check-input" type="radio" name="tipo_incidente" value="bus_sucio" id="bus_sucio" {{ old('tipo_incidente') == 'bus_sucio' ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="bus_sucio">
                                                <i class="fas fa-broom text-warning me-2"></i><strong>Bus Sucio</strong>
                                                <small class="text-muted d-block">Falta de limpieza o higiene</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check border rounded p-3 h-100 {{ old('tipo_incidente') == 'conductor_grosero' ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                                            <input class="form-check-input" type="radio" name="tipo_incidente" value="conductor_grosero" id="conductor_grosero" {{ old('tipo_incidente') == 'conductor_grosero' ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="conductor_grosero">
                                                <i class="fas fa-angry text-danger me-2"></i><strong>Conductor Grosero</strong>
                                                <small class="text-muted d-block">Trato inadecuado del conductor</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check border rounded p-3 h-100 {{ old('tipo_incidente') == 'no_abordaje' ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                                            <input class="form-check-input" type="radio" name="tipo_incidente" value="no_abordaje" id="no_abordaje" {{ old('tipo_incidente') == 'no_abordaje' ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="no_abordaje">
                                                <i class="fas fa-ban text-dark me-2"></i><strong>No pude abordar</strong>
                                                <small class="text-muted d-block">El bus no me recogió o no respetó mi reserva</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check border rounded p-3 h-100 {{ old('tipo_incidente') == 'otro' ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                                            <input class="form-check-input" type="radio" name="tipo_incidente" value="otro" id="otro" {{ old('tipo_incidente') == 'otro' ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="otro">
                                                <i class="fas fa-ellipsis-h text-secondary me-2"></i><strong>Otro</strong>
                                                <small class="text-muted d-block">Otro tipo de problema</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('tipo_incidente')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-2 text-danger"></i>Cuéntanos qué sucedió *
                                </label>
                                <textarea name="descripcion"
                                          class="form-control @error('descripcion') is-invalid @enderror"
                                          rows="6"
                                          placeholder="Por favor describe detalladamente lo ocurrido. Entre más detalles nos proporciones, mejor podremos ayudarte y mejorar nuestro servicio."
                                          required>{{ old('descripcion') }}</textarea>
                                <small class="text-muted">Mínimo 20 caracteres, máximo 1000</small>
                                @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Información adicional --}}
                            <div class="alert alert-light border">
                                <i class="fas fa-shield-alt me-2 text-success"></i>
                                <small><strong>Tu reporte es confidencial</strong> y será revisado por nuestro equipo para mejorar el servicio.</small>
                            </div>

                            {{-- Botones --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-lg text-white fw-bold" style="background-color: #1e3a8a;"> {{-- ✅ Azul oscuro --}}
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Reporte
                                </button>
                                <a href="{{ route('cliente.historial') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Volver a Mis Viajes
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script de validación --}}
    <script>
        document.getElementById('formIncidente').addEventListener('submit', function(e) {
            const tipo = document.querySelector('input[name="tipo_incidente"]:checked');
            const descripcion = document.querySelector('[name="descripcion"]').value;

            if (!tipo) {
                e.preventDefault();
                alert('Por favor selecciona el tipo de incidente');
                return false;
            }

            if (descripcion.length < 20) {
                e.preventDefault();
                alert('Por favor describe con más detalle lo ocurrido (mínimo 20 caracteres)');
                return false;
            }

            if (descripcion.length > 1000) {
                e.preventDefault();
                alert('La descripción es demasiado larga (máximo 1000 caracteres)');
                return false;
            }

            return true;
        });
    </script>
@endsection
