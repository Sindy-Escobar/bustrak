@extends('layouts.apps')

@section('content')
    <div class="container-fluid py-4" style="min-height:100vh; background: #f3f7fb;">
        <div class="row">
            <div class="col-12 px-5">

                {{-- Header grande --}}
                <div style="background: white; border-radius: 16px; padding: 30px; margin-top: 20px; box-shadow: 0 4px 12px rgba(30, 99, 184, 0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 style="margin:0; color:#1e63b8; font-weight:600; font-size:2rem;">
                                <i class="fas fa-user-check me-2"></i>Validacion de Usuarios
                            </h2>
                        </div>

                        <div style="background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%); color:#fff; padding:12px 24px; border-radius:12px; font-weight:700; font-size:1.1rem; box-shadow: 0 4px 12px rgba(30, 99, 184, 0.3);">
                            Total: {{ $usuarios->count() }}
                        </div>
                    </div>
                </div>
                {{-- Franja buscador con filtros --}}
                <div style="margin-top:20px; background:#ffffff; padding:24px; border-radius:16px; box-shadow: 0 4px 12px rgba(30, 99, 184, 0.1);">
                    <div class="d-flex align-items-center">
                        <div style="flex:1;">
                            <div class="input-group" style="max-width:820px;">
                                <input id="searchInput" type="text" class="form-control" placeholder="Buscar por Nombre Completo, DNI o Email..." style="background:#f3f7fb; border:2px solid #e5e7eb; padding:14px 20px; border-radius:12px; font-size:1rem; height: 45px;">
                            </div>
                        </div>

                        <div style="margin-left:20px;">
                            <div class="btn-group" role="group" aria-label="Filtros">
                                <a href="{{ route('usuarios.index') }}"
                                   class="btn"
                                   style="background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%);  color:white; font-weight:600; border-radius:8px; padding:0 24px; font-size:1rem;
                                   height: 45px;border: none; margin-right: 10px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-arrow-left me-2"></i>Volver
                                </a>
                                <button class="btn filtro active" data-filter="todos" style="background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%); color:white; font-weight:600; border-radius:8px; padding:0 24px; font-size:1rem; height: 45px; border: none;">Todos</button>
                                <button class="btn filtro" data-filter="activo" style="background: linear-gradient(135deg, #28a745 0%, #28a745 100%); color:white; font-weight:600; border-radius:8px; padding:0 24px; margin-left:10px; font-size:1rem; height: 45px; border: none;">Activos</button>
                                <button class="btn filtro" data-filter="inactivo" style="background:#ffc107; color:white; font-weight:600; border-radius:8px; padding:0 24px; margin-left:10px; font-size:1rem; height: 45px; border: none;">Inactivos</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabla --}}
                <div style="margin-top:20px; background:white; border-radius:16px; padding:0; box-shadow: 0 4px 12px rgba(30, 99, 184, 0.1); overflow:hidden;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                            <thead style="background: linear-gradient(135deg, #1e63b8 0%, #1976d2 100%); color: white;">
                            <tr>
                                <th style="padding:18px; border:none; width:70px; font-weight:700; font-size:1rem;">#</th>
                                <th style="padding:18px; border:none; font-weight:700; font-size:1rem;">Nombre</th>
                                <th style="padding:18px; border:none; font-weight:700; font-size:1rem;">Correo Electronico</th>
                                <th style="padding:18px; border:none; width:120px; font-weight:700; font-size:1rem;">Estado</th>
                                <th style="padding:18px; border:none; font-weight:700; font-size:1rem;">Fecha Registro</th>
                                <th style="padding:18px; border:none; width:160px; font-weight:700; font-size:1rem;" class="text-center">Acciones</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if($usuarios->count() > 0)
                                @foreach($usuarios as $index => $usuario)
                                    @php $estado = $usuario->estado ?? 'activo'; @endphp
                                    <tr data-estado="{{ $estado }}" data-nombre="{{ strtolower($usuario->nombre_completo ?? $usuario->name) }}" data-email="{{ strtolower($usuario->email) }}" style="border-bottom:1px solid #f3f7fb;">
                                        <td style="padding:18px;">
                                            <strong style="color:#1e63b8; font-size:1rem;">#{{ $index + 1 }}</strong>
                                        </td>

                                        <td style="padding:18px; font-weight:700; color:#2c3e50; font-size:1rem;">
                                            {{ $usuario->nombre_completo ?? $usuario->name }}
                                        </td>

                                        <td style="padding:18px; color:#6c757d; font-size:1rem;">{{ $usuario->email }}</td>

                                        <td style="padding:18px;">
                                            @if($estado === 'activo')
                                                <span style="background:#28a745; color:white; padding:8px 16px; border-radius:999px; font-weight:700; font-size:0.9rem;">Activo</span>
                                            @else
                                                <span style="background:#dc3545; color:white; padding:8px 16px; border-radius:999px; font-weight:700; font-size:0.9rem;">Inactivo</span>
                                            @endif
                                        </td>

                                        <td style="padding:18px; color:#6c757d; font-size:1rem;">
                                            {{ optional($usuario->created_at)->format('d/m/Y') }}
                                        </td>

                                        <td style="padding:18px;" class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="{{ route('admin.cambiarEstado', $usuario->id) }}" method="POST" onsubmit="return confirmarCambio('{{ addslashes($usuario->name) }}', '{{ $estado }}');">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($estado === 'activo')
                                                        <button type="submit" class="btn" style="background:#ffc107; color:#000; padding:0 24px; border-radius:8px; font-weight:600; font-size:1rem; border: none; height: 45px;">
                                                            Inactivar
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); color:white; padding:0 24px; border-radius:8px; font-weight:600; font-size:1rem; border: none; height: 45px;">
                                                            Activar
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
                                        <div style="padding:40px; color:#6c757d;">
                                            <i class="fas fa-inbox" style="font-size:3rem; color:#dee2e6;"></i>
                                            <p style="margin-top:12px; font-size: 1.1rem;">No hay usuarios registrados</p>
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

    <style>
        .table tbody tr:hover {
            background-color: rgba(30, 99, 184, 0.05);
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
            box-shadow: 0 4px 12px rgba(30, 99, 184, 0.3);
        }

        .filtro.active {
            box-shadow: 0 4px 12px rgba(30, 99, 184, 0.4);
        }

        .filtro:not(.active) {
            opacity: 0.8;
        }

        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 99, 184, 0.15);
            border-color: #1e63b8;
        }
    </style>

    <script>
        // Confirmacion para cambiar estado con colores personalizados
        function confirmarCambio(nombre, estadoActual) {
            const accion = estadoActual === 'activo' ? 'INACTIVAR' : 'ACTIVAR';
            const advertencia = estadoActual === 'activo'
                ? 'El usuario NO podra acceder al sistema.'
                : 'El usuario podra acceder nuevamente al sistema.';

            // Crear un modal personalizado
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            `;

            const boxColor = estadoActual === 'activo' ? '#dc3545' : '#4fc3f7';
            const confirmText = estadoActual === 'activo' ? 'Si, Inactivar' : 'Si, Activar';

            modal.innerHTML = `
                <div style="background: white; border-radius: 16px; padding: 30px; max-width: 500px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div style="background: ${boxColor}; width: 80px; height: 80px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                            <i class="fas ${estadoActual === 'activo' ? 'fa-exclamation-triangle' : 'fa-check-circle'}" style="font-size: 40px; color: white;"></i>
                        </div>
                        <h3 style="color: #2c3e50; font-weight: 700; margin-bottom: 10px;">¿Estas seguro de ${accion}?</h3>
                        <p style="color: #6c757d; font-size: 1.1rem; margin-bottom: 5px;"><strong>${nombre}</strong></p>
                        <p style="color: #6c757d;">${advertencia}</p>
                    </div>
                    <div style="display: flex; gap: 10px; margin-top: 25px;">
                        <button id="btnCancelar" style="flex: 1; background: #dc3545; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; height: 45px;">
                            No, Cancelar
                        </button>
                        <button id="btnConfirmar" style="flex: 1; background: ${boxColor}; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; height: 45px;">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            return new Promise((resolve) => {
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
            document.querySelectorAll('form[onsubmit*="confirmarCambio"]').forEach(form => {
                form.onsubmit = async function(e) {
                    e.preventDefault();
                    const nombre = this.querySelector('button').closest('tr').querySelector('td:nth-child(2)').textContent.trim();
                    const estado = this.querySelector('button').textContent.includes('Inactivar') ? 'activo' : 'inactivo';
                    const confirmado = await confirmarCambio(nombre, estado);
                    if (confirmado) {
                        this.submit();
                    }
                    return false;
                };
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
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
                        const dni = (row.cells[3] && row.cells[3].innerText) ? row.cells[3].innerText.toLowerCase() : '';
                        if (!term || nombre.includes(term) || email.includes(term) || dni.includes(term)) {
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
@endsection
