<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        #backToTop {
            position: fixed;
            bottom: 30px;
            right: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #000;
            color: #000;
            font-size: 24px;
            display: none;
            align-items: center;
            justify-content: center;
            text-align: center;
            z-index: 1000;
            cursor: pointer;
            background-color: transparent;
            transition: transform 0.3s, border-color 0.3s, color 0.3s;
            display: flex;
        }

        #backToTop:hover {
            transform: translateY(-2px);
            border-color: #06174f;
            color: #06174f;
        }


        .btn-login {
            background-color: #007bff !important;
            color: white !important;
            border: none !important;
            border-radius: 12px;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: 600;
        }
        .btn-login:hover { opacity: 0.85; }

        .btn-registro {
            background-color: #28a745 !important;
            color: white !important;
            border: none !important;
            border-radius: 12px;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: 600;
        }
        .btn-registro:hover { opacity: 0.85; }

        .btn-outline-light {
            color: #fff !important;
            border: 2px solid #fff !important;
        }
        .btn-outline-light:hover {
            background-color: #fff !important;
            color: #1976d2 !important;
        }
        .btn-gradient {
            background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            transition: all 0.3s;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            opacity: 0.95;
        }

        body { font-family: Arial, sans-serif; background: #EAF6FF; }

        /* BOTONES LOGIN / REGISTRO */
        .hero-buttons a {
            padding: 8px 18px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 10px;
        }
        .btn-login {
            background-color: #007bff !important;
            color: white !important;
            border: none !important;
        }
        .btn-login:hover { opacity: 0.85; }
        .btn-registro {
            background-color: #28a745 !important;
            color: white !important;
            border: none !important;
        }
        .btn-registro:hover { opacity: 0.85; }

        /* HERO */
        .hero-section {
            background-repeat: no-repeat;
            background-position: right top;
            background-size: 550px;
            padding: 80px 0;
        }
        .hero-title {
            font-size: 58px;
            font-weight: 800;
            line-height: 1.1;
            color: #1c1c1c;
            text-align: left;
            text-shadow: none;
        }
        .hero-subtitle {
            font-size: 22px;
            margin-top: 15px;
            color: #4b4b4b;
            text-align: left;
        }

        /* INFO BOXES */
        .info-box {
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.05);
        }
        .info-icon { width: 60px; }
        .info-text { font-size: 18px; font-weight: 500; }

        /* DEPARTAMENTOS */
        .departments .card { cursor: pointer; transition: transform 0.2s; }
        .departments .card:hover { transform: scale(1.05); }
        .departments img { height: 150px; object-fit: cover; border-radius: 10px; }


        /* Modal overlay con backdrop blur */
        .modal-info {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(5px);
            z-index: 3000;
            padding: 20px;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-info.show {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Contenedor del modal con animación */
        .modal-info-content {
            background: white;
            border-radius: 20px;
            max-width: 1200px;
            width: 100%;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header mejorado con gradiente */
        .modal-info-header {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            padding: 28px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-info-header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-info-header h2 i {
            font-size: 32px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        /* Botón cerrar mejorado */
        .modal-info-close {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .modal-info-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
            border-color: white;
        }

        /* Body del modal */
        .modal-info-body {
            padding: 32px;
            overflow-y: auto;
            max-height: calc(90vh - 100px);
        }

        /* Tabs mejoradas */
        .info-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 32px;
            border-bottom: 3px solid #e5e7eb;
            flex-wrap: wrap;
            padding-bottom: 4px;
        }

        .info-tab {
            padding: 14px 28px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            bottom: -3px;
        }

        .info-tab:hover {
            color: #1976d2;
            background: rgba(25, 118, 210, 0.05);
        }

        .info-tab.active {
            color: #1976d2;
            border-bottom-color: #1976d2;
            background: rgba(25, 118, 210, 0.08);
        }

        .info-tab i {
            font-size: 18px;
        }

        /* Contenido de tabs */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Grid de cards mejorado */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        /* Cards mejoradas con efectos */
        .info-card {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            cursor: pointer;
        }

        .info-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: #1976d2;
        }

        /* Imagen de la card con efecto zoom */
        .info-card-img {
            width: 100%;
            height: 200px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .info-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .info-card:hover .info-card-img img {
            transform: scale(1.1);
        }

        /* Overlay oscuro en hover */
        .info-card-img::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .info-card:hover .info-card-img::after {
            opacity: 1;
        }

        /* Body de la card */
        .info-card-body {
            padding: 20px;
            background: white;
        }

        .info-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            line-height: 1.4;
            transition: color 0.3s ease;
        }

        .info-card:hover .info-card-title {
            color: #1976d2;
        }

        /* Estados de carga y vacío */
        .loading-state,
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .loading-state i,
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 0.8; }
        }

        /* Scrollbar personalizada */
        .modal-info-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-info-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .modal-info-body::-webkit-scrollbar-thumb {
            background: #1976d2;
            border-radius: 10px;
        }

        .modal-info-body::-webkit-scrollbar-thumb:hover {
            background: #1565c0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-info-content {
                border-radius: 16px;
                max-height: 95vh;
            }

            .modal-info-header {
                padding: 20px;
            }

            .modal-info-header h2 {
                font-size: 22px;
            }

            .modal-info-header h2 i {
                font-size: 24px;
            }

            .modal-info-body {
                padding: 20px;
            }

            .cards-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .info-tab {
                padding: 12px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background-color: #101827;">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="Imagenes/bustrak-logo.png" alt="Bustrak Logo" style="height: 60px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            @guest
                <div class="hero-buttons d-flex gap-2">
                    <a href="/login" class="btn btn-primary btn-login">Iniciar Sesión</a>
                    <a href="/registro" class="btn btn-success btn-registro">Regístrate</a>
                </div>
            @endguest
            @auth
                <div class="d-flex gap-2 align-items-center">
                    <span class="text-white me-2">¡Hola, {{ auth()->user()->name }}!</span>
                    <a href="{{ route('cliente.perfil') }}"
                       class="btn btn-outline-light btn-sm px-3 rounded-pill shadow-sm">
                        <i class="fas fa-user me-1"></i> Perfil
                    </a>
                </div>
            @endauth



        </div>

    </div>
</nav>

<!-- HERO -->
<div class="container hero-section">
    <div class="row align-items-center">
        <!-- Columna izquierda: Texto -->
        <div class="col-md-6">
            <h1 class="hero-title">
                Encuentra pasajes<br>
                en bus baratos<br>
                en Honduras
            </h1>
            <p class="hero-subtitle">
                Busca y compra fácilmente tu próximo pasaje en bus con Bustrak, tu mejor opción.
            </p>
        </div>
        <!-- Columna derecha: Imagen -->
        <div class="col-md-6 text-center">
            <img src="/catalago/img/fondo.png" alt="Imagen hero" class="img-fluid"
                 style="max-height:400px;">
        </div>
    </div>
</div>
<hr style="border: 0; height: 1px; background-color: #1976d2; margin: 50px 0;">


<!-- INFORMACIÓN -->
<div class="container mt-4">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="info-box d-flex align-items-start gap-3">
                <img class="info-icon" src="https://www.busbud.com/pubweb-assets/images/usp/52ba7ae.usp-v3-trust-coverage.png">
                <p class="info-text">
                    Únete a más de 1 millon de viajeros al rededor de todo el pais.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box d-flex align-items-start gap-3">
                <img class="info-icon" src="https://www.busbud.com/pubweb-assets/images/usp/3136d75.usp-v3-support.png">
                <p class="info-text">
                    Disfruta de nuestro servicio de atención al cliente disponible 24/7.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box d-flex align-items-start gap-3">
                <img class="info-icon" src="https://www.busbud.com/pubweb-assets/images/usp/5619a22.usp-v3-cfar.png">
                <p class="info-text">
                    Puedes cancelar en cualquier momento y recibir un reembolso.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- DEPARTAMENTOS -->
<section class="py-5">
    <hr style="border: 0; height: 1px; background-color: #1976d2; margin: 50px 0;">

    <div class="container py-5">
        <h2 class="mb-4 text-center">Destinos turísticos en nuestra bella Honduras</h2>
        <div class="row row-cols-1 row-cols-md-4 g-3 departments">
            @foreach($departamentos as $departamento)
                <div class="col">
                    <div class="card" onclick="abrirModalInfo({{ $departamento->id }}, '{{ $departamento->nombre }}')">
                        <img src="{{ $departamento->imagen }}" alt="{{ $departamento->nombre }}">
                        <div class="card-body text-center">
                            <h6>{{ $departamento->nombre }}</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <hr style="border: 0; height: 1px; background-color: #1976d2; margin: 50px 0;">

    <div class="container mt-5">
        <div class="row align-items-center">
            <!-- Imagen a la izquierda -->
            <div class="col-md-4 text-center">
                <img src="/catalago/img/fondo1.png" class="img-fluid" alt="Imagen ilustrativa">
            </div>
            <!-- Texto a la derecha -->
            <div class="col-md-8">
                <h2 class="fw-bold mb-3">¿Por qué Bustrak es tu mejor opción?</h2>
                <p class="mb-2" style="font-size: 18px;">La mejor manera de comprar pasajes de bus</p>
                <p style="font-size: 16px; line-height: 1.6;">
                    Bustrak.com te ofrece una variedad de rutas a 16 departamentos del pais con la mayor comodidad en nuestros buses super cómodos, seguridad 100% fiable con nuestros choferes experimentados, tendrás el mejor viaje por carretera, porque Bustrak te conecta por toda Honduras.                </p>
            </div>
        </div>
    </div>
    <hr style="border: 0; height: 1px; background-color: #1976d2; margin: 50px 0;">

    <div class="container">
        <h2 class="text-center fw-bold mb-4">Beneficios en algunos de nuestros buses</h2>
        <p class="text-center mb-5" style="font-size: 18px;">
            En <strong>Bustrak</strong> su seguridad y comodidad es nuestra prioridad.<br>
            Nuestros servicios le permiten viajar por Honduras de forma confortable para que tenga un recorrido placentero y seguro con nuestros conductores certificados. Además, le ofrecemos los siguientes servicios exclusivos en nuestras unidades:
        </p>

        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-wifi fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>WIFI a bordo</h6>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-snowflake fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>Aire acondicionado</h6>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-suitcase fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>Kit de viajes</h6>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-coffee fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>Paradas de cortesía</h6>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-bolt fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>Conexiones eléctricas y USB</h6>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-map-marker-alt fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>Sistema de geolocalización GPS</h6>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-tv fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>Entretenimiento a bordo</h6>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-video fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>Cámaras de video vigilancia</h6>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="info-card text-center p-3">
                    <i class="fas fa-ellipsis-h fa-2x mb-2" style="color:#1976d2;"></i>
                    <h6>Y mucho más</h6>
                </div>
            </div>


        </div>
    </div>


    <hr style="border: 0; height: 1px; background-color: #1976d2; margin: 50px 0;">

    <!-- SECCIÓN: Prepare su viaje -->
    <section class="container my-5">
        <h2 class="text-center mb-4">Prepare su viaje</h2>
        <p class="text-center mb-5">Recomendaciones para hacer de su viaje una experiencia inolvidable.</p>

        <div class="row g-4 text-center">
            <!-- Fila 1 -->
            <div class="col-12 col-md-6">
                <img loading="lazy" alt="Planee su viaje" src="https://www.ticabus.com/documents/7092829/7093204/ico_planea.svg/227152e1-824b-3052-c301-3ee496d1ef9f?t=1732002538128" class="img-fluid mb-2" style="max-height:50px;">
                <h6>Planee su viaje</h6>
                <p>Elija su destino, fecha de viaje, horario y punto de abordaje.</p>
            </div>

            <div class="col-12 col-md-6">
                <img loading="lazy" alt="Compre su boleto" src="https://www.ticabus.com/documents/7092829/7093204/ico_compra.svg/537e738f-b33c-4091-7cfa-1053d9b4fa2c?t=1732002538017" class="img-fluid mb-2" style="max-height:50px;">
                <h6>Compre su boleto</h6>
                <p>Adquiera sus boletos en línea aquí.</p>
            </div>

            <!-- Fila 2 -->
            <div class="col-12 col-md-6">
                <img loading="lazy" alt="Aborde a tiempo" src="https://www.ticabus.com/documents/7092829/7093204/ico_aborda.svg/101af751-58dc-674b-ee0e-00bd950ce590?t=1732002537917" class="img-fluid mb-2" style="max-height:50px;">
                <h6>Aborde a tiempo</h6>
                <p>Preséntese una hora antes de su horario de salida en el punto de abordaje para el chequeo del equipaje.</p>
            </div>

            <div class="col-12 col-md-6">
                <img loading="lazy" alt="Equipaje" src="https://www.ticabus.com/documents/7092829/7093204/ico_equipaje.svg/7096cfbe-1126-f415-2858-a8dfab33ce35?t=1732002537690" class="img-fluid mb-2" style="max-height:50px;">
                <h6>Equipaje</h6>
                <p>Cada pasajero tiene derecho a llevar dos maletas de 15 kilos cada una y un bolso de mano.</p>
            </div>
        </div>

        <!-- AVISO AL FINAL -->
        <div class="mt-4 p-4 bg-light rounded text-center">
            <small>
                *La empresa no se hace responsable por pérdida o daños de valores, caja frágil, equipo de cómputo, electrónico y electrodoméstico.
                Los objetos quedan bajo la responsabilidad del pasajero.
            </small>
        </div>
    </section>



</section>

<!-- MODAL -->
<div class="modal-info" id="modalInfo">
    <div class="modal-info-content">
        <div class="modal-info-header">
            <h2 id="modalInfoTitle"><i class="fas fa-map-marker-alt"></i> Departamento</h2>
            <button class="modal-info-close" onclick="cerrarModalInfo()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-info-body">
            <div class="info-tabs">
                <button class="info-tab active" onclick="cambiarTab('lugares')"><i class="fas fa-landmark"></i> Lugares Turísticos</button>
                <button class="info-tab" onclick="cambiarTab('comidas')"><i class="fas fa-utensils"></i> Comidas Típicas</button>
            </div>
            <div id="lugaresTab" class="tab-content active">
                <div class="cards-grid" id="lugaresGrid"></div>
            </div>
            <div id="comidasTab" class="tab-content">
                <div class="cards-grid" id="comidasGrid"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    async function abrirModalInfo(departamentoId, nombreDepartamento) {
        try {
            // Mostrar modal con animación
            const modal = document.getElementById('modalInfo');
            modal.classList.add('show');

            // Actualizar título
            document.getElementById('modalInfoTitle').innerHTML =
                `<i class="fas fa-map-marker-alt"></i> ${nombreDepartamento}`;

            // Mostrar estado de carga con icono animado
            const loadingHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Cargando información...</p>
            </div>
        `;
            document.getElementById('lugaresGrid').innerHTML = loadingHTML;
            document.getElementById('comidasGrid').innerHTML = loadingHTML;

            // Hacer petición AJAX
            const response = await fetch(`/api/departamento/${departamentoId}`);

            if (!response.ok) {
                throw new Error('Error al cargar la información');
            }

            const data = await response.json();

            // Generar HTML de lugares con estructura mejorada
            const lugaresHtml = data.lugares && data.lugares.length > 0
                ? data.lugares.map(lugar => `
                <div class="info-card">
                    <div class="info-card-img">
                        <img src="${lugar.imagen}"
                             alt="${lugar.nombre}"
                             onerror="this.src='https://via.placeholder.com/300x200?text=Sin+Imagen'">
                    </div>
                    <div class="info-card-body">
                        <div class="info-card-title">${lugar.nombre}</div>
                    </div>
                </div>
            `).join('')
                : `
                <div class="empty-state">
                    <i class="fas fa-map-marked-alt"></i>
                    <p>No hay lugares turísticos registrados</p>
                </div>
            `;

            // Generar HTML de comidas con estructura mejorada
            const comidasHtml = data.comidas && data.comidas.length > 0
                ? data.comidas.map(comida => `
                <div class="info-card">
                    <div class="info-card-img">
                        <img src="${comida.imagen}"
                             alt="${comida.nombre}"
                             onerror="this.src='https://via.placeholder.com/300x200?text=Sin+Imagen'">
                    </div>
                    <div class="info-card-body">
                        <div class="info-card-title">${comida.nombre}</div>
                    </div>
                </div>
            `).join('')
                : `
                <div class="empty-state">
                    <i class="fas fa-utensils"></i>
                    <p>No hay comidas típicas registradas</p>
                </div>
            `;

            // Insertar en el DOM con pequeño delay para mejor UX
            setTimeout(() => {
                document.getElementById('lugaresGrid').innerHTML = lugaresHtml;
                document.getElementById('comidasGrid').innerHTML = comidasHtml;
            }, 300);

        } catch (error) {
            console.error('Error:', error);

            // Mostrar error en el modal en lugar de cerrarlo
            const errorHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
                <p style="color: #ef4444;">Error al cargar la información</p>
                <p style="font-size: 14px;">Por favor, intente nuevamente</p>
            </div>
        `;
            document.getElementById('lugaresGrid').innerHTML = errorHTML;
            document.getElementById('comidasGrid').innerHTML = errorHTML;
        }
    }

    function cerrarModalInfo() {
        document.getElementById('modalInfo').classList.remove('show');
    }

    function cambiarTab(tab) {
        // Remover active de todos los tabs
        document.querySelectorAll('.info-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Agregar active al tab seleccionado
        event.target.classList.add('active');
        document.getElementById(tab + 'Tab').classList.add('active');
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalInfo')?.addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalInfo();
        }
    });

    // Cerrar modal con tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalInfo();
        }
    });
</script>


<!-- Botón de subir al inicio -->
<a href="#" id="backToTop" title="Ir arriba">
    <i class="fas fa-arrow-up"></i>
</a>
<script>
    // Mostrar el botón cuando se hace scroll
    window.addEventListener('scroll', function() {
        const btn = document.getElementById('backToTop');
        if (window.scrollY > 200) {
            btn.style.display = 'flex';
        } else {
            btn.style.display = 'none';
        }
    });

    // Al hacer click, subir suavemente al inicio
    document.getElementById('backToTop').addEventListener('click', function(e){
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>


</body>
</html>
