@extends('layouts.apps')

@section('title', 'Gestión de Terminales - BusTrak')

@section('content')

    {{-- Estilos específicos para esta vista (CSS Incrustado) --}}
    <style>
        :root {
            --primary-color: #1e63b8; /* Azul corporativo */
            --secondary-color: #f7f7f7; /* Fondo claro */
            --success-color: #17a2b8; /* Verde/Azul para botones de acción */
            --danger-color: #dc3545; /* Rojo para eliminar */
            --shadow-light: 0 4px 15px rgba(0, 0, 0, 0.08);
            --border-radius: 8px;
        }

        /* Contenedor principal */
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            padding: 30px;
            margin-top: 20px;
        }

        /* Encabezado y Botón */
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--secondary-color);
        }

        .header-content h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .create-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 8px rgba(30, 99, 184, 0.4);
        }

        .create-btn:hover {
            background-color: #154c8f;
            transform: translateY(-2px);
        }

        /* Tabla */
        .terminal-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 600px; /* Asegura la legibilidad en escritorio */
        }

        .terminal-table th, .terminal-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .terminal-table th {
            background-color: var(--secondary-color);
            color: #555;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .terminal-table tr:hover {
            background-color: #fcfcfc;
        }

        .no-data {
            text-align: center !important;
            font-style: italic;
            color: #888;
            padding: 30px 0 !important;
        }

        /* Acciones */
        .actions {
            /* Permite que los botones se apilen o floten */
            white-space: nowrap;
        }

        .actions a {
            /* --- CAMBIOS CLAVE AQUÍ: Aumento de tamaño --- */
            padding: 10px 15px; /* Más padding para mayor área de clic */
            min-width: 45px;    /* Ancho mínimo para consistencia */
            text-align: center; /* Centrar el ícono */
            /* ------------------------------------------- */

            border-radius: 4px;
            text-decoration: none;
            font-size: 1rem; /* Aumento de fuente para íconos */
            font-weight: 600;
            margin-right: 5px;
            transition: background-color 0.2s;
            display: inline-block;
            margin-bottom: 5px; /* Espacio para apilar en móvil */
        }

        .view-btn {
            background-color: #007bff; /* Azul de info */
            color: white;
        }
        .view-btn:hover { background-color: #0056b3; }

        .edit-btn {
            background-color: #ffc107; /* Amarillo de advertencia */
            color: #333;
        }
        .edit-btn:hover { background-color: #e0a800; }

        /* Estilo de Horario */
        .terminal-table td small {
            display: block;
            color: #888;
            font-size: 0.85rem;
        }

        /* Paginación */
        .pagination-container {
            text-align: center;
            margin-top: 20px;
        }

        .pagination {
            display: inline-flex;
            list-style: none;
            padding: 0;
            justify-content: center;
            margin: 0;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .pagination li {
            margin: 0;
            border-right: 1px solid #ddd;
        }

        .pagination li:last-child {
            border-right: none;
        }

        .pagination li span, .pagination li a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: var(--primary-color);
            background: white;
            transition: background-color 0.2s;
        }

        .pagination li a:hover {
            background-color: var(--secondary-color);
        }

        .pagination li.active span {
            background-color: var(--primary-color);
            color: white;
            border: 1px solid var(--primary-color);
            font-weight: 700;
        }

        /* Adaptación Responsiva */
        @media (max-width: 768px) {
            .card {
                padding: 20px 15px;
            }
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            .header-content h2 {
                margin-bottom: 15px;
            }
            .table-responsive {
                overflow-x: auto;
            }
            .terminal-table th, .terminal-table td {
                padding: 10px;
                font-size: 0.9rem;
            }

            /* Ajuste de botones en móvil para que se apilen si es necesario */
            .actions {
                white-space: normal;
            }
            .actions a {
                padding: 8px 10px;
                font-size: 0.3rem;
                min-width: 40px;
            }
        }
    </style>
    {{-- Fin de Estilos --}}


    <div class="container">
        {{-- Mensajes de Sesión --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="header-content">
                <h2>Gestión de Terminales</h2>
                <a href="{{ route('terminales.create') }}" class="create-btn">
                    <i class="fas fa-plus me-1"></i>
                    Nueva Terminal
                </a>
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
                            <td>
                                {{ $terminal->nombre }}
                                <small>{{ $terminal->descripcion ? Str::limit($terminal->descripcion, 30) : 'Sin descripción' }}</small>
                            </td>
                            <td>
                                {{ $terminal->ciudad }},
                                <strong>{{ $terminal->departamento }}</strong>
                            </td>
                            <td>
                                <i class="fas fa-phone-alt"></i> {{ $terminal->telefono }}<br>
                                <small><i class="fas fa-envelope"></i> {{ $terminal->correo }}</small>
                            </td>
                            <td>
                                @if ($terminal->horario_apertura && $terminal->horario_cierre)
                                    {{ \Carbon\Carbon::parse($terminal->horario_apertura)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($terminal->horario_cierre)->format('g:i A') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="actions">
                                <a href="{{ route('terminales.show', $terminal) }}" class="view-btn" title="Ver Detalles">
                                    <i class="fas fa-eye">Ver</i>
                                </a>
                                <a href="{{ route('terminales.edit', $terminal) }}" class="edit-btn" title="Editar Terminal">
                                    <i class="fas fa-edit">Editar</i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-data">
                                <i class="fas fa-box-open me-2"></i>
                                No se encontraron terminales registradas. ¡Crea la primera!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div> {{-- Fin table-responsive --}}

            {{-- 🔹 Paginación Laravel --}}
            @if ($terminales->hasPages())
                <div class="pagination-container">
                    <ul class="pagination">
                        {{-- Anterior --}}
                        @if (!$terminales->onFirstPage())
                            <li><a href="{{ $terminales->previousPageUrl() }}" rel="prev">&laquo; Anterior</a></li>
                        @endif

                        {{-- Números --}}
                        @foreach ($terminales->getUrlRange(1, $terminales->lastPage()) as $page => $url)
                            @if ($page == $terminales->currentPage())
                                <li class="active"><span>{{ $page }}</span></li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Siguiente --}}
                        @if ($terminales->hasMorePages())
                            <li><a href="{{ $terminales->nextPageUrl() }}" rel="next">Siguiente &raquo;</a></li>
                        @endif
                    </ul>
                </div>
            @endif

        </div> {{-- Fin card --}}
    </div> {{-- Fin container --}}

    {{-- Asegúrate de que tu layout base tenga cargado Font Awesome para los íconos --}}
@endsection
