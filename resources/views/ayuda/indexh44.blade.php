@extends('layouts.layoutuser')

@section('title', 'Mis Solicitudes de Ayuda')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4 text-primary">Mis Solicitudes de Ayuda y Reclamos</h2>

        @if($consultas->isEmpty())
            <div class="alert alert-info">
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
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($consultas as $consulta)
                            <tr>
                                <td>{{ $consulta->asunto }}</td>
                                <td>{{ $consulta->mensaje }}</td>
                                <td>{{ $consulta->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>


    <script>
        setInterval(() => {
            location.reload();
        }, 20000);
    </script>
@endsection

