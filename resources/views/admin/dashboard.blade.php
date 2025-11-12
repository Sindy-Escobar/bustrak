@extends('layouts.layoutadmin')

@section('title', 'Panel de Administrador')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5">
                <h1 class="fw-bold" style="color:#1e63b8;">¡Bienvenido, Administrador!</h1>
                <p class="mt-3 fs-5 text-muted">¿Qué deseas hacer hoy?</p>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="{{ route('empleados.index') }}" class="btn btn-primary px-4">
                        <i class="fas fa-users me-2"></i> Gestionar Empleados
                    </a>
                    <a href="{{ route('viajes.index') }}" class="btn btn-success px-4">
                        <i class="fas fa-bus me-2"></i> Administrar Viajes
                    </a>
                    <a href="{{ route('logout') }}" class="btn btn-danger px-4"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </a>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection
