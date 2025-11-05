<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bustrak - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-primary: #1e63b8;
            --color-secondary: #1976d2;
            --color-light: #f3f7fb;
            --color-white: #ffffff;
            --color-dark: #01579b;
            --color-success: #87cbeb;
            --color-warning: #87cbeb;
            --color-danger: #87cbeb;
        }

        body {
            min-height: 100vh;
            background: var(--color-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #2c3e50;
        }

        .top-bar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            padding: 0.75rem 2rem;
            box-shadow: 0 2px 15px rgba(30, 99, 184, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 100%;
        }

        .logo-image {
            height: 120px;
            width: 165px;
            object-fit: cover;
            border-radius:20%;
            border: 4px solid var(--color-white);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            background: var(--color-white);
        }

        .main-content {
            margin-top: 130px;
            padding: 2rem;
            min-height: calc(100vh - 130px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 99, 184, 0.4);
            background: linear-gradient(135deg, var(--color-dark) 0%, var(--color-primary) 100%);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            height: 45px;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--color-success);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            height: 45px;
        }

        .btn-warning {
            background: var(--color-warning);
            border: none;
            color: #000;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            height: 45px;
        }

        .btn-danger {
            background: var(--color-danger);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            height: 45px;
        }

        .btn-outline-secondary {
            color: var(--color-primary);
            border-color: var(--color-primary);
            height: 45px;
        }

        .btn-outline-secondary:hover {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }

        .btn-outline-danger {
            height: 45px;
        }

        .form-control,
        .form-select {
            height: 45px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(30, 99, 184, 0.25);
        }

        textarea.form-control {
            height: auto;
            min-height: 100px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .card {
            background: var(--color-white);
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(30, 99, 184, 0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
            border: none;
        }

        .card-body {
            padding: 2rem;
        }

        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table thead {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
        }

        .table thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
        }

        .table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(30, 99, 184, 0.05);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .pagination .page-link {
            color: var(--color-primary);
            border-color: #dee2e6;
        }

        .pagination .page-link:hover {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            border-color: transparent;
        }

        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            border-radius: 6px;
        }

        h1, h2, h3 {
            color: var(--color-primary);
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .top-bar {
                padding: 0.5rem 1rem;
            }

            .logo-image {
                height: 80px;
                width: 80px;
            }

            .main-content {
                margin-top: 100px;
                padding: 1rem;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
<div class="top-bar">
    <div class="logo-section">
        <img src="{{ asset('Imagenes/bustrak-logo.jpg') }}" alt="Bustrak Logo" class="logo-image">
        <span class="text-white fw-bold fs-6 ms-2">Sistema de Gestión</span>
    </div>
</div>

<div class="main-content">
    @yield('contenido')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
