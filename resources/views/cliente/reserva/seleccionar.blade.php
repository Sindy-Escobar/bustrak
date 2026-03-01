@extends('layouts.layoutuser')

@section('contenido')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Viajes Disponibles</h4>
        </div>
        <div class="card-body">
            @if($viajes->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay viajes disponibles para esta ruta.
                </div>
            @else
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Hora Salida</th>
                        <th>Asientos Disponibles</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($viajes as $viaje)
                        <tr>
                            <td>{{ $viaje->origen->nombre }}</td>
                            <td>{{ $viaje->destino->nombre }}</td>
                            <td>{{ \Carbon\Carbon::parse($viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $viaje->asientos_count }} disponibles
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('cliente.reserva.asientos', $viaje->id) }}"
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-chair me-1"></i>Seleccionar Asiento
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
