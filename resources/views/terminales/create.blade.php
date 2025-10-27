<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de terminal - BusTrak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 24px;
        }
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea,
        .form-group select:not(.hour-select):not(.minute-select):not(.ampm-select) {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        [readonly] {
            background-color: #eee;
            cursor: not-allowed;
            color: #555;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus:not(.hour-select):not(.minute-select):not(.ampm-select) {
            border-color: #667eea;
            outline: none;
        }


        .time-12hr-picker {
            display: flex;
            align-items: center;
            gap: 5px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 0;
            background-color: white;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .time-12hr-picker[style*="border-color: rgb(229, 62, 62)"] {
            border-color: #e53e3e !important;
        }
        .form-group:has(.time-12hr-picker:focus-within) .time-12hr-picker {
            border-color: #667eea;
            outline: none;
        }
        .time-12hr-picker select {
            padding: 8px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            background: transparent;
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
            appearance: menulist;
        }

        .time-12hr-picker select:focus {
            outline: none;
            background: #f0f4ff;
        }
        .time-12hr-picker span {
            font-weight: bold;
            color: #444;
        }

        .hour-select, .minute-select {
            width: 30%;
        }

        .ampm-select {
            width: 40%;
            border-left: 1px solid #eee;
            padding-left: 10px;
        }
        .form-group textarea {
            resize: none;
            overflow: hidden;
            padding: 12px;
            line-height: 1.5;
        }
        #direccion {
            min-height: 44px;
        }
        #descripcion {
            min-height: 100px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
        }
        .submit-btn, .back-btn {
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: opacity 0.3s, background 0.3s;
        }
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: 10px;
        }
        .submit-btn:hover {
            opacity: 0.9;
        }
        .reset-btn {
            background: #e0e0e0;
            color: #333;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .reset-btn:hover {
            background: #ccc;
        }
        .back-btn {
            background: #ffffff;
            color: #667eea;
            border: 1px solid #667eea;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .back-btn:hover {
            background: #f0f4ff;
        }

        .grid-half {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 4px;
            font-weight: 500;
        }

        .action-group {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <h1> BusTrak</h1>
    <div class="nav-links">
        <a href="/">Inicio</a>
        <a href="{{ route('terminales.index') }}">Terminales</a>
        <a href="{{ route('terminales.create') }}">Registrar</a>
        <button type="button" class="logout-btn">Cerrar Sesión</button>
    </div>
</nav>

<div class="container">
    <div class="card">
        <header style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #667eea;">Registro de nueva terminal</h2>
            <p style="color: #666;">Ingresa los detalles de la nueva terminal de autobuses.</p>
        </header>

        <form id="terminalForm" action="{{ route('terminales.store') }}" method="POST" novalidate>
            @csrf

            <div class="grid-half">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" maxlength="100" required>
                    <div id="error-nombre" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="departamento">Departamento</label>
                    <select id="departamento" name="departamento" required>
                        <option value="">-- Seleccione un departamento --</option>
                        {{-- Simulación de datos para demostración --}}
                        <option value="Atlántida" {{ old('departamento') == 'Atlántida' ? 'selected' : '' }}>Atlántida</option>
                        <option value="Colón" {{ old('departamento') == 'Colón' ? 'selected' : '' }}>Colón</option>
                        <option value="Cortés" {{ old('departamento') == 'Cortés' ? 'selected' : '' }}>Cortés</option>
                        <option value="Francisco Morazán" {{ old('departamento') == 'Francisco Morazán' ? 'selected' : '' }}>Francisco Morazán</option>
                        {{-- Fin simulación --}}
                        @isset($departamentos)
                            @foreach($departamentos as $depto)
                                <option value="{{ $depto }}" {{ old('departamento') == $depto ? 'selected' : '' }}>
                                    {{ $depto }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                    <div id="error-departamento" class="error-message"></div>
                </div>
            </div>

            <div class="grid-half">
                <div class="form-group">
                    <label for="municipio">Municipio</label>
                    <select id="municipio" name="municipio" required disabled>
                        <option value="">-- Seleccione primero un departamento --</option>
                    </select>
                    <div id="error-municipio" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="codigo">Código (generado automáticamente)</label>
                    <input type="text" id="codigo" name="codigo" value="{{ old('codigo') }}" maxlength="10" required readonly>
                    <div id="error-codigo" class="error-message"></div>
                </div>
            </div>


            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea id="direccion" name="direccion" maxlength="150" required>{{ old('direccion') }}</textarea>
                <div id="error-direccion" class="error-message"></div>
            </div>

            <div class="grid-half">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono') }}" maxlength="8" required>
                    <div id="error-telefono" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" value="{{ old('correo') }}" maxlength="50" required>
                    <div id="error-correo" class="error-message"></div>
                </div>
            </div>

            <div class="grid-half">
                <div class="form-group">
                    <label>Horario de apertura</label>
                    <div class="time-12hr-picker" id="picker-apertura">
                        <input type="hidden" id="horario_apertura" name="horario_apertura" value="{{ old('horario_apertura') }}" required data-target-id="picker-apertura">
                        <select class="hour-select"></select>
                        <span>:</span>
                        <select class="minute-select"></select>
                        <select class="ampm-select">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    <div id="error-horario_apertura" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label>Horario de cierre</label>
                    <div class="time-12hr-picker" id="picker-cierre">
                        <input type="hidden" id="horario_cierre" name="horario_cierre" value="{{ old('horario_cierre') }}" required data-target-id="picker-cierre">
                        <select class="hour-select"></select>
                        <span>:</span>
                        <select class="minute-select"></select>
                        <select class="ampm-select">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    <div id="error-horario_cierre" class="error-message"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required>{{ old('descripcion') }}</textarea>
                <div id="error-descripcion" class="error-message"></div>
            </div>

            <div class="form-actions">
                <button type="button" class="back-btn" onclick="window.location.href='{{ route('terminales.index') }}'">
                    ← Volver a la lista
                </button>

                <div class="action-group">
                    <button type="button" class="reset-btn">Limpiar formulario</button>
                    <button type="submit" class="submit-btn">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const terminalForm = document.getElementById('terminalForm');
        const departamentoSelect = document.getElementById('departamento');
        const municipioSelect = document.getElementById('municipio');
        const codigoInput = document.getElementById('codigo');
        const nombreInput = document.getElementById('nombre');
        const resetButton = document.querySelector('.reset-btn');

        const direccionTextarea = document.getElementById('direccion');
        const descripcionTextarea = document.getElementById('descripcion');

        function showError(field, message) {
            const fieldId = field.id;
            const errorElement = document.getElementById(`error-${fieldId}`);
            if (errorElement) {
                errorElement.textContent = message;

                if (field.type === 'hidden' && (fieldId === 'horario_apertura' || fieldId === 'horario_cierre')) {
                    const pickerId = field.getAttribute('data-target-id');
                    const picker = document.getElementById(pickerId);
                    if (picker) {
                        picker.style.borderColor = '#e53e3e';
                    }
                } else {
                    field.style.borderColor = '#e53e3e';
                }
            }
        }

        function clearError(field) {
            const fieldId = field.id;
            const errorElement = document.getElementById(`error-${fieldId}`);
            if (errorElement) {
                errorElement.textContent = '';

                if (field.type === 'hidden' && (fieldId === 'horario_apertura' || fieldId === 'horario_cierre')) {
                    const pickerId = field.getAttribute('data-target-id');
                    const picker = document.getElementById(pickerId);
                    if (picker) {
                        picker.style.borderColor = '';
                    }
                } else {
                    field.style.borderColor = '';
                }
            }
        }

        function autoResize() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        }

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
            'Lempira': ['Gracias', 'Belen', 'Candelaria', 'Cololaca', 'Erandique', 'Gualcince', 'Guarita', 'La Campa', 'La Iguala', 'Las Flores', 'La Unión', 'La Virtud', 'Lepaera', 'Mapulaca', 'Piraera', 'Rendero', 'San Andrés', 'San Francisco', 'San Juan de Cajacas', 'San Manuel Colohete', 'San Rafael', 'San Sebastián', 'Santa Cruz', 'Talgua', 'Tambla', 'Tomala', 'Valladolid', 'Virginia', 'San Antonio'],
            'Ocotepeque': ['Ocotepeque', 'Belén Gualcho', 'Concepción', 'Dolores Merendón', 'Fraternidad', 'La Encarnación', 'La Labor', 'Lucerna', 'Mercedes', 'San Fernando', 'San Francisco del Valle', 'San Jorge', 'San Marcos', 'Santa Fe', 'Sinuapa', 'Sensenti'],
            'Olancho': ['Juticalpa', 'Campamento', 'Catacamas', 'Concordia', 'Dulce Nombre de Culmí', 'El Rosario', 'Esquipulas del Norte', 'Gualaco', 'Guarizama', 'Jano', 'La Unión', 'Mangulile', 'Manto', 'Salama', 'San Esteban', 'San Francisco de la Paz', 'Santa María del Real', 'Silca', 'Yocón', 'Patuca', 'Guayape'],
            'Santa Bárbara': ['Santa Bárbara', 'Azacualpa', 'Atima', 'Ceguaca', 'Concepción del Norte', 'Concepción del Sur', 'Chinda', 'El Níspero', 'Gualala', 'Ilama', 'Las Vegas', 'Macuelizo', 'Naranjito', 'Nueva Frontera', 'Petoa', 'Protección', 'Quimistán', 'San Francisco de Ojuera', 'San Luis', 'San Marcos', 'San Nicolás', 'San Pedro Zacapa', 'Santa Rita', 'Trinidad', 'Santa Cruz de Yojoa'],
            'Valle': ['Nacaome', 'Amapala', 'Alianza', 'Aramecina', 'Caridad', 'Goascorán', 'Langue', 'San Francisco de Coray', 'San Lorenzo'],
            'Yoro': ['Yoro', 'Arenal', 'El Negrito', 'El Progreso', 'Jocón', 'Morazán', 'Olanchito', 'Santa Rita', 'Sulaco', 'Victoria', 'Yorito']
        };

        function loadMunicipios() {
            const selectedDepto = departamentoSelect.value;
            municipioSelect.innerHTML = '';

            if (selectedDepto && municipiosData[selectedDepto]) {
                municipioSelect.disabled = false;
                municipioSelect.appendChild(new Option('-- Seleccione un municipio --', ''));

                const municipios = municipiosData[selectedDepto].sort();

                municipios.forEach(municipio => {

                    const isSelected = municipio === "{{ old('municipio') }}";
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
                const deptoCode = depto.substring(0, 3).toUpperCase();
                const muniCode = muni.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
                const nameHash = nombre.replace(/\s/g, '').substring(0, 2).toUpperCase() || 'XX';
                generatedCode = `${deptoCode}-${muniCode}-${nameHash}`;
            }

            codigoInput.value = generatedCode;
            clearError(codigoInput);
        }

        function formatTimeComponent(value) {
            return String(value).padStart(2, '0');
        }

        function convertTo24Hour(hour, minute, ampm) {
            let h = parseInt(hour, 10);
            const m = formatTimeComponent(minute);

            if (ampm === 'PM' && h < 12) {
                h += 12;
            } else if (ampm === 'AM' && h === 12) {
                h = 0; // Medianoche (12 AM) es 00
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


            for (let i = 1; i <= 12; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                hourSelect.appendChild(option);
            }


            for (let i = 0; i < 60; i += 5) {
                const minuteValue = formatTimeComponent(i);
                const option = document.createElement('option');
                option.value = minuteValue;
                option.textContent = minuteValue;
                minuteSelect.appendChild(option);
            }

            const initialValue24hr = hiddenInput.value;
            if (initialValue24hr) {
                const [hour24, minute] = initialValue24hr.split(':').map(val => parseInt(val, 10));

                let hour12 = hour24 % 12 || 12;
                let ampm = hour24 < 12 ? 'AM' : 'PM';

                hourSelect.value = hour12;
                minuteSelect.value = formatTimeComponent(minute);
                ampmSelect.value = ampm;
            } else {

                hourSelect.value = 8;
                minuteSelect.value = '00';
                ampmSelect.value = 'AM';
                updateHiddenTime(container); // Sincroniza el hidden input
            }


            [hourSelect, minuteSelect, ampmSelect].forEach(select => {
                select.addEventListener('change', () => updateHiddenTime(container));
                select.addEventListener('focus', () => clearError(hiddenInput));
            });
        }

        populateTimeSelects('picker-apertura');
        populateTimeSelects('picker-cierre');


        departamentoSelect.addEventListener('change', loadMunicipios);
        municipioSelect.addEventListener('change', updateCodigo);
        nombreInput.addEventListener('input', updateCodigo);


        direccionTextarea.addEventListener('input', autoResize);
        descripcionTextarea.addEventListener('input', autoResize);

        const fields = document.querySelectorAll(
            'input[type="text"], input[type="email"], textarea'
        );
        fields.forEach(field => {
            field.addEventListener('keydown', function(event) {
                if (event.keyCode === 32 && this.selectionStart === 0) {
                    event.preventDefault();
                }
            });
            field.addEventListener('input', function() {
                clearError(this);
            });
        });

        departamentoSelect.addEventListener('change', function() {
            clearError(this);
            clearError(municipioSelect);
        });
        municipioSelect.addEventListener('change', function() {
            clearError(this);
        });


        if (resetButton) {
            resetButton.addEventListener('click', function() {
                terminalForm.querySelectorAll(
                    'input[type="text"], input[type="email"], textarea'
                ).forEach(field => {
                    field.value = '';
                });

                departamentoSelect.value = "";
                municipioSelect.disabled = true;
                municipioSelect.innerHTML = '<option value="">-- Seleccione primero un departamento --</option>';
                codigoInput.value = '';


                ['picker-apertura', 'picker-cierre'].forEach(id => {
                    const container = document.getElementById(id);
                    if (container) {
                        container.querySelector('.hour-select').value = 8;
                        container.querySelector('.minute-select').value = '00';
                        container.querySelector('.ampm-select').value = 'AM';
                        updateHiddenTime(container); // Sincroniza el campo oculto
                    }
                });


                terminalForm.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                terminalForm.querySelectorAll('[required], input[type="text"], input[type="email"], textarea, select').forEach(el => el.style.borderColor = '');
                terminalForm.querySelectorAll('.time-12hr-picker').forEach(el => el.style.borderColor = '');



                direccionTextarea.style.height = 'auto';
                descripcionTextarea.style.height = '100px';
            });
        }


        terminalForm.addEventListener('submit', function(event) {
            let isValid = true;
            let firstInvalidField = null;


            terminalForm.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            terminalForm.querySelectorAll('[required], input[type="text"], input[type="email"], textarea, select').forEach(el => el.style.borderColor = '');
            terminalForm.querySelectorAll('.time-12hr-picker').forEach(el => el.style.borderColor = '');


            const requiredFields = terminalForm.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                let message = '';
                const value = field.value.trim();
                const fieldId = field.id;


                if ((fieldId === 'horario_apertura' || fieldId === 'horario_cierre') && value && !/^\d{2}:\d{2}$/.test(value)) {
                    message = 'El horario es obligatorio y debe ser válido.';
                    isValid = false;
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                }

                if (value === '' || (field.tagName === 'SELECT' && value === '')) {
                    const labelText = field.closest('.form-group').querySelector('label').textContent;
                    message = `El campo ${labelText.toLowerCase()} es obligatorio.`;

                    isValid = false;
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else if (fieldId === 'correo' && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value)) {
                    message = `Por favor, introduce un correo electrónico válido.`;
                    isValid = false;
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else if (fieldId === 'telefono') {
                    const phoneRegex = /^(3|8|9)\d{7}$/;
                    if (!phoneRegex.test(value)) {
                        message = `El número de teléfono debe tener 8 dígitos y comenzar con 3, 8 o 9.`;
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    }
                }
                if (message) {
                    showError(field, message);
                }
            });

            if (!isValid) {
                event.preventDefault();
                if (firstInvalidField) {

                    if (firstInvalidField.type === 'hidden') {
                        const pickerId = firstInvalidField.getAttribute('data-target-id');
                        document.getElementById(pickerId).querySelector('.hour-select').focus();
                    } else {
                        firstInvalidField.focus();
                    }
                }
            }
        });

        if (direccionTextarea.value) autoResize.call(direccionTextarea);
        if (descripcionTextarea.value) autoResize.call(descripcionTextarea);

        loadMunicipios();
        updateCodigo();
    });
</script>
</body>
</html>
