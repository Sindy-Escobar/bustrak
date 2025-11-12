@extends('layouts.layoutuser')
@section('contenido')
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Terminales - BusTrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background-color: #ecf0f1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar con azul oscuro */
        .navbar-bustrak {
            background: #0c3c60;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        /* Contenedor para logo y menú (izquierda) */
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .navbar-brand-bustrak {
            color: white !important;
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .nav-link-bustrak {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-link-bustrak:hover,
        .nav-link-bustrak.active {
            color: white !important;
            background-color: rgba(255,255,255,0.15);
        }

        .btn-login {
            background-color: #00BCD4;
            color: white;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 5px;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #00ACC1;
            color: white;
        }

        /* Contenido principal */
        .main-content {
            padding: 20px;
            margin-top: 0;
        }

        .header-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .main-title {
            color: #0c3c60;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 2rem;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .search-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-label {
            font-weight: 600;
            color: #0c3c60;
            margin-bottom: 8px;
        }

        .btn-primary-custom {
            background: #5DA3E8;
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
        }

        .btn-primary-custom:hover {
            background: #4A92D7;
            color: white;
        }

        .table-custom {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table-custom thead {
            background: #0c3c60;
            color: white;
        }

        .table-custom th {
            font-weight: 600;
            border: none;
            padding: 15px 12px;
        }

        .table-custom td {
            padding: 12px;
            vertical-align: middle;
            border-color: #ecf0f1;
        }

        .badge-active {
            background-color: #27ae60;
            color: white;
        }

        .badge-distance {
            background: #5DA3E8;
            color: white;
            font-weight: 600;
        }

        #map {
            height: 400px;
            border-radius: 8px;
            border: 1px solid #bdc3c7;
            margin-bottom: 20px;
        }

        .map-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .section-title {
            color: #0c3c60;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .pagination .page-link {
            color: #5DA3E8;
        }

        .pagination .page-item.active .page-link {
            background: #5DA3E8;
            border-color: #5DA3E8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-bustrak {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }

            .navbar-left {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                gap: 15px;
            }
        }
    </style>
</head>
<body>

<!-- Contenido Principal -->
<div class="container main-content">
    <!-- Header Section -->
    <div class="header-section">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h1 class="main-title">Consultar Terminales</h1>
                <p class="subtitle">Lista de Terminales de Autobuses Registradas</p>
            </div>
        </div>
    </div>

    <!-- Search and Location Section -->
    <div class="search-section">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Buscar por nombre o dirección...</label>
                <form action="{{ route('consulta-paradas.index') }}" method="GET" id="search-form" class="d-flex">
                    <input type="hidden" name="lat" id="input-lat" value="{{ request('lat') }}">
                    <input type="hidden" name="lng" id="input-lng" value="{{ request('lng') }}">
                    <input type="text" name="search" class="form-control me-2"
                           placeholder="Buscar terminal..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-search me-1"></i>Buscar
                    </button>
                </form>
            </div>
            <div class="col-md-5">
                <label class="form-label">Ordenar por</label>
                <form action="{{ route('consulta-paradas.index') }}" method="GET" id="sort-form">
                    <input type="hidden" name="lat" value="{{ request('lat') }}">
                    <input type="hidden" name="lng" value="{{ request('lng') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="order_by" class="form-select" onchange="document.getElementById('sort-form').submit()">
                        <option value="nombre" {{ request('order_by') == 'nombre' ? 'selected' : '' }}>Nombre (A-Z)</option>
                        <option value="nombre_desc" {{ request('order_by') == 'nombre_desc' ? 'selected' : '' }}>Nombre (Z-A)</option>
                        <option value="fecha_reciente" {{ request('order_by') == 'fecha_reciente' ? 'selected' : '' }}>Más Recientes</option>
                        <option value="fecha_antiguo" {{ request('order_by') == 'fecha_antiguo' ? 'selected' : '' }}>Más Antiguos</option>
                        <option value="activas" {{ request('order_by') == 'activas' ? 'selected' : '' }}>Solo Activas</option>
                        <option value="inactivas" {{ request('order_by') == 'inactivas' ? 'selected' : '' }}>Solo Inactivas</option>
                        @if(request('lat') && request('lng'))
                            <option value="proximidad" {{ request('order_by') == 'proximidad' ? 'selected' : '' }}>Más Cercanas</option>
                        @endif
                    </select>
                </form>
            </div>
            <div class="col-md-2">
                <!-- BOTÓN SEPARADO - NO DENTRO DE FORM -->
                <button type="button" id="btn-location" class="btn btn-primary-custom w-100">
                    <i class="fas fa-location-arrow me-1"></i>Mi Ubicación
                </button>
            </div>
        </div>
        <div class="mt-2">
            <small id="location-status" class="text-muted"></small>
        </div>
    </div>

    <!-- Interactive Map Section -->
    <div class="map-section">
        <h3 class="section-title">
            <i class="fas fa-map me-2"></i>Mapa de Terminales
        </h3>
        <div id="map"></div>
    </div>

    <!-- Results Section -->
    <div class="search-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">
                <i class="fas fa-list me-2"></i>Lista de Terminales
            </h3>
            <span class="text-muted">
                    @if($paradas->count() > 0)
                    Mostrando {{ $paradas->firstItem() }} - {{ $paradas->lastItem() }} de {{ $paradas->total() }} terminales
                @endif
                </span>
        </div>

        @if($paradas->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Horario</th>
                        <th>Contacto</th>
                        <th>Ubicación</th>
                        @if(request('lat') && request('lng'))
                            <th>Distancia</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($paradas as $parada)
                        <tr>
                            <td>
                                <strong>{{ $parada->nombre }}</strong>
                                @if($parada->estado)
                                    <span class="badge badge-active ms-1">Activa</span>
                                @endif
                            </td>
                            <td>{{ $parada->direccion }}</td>
                            <td>
                                <i class="fas fa-clock text-warning me-1"></i>
                                {{ $parada->horario ?? 'No especificado' }}
                            </td>
                            <td>
                                @if($parada->contacto)
                                    <i class="fas fa-phone text-success me-1"></i>
                                    {{ $parada->contacto }}
                                @else
                                    <span class="text-muted">Sin contacto</span>
                                @endif
                            </td>
                            <td>
                                @if($parada->latitud && $parada->longitud)
                                    <button class="btn btn-sm btn-primary-custom view-on-map"
                                            data-lat="{{ $parada->latitud }}"
                                            data-lng="{{ $parada->longitud }}"
                                            data-name="{{ $parada->nombre }}">
                                        <i class="fas fa-map-marker-alt me-1"></i>Ver Mapa
                                    </button>
                                @else
                                    <span class="text-muted">No disponible</span>
                                @endif
                            </td>
                            @if(request('lat') && request('lng'))
                                <td>
                                    @if(isset($parada->distancia))
                                        <span class="badge badge-distance">
                                                {{ number_format($parada->distancia, 1) }} km
                                            </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    {{ $paradas->links() }}
                </nav>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron terminales</h4>
                <p class="text-muted">Intenta con otros términos de búsqueda o activa tu ubicación</p>
                <button id="btn-location-empty" class="btn btn-primary-custom">
                    <i class="fas fa-location-arrow me-1"></i>Activar Mi Ubicación
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize Map
    const map = L.map('map').setView([14.0378, -86.5794], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add markers for terminales
    const paradasData = @json($paradas->items());
    paradasData.forEach(parada => {
        if (parada.latitud && parada.longitud) {
            L.marker([parada.latitud, parada.longitud])
                .addTo(map)
                .bindPopup(`
                        <div class="text-center">
                            <h6 class="text-primary mb-2">${parada.nombre}</h6>
                            <p class="mb-1 small"><i class="fas fa-map-marker-alt me-1"></i>${parada.direccion}</p>
                            <p class="mb-1 small"><i class="fas fa-clock me-1"></i>${parada.horario || 'Horario no especificado'}</p>
                            <p class="mb-0 small"><i class="fas fa-phone me-1"></i>${parada.contacto || 'Sin contacto'}</p>
                        </div>
                    `);
        }
    });

    // User location
    let userMarker = null;
    const userLat = {{ request('lat') ?: 'null' }};
    const userLng = {{ request('lng') ?: 'null' }};

    if (userLat && userLng) {
        userMarker = L.marker([userLat, userLng])
            .addTo(map)
            .bindPopup('<strong><i class="fas fa-user me-1"></i>Tu Ubicación Actual</strong>')
            .openPopup();

        // Adjust map view
        const group = new L.featureGroup([...map._layers]);
        map.fitBounds(group.getBounds().pad(0.1));
    }

    // Location button functionality - CORREGIDA
    function activateLocation(buttonId = 'btn-location') {
        const status = document.getElementById('location-status');
        const btn = document.getElementById(buttonId);

        status.textContent = 'Detectando tu ubicación...';
        status.className = 'text-warning';
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Detectando...';

        if (!navigator.geolocation) {
            status.textContent = 'Tu navegador no soporta geolocalización';
            status.className = 'text-danger';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-location-arrow me-1"></i>Mi Ubicación';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Actualizar los inputs hidden
                document.getElementById('input-lat').value = lat;
                document.getElementById('input-lng').value = lng;

                status.textContent = 'Ubicación detectada! Recargando resultados...';
                status.className = 'text-success';

                // Enviar el formulario de búsqueda con las nuevas coordenadas
                setTimeout(() => {
                    document.getElementById('search-form').submit();
                }, 1000);
            },
            function(error) {
                let message = 'Error: ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message += 'Permiso de ubicación denegado';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message += 'Ubicación no disponible';
                        break;
                    case error.TIMEOUT:
                        message += 'Tiempo de espera agotado';
                        break;
                    default:
                        message += 'Error desconocido';
                }
                status.textContent = message;
                status.className = 'text-danger';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-location-arrow me-1"></i>Mi Ubicación';
            },
            {
                timeout: 10000,
                enableHighAccuracy: true
            }
        );
    }

    // Event listeners CORREGIDOS
    document.getElementById('btn-location').addEventListener('click', () => activateLocation('btn-location'));
    if (document.getElementById('btn-location-empty')) {
        document.getElementById('btn-location-empty').addEventListener('click', () => activateLocation('btn-location-empty'));
    }

    // View on map buttons
    document.querySelectorAll('.view-on-map').forEach(button => {
        button.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            const name = this.dataset.name;

            map.setView([lat, lng], 15);

            // Find and open marker popup
            map.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    const pos = layer.getLatLng();
                    if (pos.lat === lat && pos.lng === lng) {
                        layer.openPopup();
                    }
                }
            });
        });
    });
</script>
</body>
</html>
@endsection
