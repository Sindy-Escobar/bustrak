@extends('layouts.layoutadmin')

@section('title', 'Cambiar Contraseña Admin')

@section('content')
    <div class="container mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h2 style="margin:0; color:#1e63b8; font-weight:600; font-size:2rem;">
                    <i class="fas fa-key me-2"></i>Cambiar Contraseña
                </h2>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.update-password') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Contraseña Actual
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                   class="form-control" maxlength="64" required>
                        </div>

                        <div class="col-md-6"></div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                <i class="fas fa-key me-1"></i>Nueva Contraseña
                            </label>
                            <input type="password" name="password" id="password"
                                   class="form-control" minlength="8" maxlength="64" required>
                            <small class="text-muted">Entre 8 y 64 caracteres.</small>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-key me-1"></i>Confirmar Nueva Contraseña
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control" minlength="8" maxlength="64" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i>Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
