@extends('layouts.layoutuser')

@section('contenido')

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Reservar Viaje</h4>
        </div>

        <div class="card-body">
            <form id="buscarForm" action="{{ route('cliente.reserva.buscar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Ciudad de Origen</label>
                    <select name="ciudad_origen_id" id="ciudad_origen_id" class="form-select" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($ciudades as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ciudad de Destino</label>
                    <select name="ciudad_destino_id" id="ciudad_destino_id" class="form-select" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($ciudades as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha de nacimiento --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Fecha de Nacimiento del Pasajero *
                        <i class="fas fa-info-circle text-primary"
                           data-bs-toggle="tooltip"
                           title="Los menores de 18 años requieren autorización de un tutor"></i>
                    </label>

                    <input type="date"
                           name="fecha_nacimiento_pasajero"
                           class="form-control @error('fecha_nacimiento_pasajero') is-invalid @enderror"
                           value="{{ old('fecha_nacimiento_pasajero') }}"
                           required
                           max="{{ date('Y-m-d') }}"
                           id="fecha_nacimiento">

                    @error('fecha_nacimiento_pasajero')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <small class="text-muted">
                        Si el pasajero es menor de 18 años, deberá completar una autorización del tutor
                    </small>
                </div>

                {{-- Alerta dinámica --}}
                <div id="alerta-menor" class="alert alert-warning d-none">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Atención:</strong> El pasajero es menor de edad.
                    Después de confirmar la reserva, deberás completar la autorización del tutor.
                </div>


                <button type="submit" class="btn btn-primary">
                    Buscar Viajes
                </button>
                <a href="{{ route('cliente.seleccion-tipo-servicio') }}" class="btn btn-primary ms-2">
                    <i class="fas fa-concierge-bell me-2"></i> Seleccionar Tipos de Servicios
                </a>
            </form>
        </div>
    </div>

    {{-- Modal de error --}}
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Error</h5>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('buscarForm').addEventListener('submit', function (e) {
            const origen = document.getElementById('ciudad_origen_id').value;
            const destino = document.getElementById('ciudad_destino_id').value;

            if (origen && destino && origen === destino) {
                e.preventDefault();
                document.getElementById('errorMessage').textContent = 'La ciudad de origen y destino deben ser diferentes.';
                new bootstrap.Modal(document.getElementById('errorModal')).show();
                return;
            }

            const tieneServicio = {{ session('tipo_servicio_seleccionado.id') ?? 'null' }};
            if (!tieneServicio) {
                e.preventDefault();
                document.getElementById('errorMessage').textContent = 'Debes seleccionar un tipo de servicio antes de buscar viajes.';
                new bootstrap.Modal(document.getElementById('errorModal')).show();
                return;
            }
        });

        document.getElementById('fecha_nacimiento').addEventListener('change', function() {
            const fechaNac = new Date(this.value);
            const hoy = new Date();
            let edad = hoy.getFullYear() - fechaNac.getFullYear();
            const mes = hoy.getMonth() - fechaNac.getMonth();
            if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) edad--;
            const alerta = document.getElementById('alerta-menor');
            if (edad < 18) {
                alerta.classList.remove('d-none');
            } else {
                alerta.classList.add('d-none');
            }
        });

        document.querySelector('a[href*="seleccionar-servicio"]').addEventListener('click', function(e) {
            const origen = document.getElementById('ciudad_origen_id').value;
            const destino = document.getElementById('ciudad_destino_id').value;
            const fechaNac = document.getElementById('fecha_nacimiento').value;

            if (!origen || !destino || !fechaNac) {
                e.preventDefault();
                document.getElementById('errorMessage').textContent = 'Debes llenar Ciudad de Origen, Destino y Fecha de Nacimiento antes de seleccionar el tipo de servicio.';
                new bootstrap.Modal(document.getElementById('errorModal')).show();
                return;
            }

            localStorage.setItem('reserva_origen', origen);
            localStorage.setItem('reserva_destino', destino);
            localStorage.setItem('reserva_fecha_nac', fechaNac);
        });

        if (document.referrer.includes('seleccion-tipo-servicio')) {
            const origen = localStorage.getItem('reserva_origen');
            const destino = localStorage.getItem('reserva_destino');
            const fechaNac = localStorage.getItem('reserva_fecha_nac');
            if (origen) document.getElementById('ciudad_origen_id').value = origen;
            if (destino) document.getElementById('ciudad_destino_id').value = destino;
            if (fechaNac) document.getElementById('fecha_nacimiento').value = fechaNac;
        } else {
            localStorage.removeItem('reserva_origen');
            localStorage.removeItem('reserva_destino');
            localStorage.removeItem('reserva_fecha_nac');
            fetch('{{ route("cliente.tipo-servicio.limpiar") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
        }
    </script>

@endsection

