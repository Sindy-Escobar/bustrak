<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Asientos de Buses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-transform: uppercase;
            height: 35px;
            line-height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-login {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-login:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .blurred-container {
            position: relative;
        }

        .btn-registro {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-registro:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        body { font-family: Arial, sans-serif; }
        .hero {
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
            background: linear-gradient(to right, rgba(0,0,0,0.5), rgba(0,0,0,0.3)), url('https://www.shutterstock.com/image-illustration/image-bus-on-road-600nw-2578468007.jpg') no-repeat center center;
            background-size: cover;
            text-align: center;
        }
        .hero h1 { font-size: 3rem; font-weight: bold; }
        .hero p { font-size: 1.5rem; margin-bottom: 20px; }
        .btn-login { font-size: 1.2rem; padding: 10px 25px; }

        .search-section {
            margin-top: -50px;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 10;
        }

        .blurred {
            filter: blur(4px);
            pointer-events: none; /* evita clics sobre las cards borrosas */
            user-select: none;
        }
        .catalog-btn {
            position: absolute;
            bottom: 20px;
            top : 70%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
        }
        .hero-buttons .btn {
            font-size: 1.2rem;   /* Tamaño de la letra */
            padding: 10px 25px;  /* Altura y ancho del botón */
            border-radius: 8px;  /* Opcional, para bordes redondeados */
        }
        .hero-buttons .btn {
            font-size: 1rem;   /* Tamaño de la letra */
            padding: 1px 5px;  /* Altura y ancho del botón */
            border-radius: 8px;  /* Opcional, para bordes redondeados */
        }



        .departments img { height: 150px; object-fit: cover; border-radius: 10px; }
        .departments .card { cursor: pointer; transition: transform 0.2s; }
        .departments .card:hover { transform: scale(1.05); }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero position-relative">
    <div class="container">
        <h1>Reserva tu Asiento de Bus Fácil y Rápido</h1>
        <p>Explora rutas, horarios y promociones exclusivas para ti.</p>
        <h2>Bustrak, tu mejor opcion</h2>

    </div>

    <!-- Botones en la esquina superior derecha -->
    <div class="hero-buttons position-absolute top-0 end-0 m-3">
        <a href="/login" class="btn btn-primary btn-login me-2">Iniciar Sesión</a>
        <a href="/registro" class="btn btn-success btn-registro">Registrate</a>
    </div>
</section>


<!-- Formulario de búsqueda -->
<section class="search-section">
    <form class="row g-3">
        <div class="col-md-6">
            <label for="origen" class="form-label">Origen</label>
            <select class="form-select" id="origen">
                <option selected>Seleccione un origen</option>
                <option>San Pedro Sula</option>
                <option>Tegucigalpa</option>
                <option>La Ceiba</option>
                <option>San Lorenzo</option>
                <option>Danli</option>
                <option>Santa Barbara</option>
                <option>Choluteca</option>
                <option>Trujillo</option>
                <option>Santa Rosa de Copan</option>
                <option>La Esperanza</option>
                <option>Gracias</option>
                <option>Juticalpa</option>
                <option>La paz</option>
                <option>Yoro</option>
                <option>Comayagua</option>
                <option>Nueva Ocotepeque</option>

            </select>
        </div>
        <div class="col-md-6">
            <label for="destino" class="form-label">Destino</label>
            <select class="form-select" id="destino">
                <option selected>Seleccione un destino</option>
                <option>San Pedro Sula</option>
                <option>Tegucigalpa</option>
                <option>La Ceiba</option>
                <option>San Lorenzo</option>
                <option>Danli</option>
                <option>Santa Barbara</option>
                <option>Choluteca</option>
                <option>Trujillo</option>
                <option>Santa Rosa de Copan</option>
                <option>La Esperanza</option>
                <option>Gracias</option>
                <option>Juticalpa</option>
                <option>La paz</option>
                <option>Yoro</option>
                <option>Comayagua</option>
                <option>Nueva Ocotepeque</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="fecha_ida" class="form-label">Fecha de Ida</label>
            <input type="date" class="form-control" id="fecha_ida">
        </div>
        <div class="col-md-6">
            <label for="fecha_regreso" class="form-label">Fecha de Regreso</label>
            <input type="date" class="form-control" id="fecha_regreso">
        </div>
        <div class="col-md-6">
            <label for="pasajeros" class="form-label">Número de Pasajeros</label>
            <input type="number" class="form-control" id="pasajeros" value="1" min="1">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <a href="/login" class="btn btn-primary w-100">Buscar Viaje</a>


        </div>
    </form>
</section>

<!-- Sección Departamentos de Honduras -->
<section class="py-5 position-relative">
    <div class="container">
        <h2 class="mb-4 text-center">Destinos en Honduras</h2>
        <div class="row row-cols-2 row-cols-md-4 g-3 departments">
            <div class="col"><div class="card"><img src="https://i0.wp.com/mntv.hn/wp-content/uploads/2025/04/explore-la-belleza-de-atlantida-y-descubra-sus-tesoros-turisticos-en-semana-santa-10.webp?fit=1200%2C799&ssl=1" class="card-img-top"><div class="card-body text-center"><h6>Atlántida</h6></div></div></div>
            <div class="col"><div class="card"><img src="https://i0.wp.com/www.hondurastips.hn/wp-content/uploads/2019/04/RS1104816_MH-puente-choluteca-10-1-copy.jpg?resize=1000%2C500&ssl=1" class="card-img-top"><div class="card-body text-center"><h6>Choluteca</h6></div></div></div>
            <div class="col"><div class="card"><img src="https://upload.wikimedia.org/wikipedia/commons/c/cb/CA%C3%91ONES_FORTALEZA_SANTA_BARBARA_-_panoramio_-_stanleyatala.jpg" class="card-img-top"><div class="card-body text-center"><h6>Colon</h6></div></div></div>
            <div class="col"><div class="card"><img src="https://www.hondurasensusmanos.com/turismo/wp-content/uploads/2021/04/Comayagua.jpg" class="card-img-top"><div class="card-body text-center"><h6>Comayagua</h6></div></div></div>



            <div class="col blurred"><div class="card"><img src="https://i0.wp.com/visithonduras.iht.hn/wp-content/uploads/2024/05/CUEVAS.jpg?fit=720%2C720&ssl=1" class="card-img-top"><div class="card-body text-center"><h6>Olancho</h6></div></div></div>
            <div class="col blurred"><div class="card"><img src="https://www.laprensa.hn/binrepository/600x338/0c0/0d0/none/11004/NXVQ/np-playas4-011016_LP1004815_MG67348071.jpg" class="card-img-top"><div class="card-body text-center"><h6>Cortés</h6></div></div></div>
            <div class="col blurred"><div class="card"><img src="https://diarioroatan.com/wp-content/uploads/2019/10/361B8C6B-516E-41A2-9F07-DF21065FE7F6.jpeg" class="card-img-top"><div class="card-body text-center"><h6>El Paraíso</h6></div></div></div>
            <div class="col blurred"><div class="card"><img src="https://i.pinimg.com/1200x/3c/00/e6/3c00e6102af48a46c831dcf235f7c831.jpg" class="card-img-top"><div class="card-body text-center"><h6>Francisco Morazán</h6></div></div></div>
        </div>

        <div class="catalog-btn text-center">
            <a href="/catalogo" class="btn btn-primary btn-lg">Ver Catálogo</a>
        </div>
    </div>
</section>

<!-- Sección de Servicios -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="mb-4">Nuestros Servicios</h2>
        <div class="row">
            <div class="col-md-4 mb-3 service">
                <img src="https://www.aduanas.gob.hn/wp-content/uploads/2023/02/Mapa-AduanasMapa-scaled.jpg" class="img-fluid rounded" alt="Rutas Populares">
                <h5 class="mt-2">Rutas Populares</h5>
                <p>Encuentra los destinos más solicitados por nuestros pasajeros.</p>
            </div>
            <div class="col-md-4 mb-3 service">
                <img src="https://www.banrural.com.hn/wp-content/uploads/2024/05/TravelHub-min-1.jpg" class="img-fluid rounded" alt="Promociones">
                <h5 class="mt-2">Promociones</h5>
                <p>Aprovecha descuentos y promociones especiales en tus viajes.</p>
            </div>
            <div class="col-md-4 mb-3 service">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5qJbswnaa5-zWREh0F9dnxY-Ngilrzmid_w&s" class="img-fluid rounded" alt="Viaje Seguro">
                <h5 class="mt-2">Viaje Seguro</h5>
                <p>Viaja tranquilo con nuestro sistema de reservas confiable.</p>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
