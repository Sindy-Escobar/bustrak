@extends('layouts.layoutuser')

@section('title', 'Comentarios del Conductor')

@section('contenido')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-comments me-2"></i>
                    Comentarios sobre {{ $conductor->nombre }} {{ $conductor->apellido }}
                </h4>
                <small>Lo que opinan otros pasajeros</small>
            </div>

            <div class="card-body">
                @if($comentarios->count() > 0)
                    {{-- Estadísticas --}}
                    @php
                        $promedio = $comentarios->avg('calificacion');
                        $total = $comentarios->count();
                    @endphp

                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <h1 class="display-3 text-warning mb-0">{{ number_format($promedio, 1) }}</h1>
                            <div class="text-warning fs-4">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= floor($promedio))
                                        ★
                                    @elseif($i - $promedio < 1)
                                        ⯨
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <p class="text-muted">{{ $total }} calificaciones</p>
                        </div>

                        <div class="col-md-8">
                            @foreach([5,4,3,2,1] as $estrella)
                                @php
                                    $cantidad = $comentarios->where('calificacion', $estrella)->count();
                                    $porcentaje = $total > 0 ? ($cantidad / $total) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2" style="width:60px;">{{ $estrella }} ★</span>
                                    <div class="progress flex-grow-1" style="height:20px;">
                                        <div class="progress-bar bg-warning" style="width:{{ $porcentaje }}%"></div>
                                    </div>
                                    <span class="ms-2 text-muted" style="width:40px;">{{ $cantidad }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3"><i class="fas fa-list me-2"></i>Comentarios</h5>
                    @foreach($comentarios as $comentario)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $comentario->usuario->nombre_completo ?? 'Usuario' }}</h6>
                                            <small class="text-muted">{{ $comentario->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="text-warning fs-5">
                                        @for($i=1; $i<=$comentario->calificacion; $i++) ★ @endfor
                                    </div>
                                </div>

                                <p><strong>Comentario:</strong> {{ $comentario->comentario }}</p>

                                @if($comentario->positivo)
                                    <p class="text-success mb-1">
                                        <i class="fas fa-thumbs-up"></i> <strong>Positivo:</strong> {{ $comentario->positivo }}
                                    </p>
                                @endif

                                @if($comentario->mejoras)
                                    <p class="text-warning mb-1">
                                        <i class="fas fa-exclamation-triangle"></i> <strong>Mejoras:</strong> {{ $comentario->mejoras }}
                                    </p>
                                @endif

                                @if($comentario->velocidad || $comentario->seguridad)
                                    <div class="mt-2">
                                        @if($comentario->velocidad)
                                            <span class="badge {{ $comentario->velocidad == 'si' ? 'bg-success' : 'bg-danger' }} me-2">
                                            Velocidad: {{ $comentario->velocidad == 'si' ? 'Adecuada' : 'Excesiva' }}
                                        </span>
                                        @endif
                                        @if($comentario->seguridad)
                                            <span class="badge {{ $comentario->seguridad == 'si' ? 'bg-success' : 'bg-danger' }}">
                                            Seguridad: {{ $comentario->seguridad == 'si' ? 'Seguro' : 'Inseguro' }}
                                        </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                @else
                    {{-- HU4: Mensaje cuando no hay comentarios --}}
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle fa-3x me-3"></i>
                        <div>
                            <h5 class="alert-heading">Este chofer no tiene comentarios</h5>
                            <p class="mb-0">Aún no hay calificaciones disponibles para este conductor.</p>
                        </div>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('cliente.historial') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
