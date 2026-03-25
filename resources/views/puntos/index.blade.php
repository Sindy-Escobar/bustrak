@extends('layouts.layoutuser')

@section('title', 'Mis Puntos y Canjes')

@section('contenido')
    <style>
        .pts-page {
            padding: 2rem 1.5rem;
            min-height: 100vh;
            background: #f0f4f8;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pts-inner {
            width: 100%;
            max-width: 860px;
        }

        .pts-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a3a5c;
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }

        /* Hero de puntos */
        .pts-hero {
            background: linear-gradient(135deg, #0d2f52 0%, #1a5f9c 100%);
            border-radius: 20px;
            padding: 1.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .pts-hero::after {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        .pts-hero-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #93c5fd;
            margin-bottom: 0.4rem;
        }

        .pts-hero-num {
            font-size: 4rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            margin-bottom: 0.4rem;
        }

        .pts-hero-sub {
            font-size: 0.85rem;
            color: #bfdbfe;
        }

        .pts-hero-badge {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            border: 2px solid rgba(255,255,255,0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pts-hero-badge span:first-child {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .pts-hero-badge span:last-child {
            font-size: 0.6rem;
            font-weight: 600;
            color: #93c5fd;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Botón historial canjes */
        .btn-historial-canjes {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1.5px solid #2563eb;
            color: #2563eb;
            border-radius: 10px;
            padding: 0.55rem 1.2rem;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
            box-shadow: 0 2px 8px rgba(37,99,235,0.1);
            margin-bottom: 1.5rem;
        }

        .btn-historial-canjes:hover {
            background: #eff6ff;
            color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37,99,235,0.2);
            text-decoration: none;
        }

        /* Sección label */
        .section-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 0.9rem;
        }

        /* Grid de beneficios */
        .beneficios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .ben-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .ben-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .ben-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .ben-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
        }

        .ben-desc {
            font-size: 0.82rem;
            color: #6b7280;
            line-height: 1.5;
            flex: 1;
        }

        .ben-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 4px;
            gap: 8px;
        }

        .ben-pts-badge {
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .btn-canjear {
            font-size: 0.8rem;
            font-weight: 600;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-canjear:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(37,99,235,0.35);
        }

        .btn-insuficiente {
            font-size: 0.8rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #94a3b8;
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            cursor: not-allowed;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* Tabla historial */
        .historial-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .hist-thead {
            background: #f8fafc;
            display: grid;
            grid-template-columns: 1fr 120px 130px;
            padding: 0.6rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .hist-thead span {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
        }

        .hist-row {
            display: grid;
            grid-template-columns: 1fr 120px 130px;
            align-items: center;
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.1s;
        }

        .hist-row:last-child { border-bottom: none; }
        .hist-row:hover { background: #f8fafc; }

        .hist-ruta {
            font-size: 0.88rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hist-arrow { color: #94a3b8; font-size: 0.8rem; }

        .hist-pts {
            font-size: 0.88rem;
            font-weight: 700;
            color: #16a34a;
        }

        .hist-fecha {
            font-size: 0.82rem;
            color: #94a3b8;
        }

        .alert-info-empty {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 0.88rem;
            color: #6b7280;
            text-align: center;
        }

        /* Toast container */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
        }

        @media (max-width: 600px) {
            .pts-hero { flex-wrap: wrap; gap: 1rem; }
            .hist-thead, .hist-row { grid-template-columns: 1fr auto; }
            .hist-fecha { display: none; }
        }
    </style>

    {{-- Toasts Bootstrap --}}
    <div class="toast-container">
        @if(session('success'))
            <div id="toastSuccess" class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div id="toastError" class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="5000">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-xmark"></i>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    <div class="pts-page">
        <div class="pts-inner">

            <h3 class="pts-title">Mis puntos de lealtad</h3>

            {{-- Hero de puntos --}}
            <div class="pts-hero">
                <div>
                    <div class="pts-hero-label">Puntos acumulados</div>
                    <div class="pts-hero-num">{{ $puntosTotales }}</div>
                    <div class="pts-hero-sub">Disponibles para canjear por beneficios exclusivos</div>
                </div>
                <div class="pts-hero-badge">
                    <span>{{ $puntosTotales }}</span>
                    <span>pts</span>
                </div>
            </div>

            {{-- Botón historial de canjes --}}
            <a href="{{ route('puntos.historial-canjes') }}" class="btn-historial-canjes">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Ver historial de canjes
            </a>

            {{-- Beneficios --}}
            <p class="section-label">Beneficios disponibles</p>

            @if($beneficios->count() > 0)
                <div class="beneficios-grid">
                    @foreach($beneficios as $beneficio)
                        <div class="ben-card">
                            <div class="ben-icon">
                                @if(str_contains(strtolower($beneficio->nombre), 'descuento'))
                                    <i class="fa-solid fa-tag"></i>
                                @elseif(str_contains(strtolower($beneficio->nombre), 'asiento'))
                                    <i class="fa-solid fa-couch"></i>
                                @elseif(str_contains(strtolower($beneficio->nombre), 'viaje') || str_contains(strtolower($beneficio->nombre), 'boleto'))
                                    <i class="fa-solid fa-bus"></i>
                                @else
                                    <i class="fa-solid fa-gift"></i>
                                @endif
                            </div>
                            <div class="ben-name">{{ $beneficio->nombre }}</div>
                            <div class="ben-desc">{{ $beneficio->descripcion }}</div>
                            <div class="ben-footer">
                                <span class="ben-pts-badge">
                                    <i class="fa-solid fa-star" style="font-size:0.65rem;"></i>
                                    {{ $beneficio->puntos_requeridos }} pts
                                </span>
                                @if($puntosTotales >= $beneficio->puntos_requeridos)
                                    <form action="{{ route('puntos.canjear', $beneficio->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-canjear"
                                                onclick="return confirm('¿Canjear {{ $beneficio->puntos_requeridos }} puntos por {{ $beneficio->nombre }}?')">
                                            <i class="fa-solid fa-check"></i>
                                            Canjear
                                        </button>
                                    </form>
                                @else
                                    <button class="btn-insuficiente" disabled>
                                        <i class="fa-solid fa-lock"></i>
                                        Sin puntos
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert-info-empty">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    No hay beneficios disponibles en este momento.
                </div>
            @endif

            {{-- Historial de puntos ganados --}}
            <p class="section-label" style="margin-top: 2rem;">Historial de puntos ganados</p>

            @if($puntosRegistros->count() > 0)
                <div class="historial-card">
                    <div class="hist-thead">
                        <span>Viaje</span>
                        <span>Puntos</span>
                        <span>Fecha</span>
                    </div>
                    @foreach($puntosRegistros as $registro)
                        <div class="hist-row">
                            <span class="hist-ruta">
                                @if($registro->reserva && $registro->reserva->viaje)
                                    <i class="fa-solid fa-location-dot" style="color:#94a3b8; font-size:0.75rem;"></i>
                                    {{ $registro->reserva->viaje->origen->nombre ?? 'N/A' }}
                                    <span class="hist-arrow"><i class="fa-solid fa-arrow-right"></i></span>
                                    {{ $registro->reserva->viaje->destino->nombre ?? 'N/A' }}
                                @else
                                    Viaje no disponible
                                @endif
                            </span>
                            <span class="hist-pts">
                                <i class="fa-solid fa-plus" style="font-size:0.7rem;"></i>
                                {{ $registro->puntos }}
                            </span>
                            <span class="hist-fecha">
                                <i class="fa-regular fa-calendar" style="font-size:0.75rem; margin-right:3px;"></i>
                                {{ $registro->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert-info-empty">
                    <i class="fa-solid fa-circle-info me-1"></i>
                    Aún no has acumulado puntos. Realiza viajes para empezar a ganar.
                </div>
            @endif

        </div>
    </div>

    <script>
        // Inicializar todos los toasts
        document.addEventListener('DOMContentLoaded', function () {
            var toastElems = document.querySelectorAll('.toast');
            toastElems.forEach(function (el) {
                var toast = new bootstrap.Toast(el);
                toast.show();
            });
        });
    </script>
@endsection
