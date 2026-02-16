@extends('layouts.layoutuser')

@section('contenido')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Asientos Disponibles para Viaje: {{ $viaje->origen->nombre }} a {{ $viaje->destino->nombre }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('cliente.reserva.store') }}" method="POST">
                @csrf
                <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">

                {{-- CAMPO OCULTO CON FECHA DE NACIMIENTO --}}
                <input type="hidden" name="fecha_nacimiento_pasajero" value="{{ session('fecha_nacimiento_pasajero') }}">

                <div class="mb-3">
                    <label>Seleccione Asiento</label>
                    <select name="asiento_id" class="form-select" required>
                        <option value="" disabled selected>Seleccione un asiento...</option>
                        @foreach($asientos as $asiento)
                            <option value="{{ $asiento->id }}">Asiento #{{ $asiento->numero_asiento }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- MOSTRAR ALERTA SI ES MENOR --}}
                @if(session('fecha_nacimiento_pasajero'))
                    @php
                        $edad = \Carbon\Carbon::parse(session('fecha_nacimiento_pasajero'))->age;
                    @endphp

                    @if($edad < 18)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Atención:</strong> El pasajero es menor de edad ({{ $edad }} años).
                            Después de confirmar la reserva, deberá completar la autorización del tutor.
                        </div>
                    @endif
                @endif

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-circle me-2"></i>Confirmar Reserva
                </button>
                <a href="{{ route('cliente.reserva.create') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </form>
        </div>
    </div>
@endsection
