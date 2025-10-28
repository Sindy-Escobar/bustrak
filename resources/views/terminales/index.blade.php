<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Terminales - BusTrak</title>
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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 20px;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .header-content h2 {
            color: #667eea;
        }
        .create-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #8BC34A 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.3s;
        }
        .create-btn:hover {
            opacity: 0.9;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .terminal-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .terminal-table th, .terminal-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .terminal-table th {
            background-color: #f8f8f8;
            color: #444;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
        }
        .terminal-table tr:hover {
            background-color: #fafafa;
        }
        .terminal-table tbody tr:last-child td {
            border-bottom: none;
        }
        .actions a {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            margin-right: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .view-btn {
            background-color: #667eea;
            color: white;
        }
        .view-btn:hover {
            background-color: #5a6cd4;
        }
        .edit-btn {
            background-color: #ffc107;
            color: #333;
        }
        .edit-btn:hover {
            background-color: #e0a800;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #888;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 600;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        /* 🔹 Estilos de paginación */
        .pagination {
            display: inline-flex;
            list-style: none;
            gap: 8px;
            padding: 0;
            margin-top: 25px;
        }
        .pagination li a, .pagination li span {
            display: block;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #667eea;
            background-color: #f1f1f1;
            font-weight: 600;
            transition: background 0.3s, color 0.3s;
        }
        .pagination li.active span {
            background-color: #667eea;
            color: white;
        }
        .pagination li a:hover {
            background-color: #667eea;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <h1>BusTrak</h1>
    <div class="nav-links">
        <a href="/">Inicio</a>
        <a href="#">Mi Perfil</a>
        <a href="#">Mis Reservas</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </div>
</nav>

<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="header-content">
            <h2>Gestión de Terminales</h2>
            <a href="{{ route('terminales.create') }}" class="create-btn">+ Nueva Terminal</a>
        </div>

        <div class="table-responsive">
            <table class="terminal-table">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Contacto</th>
                    <th>Horario</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($terminales as $terminal)
                    <tr>
                        <td>{{ $terminal->codigo }}</td>
                        <td>{{ $terminal->nombre }}</td>
                        <td>{{ $terminal->ciudad }}, {{ $terminal->departamento }}</td>
                        <td>
                            {{ $terminal->telefono }}<br>
                            <small>{{ $terminal->correo }}</small>
                        </td>
                        <td>
                            @if ($terminal->horario_apertura && $terminal->horario_cierre)
                                {{ \Carbon\Carbon::parse($terminal->horario_apertura)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($terminal->horario_cierre)->format('H:i') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('terminales.show', $terminal) }}" class="view-btn">Ver</a>
                            <a href="{{ route('terminales.edit', $terminal) }}" class="edit-btn">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data">No se encontraron terminales registradas.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{-- 🔹 Paginación Laravel --}}
            @if ($terminales->hasPages())
                <div style="text-align: center; margin-top: 20px;">
                    <ul class="pagination">
                        {{-- Página anterior --}}
                        @if ($terminales->onFirstPage())
                            <li><span>&laquo;</span></li>
                        @else
                            <li><a href="{{ $terminales->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                        @endif

                        {{-- Números de página --}}
                        @foreach ($terminales->getUrlRange(1, $terminales->lastPage()) as $page => $url)
                            @if ($page == $terminales->currentPage())
                                <li class="active"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Página siguiente --}}
                        @if ($terminales->hasMorePages())
                            <li><a href="{{ $terminales->nextPageUrl() }}" rel="next">&raquo;</a></li>
                        @else
                            <li><span>&raquo;</span></li>
                        @endif
                    </ul>
                </div>
@endif


</body>
</html>


