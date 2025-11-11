@extends('layouts.layoutadmin')

@section('title', 'Editar Terminal - BusTrak')

@section('content')

    {{-- Script de Tailwind CSS y Estilos (para consistencia) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos personalizados para el formulario */
        .error-message {
            color: #dc3545; /* Color de error de Bootstrap */
            font-size: 0.875em;
            margin-top: 0.25rem;
            min-height: 1.25em; /* Previene CLS */
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545 !important;
        }

        /* Estilo para los iconos en el input group */
        .input-group-text {
            border-right: none;
            background-color: #f8f9fa !important;
        }

        /* Ajuste para que los textareas se vean bien */
        textarea {
            min-height: 100px;
            resize: none;
        }

        /* Estilos para el encabezado */
        .custom-title {
            color: #1e63b8;
            font-weight: 600;
            font-size: 1.8rem;
        }
    </style>

    {{-- 📝 Contenedor Principal del Formulario --}}
    <div class="container px-0">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-4">
                <header>
                    <h2 class="mb-0 custom-title">
                        <i class="fas fa-edit me-2"></i>Editar Terminal: {{ $terminal->nombre }}
                    </h2>
                </header>

                {{-- ➡️ Formulario de Edición --}}
                <div class="card-body p-4 p-md-5">
                    <form id="terminalForm"
                          action="{{ route('terminales.update', $terminal) }}"
                          method="POST"
                          novalidate>                        @csrf
                        @method('PUT')

                        {{-- 1️⃣ DATOS DE UBICACIÓN --}}
                        <h5 class="mb-3 mt-2 custom-title" style="font-size:1.5rem;">
                            <i class="fas fa-map-marker-alt me-2"></i>1. Datos de ubicación
                        </h5>
                        <hr class="mt-0 mb-4">
                        <div class="row g-4">
                            {{-- Nombre --}}
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input
                                    type="text"
                                    id="nombre"
                                    name="nombre"
                                    value="{{ old('nombre', $terminal->nombre) }}"
                                    maxlength="100"
                                    required
                                    class="form-control"
                                >
                                <div id="error-nombre" class="error-message"></div>
                            </div>

                            {{-- Departamento (Pre-selecciona el valor guardado) --}}
                            <div class="col-md-6">
                                <label for="departamento" class="form-label">Departamento</label>
                                <select
                                    id="departamento"
                                    name="departamento"
                                    class="form-select"
                                    required
                                >
                                    <option value="">-- Seleccione un departamento --</option>
                                    @foreach($departamentos as $depto)
                                        <option
                                            value="{{ $depto }}"
                                            {{ old('departamento', $terminal->departamento) == $depto ? 'selected' : '' }}
                                        >
                                            {{ $depto }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="error-departamento" class="error-message"></div>
                            </div>

                            {{-- Municipio (Será llenado y seleccionado por JavaScript) --}}
                            <div class="col-md-6">
                                <label for="municipio" class="form-label">Municipio</label>
                                <select id="municipio" name="municipio" class="form-select" required>
                                    <option value="">-- Seleccione primero un departamento --</option>
                                </select>

                                <div id="error-municipio" class="error-message"></div>
                                {{-- Campo oculto para guardar el valor del municipio existente y cargarlo con JS --}}
                                <input type="hidden" id="old_municipio" value="{{ old('municipio', $terminal->municipio) }}">
                            </div>

                            {{-- Código --}}
                            <div class="col-md-6">
                                <label for="codigo" class="form-label">Código (generado automáticamente)</label>
                                <input
                                    type="text"
                                    id="codigo"
                                    name="codigo"
                                    value="{{ old('codigo', $terminal->codigo) }}"
                                    class="form-control bg-light"
                                    required
                                    readonly
                                >
                                <div id="error-codigo" class="error-message"></div>
                            </div>

                            {{-- Dirección --}}
                            <div class="col-12">
                                <label for="direccion" class="form-label">Dirección exacta</label>
                                <textarea
                                    id="direccion"
                                    name="direccion"
                                    class="form-control"
                                    maxlength="150"
                                    required
                                >{{ old('direccion', $terminal->direccion) }}</textarea>
                                <div id="error-direccion" class="error-message"></div>
                            </div>
                        </div>

                        {{-- 2️⃣ INFORMACIÓN DE CONTACTO --}}
                        <h5 class="mb-3 mt-5 custom-title" style="font-size:1.5rem;">
                            <i class="fas fa-address-book me-2"></i>2. Información de Contacto
                        </h5>
                        <hr class="mt-0 mb-4">
                        <div class="row g-4">
                            {{-- Teléfono --}}
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input
                                    type="text"
                                    id="telefono"
                                    name="telefono"
                                    value="{{ old('telefono', $terminal->telefono) }}"
                                    class="form-control"
                                    maxlength="8"
                                    required
                                >
                                <div id="error-telefono" class="error-message"></div>
                            </div>

                            {{-- Correo --}}
                            <div class="col-md-6">
                                <label for="correo" class="form-label">Correo electrónico</label>
                                <input
                                    type="email"
                                    id="correo"
                                    name="correo"
                                    value="{{ old('correo', $terminal->correo) }}"
                                    class="form-control"
                                    maxlength="50"
                                    required
                                >
                                <div id="error-correo" class="error-message"></div>
                            </div>
                        </div>

                        {{-- 3️⃣ HORARIOS Y DESCRIPCIÓN --}}
                        <h5 class="mb-3 mt-5 custom-title" style="font-size:1.5rem;">
                            <i class="fas fa-clock me-2"></i>3. Horarios y Detalles
                        </h5>
                        <hr class="mt-0 mb-4">
                        <div class="row g-4">
                            {{-- Horario Apertura --}}
                            <div class="col-md-4">
                                <label for="horario_apertura_hora" class="form-label">Horario de apertura</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-sun text-warning"></i></span>
                                    <select id="horario_apertura_hora" required class="form-select">
                                        <option value="">Hora</option>
                                    </select>
                                    <span class="input-group-text">:</span>
                                    <select id="horario_apertura_minuto" required class="form-select">
                                        <option value="">Min</option>
                                    </select>
                                </div>
                                <div id="error-horario_apertura" class="error-message"></div>
                            </div>

                            {{-- Horario Cierre --}}
                            <div class="col-md-4">
                                <label for="horario_cierre_hora" class="form-label">Horario de cierre</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-moon text-secondary"></i></span>
                                    <select id="horario_cierre_hora" required class="form-select">
                                        <option value="">Hora</option>
                                    </select>
                                    <span class="input-group-text">:</span>
                                    <select id="horario_cierre_minuto" required class="form-select">
                                        <option value="">Min</option>
                                    </select>
                                </div>
                                <div id="error-horario_cierre" class="error-message"></div>
                            </div>
                        </div>

                        {{-- 🟢 CAMPOS OCULTOS con valores de la DB para inicializar el JS y enviar al servidor --}}
                        <input type="hidden" name="horario_apertura" id="horario_apertura_hidden" value="{{ old('horario_apertura', $terminal->horario_apertura) }}">
                        <input type="hidden" name="horario_cierre" id="horario_cierre_hidden" value="{{ old('horario_cierre', $terminal->horario_cierre) }}">

                        {{-- Descripción --}}
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <label for="descripcion" class="form-label">Descripción y notas adicionales</label>
                                <textarea
                                    id="descripcion"
                                    name="descripcion"
                                    class="form-control"
                                    rows="3"
                                    required
                                >{{ old('descripcion', $terminal->descripcion) }}</textarea>
                                <div id="error-descripcion" class="error-message"></div>
                            </div>
                        </div>


                        {{-- 🔘 BOTONES DE ACCIÓN --}}
                        <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                            <a href="{{ route('terminales.index') }}" class="btn btn-secondary rounded-2 px-4 shadow-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver a la lista
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning reset-btn">
                                    <i class="fas fa-undo me-2"></i> Restaurar Original
                                </button>
                                <button type="submit" class="btn btn-success rounded-2 px-4 shadow">
                                    <i class="fas fa-save me-2"></i>Actualizar Terminal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endsection

        @section('scripts')
            <script>
                // La data del controlador se inyecta directamente
                const municipiosData = @json($municipiosHonduras);

                document.addEventListener('DOMContentLoaded', function() {
                    // 🏷️ Referencias a Elementos del DOM
                    const terminalForm = document.getElementById('terminalForm');
                    const departamentoSelect = document.getElementById('departamento');
                    const municipioSelect = document.getElementById('municipio');
                    const codigoInput = document.getElementById('codigo');
                    const nombreInput = document.getElementById('nombre');

                    // ⏰ Referencias de los SELECTS de Hora/Minuto y los campos HIDDEN
                    const horarioAperturaHoraSelect = document.getElementById('horario_apertura_hora');
                    const horarioAperturaMinutoSelect = document.getElementById('horario_apertura_minuto');
                    const horarioCierreHoraSelect = document.getElementById('horario_cierre_hora');
                    const horarioCierreMinutoSelect = document.getElementById('horario_cierre_minuto');
                    const horarioAperturaHidden = document.getElementById('horario_apertura_hidden');
                    const horarioCierreHidden = document.getElementById('horario_cierre_hidden');


                    // 🟢 VALOR GUARDADO DEL MUNICIPIO (Se lee aquí para estar seguro)
                    const oldMunicipioValue = document.getElementById('old_municipio').value;


                    // ❌ Funciones de Manejo de Errores (Mostrar/Limpiar)
                    function showError(field, message) {
                        const fieldId = field.id;
                        // Para horarios, el error se muestra en el div principal
                        const errorId = fieldId.startsWith('horario_') ? fieldId.substring(0, fieldId.lastIndexOf('_')) : fieldId;
                        const errorElement = document.getElementById(`error-${errorId}`);

                        if (errorElement) {
                            if (fieldId.startsWith('horario_')) {
                                // Mostrar mensaje en el div principal y marcar ambos selects
                                errorElement.innerHTML = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                                document.getElementById(fieldId.startsWith('horario_apertura') ? 'horario_apertura_hora' : 'horario_cierre_hora').classList.add('is-invalid');
                                document.getElementById(fieldId.startsWith('horario_apertura') ? 'horario_apertura_minuto' : 'horario_cierre_minuto').classList.add('is-invalid');
                            } else {
                                errorElement.innerHTML = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                                field.classList.add('is-invalid');
                            }
                        }
                    }

                    function clearError(field) {
                        const fieldId = field.id;
                        const errorId = fieldId.startsWith('horario_') ? fieldId.substring(0, fieldId.lastIndexOf('_')) : fieldId;
                        const errorElement = document.getElementById(`error-${errorId}`);

                        if (errorElement && !fieldId.startsWith('horario_')) {
                            errorElement.textContent = '';
                        }

                        field.classList.remove('is-invalid');

                        // Limpieza especial para Horarios: Si ambos selects tienen valor, limpiar el error principal.
                        if (fieldId.startsWith('horario_')) {
                            const horaSelect = document.getElementById(fieldId.startsWith('horario_apertura') ? 'horario_apertura_hora' : 'horario_cierre_hora');
                            const minutoSelect = document.getElementById(fieldId.startsWith('horario_apertura') ? 'horario_apertura_minuto' : 'horario_cierre_minuto');

                            if (horaSelect.value && minutoSelect.value) {
                                horaSelect.classList.remove('is-invalid');
                                minutoSelect.classList.remove('is-invalid');
                                document.getElementById(errorId).textContent = '';
                            }
                        }
                    }

                    // 📐 Función para Auto-Redimensionar Textareas
                    function autoResize() {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                    }

                    // 🏙️ Funciones de Ubicación y Código
                    function loadMunicipios() {
                        const selectedDepto = departamentoSelect.value;
                        municipioSelect.innerHTML = ''; // Limpiar opciones anteriores

                        if (selectedDepto && municipiosData[selectedDepto]) {
                            // 🟢 Asegurar que el select esté habilitado al cargar data
                            municipioSelect.disabled = false;
                            municipioSelect.appendChild(new Option('-- Seleccione un municipio --', ''));

                            const municipios = municipiosData[selectedDepto].sort();

                            municipios.forEach(municipio => {
                                const option = new Option(municipio, municipio);

                                // LÓGICA DE PRE-SELECCIÓN DEL MUNICIPIO GUARDADO
                                if (municipio === oldMunicipioValue) { // Usa la variable segura
                                    option.selected = true;
                                }
                                municipioSelect.appendChild(option);
                            });
                        } else {
                            municipioSelect.disabled = true;
                            municipioSelect.appendChild(new Option('-- Seleccione primero un departamento --', ''));
                        }
                        updateCodigo();

                        if (municipioSelect.value && municipioSelect.classList.contains('is-invalid')) {
                            clearError(municipioSelect);
                        }
                    }

                    function updateCodigo() {
                        const depto = departamentoSelect.value;
                        const muni = municipioSelect.value;
                        const nombre = nombreInput.value;
                        let generatedCode = '';

                        if (depto && muni) {
                            const deptoCode = depto.substring(0, 3).toUpperCase();
                            const muniCode = muni.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
                            const nameHash = nombre.replace(/\s/g, '').substring(0, 2).toUpperCase() || 'XX';
                            generatedCode = `${deptoCode}-${muniCode}-${nameHash}`;
                        } else if (depto && !muni) {
                            const deptoCode = depto.substring(0, 3).toUpperCase();
                            const nameHash = nombre.replace(/\s/g, '').substring(0, 2).toUpperCase() || 'XX';
                            generatedCode = `${deptoCode}-MUN-XX-${nameHash}`;
                        }

                        codigoInput.value = generatedCode;
                        clearError(codigoInput);
                    }

                    // ⏱️ Funciones de Horario
                    function generateHourOptions() {
                        const options = [];
                        for (let h = 0; h < 24; h++) {
                            const hour24 = String(h).padStart(2, '0');
                            let hour12 = h % 12 || 12;
                            const ampm = h < 12 ? 'AM' : 'PM';
                            const text12 = `${String(hour12).padStart(2, '0')} ${ampm}`;
                            options.push({ value: hour24, text: text12 });
                        }
                        return options;
                    }

                    function generateMinuteOptions() {
                        const options = [];
                        for (let m = 0; m < 60; m += 5) {
                            const minute = String(m).padStart(2, '0');
                            options.push({ value: minute, text: minute });
                        }
                        return options;
                    }

                    function updateHiddenFields() {
                        const ap_h = horarioAperturaHoraSelect.value;
                        const ap_m = horarioAperturaMinutoSelect.value;
                        const ci_h = horarioCierreHoraSelect.value;
                        const ci_m = horarioCierreMinutoSelect.value;

                        horarioAperturaHidden.value = (ap_h && ap_m) ? `${ap_h}:${ap_m}` : '';
                        horarioCierreHidden.value = (ci_h && ci_m) ? `${ci_h}:${ci_m}` : '';

                        if (horarioAperturaHidden.value) {
                            clearError(horarioAperturaHoraSelect);
                        }
                        if (horarioCierreHidden.value) {
                            clearError(horarioCierreHoraSelect);
                        }
                    }

                    // 🟢 CORRECCIÓN CLAVE: Lógica para pre-seleccionar los selects de Hora/Minuto
                    function populateTimeSelects() {
                        const hourOptions = generateHourOptions();
                        const minuteOptions = generateMinuteOptions();

                        const oldApertura = horarioAperturaHidden.value; // Formato HH:MM (e.g., "08:00")
                        const oldCierre = horarioCierreHidden.value;     // Formato HH:MM (e.g., "17:00")

                        // ✅ FIX: Usar split para parsear de forma robusta
                        const [apHora, apMinuto] = oldApertura.split(':');
                        const oldAperturaHora = apHora || '';
                        const oldAperturaMinuto = apMinuto || '';

                        const [ciHora, ciMinuto] = oldCierre.split(':');
                        const oldCierreHora = ciHora || '';
                        const oldCierreMinuto = ciMinuto || '';

                        // Función auxiliar para llenar un select
                        const fillSelect = (selectElement, options, oldValue, defaultText) => {
                            selectElement.innerHTML = `<option value="">${defaultText}</option>`;
                            options.forEach(optionData => {
                                const newOption = new Option(optionData.text, optionData.value);

                                // LÓGICA DE PRE-SELECCIÓN
                                if (optionData.value === oldValue) {
                                    newOption.selected = true;
                                }
                                selectElement.appendChild(newOption);
                            });
                        };

                        // Llenar y seleccionar Apertura
                        fillSelect(horarioAperturaHoraSelect, hourOptions, oldAperturaHora, 'Hora');
                        fillSelect(horarioAperturaMinutoSelect, minuteOptions, oldAperturaMinuto, 'Min');

                        // Llenar y seleccionar Cierre
                        fillSelect(horarioCierreHoraSelect, hourOptions, oldCierreHora, 'Hora');
                        fillSelect(horarioCierreMinutoSelect, minuteOptions, oldCierreMinuto, 'Min');

                        updateHiddenFields();
                    }

                    // 🚀 Inicialización

                    // 1. Lógica para Ubicación
                    if (departamentoSelect.value) {
                        // Llamar a loadMunicipios explícitamente al inicio si hay un departamento pre-seleccionado
                        loadMunicipios();
                    }

                    // 2. Lógica para Horarios
                    populateTimeSelects();

                    // 👂 Event Listeners para Ubicación y Código
                    departamentoSelect.addEventListener('change', loadMunicipios);
                    municipioSelect.addEventListener('change', updateCodigo);
                    nombreInput.addEventListener('input', updateCodigo);

                    // 👂 Event Listeners para Horarios (mantienen el campo hidden actualizado)
                    [
                        horarioAperturaHoraSelect,
                        horarioAperturaMinutoSelect,
                        horarioCierreHoraSelect,
                        horarioCierreMinutoSelect
                    ].forEach(select => {
                        select.addEventListener('change', updateHiddenFields);
                    });


                    // 👂 Event Listeners para Textareas y ajuste de tamaño inicial
                    const direccionTextarea = document.getElementById('direccion');
                    const descripcionTextarea = document.getElementById('descripcion');

                    direccionTextarea.addEventListener('input', autoResize);
                    descripcionTextarea.addEventListener('input', autoResize);
                    if (direccionTextarea.value) autoResize.call(direccionTextarea);
                    if (descripcionTextarea.value) autoResize.call(descripcionTextarea);

                    // 👂 Event Listeners para limpiar errores en inputs/textareas/selects
                    document.querySelectorAll(
                        'input:not([type="hidden"]):not([readonly]), select, textarea'
                    ).forEach(field => {
                        field.addEventListener('input', function() { clearError(this); });
                        field.addEventListener('change', function() { clearError(this); });
                    });


                    // 🗑️ Lógica para el botón de Restaurar (Recarga para mostrar valores originales)
                    const resetButton = document.querySelector('.reset-btn');
                    if (resetButton) {
                        resetButton.addEventListener('click', function() {
                            window.location.reload();
                        });
                    }

                    // 🛑 Lógica para la Validación del Formulario al Enviar (Validación Cliente)
                    terminalForm.addEventListener('submit', function(event) {
                        let isValid = true;
                        let firstInvalidField = null;

                        // 1. Asegurarse de que los campos ocultos están actualizados justo antes del envío
                        updateHiddenFields();

                        // Limpiar todos los mensajes de error antes de revalidar
                        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));


                        const requiredFields = terminalForm.querySelectorAll('[required]');

                        requiredFields.forEach(field => {
                            let message = '';
                            const value = field.value.trim();
                            const fieldId = field.id;
                            let isFieldInvalid = false;

                            // 1. Validación de campos de Horario (Solo chequeamos una vez por tipo)
                            if (fieldId.startsWith('horario_apertura_hora') || fieldId.startsWith('horario_cierre_hora')) {
                                const horaSelect = document.getElementById(fieldId);
                                const minutoSelect = document.getElementById(fieldId.replace('_hora', '_minuto'));

                                if (!horaSelect.value || !minutoSelect.value) {
                                    message = 'Debe seleccionar tanto la **hora** como el **minuto** para el horario.';
                                    isFieldInvalid = true;

                                    if (!firstInvalidField) firstInvalidField = horaSelect;
                                }

                                if (isFieldInvalid) {
                                    isValid = false;
                                    showError(horaSelect, message); // Muestra el error en el div principal y marca los selects
                                }
                            }
                            // 2. Validación de otros campos vacíos
                            else if (!fieldId.startsWith('horario_') && !value) {
                                message = 'Este campo es **obligatorio**.';
                                isFieldInvalid = true;
                            }

                            // 3. Validación de Teléfono (8 dígitos)
                            else if (fieldId === 'telefono' && !/^\d{8}$/.test(value)) {
                                message = 'El teléfono debe contener exactamente **8 dígitos** numéricos.';
                                isFieldInvalid = true;
                            }

                            // 4. Validación de Correo electrónico
                            else if (fieldId === 'correo' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                                message = 'Ingrese un **correo electrónico válido** (ej: nombre@dominio.com).';
                                isFieldInvalid = true;
                            }


                            if (isFieldInvalid && !fieldId.startsWith('horario_')) {
                                isValid = false;
                                showError(field, message);
                                if (!firstInvalidField) {
                                    firstInvalidField = field;
                                }
                            }
                        });

                        // 5. Validación Horario Cierre vs Apertura (Final)
                        const aperturaValue = horarioAperturaHidden.value;
                        const cierreValue = horarioCierreHidden.value;

                        if (aperturaValue && cierreValue && cierreValue <= aperturaValue) {
                            const message = 'El horario de cierre debe ser **posterior** al de apertura.';
                            isValid = false;
                            showError(horarioCierreHoraSelect, message); // Muestra error y marca los selects de cierre

                            // Si no se ha marcado un campo aún, marcamos el de cierre para enfocar
                            if (!firstInvalidField) firstInvalidField = horarioCierreHoraSelect;
                        }

                        if (!isValid) {
                            event.preventDefault();
                            if (firstInvalidField) {
                                firstInvalidField.focus();
                            }
                        }

                });
                });
            </script>
@endsection
