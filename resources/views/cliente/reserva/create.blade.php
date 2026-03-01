@extends('layouts.layoutuser')

@section('contenido')
    @section('contenido')

        {{-- Bloque de Mensajes de Error/Éxito --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-bus me-2"></i>Reservar Viaje</h4>
        </div>

        <div class="card-body">
            <form id="buscarForm" action="{{ route('cliente.reserva.buscar') }}" method="POST" autocomplete="off" novalidate>
                @csrf

                <div class="row">
                    {{-- Ciudad de Origen --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Ciudad de Origen</label>
                        <select name="ciudad_origen_id" id="ciudad_origen_id" class="form-select" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($ciudades as $c)
                                <option value="{{ $c->id }}" {{ old('ciudad_origen_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Ciudad de Destino --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Ciudad de Destino</label>
                        <select name="ciudad_destino_id" id="ciudad_destino_id" class="form-select" required>
                            <option value="">-- Seleccione --</option>
                            @foreach($ciudades as $c)
                                <option value="{{ $c->id }}" {{ old('ciudad_destino_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Fecha de nacimiento --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Fecha de Nacimiento del Pasajero *
                        <i class="fas fa-info-circle text-primary" data-bs-toggle="tooltip" title="Los menores de 18 años requieren autorización de un tutor"></i>
                    </label>

                    <input type="date"
                           name="fecha_nacimiento_pasajero"
                           id="fecha_nacimiento"
                           class="form-control @error('fecha_nacimiento_pasajero') is-invalid @enderror"
                           value="{{ old('fecha_nacimiento_pasajero') }}"
                           required
                           max="{{ date('Y-m-d') }}">

                    @error('fecha_nacimiento_pasajero')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <small class="text-muted d-block mt-1">
                        Si el pasajero es menor de 18 años, deberá completar una autorización del tutor.
                    </small>
                </div>

                {{-- Alerta dinámica para menores --}}
                <div id="alerta-menor" class="alert alert-warning d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Atención:</strong> El pasajero es menor de edad. Se requerirá autorización del tutor tras confirmar.
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">


                    <a href="{{ route('cliente.seleccion-tipo-servicio') }}" id="btn-servicio" class="btn btn-outline-primary">
                        <i class="fas fa-concierge-bell me-2"></i>
                        @if(session('tipo_servicio_seleccionado'))
                            Servicio: {{ session('tipo_servicio_seleccionado.nombre') }} (Cambiar)
                        @else
                            Seleccionar Tipo de Servicio
                        @endif
                    </a>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-2"></i>Buscar Viajes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal de error --}}
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-circle me-2"></i>Error de Validación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const form = document.getElementById('buscarForm');
            const inputOrigen = document.getElementById('ciudad_origen_id');
            const inputDestino = document.getElementById('ciudad_destino_id');
            const inputFecha = document.getElementById('fecha_nacimiento');
            const btnServicio = document.getElementById('btn-servicio');

            // --- 1. Lógica de Persistencia (Recuperar datos al volver) ---
            if (params.get('from') === 'servicio' && sessionStorage.getItem('volviendo_de_servicio') === '1') {
                inputOrigen.value = localStorage.getItem('reserva_origen') || '';
                inputDestino.value = localStorage.getItem('reserva_destino') || '';
                inputFecha.value = localStorage.getItem('reserva_fecha_nac') || '';
                sessionStorage.removeItem('volviendo_de_servicio');

                // Disparar validación de edad si ya hay fecha
                validarEdad(inputFecha.value);
            } else if (!params.has('page')) { // Si no es paginación o retorno, limpiar
                localStorage.clear();
                // Opcional: Llamada al servidor para limpiar sesión de servicio si es entrada fresca
                if (!{{ session('tipo_servicio_seleccionado') ? 'true' : 'false' }}) {
                    fetch('{{ route("cliente.tipo-servicio.limpiar") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                    });
                }
            }

            // --- 2. Validación de Edad en tiempo real ---
            function validarEdad(fechaValor) {
                if(!fechaValor) return;
                const fechaNac = new Date(fechaValor);
                const hoy = new Date();
                let edad = hoy.getFullYear() - fechaNac.getFullYear();
                const mes = hoy.getMonth() - fechaNac.getMonth();
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) edad--;

                const alerta = document.getElementById('alerta-menor');
                edad < 18 ? alerta.classList.remove('d-none') : alerta.classList.add('d-none');
            }

            inputFecha.addEventListener('change', (e) => validarEdad(e.target.value));

            // --- 3. Guardar datos antes de ir a seleccionar servicio ---
            btnServicio.addEventListener('click', function(e) {
                if (!inputOrigen.value || !inputDestino.value || !inputFecha.value) {
                    e.preventDefault();
                    mostrarError('Por favor, indica origen, destino y fecha antes de elegir el servicio.');
                    return;
                }
                sessionStorage.setItem('volviendo_de_servicio', '1');
                localStorage.setItem('reserva_origen', inputOrigen.value);
                localStorage.setItem('reserva_destino', inputDestino.value);
                localStorage.setItem('reserva_fecha_nac', inputFecha.value);
            });

            // --- 4. Procesar Envío del Formulario ---
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Detener siempre primero para validar

                const tieneServicio = @json(session('tipo_servicio_seleccionado.id'));

                if (!inputOrigen.value || !inputDestino.value || !inputFecha.value) {
                    mostrarError('Todos los campos son obligatorios.');
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

                // Si pasa todo, enviamos
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
