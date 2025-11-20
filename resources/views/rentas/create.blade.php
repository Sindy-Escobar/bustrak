@extends('layouts.layoutadmin')

@section('title', 'Registro de Renta de Viaje Express')

@section('content')
    <div class="container mt-4">
        {{-- Encabezado y título principal --}}
        <h2 class="mb-4 text-center">Registro de renta de viaje express </h2>

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

        {{-- Formulario de Registro --}}
        <form action="{{ route('rentas.store') }}" method="POST" id="registroRentaForm" class="card p-4 shadow-sm rounded-3">
            @csrf

            {{-- Sección 1: Datos del Nuevo Cliente y Partida --}}
            <h3>Datos del cliente y partida</h3>
            <hr>
            {{-- Fila 1: Datos del Nuevo Cliente (Nombre, Email y DNI) --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre_cliente" class="form-label">Ingrese su nombre</label>
                    <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" placeholder="" required value="{{ old('nombre_cliente') }}">
                </div>
                <div class="col-md-4">
                    <label for="email_cliente" class="form-label">Ingrese su correo electronico</label>
                    <input type="text" name="email_cliente" id="email_cliente" class="form-control" placeholder value="{{ old('email_cliente') }}">
                </div>
                {{-- CAMPO DNI/Identificación que reemplaza a usuario_id --}}
                <div class="col-md-4">
                    <label for="dni_cliente" class="form-label">DNI / identificación</label>
                    {{-- Se cambia el nombre del campo a 'dni_cliente' --}}
                    {{-- Los atributos de validación se ajustan a lo que espera el Controller (max: 20, alfanumérico) --}}
                    <input type="text" name="dni_cliente" id="dni_cliente" class="form-control" required
                           maxlength="20"
                           placeholder="Identificación única del cliente"
                           value="{{ old('dni_cliente') }}">
                    {{-- SE ELIMINA EL CAMPO OCULTO 'id_cliente' ya que la lógica fue removida --}}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="punto_partida" class="form-label">Punto de partida</label>
                    <input type="text" name="punto_partida" id="punto_partida" class="form-control" required value="{{ old('punto_partida') }}">
                </div>
            </div>

            {{-- Sección 2: Detalles del Viaje --}}
            <h3>Detalles del viaje</h3>
            <hr>
            {{-- Fila 2: Destino y Fecha de Inicio --}}
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

            {{-- Fila 3: Fecha de Fin y Tipo de Evento --}}
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

            {{-- Fila 5: Pasajeros y Horarios --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="num_pasajeros_confirmados" class="form-label">Pasajeros confirmados</label>
                    <input type="number" name="num_pasajeros_confirmados" id="num_pasajeros_confirmados" class="form-control" value="{{ old('num_pasajeros_confirmados') }}">
                </div>
                <div class="col-md-3">
                    <label for="num_pasajeros_estimados" class="form-label">Pasajeros estimados</label>
                    <input type="number" name="num_pasajeros_estimados" id="num_pasajeros_estimados" class="form-control" value="{{ old('num_pasajeros_estimados') }}">
                </div>
                <div class="col-md-3">
                    <label for="hora_salida" class="form-label">Hora de salida</label>
                    <input type="time" name="hora_salida" id="hora_salida" class="form-control" value="{{ old('hora_salida') }}">
                </div>
                <div class="col-md-3">
                    <label for="hora_retorno" class="form-label">Hora de retorno</label>
                    <input type="time" name="hora_retorno" id="hora_retorno" class="form-control" value="{{ old('hora_retorno') }}">
                </div>
            </div>

            {{-- Sección 3: Finanzas --}}
            <h3>Información financiera</h3>
            <hr>
            {{-- Fila 4: Tarifa, Descuento, Total y Anticipo --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="tarifa" class="form-label">Tarifa base (Lps)</label>
                    <input type="number" step="0.01" name="tarifa" id="tarifa" class="form-control" required value="{{ old('tarifa') }}">
                </div>
                <div class="col-md-3">
                    <label for="descuento" class="form-label">Descuento (%) aplicado</label>
                    <select id="descuento_display" class="form-select" disabled>
                        <option value="0">0% (Ninguno)</option>
                        <option value="5">Familiar 5%</option>
                        <option value="8">Campamento 8%</option>
                        <option value="10">Excursión 10%</option>
                        <option value="15">Educativo 15%</option>
                        <option value="12">Empresarial 12%</option>
                    </select>
                    <input type="hidden" name="descuento_valor" id="descuento_valor" value="{{ old('descuento_valor') ?? '0' }}">
                </div>
                <div class="col-md-3">
                    <label for="total" class="form-label">Total a pagar (Lps)</label>
                    <input type="number" step="0.01" name="total" id="total" class="form-control" readonly value="{{ old('total') ?? '0.00' }}">
                </div>
                <div class="col-md-3">
                    <label for="anticipo" class="form-label">Anticipo (Lps)</label>
                    <input type="number" step="0.01" name="anticipo" id="anticipo" class="form-control" value="{{ old('anticipo') }}">
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Reservar renta </button>
                <button type="button" class="btn btn-warning px-4" id="limpiarFormulario">Limpiar</button>
                <a href="{{ route('rentas.index') }}" class="btn btn-secondary px-4">Cancelar</a>
            </div>
        </form>
    </div>

    {{-- Script para la lógica --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipoEvento = document.getElementById('tipo_evento');
            const descuentoDisplay = document.getElementById('descuento_display');
            const descuentoValorInput = document.getElementById('descuento_valor');
            const tarifaInput = document.getElementById('tarifa');
            const totalInput = document.getElementById('total');
            const limpiarBoton = document.getElementById('limpiarFormulario');
            const formulario = document.getElementById('registroRentaForm');

            // --- CAMPOS CLIENTE ---
            const nombreClienteInput = document.getElementById('nombre_cliente');
            // const idClienteInput = document.getElementById('id_cliente'); // Se elimina

            // Mapeo de eventos a porcentajes de descuento
            const descuentos = {
                'Familiar': 5,
                'Campamento': 8,
                'Excursión': 10,
                'Educativo': 15,
                'Empresarial': 12
            };

            // --- Funciones de Cálculo ---

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

            function establecerDescuento() {
                const evento = tipoEvento.value;
                const porcentaje = descuentos[evento] || 0;

                descuentoValorInput.value = porcentaje;

                let encontrado = false;
                for (let i = 0; i < descuentoDisplay.options.length; i++) {
                    if (descuentoDisplay.options[i].value == porcentaje) {
                        descuentoDisplay.selectedIndex = i;
                        encontrado = true;
                        break;
                    }
                }
                if (!encontrado) {
                    descuentoDisplay.selectedIndex = 0;
                }

                calcularTotal();
            }

            // SE ELIMINÓ LA FUNCIÓN generarIdCliente()

            // --- Event Listeners y Inicialización ---

            tipoEvento.addEventListener('change', establecerDescuento);
            tarifaInput.addEventListener('input', calcularTotal);

            // Listener para el campo de nombre que generaba el ID fue removido.
            // nombreClienteInput.addEventListener('input', generarIdCliente); // Se elimina

            // Inicialización al cargar la página
            establecerDescuento();
            // generarIdCliente(); // Se elimina la inicialización

            // Limpiar Formulario
            limpiarBoton.addEventListener('click', function() {
                // Limpia todos los campos del formulario
                formulario.reset();

                // Reinicia los campos de cálculo
                descuentoValorInput.value = '0';
                totalInput.value = '0.00';
                descuentoDisplay.selectedIndex = 0;

                // Regenerar/limpiar el ID del cliente oculto (Esta línea fue actualizada para no llamar a la función eliminada)
            });
        });
    </script>
@endsection
