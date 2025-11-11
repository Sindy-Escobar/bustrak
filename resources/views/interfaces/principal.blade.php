<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Asientos de Buses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            pointer-events: none;
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
            font-size: 1.2rem;
            padding: 10px 25px;
            border-radius: 8px;
        }
        .hero-buttons .btn {
            font-size: 1rem;
            padding: 1px 5px;
            border-radius: 8px;
        }

        .departments img { height: 150px; object-fit: cover; border-radius: 10px; }
        .departments .card { cursor: pointer; transition: transform 0.2s; }
        .departments .card:hover { transform: scale(1.05); }

        /* Estilos del modal de viajes */
        .modal-viajes {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 3000;
            overflow-y: auto;
            padding: 20px;
        }
        .modal-viajes.show { display: block; }
        .modal-viajes-content {
            background: white;
            border-radius: 12px;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        .modal-viajes-header {
            background: linear-gradient(180deg, #1e63b8, #1976d2);
            color: white;
            padding: 24px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-viajes-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .modal-viajes-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-viajes-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .modal-viajes-body {
            padding: 24px;
        }
        .viaje-card {
            border-left: 4px solid #1976d2;
            padding: 20px;
            background: #fafbfc;
            border-radius: 8px;
            margin-bottom: 12px;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
        }
        .viaje-card:hover {
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }
        .viaje-content {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr 1fr auto;
            gap: 16px;
            align-items: center;
        }
        .viaje-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .viaje-label {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .viaje-value {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }
        .viaje-precio {
            font-size: 20px;
            color: #1976d2;
            font-weight: 700;
        }
        .viaje-precio-compra {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-end;
        }
        .btn-comprar {
            background-color: #1976d2;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-comprar:hover { background-color: #1565c0; }
        .btn-eliminar {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-eliminar:hover { background-color: #dc2626; }
        .btn-eliminar:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .modal-waitlist {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 4000;
            align-items: center;
            justify-content: center;
        }
        .modal-waitlist.show { display: flex; }
        .modal-content-waitlist {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        .modal-waitlist h2 {
            color: #1976d2;
            margin-bottom: 15px;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-waitlist p {
            color: #6b7280;
            margin-bottom: 20px;
        }
        .waitlist-info {
            background: #e0f2fe;
            border-left: 4px solid #1976d2;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #1f2937;
            line-height: 1.8;
        }
        .waitlist-info strong {
            color: #1976d2;
            display: block;
            margin-bottom: 5px;
        }
        .modal-buttons {
            display: flex;
            gap: 10px;
        }
        .modal-buttons button {
            flex: 1;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-confirmar {
            background-color: #1976d2;
            color: white;
        }
        .btn-confirmar:hover {
            background-color: #1565c0;
        }
        .btn-cancelar {
            background-color: #ef4444;
            color: white;
        }
        .btn-cancelar:hover {
            background-color: #dc2626;
        }
        .no-viajes {
            background: #dbeafe;
            padding: 40px;
            border-radius: 12px;
            border: 2px dashed #3b82f6;
            text-align: center;
        }
        .no-viajes i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #3b82f6;
        }
        .no-viajes h3 {
            color: #1e40af;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        .no-viajes p {
            color: #1e40af;
        }
        @media (max-width: 768px) {
            .viaje-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Modal de confirmación de compra/eliminación -->
<div class="modal-waitlist" id="waitlistModal">
    <div class="modal-content-waitlist">
        <h2 id="modalTitle"><i class="fas fa-check-circle"></i> Confirmar Compra</h2>
        <p id="modalMessage">¿Deseas confirmar la compra de este boleto?</p>
        <div class="waitlist-info" id="waitlistDetails"></div>
        <div class="modal-buttons">
            <button class="btn-confirmar" onclick="confirmarAccion()"><i class="fas fa-check"></i> Confirmar</button>
            <button class="btn-cancelar" onclick="cerrarModalConfirmacion()"><i class="fas fa-times"></i> Cancelar</button>
        </div>
    </div>
</div>

<!-- Modal de viajes -->
<div class="modal-viajes" id="modalViajes">
    <div class="modal-viajes-content">
        <div class="modal-viajes-header">
            <h2 id="modalViajesTitle"><i class="fas fa-bus"></i> Viajes Disponibles</h2>
            <button class="modal-viajes-close" onclick="cerrarModalViajes()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-viajes-body">
            <div id="viajesContainer"></div>
        </div>
    </div>
</div>

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
        <div class="row row-cols-1 row-cols-md-3 g-3 departments">
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Cortés')"><img src="/catalago/img/cortes.jpg" class="card-img-top"><div class="card-body text-center"><h6>Cortés</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Atlántida')"><img src="/catalago/img/atlantida.jpg" class="card-img-top"><div class="card-body text-center"><h6>Atlántida</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Francisco Morazán')"><img src="/catalago/img/francisco.jpg" class="card-img-top"><div class="card-body text-center"><h6>Francisco Morazán</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Choluteca')"><img src="/catalago/img/choluteca.jpg" class="card-img-top"><div class="card-body text-center"><h6>Choluteca</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Comayagua')"><img src="/catalago/img/comayagua.jpg" class="card-img-top"><div class="card-body text-center"><h6>Comayagua</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Colón')"><img src="/catalago/img/colon.jpg" class="card-img-top"><div class="card-body text-center"><h6>Colón</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Copán')"><img src="/catalago/img/copan.jpg" class="card-img-top"><div class="card-body text-center"><h6>Copán</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('El Paraíso')"><img src="/catalago/img/elparaiso.jpg" class="card-img-top"><div class="card-body text-center"><h6>El Paraíso</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Yoro')"><img src="/catalago/img/yoro.jpg" class="card-img-top"><div class="card-body text-center"><h6>Yoro</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Gracias a Dios')"><img src="/catalago/img/gracias.jpg" class="card-img-top"><div class="card-body text-center"><h6>Gracias a Dios</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Intibucá')"><img src="/catalago/img/intibuca.jpg" class="card-img-top"><div class="card-body text-center"><h6>Intibucá</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Islas de la Bahía')"><img src="/catalago/img/islas.jpg" class="card-img-top"><div class="card-body text-center"><h6>Islas de la Bahía</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('La Paz')"><img src="/catalago/img/lapaz.jpg" class="card-img-top"><div class="card-body text-center"><h6>La Paz</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Lempira')"><img src="/catalago/img/lempira.jpg" class="card-img-top"><div class="card-body text-center"><h6>Lempira</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Ocotepeque')"><img src="/catalago/img/ocotepeque.jpg" class="card-img-top"><div class="card-body text-center"><h6>Ocotepeque</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Olancho')"><img src="/catalago/img/olancho.jpg" class="card-img-top"><div class="card-body text-center"><h6>Olancho</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Santa Bárbara')"><img src="/catalago/img/santabarbara.jpg" class="card-img-top"><div class="card-body text-center"><h6>Santa Bárbara</h6></div></div></div>
            <div class="col"><div class="card" onclick="mostrarViajesDepartamento('Valle')"><img src="/catalago/img/valle.jpg" class="card-img-top"><div class="card-body text-center"><h6>Valle</h6></div></div></div>
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
<script>
    let boletosComprados = 0;
    let viajeSeleccionado = null;
    let esEliminacion = false;
    let comprasUsuario = {};
    let asientosVendidos = {};
    let departamentoActual = '';

    const viajesDatos = [
        { ruta: 'Cortés', fecha: '2025-01-15', horario: '6:00 AM', duracion: '3h 30m', precio: 250, destino: 'Cortés', empresa: 'Transporte Express' },
        { ruta: 'Cortés', fecha: '2025-02-20', horario: '9:00 AM', duracion: '3h 30m', precio: 250, destino: 'Cortés', empresa: 'Buses del Norte' },
        { ruta: 'Atlántida', fecha: '2025-02-10', horario: '9:00 AM', duracion: '4h 15m', precio: 320, destino: 'Atlántida', empresa: 'Viajes Rápidos' },
        { ruta: 'Atlántida', fecha: '2025-03-15', horario: '7:30 AM', duracion: '4h 15m', precio: 320, destino: 'Atlántida', empresa: 'Línea Dorada' },
        { ruta: 'Francisco Morazán', fecha: '2025-03-20', horario: '12:00 PM', duracion: '2h 45m', precio: 180, destino: 'Francisco Morazán', empresa: 'Autobuses Unidos' },
        { ruta: 'Francisco Morazán', fecha: '2025-04-18', horario: '3:00 PM', duracion: '2h 45m', precio: 180, destino: 'Francisco Morazán', empresa: 'Transporte Express' },
        { ruta: 'Choluteca', fecha: '2025-04-12', horario: '7:30 AM', duracion: '5h 20m', precio: 380, destino: 'Choluteca', empresa: 'Buses del Norte' },
        { ruta: 'Choluteca', fecha: '2025-05-22', horario: '10:30 AM', duracion: '5h 20m', precio: 380, destino: 'Choluteca', empresa: 'Viajes Rápidos' },
        { ruta: 'Comayagua', fecha: '2025-05-08', horario: '3:00 PM', duracion: '3h 10m', precio: 210, destino: 'Comayagua', empresa: 'Línea Dorada' },
        { ruta: 'Comayagua', fecha: '2025-06-12', horario: '6:00 AM', duracion: '3h 10m', precio: 210, destino: 'Comayagua', empresa: 'Autobuses Unidos' },
        { ruta: 'Colón', fecha: '2025-06-18', horario: '10:30 AM', duracion: '4h 05m', precio: 290, destino: 'Colón', empresa: 'Transporte Express' },
        { ruta: 'Colón', fecha: '2025-07-14', horario: '1:30 PM', duracion: '4h 05m', precio: 290, destino: 'Colón', empresa: 'Buses del Norte' },
        { ruta: 'Copán', fecha: '2025-07-25', horario: '1:30 PM', duracion: '4h 40m', precio: 340, destino: 'Copán', empresa: 'Viajes Rápidos' },
        { ruta: 'Copán', fecha: '2025-08-19', horario: '4:30 PM', duracion: '4h 40m', precio: 340, destino: 'Copán', empresa: 'Línea Dorada' },
        { ruta: 'El Paraíso', fecha: '2025-08-14', horario: '4:30 PM', duracion: '2h 55m', precio: 165, destino: 'El Paraíso', empresa: 'Autobuses Unidos' },
        { ruta: 'El Paraíso', fecha: '2025-09-10', horario: '6:00 PM', duracion: '2h 55m', precio: 165, destino: 'El Paraíso', empresa: 'Transporte Express' },
        { ruta: 'Yoro', fecha: '2025-09-22', horario: '6:00 PM', duracion: '3h 20m', precio: 220, destino: 'Yoro', empresa: 'Buses del Norte' },
        { ruta: 'Yoro', fecha: '2025-10-16', horario: '9:00 AM', duracion: '3h 20m', precio: 220, destino: 'Yoro', empresa: 'Viajes Rápidos' },
        { ruta: 'Gracias a Dios', fecha: '2025-10-05', horario: '8:00 AM', duracion: '4h 50m', precio: 295, destino: 'Gracias a Dios', empresa: 'Línea Dorada' },
        { ruta: 'Intibucá', fecha: '2025-11-30', horario: '2:00 PM', duracion: '3h 25m', precio: 185, destino: 'Intibucá', empresa: 'Autobuses Unidos' },
        { ruta: 'Islas de la Bahía', fecha: '2025-01-25', horario: '7:30 AM', duracion: '5h 10m', precio: 420, destino: 'Islas de la Bahía', empresa: 'Transporte Express' },
        { ruta: 'Islas de la Bahía', fecha: '2025-03-12', horario: '10:30 AM', duracion: '5h 10m', precio: 420, destino: 'Islas de la Bahía', empresa: 'Buses del Norte' },
        { ruta: 'La Paz', fecha: '2025-02-14', horario: '12:00 PM', duracion: '3h 35m', precio: 195, destino: 'La Paz', empresa: 'Viajes Rápidos' },
        { ruta: 'La Paz', fecha: '2025-04-22', horario: '1:30 PM', duracion: '3h 35m', precio: 195, destino: 'La Paz', empresa: 'Línea Dorada' },
        { ruta: 'Lempira', fecha: '2025-05-18', horario: '6:00 AM', duracion: '4h 25m', precio: 310, destino: 'Lempira', empresa: 'Autobuses Unidos' },
        { ruta: 'Lempira', fecha: '2025-07-08', horario: '9:00 AM', duracion: '4h 25m', precio: 310, destino: 'Lempira', empresa: 'Transporte Express' },
        { ruta: 'Ocotepeque', fecha: '2025-06-05', horario: '3:00 PM', duracion: '4h 55m', precio: 360, destino: 'Ocotepeque', empresa: 'Buses del Norte' },
        { ruta: 'Ocotepeque', fecha: '2025-08-28', horario: '4:30 PM', duracion: '4h 55m', precio: 360, destino: 'Ocotepeque', empresa: 'Viajes Rápidos' },
        { ruta: 'Olancho', fecha: '2025-07-17', horario: '7:30 AM', duracion: '3h 40m', precio: 240, destino: 'Olancho', empresa: 'Línea Dorada' },
        { ruta: 'Olancho', fecha: '2025-09-19', horario: '10:30 AM', duracion: '3h 40m', precio: 240, destino: 'Olancho', empresa: 'Autobuses Unidos' },
        { ruta: 'Santa Bárbara', fecha: '2025-08-06', horario: '12:00 PM', duracion: '4h 15m', precio: 285, destino: 'Santa Bárbara', empresa: 'Transporte Express' },
        { ruta: 'Santa Bárbara', fecha: '2025-10-24', horario: '1:30 PM', duracion: '4h 15m', precio: 285, destino: 'Santa Bárbara', empresa: 'Buses del Norte' },
        { ruta: 'Valle', fecha: '2025-09-03', horario: '6:00 PM', duracion: '5h 30m', precio: 395, destino: 'Valle', empresa: 'Viajes Rápidos' },
        { ruta: 'Valle', fecha: '2025-11-14', horario: '9:00 AM', duracion: '5h 30m', precio: 395, destino: 'Valle', empresa: 'Línea Dorada' }
    ];

    function mostrarViajesDepartamento(departamento) {
        departamentoActual = departamento;
        const origenSeleccionado = document.getElementById('origen').value;

        if (!origenSeleccionado || origenSeleccionado === 'Seleccione un origen') {
            alert('Por favor, seleccione un origen antes de ver los viajes disponibles.');
            return;
        }

        const viajesFiltrados = viajesDatos.filter(v => v.destino === departamento);

        document.getElementById('modalViajesTitle').innerHTML = `<i class="fas fa-bus"></i> Viajes de ${origenSeleccionado} a ${departamento}`;

        const container = document.getElementById('viajesContainer');

        if (viajesFiltrados.length === 0) {
            container.innerHTML = `
                <div class="no-viajes">
                    <i class="fas fa-search"></i>
                    <h3>No hay viajes disponibles</h3>
                    <p>No se encontraron viajes desde ${origenSeleccionado} hacia ${departamento} en este momento.</p>
                </div>
            `;
        } else {
            container.innerHTML = viajesFiltrados.map((viaje, i) => {
                const viajeIndex = viajesDatos.indexOf(viaje);
                const hayAsientos = (asientosVendidos[viajeIndex] || 0) > 0;
                return `
                    <div class="viaje-card">
                        <div class="viaje-content">
                            <div class="viaje-info">
                                <span class="viaje-label"><i class="fas fa-route"></i> Ruta</span>
                                <span class="viaje-value">${origenSeleccionado} → ${viaje.ruta}</span>
                            </div>
                            <div class="viaje-info">
                                <span class="viaje-label"><i class="fas fa-calendar"></i> Fecha</span>
                                <span class="viaje-value">${new Date(viaje.fecha + 'T00:00:00').toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' })}</span>
                            </div>
                            <div class="viaje-info">
                                <span class="viaje-label"><i class="fas fa-clock"></i> Horario</span>
                                <span class="viaje-value">${viaje.horario}</span>
                            </div>
                            <div class="viaje-info">
                                <span class="viaje-label"><i class="fas fa-hourglass"></i> Duración</span>
                                <span class="viaje-value">${viaje.duracion}</span>
                            </div>
                            <div class="viaje-info">
                                <span class="viaje-label"><i class="fas fa-dollar-sign"></i> Precio</span>
                                <span class="viaje-precio">L${viaje.precio.toFixed(2)}</span>
                            </div>
                            <div class="viaje-precio-compra">
                                <button class="btn-comprar" onclick="solicitarViaje(${viajeIndex})"><i class="fas fa-ticket-alt"></i> Comprar</button>
                                <button class="btn-eliminar" onclick="eliminarCompra(${viajeIndex})" ${!hayAsientos ? 'disabled' : ''}><i class="fas fa-trash"></i> Eliminar</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        document.getElementById('modalViajes').classList.add('show');
    }

    function cerrarModalViajes() {
        document.getElementById('modalViajes').classList.remove('show');
    }

    function solicitarViaje(index) {
        viajeSeleccionado = index;
        esEliminacion = false;
        const viaje = viajesDatos[index];
        const fechaFormateada = new Date(viaje.fecha + 'T00:00:00').toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });

        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-ticket-alt"></i> Confirmar Compra';
        document.getElementById('modalMessage').textContent = '¿Deseas confirmar la compra de este boleto?';
        document.getElementById('waitlistDetails').innerHTML = `
            <strong>Ruta:</strong> ${viaje.ruta}<br>
            <strong>Fecha:</strong> ${fechaFormateada}<br>
            <strong>Horario:</strong> ${viaje.horario}<br>
            <strong>Precio:</strong> L${viaje.precio.toFixed(2)}<br>
            <strong>Asientos Vendidos:</strong> ${asientosVendidos[index] || 0}/30
        `;
        document.getElementById('waitlistModal').classList.add('show');
    }

    function eliminarCompra(index) {
        viajeSeleccionado = index;
        esEliminacion = true;
        const viaje = viajesDatos[index];
        const fechaFormateada = new Date(viaje.fecha + 'T00:00:00').toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });

        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-trash"></i> Eliminar Compra';
        document.getElementById('modalMessage').textContent = '¿Deseas eliminar esta compra?';
        document.getElementById('waitlistDetails').innerHTML = `
            <strong>Ruta:</strong> ${viaje.ruta}<br>
            <strong>Fecha:</strong> ${fechaFormateada}<br>
            <strong>Horario:</strong> ${viaje.horario}<br>
            <strong>Precio:</strong> L${viaje.precio.toFixed(2)}<br>
            <strong>Asientos Vendidos:</strong> ${asientosVendidos[index] || 0}/30
        `;
        document.getElementById('waitlistModal').classList.add('show');
    }

    function confirmarAccion() {
        if (esEliminacion) {
            boletosComprados--;
            if (boletosComprados < 0) boletosComprados = 0;
            asientosVendidos[viajeSeleccionado] = (asientosVendidos[viajeSeleccionado] || 0) - 1;
            if (asientosVendidos[viajeSeleccionado] < 0) asientosVendidos[viajeSeleccionado] = 0;
            delete comprasUsuario[viajeSeleccionado];
        } else {
            if (boletosComprados < 30) {
                boletosComprados++;
                asientosVendidos[viajeSeleccionado] = (asientosVendidos[viajeSeleccionado] || 0) + 1;
                comprasUsuario[viajeSeleccionado] = true;
            }
        }

        document.getElementById('waitlistModal').classList.remove('show');
        mostrarViajesDepartamento(departamentoActual);
    }

    function cerrarModalConfirmacion() {
        document.getElementById('waitlistModal').classList.remove('show');
        viajeSeleccionado = null;
        esEliminacion = false;
    }
</script>
</body>
</html>
