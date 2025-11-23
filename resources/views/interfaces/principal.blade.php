<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Asientos de Buses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .btn { display: inline-block; padding: 10px 20px; font-size: 16px; font-weight: bold; text-align: center; text-decoration: none; border-radius: 5px; transition: background-color 0.3s ease; text-transform: uppercase; height: 35px; line-height: 30px; display: inline-flex; align-items: center; justify-content: center; }
        .btn-login { background-color: #007bff; border-color: #007bff; color: white; }
        .btn-login:hover { background-color: #0056b3; }
        .btn-registro { background-color: #28a745; border-color: #28a745; color: white; }
        .btn-registro:hover { background-color: #218838; }
        .hero { height: 60vh; display: flex; align-items: center; justify-content: center; color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.7); background: linear-gradient(to right, rgba(0,0,0,0.5), rgba(0,0,0,0.3)), url('https://www.shutterstock.com/image-illustration/image-bus-on-road-600nw-2578468007.jpg') no-repeat center center; background-size: cover; text-align: center; }
        .hero h1 { font-size: 3rem; font-weight: bold; }
        .hero p { font-size: 1.5rem; margin-bottom: 20px; }
        .search-section { margin-top: -50px; background: white; padding: 20px; border-radius: 15px; box-shadow: 0px 5px 15px rgba(0,0,0,0.2); max-width: 1000px; margin-left: auto; margin-right: auto; position: relative; z-index: 10; }
        .departments .card { cursor: pointer; transition: transform 0.2s; }
        .departments .card:hover { transform: scale(1.05); }
        .departments img { height: 150px; object-fit: cover; border-radius: 10px; }
        .modal-info { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 3000; padding: 20px; align-items: center; justify-content: center; }
        .modal-info.show { display: flex; }
        .modal-info-content { background: white; border-radius: 12px; max-width: 1200px; width: 100%; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); max-height: 85vh; overflow-y: auto; }
        .modal-info-header { background: linear-gradient(180deg, #1e63b8, #1976d2); color: white; padding: 24px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 10; }
        .modal-info-header h2 { margin: 0; font-size: 1.5rem; font-weight: 700; }
        .modal-info-close { background: rgba(255, 255, 255, 0.2); border: none; color: white; width: 40px; height: 40px; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .modal-info-close:hover { background: rgba(255, 255, 255, 0.3); }
        .modal-info-body { padding: 24px; }
        .info-tabs { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; flex-wrap: wrap; }
        .info-tab { padding: 10px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: #6b7280; border-bottom: 3px solid transparent; transition: all 0.3s; font-size: 14px; }
        .info-tab.active { color: #1976d2; border-bottom-color: #1976d2; }
        .info-tab:hover { color: #1976d2; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .info-card { border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; transition: all 0.3s; background: white; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .info-card:hover { transform: translateY(-8px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15); }
        .info-card-img { width: 100%; height: 160px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: white; }
        .info-card-body { padding: 16px; }
        .info-card-title { font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 8px; }
        .info-card-desc { font-size: 13px; color: #6b7280; line-height: 1.6; }
        .modal-viajes { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 3000; padding: 20px; align-items: center; justify-content: center; }
        .modal-viajes.show { display: flex; }
        .modal-viajes-content { background: white; border-radius: 12px; max-width: 1200px; width: 100%; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); max-height: 85vh; overflow-y: auto; }
        .modal-viajes-header { background: linear-gradient(180deg, #1e63b8, #1976d2); color: white; padding: 24px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 10; }
        .modal-viajes-header h2 { margin: 0; font-size: 1.5rem; font-weight: 700; }
        .modal-viajes-close { background: rgba(255, 255, 255, 0.2); border: none; color: white; width: 40px; height: 40px; border-radius: 50%; font-size: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .modal-viajes-close:hover { background: rgba(255, 255, 255, 0.3); }
        .modal-viajes-body { padding: 24px; }
        .viaje-card { border-left: 4px solid #1976d2; padding: 20px; background: #fafbfc; border-radius: 8px; margin-bottom: 12px; transition: all 0.2s; border: 1px solid #e5e7eb; }
        .viaje-card:hover { background: white; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); }
        .viaje-content { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr 1fr auto; gap: 16px; align-items: center; }
        .viaje-info { display: flex; flex-direction: column; justify-content: center; }
        .viaje-label { font-size: 11px; color: #9ca3af; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
        .viaje-value { font-size: 14px; font-weight: 600; color: #1f2937; }
        .viaje-precio { font-size: 20px; color: #1976d2; font-weight: 700; }
        .viaje-precio-compra { display: flex; align-items: center; gap: 10px; justify-content: flex-end; }
        .btn-comprar { background-color: #1976d2; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer; white-space: nowrap; }
        .btn-comprar:hover { background-color: #1565c0; }
        .btn-eliminar { background-color: #ef4444; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer; white-space: nowrap; }
        .btn-eliminar:hover { background-color: #dc2626; }
        .btn-eliminar:disabled { opacity: 0.5; cursor: not-allowed; }
        .modal-waitlist { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 4000; align-items: center; justify-content: center; }
        .modal-waitlist.show { display: flex; }
        .modal-content-waitlist { background: white; border-radius: 12px; padding: 30px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); }
        .modal-waitlist h2 { color: #1976d2; margin-bottom: 15px; font-size: 24px; display: flex; align-items: center; gap: 10px; }
        .modal-waitlist p { color: #6b7280; margin-bottom: 20px; }
        .waitlist-info { background: #e0f2fe; border-left: 4px solid #1976d2; padding: 15px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; color: #1f2937; line-height: 1.8; }
        .waitlist-info strong { color: #1976d2; display: block; margin-bottom: 5px; }
        .modal-buttons { display: flex; gap: 10px; }
        .modal-buttons button { flex: 1; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer; }
        .btn-confirmar { background-color: #1976d2; color: white; }
        .btn-confirmar:hover { background-color: #1565c0; }
        .btn-cancelar { background-color: #ef4444; color: white; }
        .btn-cancelar:hover { background-color: #dc2626; }
        .no-viajes { background: #dbeafe; padding: 40px; border-radius: 12px; border: 2px dashed #3b82f6; text-align: center; }
        .no-viajes i { font-size: 4rem; margin-bottom: 20px; color: #3b82f6; }
        .no-viajes h3 { color: #1e40af; margin-bottom: 15px; font-size: 1.3rem; }
        .no-viajes p { color: #1e40af; }
        @media (max-width: 768px) {
            .viaje-content { grid-template-columns: 1fr; }
            .cards-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

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

<div class="modal-viajes" id="modalViajes">
    <div class="modal-viajes-content">
        <div class="modal-viajes-header">
            <h2 id="modalViajesTitle"><i class="fas fa-bus"></i> Viajes Disponibles</h2>
            <button class="modal-viajes-close" onclick="cerrarModalViajes()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-viajes-body">
            <div id="viajesContainer"></div>
        </div>
    </div>
</div>

<section class="hero position-relative">
    <div class="container">
        <h1>Reserva tu Asiento de Bus Fácil y Rápido</h1>
        <p>Explora rutas, horarios y promociones exclusivas para ti.</p>
        <h2>Bustrak, tu mejor opción</h2>
    </div>
    <div class="hero-buttons position-absolute top-0 end-0 m-3">
        <a href="/login" class="btn btn-primary btn-login me-2">Iniciar Sesión</a>
        <a href="/registro" class="btn btn-success btn-registro">Registrate</a>
    </div>
</section>

<section class="search-section">
    <form class="row g-3">
        <div class="col-md-6">
            <label for="origen" class="form-label">Origen</label>
            <select class="form-select" id="origen">
                <option selected>Seleccione un origen</option>
                <option>Cortés</option>
                <option>Atlántida</option>
                <option>Francisco Morazán</option>
                <option>Choluteca</option>
                <option>Comayagua</option>
                <option>Colón</option>
                <option>Copán</option>
                <option>El Paraíso</option>
                <option>Yoro</option>
                <option>Intibucá</option>
                <option>La Paz</option>
                <option>Lempira</option>
                <option>Ocotepeque</option>
                <option>Olancho</option>
                <option>Santa Bárbara</option>
                <option>Valle</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="destino" class="form-label">Destino</label>
            <select class="form-select" id="destino">
                <option selected>Seleccione un destino</option>
                <option>Cortés</option>
                <option>Atlántida</option>
                <option>Francisco Morazán</option>
                <option>Choluteca</option>
                <option>Comayagua</option>
                <option>Colón</option>
                <option>Copán</option>
                <option>El Paraíso</option>
                <option>Yoro</option>
                <option>Intibucá</option>
                <option>La Paz</option>
                <option>Lempira</option>
                <option>Ocotepeque</option>
                <option>Olancho</option>
                <option>Santa Bárbara</option>
                <option>Valle</option>
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
            <button type="button" class="btn btn-primary w-100" onclick="buscarViajes()">Buscar Viaje</button>
        </div>
    </form>
</section>

<section class="py-5 position-relative">
    <div class="container">
        <h2 class="mb-4 text-center">Destinos en Honduras</h2>
        <div class="row row-cols-1 row-cols-md-3 g-3 departments">
            <div class="col"><div class="card" onclick="abrirModalInfo('Cortés')"><img src="/catalago/img/cortes.jpg" class="card-img-top"><div class="card-body text-center"><h6>Cortés</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Atlántida')"><img src="/catalago/img/atlantida.jpg" class="card-img-top"><div class="card-body text-center"><h6>Atlántida</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Francisco Morazán')"><img src="/catalago/img/francisco.jpg" class="card-img-top"><div class="card-body text-center"><h6>Francisco Morazán</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Choluteca')"><img src="/catalago/img/choluteca.jpg" class="card-img-top"><div class="card-body text-center"><h6>Choluteca</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Comayagua')"><img src="/catalago/img/comayagua.jpg" class="card-img-top"><div class="card-body text-center"><h6>Comayagua</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Colón')"><img src="/catalago/img/colon.jpg" class="card-img-top"><div class="card-body text-center"><h6>Colón</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Copán')"><img src="/catalago/img/copan.jpg" class="card-img-top"><div class="card-body text-center"><h6>Copán</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('El Paraíso')"><img src="/catalago/img/elparaiso.jpg" class="card-img-top"><div class="card-body text-center"><h6>El Paraíso</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Yoro')"><img src="/catalago/img/yoro.jpg" class="card-img-top"><div class="card-body text-center"><h6>Yoro</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Intibucá')"><img src="/catalago/img/intibuca.jpg" class="card-img-top"><div class="card-body text-center"><h6>Intibucá</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('La Paz')"><img src="/catalago/img/lapaz.jpg" class="card-img-top"><div class="card-body text-center"><h6>La Paz</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Lempira')"><img src="/catalago/img/lempira.jpg" class="card-img-top"><div class="card-body text-center"><h6>Lempira</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Ocotepeque')"><img src="/catalago/img/ocotepeque.jpg" class="card-img-top"><div class="card-body text-center"><h6>Ocotepeque</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Olancho')"><img src="/catalago/img/olancho.jpg" class="card-img-top"><div class="card-body text-center"><h6>Olancho</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Santa Bárbara')"><img src="/catalago/img/santabarbara.jpg" class="card-img-top"><div class="card-body text-center"><h6>Santa Bárbara</h6></div></div></div>
            <div class="col"><div class="card" onclick="abrirModalInfo('Valle')"><img src="/catalago/img/valle.jpg" class="card-img-top"><div class="card-body text-center"><h6>Valle</h6></div></div></div>
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

    const infoDestinos = {
        'Cortés': {lugares:[{nombre:'La Ceiba',desc:'Puerta de entrada a las Islas de la Bahía, ciudad portuaria con playas paradisíacas.'},{nombre:'Tela',desc:'Hermoso puerto caribeño con playas de arena blanca y gastronomía marina.'},{nombre:'Jardín Botánico Lancetilla',desc:'Reserva natural con flora tropical única en América Central.'},{nombre:'Isla de Roatán',desc:'Archipiélago con playas vírgenes, arrecifes de coral y buceo de clase mundial.'},{nombre:'Parque Nacional Punta Sal',desc:'Reserva marina con biodiversidad excepcional y sitio arqueológico.'}],comidas:[{nombre:'Hudut',desc:'Pescado marinado en salsa de coco con plátanos fritos, plato típico caribeño.'},{nombre:'Sopa de Camarones',desc:'Caldo cremoso con camarones frescos y especias locales.'},{nombre:'Ceviche Costeño',desc:'Camarón fresco marinado en limón con cítricos y vegetales.'},{nombre:'Rondón',desc:'Estofado tradicional con pescado, coco y plátano.'},{nombre:'Cambulón',desc:'Torrejas de plátano con salsa de carne molida y tomate.'}]},
        'Atlántida': {lugares:[{nombre:'Parque Nacional Punta Sal',desc:'Reserva marina protegida con arrecifes de coral y biodiversidad marina.'},{nombre:'Playa Tela',desc:'Extensas playas con palmeras, aguas cristalinas y acceso a cayos.'},{nombre:'Laguna Están',desc:'Laguna costera ideal para observar aves migratorias y fauna marina.'},{nombre:'Cayos de Utila',desc:'Pequeño archipiélago para buceo, snorkel y actividades acuáticas.'},{nombre:'Refugio Cuero y Salado',desc:'Santuario de manatíes, tortugas y aves acuáticas de importancia internacional.'}],comidas:[{nombre:'Sopa de Caracol',desc:'Caldo cremoso con caracol marino, coco y especias auténticas atlánticas.'},{nombre:'Pescado Frito con Tostones',desc:'Pescado crujiente acompañado de plátanos maduros fritos.'},{nombre:'Ensalada de Mariscos',desc:'Mezcla de camarones, caracol y pulpo en vinagreta con cítricos.'},{nombre:'Arroz de Coco',desc:'Arroz blanco cocinado con leche de coco fresco.'},{nombre:'Conch Fritters',desc:'Buñuelos de caracol marino fritos, entrada tradicional caribeña.'}]},
        'Francisco Morazán': {lugares:[{nombre:'Tegucigalpa',desc:'Capital cultural y comercial de Honduras, centro político administrativo.'},{nombre:'Basílica de Suyapa',desc:'Templo católico más importante del país, patrona de Honduras.'},{nombre:'Cerro Picacho',desc:'Mirador con vistas panorámicas de Tegucigalpa, símbolo de la ciudad.'},{nombre:'Catedral Metropolitana',desc:'Iglesia histórica en el centro, templo colonial de gran importancia.'},{nombre:'Parque La Leona',desc:'Parque central histórico con monumentos y sitios de interés cultural.'}],comidas:[{nombre:'Baleadas',desc:'Tortillas rellenas de frijoles, queso y huevo, desayuno tradicional.'},{nombre:'Sopa de Res',desc:'Caldo con carne de res, papa y verduras frescas.'},{nombre:'Enchiladas Rojas',desc:'Tortillas bañadas en salsa roja picante con queso.'},{nombre:'Pupusas',desc:'Masa gruesa rellena de queso, chicharrón o frijoles.'},{nombre:'Tamales',desc:'Masa de maíz rellena y envuelta en hoja de plátano.'}]},
        'Choluteca': {lugares:[{nombre:'Isla de Tigre',desc:'Isla con vistas panorámicas del Golfo de Fonseca y atractivos turísticos.'},{nombre:'Playas Negras',desc:'Playas con arena volcánica única en el Golfo de Fonseca.'},{nombre:'Mercado Central',desc:'Centro comercial con productos locales y gastronomía regional.'},{nombre:'Punta Ratón',desc:'Punto ideal para avistamiento marino y actividades acuáticas.'},{nombre:'Playas del Pacífico',desc:'Costas con vista al Océano Pacífico y ambiente turístico.'}],comidas:[{nombre:'Mariscada',desc:'Mezcla de mariscos frescos en caldo sabroso y especiado.'},{nombre:'Caldo de Gallina',desc:'Sopa tradicional con pollo tierno y verduras frescas.'},{nombre:'Tostadas con Carne',desc:'Tortillas crujientes con carne desmenuzada sazonada.'},{nombre:'Ceviche',desc:'Pescado marinado en limón con cítricos y vegetales frescos.'},{nombre:'Quesadillas',desc:'Tortillas rellenas de queso fresco de la región.'}]},
        'Comayagua': {lugares:[{nombre:'Catedral de Comayagua',desc:'Iglesia histórica con arquitectura colonial de importancia nacional.'},{nombre:'Museo Regional',desc:'Museo con arte y objetos históricos de la región.'},{nombre:'Plaza Central',desc:'Centro histórico de la ciudad con arquitectura colonial.'},{nombre:'Iglesia de San Juan',desc:'Templo colonial antiguo, patrimonio histórico local.'},{nombre:'Parque Arqueológico',desc:'Sitio con ruinas prehispánicas de importancia científica.'}],comidas:[{nombre:'Gallo en Chicha',desc:'Pollo fermentado en bebida tradicional, plato muy típico.'},{nombre:'Sopa de Mondongo',desc:'Caldo con vísceras y verduras, receta ancestral.'},{nombre:'Frijoles Volteados',desc:'Frijoles refritos con especias locales y queso.'},{nombre:'Queso de Hoja',desc:'Queso envuelto en hoja de plátano, especialidad local.'},{nombre:'Elotes con Queso',desc:'Maíz tierno con queso fresco rallado.'}]},
        'Colón': {lugares:[{nombre:'Puerto Cortés',desc:'Puerto caribeño importante de Honduras, ciudad comercial.'},{nombre:'Playas del Caribe',desc:'Extensas playas con arena blanca y aguas cristalinas.'},{nombre:'Mercado Puerto',desc:'Centro comercial con productos del mar y locales.'},{nombre:'Cayo Cochino',desc:'Archipiélago con biodiversidad marina excepcional.'},{nombre:'Reserva Natural Laguna',desc:'Área protegidaLaguna',desc:'Área protegida con flora tropical y fauna marina.'}],comidas:[{nombre:'Sopa de Camarones',desc:'Caldo cremoso con camarones frescos del puerto.'},{nombre:'Filete de Pescado',desc:'Pescado fresco a la parrilla con especias caribeñas.'},{nombre:'Plátanos Maduros',desc:'Plátanos dulces fritos, acompañamiento tradicional.'},{nombre:'Coconut Rice',desc:'Arroz cocinado con leche de coco fresco.'},{nombre:'Conch Soup',desc:'Sopa tradicional de caracol marino con especias.'}]},
        'Copán': {lugares:[{nombre:'Ruinas de Copán',desc:'Sitio arqueológico maya de importancia mundial, patrimonio UNESCO.'},{nombre:'Pueblo Viejo',desc:'Centro colonial con arquitectura histórica bien preservada.'},{nombre:'Escultura Maya',desc:'Monumentos y estelas mayas esculpidas de gran valor.'},{nombre:'Parque Arqueológico',desc:'Zona protegida con ruinas mayas y senderos naturales.'},{nombre:'Mercado Artesanal',desc:'Tiendas de artesanías y recuerdos mayas tradicionales.'}],comidas:[{nombre:'Sopa Chapín',desc:'Sopa tradicional con ingredientes locales muy sabrosa.'},{nombre:'Tacos de Pollo',desc:'Tortillas con pollo desmenuzado y salsas caseras.'},{nombre:'Chiles Rellenos',desc:'Chile poblano relleno de queso y cubierto de salsa.'},{nombre:'Elote Loco',desc:'Maíz con mayonesa, queso y chili picante.'},{nombre:'Rellenitos',desc:'Plátano relleno de frijoles fritos dulces.'}]},
        'El Paraíso': {lugares:[{nombre:'Juticalpa',desc:'Cabecera comercial de la región, centro de servicios.'},{nombre:'Parque Patuca',desc:'Reserva natural con biodiversidad tropical excepcional.'},{nombre:'Río Patuca',desc:'Río importante para navegación y turismo ecológico.'},{nombre:'Bosques Tropicales',desc:'Selva con flora y fauna excepcional de Honduras.'},{nombre:'Cataratas del Río',desc:'Cascadas hermosas en la región de selva tropical.'}],comidas:[{nombre:'Guiso de Carne',desc:'Carne guisada en salsa con verduras frescas.'},{nombre:'Arroz Blanco',desc:'Arroz cocido al vapor con sabor tradicional.'},{nombre:'Frijoles Negros',desc:'Frijoles cocidos lentamente hasta cremosos.'},{nombre:'Ensalada Fresca',desc:'Verduras frescas de la región con vinagreta.'},{nombre:'Tortillas a Mano',desc:'Tortillas recién hechas de maíz en comal tradicional.'}]},
        'Yoro': {lugares:[{nombre:'Lluvia de Peces',desc:'Evento natural único en mayo, lluvia de peces legendaria.'},{nombre:'Pueblo de Yoro',desc:'Centro con tradiciones locales y cultura milenaria.'},{nombre:'Iglesia San Isidro',desc:'Templo principal de la región de gran belleza.'},{nombre:'Mercado Local',desc:'Mercado con productos agrícolas y artesanías.'},{nombre:'Parque Municipal',desc:'Área de recreación y esparcimiento para turistas.'}],comidas:[{nombre:'Caldo de Res',desc:'Carne con verduras en caldo caliente y nutritivo.'},{nombre:'Enchiladas Verdes',desc:'Tortillas con salsa verde y queso fresco.'},{nombre:'Quesillas',desc:'Tortilla gruesa rellena de queso derretido.'},{nombre:'Frijoles Refritos',desc:'Frijoles aplastados y fritos con mantequilla.'},{nombre:'Chismol',desc:'Salsa fresca de tomate, cebolla y cilantro.'}]},
        'Intibucá': {lugares:[{nombre:'La Esperanza',desc:'Cabecera montañosa con clima fresco y paisajes andinos.'},{nombre:'Artesanías Locales',desc:'Textiles y cerámica tradicionales de gran calidad.'},{nombre:'Mercado de Flores',desc:'Plantas ornamentales y flores de la región montañosa.'},{nombre:'Parque Paz',desc:'Parque con vistas panorámicas de las montañas.'},{nombre:'Iglesia Colonial',desc:'Templo histórico de arquitectura colonial bien preservada.'}],comidas:[{nombre:'Pinol',desc:'Bebida de maíz y cacao tostado, muy tradicional.'},{nombre:'Riguas',desc:'Tamal de maíz tierno envuelto en hoja de elote.'},{nombre:'Dulce de Ayote',desc:'Postre de calabaza caramelizada con canela.'},{nombre:'Pan Casero',desc:'Pan recién horneado en horno de leña.'},{nombre:'Quesillo',desc:'Queso fresco tipo oaxaca de producción local.'}]},
        'La Paz': {lugares:[{nombre:'Cabecera La Paz',desc:'Cabecera departamental con historia colonial importante.'},{nombre:'Iglesia Católica',desc:'Templo principal de La Paz de gran belleza arquitectónica.'},{nombre:'Mercado Central',desc:'Centro comercial con productos locales y artesanías.'},{nombre:'Playas del Golfo',desc:'Acceso al Golfo de Fonseca para actividades acuáticas.'},{nombre:'Artesanía Local',desc:'Productos hechos a mano con técnicas tradicionales.'}],comidas:[{nombre:'Asado de Cerdo',desc:'Cerdo marinado y asado a la parrilla con especias.'},{nombre:'Tamales de Rajas',desc:'Tamales con chile y queso de producción local.'},{nombre:'Mole Rojo',desc:'Salsa tradicional con especias y chiles asados.'},{nombre:'Frijoles Volteados',desc:'Frijoles refritos cremosos con queso fresco.'},{nombre:'Pan Francés',desc:'Pan tipo baguette de producción local tradicional.'}]},
        'Lempira': {lugares:[{nombre:'Gracias',desc:'Pueblo colonial con arquitectura histórica de gran belleza.'},{nombre:'Fortaleza Santa Bárbara',desc:'Fuerte histórico de defensa colonial bien preservado.'},{nombre:'Parque Central',desc:'Plaza principal de Gracias con ambiente colonial.'},{nombre:'Iglesia Santiago',desc:'Templo colonial antiguo construido en piedra.'},{nombre:'Mercado Artesanal',desc:'Tiendas de artesanías, textiles y recuerdos locales.'}],comidas:[{nombre:'Tajadas',desc:'Plátano verde frito en rodajas crujientes.'},{nombre:'Chilaquiles',desc:'Tortillas fritas con queso y salsa picante.'},{nombre:'Sopa de Mondongo',desc:'Caldo tradicional con vísceras y vegetales.'},{nombre:'Horchata',desc:'Bebida de arroz, canela y leche muy refrescante.'},{nombre:'Frijoles con Chorizo',desc:'Frijoles cocidos con chorizo casero picante.'}]},
        'Ocotepeque': {lugares:[{nombre:'Nueva Ocotepeque',desc:'Cabecera en la frontera con Guatemala, centro comercial.'},{nombre:'Frontera Guatemala',desc:'Paso internacional importante, puerta a Centroamérica.'},{nombre:'Mercado Fronterizo',desc:'Comercio binacional activo con productos variados.'},{nombre:'Iglesia Principal',desc:'Templo central de la ciudad con arquitectura colonial.'},{nombre:'Parque Municipal',desc:'Área de recreación pública para visitantes.'}],comidas:[{nombre:'Chiles Rellenos',desc:'Poblano relleno de queso asado y bañado en salsa.'},{nombre:'Carne Asada',desc:'Carne a la parrilla tradicional con especies locales.'},{nombre:'Arroz con Pollo',desc:'Arroz cocinado con pollo jugoso y verduras.'},{nombre:'Rellenitos',desc:'Plátano con frijoles refritos y salsa picante.'},{nombre:'Sopa de Maíz',desc:'Sopa con elote y verduras frescas de la región.'}]},
        'Olancho': {lugares:[{nombre:'Catacamas',desc:'Cabecera comercial de Olancho, centro regional importante.'},{nombre:'Río Patuca',desc:'Río principal para navegación y turismo ecológico.'},{nombre:'Reserva de Biosfera',desc:'Área protegida con biodiversidad única de Honduras.'},{nombre:'Playas Río',desc:'Playas fluviales para recreación y ecoturismo.'},{nombre:'Selva Tropical',desc:'Bosque con fauna y flora excepcional de Centroamérica.'}],comidas:[{nombre:'Sopa de Reses',desc:'Caldo de carne roja con verduras y especias.'},{nombre:'Guiso de Gallina',desc:'Pollo guisado en salsa picante tradicional.'},{nombre:'Pescado Olanchano',desc:'Pescado de río preparado localmente con hierbas.'},{nombre:'Yuca Frita',desc:'Raíz frita crujiente, acompañamiento tradicional.'},{nombre:'Frijoles con Olote',desc:'Frijoles con maíz tierno y especias locales.'}]},
        'Santa Bárbara': {lugares:[{nombre:'Región Cafetalera',desc:'Zona productora de café de Honduras de gran calidad.'},{nombre:'Plantaciones de Café',desc:'Tours por cafetales tradicionales y beneficios.'},{nombre:'Iglesia Católica',desc:'Templo central de Santa Bárbara de bella arquitectura.'},{nombre:'Mercado Central',desc:'Mercado con café local y productos artesanales.'},{nombre:'Parque Recreativo',desc:'Área verde para descanso y recreación de visitantes.'}],comidas:[{nombre:'Café de Olla',desc:'Café tradicional preparado en olla de barro.'},{nombre:'Pan de Yema',desc:'Pan dulce local tradicional muy sabroso.'},{nombre:'Queso Fresco',desc:'Queso de vaca recién hecho de la región.'},{nombre:'Tamales Verdes',desc:'Tamales con rajas de chile verde fresco.'},{nombre:'Miel Pura',desc:'Miel de abejas de la región muy pura.'}]},
        'Valle': {lugares:[{nombre:'Nacaome',desc:'Cabecera sur de Honduras, puerta al Pacífico.'},{nombre:'Playas del Golfo',desc:'Costas del Pacífico hondureño con belleza natural.'},{nombre:'Laguna Nacaome',desc:'Laguna costera importante para aves y fauna marina.'},{nombre:'Mercado de Pescado',desc:'Mercado con productos marinos frescos diarios.'},{nombre:'Iglesia Nacaome',desc:'Templo principal de la región de construcción histórica.'}],comidas:[{nombre:'Camarones Frescos',desc:'Camarones preparados de varias formas tradicionales.'},{nombre:'Ceviche Valluno',desc:'Ceviche con estilo local y toque regional.'},{nombre:'Arroz con Mariscos',desc:'Variedad de mariscos frescos con arroz blanco.'},{nombre:'Pupusas de Camarón',desc:'Pupusas rellenas de camarón fresco y queso.'},{nombre:'Sopa de Pescado',desc:'Sopa cremosa de pescado fresco del Pacífico.'}]}
    };

    const viajesBase = [
        {ruta:'Cortés',horario:'6:00 AM',duracion:'3h 30m',precio:250,empresa:'Transporte Express'},
        {ruta:'Cortés',horario:'9:00 AM',duracion:'3h 30m',precio:250,empresa:'Buses del Norte'},
        {ruta:'Atlántida',horario:'9:00 AM',duracion:'4h 15m',precio:320,empresa:'Viajes Rápidos'},
        {ruta:'Atlántida',horario:'7:30 AM',duracion:'4h 15m',precio:320,empresa:'Línea Dorada'},
        {ruta:'Francisco Morazán',horario:'12:00 PM',duracion:'2h 45m',precio:180,empresa:'Autobuses Unidos'},
        {ruta:'Francisco Morazán',horario:'3:00 PM',duracion:'2h 45m',precio:180,empresa:'Transporte Express'},
        {ruta:'Choluteca',horario:'7:30 AM',duracion:'5h 20m',precio:380,empresa:'Buses del Norte'},
        {ruta:'Choluteca',horario:'10:30 AM',duracion:'5h 20m',precio:380,empresa:'Viajes Rápidos'},
        {ruta:'Comayagua',horario:'3:00 PM',duracion:'3h 10m',precio:210,empresa:'Línea Dorada'},
        {ruta:'Comayagua',horario:'6:00 AM',duracion:'3h 10m',precio:210,empresa:'Autobuses Unidos'},
        {ruta:'Colón',horario:'10:30 AM',duracion:'4h 05m',precio:290,empresa:'Transporte Express'},
        {ruta:'Colón',horario:'1:30 PM',duracion:'4h 05m',precio:290,empresa:'Buses del Norte'},
        {ruta:'Copán',horario:'1:30 PM',duracion:'4h 40m',precio:340,empresa:'Viajes Rápidos'},
        {ruta:'Copán',horario:'4:30 PM',duracion:'4h 40m',precio:340,empresa:'Línea Dorada'},
        {ruta:'El Paraíso',horario:'4:30 PM',duracion:'2h 55m',precio:165,empresa:'Autobuses Unidos'},
        {ruta:'El Paraíso',horario:'6:00 PM',duracion:'2h 55m',precio:165,empresa:'Transporte Express'},
        {ruta:'Yoro',horario:'6:00 PM',duracion:'3h 20m',precio:220,empresa:'Buses del Norte'},
        {ruta:'Yoro',horario:'9:00 AM',duracion:'3h 20m',precio:220,empresa:'Viajes Rápidos'},
        {ruta:'Intibucá',horario:'2:00 PM',duracion:'3h 25m',precio:185,empresa:'Autobuses Unidos'},
        {ruta:'Intibucá',horario:'8:00 AM',duracion:'3h 25m',precio:185,empresa:'Línea Dorada'},
        {ruta:'La Paz',horario:'12:00 PM',duracion:'3h 35m',precio:195,empresa:'Viajes Rápidos'},
        {ruta:'La Paz',horario:'1:30 PM',duracion:'3h 35m',precio:195,empresa:'Línea Dorada'},
        {ruta:'Lempira',horario:'6:00 AM',duracion:'4h 25m',precio:310,empresa:'Autobuses Unidos'},
        {ruta:'Lempira',horario:'9:00 AM',duracion:'4h 25m',precio:310,empresa:'Transporte Express'},
        {ruta:'Ocotepeque',horario:'3:00 PM',duracion:'4h 55m',precio:360,empresa:'Buses del Norte'},
        {ruta:'Ocotepeque',horario:'4:30 PM',duracion:'4h 55m',precio:360,empresa:'Viajes Rápidos'},
        {ruta:'Olancho',horario:'7:30 AM',duracion:'3h 40m',precio:240,empresa:'Línea Dorada'},
        {ruta:'Olancho',horario:'10:30 AM',duracion:'3h 40m',precio:240,empresa:'Autobuses Unidos'},
        {ruta:'Santa Bárbara',horario:'12:00 PM',duracion:'4h 15m',precio:285,empresa:'Transporte Express'},
        {ruta:'Santa Bárbara',horario:'1:30 PM',duracion:'4h 15m',precio:285,empresa:'Buses del Norte'},
        {ruta:'Valle',horario:'6:00 PM',duracion:'5h 30m',precio:395,empresa:'Viajes Rápidos'},
        {ruta:'Valle',horario:'9:00 AM',duracion:'5h 30m',precio:395,empresa:'Línea Dorada'}
    ];

    function abrirModalInfo(departamento) {
        const info = infoDestinos[departamento];
        if (!info) return;
        document.getElementById('modalInfoTitle').innerHTML = `<i class="fas fa-map-marker-alt"></i> ${departamento}`;
        const lugaresHtml = info.lugares.map(lugar => `<div class="info-card"><div class="info-card-img"><i class="fas fa-landmark"></i></div><div class="info-card-body"><div class="info-card-title">${lugar.nombre}</div><div class="info-card-desc">${lugar.desc}</div></div></div>`).join('');
        const comidasHtml = info.comidas.map(comida => `<div class="info-card"><div class="info-card-img"><i class="fas fa-utensils"></i></div><div class="info-card-body"><div class="info-card-title">${comida.nombre}</div><div class="info-card-desc">${comida.desc}</div></div></div>`).join('');
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
        event.target.closest('.info-tab').classList.add('active');
        document.getElementById(tab + 'Tab').classList.add('active');
    }

    function buscarViajes() {
        const origen = document.getElementById('origen').value;
        const destino = document.getElementById('destino').value;
        const fechaIda = document.getElementById('fecha_ida').value;
        if (!origen || origen === 'Seleccione un origen') {
            alert('Por favor, seleccione un origen.');
            return;
        }
        if (!destino || destino === 'Seleccione un destino') {
            alert('Por favor, seleccione un destino.');
            return;
        }
        if (!fechaIda) {
            alert('Por favor, seleccione una fecha de ida.');
            return;
        }
        if (origen === destino) {
            alert('El origen y el destino no pueden ser iguales.');
            return;
        }
        mostrarViajesDepartamento(destino);
    }

    function mostrarViajesDepartamento(departamento) {
        departamentoActual = departamento;
        const origenSeleccionado = document.getElementById('origen').value;
        const fechaIda = document.getElementById('fecha_ida').value;
        if (!origenSeleccionado || origenSeleccionado === 'Seleccione un origen') {
            alert('Por favor, seleccione un origen.');
            return;
        }
        if (!fechaIda) {
            alert('Por favor, seleccione una fecha.');
            return;
        }
        const viajesFiltrados = viajesBase.filter(v => v.ruta === departamento).map(v => ({ ...v, fecha: fechaIda, destino: departamento }));
        document.getElementById('modalViajesTitle').innerHTML = `<i class="fas fa-bus"></i> Viajes de ${origenSeleccionado} a ${departamento}`;
        const container = document.getElementById('viajesContainer');
        if (viajesFiltrados.length === 0) {
            container.innerHTML = `<div class="no-viajes"><i class="fas fa-search"></i><h3>No hay viajes</h3><p>No hay viajes disponibles.</p></div>`;
        } else {
            container.innerHTML = viajesFiltrados.map((viaje, i) => {
                const viajeIndex = viajesBase.findIndex(v => v.horario === viaje.horario && v.ruta === viaje.ruta && v.empresa === viaje.empresa);
                const hayAsientos = (asientosVendidos[viajeIndex] || 0) > 0;
                return `<div class="viaje-card"><div class="viaje-content"><div class="viaje-info"><span class="viaje-label">Ruta</span><span class="viaje-value">${origenSeleccionado} → ${viaje.ruta}</span></div><div class="viaje-info"><span class="viaje-label">Fecha</span><span class="viaje-value">${new Date(viaje.fecha + 'T00:00:00').toLocaleDateString('es-ES')}</span></div><div class="viaje-info"><span class="viaje-label">Horario</span><span class="viaje-value">${viaje.horario}</span></div><div class="viaje-info"><span class="viaje-label">Duración</span><span class="viaje-value">${viaje.duracion}</span></div><div class="viaje-info"><span class="viaje-label">Precio</span><span class="viaje-precio">L${viaje.precio.toFixed(2)}</span></div><div class="viaje-precio-compra"><button class="btn-comprar" onclick="solicitarViaje(${viajeIndex}, '${viaje.fecha}')"><i class="fas fa-ticket-alt"></i> Comprar</button><button class="btn-eliminar" onclick="eliminarCompra(${viajeIndex}, '${viaje.fecha}')" ${!hayAsientos ? 'disabled' : ''}><i class="fas fa-trash"></i></button></div></div></div>`;
            }).join('');
        }
        document.getElementById('modalViajes').classList.add('show');
    }

    function cerrarModalViajes() {
        document.getElementById('modalViajes').classList.remove('show');
    }

    function solicitarViaje(index, fecha) {
        viajeSeleccionado = index;
        esEliminacion = false;
        const viaje = viajesBase[index];
        const fechaFormateada = new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES');
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-ticket-alt"></i> Confirmar Compra';
        document.getElementById('modalMessage').textContent = '¿Deseas confirmar la compra?';
        document.getElementById('waitlistDetails').innerHTML = `<strong>Ruta:</strong> ${viaje.ruta}<br><strong>Fecha:</strong> ${fechaFormateada}<br><strong>Horario:</strong> ${viaje.horario}<br><strong>Precio:</strong> L${viaje.precio.toFixed(2)}<br><strong>Asientos:</strong> ${asientosVendidos[index] || 0}/30`;
        document.getElementById('waitlistModal').classList.add('show');
    }

    function eliminarCompra(index, fecha) {
        viajeSeleccionado = index;
        esEliminacion = true;
        const viaje = viajesBase[index];
        const fechaFormateada = new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES');
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-trash"></i> Eliminar Compra';
        document.getElementById('modalMessage').textContent = '¿Deseas eliminar esta compra?';
        document.getElementById('waitlistDetails').innerHTML = `<strong>Ruta:</strong> ${viaje.ruta}<br><strong>Fecha:</strong> ${fechaFormateada}<br><strong>Horario:</strong> ${viaje.horario}<br><strong>Precio:</strong> L${viaje.precio.toFixed(2)}<br><strong>Asientos:</strong> ${asientosVendidos[index] || 0}/30`;
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
