@extends('layouts.layoutadmin')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Alertas --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-bus me-2"></i>Gestión de Viajes</h4>
                    </div>

                    <div class="card-body">

                        {{-- Sección: Generar Viajes --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2"><i class="fas fa-plus-circle text-success me-2"></i>Generar Viajes</h5>
                            <p class="text-muted">Crea viajes para todas las rutas y servicios.</p>

                            <form action="{{ route('admin.viajes.generar') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="row g-3 align-items-end">

                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Días a generar</label>
                                        <select name="dias" class="form-select" style="height: 35px;" required>
                                            <option value="7">7 días (1 semana)</option>
                                            <option value="14">14 días (2 semanas)</option>
                                            <option value="21">21 días (3 semanas)</option>
                                            <option value="30">30 días (1 mes)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary w-100" style="height: 35px;">
                                            <i class="fas fa-magic me-2"></i>Generar Viajes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <hr>

                        {{-- Sección: Limpiar Viajes --}}
                        <div>
                            <h5 class="border-bottom pb-2"><i class="fas fa-trash text-danger me-2"></i>Limpiar Viajes Pasados</h5>
                            <p class="text-muted">Elimina viajes con fechas pasadas que no tienen reservas.</p>

                            <form action="{{ route('admin.viajes.limpiar') }}" method="POST"
                                  onsubmit="return confirm('¿Estás seguro de eliminar los viajes pasados sin reservas?')">
                                @csrf

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-broom me-2"></i>Limpiar Viajes Pasados
                                </button>
                            </form>
                        </div>

                        <hr>

                        {{-- Información --}}
                        <div class="alert alert-info">
                            <ul class="mb-0">
                                <li><strong>Generar Viajes:</strong> Crea viajes para TODAS las rutas con TODOS los servicios (Urbano, Express, Premium, etc.) en los horarios 6am, 12pm y 6pm.</li>
                                <li><strong>Limpiar:</strong> Elimina solo viajes pasados SIN reservas para mantener la base de datos limpia.</li>
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
