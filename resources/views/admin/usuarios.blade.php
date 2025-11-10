<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Validación de Usuarios</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .table tbody tr:hover {
            background-color: rgba(25, 118, 210, 0.05);
        }

        .btn {
            border: none;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .filtro {
            border: none;
            color:#fff;
            transition: all 0.3s ease;
        }

        .filtro:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
        }

        .filtro.active {
            box-shadow: 0 4px 12px rgba(25, 118, 210, 0.4);
        }

        .filtro:not(.active) {
            opacity: 0.8;
        }

        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.15);
            border-color: #1976d2;
        }
    </style>
</head>
<body>
<div class="container-fluid py-4" style="min-height:100vh;">
    <div class="row">
        <div class="col-12 px-5">

            {{-- Header grande --}}
            <div style="background: white; border-radius: 20px; padding: 30px; margin-top: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 style="margin:0; color:#1976d2; font-weight:700; font-size:2rem;">
                            <i class="fas fa-user-check me-2"></i>Validación de Usuarios
                        </h2>
                    </div>

                    <div style="background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%); color:#fff; padding:15px 30px; border-radius:15px; font-weight:700; font-size:1.2rem; box-shadow: 0 6px 20px rgba(25, 118, 210, 0.4);">
                        <i class="fas fa-users me-2"></i>Total: {{ $usuarios->count() }}
                    </div>
                </div>
            </div>

            {{-- Franja buscador con filtros --}}
            <div style="margin-top:25px; background:#ffffff; padding:30px; border-radius:20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <div style="flex:1; min-width: 300px;">
                        <div class="input-group">
                            <span class="input-group-text" style="background:#1976d2; border:none; border-radius:12px 0 0 12px;">
                                <i class="fas fa-search" style="color:white;"></i>
                            </span>
                            <input id="searchInput" type="text" class="form-control" placeholder="Buscar por Nombre Completo, DNI o Email..." style="background:#f8f9fa; border:2px solid #e9ecef; padding:14px 20px; border-radius:0 12px 12px 0; font-size:1rem; height: 50px; border-left: none;">
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('usuarios.index') }}"
                           class="btn"
                           style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); color:white; font-weight:600; border-radius:12px; padding:0 24px; font-size:1rem; height: 50px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                        <button class="btn filtro active" data-filter="todos" style="background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%); color:white; font-weight:600; border-radius:12px; padding:0 30px; font-size:1rem; height: 50px;">
                            <i class="fas fa-list me-2"></i>Todos
                        </button>
                        <button class="btn filtro" data-filter="activo" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color:white; font-weight:600; border-radius:12px; padding:0 30px; font-size:1rem; height: 50px;">
                            <i class="fas fa-check-circle me-2"></i>Activos
                        </button>
                        <button class="btn filtro" data-filter="inactivo" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color:white; font-weight:600; border-radius:12px; padding:0 30px; font-size:1rem; height: 50px;">
                            <i class="fas fa-times-circle me-2"></i>Inactivos
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div style="margin-top:25px; background:white; border-radius:20px; padding:0; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); overflow:hidden;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                        <thead style="background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%); color: white;">
                        <tr>
                            <th style="padding:20px; border:none; width:80px; font-weight:700; font-size:1rem;">#</th>
                            <th style="padding:20px; border:none; font-weight:700; font-size:1rem;">Nombre</th>
                            <th style="padding:20px; border:none; font-weight:700; font-size:1rem;">Correo Electrónico</th>
                            <th style="padding:20px; border:none; width:140px; font-weight:700; font-size:1rem;">Estado</th>
                            <th style="padding:20px; border:none; font-weight:700; font-size:1rem;">Fecha Registro</th>
                            <th style="padding:20px; border:none; width:180px; font-weight:700; font-size:1rem;" class="text-center">Acciones</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if($usuarios->count() > 0)
                            @foreach($usuarios as $index => $usuario)
                                @php $estado = $usuario->estado ?? 'activo'; @endphp
                                <tr data-estado="{{ $estado }}" data-nombre="{{ strtolower($usuario->nombre_completo ?? $usuario->name) }}" data-email="{{ strtolower($usuario->email) }}" style="border-bottom:1px solid #f1f3f5;">
                                    <td style="padding:20px;">
                                        <strong style="color:#1976d2; font-size:1.1rem; font-weight:700;">#{{ $index + 1 }}</strong>
                                    </td>

                                    <td style="padding:20px; font-weight:600; color:#2c3e50; font-size:1rem;">
                                        <i class="fas fa-user me-2" style="color:#1976d2;"></i>{{ $usuario->nombre_completo ?? $usuario->name }}
                                    </td>

                                    <td style="padding:20px; color:#6c757d; font-size:1rem;">
                                        <i class="fas fa-envelope me-2" style="color:#1976d2;"></i>{{ $usuario->email }}
                                    </td>

                                    <td style="padding:20px;">
                                        @if($estado === 'activo')
                                            <span style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color:white; padding:10px 20px; border-radius:25px; font-weight:700; font-size:0.9rem; display:inline-flex; align-items:center; gap:8px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);">
                                                <i class="fas fa-check-circle"></i>Activo
                                            </span>
                                        @else
                                            <span style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color:white; padding:10px 20px; border-radius:25px; font-weight:700; font-size:0.9rem; display:inline-flex; align-items:center; gap:8px; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);">
                                                <i class="fas fa-times-circle"></i>Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    <td style="padding:20px; color:#6c757d; font-size:1rem;">
                                        <i class="fas fa-calendar-alt me-2" style="color:#1976d2;"></i>{{ optional($usuario->created_at)->format('d/m/Y') }}
                                    </td>

                                    <td style="padding:20px;" class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('admin.cambiarEstado', $usuario->id) }}" method="POST" class="form-cambiar-estado">
                                                @csrf
                                                @method('PATCH')
                                                @if($estado === 'activo')
                                                    <button type="submit" class="btn" data-nombre="{{ $usuario->name }}" data-estado="activo" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color:#000; padding:0 24px; border-radius:12px; font-weight:700; font-size:1rem; border: none; height: 48px; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);">
                                                        <i class="fas fa-ban me-2"></i>Inactivar
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn" data-nombre="{{ $usuario->name }}" data-estado="inactivo" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color:white; padding:0 24px; border-radius:12px; font-weight:700; font-size:1rem; border: none; height: 48px; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);">
                                                        <i class="fas fa-check-circle me-2"></i>Activar
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div style="padding:60px 20px; color:#6c757d;">
                                        <i class="fas fa-inbox" style="font-size:4rem; color:#dee2e6;"></i>
                                        <p style="margin-top:20px; font-size: 1.3rem; font-weight:600;">No hay usuarios registrados</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Confirmación para cambiar estado con diseño mejorado
    function confirmarCambio(nombre, estadoActual) {
        return new Promise((resolve) => {
            const accion = estadoActual === 'activo' ? 'INACTIVAR' : 'ACTIVAR';
            const advertencia = estadoActual === 'activo'
                ? 'El usuario NO podrá acceder al sistema.'
                : 'El usuario podrá acceder nuevamente al sistema.';

            // Crear un modal personalizado mejorado
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.7);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                backdrop-filter: blur(5px);
                animation: fadeIn 0.3s ease;
            `;

            const isActivar = estadoActual === 'inactivo';
            const iconColor = isActivar ? '#28a745' : '#ffc107';
            const iconClass = isActivar ? 'fa-check-circle' : 'fa-exclamation-triangle';
            const confirmBg = isActivar ? 'linear-gradient(135deg, #28a745 0%, #20c997 100%)' : 'linear-gradient(135deg, #ffc107 0%, #ff9800 100%)';
            const confirmText = isActivar ? 'Sí, Activar' : 'Sí, Inactivar';

            modal.innerHTML = `
                <div style="background: white; border-radius: 25px; padding: 40px; max-width: 520px; width: 90%; box-shadow: 0 20px 60px rgba(0,0,0,0.4); animation: slideUp 0.3s ease;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="background: ${iconColor}; width: 90px; height: 90px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 25px; box-shadow: 0 8px 25px ${iconColor}40;">
                            <i class="fas ${iconClass}" style="font-size: 45px; color: white;"></i>
                        </div>
                        <h3 style="color: #2c3e50; font-weight: 700; margin-bottom: 15px; font-size: 1.8rem;">¿Estás seguro de ${accion}?</h3>
                        <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 20px; border-radius: 15px; margin: 20px 0;">
                            <p style="color: #1976d2; font-size: 1.2rem; margin-bottom: 8px; font-weight: 700;">
                                <i class="fas fa-user me-2"></i>${nombre}
                            </p>
                            <p style="color: #6c757d; margin: 0; font-size: 1rem;">${advertencia}</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <button id="btnCancelar" style="flex: 1; background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); color: white; border: none; padding: 16px; border-radius: 15px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);">
                            <i class="fas fa-times me-2"></i>No, Cancelar
                        </button>
                        <button id="btnConfirmar" style="flex: 1; background: ${confirmBg}; color: white; border: none; padding: 16px; border-radius: 15px; font-weight: 700; font-size: 1.1rem; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px ${iconColor}40;">
                            <i class="fas ${isActivar ? 'fa-check' : 'fa-ban'} me-2"></i>${confirmText}
                        </button>
                    </div>
                </div>
                <style>
                    @keyframes fadeIn {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                    @keyframes slideUp {
                        from { opacity: 0; transform: translateY(30px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    #btnCancelar:hover, #btnConfirmar:hover {
                        transform: translateY(-3px);
                        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
                    }
                </style>
            `;

            document.body.appendChild(modal);

            document.getElementById('btnConfirmar').onclick = () => {
                document.body.removeChild(modal);
                resolve(true);
            };
            document.getElementById('btnCancelar').onclick = () => {
                document.body.removeChild(modal);
                resolve(false);
            };
            modal.onclick = (e) => {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                    resolve(false);
                }
            };
        });
    }

    // Interceptar el submit del formulario
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.form-cambiar-estado').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const button = this.querySelector('button[type="submit"]');
                const nombre = button.getAttribute('data-nombre');
                const estado = button.getAttribute('data-estado');
                const confirmado = await confirmarCambio(nombre, estado);
                if (confirmado) {
                    this.submit();
                }
            });
        });

        // Buscador
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const term = this.value.trim().toLowerCase();
                const rows = document.querySelectorAll('#tablaUsuarios tbody tr');
                rows.forEach(row => {
                    if (row.querySelector('.fa-inbox')) return;
                    const nombre = row.getAttribute('data-nombre') || '';
                    const email = row.getAttribute('data-email') || '';
                    if (!term || nombre.includes(term) || email.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Filtros
        const filtros = document.querySelectorAll('.filtro');
        filtros.forEach(btn => {
            btn.addEventListener('click', function () {
                filtros.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const filtro = this.getAttribute('data-filter');
                const rows = document.querySelectorAll('#tablaUsuarios tbody tr');
                rows.forEach(row => {
                    if (row.querySelector('.fa-inbox')) return;
                    const estado = row.getAttribute('data-estado') || 'activo';
                    if (filtro === 'todos' || estado === filtro) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
</body>
</html>
