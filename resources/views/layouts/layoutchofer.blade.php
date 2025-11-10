<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Bustrak - Panel de Usuario')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/a2e0c8b2b1.js" crossorigin="anonymous"></script>

    <style>
        html { height: 100%; overflow-y: scroll; }
        body { margin: 0; font-family: "Segoe UI", Roboto, sans-serif; background-color: #f5f7fa; height: 100%; }
        main { display: flex; min-height: 100vh; overflow: hidden; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background-color: #101827;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding-top: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            box-shadow: 3px 0 8px rgba(0,0,0,0.3);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #5cb3ff #101827;
        }
        .sidebar::-webkit-scrollbar { width: 8px; }
        .sidebar::-webkit-scrollbar-thumb { background-color: #5cb3ff; border-radius: 4px; }

        .user-info { text-align: center; margin-bottom: 2rem; }
        .user-avatar {
            width: 70px; height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5cb3ff, #1e63b8);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.8rem;
            font-size: 2rem; color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .user-info h3 { font-size: 1.1rem; font-weight: 600; color: #fff; margin: 0; }
        .user-info small { color: #9ca3af; font-size: 0.85rem; }

        /* ===== NAVEGACIÓN ===== */
        .nav-section { margin-bottom: 1rem; }
        .btn-toggle {
            display: flex; align-items: center; justify-content: space-between;
            width: 100%; padding: 0.7rem 1rem;
            font-weight: 600; font-size: 0.95rem; color: #cfd8e3;
            background-color: transparent; border: none; border-left: 4px solid transparent;
            border-radius: 0.4rem; transition: all 0.25s ease;
        }
        .btn-toggle i { margin-right: 0.7rem; color: #5cb3ff; }
        .btn-toggle:hover, .btn-toggle:focus { color: #fff; border-left: 4px solid #00b7ff; }
        .btn-toggle[aria-expanded="true"] { border-left: 4px solid #00b7ff; }
        .btn-toggle .chevron { transition: transform 0.3s ease; }
        .btn-toggle[aria-expanded="true"] .chevron { transform: rotate(90deg); }
        .btn-toggle-nav a {
            display: block; padding: 0.45rem 0 0.45rem 2.8rem;
            font-size: 0.9rem; color: #9ca3af; text-decoration: none; transition: all 0.25s ease;
        }
        .btn-toggle-nav a:hover { color: #fff; }
        .btn-toggle-nav a.active { color: #00b7ff; font-weight: 600; }

        /* ===== CONTENIDO ===== */
        .content-area { flex-grow: 1; margin-left: 260px; background-color: #f5f7fa; padding: 28px; min-height: 100vh; }

        @media (max-width:768px){
            .sidebar { position: relative; width: 100%; height: auto; }
            .content-area { margin-left: 0; }
        }

        .sidebar.collapsed { width: 0; overflow: hidden; transition: all 0.3s ease; }
        .content-area.expanded { margin-left: 0; width: 100%; transition: all 0.3s ease; }
        #toggleSidebar { display: inline-flex; align-items: center; gap: 6px; }
    </style>
</head>

<body>
<main>
    <nav class="sidebar">
        <!-- Información del usuario -->
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user"></i></div>
            <h3>{{ Auth::user()->nombre_completo ?? 'Chofer' }}</h3>
            <small>{{ Auth::user()->email ?? 'usuario@bustrak.com' }}</small>
        </div>

        <!-- Secciones del panel de usuario -->
        <div class="nav-section">
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#miCuenta" aria-expanded="{{ request()->routeIs('usuario.perfil*') ? 'true':'false' }}">
                <span><i class="fas fa-user-circle"></i> Cuenta</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav {{ request()->routeIs('usuario.perfil*') ? 'show':'' }}" id="miCuenta">
                <a href="{{ route('empleado.perfil') }}" class="{{ request()->routeIs('empleado.perfil') ? 'active':'' }}">Ver perfil</a>

            </div>
        </div>

        <div class="nav-section">
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#viajes" aria-expanded="{{ request()->routeIs('usuario.viajes*') ? 'true':'false' }}">
                <span><i class="fas fa-list"></i> Acciones</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>

            <div class="collapse btn-toggle-nav {{ request()->routeIs('usuario.viajes*') ? 'show':'' }}" id="viajes">
                <a href="{{ route('usuario.viajes') }}" class="{{ request()->routeIs('usuario.viajes') ? 'active':'' }}">Ver lista de viajes</a>
                <a href="{{ route('usuario.pasajeros') }}" class="{{ request()->routeIs('usuario.pasajeros') ? 'active':'' }}">Pasajeros y reservas</a>
                <a href="{{ route('usuario.confirmar') }}" class="{{ request()->routeIs('usuario.confirmar') ? 'active':'' }}">Confirmar salida/llegada</a>
                <a href="{{ route('usuario.qr') }}" class="{{ request()->routeIs('usuario.qr') ? 'active':'' }}">Escanear código QR</a>
            </div>
        </div>

        <!-- Cerrar sesión -->
        <div class="nav-section">
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#soporte" aria-expanded="false">
                <span><i class="fas fa-headset"></i> Cerrar sesion</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav" id="soporte">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>

    </nav>

    <div class="content-area">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button id="toggleSidebar" class="btn btn-outline-primary"><i class="fas fa-bars"></i> Menú</button>
        </div>
        @yield('contenido')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let openGroups = JSON.parse(localStorage.getItem('sidebarUserOpenGroups') || '[]');
        openGroups.forEach(id => {
            const el = document.querySelector(`#${id}`);
            if(el){ el.classList.add('show'); const btn = el.previousElementSibling; if(btn) btn.setAttribute('aria-expanded','true'); }
        });
        document.querySelectorAll('.collapse').forEach(group => {
            group.addEventListener('shown.bs.collapse', ()=>{ if(!openGroups.includes(group.id)){ openGroups.push(group.id); localStorage.setItem('sidebarUserOpenGroups', JSON.stringify(openGroups)); } });
            group.addEventListener('hidden.bs.collapse', ()=>{ openGroups = openGroups.filter(id=>id!==group.id); localStorage.setItem('sidebarUserOpenGroups', JSON.stringify(openGroups)); });
        });

        const sidebar=document.querySelector('.sidebar'); const content=document.querySelector('.content-area'); const toggleBtn=document.getElementById('toggleSidebar');
        toggleBtn.addEventListener('click',function(){
            sidebar.classList.toggle('collapsed'); content.classList.toggle('expanded');
            const icon=toggleBtn.querySelector('i'); if(sidebar.classList.contains('collapsed')){ icon.classList.remove('fa-bars'); icon.classList.add('fa-xmark'); }else{ icon.classList.remove('fa-xmark'); icon.classList.add('fa-bars'); }
        });
    });
</script>
</body>
</html>
