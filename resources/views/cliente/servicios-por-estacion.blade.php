@extends('layouts.layoutuser')

@section('contenido')
    <style>
        .servicios-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }

        .header-card {
            background: linear-gradient(135deg, #5cb3ff 0%, #1e63b8 100%);
            border-radius: 16px;
            padding: 40px;
            color: white;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .header-card h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-card p {
            margin: 12px 0 0 0;
            font-size: 14px;
            opacity: 0.95;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            margin-top: 20px;
        }

        .card h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .card h2 i {
            color: #5cb3ff;
            margin-right: 15px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .info-card h3 {
            color: #333;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .info-card p {
            color: #666;
            line-height: 1.6;
            margin: 0;
            font-size: 15px;
        }

        .terminales-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .terminal-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .terminal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border-color: #5cb3ff;
        }

        .terminal-icon {
            font-size: 3rem;
            color: #5cb3ff;
            margin-bottom: 15px;
        }

        .terminal-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .terminal-description {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .servicios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .servicio-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s ease;
        }

        .servicio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border-color: #5cb3ff;
        }

        .servicio-icon {
            font-size: 3rem;
            color: #5cb3ff;
            margin-bottom: 15px;
            display: block;
        }

        .servicio-nombre {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .servicio-descripcion {
            color: #666;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-icon {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-message {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .empty-submessage {
            font-size: 0.95rem;
            color: #999;
        }

        .btn-volver {
            display: inline-block;
            padding: 10px 24px;
            background: #5cb3ff;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-volver:hover {
            background: #3d97f0;
            transform: translateY(-2px);
            color: white;
        }

        @media (max-width: 768px) {
            .terminales-grid {
                grid-template-columns: 1fr;
            }

            .servicios-grid {
                grid-template-columns: 1fr;
            }

            .header-card {
                padding: 30px 20px;
            }

            .header-card h1 {
                font-size: 24px;
            }

            .card {
                padding: 30px 20px;
            }
        }
    </style>

    <div class="container-fluid px-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background-color: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('cliente.perfil') }}">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Servicios por Estación</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-12">

                <div class="header-card">
                    <h1>
                        <i class="fas fa-building"></i>
                        Servicios Disponibles por Estación
                    </h1>
                    <p>Descubre qué comodidades encontrarás en cada terminal</p>
                </div>

                <div class="card">
                    <h2>
                        <i class="fas fa-map-marker-alt"></i>Selecciona una Estación
                    </h2>

                    <div class="info-card">
                        <h3>
                            <i class="fas fa-info-circle me-2" style="color: #5cb3ff;"></i>
                            ¿Qué servicios ofrece cada estación?
                        </h3>
                        <p>
                            Conoce todos los servicios y comodidades disponibles en nuestras terminales para que planifiques mejor tu tiempo de espera antes de tu viaje.
                        </p>
                    </div>

                    <div class="terminales-grid" id="terminalesGrid">
                        @foreach($terminales as $terminal)
                            <div class="terminal-card" onclick="verServicios({{ $terminal->id }}, '{{ $terminal->nombre }}')">
                                <div class="terminal-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="terminal-name">{{ $terminal->nombre }}</div>
                                <div class="terminal-description">
                                    {{ $terminal->municipio }}, {{ $terminal->departamento }}
                                </div>
                                <small style="color: #5cb3ff; font-weight: 600;">Ver servicios →</small>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sección de servicios (oculta inicialmente) -->
                    <div id="serviciosSection" style="display: none;">
                        <h3 style="margin-top: 40px; margin-bottom: 30px; color: #333; font-size: 1.5rem;">
                            <i class="fas fa-check-circle" style="color: #5cb3ff; margin-right: 10px;"></i>
                            Servicios en <span id="nombreTerminal"></span>
                        </h3>

                        <div class="servicios-grid" id="serviciosGrid">
                            <!-- Se llena dinámicamente -->
                        </div>

                        <div style="text-align: center; margin-top: 30px;">
                            <button class="btn-volver" onclick="volver()">
                                <i class="fas fa-arrow-left me-2"></i>Volver a Estaciones
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        const serviciosPorTerminal = {
            @foreach($terminales as $terminal)
                {{ $terminal->id }}: {
                nombre: '{{ $terminal->nombre }}',
                servicios: {!! json_encode(
                        \App\Models\Servicio::activos()
                            ->porTerminal($terminal->id)
                            ->ordenadosPorNombre()
                            ->get()
                    ) !!}
            },
            @endforeach
        };

        function verServicios(terminalId, terminalNombre) {
            document.getElementById('terminalesGrid').style.display = 'none';
            document.getElementById('serviciosSection').style.display = 'block';
            document.getElementById('nombreTerminal').textContent = terminalNombre;

            const servicios = serviciosPorTerminal[terminalId].servicios;
            const serviciosGrid = document.getElementById('serviciosGrid');
            serviciosGrid.innerHTML = '';

            if (servicios.length === 0) {
                serviciosGrid.innerHTML = `
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <div class="empty-message">No hay servicios disponibles</div>
                        <div class="empty-submessage">En esta estación no hay servicios registrados en este momento.</div>
                    </div>
                `;
            } else {
                servicios.forEach(servicio => {
                    const card = document.createElement('div');
                    card.className = 'servicio-card';
                    card.innerHTML = `
                        <i class="${servicio.icono} servicio-icon"></i>
                        <div class="servicio-nombre">${servicio.nombre}</div>
                        <div class="servicio-descripcion">${servicio.descripcion}</div>
                    `;
                    serviciosGrid.appendChild(card);
                });
            }
        }

        function volver() {
            document.getElementById('terminalesGrid').style.display = 'grid';
            document.getElementById('serviciosSection').style.display = 'none';
        }
    </script>
@endsection
