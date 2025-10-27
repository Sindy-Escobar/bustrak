<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar terminal - BusTrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Reutilizamos tus estilos de create.blade */
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
        .navbar h1 { font-size: 24px; }
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a {
            color: white; text-decoration: none; padding: 8px 16px;
            border-radius: 6px; transition: background 0.3s;
        }
        .nav-links a:hover { background: rgba(255,255,255,0.2); }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white; border: none; padding: 10px 20px;
            border-radius: 8px; cursor: pointer;
        }
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .card {
            background: white; border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px; margin-bottom: 20px;
        }
        .form-group { margin-bottom: 20px; }
        label { font-weight: 600; margin-bottom: 8px; display: block; color: #444; }
        input, select, textarea {
            width: 100%; padding: 12px; border: 1px solid #ccc;
            border-radius: 8px; font-size: 16px; transition: border-color 0.3s;
        }
        input:focus, textarea:focus, select:focus {
            border-color: #667eea; outline: none;
        }
        textarea { resize: none; line-height: 1.5; }
        .grid-half { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-actions { display: flex; justify-content: space-between; align-items: center; padding-top: 20px; }
        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; padding: 12px 25px; border-radius: 8px;
            font-size: 16px; font-weight: 600; cursor: pointer;
        }
        .submit-btn:hover { opacity: 0.9; }
        .back-btn {
            background: #ffffff; color: #667eea;
            border: 1px solid #667eea; border-radius: 8px;
            padding: 12px 25px; text-decoration: none; font-weight: 600;
        }
        .back-btn:hover { background: #f0f4ff; }
        .error-message { color: #e53e3e; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body>
<nav class="navbar">
    <h1>BusTrak</h1>
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
            <h2 style="color: #667eea;">Editar terminal: {{ $terminal->nombre }}</h2>
            <p style="color: #666;">Modifica los detalles de la terminal seleccionada.</p>
        </header>

        <form id="terminalForm" action="{{ route('terminales.update', $terminal->id) }}" method="POST" >
            @csrf
            @method('PUT')

            <div class="grid-half">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre"
                           value="{{ old('nombre', $terminal->nombre) }}" maxlength="100" required>
                    <div id="error-nombre" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="departamento">Departamento</label>
                    <select id="departamento" name="departamento" required>
                        <option value="">-- Seleccione un departamento --</option>
                        @foreach($departamentos as $depto)
                            <option value="{{ $depto }}" {{ old('departamento', $terminal->departamento) == $depto ? 'selected' : '' }}>
                                {{ $depto }}
                            </option>
                        @endforeach
                    </select>
                    <div id="error-departamento" class="error-message"></div>
                </div>
            </div>

            <div class="grid-half">
                <div class="form-group">
                    <label for="municipio">Municipio</label>
                    <select id="municipio" name="municipio" required>
                        <option value="{{ $terminal->municipio }}">{{ $terminal->municipio }}</option>
                    </select>
                    <div id="error-municipio" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input type="text" id="codigo" name="codigo"
                           value="{{ old('codigo', $terminal->codigo) }}" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea id="direccion" name="direccion" maxlength="150" required>{{ old('direccion', $terminal->direccion) }}</textarea>
                <div id="error-direccion" class="error-message"></div>
            </div>

            <div class="grid-half">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono"
                           value="{{ old('telefono', $terminal->telefono) }}" maxlength="8" required>
                    <div id="error-telefono" class="error-message"></div>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo"
                           value="{{ old('correo', $terminal->correo) }}" maxlength="50" required>
                    <div id="error-correo" class="error-message"></div>
                </div>
            </div>

            <div class="grid-half">
                <div class="form-group">
                    <label for="horario_apertura">Horario de apertura</label>
                    <input type="time" id="horario_apertura" name="horario_apertura"
                           value="{{ old('horario_apertura', $terminal->horario_apertura) }}" required>
                </div>
                <div class="form-group">
                    <label for="horario_cierre">Horario de cierre</label>
                    <input type="time" id="horario_cierre" name="horario_cierre"
                           value="{{ old('horario_cierre', $terminal->horario_cierre) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" required>{{ old('descripcion', $terminal->descripcion) }}</textarea>
                <div id="error-descripcion" class="error-message"></div>
            </div>

            <div class="form-actions">
                <a href="{{ route('terminales.index') }}" class="back-btn">← Volver a la lista</a>
                <button type="submit" class="submit-btn">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Reutilizamos la lógica de municipios y código generado
    document.addEventListener('DOMContentLoaded', function() {
        const departamentoSelect = document.getElementById('departamento');
        const municipioSelect = document.getElementById('municipio');
        const nombreInput = document.getElementById('nombre');
        const codigoInput = document.getElementById('codigo');

        const municipiosData = @json($municipios ?? []);

        function loadMunicipios() {
            const selectedDepto = departamentoSelect.value;
            municipioSelect.innerHTML = '';

            if (selectedDepto && municipiosData[selectedDepto]) {
                municipiosData[selectedDepto].forEach(m => {
                    const option = new Option(m, m);
                    if (m === "{{ old('municipio', $terminal->municipio) }}") option.selected = true;
                    municipioSelect.appendChild(option);
                });
            } else {
                municipioSelect.appendChild(new Option('-- Seleccione un departamento --', ''));
            }
            updateCodigo();
        }

        function updateCodigo() {
            const depto = departamentoSelect.value;
            const muni = municipioSelect.value;
            const nombre = nombreInput.value;
            if (depto && muni && nombre) {
                const codigo = `${depto.substring(0,3).toUpperCase()}-${muni.substring(0,3).toUpperCase()}-${nombre.substring(0,2).toUpperCase()}`;
                codigoInput.value = codigo;
            }
        }

        departamentoSelect.addEventListener('change', loadMunicipios);
        municipioSelect.addEventListener('change', updateCodigo);
        nombreInput.addEventListener('input', updateCodigo);

        loadMunicipios();
    });
</script>
</body>
</html>
