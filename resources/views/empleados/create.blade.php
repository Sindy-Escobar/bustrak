<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empleado - BusTrak</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height:100vh; display:flex; justify-content:center; align-items:center; padding:20px; }
        .container { background:white; border-radius:20px; padding:40px; width:100%; max-width:450px; box-shadow:0 20px 60px rgba(0,0,0,0.3); }
        h1 { text-align:center; color:#667eea; margin-bottom:20px; }
        .form-group { margin-bottom:20px; }
        label { display:block; margin-bottom:8px; font-weight:600; color:#555; }
        input[type="text"], input[type="date"] { width:100%; padding:12px; border:2px solid #e0e0e0; border-radius:10px; font-size:16px; transition:border-color 0.3s; }
        input:focus { border-color:#667eea; outline:none; }
        .error-message { color:#e74c3c; font-size:13px; margin-top:5px; }
        .btn { width:100%; padding:14px; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:white; border:none; border-radius:10px; font-size:16px; font-weight:600; cursor:pointer; transition:transform 0.2s; }
        .btn:hover { transform:translateY(-2px); }
        .rol-container { display:flex; gap:15px; margin-top:10px; }
        .rol-card { flex:1; padding:12px; border-radius:12px; text-align:center; cursor:pointer; border:2px solid #ccc; background:#f5f5f5; transition:0.2s; user-select:none; }
        .rol-card.selected { background:#667eea; color:#fff; border-color:#667eea; }
        .cancel-link { display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Registrar Empleado</h1>

    @if ($errors->any())
        <div class="error-message">
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

        <div class="form-group">
            <label>Rol</label>
            <div class="rol-container">
                <div class="rol-card {{ old('rol')=='Empleado' ? 'selected' : '' }}" data-value="Empleado">Empleado
                    <input type="radio" name="rol" value="Empleado" style="display:none;" {{ old('rol')=='Empleado' ? 'checked' : '' }}>
                </div>
                <div class="rol-card {{ old('rol')=='Administrador' ? 'selected' : '' }}" data-value="Administrador">Administrador
                    <input type="radio" name="rol" value="Administrador" style="display:none;" {{ old('rol')=='Administrador' ? 'checked' : '' }}>
                </div>
            </div>
            @error('rol')
            <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn">Registrar</button>
    </form>

    <a href="{{ route('empleados.index') }}" class="cancel-link">Cancelar</a>
</div>

<script>
    const rolCards = document.querySelectorAll('.rol-card');
    rolCards.forEach(card => {
        card.addEventListener('click', () => {
            rolCards.forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            card.querySelector('input').checked = true;
        });
    });
</script>
</body>
</html>
