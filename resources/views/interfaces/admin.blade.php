@extends('layouts.apps')

@section('title', 'Dashboard Administrador')

@section('content')
    <!-- Grid de tarjetas -->
    <div class="cards-grid">

        <!-- Empleados -->
        <div class="card-big" onclick="window.location.href='{{ route('empleados.index') }}'">
            <div class="card-illustration">
                <!-- SVG estilo lineal + acento azul -->
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="8" r="3" stroke="#1e63b8" stroke-width="1.6" fill="#eaf4ff"/>
                    <path d="M4 20c0-3.2 2.8-6 8-6s8 2.8 8 6" stroke="#1976d2" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="card-title">Empleados</div>
            <p class="card-sub">Gestiona el personal</p>
        </div>

        <!-- Usuarios -->
        <div class="card-big" onclick="window.location.href='{{ route('usuarios.index') }}'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="7" width="18" height="11" rx="2" stroke="#1e63b8" stroke-width="1.6" fill="#fff"/>
                    <circle cx="8.5" cy="12" r="1.8" fill="#1976d2"/>
                    <circle cx="12" cy="12" r="1.8" fill="#1e63b8"/>
                    <circle cx="15.5" cy="12" r="1.8" fill="#1976d2"/>
                </svg>
            </div>
            <div class="card-title">Usuarios</div>
            <p class="card-sub">Controla cuentas</p>
        </div>

        <!-- Empresas -->
        <div class="card-big" onclick="window.location.href='{{ route('empresas.index') }}'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 20V8h16v12" stroke="#1e63b8" stroke-width="1.6" stroke-linejoin="round" fill="#eaf4ff"/>
                    <rect x="7" y="11" width="3" height="3" rx="0.5" fill="#1976d2"/>
                    <rect x="11" y="11" width="3" height="3" rx="0.5" fill="#1e63b8"/>
                </svg>
            </div>
            <div class="card-title">Empresas</div>
            <p class="card-sub">Administra empresas</p>
        </div>

        <!-- Terminales -->
        <div class="card-big" onclick="window.location.href='{{ route('terminales.index') }}'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2v11" stroke="#1e63b8" stroke-width="1.6" stroke-linecap="round"/>
                    <circle cx="12" cy="16" r="4" stroke="#1976d2" stroke-width="1.6" fill="#fff"/>
                </svg>
            </div>
            <div class="card-title">Terminales</div>
            <p class="card-sub">Control de terminales</p>
        </div>

        <!-- Buses -->
        <div class="card-big" onclick="window.location.href='/buses'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="6" width="20" height="10" rx="2" stroke="#1e63b8" stroke-width="1.6" fill="#fff"/>
                    <circle cx="7.5" cy="17" r="1.3" fill="#1976d2"/>
                    <circle cx="16.5" cy="17" r="1.3" fill="#1976d2"/>
                </svg>
            </div>
            <div class="card-title">Buses</div>
            <p class="card-sub">Vehículos registrados</p>
        </div>

        <!-- Choferes -->
        <div class="card-big" onclick="window.location.href='/choferes'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="9" r="2.4" stroke="#1e63b8" stroke-width="1.6" fill="#eaf4ff"/>
                    <path d="M6 20c1.5-3 4.5-4 6-4s4.5 1 6 4" stroke="#1976d2" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="card-title">Choferes</div>
            <p class="card-sub">Gestión de conductores</p>
        </div>

        <!-- Sugerencias -->
        <div class="card-big" onclick="window.location.href='/buzon-de-sugerencias'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="6" width="18" height="12" rx="2" stroke="#1e63b8" stroke-width="1.6" fill="#fff"/>
                    <path d="M3 8l9 6 9-6" stroke="#1976d2" stroke-width="1.4" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
            <div class="card-title">Sugerencias</div>
            <p class="card-sub">Buzón de usuarios</p>
        </div>

        <!-- Estadísticas -->
        <div class="card-big" onclick="window.location.href='/estadisticas'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="7" width="3" height="9" rx="0.6" fill="#eaf4ff" stroke="#1e63b8" stroke-width="1.2"/>
                    <rect x="10.5" y="4" width="3" height="12" rx="0.6" fill="#fff" stroke="#1976d2" stroke-width="1.2"/>
                    <rect x="17" y="10" width="3" height="6" rx="0.6" fill="#eaf4ff" stroke="#1e63b8" stroke-width="1.2"/>
                </svg>
            </div>
            <div class="card-title">Estadísticas</div>
            <p class="card-sub">Datos e informes</p>
        </div>

        <!-- Soporte -->
        <div class="card-big" onclick="window.location.href='/soporte'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 15v-1a7 7 0 0114 0v1" stroke="#1e63b8" stroke-width="1.6" stroke-linecap="round"/>
                    <circle cx="12" cy="6" r="3" stroke="#1976d2" stroke-width="1.6" fill="#fff"/>
                </svg>
            </div>
            <div class="card-title">Soporte</div>
            <p class="card-sub">Ayuda técnica</p>
        </div>

        <!-- Solicitudes -->
        <div class="card-big" onclick="window.location.href='/solicitudes-de-empleo'">
            <div class="card-illustration">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3.5" y="5.5" width="17" height="13" rx="1.2" stroke="#1e63b8" stroke-width="1.6" fill="#fff"/>
                    <path d="M7 9h10" stroke="#1976d2" stroke-width="1.4" stroke-linecap="round"/>
                    <path d="M7 13h6" stroke="#1e63b8" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="card-title">Solicitudes</div>
            <p class="card-sub">Aplicaciones laborales</p>
        </div>

    </div>
@endsection
