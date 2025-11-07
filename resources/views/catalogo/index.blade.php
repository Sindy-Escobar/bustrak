<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusTrak - Catálogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #e8f5f3; min-height: 100vh; }
        .topbar { background: linear-gradient(180deg, #1e63b8, #1976d2); box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06); padding: 14px 28px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: flex; align-items: center; gap: 14px; }
        .brand-logo { width: 42px; height: 42px; border-radius: 10px; background: linear-gradient(180deg, #1976d2, #1e63b8); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; box-shadow: 0 3px 8px rgba(29, 78, 216, 0.18); }
        .brand-text { font-weight: 700; font-size: 1.2rem; color: #ffffff; }
        .brand-sub { font-size: 0.85rem; color: rgba(255, 255, 255, 0.8); font-weight: 600; }
        .topbar-title { flex-grow: 1; text-align: center; }
        .topbar-title h1 { font-size: 1.4rem; font-weight: 700; color: #ffffff; margin: 0; }
        .btn-admin { background-color: white; color: #1976d2; border: 2px solid white; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-admin:hover { background-color: #f0f0f0; transform: translateY(-2px); }
        .container-main { max-width: 1400px; margin: 0 auto; padding: 24px; }
        .filtros-wrapper { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); margin-bottom: 24px; }
        .filtros-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .filtros-header h2 { font-size: 18px; font-weight: 700; color: #1f2937; margin: 0; }
        .btn-limpiar { background-color: #fee2e2; color: #dc2626; border: none; padding: 10px 16px; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer; transition: background-color 0.3s; }
        .btn-limpiar:hover { background-color: #fecaca; }
        .filtros-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
        .filtro-group { display: flex; flex-direction: column; }
        .filtro-group label { font-size: 13px; font-weight: 700; color: #1f2937; margin-bottom: 8px; }
        .filtro-group input, .filtro-group select { padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background-color: #f9fafb; }
        .filtro-group input:focus, .filtro-group select:focus { outline: none; border-color: #1976d2; background-color: white; box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1); }
        .departamentos-section { margin-bottom: 24px; }
        .departamentos-section h2 { font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 16px; }
        .departamentos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; }
        .departamento-card { padding: 16px; border-radius: 8px; text-align: center; color: white; font-weight: 600; cursor: pointer; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); transition: all 0.2s; font-size: 13px; min-height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #0d9488 0%, #0d9488dd 100%); }
        .departamento-card:hover { transform: translateY(-4px); box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2); }
        .departamento-card img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; background: white; padding: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.15); }
        .viajes-section { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
        .viajes-section h2 { font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 20px; }
        .viaje-card { border-left: 4px solid #0d9488; padding: 18px; background: #fafbfc; border-radius: 8px; margin-bottom: 12px; transition: all 0.2s; border: 1px solid #e5e7eb; }
        .viaje-card:hover { background: white; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); }
        .viaje-content { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr 140px; gap: 20px; align-items: center; }
        .viaje-info { display: flex; flex-direction: column; }
        .viaje-label { font-size: 11px; color: #9ca3af; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
        .viaje-value { font-size: 14px; font-weight: 600; color: #1f2937; }
        .viaje-precio { font-size: 20px; color: #0d9488; font-weight: 700; }
        .btn-comprar { background-color: #0d9488; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 13px; cursor: pointer; transition: background-color 0.2s; }
        .btn-comprar:hover { background-color: #0f766e; }
        .modal-waitlist { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-waitlist.show { display: flex; }
        .modal-content-waitlist { background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); animation: slideUp 0.3s ease-out; }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-waitlist h2 { color: #0d9488; margin-bottom: 15px; font-size: 24px; display: flex; align-items: center; gap: 10px; justify-content: center; }
        .modal-waitlist p { color: #6b7280; margin-bottom: 20px; line-height: 1.6; }
        .waitlist-info { background: #f0fdf4; border-left: 4px solid #0d9488; padding: 15px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; color: #1f2937; }
        .waitlist-info strong { color: #0d9488; display: block; margin-bottom: 5px; }
        .modal-waitlist button { background-color: #0d9488; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer; width: 100%; transition: background-color 0.2s; }
        .modal-waitlist button:hover { background-color: #0f766e; }
        @media (max-width: 768px) { .viaje-content { grid-template-columns: 1fr; } .btn-comprar { width: 100%; } }
    </style>
</head>
<body>
<div class="modal-waitlist" id="waitlistModal">
    <div class="modal-content-waitlist">
        <h2><i class="fas fa-check-circle"></i> ¡Registrado!</h2>
        <p>Has sido agregado exitosamente a la lista de espera.</p>
        <div class="waitlist-info">
            <strong>Detalles:</strong>
            <div id="waitlistDetails"></div>
        </div>
        <p style="font-size: 12px; color: #9ca3af;">Te notificaremos cuando el viaje esté disponible.</p>
        <button onclick="cerrarWaitlist()">Aceptar</button>
    </div>
</div>

<header class="topbar">
    <div class="brand">
        <div class="brand-logo">B</div>
        <div>
            <div class="brand-text">BusTrak</div>
            <div class="brand-sub">Sistema de Gestión</div>
        </div>
    </div>
    <div class="topbar-title">
        <h1>Catálogo de Viajes</h1>
    </div>
    <a href="#" class="btn-admin"><i class="fas fa-cog"></i> Panel Administrativo</a>
</header>

<div class="container-main">
    <div class="filtros-wrapper">
        <div class="filtros-header">
            <h2><i class="fas fa-search"></i> Filtros de Búsqueda</h2>
            <button class="btn-limpiar" onclick="location.reload()"><i class="fas fa-times"></i> Limpiar Filtros</button>
        </div>
        <div class="filtros-grid">
            <div class="filtro-group">
                <label><i class="fas fa-map-marker-alt"></i> Origen</label>
                <input type="text" value="Danlí" disabled style="font-weight: 700;">
            </div>
            <div class="filtro-group">
                <label><i class="fas fa-map-marker-alt"></i> Destino</label>
                <select id="destino" onchange="filtrar()">
                    <option value="">Seleccionar</option>
                    <option value="Atlántida">Atlántida</option>
                    <option value="Cholulteca">Cholulteca</option>
                    <option value="Colón">Colón</option>
                    <option value="Comayagua">Comayagua</option>
                    <option value="Copán">Copán</option>
                    <option value="Cortés">Cortés</option>
                    <option value="El Paraíso">El Paraíso</option>
                    <option value="Francisco Morazán">Francisco Morazán</option>
                    <option value="Gracias a Dios">Gracias a Dios</option>
                    <option value="Intibucá">Intibucá</option>
                    <option value="Islas de la Bahía">Islas de la Bahía</option>
                    <option value="La Paz">La Paz</option>
                    <option value="Lempira">Lempira</option>
                    <option value="Ocotepeque">Ocotepeque</option>
                    <option value="Olancho">Olancho</option>
                    <option value="Santa Bárbara">Santa Bárbara</option>
                    <option value="Valle">Valle</option>
                    <option value="Yoro">Yoro</option>
                </select>
            </div>
            <div class="filtro-group">
                <label><i class="fas fa-calendar"></i> Fecha</label>
                <select id="fecha" onchange="mostrarDias()">
                    <option value="">Seleccionar Mes</option>
                    <option value="1">Enero 2025</option>
                    <option value="2">Febrero 2025</option>
                    <option value="3">Marzo 2025</option>
                    <option value="4">Abril 2025</option>
                    <option value="5">Mayo 2025</option>
                    <option value="6">Junio 2025</option>
                    <option value="7">Julio 2025</option>
                    <option value="8">Agosto 2025</option>
                    <option value="9">Septiembre 2025</option>
                    <option value="10">Octubre 2025</option>
                    <option value="11">Noviembre 2025</option>
                    <option value="12">Diciembre 2025</option>
                </select>
            </div>
            <div class="filtro-group" id="diasContainer" style="display:none;">
                <label><i class="fas fa-calendar-day"></i> Día</label>
                <select id="dia" onchange="filtrar()">
                    <option value="">Seleccionar Día</option>
                </select>
            </div>
            <div class="filtro-group">
                <label><i class="fas fa-clock"></i> Horario</label>
                <select id="horario" onchange="filtrar()">
                    <option value="">Seleccionar</option>
                    <option value="6:00 AM">6:00 AM</option>
                    <option value="7:30 AM">7:30 AM</option>
                    <option value="9:00 AM">9:00 AM</option>
                    <option value="10:30 AM">10:30 AM</option>
                    <option value="12:00 PM">12:00 PM</option>
                    <option value="1:30 PM">1:30 PM</option>
                    <option value="3:00 PM">3:00 PM</option>
                    <option value="4:30 PM">4:30 PM</option>
                    <option value="6:00 PM">6:00 PM</option>
                </select>
            </div>
        </div>
    </div>

    <div class="departamentos-section">
        <h2><i class="fas fa-map"></i> Destinos desde Danlí</h2>
        <div class="departamentos-grid">
            <div class="departamento-card" onclick="filtrarDestino('Cortés')"><img src="/catalago/img/cortes.jpg" alt="Cortés" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Cortés</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Atlántida')"><img src="/catalago/img/atlantida.jpg" alt="Atlántida" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Atlántida</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Francisco Morazán')"><img src="/catalago/img/francisco.jpg" alt="Francisco Morazán" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Francisco Morazán</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Cholulteca')"><img src="/catalago/img/cholulteca.jpg" alt="Cholulteca" onerror="this.onerror=null;this.src='/catalago/img/cholulteca.jpg'"><div>Cholulteca</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Comayagua')"><img src="/catalago/img/comayagua.jpg" alt="Comayagua" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Comayagua</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Colón')"><img src="/catalago/img/colon.jpg" alt="Colón" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Colón</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Copán')"><img src="/catalago/img/copan.jpg" alt="Copán" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Copán</div></div>
            <div class="departamento-card" onclick="filtrarDestino('El Paraíso')"><img src="/catalago/img/elparaiso.jpg" alt="El Paraíso" onerror="this.src='/catalago/img/default_bus.jpg'"><div>El Paraíso</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Yoro')"><img src="/catalago/img/yoro.jpg" alt="Yoro" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Yoro</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Gracias a Dios')"><img src="/catalago/img/gracias.jpg" alt="Gracias a Dios" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Gracias a Dios</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Intibucá')"><img src="/catalago/img/intibuca.jpg" alt="Intibucá" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Intibucá</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Islas de la Bahía')"><img src="/catalago/img/islas.jpg" alt="Islas de la Bahía" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Islas de la Bahía</div></div>
            <div class="departamento-card" onclick="filtrarDestino('La Paz')"><img src="/catalago/img/lapaz.jpg" alt="La Paz" onerror="this.src='/catalago/img/default_bus.jpg'"><div>La Paz</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Lempira')"><img src="/catalago/img/lempira.jpg" alt="Lempira" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Lempira</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Ocotepeque')"><img src="/catalago/img/ocotepeque.jpg" alt="Ocotepeque" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Ocotepeque</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Olancho')"><img src="/catalago/img/olancho.jpg" alt="Olancho" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Olancho</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Santa Bárbara')"><img src="/catalago/img/santabarbara.jpg" alt="Santa Bárbara" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Santa Bárbara</div></div>
            <div class="departamento-card" onclick="filtrarDestino('Valle')"><img src="/catalago/img/valle.jpg" alt="Valle" onerror="this.src='/catalago/img/default_bus.jpg'"><div>Valle</div></div>
        </div>
    </div>

    <div class="viajes-section">
        <h2><i class="fas fa-plane"></i> Viajes Disponibles <span id="contador">(0)</span></h2>
        <div id="viajesContainer"></div>
    </div>
</div>

<script>
    const viajesDatos = [
        { ruta: 'Danlí → Cortés', fecha: '2025-01-15', horario: '6:00 AM', duracion: '3h 30m', precio: 250, destino: 'Cortés' },
        { ruta: 'Danlí → Atlántida', fecha: '2025-02-10', horario: '9:00 AM', duracion: '4h 15m', precio: 320, destino: 'Atlántida' },
        { ruta: 'Danlí → Francisco Morazán', fecha: '2025-03-20', horario: '12:00 PM', duracion: '2h 45m', precio: 180, destino: 'Francisco Morazán' },
        { ruta: 'Danlí → Cholulteca', fecha: '2025-04-12', horario: '7:30 AM', duracion: '5h 20m', precio: 380, destino: 'Cholulteca' },
        { ruta: 'Danlí → Comayagua', fecha: '2025-05-08', horario: '3:00 PM', duracion: '3h 10m', precio: 210, destino: 'Comayagua' },
        { ruta: 'Danlí → Colón', fecha: '2025-06-18', horario: '10:30 AM', duracion: '4h 05m', precio: 290, destino: 'Colón' },
        { ruta: 'Danlí → Copán', fecha: '2025-07-25', horario: '1:30 PM', duracion: '4h 40m', precio: 340, destino: 'Copán' },
        { ruta: 'Danlí → El Paraíso', fecha: '2025-08-14', horario: '4:30 PM', duracion: '2h 55m', precio: 165, destino: 'El Paraíso' },
        { ruta: 'Danlí → Yoro', fecha: '2025-09-22', horario: '6:00 PM', duracion: '3h 20m', precio: 220, destino: 'Yoro' },
        { ruta: 'Danlí → Gracias a Dios', fecha: '2025-10-05', horario: '8:00 AM', duracion: '4h 50m', precio: 295, destino: 'Gracias a Dios' },
        { ruta: 'Danlí → Intibucá', fecha: '2025-11-30', horario: '2:00 PM', duracion: '3h 25m', precio: 185, destino: 'Intibucá' },
        { ruta: 'Danlí → Islas de la Bahía', fecha: '2025-12-15', horario: '5:30 AM', duracion: '6h 10m', precio: 450, destino: 'Islas de la Bahía' }
    ];

    function getDaysInMonth(m) { const d=[31,29,31,30,31,30,31,31,30,31,30,31]; return d[m-1]; }
    function mostrarDias() {
        const m=document.getElementById('fecha').value, dc=document.getElementById('diasContainer'), ds=document.getElementById('dia');
        if(m==='') { dc.style.display='none'; ds.innerHTML='<option value="">Seleccionar Día</option>'; return; }
        dc.style.display='flex'; let h='<option value="">Seleccionar Día</option>';
        for(let i=1;i<=getDaysInMonth(parseInt(m));i++) h+=`<option value="${String(i).padStart(2,'0')}">${String(i).padStart(2,'0')}</option>`;
        ds.innerHTML=h;
    }
    function filtrarDestino(d) { document.getElementById('destino').value=d; filtrar(); }
    function filtrar() {
        const d=document.getElementById('destino').value, m=document.getElementById('fecha').value, dia=document.getElementById('dia').value, h=document.getElementById('horario').value;
        let f=''; if(m&&dia) f=`2025-${m.padStart(2,'0')}-${dia}`;
        mostrarViajes(viajesDatos.filter(v=>{
            const cd=!d||v.destino===d, cf=!f||v.fecha===f, ch=!h||v.horario===h;
            return cd&&cf&&ch;
        }));
    }
    function solicitarViaje(ruta, fecha, horario, precio) {
        const d=document.getElementById('destino').value, m=document.getElementById('fecha').value, dia=document.getElementById('dia').value, h=document.getElementById('horario').value;
        let ff=''; if(m&&dia) ff=`${dia}/${m}/2025`;
        document.getElementById('waitlistDetails').innerHTML=`
        <strong>Ruta:</strong> ${ruta||'No seleccionada'}<br>
        <strong>Fecha:</strong> ${ff||'No seleccionada'}<br>
        <strong>Horario:</strong> ${h||'No seleccionado'}<br>
        <strong>Precio:</strong> L${precio.toFixed(2)||'0.00'}
    `;
        document.getElementById('waitlistModal').classList.add('show');
    }
    function cerrarWaitlist() { document.getElementById('waitlistModal').classList.remove('show'); }
    function mostrarViajes(v) {
        const c=document.getElementById('viajesContainer'), cn=document.getElementById('contador');
        if(v.length===0) {
            const destino = document.getElementById('destino').value;
            const mes = document.getElementById('fecha').value;
            const dia = document.getElementById('dia').value;
            const horario = document.getElementById('horario').value;

            let mensajeFiltros = '';
            if (destino) mensajeFiltros += `<strong>Destino:</strong> ${destino}<br>`;
            if (mes && dia) mensajeFiltros += `<strong>Fecha:</strong> ${dia}/${mes}/2025<br>`;
            if (horario) mensajeFiltros += `<strong>Horario:</strong> ${horario}<br>`;

            c.innerHTML = `
            <div class="sin-resultados" style="background: #fff3cd; padding: 40px; border-radius: 12px; border: 2px dashed #ffc107;">
                <div style="font-size: 3rem; margin-bottom: 20px;">😕</div>
                <h3 style="color: #856404; margin-bottom: 15px; font-size: 1.3rem;">No hay viajes disponibles</h3>
                <p style="color: #856404; margin-bottom: 20px;">No encontramos viajes con los siguientes criterios:</p>
                <div style="background: white; padding: 15px; border-radius: 8px; margin-bottom: 25px; text-align: left; display: inline-block;">
                    ${mensajeFiltros || '<em>Sin filtros aplicados</em>'}
                </div>
                <div style="margin-top: 20px;">
                    <button onclick="solicitarViaje('Viaje personalizado', '2025-01-01', 'A definir', 0)" style="background: #28a745; color: white; border: none; padding: 14px 30px; border-radius: 8px; font-weight: 700; font-size: 15px; cursor: pointer; margin-right: 10px; box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);">
                        <i class="fas fa-check"></i> Solicitar este viaje
                    </button>
                    <button onclick="location.reload()" style="background: #6c757d; color: white; border: none; padding: 14px 30px; border-radius: 8px; font-weight: 700; font-size: 15px; cursor: pointer; box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);">
                        <i class="fas fa-sync"></i> Ver todos los viajes
                    </button>
                </div>
            </div>
        `;
            cn.textContent = '(0)';
            return;
        }
        c.innerHTML=v.map(x=>`<div class="viaje-card"><div class="viaje-content"><div class="viaje-info"><span class="viaje-label">Ruta</span><span class="viaje-value">${x.ruta}</span></div><div class="viaje-info"><span class="viaje-label">Fecha</span><span class="viaje-value">${new Date(x.fecha).toLocaleDateString('es-ES',{day:'2-digit',month:'short',year:'numeric'})}</span></div><div class="viaje-info"><span class="viaje-label">Horario</span><span class="viaje-value">${x.horario}</span></div><div class="viaje-info"><span class="viaje-label">Duración</span><span class="viaje-value">${x.duracion}</span></div><div class="viaje-info"><span class="viaje-label">Precio</span><span class="viaje-precio">L${x.precio.toFixed(2)}</span></div><button class="btn-comprar" onclick="solicitarViaje('${x.ruta}','${x.fecha}','${x.horario}',${x.precio})">Comprar</button></div></div>`).join('');
        cn.textContent=`(${v.length})`;
    }
    mostrarViajes(viajesDatos);
</script>
</body>
</html>
