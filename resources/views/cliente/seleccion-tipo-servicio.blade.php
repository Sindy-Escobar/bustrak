@extends('layouts.layoutuser')

@section('contenido')
    <style>
        .seleccion-container {
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

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .service-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border-color: #5cb3ff;
        }

        .service-card.selected {
            border-color: #5cb3ff;
            background: linear-gradient(135deg, rgba(92, 179, 255, 0.05), rgba(30, 99, 184, 0.05));
            box-shadow: 0 4px 15px rgba(92, 179, 255, 0.3);
        }

        .service-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
            display: block;
            color: #5cb3ff;
        }

        .service-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .service-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            min-height: 75px;
            font-size: 14px;
        }

        .service-card input[type="radio"] {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 24px;
            height: 24px;
            cursor: pointer;
            appearance: none;
            border: 2px solid #e0e0e0;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .service-card input[type="radio"]:checked {
            border-color: #5cb3ff;
            background: #5cb3ff;
            box-shadow: 0 0 0 3px rgba(92, 179, 255, 0.2);
        }

        .service-card input[type="radio"]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-continue {
            padding: 10px 24px;
            background: #5cb3ff;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-continue:hover:not(:disabled) {
            background: #3d97f0;
            transform: translateY(-2px);
            color: white;
        }

        .btn-continue:disabled {
            background: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .button-container {
            text-align: center;
            margin-top: 30px;
        }

        .alert-custom {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .loading-container {
            text-align: center;
            padding: 80px 20px;
            color: #666;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #5cb3ff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .services-grid {
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
                <li class="breadcrumb-item active" aria-current="page">Seleccionar Servicio</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-12">

                <div class="header-card">
                    <h1>
                        <i class="fas fa-bus"></i>
                        Selecciona tu Tipo de Servicio
                    </h1>
                    <p>Elige la opción que mejor se adapte a tus necesidades de viaje</p>
                </div>

                <div class="card">
                    <h2>
                        <i class="fas fa-route"></i>Tipos de Servicio Disponibles
                    </h2>

                    <div id="alertContainer"></div>

                    <div class="info-card">
                        <h3>
                            <i class="fas fa-info-circle me-2" style="color: #5cb3ff;"></i>
                            ¿Qué tipo de servicio necesitas?
                        </h3>
                        <p>
                            Cada tipo de servicio está diseñado para diferentes necesidades de transporte.
                            Revisa las características de cada uno para elegir el que mejor se ajuste a tu viaje.
                        </p>
                    </div>

                    <div class="loading-container" id="loadingContainer" style="display: none;">
                        <div class="spinner"></div>
                        <p>Cargando tipos de servicio...</p>
                    </div>

                    <div class="services-grid" id="servicesGrid">
                        @foreach($tiposServicio as $servicio)
                            <div class="service-card" data-servicio-id="{{ $servicio->id }}" onclick="seleccionarServicio({{ $servicio->id }})">
                                <input
                                    type="radio"
                                    name="tipo_servicio"
                                    value="{{ $servicio->id }}"
                                    id="servicio_{{ $servicio->id }}"
                                >
                                <div class="service-icon">
                                    <i class="{{ $servicio->icono }}"></i>
                                </div>
                                <div class="service-name">{{ $servicio->nombre }}</div>
                                <div class="service-description">{{ $servicio->descripcion }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="button-container">
                        <button
                            id="btnContinuar"
                            class="btn-continue"
                            onclick="procesarSeleccion()"
                            disabled
                        >
                            <span>Continuar con la Reserva</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        let servicioSeleccionado = null;
        const csrfToken = '{{ csrf_token() }}';

        function seleccionarServicio(id) {
            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.remove('selected');
            });

            const card = document.querySelector(`[data-servicio-id="${id}"]`);
            const radio = document.getElementById(`servicio_${id}`);

            if (card && radio) {
                card.classList.add('selected');
                radio.checked = true;
                servicioSeleccionado = id;
                document.getElementById('btnContinuar').disabled = false;
                document.getElementById('alertContainer').innerHTML = '';
            }
        }

        async function procesarSeleccion() {
            if (!servicioSeleccionado) {
                mostrarAlerta('Por favor, selecciona un tipo de servicio', 'warning');
                return;
            }

            const btn = document.getElementById('btnContinuar');
            const btnTextoOriginal = btn.innerHTML;

            try {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner" style="width: 20px; height: 20px; margin-right: 8px; border-width: 3px;"></span> Procesando...';

                const response = await fetch('{{ route("cliente.tipo-servicio.seleccionar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tipo_servicio_id: servicioSeleccionado
                    })
                });

                const result = await response.json();

                if (result.success) {
                    mostrarAlerta('¡Tipo de servicio seleccionado correctamente!', 'success');

                    setTimeout(() => {
                        if (result.redirect) {
                            window.location.href = result.redirect;
                        }
                    }, 1500);
                } else {
                    mostrarAlerta(result.message || 'Error al procesar la selección', 'error');
                    btn.disabled = false;
                    btn.innerHTML = btnTextoOriginal;
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarAlerta('Error de conexión al procesar la selección', 'error');
                btn.disabled = false;
                btn.innerHTML = btnTextoOriginal;
            }
        }

        function mostrarAlerta(mensaje, tipo = 'info') {
            const iconos = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle'
            };

            const alert = document.createElement('div');
            alert.className = `alert-custom alert-${tipo}`;
            alert.innerHTML = `
                <i class="fas ${iconos[tipo] || 'fa-info-circle'}"></i>
                <span>${mensaje}</span>
            `;

            const container = document.getElementById('alertContainer');
            container.innerHTML = '';
            container.appendChild(alert);

            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session()->has('tipo_servicio_seleccionado'))
            const servicioAnterior = {{ session()->get('tipo_servicio_seleccionado')['id'] ?? 'null' }};
            if (servicioAnterior) {
                seleccionarServicio(servicioAnterior);
            }
            @endif
        });
    </script>
@endsection
