<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Bustrack')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #001f3f;
            color: white;
        }

        .card {
            background-color: #00264d;
            border: none;
            color: white;
        }

        .btn-primary {
            background-color: #004080;
        }
        .btn-primary:hover {
            background-color: #0059b3;
        }

        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .form-control {
            background-color: #e9ecef;
            color: #000;
        }
        .form-control:focus {
            border-color: #004080;
            box-shadow: 0 0 0 0.2rem rgba(0,64,128,0.25);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Bustrack</a>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>
</body>
</html>

