<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - BusTrak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 24px;
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .welcome h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .menu-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s;
            text-decoration: none;
            color: #333;
        }
        .menu-card:hover {
            transform: translateY(-5px);
        }
        .menu-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .menu-card h3 {
            margin-bottom: 10px;
            color: #667eea;
        }
        .menu-card p {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <h1> BusTrak - Panel de Administración</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">Cerrar Sesión</button>
    </form>
</nav>

<div class="container">
    <div class="welcome">
        <h2>Bienvenido, {{ Auth::user()->name }}</h2>
        <p style="color: #666;">Panel de control administrativo</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>TOTAL USUARIOS</h3>
            <div class="stat-number">{{ $totalUsuarios }}</div>
        </div>
        <div class="stat-card">
            <h3>USUARIOS ACTIVOS</h3>
            <div class="stat-number" style="color: #28a745;">{{ $usuariosActivos }}</div>
        </div>
        <div class="stat-card">
            <h3>USUARIOS INACTIVOS</h3>
            <div class="stat-number" style="color: #dc3545;">{{ $usuariosInactivos }}</div>
        </div>
    </div>

    <div class="menu-grid">
        <a href="{{ route('admin.usuarios') }}" class="menu-card">
            <div class="menu-icon">👥</div>
            <h3>Gestión de Usuarios</h3>
            <p>Validar y administrar usuarios del sistema (HU24)</p>
        </a>

        <div class="menu-card">
            <div class="menu-icon">🚌</div>
            <h3>Gestión de Buses</h3>
            <p>Administrar flota de buses</p>
        </div>

        <div class="menu-card">
            <div class="menu-icon">📅</div>
            <h3>Reservas</h3>
            <p>Ver y gestionar todas las reservas</p>
        </div>

        <div class="menu-card">
            <div class="menu-icon">📊</div>
            <h3>Reportes</h3>
            <p>Estadísticas y reportes del sistema</p>
        </div>
    </div>
</div>
</body>
</html>
