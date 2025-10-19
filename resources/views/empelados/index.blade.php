<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Empleados</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('empleados.create') }}" class="btn btn-primary mb-3">Registrar Empleado</a>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Cargo</th>
            <th>Fecha ingreso</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse($empleados as $empleado)
            <tr>
                <td>{{ $empleado->id }}</td>
                <td>{{ $empleado->nombre }}</td>
                <td>{{ $empleado->apellido }}</td>
                <td>{{ $empleado->dni }}</td>
                <td>{{ $empleado->cargo }}</td>
                <td>{{ $empleado->fecha_ingreso }}</td>
                <td>{{ $empleado->estado }}</td>
                <td>
                    <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm {{ $empleado->estado === 'Activo' ? 'btn-danger' : 'btn-success' }}">
                            {{ $empleado->estado === 'Activo' ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No hay empleados registrados</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
