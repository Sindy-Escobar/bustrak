@extends('layouts.layoutadmin')

@section('content')
    <style>
        .reembolsos-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .header-card {
            background: linear-gradient(135deg, #5cb3ff 0%, #1e63b8 100%);
            border-radius: 16px;
            padding: 40px;
            color: white;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-card h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-crear {
            padding: 10px 24px;
            background: white;
            color: #1e63b8;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-crear:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 20px;
        }

        .filtros {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .filtros select {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }

        .filtros button {
            padding: 10px 20px;
            background: #5cb3ff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .badge-procesado {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-entregado {
            background: #d4edda;
            color: #155724;
        }

        .badge-completado {
            background: #cce5ff;
            color: #004085;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
    </style>

    <div class="reembolsos-container">
        <div class="header-card">
            <h1>
                <i class="fas fa-undo-alt"></i>
                Procesar Reembolsos
            </h1>
            <a href="{{ route('admin.reembolsos.crear') }}" class="btn-crear">
                <i class="fas fa-plus"></i>Nuevo Reembolso
            </a>
        </div>

        <div class="card">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filtros -->
            <div class="filtros">
                <form method="GET" action="{{ route('admin.reembolsos') }}" style="display: flex; gap: 15px;">
                    <select name="estado">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="procesado" {{ $estado === 'procesado' ? 'selected' : '' }}>Procesado</option>
                        <option value="entregado" {{ $estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                        <option value="completado" {{ $estado === 'completado' ? 'selected' : '' }}>Completado</option>
                    </select>
                    <button type="submit">Filtrar</button>
                </form>
            </div>

            <!-- Tabla -->
            @if($reembolsos->count() > 0)
                <table>
                    <thead>
                    <tr>
                        <th>Código Reembolso</th>
                        <th>Cliente</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reembolsos as $reembolso)
                        <tr>
                            <td><strong>{{ $reembolso->codigo_reembolso }}</strong></td>
                            <td>{{ $reembolso->usuario->name ?? 'N/A' }}</td>
                            <td>L. {{ number_format($reembolso->monto_reembolso, 2) }}</td>
                            <td>{{ ucfirst($reembolso->metodo_pago) }}</td>
                            <td>
                                    <span class="badge badge-{{ $reembolso->estado }}">
                                        {{ ucfirst($reembolso->estado) }}
                                    </span>
                            </td>
                            <td>{{ $reembolso->fecha_procesamiento ? $reembolso->fecha_procesamiento->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.reembolsos.comprobante', $reembolso->id) }}" class="btn" style="padding: 6px 12px; background: #5cb3ff; color: white; border: none; border-radius: 6px; text-decoration: none;">
                                    Ver Comprobante
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Paginación -->
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div>Mostrando {{ $reembolsos->firstItem() }} - {{ $reembolsos->lastItem() }} de {{ $reembolsos->total() }}</div>
                    <div>{{ $reembolsos->appends(request()->all())->links() }}</div>
                </div>
            @else
                <p style="text-align: center; color: #999;">No hay reembolsos registrados</p>
            @endif
        </div>
    </div>
@endsection
