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
    @guest

    <div class="hero-buttons position-absolute top-0 end-0 m-3">
        <a href="/login" class="btn btn-primary btn-login me-2">Iniciar Sesión</a>
        <a href="/registro" class="btn btn-success btn-registro">Registrate</a>
    </div>
   @endguest
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

    const destinosData = {
        'Atlántida': { lugares: ['Parque Nacional Pico Bonito', 'Las Cascadas de Zacate', 'Jardín Botánico Lancetilla', 'Playas del Municipio de Tela', 'Muelle de La Ceiba', 'Río Cangrejal (Área de Rafting)', 'Reserva Marina Cuero y Salado', 'Laguna de Los Micos'], comidas: ['Pan de Coco', 'Tapado Costeño', 'Pescado Frito con Tajadas', 'Casabe (Yuca)', 'Tostones', 'Sopa de Mariscos (Estilo Ceibeño)', 'Rice and Beans', 'Dulce de Banano'] },
        'Colón': { lugares: ['Playas de Trujillo', 'Fortaleza de Santa Bárbara', 'Parque Nacional Capiro y Calentura', 'Laguna de Guaymoreto', 'Puerto de Castilla', 'Comunidades Garífunas (Santa Fe)', 'Montaña de Calentura', 'Balnearios en Río Aguán'], comidas: ['Machuca (Puré de Plátano)', 'Sopa de Caracol', 'Pescado al Vapor', 'Casabe', 'Pan de Coco', 'Carne de Cerdo Ahumada', 'Atol de Coco', 'Tostones con frijoles'] },
        'Comayagua': { lugares: ['Catedral de Comayagua (Reloj Antiguo)', 'Cuevas de Taulabé', 'Museo Colonial de Comayagua', 'Plaza Central León Alvarado', 'Iglesia La Merced', 'Cueva Hedionda', 'La Villa de San Antonio', 'Casa de la Cultura de Comayagua'], comidas: ['Sopa de Capirotadas', 'Pinol (Carne molida)', 'Nacatamales de cerdo', 'Tustacas', 'Alcitrones', 'Tamalitos de Chipilín', 'Montucas de maíz', 'Dulce de Leche (Cajetas)'] },
        'Copán': { lugares: ['Sitio Arqueológico Ruinas de Copán', 'El Bosque de Guacamayas', 'Aguas Termales de Luna Jaguar', 'Hacienda San Lucas', 'Fincas de café (Ruta)', 'Las Sepulturas (Sitio Arqueológico)', 'Museos Arqueológicos de Copán', 'Túneles Mayas (Bajo las Ruinas)'], comidas: ['Tamalitos de Frijoles (o ticucos)', 'Montucas', 'Sopa de Gallina de Patio', 'Picucos (dulce)', 'Totopostes', 'Chilate (bebida)', 'Atol de Maíz', 'Carne Asada al Carbón'] },
        'Cortés': { lugares: ['Cataratas de Pulhapanzak', 'Fortaleza de San Fernando de Omoa', 'Playas de Puerto Cortés', 'Parque Nacional Cusuco', 'Museo de la Naturaleza (SPS)', 'Muelle de Puerto Cortés', 'Cienaguita (Playas)', 'Museo de Antropología e Historia (SPS)'], comidas: ['Baleadas', 'Pollo Chuco', 'Pastelitos de Perro', 'Sopa de Frijoles con Huevo', 'Sopa de Mondongo', 'Tajadas de plátano maduro', 'Atol de Elote', 'Chuletas de Cerdo'] },
        'Choluteca': { lugares: ['Playas de Cedeño', 'Reserva de Vida Silvestre El Jicarito', 'Museo de Historia Regional (Choluteca)', 'Puente de Choluteca', 'Manglares de la zona de Cedeño', 'Playas de Punta Condega', 'Aguas Termales en El Corpus', 'Iglesia Inmaculada Concepción'], comidas: ['Rosquillas en Miel', 'Chancletas de Pataste', 'Cuznaca (Dulce de mango)', 'Tamales Pisques', 'Curiles (mariscos locales)', 'Pescado Frito (de la zona costera)', 'Sopa de Mariscada Sureña', 'Queso Frito'] },
        'El Paraíso': { lugares: ['Fábricas de Puros (Danlí)', 'Aguas Termales de San Francisco de la Venta', 'Cascada de El Cacao', 'Montaña de Teupasenti', 'Reserva de Vida Silvestre Texíguat (Zona El Paraíso)', 'Fincas de café (Ruta)', 'Iglesia Inmaculada Concepción (Danlí)', 'Parque Nacional La Botija (Montaña)'], comidas: ['Ticuco (Tamales de frijol tierno)', 'Atol de Elote', 'Cuajada de Danlí', 'Nacatamales', 'Ayote en Miel', 'Tamalitos de Cambray', 'Carne de Cerdo Asada', 'Tamales de Yuca'] },
        'Francisco Morazán': { lugares: ['El Cristo del Picacho', 'Parque Nacional La Tigra', 'Parque Nacional El Hatillo', 'Valle de Ángeles (Pueblo Artesanal)', 'Basílica de Suyapa', 'Ruinas de Choluteca La Vieja (Ojojona)', 'Santa Lucía (Pueblo colonial)', 'Museo para la Identidad Nacional (MIN)'], comidas: ['Carne Asada (con chimol y frijoles)', 'Nacatamales', 'Sopa de Mondongo', 'Rosquillas de Maíz', 'Sopa de Olla', 'Torrejas', 'Frijoles Fritos (con queso)', 'Tamales de Elote'] },
        'Intibucá': { lugares: ['Lagos de Chiligatoro', 'Gruta de La Esperanza', 'Cascadas de La Campa', 'La Gruta de Las Mercedes', 'El Cerrito (Mirador)', 'Feria de la Papa y el Vino (Evento Anual)', 'Senderos Lencas', 'Iglesia La Merced (La Esperanza)'], comidas: ['Sopa de Gallina India', 'Atol Shuco', 'Chicha (bebida)', 'Ticucos', 'Miel de Abeja (de la zona)', 'Pan Casero y Rosquetes', 'Tamales de Elote', 'Sopa de Frijoles (con cuajada)'] },
        'La Paz': { lugares: ['Cascada de Santa Elena', 'Parque Arqueológico Yarumela', 'Montaña de Chinacla', 'El Picacho de Chinacla', 'Iglesia Colonial de La Paz', 'Museo Regional de Arqueología (Yarumela)', 'Balnearios en los ríos locales', 'Templo Católico de San Antonio del Norte'], comidas: ['Chancletas de Pataste', 'Sopa de Albóndigas', 'Torrejas (de yuca o plátano)', 'Rosquillas', 'Queso de Capa', 'Tamalitos de Frijol', 'Tamales de Cerdo', 'Sopa de Gallina'] },
        'Lempira': { lugares: ['Fuerte de San Cristóbal (Gracias)', 'Parque Nacional Montaña de Celaque', 'Aguas Termales de Gracias', 'Casa Presidencial de Lempira (museo)', 'Laguna Madre Vieja', 'Fincas de Café (Ruta del Lenca)', 'Monumento a Lempira', 'Iglesia La Merced (Gracias)'], comidas: ['Atol Shuco', 'Chilate', 'Ticucos', 'Totopostes', 'Sopa de Olla Lenca', 'Cuajada en penca', 'Tamalitos de Elote', 'Rosquillas (de Maíz)'] },
        'Ocotepeque': { lugares: ['Reserva Biológica Güisayote', 'Aguas Termales de Azacualpa', 'Ruinas de la Antigua Ocotepeque', 'Río Lempa (Fuente en la montaña)', 'Iglesia de San Francisco de Asís', 'Mirador de Ocotepeque', 'La Casa de la Cultura de Ocotepeque', 'Montaña de El Pital'], comidas: ['Sopa de Tortas de Pescado', 'Tamalitos de Cambray', 'Atol de Elote', 'Cuajada', 'Sopa de Frijoles', 'Chilate', 'Carne de Cerdo Asada', 'Tamal de Frijol con arroz'] },
        'Olancho': { lugares: ['Parque Nacional Sierra de Agalta', 'Laguna de Talgua (Catacamas)', 'Cueva de El Gigante (Catacamas)', 'Cuevas de Cuyamel', 'Río Tinto', 'Catedral de Juticalpa', 'Aguas Termales de San Francisco de la Paz', 'Reserva Biológica Sierra de Río Tinto'], comidas: ['Cuajada en Penca', 'Carne de res con Yuca (o Guisada)', 'Tamales de Frijol', 'Quesillo', 'Sopa de Olla Olanchana', 'Tamalitos de Cambray', 'Atol de Maíz', 'Tustacas'] },
        'Santa Bárbara': { lugares: ['Parque Nacional Montaña de Santa Bárbara (PANAMOSAB)', 'Cuevas de la Kinkora', 'Festival del Junco (Evento Artesanal)', 'Balnearios en los ríos de la zona', 'Cuevas de Gualala', 'Fincas de café (Ruta)', 'El Junco (Área de artesanía)', 'Iglesia de Santa Bárbara (Histórica)'], comidas: ['Montucas', 'Atol de Piña', 'Sopa de Olla', 'Tamalitos de Frijol', 'Pescado Frito (de la zona del Lago)', 'Tamales de Chicharrones', 'Sopa de Pescado', 'Queso Frito'] },
        'Valle': { lugares: ['Puerto de San Lorenzo (Muelle)', 'Isla del Tigre (Amapala)', 'Playas del Golfo de Fonseca (Playa Grande)', 'Humedales del Golfo de Fonseca', 'Isla Zacate Grande', 'Casco Urbano de Amapala', 'El Jicarito (reserva de manglares)', 'Reserva Biológica de Guanacaste'], comidas: ['Pescado Frito (del Golfo)', 'Ceviches (de camarón y pescado)', 'Sopa Marinera', 'Curiles (en salsa o frescos)', 'Tamalitos de Elote', 'Camarones a la diabla', 'Sopa de Almejas', 'Dulce de Nance'] },
        'Yoro': { lugares: ['Parque Nacional Pico Pijol', 'Cuevas de la Lluvia de Peces', 'Balneario El Ocote', 'Río Sulaco', 'Fincas de palma africana y cacao', 'Montaña de Mico Quemado', 'Parque Nacional Cerro Azul de Meámbar (Parte Yoro)', 'Río Cuyamapa'], comidas: ['Sopa de Jutes (Caracoles de río)', 'Sopa de Frijoles con Hueso de Cerdo', 'Cuajada de Leche', 'Atol de Maíz', 'Sopa de Mondongo', 'Tamales de Maíz', 'Carne de Res Asada', 'Tajadas de Plátano'] }
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
        const info = destinosData[departamento];
        if (!info) return;
        document.getElementById('modalInfoTitle').innerHTML = `<i class="fas fa-map-marker-alt"></i> ${departamento}`;
        const lugaresHtml = info.lugares.map(lugar => `<div class="info-card"><div class="info-card-img"><i class="fas fa-landmark"></i></div><div class="info-card-body"><div class="info-card-title">${lugar}</div></div></div>`).join('');
        const comidasHtml = info.comidas.map(comida => `<div class="info-card"><div class="info-card-img"><i class="fas fa-utensils"></i></div><div class="info-card-body"><div class="info-card-title">${comida}</div></div></div>`).join('');
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
