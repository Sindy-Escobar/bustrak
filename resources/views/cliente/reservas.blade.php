@extends('layouts.layoutuser')
    @section('contenido')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - BusTrak</title>
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
            background-color: #f5f5f5;
            padding: 0;
            min-height: 100vh;
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

        /* ===== CONTENIDO ORIGINAL ===== */
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width:768px){
            .sidebar {
                position: fixed;
                width: 260px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-area {
                margin-left: 0;
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
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#reservas" aria-expanded="true">
                <span><i class="fas fa-ticket-alt"></i> Mis Reservas</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav show" id="reservas">
                <a href="/cliente/reservas" class="active">Ver reservas</a>
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
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#soporte">
                <span><i class="fas fa-headset"></i> Ayuda y Soporte</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav" id="soporte">
                <a href="/ayuda-soporte">Enviar consulta</a>
            </div>
        </div>

        <!-- Sesión -->
        <div class="nav-section">
            <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#sesion">
                <span><i class="fas fa-sign-out-alt"></i> Sesión</span>
                <i class="fas fa-chevron-right chevron"></i>
            </button>
            <div class="collapse btn-toggle-nav" id="sesion">
                <a href="/login">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="content-area">
        <div class="container">
            <div class="card">
                <h2><i class="fas fa-ticket-alt" style="color: #1976d2; margin-right: 10px;"></i>Mis Reservas</h2>

                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-calendar-times"></i></div>
                    <h3>No tienes reservas aún</h3>
                    <p>Cuando realices una reserva, aparecerá aquí.</p>
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

        // Cerrar sidebar al hacer clic fuera en móvil
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    });
</script>
</body>
</html>
@endsection
