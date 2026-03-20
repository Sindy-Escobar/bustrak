@extends('layouts.layoutuser')

@section('contenido')
    <div class="container-fluid py-4"> {{--  container-fluid para más ancho --}}

        {{-- Mensajes --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ENCABEZADO --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-11">
                <div class="alert alert-primary border-0 shadow-sm mb-0 py-3"
                     style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-route fa-2x text-white"></i>
                        <div class="text-start">
                            <h4 class="fw-bold text-white mb-1">Reserva tu Viaje</h4>
                            <p class="text-white mb-0" style="opacity: 0.9;">
                                Selecciona el servicio y destino que prefieras para comenzar tu aventura
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{--  BARRA DE PROGRESO --}}
        @include('components.progress-stepper', ['step' => 1])

        <form id="buscarForm" action="{{ route('cliente.reserva.buscar') }}" method="POST" autocomplete="off" novalidate>
            @csrf

            {{-- PASO 1 --}}
            <div class="row justify-content-center mb-2">
                <div class="col-lg-11"> {{--  CAMBIO --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header border-0 bg-white pt-4 pb-3">
                            <h4 class="mb-1" style="color: #3b82f6;">
                                <i class="fas fa-star me-2"></i>Selecciona tu servicio de transporte
                            </h4>
                            <p class="text-muted mb-0 small">Elige el tipo de experiencia que prefieras</p>
                        </div>
                        <div class="card-body p-4">
                            @if(session('tipo_servicio_seleccionado'))
                                <div class="alert alert-success d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fas fa-check-circle me-2"></i>
                                        <strong>Servicio seleccionado:</strong> {{ session('tipo_servicio_seleccionado.nombre') }}
                                    </div>
                                    <a href="{{ route('cliente.seleccion-tipo-servicio') }}"
                                       id="btn-servicio-cambiar"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Cambiar
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-bus me-2"></i>
                                    <p class="text-muted mb-3">Primero selecciona un tipo de servicio</p>
                                    <a href="{{ route('cliente.seleccion-tipo-servicio') }}"
                                       id="btn-servicio"
                                       class="btn btn-lg text-white"
                                       style="background-color: #3b82f6;">
                                        <i class=""></i>Seleccionar
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PASO 2 --}}
            <div class="row justify-content-center mb-5">
                <div class="col-lg-11"> {{-- CAMBIO --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header border-0 bg-white pt-4 pb-3">
                            <h4 class="mb-1" style="color: #3b82f6;">
                                <i class="fas fa-map-marked-alt me-2"></i>¿A dónde viajas?
                            </h4>
                            <p class="text-muted mb-0 small">Selecciona tu origen y destino</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>Desde
                                    </label>
                                    <select name="ciudad_origen_id"
                                            id="ciudad_origen_id"
                                            class="form-select form-select-small"
                                            required>
                                        <option value="">Ciudad de origen</option>
                                        @foreach($ciudades as $c)
                                            <option value="{{ $c->id }}" {{ old('ciudad_origen_id') == $c->id ? 'selected' : '' }}>
                                                {{ $c->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-flag-checkered me-2 text-success"></i>Hasta
                                    </label>
                                    <select name="ciudad_destino_id"
                                            id="ciudad_destino_id"
                                            class="form-select form-select-small"
                                            required>
                                        <option value="">Ciudad de destino</option>
                                        @foreach($ciudades as $c)
                                            <option value="{{ $c->id }}" {{ old('ciudad_destino_id') == $c->id ? 'selected' : '' }}>
                                                {{ $c->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botón Buscar --}}
            <div class="row justify-content-center">
                <div class="col-lg-11"> {{--  CAMBIO --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-lg text-white py-3 shadow-lg" style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); border: none;">
                            <i class="fas fa-search me-3"></i>Buscar Viajes Disponibles
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    {{-- Modal de error --}}
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-circle me-2"></i>Error de Validación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p id="errorMessage" class="fs-5 mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .btn:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInDown 0.6s ease-out;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const form = document.getElementById('buscarForm');
            const inputOrigen = document.getElementById('ciudad_origen_id');
            const inputDestino = document.getElementById('ciudad_destino_id');
            const btnServicio = document.getElementById('btn-servicio');
            const btnServicioCambiar = document.getElementById('btn-servicio-cambiar');

            if (params.get('from') === 'servicio' && sessionStorage.getItem('volviendo_de_servicio') === '1') {
                inputOrigen.value = localStorage.getItem('reserva_origen') || '';
                inputDestino.value = localStorage.getItem('reserva_destino') || '';
                sessionStorage.removeItem('volviendo_de_servicio');
            } else if (!params.has('page')) {
                localStorage.clear();
            }

            if(btnServicio) {
                btnServicio.addEventListener('click', function(e) {
                    if (inputOrigen.value && inputDestino.value) {
                        sessionStorage.setItem('volviendo_de_servicio', '1');
                        localStorage.setItem('reserva_origen', inputOrigen.value);
                        localStorage.setItem('reserva_destino', inputDestino.value);
                    }
                });
            }

            if(btnServicioCambiar) {
                btnServicioCambiar.addEventListener('click', function(e) {
                    if (inputOrigen.value && inputDestino.value) {
                        sessionStorage.setItem('volviendo_de_servicio', '1');
                        localStorage.setItem('reserva_origen', inputOrigen.value);
                        localStorage.setItem('reserva_destino', inputDestino.value);
                    }
                });
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const tieneServicio = {{ session('tipo_servicio_seleccionado.id') ? 'true' : 'false' }};

                if (!inputOrigen.value || !inputDestino.value) {
                    mostrarError('Por favor selecciona origen y destino.');
                    return;
                }

                if (inputOrigen.value === inputDestino.value) {
                    mostrarError('La ciudad de origen y destino no pueden ser la misma.');
                    return;
                }

                if (!tieneServicio) {
                    mostrarError('Debes seleccionar un tipo de servicio antes de buscar viajes.');
                    return;
                }

                this.submit();
            });

            function mostrarError(mensaje) {
                document.getElementById('errorMessage').textContent = mensaje;
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            }
        });
    </script>
@endsection
