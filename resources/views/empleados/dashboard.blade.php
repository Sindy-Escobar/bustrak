@extends('layouts.layoutempleado')

@section('title', 'Panel del Empleado')

@section('content')
    <div class="container">
        <h1>Bienvenido, {{ Auth::user()->nombre_completo ?? 'Empleado' }}</h1>
        <p>Este es tu panel de control.</p>
    </div>
@endsection
