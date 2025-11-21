@extends('layouts.layoutadmin')

@section('title', 'Edición de Terminal - BusTrak')

@section('content')

    {{-- 🛑 Mensajes de Sesión (Errores o Éxito) --}}
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    {{-- 📝 Contenedor Principal del Formulario --}}
    <div class="container px-0">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-4">
                <header>
                    <h2 class="mb-0" style="color:#1e63b8; font-weight:600; font-size:1.8rem;">
                        <i class="fas fa-bus me-2"></i>Editar Terminal
                    </h2>
                </header>

                {{-- ➡ Formulario de Edición --}}
                <div class="card-body p-4 p-md-5">
                    {{-- 🛑 RUTA DE ACTUALIZACIÓN CON MÉTODO PUT/PATCH --}}
                    <form id="terminalForm" action="{{ route('terminales.update', $terminal->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')


                        {{-- 1️⃣ DATOS DE UBICACIÓN --}}
                        <h5 class="mb-3 mt-2" style="color:#1e63b8; font-weight:600; font-size:1.5rem;">
                            <i class="fas fa-map-marker-alt me-2"></i>1. Datos de ubicación
                        </h5>
                        <hr class="mt-0 mb-4">
                        <div class="row g-4">
                            {{-- Fila 1: Nombre y Código --}}
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                {{-- 🛑 PRECARGA DE VALOR EXISTENTE --}}
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $terminal->nombre) }}" maxlength="100" required
                                       class="form-control @error('nombre') is-invalid @enderror">
                                @error('nombre')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div id="error-nombre" class="error-message"></div>
                            </div>

                            {{-- Fila 2: Departamento y Municipio --}}
                            <div class="col-md-6">
                                <label for="departamento" class="form-label">Departamento</label>
                                <select id="departamento" name="departamento" required
                                        class="form-select @error('departamento') is-invalid @enderror">
                                    <option value="">-- Seleccione un departamento --</option>
                                    @foreach($departamentos as $depto)
                                        {{-- 🛑 PRECARGA DE VALOR EXISTENTE --}}
                                        <option value="{{ $depto }}" {{ old('departamento', $terminal->departamento) == $depto ? 'selected' : '' }}>
                                            {{ $depto }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departamento')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div id="error-departamento" class="error-message"></div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="municipio" class="form-label">Municipio</label>
                                    <select id="municipio" name="municipio" required disabled
                                            class="form-select @error('municipio') is-invalid @enderror">
                                        {{-- Las opciones se cargan mediante JavaScript --}}
                                        <option value="">-- Seleccione primero un departamento --</option>
                                    </select>
                                    @error('municipio')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    <div id="error-municipio" class="error-message"></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código</label>
                                    {{-- 🛑 PRECARGA DE VALOR EXISTENTE Y PERMANENTE EN EDICIÓN --}}
                                    <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $terminal->codigo) }}" maxlength="10" required readonly
                                           class="form-control bg-light @error('codigo') is-invalid @enderror">
                                    @error('codigo')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    <div id="error-codigo" class="error-message"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Fila 3: Dirección --}}
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    {{-- 🛑 PRECARGA DE VALOR EXISTENTE --}}
                                    <textarea id="direccion" name="direccion" maxlength="150" required
                                              class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion', $terminal->direccion) }}</textarea>
                                    @error('direccion')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    <div id="error-direccion" class="error-message"></div>
                                </div>
                            </div>
                        </div>

                        {{-- 2️⃣ INFORMACIÓN DE CONTACTO --}}
                        <h5 class="mb-3 mt-5" style="color:#1e63b8; font-weight:600; font-size:1.5rem;">
                            <i class="fas fa-address-book me-2"></i>2. Información de contacto
                        </h5>
                        <hr class="mt-0 mb-4">
                        <div class="row g-4">
                            {{-- Fila 4: Teléfono y Correo --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    {{-- 🛑 PRECARGA DE VALOR EXISTENTE --}}
                                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $terminal->telefono) }}" maxlength="8" required
                                           class="form-control @error('telefono') is-invalid @enderror">
                                    @error('telefono')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    <div id="error-telefono" class="error-message"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="correo" class="form-label">Correo electrónico</label>
                                    {{-- 🛑 PRECARGA DE VALOR EXISTENTE --}}
                                    <input type="email" id="correo" name="correo" value="{{ old('correo', $terminal->correo) }}" maxlength="50" required
                                           class="form-control @error('correo') is-invalid @enderror">
                                    @error('correo')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    <div id="error-correo" class="error-message"></div>
                                </div>
                            </div>
                        </div>

                        {{-- 3️⃣ HORARIOS Y DESCRIPCIÓN --}}
                        <h5 class="mb-3 mt-5" style="color:#1e63b8; font-weight:600; font-size:1.5rem;">
                            <i class="fas fa-clock me-2"></i>3. Horarios y detalles
                        </h5>
                        <hr class="mt-0 mb-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label for="horario_apertura_hora" class="form-label">Horario de apertura</label>
                                <div class="input-group">
                                    {{-- Icono de Sol (Apertura) --}}
                                    <span class="input-group-text bg-light"><i class="fas fa-sun text-warning"></i></span>

                                    {{-- Select Hora --}}
                                    <select id="horario_apertura_hora" required
                                            class="form-select">
                                        <option value="">Hora</option>
                                    </select>

                                    {{-- Separador --}}
                                    <span class="input-group-text">:</span>

                                    {{-- Select Minuto --}}
                                    <select id="horario_apertura_minuto" required
                                            class="form-select">
                                        <option value="">Min</option>
                                    </select>
                                </div>
                                {{-- El mensaje de error usa el ID original del campo (horario_apertura) --}}
                                @error('horario_apertura')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div id="error-horario_apertura" class="error-message"></div>
                            </div>

                            <div class="col-md-4">
                                <label for="horario_cierre_hora" class="form-label">Horario de cierre</label>
                                <div class="input-group">
                                    {{-- Icono de Luna (Cierre) --}}
                                    <span class="input-group-text bg-light"><i class="fas fa-moon text-secondary"></i></span>

                                    {{-- Select Hora --}}
                                    <select id="horario_cierre_hora" required
                                            class="form-select">
                                        <option value="">Hora</option>
                                    </select>

                                    {{-- Separador --}}
                                    <span class="input-group-text">:</span>

                                    {{-- Select Minuto --}}
                                    <select id="horario_cierre_minuto" required
                                            class="form-select">
                                        <option value="">Min</option>
                                    </select>
                                </div>
                                @error('horario_cierre')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div id="error-horario_cierre" class="error-message"></div>
                            </div>
                        </div>

                        {{-- CAMPOS OCULTOS para enviar el valor completo HH:MM a Laravel --}}
                        <input type="hidden" name="horario_apertura" id="horario_apertura_hidden">
                        <input type="hidden" name="horario_cierre" id="horario_cierre_hidden">

                        {{-- Fila 6: Descripción --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    {{-- 🛑 PRECARGA DE VALOR EXISTENTE --}}
                                    <textarea id="descripcion" name="descripcion" required
                                              class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $terminal->descripcion) }}</textarea>
                                    @error('descripcion')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                    <div id="error-descripcion" class="error-message"></div>
                                </div>
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
    </div>

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

            // ⏰ Referencias de los nuevos SELECTS de Hora/Minuto y los campos HIDDEN
            const horarioAperturaHoraSelect = document.getElementById('horario_apertura_hora');
            const horarioAperturaMinutoSelect = document.getElementById('horario_apertura_minuto');
            const horarioCierreHoraSelect = document.getElementById('horario_cierre_hora');
            const horarioCierreMinutoSelect = document.getElementById('horario_cierre_minuto');

            const horarioAperturaHidden = document.getElementById('horario_apertura_hidden');
            const horarioCierreHidden = document.getElementById('horario_cierre_hidden');

            const terminal = @json($terminal);



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

                if (fieldId.startsWith('horario_') && errorElement) {
                    const otherSelectId = fieldId.endsWith('_hora') ? fieldId.replace('_hora', '_minuto') : fieldId.replace('_minuto', '_hora');
                    const otherSelect = document.getElementById(otherSelectId);

                    if (field.value && otherSelect.value) {
                        errorElement.textContent = '';
                    }

                    if (!field.value) {
                        otherSelect.classList.remove('is-invalid');
                    }
                }

                if (!fieldId.startsWith('horario_') && errorElement) {
                    errorElement.textContent = '';
                }

                if (fieldId === 'departamento') {
                    municipioSelect.classList.remove('is-invalid');
                    document.getElementById('error-municipio').textContent = '';
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
                    municipioSelect.disabled = false;
                    municipioSelect.appendChild(new Option('-- Seleccione un municipio --', ''));

                    const municipios = municipiosData[selectedDepto].sort();

                    municipios.forEach(municipio => {
                        const isSelected = municipio === oldMunicipio;
                        const option = new Option(municipio, municipio, false, isSelected);
                        municipioSelect.appendChild(option);
                    });
                } else {
                    municipioSelect.disabled = true;
                    municipioSelect.appendChild(new Option('-- Seleccione primero un departamento --', ''));
                }
            }

            function updateCodigo() {
                // En edición, el código es de solo lectura. Solo se usa para limpiar el error si se manipula el campo nombre.
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

            function populateTimeSelects() {
                const hourOptions = generateHourOptions();
                const minuteOptions = generateMinuteOptions();

                // 🛑 Recuperar valores antiguos o del modelo $terminal
                const oldApertura = "{{ old('horario_apertura', $terminal->horario_apertura) }}";
                const oldCierre = "{{ old('horario_cierre', $terminal->horario_cierre) }}";

                // Los valores antiguos se separan en Hora y Minuto (si el formato es HH:MM)
                const oldAperturaHora = oldApertura.substring(0, 2);
                const oldAperturaMinuto = oldApertura.substring(3, 5);
                const oldCierreHora = oldCierre.substring(0, 2);
                const oldCierreMinuto = oldCierre.substring(3, 5);

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

                // Llenar Apertura
                fillSelect(horarioAperturaHoraSelect, hourOptions, oldAperturaHora, 'Hora');
                fillSelect(horarioAperturaMinutoSelect, minuteOptions, oldAperturaMinuto, 'Min');

                // Llenar Cierre
                fillSelect(horarioCierreHoraSelect, hourOptions, oldCierreHora, 'Hora');
                fillSelect(horarioCierreMinutoSelect, minuteOptions, oldCierreMinuto, 'Min');

                updateHiddenFields();
            }

            // 🚀 Inicialización
            populateTimeSelects();

            if (departamentoSelect.value) {
                loadMunicipios();
            }

            // 📐 Redimensionamiento de Textareas
            direccionTextarea.addEventListener('input', autoResize);
            descripcionTextarea.addEventListener('input', autoResize);
            if (direccionTextarea.value) autoResize.call(direccionTextarea);
            if (descripcionTextarea.value) autoResize.call(descripcionTextarea);

            // 👂 Event Listeners para Ubicación y Código
            departamentoSelect.addEventListener('change', loadMunicipios);
            municipioSelect.addEventListener('change', updateCodigo);
            nombreInput.addEventListener('input', updateCodigo);

            // Registrar listeners para horarios y limpieza de errores
            [
                horarioAperturaHoraSelect,
                horarioAperturaMinutoSelect,
                horarioCierreHoraSelect,
                horarioCierreMinutoSelect
            ].forEach(select => {
                select.addEventListener('change', updateHiddenFields);
                select.addEventListener('change', function() { clearError(this); });
            });

            // Listeners para campos regulares
            document.querySelectorAll(
                'input:not([type="hidden"]):not([readonly]), select:not([id^="horario_apertura_"]):not([id^="horario_cierre_"]), textarea'
            ).forEach(field => {
                field.addEventListener('input', function() { clearError(this); });
                field.addEventListener('change', function() { clearError(this); });
            });


            // 🛑 Lógica para la Validación del Formulario al Enviar (Validación Cliente)
            terminalForm.addEventListener('submit', function(event) {
                let isValid = true;
                let firstInvalidField = null;

                updateHiddenFields();

                // Limpiar errores antes de revalidar
                document.querySelectorAll(
                    'input:not([type="hidden"]):not([readonly]), select, textarea'
                ).forEach(field => clearError(field));

                const requiredFields = terminalForm.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    let message = '';
                    const value = field.value.trim();
                    const fieldId = field.id;
                    let isFieldInvalid = false;

                    let horaSelect, minutoSelect;
                    if (fieldId.startsWith('horario_')) {
                        horaSelect = document.getElementById(fieldId.startsWith('horario_apertura') ? 'horario_apertura_hora' : 'horario_cierre_hora');
                        minutoSelect = document.getElementById(fieldId.startsWith('horario_apertura') ? 'horario_apertura_minuto' : 'horario_cierre_minuto');
                    }


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

                    if (isFieldInvalid) {
                        isValid = false;
                        if (!fieldId.startsWith('horario_')) {
                            showError(field, message);
                        } else if (fieldId.endsWith('_hora')) {
                            if (!horaSelect.value) showError(field, message);
                        }

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
