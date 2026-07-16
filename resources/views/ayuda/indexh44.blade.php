@extends('layouts.layoutuser')

@section('contenido')
    <div class="container mt-4">
        <h2 class="mb-3" style="color:#1e63b8;">
            <i class="fas fa-headset me-2"></i>Mis Consultas
        </h2>

        @if($consultas->isEmpty())
            <div class="alert alert-info text-center">
                No has enviado ninguna solicitud aún.
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="table-primary">
                        <tr>
                            <th>Asunto</th>
                            <th>Mensaje</th>
                            <th>Fecha de Envío</th>
                            <th>Respuesta de Soporte</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($consultas as $consulta)
                            <tr>
                                <td>{{ $consulta->asunto }}</td>
                                <td>{{ $consulta->mensaje }}</td>
                                <td>{{ $consulta->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($consulta->respuesta)
                                        <span class="badge bg-success mb-1">Respondida</span><br>
                                        {{ $consulta->respuesta }}
                                    @else
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
