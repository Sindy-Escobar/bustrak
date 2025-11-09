@extends('layouts.apps')

@section('title', 'Registro de Renta de Viaje Express')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-center">Registro de renta de viaje express</h2>

        {{-- Mensajes de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>¡Ups! Algo salió mal.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rentas.store') }}" method="POST" id="registroRentaForm" class="card p-4 shadow-sm rounded-3">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="usuario_id" class="form-label">ID del cliente</label>
                    <input type="number" name="usuario_id" id="usuario_id" class="form-control" placeholder="" required value="{{ old('usuario_id') }}">
                </div>
                <div class="col-md-6">
                    <label for="punto_partida" class="form-label">Punto de partida</label>
                    <input type="text" name="punto_partida" id="punto_partida" class="form-control" required value="{{ old('punto_partida') }}">
                </div>
            </div>

            <div class="row mb-3">

                <div class="col-md-6">
                    <label for="destino" class="form-label">Destino</label>
                    <input type="text" name="destino" id="destino" class="form-control" required value="{{ old('destino') }}">
                </div>
                <div class="col-md-6">
                    <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="{{ old('fecha_inicio') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha_fin" class="form-label">Fecha de fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required value="{{ old('fecha_fin') }}">
                </div>
                <div class="col-md-6">
                    <label for="tipo_evento" class="form-label">Tipo de evento</label>
                    <select name="tipo_evento" id="tipo_evento" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="Familiar" {{ old('tipo_evento') == 'Familiar' ? 'selected' : '' }}>Familiar</option>
                        <option value="Campamento" {{ old('tipo_evento') == 'Campamento' ? 'selected' : '' }}>Campamento</option>
                        <option value="Excursión" {{ old('tipo_evento') == 'Excursión' ? 'selected' : '' }}>Excursión</option>
                        <option value="Educativo" {{ old('tipo_evento') == 'Educativo' ? 'selected' : '' }}>Educativo</option>
                        <option value="Empresarial" {{ old('tipo_evento') == 'Empresarial' ? 'selected' : '' }}>Empresarial</option>
                    </select>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="tarifa" class="form-label">Tarifa (Lps)</label>
                    <input type="number" step="0.01" name="tarifa" id="tarifa" class="form-control" required value="{{ old('tarifa') }}">
                </div>
                <div class="col-md-3">
                    <label for="descuento" class="form-label">Descuento (%)</label>
                    {{-- El campo ahora es de tipo hidden y se usa un select solo para mostrar el valor y enviarlo --}}
                    <select name="descuento" id="descuento" class="form-select" disabled>
                        <option value="0">Seleccione...</option>
                        <option value="5" data-text="Familiar 5%">Familiar 5%</option>
                        <option value="8" data-text="Campamento 8%">Campamento 8%</option>
                        <option value="10" data-text="Excursión 10%">Excursión 10%</option>
                        <option value="15" data-text="Educativo 15%">Educativo 15%</option>
                        <option value="12" data-text="Empresarial 12%">Empresarial 12%</option>
                    </select>
                    {{-- Campo hidden para enviar el valor del descuento --}}
                    <input type="hidden" name="descuento_valor" id="descuento_valor" value="0">
                </div>
                <div class="col-md-3">
                    <label for="total" class="form-label">Total (Lps)</label>
                    {{-- El campo total será de solo lectura y se calcula por JS --}}
                    <input type="number" step="0.01" name="total" id="total" class="form-control" readonly value="{{ old('total') ?? '0.00' }}">
                </div>
                <div class="col-md-3">
                    <label for="anticipo" class="form-label">Anticipo (Lps)</label>
                    <input type="number" step="0.01" name="anticipo" id="anticipo" class="form-control" value="{{ old('anticipo') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="num_pasajeros_confirmados" class="form-label">Pasajeros confirmados</label>
                    <input type="number" name="num_pasajeros_confirmados" id="num_pasajeros_confirmados" class="form-control" value="{{ old('num_pasajeros_confirmados') }}">
                </div>
                <div class="col-md-6">
                    <label for="num_pasajeros_estimados" class="form-label">Pasajeros estimados</label>
                    <input type="number" name="num_pasajeros_estimados" id="num_pasajeros_estimados" class="form-control" value="{{ old('num_pasajeros_estimados') }}">
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="hora_salida" class="form-label">Hora de salida</label>
                    <input type="time" name="hora_salida" id="hora_salida" class="form-control" value="{{ old('hora_salida') }}">
                </div>
                <div class="col-md-6">
                    <label for="hora_retorno" class="form-label">Hora de retorno</label>
                    <input type="time" name="hora_retorno" id="hora_retorno" class="form-control" value="{{ old('hora_retorno') }}">
                </div>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary px-4">Guardar Renta</button>
                <button type="button" class="btn btn-warning px-4" id="limpiarFormulario">Limpiar</button>
                <a href="{{ route('rentas.index') }}" class="btn btn-secondary px-4">Cancelar</a>
            </div>
        </form>
    </div>

    {{-- Script para la lógica de Descuento y Total --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoEvento = document.getElementById('tipo_evento');
            const descuentoSelect = document.getElementById('descuento');
            const descuentoValorInput = document.getElementById('descuento_valor');
            const tarifaInput = document.getElementById('tarifa');
            const totalInput = document.getElementById('total');
            const limpiarBoton = document.getElementById('limpiarFormulario');
            const formulario = document.getElementById('registroRentaForm');

            // Mapeo de eventos a porcentajes de descuento
            const descuentos = {
                'Familiar': 5,
                'Campamento': 8,
                'Excursión': 10,
                'Educativo': 15,
                'Empresarial': 12
            };

            /**
             * Calcula el total aplicando el descuento a la tarifa.
             */
            function calcularTotal() {
                const tarifa = parseFloat(tarifaInput.value) || 0;
                const descuentoPorcentaje = parseFloat(descuentoValorInput.value) || 0;

                if (tarifa > 0) {
                    const descuentoMonto = tarifa * (descuentoPorcentaje / 100);
                    const totalCalculado = tarifa - descuentoMonto;
                    totalInput.value = totalCalculado.toFixed(2);
                } else {
                    totalInput.value = '0.00';
                }
            }

            /**
             * Establece el descuento basado en el tipo de evento seleccionado.
             */
            function establecerDescuento() {
                const evento = tipoEvento.value;
                const porcentaje = descuentos[evento] || 0;

                // Actualizar el input hidden con el valor numérico
                descuentoValorInput.value = porcentaje;

                // Actualizar el <select> visible (solo visualmente, ya que está deshabilitado)
                // Esto busca la opción cuyo valor coincida con el porcentaje
                let encontrado = false;
                for (let i = 0; i < descuentoSelect.options.length; i++) {
                    if (descuentoSelect.options[i].value == porcentaje) {
                        descuentoSelect.selectedIndex = i;
                        encontrado = true;
                        break;
                    }
                }
                if (!encontrado) {
                    descuentoSelect.selectedIndex = 0; // Seleccionar "Seleccione..." o 0%
                }

                calcularTotal();
            }

            // Event Listeners
            tipoEvento.addEventListener('change', establecerDescuento);
            tarifaInput.addEventListener('input', calcularTotal);
            // También se llama al cargar la página para aplicar valores antiguos si existen
            establecerDescuento();


            /**
             * Limpia todos los campos del formulario.
             */
            limpiarBoton.addEventListener('click', function() {
                formulario.reset(); // Usa el método nativo reset
                // Asegurar que los campos calculados y selects vuelvan a su estado inicial
                descuentoValorInput.value = '0';
                totalInput.value = '0.00';
                descuentoSelect.selectedIndex = 0;
            });
        });
    </script>
@endsection
