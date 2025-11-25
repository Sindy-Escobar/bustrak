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

        /* MODAL */
        .modal-info { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 3000; padding: 20px; align-items: center; justify-content: center; }
        .modal-info.show { display: flex; }
        .modal-info-content { background: white; border-radius: 12px; max-width: 1200px; width: 100%; box-shadow: 0 10px 40px rgba(0,0,0,0.3); max-height: 85vh; overflow-y: auto; }
        .modal-info-header { background: linear-gradient(180deg, #1e63b8, #1976d2); color: white; padding: 24px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 10; }
        .modal-info-header h2 { margin: 0; font-size: 1.5rem; font-weight: 700; }
        .modal-info-close { background: rgba(255,255,255,0.2); border: none; color: white; width: 40px; height: 40px; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .modal-info-close:hover { background: rgba(255,255,255,0.3); }
        .modal-info-body { padding: 24px; }
        .info-tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; flex-wrap: wrap; }
        .info-tab { padding: 10px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6b7280; border-bottom: 3px solid transparent; transition: all 0.3s; font-size: 14px; }
        .info-tab.active { color: #1976d2; border-bottom-color: #1976d2; }
        .info-tab:hover { color: #1976d2; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .info-card { border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; transition: all 0.3s; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .info-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0,0,0,0.15); }
        .info-card-img { width: 100%; height: 160px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: white; }
        .info-card-body { padding: 16px; }
        .info-card-title { font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 8px; }
        @media (max-width: 768px) { .cards-grid { grid-template-columns: 1fr; } }
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
            <div class="col"><div class="card" onclick="abrirModalInfo('Atlántida')"><img src="/catalago/img/atlantida.jpg"><div class="card-body text-center"><h6>Atlántida</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Choluteca')"><img src="/catalago/img/choluteca.jpg"><div class="card-body text-center"><h6>Choluteca</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Colón')"><img src="/catalago/img/colon.jpg"><div class="card-body text-center"><h6>Colón</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Comayagua')"><img src="/catalago/img/comayagua.jpg"><div class="card-body text-center"><h6>Comayagua</h6></div></div></div>

            <div class="col"><div class="card" onclick="abrirModalInfo('Copán')"><img src="/catalago/img/copan.jpg" class="card-img-top"><div class="card-body text-center"><h6>Copán</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Cortés')"><img src="/catalago/img/cortes.jpg"><div class="card-body text-center"><h6>Cortés</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('El Paraíso')"><img src="/catalago/img/elparaiso.jpg"><div class="card-body text-center"><h6>El Paraíso</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Francisco Morazán')"><img src="/catalago/img/francisco.jpg"><div class="card-body text-center"><h6>Francisco Morazán</h6></div></div></div>


            <div class="col"><div class="card" onclick="abrirModalInfo('Intibucá')"><img src="/catalago/img/intibuca.jpg"><div class="card-body text-center"><h6>Intibucá</h6></div></div></div>

            <div class="col"><div class="card" onclick="abrirModalInfo('La Paz')"><img src="/catalago/img/lapaz.jpg"><div class="card-body text-center"><h6>La Paz</h6></div></div></div>

            <div class="col"><div class="card" onclick="abrirModalInfo('Lempira')"><img src="/catalago/img/lempira.jpg"><div class="card-body text-center"><h6>Lempira</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Ocotepeque')"><img src="/catalago/img/ocotepeque.jpg"><div class="card-body text-center"><h6>Ocotepeque</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Olancho')"><img src="/catalago/img/olancho.jpg"><div class="card-body text-center"><h6>Olancho</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Santa Bárbara')"><img src="/catalago/img/santabarbara.jpg"><div class="card-body text-center"><h6>Santa Bárbara</h6></div></div></div>

            <div class="col"><div class="card" onclick="abrirModalInfo('Valle')"><img src="/catalago/img/valle.jpg"><div class="card-body text-center"><h6>Valle</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Yoro')"><img src="/catalago/img/yoro.jpg"><div class="card-body text-center"><h6>Yoro</h6></div></div></div>
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
    const destinosData = {
        'Atlántida': {
            lugares: [
                { nombre: 'Parque Nacional Pico Bonito', imagen: '/catalago/img/lugares/parque pico bonito.jpg' },
                { nombre: 'Las Cascadas de Zacate', imagen: '/catalago/img/lugares/cascada zacate.jpg' }
            ],
            comidas: [
                { nombre: 'Pan de Coco', imagen: '/catalago/img/comidas/Pan de Coco.jpg' },
                { nombre: 'Tapado Costeño', imagen: '/catalago/img/comidas/Tapado Costeño.jpg' }
            ]
        },
        'Cortés': {
            lugares: [
                { nombre: 'Cataratas de Pulhapanzak', imagen: '/catalago/img/lugares/Cataratas de Pulhapanzak.jpg' },
                { nombre: 'Fortaleza de San Fernando de Omoa', imagen: '/catalago/img/lugares/Fortaleza de San Fernando de Omoa.jpg' }
            ],
            comidas: [
                { nombre: 'Baleadas', imagen: '/catalago/img/comidas/Baleadas.jpg' },
                { nombre: 'Pollo Chuco', imagen: '/catalago/img/comidas/Pollo Chuco.jpg' }
            ]
        },
        'Francisco Morazán': {
            lugares: [
                { nombre: 'Cristo del Picacho', imagen: '/catalago/img/lugares/picacho.jpg' },
                { nombre: 'Parque Nacional La Tigra', imagen: '/catalago/img/lugares/Parque Nacional La Tigra.jpg' }
            ],
            comidas: [
                { nombre: 'Carne Asada', imagen: '/catalago/img/comidas/Carne Asada.jpg' },
                { nombre: 'Nacatamales', imagen: '/catalago/img/comidas/Nacatamales.jpg' }
            ]
        },
        'Choluteca': {
            lugares: [
                { nombre: 'Playas de Cedeño', imagen: '/catalago/img/lugares/Playas de Cedeño.jpg' },
                { nombre: 'Museo de Historia Regional', imagen: '/catalago/img/lugares/Museo de Historia Regional.jpg' }
            ],
            comidas: [
                { nombre: 'Curiles', imagen: '/catalago/img/comidas/Curiles.jpg' },
                { nombre: 'Tamales Pisques', imagen: '/catalago/img/comidas/Tamales Pisques.jpg' }
            ]
        },
        'Colón': {
            lugares: [
                { nombre: 'Playas de Trujillo', imagen: '/catalago/img/lugares/Playas de Trujillo.jpg' },
                { nombre: 'Parque Nacional Capiro y Calentura', imagen: '/catalago/img/lugares/Parque Nacional Capiro y Calentura.jpg' }
            ],
            comidas: [
                { nombre: 'Machuca', imagen: '/catalago/img/comidas/Machuca.jpg' },
                { nombre: 'Sopa de Caracol', imagen: '/catalago/img/comidas/Sopa de Caracol.jpg' }
            ]
        },
        'Comayagua': {
            lugares: [
                { nombre: 'Catedral de Comayagua', imagen: '/catalago/img/lugares/Catedral de Comayagua.jpg' },
                { nombre: 'Cuevas de Taulabe', imagen: '/catalago/img/lugares/Cuevas de Taulabe.jpg' }
            ],
            comidas: [
                { nombre: 'Sopa de Capirotada', imagen: '/catalago/img/comidas/Sopa de Capirotada.jpg' },
                { nombre: 'Pinol', imagen: '/catalago/img/comidas/Pinol.jpg' }
            ]
        },
        'Copán': {
            lugares: [
                { nombre: 'Ruinas de Copán', imagen: '/catalago/img/lugares/Ruinas de Copán.jpg' },
                { nombre: 'Tuneles Mayas', imagen: '/catalago/img/lugares/Tuneles Mayas.jpg' }
            ],
            comidas: [
                { nombre: 'Montuca', imagen: '/catalago/img/comidas/Montuca.jpg' },
                { nombre: 'Totopostes', imagen: '/catalago/img/comidas/Totopostes.jpg' }
            ]
        },
        'El Paraíso': {
            lugares: [
                { nombre: 'Cascada de El Cacao', imagen: '/catalago/img/lugares/Cascada El Cacao.jpg' },
                { nombre: 'Montaña de Teupasenti', imagen: '/catalago/img/lugares/Montaña de Teupasenti.jpg' }
            ],
            comidas: [
                { nombre: 'Ticuco', imagen: '/catalago/img/comidas/Ticuco.jpg' },
                { nombre: 'Ayote en Miel', imagen: '/catalago/img/comidas/Ayote en Miel.jpg' }
            ]
        },
        'Intibucá': {
            lugares: [
                { nombre: 'Lagos de Chiligatoro', imagen: '/catalago/img/lugares/Lagos de Chiligatoro.jpg' },
                { nombre: 'Gruta de La Esperanza', imagen: '/catalago/img/lugares/Gruta de La Esperanza.jpg' }
            ],
            comidas: [
                { nombre: 'Sopa de Gallina India', imagen: '/catalago/img/comidas/Sopa de Gallina India.jpg' },
                { nombre: 'Chicha', imagen: '/catalago/img/comidas/Chicha.jpg' }
            ]
        },
        'La Paz': {
            lugares: [
                { nombre: 'Cascada de Santa Elena', imagen: '/catalago/img/lugares/Cascada de Santa Elena.jpg' },
                { nombre: 'Parque Arqueológico Yarumela', imagen: '/catalago/img/lugares/Parque Arqueológico Yarumela.jpg' }
            ],
            comidas: [
                { nombre: 'Chancletas de Pataste', imagen: '/catalago/img/comidas/Chancletas de Pataste.jpg' },
                { nombre: 'Sopa de Albóndigas', imagen: '/catalago/img/comidas/Sopa de Albóndigas.jpg' }
            ]
        },
        'Lempira': {
            lugares: [
                { nombre: 'Parque Nacional Celaque', imagen: '/catalago/img/lugares/Parque Nacional Celaque.jpg' },
                { nombre: 'Monumento a Lempira', imagen: '/catalago/img/lugares/Monumento a Lempira.jpg' }
            ],
            comidas: [
                { nombre: 'Atol Shuco', imagen: '/catalago/img/comidas/Atol Shuco.jpg' },
                { nombre: 'Chilate', imagen: '/catalago/img/comidas/Chicha.jpg' }
            ]
        },
        'Ocotepeque': {
            lugares: [
                { nombre: 'La Casa de la Cultura de Ocotepeque', imagen: '/catalago/img/lugares/La Casa de la Cultura de Ocotepeque.jpg' },
                { nombre: 'Ruinas de la Antigua Ocotepeque', imagen: '/catalago/img/lugares/Ruinas de la Antigua Ocotepeque.jpg' }
            ],
            comidas: [
                { nombre: 'Sopa de Tortas de Pescado', imagen: '/catalago/img/comidas/Sopa de Tortas de Pescado.jpg' },
                { nombre: 'Tamalitos de Cambray', imagen: '/catalago/img/comidas/Tamalitos de Cambray.jpg' }
            ]
        },
        'Olancho': {
            lugares: [
                { nombre: 'Parque Nacional Sierra de Agalta', imagen: '/catalago/img/lugares/Parque Nacional Sierra de Agalta.jpg' },
                { nombre: 'Cuevas de Cuyamel', imagen: '/catalago/img/lugares/Cuevas de Cuyamel.jpg' }
            ],
            comidas: [
                { nombre: 'Cuajada en Penca', imagen: '/catalago/img/comidas/Cuajada en Penca.jpg' },
                { nombre: 'Carne de res con Yuca', imagen: '/catalago/img/comidas/Carne de res con Yuca.jpg' }
            ]
        },
        'Santa Bárbara': {
            lugares: [
                { nombre: 'Parque Nacional Montaña de Santa Bárbara', imagen: '/catalago/img/lugares/Parque Nacional Montaña de Santa Bárbara.jpg' },
                { nombre: 'Cuevas de la Kinkora', imagen: '/catalago/img/lugares/Cuevas de la Kinkora.jpg' }
            ],
            comidas: [
                { nombre: 'Montucas', imagen: '/catalago/img/comidas/Montuca.jpg' },
                { nombre: 'Atol de Piña', imagen: '/catalago/img/comidas/Atol de Piña.jpg' }
            ]
        },
        'Valle': {
            lugares: [
                { nombre: 'Puerto de San Lorenzo', imagen: '/catalago/img/lugares/Puerto de San Lorenzo.jpg' },
                { nombre: 'Casco Urbano de Amapala', imagen: '/catalago/img/lugares/Casco Urbano de Amapala.jpg' }
            ],
            comidas: [
                { nombre: 'Pescado Frito', imagen: '/catalago/img/comidas/Pescado Frito.jpg' },
                { nombre: 'Sopa Marinera', imagen: '/catalago/img/comidas/Sopa Marinera.jpg' }
            ]
        },
        'Yoro': {
            lugares: [
                { nombre: 'Parque Nacional Pico Pijol', imagen: '/catalago/img/lugares/Parque Nacional Pico Pijol.jpg' },
                { nombre: 'Cuevas de la Lluvia de Peces', imagen: '/catalago/img/lugares/Cuevas de la Lluvia de Peces.jpg' }
            ],
            comidas: [
                { nombre: 'Sopa de Jutes', imagen: '/catalago/img/comidas/Sopa de Jutes.jpg' },
                { nombre: 'Sopa de Frijoles con Hueso de Cerdo', imagen: '/catalago/img/comidas/Sopa de Frijoles con Hueso de Cerdo.jpg' }
            ]
        }
    };

    function abrirModalInfo(departamento) {
        const info = destinosData[departamento];
        if (!info) return;
        document.getElementById('modalInfoTitle').innerHTML = `<i class="fas fa-map-marker-alt"></i> ${departamento}`;
        const lugaresHtml = info.lugares.map(lugar => `<div class="info-card"><div class="info-card-img"><img src="${lugar.imagen}" alt="${lugar.nombre}" style="width:100%; height:100%; object-fit:cover;"></div><div class="info-card-body"><div class="info-card-title">${lugar.nombre}</div></div></div>`).join('');
        const comidasHtml = info.comidas.map(comida => `<div class="info-card"><div class="info-card-img"><img src="${comida.imagen}" alt="${comida.nombre}" style="width:100%; height:100%; object-fit:cover;"></div><div class="info-card-body"><div class="info-card-title">${comida.nombre}</div></div></div>`).join('');
        document.getElementById('lugaresGrid').innerHTML = lugaresHtml;
        document.getElementById('comidasGrid').innerHTML = comidasHtml;
        document.getElementById('modalInfo').classList.add('show');
    }

    function cerrarModalInfo() {
        document.getElementById('modalInfo').classList.remove('show');
    }

    function cambiarTab(tab) {
        document.querySelectorAll('.info-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        event.target.classList.add('active');
        document.getElementById(tab + 'Tab').classList.add('active');
    }
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
