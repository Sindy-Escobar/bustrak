@extends('layouts.layoutuser')
    @section('contenido')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - BusTrak</title>
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
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
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
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            margin-bottom: 20px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 48px;
        }
        .profile-info {
            display: grid;
            gap: 20px;
        }
        .info-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-label {
            font-weight: 600;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
<nav class="navbar">
    <h1> BusTrak</h1>
    <div class="nav-links">
        <a href="/">Inicio</a>
        <a href="{{ route('cliente.perfil') }}">Mi Perfil</a>
        <a href="{{ route('cliente.reservas') }}">Mis Reservas</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="card">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr($usuario->name, 0, 1)) }}
            </div>
            <h2>{{ $usuario->name }}</h2>
            <p style="color: #666;">Cliente BusTrak</p>
        </div>

        <div class="profile-info">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value">{{ $usuario->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $usuario->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipo de cuenta:</div>
                <div class="info-value">{{ ucfirst($usuario->role) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">
                    <span class="badge">{{ ucfirst($usuario->estado) }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Miembro desde:</div>
                <div class="info-value">{{ $usuario->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
