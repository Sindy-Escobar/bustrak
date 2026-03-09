@extends('layouts.layoutuser')

@section('contenido')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11"> {{--  CAMBIO: col-lg-10 a col-lg-11 (más ancho) --}}

                <div class="card shadow-sm">
                    {{--  CAMBIO: bg-warning a azul oscuro --}}
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #1e3a8a; color: white;">
                        <h4 class="mb-0">
                            <i class="fas fa-history me-2"></i>Mis Reportes de Incidentes
                        </h4>
                        {{--  CAMBIO: btn-dark a btn-light --}}
                        <a href="{{ route('incidentes.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>Nuevo Reporte
                        </a>
                    </div>
                    <div class="card-body">
                        {{-- Alerta de éxito --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Si no hay reportes --}}
                        @if($incidentes->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No has reportado ningún incidente</h5>
                                <p class="text-muted">Si tienes algún problema con nuestro servicio, no dudes en reportarlo.</p>
                                {{--  CAMBIO: btn-warning a azul oscuro --}}
                                <a href="{{ route('incidentes.create') }}" class="btn text-white mt-3" style="background-color: #1e3a8a;">
                                    <i class="fas fa-plus me-2"></i>Reportar Problema
                                </a>
                            </div>
                        @else

                            {{-- Lista de incidentes --}}
                            <div class="row g-3">
                                @foreach($incidentes as $incidente)
                                    <div class="col-12">
                                        <div class="card border-start border-4
                                        @if($incidente->tipo_incidente == 'retraso') border-info
                                        @elseif($incidente->tipo_incidente == 'mal_servicio') border-danger
                                        @elseif($incidente->tipo_incidente == 'bus_sucio') border-warning
                                        @elseif($incidente->tipo_incidente == 'conductor_grosero') border-danger
                                        @elseif($incidente->tipo_incidente == 'no_abordaje') border-dark
                                        @else border-secondary
                                        @endif">

                                            <div class="card-body">

                                                {{-- Encabezado: Badge de tipo y fecha --}}
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                    <span class="badge
                                                        @if($incidente->tipo_incidente == 'retraso') bg-info
                                                        @elseif($incidente->tipo_incidente == 'mal_servicio') bg-danger
                                                        @elseif($incidente->tipo_incidente == 'bus_sucio') bg-warning text-dark
                                                        @elseif($incidente->tipo_incidente == 'conductor_grosero') bg-danger
                                                        @elseif($incidente->tipo_incidente == 'no_abordaje') bg-dark
                                                        @else bg-secondary
                                                        @endif">
                                                        {{--  CAMBIO: Emojis a iconos Font Awesome --}}
                                                        @switch($incidente->tipo_incidente)
                                                            @case('retraso')
                                                                <i class="fas fa-clock me-1"></i> RETRASO
                                                                @break
                                                            @case('mal_servicio')
                                                                <i class="fas fa-frown me-1"></i> MAL SERVICIO
                                                                @break
                                                            @case('bus_sucio')
                                                                <i class="fas fa-broom me-1"></i> BUS SUCIO
                                                                @break
                                                            @case('conductor_grosero')
                                                                <i class="fas fa-angry me-1"></i> CONDUCTOR GROSERO
                                                                @break
                                                            @case('no_abordaje')
                                                                <i class="fas fa-ban me-1"></i> NO PUDE ABORDAR
                                                                @break
                                                            @default
                                                                <i class="fas fa-ellipsis-h me-1"></i> OTRO
                                                        @endswitch
                                                    </span>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $incidente->fecha_hora_incidente->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>

                                                {{-- Información del viaje (si existe reserva) --}}
                                                @if($incidente->reserva && $incidente->reserva->viaje)
                                                    <div class="alert alert-light border mb-3">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-8">
                                                                <small class="text-muted d-block mb-1">
                                                                    <i class="fas fa-route me-1"></i><strong>Viaje reportado:</strong>
                                                                </small>
                                                                <span class="fw-bold">
                                                                {{ $incidente->reserva->viaje->origen->nombre ?? 'N/A' }}
                                                                    {{--  CAMBIO: text-primary a azul oscuro --}}
                                                                <i class="fas fa-arrow-right mx-2" style="color: #1e3a8a;"></i>
                                                                {{ $incidente->reserva->viaje->destino->nombre ?? 'N/A' }}
                                                            </span>
                                                                <br>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-clock me-1"></i>
                                                                    {{ \Carbon\Carbon::parse($incidente->reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }}
                                                                </small>
                                                            </div>
                                                            <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                                                <small class="text-muted d-block">Código de reserva</small>
                                                                {{--  CAMBIO: bg-dark a azul oscuro --}}
                                                                <span class="badge" style="background-color: #1e3a8a;">{{ $incidente->reserva->codigo_reserva }}</span>
                                                            </div>
                                                        </div>
                                                        @if($incidente->numero_bus)
                                                            <hr class="my-2">
                                                            <small class="text-muted">
                                                                <i class="fas fa-bus me-1"></i><strong>Bus:</strong> {{ $incidente->numero_bus }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                @else
                                                    {{-- Si no hay reserva asociada --}}
                                                    <div class="mb-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-route me-1"></i><strong>Ruta:</strong> {{ $incidente->ruta }}
                                                        </small>
                                                        @if($incidente->numero_bus)
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-bus me-1"></i><strong>Bus:</strong> {{ $incidente->numero_bus }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                @endif

                                                {{-- Descripción del incidente --}}
                                                <div>
                                                    {{-- ✅ CAMBIO: text-primary a azul oscuro --}}
                                                    <h6 class="mb-2"><i class="fas fa-comment-dots me-2" style="color: #1e3a8a;"></i>Descripción:</h6>
                                                    <p class="text-muted mb-0 ps-4">{{ $incidente->descripcion }}</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Paginación --}}
                            <div class="mt-4">
                                {{ $incidentes->links() }}
                            </div>

                        @endif

                    </div>

                    {{-- Footer con información --}}
                    <div class="card-footer bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    @if($incidentes->total() > 0)
                                        Mostrando {{ $incidentes->firstItem() }} - {{ $incidentes->lastItem() }} de {{ $incidentes->total() }} reportes
                                    @else
                                        Sin reportes registrados
                                    @endif
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <a href="{{ route('cliente.historial') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Volver a Mis Viajes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información adicional --}}
                <div class="alert alert-info mt-3">
                    <i class="fas fa-shield-alt me-2"></i>
                    <strong>Confidencialidad:</strong> Tus reportes son confidenciales y serán revisados por nuestro equipo para mejorar la calidad del servicio.
                </div>

            </div>
        </div>
    </div>
@endsection
