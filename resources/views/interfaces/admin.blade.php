@extends('layouts.layoutadmin')

@section('title', 'Dashboard Administrador')

@section('content')
    <!-- Grid de tarjetas -->


    <!-- Estadísticas de Empleados y Usuarios -->
    <div class="report-container"
         style="display:flex; justify-content:center; gap:40px; margin-top:40px; width:100%; flex-wrap:wrap;">

        <!-- Columna Empleados -->
        <div class="column-empleados"
             style="flex:1 1 300px; max-width:300px; display:flex; flex-direction:column; align-items:center; gap:20px;">
            <h1 class="hero-title" style="font-size:1.4rem;font-weight:700;color:#000;margin:0;">Empleados</h1>

            <div class="stat-box"
                 style="width:100%; background:#f8f9fa; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                <h2 style="margin:0; font-size:32px; color:#1e63b8;">{{ $total_activos }}</h2>
                <p style="margin:5px 0 0; color:#555; font-weight:500;">Activos</p>
            </div>

            <div class="stat-box"
                 style="width:100%; background:#f8f9fa; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                <h2 style="margin:0; font-size:32px; color:#ff7f50;">{{ $total_inactivos }}</h2>
                <p style="margin:5px 0 0; color:#555; font-weight:500;">Inactivos</p>
            </div>

            <div class="stat-box"
                 style="width:100%; background:#f8f9fa; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                <h2 style="margin:0; font-size:32px; color:#2ecc71;">{{ $total_empleados }}</h2>
                <p style="margin:5px 0 0; color:#555; font-weight:500;">Total</p>
            </div>
        </div>

        <!-- Columna Usuarios -->
        <div class="column-usuarios"
             style="flex:1 1 300px; max-width:300px; display:flex; flex-direction:column; align-items:center; gap:20px;">
            <h1 class="hero-title" style="font-size:1.4rem;font-weight:700;color:#000;margin:0;">Usuarios</h1>

            <div class="stat-box"
                 style="width:100%; background:#f8f9fa; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                <h2 style="margin:0; font-size:32px; color:#1e63b8;">{{ $usuariosActivos }}</h2>
                <p style="margin:5px 0 0; color:#555; font-weight:500;">Activos</p>
            </div>

            <div class="stat-box"
                 style="width:100%; background:#f8f9fa; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                <h2 style="margin:0; font-size:32px; color:#ff7f50;">{{ $usuariosInactivos }}</h2>
                <p style="margin:5px 0 0; color:#555; font-weight:500;">Inactivos</p>
            </div>

            <div class="stat-box"
                 style="width:100%; background:#f8f9fa; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                <h2 style="margin:0; font-size:32px; color:#2ecc71;">{{ $totalUsuarios }}</h2>
                <p style="margin:5px 0 0; color:#555; font-weight:500;">Total</p>
            </div>
        </div>

    </div> <!-- FIN report-container -->

@endsection
