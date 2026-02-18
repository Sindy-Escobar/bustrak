@extends('layouts.layoutadmin')

@section('content')
    <style>
        .page-container {
            padding: 30px;
            max-width: 1400px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a56db;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .page-title i {
            color: #1a56db;
        }

        .search-section {
            margin-bottom: 20px;
        }

        .search-label {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-label i {
            color: #1a56db;
        }

        .search-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-input-wrapper {
            flex: 1;
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .search-input {
            width: 100%;
            padding: 10px 10px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-buscar {
            padding: 10px 24px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-filtros {
            padding: 10px 24px;
            background: white;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-nuevo {
            padding: 10px 20px;
            background: white;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            text-decoration: none;
            float: right;
            margin-top: -55px;
        }

        .btn-nuevo:hover {
            background: #f5f5f5;
            color: #333;
        }

        .filtros-adicionales {
            background: #eef4ff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .filtros-title {
            font-weight: 700;
            color: #1a56db;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filtros-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .filtro-item label {
            font-weight: 700;
            font-size: 13px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
        }

        .filtro-item select,
        .filtro-item input {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 13px;
            background: white;
        }

        .table-wrapper {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #eef4ff;
        }

        th {
            padding: 14px 16px;
            text-align: left;
            font-weight: 700;
            font-size: 13px;
            color: #333;
            border-bottom: 1px solid #e0e0e0;
        }

        th i {
            margin-right: 6px;
            color: #555;
        }

        td {
            padding: 14px 16px;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fafafa;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pendiente { background: #fff3cd; color: #856404; }
        .badge-procesado { background: #d1ecf1; color: #0c5460; }
        .badge-entregado { background: #d4edda; color: #155724; }
        .badge-completado { background: #cce5ff; color: #004085; }

        .btn-ver {
            padding: 6px 16px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-ver:hover {
            background: #1648b8;
            color: white;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 60px;
            color: #999;
        }

        .pagination-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border-top: 1px solid #e0e0e0;
            font-size: 13px;
            color: #666;
        }
    </style>

    <div class="page-container">

        <!-- Título y botón nuevo -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div class="page-title">
                <i class="fas fa-undo-alt"></i> Reembolsos
            </div>
            <a href="{{ route('admin.reembolsos.crear') }}" class="btn-buscar">
                <i class="fas fa-plus"></i> Nuevo Reembolso
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Búsqueda -->
        <form method="GET" action="{{ route('admin.reembolsos') }}">
            <div class="search-section">
                <div class="search-label">
                    <i class="fas fa-search"></i> Búsqueda General
                </div>
                <div class="search-row">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" name="buscar" class="search-input" placeholder="Buscar por código o cliente" value="{{ request('buscar') }}">
                    </div>
                    <button type="submit" class="btn-buscar">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <button type="button" class="btn-filtros" onclick="toggleFiltros()">
                        <i class="fas fa-sliders-h"></i> Filtros
                    </button>
                </div>
            </div>

            <!-- Filtros Adicionales -->
            <div class="filtros-adicionales" id="filtrosAdicionales">
                <div class="filtros-title">
                    <i class="fas fa-filter"></i> Filtros Adicionales
                </div>
                <div class="filtros-grid">
                    <div class="filtro-item">
                        <label><i class="fas fa-toggle-on" style="color:#28a745;"></i> Estado</label>
                        <select name="estado">
                            <option value="">Todos los estados</option>
                            <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="procesado" {{ $estado === 'procesado' ? 'selected' : '' }}>Procesado</option>
                            <option value="entregado" {{ $estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="completado" {{ $estado === 'completado' ? 'selected' : '' }}>Completado</option>
                        </select>
                    </div>
                    <div class="filtro-item">
                        <label><i class="fas fa-wallet" style="color:#1a56db;"></i> Método de Pago</label>
                        <select name="metodo">
                            <option value="">Todos los métodos</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="credito">Crédito</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="filtro-item">
                        <label><i class="fas fa-calendar" style="color:#1a56db;"></i> Fecha</label>
                        <input type="date" name="fecha" value="{{ request('fecha') }}">
                    </div>
                </div>
            </div>
        </form>

        <!-- Tabla -->
        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> Código</th>
                    <th><i class="fas fa-user"></i> Cliente</th>
                    <th><i class="fas fa-coins"></i> Monto</th>
                    <th><i class="fas fa-wallet"></i> Método</th>
                    <th><i class="fas fa-toggle-on"></i> Estado</th>
                    <th><i class="fas fa-calendar"></i> Fecha</th>
                    <th><i class="fas fa-cog"></i> Acciones</th>
                </tr>
                </thead>
                <tbody>
                @if($reembolsos->count() > 0)
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
                                <a href="{{ route('admin.reembolsos.comprobante', $reembolso->id) }}" class="btn-ver">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; display: block; margin-bottom: 15px;"></i>
                                No hay reembolsos registrados
                            </div>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>

            @if($reembolsos->count() > 0)
                <div class="pagination-row">
                    <div>Mostrando {{ $reembolsos->firstItem() }} - {{ $reembolsos->lastItem() }} de {{ $reembolsos->total() }}</div>
                    <div>{{ $reembolsos->appends(request()->all())->links() }}</div>
                </div>
            @endif
        </div>

    </div>

    <script>
        function toggleFiltros() {
            const f = document.getElementById('filtrosAdicionales');
            f.style.display = f.style.display === 'none' ? 'block' : 'none';
        }
    </script>
@endsection
