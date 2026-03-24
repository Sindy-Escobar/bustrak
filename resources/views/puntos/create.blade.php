@extends('layouts.layoutuser')

@section('title', 'Registrar Puntos')

@section('contenido')
    <style>
        .puntos-page {
            min-height: 100vh;
            background: #f0f4f8;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .puntos-header {
            width: 100%;
            max-width: 820px;
            margin-bottom: 1rem;
        }

        .puntos-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a3a5c;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
        }

        .puntos-header p {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .alert {
            border-radius: 12px;
            border: none;
            width: 100%;
            max-width: 820px;
            margin-bottom: 1rem;
        }

        .viaje-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            width: 100%;
            max-width: 820px;
        }

        /* Cabecera con ruta */
        .viaje-card-header {
            background: linear-gradient(135deg, #0d2f52 0%, #1a5f9c 100%);
            padding: 1.75rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .viaje-card-header::after {
            content: '🚌';
            position: absolute;
            right: 2rem;
            font-size: 4rem;
            opacity: 0.12;
        }

        .ruta-info {
            flex: 1;
        }

        .ruta-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #93c5fd;
            margin-bottom: 0.25rem;
        }

        .ruta-nombre {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
        }

        .ruta-arrow {
            color: #38bdf8;
            font-size: 1.8rem;
            font-weight: 300;
            flex-shrink: 0;
        }

        /* Cuerpo */
        .viaje-card-body {
            padding: 1.75rem 2rem;
        }

        /* Grid de detalles */
        .detalles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detalle-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            border-left: 3px solid #3b82f6;
        }

        .detalle-item .label {
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #9ca3af;
            margin-bottom: 0.3rem;
        }

        .detalle-item .valor {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .detalle-item .valor.highlight {
            color: #2563eb;
        }

        /* Chips de asientos múltiples */
        .asientos-lista {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.3rem;
        }

        .asiento-chip {
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 6px;
            padding: 0.1rem 0.5rem;
            font-size: 0.85rem;
            font-weight: 700;
        }

        /* Sección de puntos */
        .puntos-section {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            border: 1px solid #bfdbfe;
        }

        .puntos-texto .titulo {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #3b82f6;
            margin-bottom: 0.2rem;
        }

        .puntos-texto .subtitulo {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .puntos-badge {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border-radius: 50%;
            width: 76px;
            height: 76px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
            flex-shrink: 0;
        }

        .puntos-badge .numero {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .puntos-badge .pts {
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.85;
        }

        /* Botón */
        .btn-guardar {
            width: 100%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.9rem;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-guardar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            color: white;
        }

        .btn-guardar:active {
            transform: translateY(0);
        }

        @media (max-width: 600px) {
            .detalles-grid {
                grid-template-columns: 1fr 1fr;
            }
            .viaje-card-header {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
    </style>

    <div class="puntos-page">

        <div class="puntos-header">
            <h3>Puntos de tu viaje</h3>
            <p>Revisa los detalles y guarda tus puntos acumulados</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php
            // Obtener todos los asientos asignados a esta reserva
            $asientosReserva = $reserva->viaje->asientos()
                ->where('reserva_id', $reserva->id)
                ->get();
        @endphp

        <div class="viaje-card">

            {{-- Cabecera: Origen → Destino --}}
            <div class="viaje-card-header">
                <div class="ruta-info">
                    <div class="ruta-label">Origen</div>
                    <div class="ruta-nombre">{{ $reserva->viaje->origen->nombre ?? '-' }}</div>
                </div>
                <div class="ruta-arrow">→</div>
                <div class="ruta-info">
                    <div class="ruta-label">Destino</div>
                    <div class="ruta-nombre">{{ $reserva->viaje->destino->nombre ?? '-' }}</div>
                </div>
            </div>

            {{-- Cuerpo --}}
            <div class="viaje-card-body">

                {{-- Grid de 3 columnas: Fecha Salida | Hora | Asiento(s) --}}
                <div class="detalles-grid">

                    <div class="detalle-item">
                        <div class="label">Fecha de Salida</div>
                        <div class="valor">
                            {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="detalle-item">
                        <div class="label">Hora de Salida</div>
                        <div class="valor">
                            {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('H:i') }}
                        </div>
                    </div>

                    <div class="detalle-item">
                        <div class="label">
                            {{ $asientosReserva->count() > 1 ? 'Numero de asientos' : 'Asiento' }}
                        </div>
                        <div class="valor highlight">
                            @if($asientosReserva->isNotEmpty())
                                <div class="asientos-lista">
                                    @foreach($asientosReserva as $asiento)
                                        <span class="asiento-chip">#{{ $asiento->numero_asiento }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#9ca3af; font-weight:500;">No asignado</span>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Puntos --}}
                <div class="puntos-section">
                    <div class="puntos-texto">
                        <div class="titulo">⭐ Puntos a ganar</div>
                        <div class="subtitulo">Por completar este viaje</div>
                    </div>
                    <div class="puntos-badge">
                        <span class="numero">10</span>
                        <span class="pts">pts</span>
                    </div>
                </div>

                {{-- Formulario --}}
                <form method="POST" action="{{ route('puntos.store', $reserva->id) }}">
                    @csrf
                    <input type="hidden" name="puntos" value="10">
                    <button type="submit" class="btn-guardar">
                        ✓ Guardar puntos
                    </button>
                </form>

            </div>
        </div>
    </div>
@endsection
