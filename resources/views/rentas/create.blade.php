@extends('layouts.layoutadmin')

@section('title', 'Registro de Renta de Viaje Express')

@section('content')
    <div class="container mt-4">
        {{-- Encabezado y título principal --}}
        <h2 class="mb-4 text-center">Registro de renta de viaje express y nuevo cliente</h2>

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
            <h3>Datos del Cliente y Partida</h3>
            <hr>
            {{-- Fila 1: Datos del Nuevo Cliente (Nombre y Email/Contacto) --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre_cliente" class="form-label">**Nombre completo del cliente**</label>
                    <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" placeholder="Escriba el nombre del nuevo cliente" required value="{{ old('nombre_cliente') }}">
                </div>
                <div class="col-md-4">
                    <label for="email_cliente" class="form-label">**Email o Contacto**</label>
                    <input type="text" name="email_cliente" id="email_cliente" class="form-control" placeholder="Ej: correo@ejemplo.com o 9988-7766" required value="{{ old('email_cliente') }}">
                </div>
                {{-- CAMPO usuario_id AHORA ES ESCRIBIBLE --}}
                <div class="col-md-4">
                    <label for="usuario_id" class="form-label">**usuario_id (Escriba 13 dígitos)**</label>
                    {{-- SE QUITA EL atributo 'readonly' --}}
                    {{-- También añadí 'maxlength' y 'minlength' para guiar al usuario --}}
                    <input type="text" name="usuario_id" id="codigo_renta" class="form-control" required
                           minlength="13" maxlength="13" pattern="[0-9]*"
                           placeholder="Escriba los 13 dígitos del ID"
                           value="{{ old('usuario_id') }}">
                    {{-- CAMPO OCULTO PARA EL ID DEL CLIENTE, generado con las iniciales --}}
                    <input type="hidden" name="id_cliente" id="id_cliente" value="{{ old('id_cliente') }}">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label for="punto_partida" class="form-label">Punto de partida</label>
                    <input type="text" name="punto_partida" id="punto_partida" class="form-control" required value="{{ old('punto_partida') }}">
                </div>
            </div>

            {{-- Sección 2: Detalles del Viaje --}}
            <h3>Detalles del Viaje</h3>
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
            <h3>Información Financiera</h3>
            <hr>
            {{-- Fila 4: Tarifa, Descuento, Total y Anticipo --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="tarifa" class="form-label">Tarifa Base (Lps)</label>
                    <input type="number" step="0.01" name="tarifa" id="tarifa" class="form-control" required value="{{ old('tarifa') }}">
                </div>
                <div class="col-md-3">
                    <label for="descuento" class="form-label">Descuento (%) Aplicado</label>
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
                    <label for="total" class="form-label">Total a Pagar (Lps)</label>
                    <input type="number" step="0.01" name="total" id="total" class="form-control" readonly value="{{ old('total') ?? '0.00' }}">
                </div>
                <div class="col-md-3">
                    <label for="anticipo" class="form-label">Anticipo (Lps)</label>
                    <input type="number" step="0.01" name="anticipo" id="anticipo" class="form-control" value="{{ old('anticipo') }}">
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Guardar Renta y Cliente</button>
                <button type="button" class="btn btn-warning px-4" id="limpiarFormulario">Limpiar</button>
                <a href="{{ route('rentas.index') }}" class="btn btn-secondary px-4">Cancelar</a>
            </div>
        </form>
    </div>

    {{-- Script para la lógica (Se elimina la generación automática de usuario_id) --}}
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
            const idClienteInput = document.getElementById('id_cliente');

            // Mapeo de eventos a porcentajes de descuento
            const descuentos = {
                'Familiar': 5,
                'Campamento': 8,
                'Excursión': 10,
                'Educativo': 15,
                'Empresarial': 12
            };

            // --- Funciones de Cálculo y Generación ---

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

            // Función para Generar ID de Cliente basado en el nombre (SE MANTIENE, ya que este es el id_cliente oculto)
            function generarIdCliente() {
                const nombreCompleto = nombreClienteInput.value.trim();

                // Si el campo de nombre está vacío, el ID se limpia
                if (nombreCompleto === '') {
                    idClienteInput.value = '';
                    return;
                }

                // 1. Obtener iniciales del nombre
                const partes = nombreCompleto.split(/\s+/).filter(p => p.length > 0);
                let iniciales = partes.map(p => p.charAt(0).toUpperCase()).join('');

                if (iniciales.length === 0) {
                    iniciales = 'NC';
                }

                // 2. Obtener la fecha corta (MMDD)
                const date = new Date();
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');

                // 3. Generar 3 dígitos aleatorios
                const random = Math.floor(100 + Math.random() * 900);

                // 4. Formato final: [C-] + [Iniciales] + [MMDD] + [XXX]
                idClienteInput.value = `C-${iniciales}${month}${day}${random}`;
            }


            // --- Event Listeners y Inicialización ---

            tipoEvento.addEventListener('change', establecerDescuento);
            tarifaInput.addEventListener('input', calcularTotal);

            // Listener para el campo de nombre que genera el ID del Cliente
            nombreClienteInput.addEventListener('input', generarIdCliente);

            // Inicialización al cargar la página
            establecerDescuento();
            generarIdCliente(); // El ID del cliente oculto se sigue generando

            // Limpiar Formulario
            limpiarBoton.addEventListener('click', function() {
                // Limpia todos los campos del formulario
                formulario.reset();

                // Reinicia los campos de cálculo
                descuentoValorInput.value = '0';
                totalInput.value = '0.00';
                descuentoDisplay.selectedIndex = 0;

                // Regenerar/limpiar el ID del cliente oculto
                generarIdCliente();
            });
        });
    </script>
@endsection
