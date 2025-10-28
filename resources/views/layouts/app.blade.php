<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Bustrack')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }

        /* Navbar con degradado morado */
        .navbar {
            background: linear-gradient(90deg, #7c6fd0 0%, #8b7fd8 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            color: #333;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: linear-gradient(90deg, #7c6fd0 0%, #8b7fd8 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #6b5fbf 0%, #7a6ec7 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 111, 208, 0.4);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            border-radius: 8px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .form-control {
            background-color: #f8f9fa;
            color: #000;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .form-control:focus {
            border-color: #7c6fd0;
            box-shadow: 0 0 0 0.2rem rgba(124, 111, 208, 0.25);
        }

        .form-label {
            color: #333;
            font-weight: 500;
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
