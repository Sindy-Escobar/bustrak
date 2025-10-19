<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Registrar Empleado</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('empleados.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Apellido</label>
            <input type="text" name="apellido" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>DNI</label>
            <input type="text" name="dni" class="form-control" required pattern="\d{13}" title="Debe ser un número de 13 dígitos">
        </div>
        <div class="mb-3">
            <label>Cargo</label>
            <input type="text" name="cargo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Fecha ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
