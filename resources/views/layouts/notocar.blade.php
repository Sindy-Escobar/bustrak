<!DOCTYPE html>
<html lang="es">

<meta charset="UTF-8">
<title>@yield('title', 'BusTrak')</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://kit.fontawesome.com/a2e0c8b2b1.js" crossorigin="anonymous"></script>

<style>
    :root{
        --blue-600: #1e63b8;
        --blue-500: #1976d2;
        --bg: #f3f7fb;
        --card-bg: #ffffff;
        --muted: #6b7280;
    }

    html,body{
        height:100%;
        margin:0;
        font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background: var(--bg);
        color: #111827;
    }

    /* TOPBAR */
    .topbar {
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(15,23,42,0.06);
        padding: 14px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .brand-logo img {
        width: 140px; /* logo más largo */
        height: 70px;
        border-radius: 12px;
        object-fit: cover;
    }

    .profile-box{
        display:flex;
        align-items:center;
        gap:12px;
        background:#ffffff;
        padding:6px 10px;
        border-radius:12px;
        box-shadow: 0 2px 8px rgba(15,23,42,0.06);
    }

    .profile-name{
        font-weight:600;
        color:#0f172a;
        font-size:0.95rem;
    }

    .avatar-wrap{
        position:relative;
        width:48px;
        height:48px;
        border-radius:12px;
        background: linear-gradient(180deg,#ffffff,#f1f5f9);
        display:flex;
        align-items:center;
        justify-content:center;
        box-shadow: 0 2px 6px rgba(2,6,23,0.06);
        border: 2px solid rgba(29,78,216,0.12);
        overflow:hidden;
    }

    .avatar-wrap img{
        width:44px;
        height:44px;
        border-radius:8px;
        object-fit:cover;
    }

    .hero {
        position: sticky;
        top: 50px;
        background: linear-gradient(180deg, var(--blue-600), var(--blue-500));
        padding: 36px 28px;
        border-bottom-left-radius: 14px;
        border-bottom-right-radius: 14px;
        margin: 16px 28px 0 28px;
        color: #fff;
        box-shadow: 0 6px 18px rgba(29,78,216,0.08);
        z-index: 900;
    }

    main.container-main{
        max-width:1200px;
        margin: 0 auto 60px;
        padding: 28px;
        padding-top: 50px;
    }

    .cards-grid{
        display:grid;
        grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
        gap:20px;
    }

    .card-big {
        background: var(--card-bg);
        border-radius:12px;
        padding:22px;
        text-align:center;
        box-shadow: 0 8px 20px rgba(2,6,23,0.06);
        transition: transform .22s ease, box-shadow .22s ease;
        cursor:pointer;
    }
    .card-big:hover{
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(2,6,23,0.09);
        background: linear-gradient(180deg,#ffffff,#f8fbff);
    }

    .card-illustration{
        width:72px;
        height:72px;
        margin:0 auto 12px auto;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:14px;
        background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(240,248,255,0.9));
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
    }

    .card-title{
        font-weight:700;
        font-size:1rem;
        color:#0f172a;
        margin:6px 0 4px 0;
    }
    .card-sub {
        font-size:0.85rem;
        color:var(--muted);
        margin:0;
    }

    footer.site-footer{
        text-align:center;
        color:#94a3b8;
        font-size:0.85rem;
        margin-top:30px;
        padding-bottom:40px;
    }

    @media (max-width:576px){
        .hero{ padding:20px; margin:12px;}
        main.container-main{ margin-top:-40px; padding: 0 16px;}
    }
</style>

@yield('styles')
<header class="topbar">
    <div class="brand">
        <div class="brand-logo">
            <img src="{{ asset('imagenes/bustrak-logo.jpg') }}" alt="Logo">
        </div>
    </div>

    <div class="topbar-title text-center flex-grow-1">
        <h1 class="hero-title" style="font-size:1.4rem;font-weight:700;color:#000;margin:0;">
            Panel de administración
        </h1>
    </div>

    @auth
        @php
            $empleado = \App\Models\Empleado::where('email', Auth::user()->email)->first();
            $foto = $empleado && $empleado->foto ? asset('storage/' . $empleado->foto) : 'https://via.placeholder.com/80';
        @endphp
        <div class="profile-box d-flex align-items-center gap-3">
            <div class="d-flex flex-column text-center">
                <span class="profile-name fw-semibold" style="color:#1e293b;">{{ Auth::user()->name }}</span>
                <small style="color:#1e293b; font-size:0.8rem; font-weight:500;">Administrador</small>

            </div>
            <div class="avatar-wrap position-relative">
                <img src="{{ $foto }}" alt="avatar">
                <span class="position-absolute bottom-0 end-0 translate-middle p-1 bg-success border border-light rounded-circle"></span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm" style="border-radius:8px;font-weight:500;padding:5px 12px;transition:all 0.3s ease;">
                    <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
                </button>
            </form>
        </div>
    @endauth
</header>

<section class="hero"></section>

<main class="container-main">
    @yield('content')
</main>

<footer class="site-footer">
    © {{ date('Y') }} BusTrak. Todos los derechos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')

</body>
</html>
