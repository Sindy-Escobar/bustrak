<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda y Soporte - BusTrak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100%;
            overflow-y: scroll;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background-color: #f5f7fa;
            height: 100%;
        }

        main {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

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
            box-shadow: 3px 0 8px rgba(0, 0, 0, 0.3);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #5cb3ff #101827;
        }

        .sidebar::-webkit-scrollbar {
            width: 8px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #5cb3ff;
            border-radius: 4px;
        }

        /* ===== LOGO Y USUARIO ===== */
        .brand-logo {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
        }

        .brand-logo h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .brand-logo small {
            display: block;
            color: #5cb3ff;
            font-size: 0.85rem;
            margin-top: 0.3rem;
        }

        .user-info {
            text-align: center;
            margin-bottom: 2rem;
        }

        .user-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5cb3ff, #1e63b8);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.8rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .user-info h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffffff;
            margin: 0;
        }

        .user-info small {
            color: #9ca3af;
            font-size: 0.85rem;
        }

        /* ===== NAVEGACIÓN ===== */
        .nav-section {
            margin-bottom: 1rem;
        }

        .btn-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.7rem 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #cfd8e3;
            background-color: transparent;
            border: none;
            border-left: 4px solid transparent;
            border-radius: 0.4rem;
            transition: all 0.25s ease;
        }

        .btn-toggle i {
            margin-right: 0.7rem;
            color: #5cb3ff;
        }

        .btn-toggle:hover,
        .btn-toggle:focus {
            color: #fff;
            border-left: 4px solid #00b7ff;
        }

        .btn-toggle[aria-expanded="true"] {
            border-left: 4px solid #00b7ff;
        }

        .btn-toggle .chevron {
            transition: transform 0.3s ease;
        }

        .btn-toggle[aria-expanded="true"] .chevron {
            transform: rotate(90deg);
        }

        .btn-toggle-nav a {
            display: block;
            padding: 0.45rem 0 0.45rem 2.8rem;
            font-size: 0.9rem;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-toggle-nav a:hover {
            color: #fff;
        }

        .btn-toggle-nav a.active {
            color: #00b7ff;
            font-weight: 600;
        }

        /* ===== CONTENIDO ===== */
        .content-area {
            flex-grow: 1;
            margin-left: 260px;
            background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ===== CARD DE SOPORTE ===== */
        .container-soporte {
            width: 100%;
            max-width: 480px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            border: none;
            padding: 24px 20px;
            text-align: center;
            color: white;
        }

        .card-header i {
            font-size: 2.2rem;
            margin-bottom: 10px;
            display: block;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .card-header h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .card-body {
            padding: 18px;
            background: white;
        }

        .subtitle {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 0.8rem;
            color: #333;
            margin-bottom: 3px;
        }

        label i {
            color: #1976d2;
            margin-right: 6px;
            width: 14px;
            text-align: center;
            font-size: 0.85rem;
        }

        .form-control {
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            height: auto;
        }

        .form-control::placeholder {
            color: #aaa;
            font-size: 0.82rem;
        }

        .form-control:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 50px;
            font-family: inherit;
        }

        .form-text {
            font-size: 0.7rem;
            color: #999;
            margin-top: 2px;
        }

        .alert {
            border: none;
            border-radius: 8px;
            margin-bottom: 14px;
            font-size: 0.85rem;
            padding: 10px 12px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert i {
            margin-right: 6px;
        }

        .btn-wrapper {
            display: flex;
            gap: 10px;
            margin-top: 14px;
        }

        .btn {
            flex: 1;
            padding: 10px 14px;
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 118, 210, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn i {
            margin-right: 6px;
        }

        /* ===== TOGGLE SIDEBAR ===== */
        .sidebar.collapsed {
            width: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .content-area.expanded {
            margin-left: 0;
            width: 100%;
            transition: all 0.3s ease;
        }

        #toggleSidebar {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1976d2;
        }

        #toggleSidebar:hover {
            background: white;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width:768px){
            .sidebar {
                position: fixed;
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-area {
                margin-left: 0;
            }

            #toggleSidebar {
                left: 10px;
                top: 10px;
            }
        }
    </style>
</head>
<body>
<main>
    <nav class="sidebar">
        <!-- Logo de la Aplicación -->
        <div class="brand-logo">
            <h2>BusTrak</h2>
            <small>Sistema de Gestión</small>
        </div>

        <!-- Información del Usuario -->
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h3>Usuario Demo</h3>
            <small><i class="fas fa-envelope me-1"></i>usuario@bustrak.com</small>
        </div>

        <!-- Mi Cuenta -->
        <div class="nav-section">
            <button class="btn-toggle d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse"
                    data-bs-target="#miCuenta">
                <span><i class="fas fa-user-circle"></i> Cuenta</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav" id="miCuenta">
                <a href="/cliente/perfil">Ver perfil</a>
                <a href="/registro">Registrar usuario</a>
            </div>
        </div>

        <!-- Mis Reservas -->
        <div class="nav-section">
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#reservas">
                <span><i class="fas fa-ticket-alt"></i> Mis Reservas</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav" id="reservas">
                <a href="/cliente/reservas">Ver reservas</a>
            </div>
        </div>

        <!-- Rutas y Horarios -->
        <div class="nav-section">
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#rutas">
                <span><i class="fas fa-route"></i> Rutas y Horarios</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav" id="rutas">
                <a href="/consulta-paradas">Ver rutas disponibles</a>
            </div>
        </div>

        <!-- Notificaciones -->
        <div class="nav-section">
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#notificaciones">
                <span><i class="fas fa-bell"></i> Notificaciones</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav" id="notificaciones">
            </div>
        </div>

        <!-- Soporte -->
        <div class="nav-section">
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#soporte" aria-expanded="true">
                <span><i class="fas fa-headset"></i> Ayuda y Soporte</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav show" id="soporte">
                <a href="/ayuda-soporte" class="active">Enviar consulta</a>
                <a href="/login">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="content-area">
        <!-- Botón para mostrar/ocultar la barra lateral -->
        <button id="toggleSidebar">
            <i class="fas fa-bars"></i> Menú
        </button>

        <div class="container-soporte">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-headset"></i>
                    <h3>Ayuda y Soporte</h3>
                </div>

                <div class="card-body">
                    <p class="subtitle">
                        <i class="fas fa-info-circle"></i>
                        Completa el formulario y nos pondremos en contacto contigo lo antes posible.
                    </p>

                    <form id="soporteForm">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-user"></i> Nombre Completo
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="nombre"
                                name="nombre"
                                placeholder="Tu nombre completo"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="correo">
                                <i class="fas fa-envelope"></i> Correo Electrónico
                            </label>
                            <input
                                type="email"
                                class="form-control"
                                id="correo"
                                name="correo"
                                placeholder="tu@email.com"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="asunto">
                                <i class="fas fa-tag"></i> Asunto
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                id="asunto"
                                name="asunto"
                                placeholder="Asunto de tu consulta"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="mensaje">
                                <i class="fas fa-comments"></i> Mensaje
                            </label>
                            <textarea
                                class="form-control"
                                id="mensaje"
                                name="mensaje"
                                placeholder="Cuéntanos tu problema o duda..."
                                maxlength="1000"
                                required></textarea>
                            <small class="form-text"><i class="fas fa-info-circle"></i> Máximo 1000 caracteres</small>
                        </div>

                        <div class="btn-wrapper">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle Sidebar
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content-area');
        const toggleBtn = document.getElementById('toggleSidebar');

        toggleBtn.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
            }

            const icon = toggleBtn.querySelector('i');
            if (sidebar.classList.contains('collapsed') || sidebar.classList.contains('show')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        });

        // Cerrar sidebar al hacer clic fuera en móvil
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        });

        // Manejo del formulario
        document.getElementById('soporteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('¡Mensaje enviado con éxito! Nos pondremos en contacto contigo pronto.');
            this.reset();
        });
    });
</script>
</body>
</html>
