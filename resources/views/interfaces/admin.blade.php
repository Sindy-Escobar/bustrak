@extends('layouts.layoutadmin')

@section('title', 'Bienvenido Administrador')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <h1 class="mb-3" style="color: #1e63b8; font-weight: 700;">
                    Bienvenido Administrador del Sistema
                </h1>
                <p class="lead mb-4" style="color: #555;">
                    ¿Qué deseas hacer hoy?
                </p>

                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('usuarios.consultar') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-users me-2"></i>Gestionar Usuarios
                    </a>
                    <a href="{{ route('admin.estadisticas') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-chart-line me-2"></i> Ver Estadísticas
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection
