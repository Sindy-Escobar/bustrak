@extends('layouts.layoutuser')
@section('title', 'Historial de Viajes')

@section('contenido')
    <div class="container mt-4">

        <h2 class="mb-4 text-primary"><i class="fas fa-history"></i> Historial de Viajes</h2>

        <!-- FILTROS -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="origen" class="form-control" placeholder="Origen" value="{{ request('origen') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="destino" class="form-control" placeholder="Destino" value="{{ request('destino') }}">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Estado</option>
                    <option value="pendiente" {{ request('estado')=='pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ request('estado')=='confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelada" {{ request('estado')=='cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search"></i> Buscar</button>
                <a href="{{ route('cliente.historial') }}" class="btn btn-secondary"><i class="fas fa-sync-alt"></i> Limpiar</a>
            </div>
        </form>

        <!-- LISTADO -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-primary">
                    <tr>
                        <th>Fecha de Reserva</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Fecha de Salida</th>
                        <th>Fecha de Llegada</th>
                        <th>Estado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($reservas as $reserva)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i') }}</td>
                            <td>{{ $reserva->viaje->origen }}</td>
                            <td>{{ $reserva->viaje->destino }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->viaje->fecha_salida)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->viaje->fecha_llegada)->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($reserva->viaje->precio, 2) }}</td>
                            <td>
                                <span class="badge
                                    @if($reserva->estado=='pendiente') bg-warning
                                    @elseif($reserva->estado=='confirmada') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x mb-2 text-muted"></i>
                                <div>No tienes reservas registradas</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $reservas->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
