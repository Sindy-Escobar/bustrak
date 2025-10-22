@extends('layouts.app')

@section('title', 'Registrar Empresa de Buses')

@section('content')
    <div class="card p-4 mx-auto" style="max-width: 600px; background-color: #00264d;">
        <h2 class="mb-4 text-center text-white">Registrar Empresa de Buses</h2>

        {{-- Mensaje de éxito --}}
        @if(session('success'))
            <div class="alert alert-success text-dark">{{ session('success') }}</div>
        @endif

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="alert alert-danger text-dark">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Aquí cambiamos la ruta del formulario --}}
        <form action="{{ route('empresa.form') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label text-white">Nombre de la empresa *</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Propietario *</label>
                <input type="text" name="propietario" class="form-control" value="{{ old('propietario') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Teléfono *</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Correo Electrónico </label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label class="form-label text-white">Dirección *</label>
                <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}"required>
            </div>

            <div class="d-flex justify-content-between">
                {{-- "Volver" ahora lleva también a la misma ruta /empresa --}}
                <button type="submit" class="btn btn-primary">Guardar Empresa</button>
            </div>
        </form>
    </div>
@endsection
