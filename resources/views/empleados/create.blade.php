<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empleado - BusTrak</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        h1 { color: #667eea; margin-bottom: 20px; text-align:center; }
        .form-group { margin-bottom: 20px; }
        label { display:block; margin-bottom:8px; color:#555; font-weight:600; font-size:14px; }
        input { width:100%; padding:12px 15px; border:2px solid #e0e0e0; border-radius:10px; font-size:16px; transition:border-color 0.3s; }
        input:focus { outline:none; border-color:#667eea; }
        .error-message { color:#e74c3c; font-size:13px; margin-top:5px; }
        .btn { width:100%; padding:14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border:none; border-radius:10px; font-size:16px; font-weight:600; cursor:pointer; transition: transform 0.2s; }
        .btn:hover { transform: translateY(-2px); }
        .cancel-link { text-align:center; margin-top:15px; font-size:14px; }
        .cancel-link a { color:#667eea; text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
<div class="form-container">
    <h1>Registrar Empleado</h1>

    @if ($errors->any())
        <div class="error-message" style="margin-bottom:15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('empleados.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required>
        </div>
        <div class="form-group">
            <label>Apellido</label>
            <input type="text" name="apellido" value="{{ old('apellido') }}" required>
        </div>
        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni" value="{{ old('dni') }}" required pattern="\d{13}" title="Debe ser un número de 13 dígitos">
        </div>
        <div class="form-group">
            <label>Cargo</label>
            <input type="text" name="cargo" value="{{ old('cargo') }}" required>
        </div>
        <div class="form-group">
            <label>Fecha de ingreso</label>
            <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" required>
        </div>
        <button type="submit" class="btn">Registrar</button>
    </form>

    <div class="cancel-link">
        <a href="{{ route('empleados.index') }}">Cancelar</a>
    </div>
</div>
</body>
</html>
