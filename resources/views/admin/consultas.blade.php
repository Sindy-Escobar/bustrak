@extends('layouts.layoutadmin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 style="margin:0; color:#1e63b8; font-weight:600; font-size:2rem;">
                    <i class="fas fa-headset me-2"></i>Consultas de Usuarios
                </h2>
            </div>
            <div class="card-body">
                <!-- Mensajes de éxito -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-circle-check me-2"></i>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                <!-- Tabla de Consultas -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-primary">
                        <tr>
                            <th><i class="fas fa-user me-2"></i>Nombre</th>
                            <th><i class="fas fa-envelope me-2"></i>Correo</th>
                            <th><i class="fas fa-tag me-2"></i>Asunto</th>
                            <th><i class="fas fa-comment me-2"></i>Mensaje</th>
                            <th><i class="fas fa-calendar me-2"></i>Fecha de Envío</th>
                            <th class="text-center"><i class="fas fa-cog me-2"></i>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($consultas as $consulta)
                            <tr>
                                <td>{{ $consulta->nombre_completo }}</td>
                                <td>{{ $consulta->correo }}</td>
                                <td>{{ $consulta->asunto }}</td>
                                <td>{{ $consulta->mensaje }}</td>
                                <td>{{ $consulta->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('consulta.eliminar', $consulta->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta consulta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash me-1"></i>Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    No hay consultas registradas
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $consultas->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
