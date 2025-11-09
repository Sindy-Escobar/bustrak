@extends('layouts.apps')

@section('title', 'Editar Terminal - BusTrak')

@section('content')
    <div class="container">
        {{-- Aplicando estilo y redondeo consistente con el formulario de creación --}}
        <div class="card shadow-lg border-0 rounded-3">
            {{-- CARD HEADER: Título principal --}}
            <div class="card-header bg-white border-bottom p-4">
                <h2 style="margin:0; color:#1e63b8; font-weight:700; font-size:2rem;">
                    <i class="fas fa-edit me-3"></i>
                    Editar Terminal: {{ $terminal->nombre }}
                </h2>
            </div>

            {{-- CARD BODY: Formulario --}}
            <div class="card-body p-4 p-md-5">
                <form id="terminalForm" action="{{ route('terminales.update', $terminal->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- 1. DATOS DE UBICACIÓN --}}
                    <h5 class="mb-3 mt-2 text-primary"><i class="fas fa-map-marker-alt me-2"></i>1. Datos de Ubicación</h5>
                    <hr class="mt-0 mb-4">
                    <div class="row g-4">
                        {{-- Nombre --}}
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre de la Terminal</label>
                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre', $terminal->nombre) }}"
                                class="form-control @error('nombre') is-invalid @enderror rounded-3"
                                maxlength="100"
                                required
                            >
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="error-nombre" class="error-message text-danger"></div>
                        </div>
                        {{-- Departamento --}}
                        <div class="col-md-6">
                            <label for="departamento" class="form-label">Departamento</label>
                            <select id="departamento" name="departamento" class="form-select @error('departamento') is-invalid @enderror rounded-3" required>
                                <option value="">-- Seleccione un departamento --</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto }}" {{ old('departamento', $terminal->departamento) == $depto ? 'selected' : '' }}>
                                        {{ $depto }}
                                    </option>
                                @endforeach
                            </select>
                            @error('departamento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="error-departamento" class="error-message text-danger"></div>
                        </div>
                        {{-- Municipio --}}
                        <div class="col-md-6">
                            <label for="municipio" class="form-label">Municipio</label>
                            {{-- El script JS lo llenará y seleccionará el valor correcto --}}
                            <select id="municipio" name="municipio" class="form-select @error('municipio') is-invalid @enderror rounded-3" required>
                                <option value="">-- Seleccione un municipio --</option>
                            </select>
                            @error('municipio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="error-municipio" class="error-message text-danger"></div>
                        </div>
                        {{-- Código (Readonly) --}}
                        <div class="col-md-6">
                            <label for="codigo" class="form-label">Código (generado automáticamente)</label>
                            <input
                                type="text"
                                id="codigo"
                                name="codigo"
                                value="{{ old('codigo', $terminal->codigo) }}"
                                class="form-control bg-light text-muted rounded-3"
                                readonly
                            >
                            <div id="error-codigo" class="error-message text-danger"></div>
                        </div>
                        {{-- Dirección --}}
                        <div class="col-12">
                            <label for="direccion" class="form-label">Dirección exacta</label>
                            <textarea
                                id="direccion"
                                name="direccion"
                                class="form-control @error('direccion') is-invalid @enderror rounded-3"
                                maxlength="150"
                                rows="2"
                                required
                            >{{ old('direccion', $terminal->direccion) }}</textarea>
                            @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="error-direccion" class="error-message text-danger"></div>
                        </div>
                    </div>

                    {{-- 2. INFORMACIÓN DE CONTACTO --}}
                    <h5 class="mb-3 mt-5 text-primary"><i class="fas fa-address-book me-2"></i>2. Información de Contacto</h5>
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
                                class="form-control @error('telefono') is-invalid @enderror rounded-3"
                                maxlength="8"
                                required
                            >
                            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="error-telefono" class="error-message text-danger"></div>
                        </div>
                        {{-- Correo --}}
                        <div class="col-md-6">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input
                                type="email"
                                id="correo"
                                name="correo"
                                value="{{ old('correo', $terminal->correo) }}"
                                class="form-control @error('correo') is-invalid @enderror rounded-3"
                                maxlength="50"
                                required
                            >
                            @error('correo')
                            @php
                                // Traducción manual de errores de Laravel conocidos
                                $errorMessage = $message;
                                if (str_contains($message, 'has already been taken')) {
                                    $errorMessage = 'Este correo ya está registrado para otra terminal.';
                                }
                            @endphp
                            <div class="invalid-feedback">{{ $errorMessage }}</div>
                            @enderror
                            <div id="error-correo" class="error-message text-danger"></div>
                        </div>
                    </div>

                    {{-- 3. HORARIOS Y DESCRIPCIÓN --}}
                    <h5 class="mb-3 mt-5 text-primary"><i class="fas fa-clock me-2"></i>3. Horarios y Detalles</h5>
                    <hr class="mt-0 mb-4">
                    <div class="row g-4">
                        {{-- Horario Apertura --}}
                        <div class="col-md-3">
                            <label class="form-label">Horario de apertura</label>
                            <div class="input-group time-picker-custom @error('horario_apertura') is-invalid-picker @enderror" id="picker-apertura">
                                <input type="hidden" id="horario_apertura" name="horario_apertura" value="{{ old('horario_apertura', $terminal->horario_apertura) }}" required data-target-id="picker-apertura">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                <select class="form-select hour-select border-0"></select>
                                <span class="input-group-text time-separator">:</span>
                                <select class="form-select minute-select border-0"></select>
                                <select class="form-select ampm-select border-start-0 border-end-0"></select>
                            </div>
                            @error('horario_apertura')
                            @php
                                $errorMessage = $message;
                                if (str_contains($message, 'match the format H:i')) {
                                    $errorMessage = 'El formato del horario de apertura es incorrecto. Debe ser HH:MM.';
                                }
                            @endphp
                            <div class="invalid-feedback d-block">{{ $errorMessage }}</div>
                            @enderror
                            <div id="error-horario_apertura" class="error-message text-danger"></div>
                        </div>
                        {{-- Horario Cierre --}}
                        <div class="col-md-3">
                            <label class="form-label">Horario de cierre</label>
                            <div class="input-group time-picker-custom @error('horario_cierre') is-invalid-picker @enderror" id="picker-cierre">
                                <input type="hidden" id="horario_cierre" name="horario_cierre" value="{{ old('horario_cierre', $terminal->horario_cierre) }}" required data-target-id="picker-cierre">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                <select class="form-select hour-select border-0"></select>
                                <span class="input-group-text time-separator">:</span>
                                <select class="form-select minute-select border-0"></select>
                                <select class="form-select ampm-select border-start-0 border-end-0"></select>
                            </div>
                            @error('horario_cierre')
                            @php
                                $errorMessage = $message;
                                if (str_contains($message, 'be a date after horario apertura')) {
                                    $errorMessage = 'El horario de cierre debe ser posterior al horario de apertura.';
                                }
                            @endphp
                            <div class="invalid-feedback d-block">{{ $errorMessage }}</div>
                            @enderror
                            <div id="error-horario_cierre" class="error-message text-danger"></div>
                        </div>
                        {{-- Descripción --}}
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción y notas adicionales</label>
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                class="form-control @error('descripcion') is-invalid @enderror rounded-3"
                                rows="3"
                                required
                            >{{ old('descripcion', $terminal->descripcion) }}</textarea>
                            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div id="error-descripcion" class="error-message text-danger"></div>
                        </div>
                    </div>

                    {{-- Botones de Acción (CUADRADOS) --}}
                    <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                        <a href="{{ route('terminales.index') }}" class="btn btn-secondary rounded-2 px-4 shadow-sm">
                            <i class="fas fa-arrow-left me-1"></i>Volver a la lista
                        </a>
                        <button type="submit" class="btn btn-success rounded-2 px-4 shadow">
                            <i class="fas fa-save me-2"></i>Actualizar Terminal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const terminalForm = document.getElementById('terminalForm');
            const departamentoSelect = document.getElementById('departamento');
            const municipioSelect = document.getElementById('municipio');
            const codigoInput = document.getElementById('codigo');
            const nombreInput = document.getElementById('nombre');

            const direccionTextarea = document.getElementById('direccion');
            const descripcionTextarea = document.getElementById('descripcion');
            const telefonoInput = document.getElementById('telefono');
            const correoInput = document.getElementById('correo');

            const horarioAperturaInput = document.getElementById('horario_apertura');
            const horarioCierreInput = document.getElementById('horario_cierre');

            // Valores iniciales de la terminal para la carga inicial/persistencia
            const initialDepto = "{{ old('departamento', $terminal->departamento) }}";
            const initialMuni = "{{ old('municipio', $terminal->municipio) }}";

            // Asegúrate de que $municipios esté disponible desde Laravel
            const municipiosData = @json($municipios ?? []);

            // --- FUNCIONES DE MANEJO DE ERRORES Y ESTILOS ---

            function showError(field, message) {
                const fieldId = field.id;
                const errorElement = document.getElementById(`error-${fieldId}`);

                if (field.classList.contains('form-control') || field.classList.contains('form-select')) {
                    field.classList.add('is-invalid');
                }

                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.add('d-block');
                }

                if (field.type === 'hidden' && (fieldId === 'horario_apertura' || fieldId === 'horario_cierre')) {
                    const pickerId = field.getAttribute('data-target-id');
                    const picker = document.getElementById(pickerId);
                    if (picker) {
                        picker.classList.add('is-invalid-picker');
                    }
                }
            }


            function clearError(field) {
                const fieldId = field.id;
                const errorElement = document.getElementById(`error-${fieldId}`);

                field.classList.remove('is-invalid');

                if (errorElement) {
                    errorElement.textContent = '';
                    errorElement.classList.remove('d-block');
                }

                if (field.type === 'hidden' && (fieldId === 'horario_apertura' || fieldId === 'horario_cierre')) {
                    const pickerId = field.getAttribute('data-target-id');
                    const picker = document.getElementById(pickerId);
                    if (picker) {
                        picker.classList.remove('is-invalid-picker');
                    }
                }
            }

            function autoResize() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            }

            // --- LÓGICA DE VALIDACIÓN CLIENTE ---

            function validateForm() {
                let isValid = true;

                // 1. Limpiar todos los errores antes de revalidar
                terminalForm.querySelectorAll('.is-invalid, .is-invalid-picker').forEach(el => el.classList.remove('is-invalid', 'is-invalid-picker'));
                terminalForm.querySelectorAll('.invalid-feedback.d-block').forEach(el => el.classList.remove('d-block')); // Limpiar errores del servidor
                terminalForm.querySelectorAll('.error-message').forEach(el => {
                    el.textContent = '';
                    el.classList.remove('d-block');
                });


                // Definiciones de campos en español para mensajes
                const requiredFields = [
                    { id: 'nombre', name: 'Nombre de la Terminal' },
                    { id: 'departamento', name: 'Departamento' },
                    { id: 'municipio', name: 'Municipio' },
                    { id: 'direccion', name: 'Dirección exacta' },
                    { id: 'telefono', name: 'Teléfono' },
                    { id: 'correo', name: 'Correo electrónico' },
                    { id: 'horario_apertura', name: 'Horario de apertura' },
                    { id: 'horario_cierre', name: 'Horario de cierre' },
                    { id: 'descripcion', name: 'Descripción' }
                ];

                // A. Validar campos requeridos
                requiredFields.forEach(item => {
                    const field = document.getElementById(item.id);

                    if (!field.value || !field.value.trim()) {
                        showError(field, `El campo '${item.name}' es obligatorio.`);
                        isValid = false;
                    }
                });

                // B. Validación específica de formato y longitud

                const nombreValue = nombreInput.value.trim();
                if (nombreValue && nombreValue.length < 3) {
                    showError(nombreInput, 'El Nombre debe tener al menos 3 caracteres.');
                    isValid = false;
                }

                const telefonoValue = telefonoInput.value.trim();
                const telefonoRegex = /^\d{8}$/;
                if (telefonoValue) {
                    if (!telefonoRegex.test(telefonoValue)) {
                        showError(telefonoInput, 'El Teléfono debe contener exactamente 8 dígitos numéricos.');
                        isValid = false;
                    }
                }

                const correoValue = correoInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (correoValue) {
                    if (!emailRegex.test(correoValue)) {
                        showError(correoInput, 'El Correo Electrónico debe ser una dirección válida (ej. usuario@dominio.com).');
                        isValid = false;
                    }
                }

                // C. VALIDACIÓN DE LÓGICA DE HORARIOS
                const aperturaTime = horarioAperturaInput.value;
                const cierreTime = horarioCierreInput.value;

                if (aperturaTime && cierreTime) {
                    // El formato es HH:MM (24h)
                    if (cierreTime <= aperturaTime) {
                        showError(horarioCierreInput, 'El Horario de Cierre debe ser posterior al Horario de Apertura.');
                        isValid = false;
                    }
                }

                // 3. Enfocar el primer campo inválido
                if (!isValid) {
                    const firstInvalid = terminalForm.querySelector('.is-invalid, .is-invalid-picker');
                    if (firstInvalid) {
                        setTimeout(() => {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 0);
                    }
                }

                return isValid;
            }


            // --- LÓGICA DE MUNICIPIOS Y CÓDIGO ---
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
                }

                codigoInput.value = generatedCode;
            }

            function loadMunicipios() {
                const selectedDepto = departamentoSelect.value;
                municipioSelect.innerHTML = '<option value="">-- Seleccione un municipio --</option>';
                municipioSelect.disabled = true;

                clearError(municipioSelect);

                if (selectedDepto && municipiosData[selectedDepto]) {
                    municipioSelect.disabled = false;

                    municipiosData[selectedDepto].forEach(m => {
                        const option = new Option(m, m);
                        municipioSelect.appendChild(option);
                    });

                    // Asegurar que el municipio guardado (o old) se seleccione
                    if (initialMuni) {
                        municipioSelect.value = initialMuni;
                    }
                }
                updateCodigo();
            }

            // --- LÓGICA DE HORARIO 12h A 24h ---
            function formatTimeComponent(value) {
                return String(value).padStart(2, '0');
            }

            function convertTo24Hour(hour, minute, ampm) {
                let h = parseInt(hour, 10);
                const m = formatTimeComponent(minute);

                if (ampm === 'PM' && h < 12) {
                    h += 12;
                } else if (ampm === 'AM' && h === 12) {
                    h = 0;
                }
                return `${formatTimeComponent(h)}:${m}`;
            }

            function updateHiddenTime(container) {
                const hourSelect = container.querySelector('.hour-select');
                const minuteSelect = container.querySelector('.minute-select');
                const ampmSelect = container.querySelector('.ampm-select');
                const hiddenInput = container.querySelector('input[type="hidden"]');

                if (hourSelect.value && minuteSelect.value && ampmSelect.value) {
                    hiddenInput.value = convertTo24Hour(
                        hourSelect.value,
                        minuteSelect.value,
                        ampmSelect.value
                    );
                    clearError(hiddenInput);

                    // Forzar la revalidación de la lógica de horarios
                    if(horarioAperturaInput.value && horarioCierreInput.value) {
                        setTimeout(validateForm, 0);
                    }
                } else {
                    hiddenInput.value = '';
                }
            }

            function populateTimeSelects(containerId) {
                const container = document.getElementById(containerId);
                if (!container) return;

                const hourSelect = container.querySelector('.hour-select');
                const minuteSelect = container.querySelector('.minute-select');
                const hiddenInput = container.querySelector('input[type="hidden"]');
                const ampmSelect = container.querySelector('.ampm-select');

                // Llenar horas (1 a 12)
                for (let i = 1; i <= 12; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = i;
                    hourSelect.appendChild(option);
                }

                // Llenar minutos (en intervalos de 5)
                for (let i = 0; i < 60; i += 5) {
                    const minuteValue = formatTimeComponent(i);
                    const option = document.createElement('option');
                    option.value = minuteValue;
                    option.textContent = minuteValue;
                    minuteSelect.appendChild(option);
                }

                // Opciones de AM/PM
                ampmSelect.innerHTML = `
                <option value="AM">AM</option>
                <option value="PM">PM</option>
            `;

                // Establecer valor inicial a partir de old() o del modelo
                const initialValue24hr = hiddenInput.value;
                if (initialValue24hr) {
                    const [hour24, minute] = initialValue24hr.split(':').map(val => parseInt(val, 10));

                    let hour12 = hour24 % 12 || 12;
                    let ampm = hour24 < 12 || hour24 === 24 ? 'AM' : 'PM';

                    hourSelect.value = hour12;
                    minuteSelect.value = formatTimeComponent(minute);
                    ampmSelect.value = ampm;

                    // Importante: Asegurar que el valor HH:MM se establezca correctamente en el hidden input
                    updateHiddenTime(container);
                } else {
                    // Si no hay valor guardado, establecer un valor predeterminado seguro
                    hourSelect.value = 8;
                    minuteSelect.value = '00';
                    ampmSelect.value = 'AM';
                    updateHiddenTime(container);
                }

                [hourSelect, minuteSelect, ampmSelect].forEach(select => {
                    select.addEventListener('change', () => updateHiddenTime(container));
                    select.addEventListener('input', () => updateHiddenTime(container));
                    select.addEventListener('focus', () => clearError(hiddenInput));
                });
            }

            // --- INICIALIZACIÓN Y LISTENERS ---

            terminalForm.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });

            const fields = terminalForm.querySelectorAll('input:not([readonly]):not([type="hidden"]), textarea, select:not(.hour-select):not(.minute-select):not(.ampm-select)');
            fields.forEach(field => {
                field.addEventListener('input', () => clearError(field));
                field.addEventListener('change', () => clearError(field));
            });

            departamentoSelect.addEventListener('change', loadMunicipios);
            municipioSelect.addEventListener('change', updateCodigo);
            nombreInput.addEventListener('input', updateCodigo);

            direccionTextarea.addEventListener('input', autoResize);
            descripcionTextarea.addEventListener('input', autoResize);
            if (direccionTextarea.value) autoResize.call(direccionTextarea);
            if (descripcionTextarea.value) autoResize.call(descripcionTextarea);

            populateTimeSelects('picker-apertura');
            populateTimeSelects('picker-cierre');

            loadMunicipios();
        });
    </script>

    <style>
        /* --- ESTILOS VISUALES MEJORADOS PARA EL PICKER DE HORA --- */

        .time-picker-custom {
            border: 1px solid #ced4da;
            border-radius: 0.3rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.2s ease-in-out;
            font-size: 0.9rem;
        }

        .time-picker-custom:focus-within {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .time-picker-custom .input-group-text {
            background-color: #f8f9fa;
            color: #495057;
            border: none;
            padding: 0.3rem 0.5rem;
        }

        .time-picker-custom .time-separator {
            background-color: transparent;
            color: #495057;
            font-weight: bold;
            padding: 0.3rem 0.2rem;
        }

        .time-picker-custom .form-select {
            border: none !important;
            background-color: transparent;
            padding: 0.3rem 0.5rem;
            cursor: pointer;
            text-align: center;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-size: 0.7rem;
            background-position: right 0.4rem center;
            background-repeat: no-repeat;
            flex-grow: 1;
        }

        .is-invalid-picker {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        }

        .invalid-feedback.d-block {
            display: block !important;
        }

        .error-message.d-block {
            display: block !important;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: var(--bs-danger);
        }
    </style>
@endsection
