@extends('layouts.layoutadmin')

@section('title', 'Registro de Terminal - BusTrak')

@section('content')

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- 📝 Contenedor Principal del Formulario --}}
    <div class="container px-0">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-4">
                <header>
                    <h2 class="mb-0" style="color:#1e63b8; font-weight:600; font-size:1.8rem;">
                        <i class="fas fa-bus me-2"></i>Registrar Terminal
                    </h2>
                </header>

                {{-- ➡️ Formulario de Registro --}}
                <div class="card-body p-4 p-md-5">
                <form id="terminalForm" action="{{ route('terminales.store') }}" method="POST" novalidate>
                    @csrf


                    {{-- 1️⃣ DATOS DE UBICACIÓN --}}
                    <h5 class="mb-3 mt-2" style="color:#1e63b8; font-weight:600; font-size:1.5rem;">
                        <i class="fas fa-map-marker-alt me-2"></i>1. Datos de ubicación
                    </h5>
                    <hr class="mt-0 mb-4">
                    <div class="row g-4">
                    {{-- Fila 1: Nombre y Código --}}

                        <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" maxlength="100" required
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
                                        <option value="{{ $depto }}" {{ old('departamento') == $depto ? 'selected' : '' }}>
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
                                <label for="codigo" class="form-label">Código (generado automáticamente)</label>
                                <input type="text" id="codigo" name="codigo" value="{{ old('codigo') }}" maxlength="10" required readonly
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
                                <textarea id="direccion" name="direccion" maxlength="150" required
                                          class="form-control @error('direccion') is-invalid @enderror">{{ old('direccion') }}</textarea>
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

                        {{-- Campo Teléfono en la vista Blade --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" maxlength="8" required
                                       class="form-control @error('telefono') is-invalid @enderror">
                                {{-- ESTE BLOQUE MUESTRA EL ERROR DEL SERVIDOR (ej. unique) --}}
                                @error('telefono')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                {{-- ESTE DIV MUESTRA EL ERROR DE VALIDACIÓN CLIENTE (ej. 8 dígitos) --}}
                                <div id="error-telefono" class="error-message"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo electrónico</label>
                                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" maxlength="50" required
                                       class="form-control @error('correo') is-invalid @enderror">
                                @error('correo')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div id="error-correo" class="error-message"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Fila 5: Horarios (SELECTORES SEPARADOS DE HORA Y MINUTO) --}}
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
                                <textarea id="descripcion" name="descripcion" required
                                          class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                                <div id="error-descripcion" class="error-message"></div>
                            </div>
                        </div>
                    </div>

                    {{-- ⚙️ Botones de Acción --}}
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('terminales.index') }}'">
                            <i class="fas fa-arrow-left me-2"></i> Volver a la lista
                        </button>

                        <div class="d-flex gap-2">
                            {{-- Botón Limpiar: Cambiado a btn-warning --}}
                            <button type="button" class="btn btn-warning reset-btn">
                                <i class="fas fa-undo me-2"></i> Limpiar formulario
                            </button>
                            <button type="submit" class="btn btn-primary submit-btn">
                                <i class="fas fa-save me-2"></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 🧠 Lógica JavaScript COMPLETA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 🏷️ Referencias a Elementos del DOM
            const terminalForm = document.getElementById('terminalForm');
            const departamentoSelect = document.getElementById('departamento');
            const municipioSelect = document.getElementById('municipio');
            const codigoInput = document.getElementById('codigo');
            const nombreInput = document.getElementById('nombre');
            const telefonoInput = document.getElementById('telefono');
            const correoInput = document.getElementById('correo');
            const resetButton = document.querySelector('.reset-btn');
            const direccionTextarea = document.getElementById('direccion');
            const descripcionTextarea = document.getElementById('descripcion');

            // ⏰ Referencias de los nuevos SELECTS de Hora/Minuto y los campos HIDDEN
            const horarioAperturaHoraSelect = document.getElementById('horario_apertura_hora');
            const horarioAperturaMinutoSelect = document.getElementById('horario_apertura_minuto');
            const horarioCierreHoraSelect = document.getElementById('horario_cierre_hora');
            const horarioCierreMinutoSelect = document.getElementById('horario_cierre_minuto');

            const horarioAperturaHidden = document.getElementById('horario_apertura_hidden');
            const horarioCierreHidden = document.getElementById('horario_cierre_hidden');


            // 🗺️ Data de Municipios por Departamento (Honduras)
            const municipiosData = {
                'Atlántida': ['La Ceiba', 'El Porvenir', 'Tela', 'San Francisco', 'Arizona', 'Esparta', 'Jutiapa', 'La Másica'],
                'Colón': ['Trujillo', 'Balfate', 'Iriona', 'Limón', 'Sabá', 'Santa Rosa de Aguán', 'Sonaguera', 'Tocoa', 'Bonito Oriental', 'Fe'],
                'Comayagua': ['Comayagua', 'Ajuterique', 'El Rosario', 'Esquías', 'Guanja', 'La Libertad', 'Lamaní', 'La Paz', 'Leyes', 'Meámbar', 'Minas de Oro', 'Ojo de Agua', 'San Jerónimo', 'San José de Comayagua', 'San José del Potrero', 'San Luis', 'San Sebastián', 'Siguatepeque', 'Taulabé', 'Villa de San Antonio', 'Las Lajas'],
                'Copán': ['Santa Rosa de Copán', 'Cabañas', 'Concepción', 'Copán Ruinas', 'Corquín', 'Dolores', 'Dulce Nombre', 'El Paraíso', 'Florida', 'La Unión', 'Leapaera', 'Lucerna', 'Nueva Arcadia', 'San Agustín', 'San Antonio', 'San Jerónimo', 'San José', 'San Juan de Opoa', 'San Nicolás', 'San Pedro de Copán', 'Santa Rita', 'Trinidad de Copán', 'Veracruz'],
                'Cortés': ['San Pedro Sula', 'Choloma', 'Puerto Cortés', 'La Lima', 'Omoa', 'San Antonio de Cortés', 'San Francisco de Yojoa', 'San Manuel', 'Villanueva', 'Potrerillos', 'Pimienta', 'Santa Cruz de Yojoa'],
                'Choluteca': ['Choluteca', 'Apacilagua', 'Concepción de María', 'El Corpus', 'El Triunfo', 'Marcovia', 'Morolica', 'Namasigüe', 'Orocuina', 'Pespire', 'San Antonio de Flores', 'San Isidro', 'San José', 'San Marcos de Colón', 'Santa Ana de Yusguare', 'Ciudad Choluteca'],
                'El Paraíso': ['Yuscarán', 'Alauca', 'Danlí', 'El Paraíso', 'Güinope', 'Jacaleapa', 'Liure', 'Morocelí', 'Oropolí', 'Potrerillos', 'San Antonio de Flores', 'San Lucas', 'San Matías', 'Soledad', 'Teupasenti', 'Vado Ancho', 'Trojes', 'Texiguat'],
                'Francisco Morazán': ['Distrito Central (Tegucigalpa y Comayagüela)', 'Alubarén', 'Cedros', 'Curarén', 'El Porvenir', 'Guaimaca', 'La Libertad', 'La Venta', 'Lepaterique', 'Maraita', 'Marale', 'Nueva Armenia', 'Ojojona', 'Orica', 'Reitoca', 'Sabana Grande', 'San Antonio de Oriente', 'San Buenaventura', 'San Ignacio', 'San Juan de Flores (Cantarranas)', 'San Miguelito', 'Santa Ana', 'Santa Lucía', 'Talanga', 'Tatumbla', 'Valle de Ángeles', 'Villa de San Francisco', 'Vallecillo'],
                'Gracias a Dios': ['Puerto Lempira', 'Brus Laguna', 'Ahuas', 'Juan Francisco Bulnes', 'Villeda Morales', 'Wampusirpi'],
                'Intibucá': ['La Esperanza', 'Camasca', 'Colomoncagua', 'Concepción', 'Dolores', 'Honduritas', 'Intibucá', 'Jesús de Otoro', 'Magdalena', 'Masaguara', 'San Antonio', 'San Francisco de Opalaca', 'San Isidro', 'San Juan', 'San Marco de la Sierra', 'San Miguelito', 'Santa Lucía', 'Yamaranguila', 'Yurique'],
                'Islas de la Bahía': ['Roatán', 'Guanaja', 'José Santos Guardiola', 'Utila'],
                'La Paz': ['La Paz', 'Aguanqueterique', 'Cabañas', 'Cane', 'Chinacla', 'Guajiquiro', 'Laura', 'Marcala', 'Mercedes de Oriente', 'Opatoro', 'San Antonio del Norte', 'San José', 'San Juan', 'San Pedro de Tutule', 'Santa Ana', 'Santa Elena', 'Santa María', 'Santiago de Puringla', 'Yarula'],
                'Lempira': ['Gracias', 'Belen', 'Candelaria', 'Cololaca', 'Erandique', 'Gualcince', 'Guarita', 'La Campa', 'La Iguala', 'Las Flores', 'La Unión', 'La Virtud', 'Lepaera', 'Mapulaca', 'Piraera', 'Rendero', 'San Andrés', 'San Francisco', 'San Juan de Cajacas', 'San Manuel Colohete', 'San Rafael', 'San Sebastián', 'Santa Cruz', 'Talgua', 'Tambla', 'Tomala', 'Tomala', 'Valladolid', 'Virginia', 'San Antonio'],
                'Ocotepeque': ['Ocotepeque', 'Belén Gualcho', 'Concepción', 'Dolores Merendón', 'Fraternidad', 'La Encarnación', 'La Labor', 'Lucerna', 'Mercedes', 'San Fernando', 'San Francisco del Valle', 'San Jorge', 'San Marcos', 'Santa Fe', 'Sinuapa', 'Sensenti'],
                'Olancho': ['Juticalpa', 'Campamento', 'Catacamas', 'Concordia', 'Dulce Nombre de Culmí', 'El Rosario', 'Esquipulas del Norte', 'Gualaco', 'Guarizama', 'Jano', 'La Unión', 'Mangulile', 'Manto', 'Salama', 'San Esteban', 'San Francisco de la Paz', 'Santa María del Real', 'Silca', 'Yocón', 'Patuca', 'Guayape'],
                'Santa Bárbara': ['Santa Bárbara', 'Azacualpa', 'Atima', 'Ceguaca', 'Concepción del Norte', 'Concepción del Sur', 'Chinda', 'El Níspero', 'Gualala', 'Ilama', 'Las Vegas', 'Macuelizo', 'Naranjito', 'Nueva Frontera', 'Petoa', 'Protección', 'Quimistán', 'San Francisco de Ojuera', 'San Luis', 'San Marcos', 'San Nicolás', 'San Pedro Zacapa', 'Santa Rita', 'Trinidad', 'Santa Cruz de Yojoa'],
                'Valle': ['Nacaome', 'Amapala', 'Alianza', 'Aramecina', 'Caridad', 'Goascorán', 'Langue', 'San Francisco de Coray', 'San Lorenzo'],
                'Yoro': ['Yoro', 'Arenal', 'El Negrito', 'El Progreso', 'Jocón', 'Morazán', 'Olanchito', 'Santa Rita', 'Sulaco', 'Victoria', 'Yorito']
            };

            // ❌ Funciones de Manejo de Errores (Mostrar/Limpiar) - ADAPTADAS A BOOTSTRAP
            function showError(field, message) {
                const fieldId = field.id;
                // Si el campo es un select de hora/minuto, apuntamos al ID del campo oculto (ej: horario_apertura) para el div de error
                const errorId = fieldId.startsWith('horario_') ? fieldId.substring(0, fieldId.lastIndexOf('_')) : fieldId;
                const errorElement = document.getElementById(`error-${errorId}`);

                if (errorElement) {
                    // Muestra el mensaje de error (solo en el div de error principal)
                    if (fieldId.endsWith('_minuto')) {
                        errorElement.innerHTML = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    } else if (!fieldId.startsWith('horario_')) {
                        errorElement.innerHTML = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    } else if (message === '') {
                        errorElement.textContent = ''; // Limpiar mensaje si solo se aplica estilo
                    }

                    // Añade la clase is-invalid al select actual
                    field.classList.add('is-invalid');
                }
            }

            function clearError(field) {
                const fieldId = field.id;
                const errorId = fieldId.startsWith('horario_') ? fieldId.substring(0, fieldId.lastIndexOf('_')) : fieldId;
                const errorElement = document.getElementById(`error-${errorId}`);

                if (errorElement) {
                    // Limpia el mensaje de error del div principal si se limpia el select de minuto
                    if (fieldId.endsWith('_minuto') || !fieldId.startsWith('horario_')) {
                        errorElement.textContent = '';
                    }
                }

                // Remueve la clase is-invalid de Bootstrap
                field.classList.remove('is-invalid');

                // Si es un select, también verifica si debe deshabilitar el select de municipio
                if (fieldId === 'departamento') {
                    // Si el departamento cambia, limpia el error del municipio también
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
                municipioSelect.innerHTML = '';
                clearError(municipioSelect);

                if (selectedDepto && municipiosData[selectedDepto]) {
                    municipioSelect.disabled = false;
                    municipioSelect.appendChild(new Option('-- Seleccione un municipio --', ''));

                    const municipios = municipiosData[selectedDepto].sort();
                    const oldMunicipio = "{{ old('municipio') }}";

                    municipios.forEach(municipio => {
                        const isSelected = municipio === oldMunicipio;
                        const option = new Option(municipio, municipio, false, isSelected);
                        municipioSelect.appendChild(option);
                    });
                } else {
                    municipioSelect.disabled = true;
                    municipioSelect.appendChild(new Option('-- Seleccione primero un departamento --', ''));
                }
                updateCodigo();
            }

            function updateCodigo() {
                const depto = departamentoSelect.value;
                const muni = municipioSelect.value;
                const nombre = nombreInput.value;
                let generatedCode = '';

                if (depto && muni) {
                    // Usar las tres primeras letras del departamento y municipio
                    const deptoCode = depto.substring(0, 3).toUpperCase();
                    const muniCode = muni.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
                    // Usar las dos primeras letras del nombre de la terminal
                    const nameHash = nombre.replace(/\s/g, '').substring(0, 2).toUpperCase() || 'XX';
                    generatedCode = `${deptoCode}-${muniCode}-${nameHash}`;
                }

                codigoInput.value = generatedCode;
                clearError(codigoInput);
            }

            // ⏱️ Funciones de Horario (SELECTORES HORA Y MINUTO con AM/PM)

            // Genera la lista completa de horas (0-23) con formato de 12h para el texto
            function generateHourOptions() {
                const options = [];
                for (let h = 0; h < 24; h++) {
                    const hour24 = String(h).padStart(2, '0'); // 00, 01, ..., 23 (para el valor)

                    let hour12 = h % 12 || 12; // 12, 01, ..., 11, 12, 01, ...
                    const ampm = h < 12 ? 'AM' : 'PM';
                    const text12 = `${String(hour12).padStart(2, '0')} ${ampm}`; // 01 AM, 12 PM, etc. (para el texto visible)

                    options.push({ value: hour24, text: text12 });
                }
                return options;
            }

            // Genera la lista de minutos (0, 5, 10, ..., 55)
            function generateMinuteOptions() {
                const options = [];
                // Intervalos de 5 minutos
                for (let m = 0; m < 60; m += 5) {
                    const minute = String(m).padStart(2, '0');
                    options.push({ value: minute, text: minute });
                }
                return options;
            }

            // Función para combinar los 4 selects en los 2 campos ocultos (HH:MM)
            function updateHiddenFields() {
                const ap_h = horarioAperturaHoraSelect.value;
                const ap_m = horarioAperturaMinutoSelect.value;
                const ci_h = horarioCierreHoraSelect.value;
                const ci_m = horarioCierreMinutoSelect.value;

                horarioAperturaHidden.value = (ap_h && ap_m) ? `${ap_h}:${ap_m}` : '';
                horarioCierreHidden.value = (ci_h && ci_m) ? `${ci_h}:${ci_m}` : '';

                // Limpiar errores del campo oculto si ya no están vacíos
                if (horarioAperturaHidden.value) {
                    clearError(horarioAperturaHoraSelect);
                    clearError(horarioAperturaMinutoSelect);
                    document.getElementById('error-horario_apertura').textContent = '';
                }
                if (horarioCierreHidden.value) {
                    clearError(horarioCierreHoraSelect);
                    clearError(horarioCierreMinutoSelect);
                    document.getElementById('error-horario_cierre').textContent = '';
                }
            }


            function populateTimeSelects() {
                const hourOptions = generateHourOptions();
                const minuteOptions = generateMinuteOptions();

                // Recuperar valores antiguos (asumiendo que vienen en formato HH:MM, ej: 08:00)
                const oldApertura = "{{ old('horario_apertura') }}";
                const oldCierre = "{{ old('horario_cierre') }}";

                // Los valores antiguos se separan en Hora y Minuto
                const oldAperturaHora = oldApertura.substring(0, 2);
                const oldAperturaMinuto = oldApertura.substring(3, 5);
                const oldCierreHora = oldCierre.substring(0, 2);
                const oldCierreMinuto = oldCierre.substring(3, 5);

                // Función auxiliar para llenar un select
                const fillSelect = (selectElement, options, oldValue, defaultText) => {
                    selectElement.innerHTML = `<option value="">${defaultText}</option>`;
                    options.forEach(option => {
                        const isSelected = option.value === oldValue;
                        const newOption = new Option(option.text, option.value, false, isSelected);
                        selectElement.appendChild(newOption);
                    });
                };

                // Llenar Apertura
                fillSelect(horarioAperturaHoraSelect, hourOptions, oldAperturaHora, 'Hora');
                fillSelect(horarioAperturaMinutoSelect, minuteOptions, oldAperturaMinuto, 'Min');

                // Llenar Cierre
                fillSelect(horarioCierreHoraSelect, hourOptions, oldCierreHora, 'Hora');
                fillSelect(horarioCierreMinutoSelect, minuteOptions, oldCierreMinuto, 'Min');

                // Inicializar campo oculto si hay valores antiguos
                updateHiddenFields();
            }

            // 🚀 Inicialización
            populateTimeSelects(); // Inicializa los 4 selects

            // Cargar municipios si hay un valor anterior
            if (departamentoSelect.value) {
                loadMunicipios();
            }

            // 👂 Event Listeners para Ubicación y Código
            departamentoSelect.addEventListener('change', loadMunicipios);
            municipioSelect.addEventListener('change', updateCodigo);
            nombreInput.addEventListener('input', updateCodigo);

            // Registrar listeners para actualizar campos ocultos y limpiar errores en los selects de hora
            [
                horarioAperturaHoraSelect,
                horarioAperturaMinutoSelect,
                horarioCierreHoraSelect,
                horarioCierreMinutoSelect
            ].forEach(select => {
                select.addEventListener('change', updateHiddenFields);
                select.addEventListener('input', updateHiddenFields); // Aunque select usa 'change', se mantiene por consistencia
            });


            // 👂 Event Listeners para Textareas
            direccionTextarea.addEventListener('input', autoResize);
            descripcionTextarea.addEventListener('input', autoResize);
            if (direccionTextarea.value) autoResize.call(direccionTextarea);
            if (descripcionTextarea.value) autoResize.call(descripcionTextarea);

            // 👂 Event Listeners para limpiar errores en inputs/textareas/selects
            // Excluimos los selects de horario aquí para que updateHiddenFields maneje la limpieza
            document.querySelectorAll(
                'input:not([type="hidden"]):not([readonly]), select:not([id^="horario_apertura_"]):not([id^="horario_cierre_"]), textarea'
            ).forEach(field => {
                field.addEventListener('input', function() { clearError(this); });
                field.addEventListener('change', function() { clearError(this); });
            });

            // 🗑️ Lógica para el botón de Limpiar Formulario
            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    terminalForm.reset();

                    // Limpieza específica para campos manipulados por JS
                    terminalForm.querySelectorAll(
                        'input[type="text"], input[type="email"], textarea'
                    ).forEach(field => { field.value = ''; });

                    // Resetear Ubicación
                    departamentoSelect.value = "";
                    municipioSelect.disabled = true;
                    municipioSelect.innerHTML = '<option value="">-- Seleccione primero un departamento --</option>';
                    codigoInput.value = '';

                    // Resetear Horarios
                    horarioAperturaHoraSelect.value = "";
                    horarioAperturaMinutoSelect.value = "";
                    horarioCierreHoraSelect.value = "";
                    horarioCierreMinutoSelect.value = "";
                    horarioAperturaHidden.value = "";
                    horarioCierreHidden.value = "";

                    // Limpiar estilos de error de Bootstrap y mensajes
                    terminalForm.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                    terminalForm.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));

                    // Resetear tamaño de textareas
                    direccionTextarea.style.height = 'auto';
                    descripcionTextarea.style.height = '100px';
                });
            }

            // 🛑 Lógica para la Validación del Formulario al Enviar (Validación Cliente)
            terminalForm.addEventListener('submit', function(event) {
                let isValid = true;
                let firstInvalidField = null;

                // 1. Asegurarse de que los campos ocultos están actualizados justo antes del envío
                updateHiddenFields();

                // **Solo limpiar los errores del lado del cliente**
                document.querySelectorAll(
                    'input:not([type="hidden"]):not([readonly]), select, textarea'
                ).forEach(field => clearError(field));

                // Incluir los 4 selects de hora/minuto en la verificación de obligatorios
                const requiredFields = terminalForm.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    let message = '';
                    const value = field.value.trim();
                    const fieldId = field.id;
                    let isFieldInvalid = false;

                    // Solo necesitamos la referencia de hora y minuto si estamos dentro de un campo de horario
                    let horaSelect, minutoSelect;
                    if (fieldId.startsWith('horario_')) {
                        horaSelect = document.getElementById(fieldId.startsWith('horario_apertura') ? 'horario_apertura_hora' : 'horario_cierre_hora');
                        minutoSelect = document.getElementById(fieldId.startsWith('horario_apertura') ? 'horario_apertura_minuto' : 'horario_cierre_minuto');
                    }


                    // 1.1 Validación de campos de Horario
                    if (fieldId.startsWith('horario_') && (fieldId.endsWith('_hora') || fieldId.endsWith('_minuto'))) {

                        // Solo validamos cuando estamos en el select de Hora (para evitar doble chequeo)
                        if (fieldId.endsWith('_hora')) {
                            if (!horaSelect.value || !minutoSelect.value) {
                                message = 'Debe seleccionar tanto la **hora** como el **minuto**.';
                                isFieldInvalid = true;

                                // Aplica el estilo rojo al select vacío correspondiente
                                if (!horaSelect.value) showError(horaSelect, '');
                                if (!minutoSelect.value) showError(minutoSelect, message); // Mostrar mensaje en el select de Minuto
                            }
                        }
                    }
                    // 1.2 Validación de otros campos vacíos
                    else if (!value) {
                        message = 'Este campo es **obligatorio**.';
                        isFieldInvalid = true;
                    }

                    // 2. Validación de Teléfono (8 dígitos)
                    else if (fieldId === 'telefono' && !/^\d{8}$/.test(value)) {
                        message = 'El teléfono debe contener exactamente **8 dígitos** numéricos.';
                        isFieldInvalid = true;
                    }

                    // 3. Validación de Correo electrónico
                    else if (fieldId === 'correo' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        message = 'Ingrese un **correo electrónico válido** (ej: nombre@dominio.com).';
                        isFieldInvalid = true;
                    }

                    // 4. Validación Horario Cierre vs Apertura (Usando campos ocultos)
                    const aperturaValue = horarioAperturaHidden.value;
                    const cierreValue = horarioCierreHidden.value;

                    // Solo validar la comparación cuando ambos campos ocultos tienen valor y estamos revisando un select de cierre
                    if (fieldId.startsWith('horario_cierre_') && cierreValue && aperturaValue) {
                        if (cierreValue <= aperturaValue) {
                            message = 'El horario de cierre debe ser **posterior** al de apertura.';
                            isFieldInvalid = true;
                            // Aplica el error a los selects de cierre
                            showError(horarioCierreHoraSelect, '');
                            showError(horarioCierreMinutoSelect, message); // Mostrar el mensaje en el Minuto
                        }
                    }

                    if (isFieldInvalid) {
                        isValid = false;
                        // Mostrar error si no es un select de hora/minuto, o si es el select de hora que falla la validación 1.1
                        if (!fieldId.startsWith('horario_')) {
                            showError(field, message);
                        } else if (fieldId.endsWith('_hora') && !horaSelect.value) {
                            showError(field, message); // Asegurar que el error se muestre para hora vacía
                        }

                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                    if (firstInvalidField) {
                        // Enfocar el primer campo inválido
                        firstInvalidField.focus();
                    }
                }
                // Si es válido, el formulario se envía automáticamente.
            });

        });
    </script>
@endsection
