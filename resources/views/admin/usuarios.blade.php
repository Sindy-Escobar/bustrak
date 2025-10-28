@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4" style="min-height:100vh; background: linear-gradient(135deg, #7c6fd0 0%, #8b7fd8 100%);">
        <div class="row">
            <div class="col-12 px-5">

                {{-- Header grande blanco sobre el gradiente --}}
                <div style="background: white; border-radius: 16px; padding: 30px; margin-top: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 style="margin:0; color:#8b7fd8; font-weight:700; font-size:2rem;">
                                Validación de Usuarios
                            </h2>
                        </div>

                        <div style="background:#8b7fd8; color:#fff; padding:12px 24px; border-radius:12px; font-weight:700; font-size:1.1rem;">
                            Total: {{ $usuarios->count() }}
                        </div>
                    </div>
                </div>

                {{-- Franja buscador blanca con buscador y filtros a la derecha --}}
                <div style="margin-top:20px; background:#ffffff; padding:24px; border-radius:16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="d-flex align-items-center">
                        <div style="flex:1;">
                            <div class="input-group" style="max-width:820px;">
                                <input id="searchInput" type="text" class="form-control" placeholder="Buscar por Nombre Completo, DNI o Email..." style="background:#f3f4f6; border:2px solid #e5e7eb; padding:14px 20px; border-radius:12px; font-size:1rem;">
                            </div>
                        </div>

                        <div style="margin-left:20px;">
                            <div class="btn-group" role="group" aria-label="Filtros">
                                <button class="btn filtro active" data-filter="todos" style="background:#8b7fd8; color:white; font-weight:700; border-radius:12px; padding:12px 24px; font-size:1rem;">Todos</button>
                                <button class="btn filtro" data-filter="activo" style="background:#10b981; color:white; font-weight:700; border-radius:12px; padding:12px 24px; margin-left:10px; font-size:1rem;">Activos</button>
                                <button class="btn filtro" data-filter="inactivo" style="background:#ef4444; color:white; font-weight:700; border-radius:12px; padding:12px 24px; margin-left:10px; font-size:1rem;">Inactivos</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabla con estilo actualizado --}}
                <div style="margin-top:20px; background:white; border-radius:16px; padding:0; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow:hidden;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
                            <thead style="background:#f9fafb; color:#111827; border-bottom:2px solid #e5e7eb;">
                            <tr>
                                <th style="padding:18px; border:none; width:70px; font-weight:700; font-size:1rem;">#</th>
                                <th style="padding:18px; border:none; font-weight:700; font-size:1rem;">Nombre</th>
                                <th style="padding:18px; border:none; font-weight:700; font-size:1rem;">Correo electrónico</th>
                                <th style="padding:18px; border:none; width:120px; font-weight:700; font-size:1rem;">Estado</th>
                                <th style="padding:18px; border:none; width:140px; font-weight:700; font-size:1rem;">Fecha Registro</th>
                                <th style="padding:18px; border:none; width:160px; font-weight:700; font-size:1rem;" class="text-center">Acciones</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if($usuarios->count() > 0)
                                @foreach($usuarios->sortByDesc('id') as $usuario)
                                    @php $estado = $usuario->estado ?? 'activo'; @endphp
                                    <tr data-estado="{{ $estado }}" data-nombre="{{ strtolower($usuario->name) }}" data-email="{{ strtolower($usuario->email) }}" style="border-bottom:1px solid #f3f4f6;">
                                        <td style="padding:18px;">
                                            <strong style="color:#8b7fd8; font-size:1rem;">#{{ $loop->iteration }}</strong>
                                        </td>

                                        <td style="padding:18px; font-weight:700; color:#111827; font-size:1rem;">
                                            {{ $usuario->name }}
                                        </td>

                                        <td style="padding:18px; color:#6b7280; font-size:1rem;">{{ $usuario->email }}</td>

                                        <td style="padding:18px;">
                                            @if($estado === 'activo')
                                                <span style="background:#10b981; color:white; padding:8px 16px; border-radius:999px; font-weight:700; font-size:0.9rem;">Activo</span>
                                            @else
                                                <span style="background:#f97316; color:white; padding:8px 16px; border-radius:999px; font-weight:700; font-size:0.9rem;">Inactivo</span>
                                            @endif
                                        </td>

                                        <td style="padding:18px; color:#6b7280; font-size:1rem;">
                                            {{ optional($usuario->created_at)->format('d/m/Y') }}
                                        </td>

                                        <td style="padding:18px;" class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                {{-- SOLO el botón toggle (Inactivar / Activar) --}}
                                                <form action="{{ route('admin.cambiarEstado', $usuario->id) }}" method="POST" onsubmit="return confirmarCambio('{{ addslashes($usuario->name) }}', '{{ $estado }}');">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($estado === 'activo')
                                                        <button type="submit" class="btn" style="background:#f59e0b; color:white; padding:10px 20px; border-radius:12px; font-weight:700; font-size:1rem;">
                                                            Inactivar
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn" style="background:#10b981; color:white; padding:10px 20px; border-radius:12px; font-weight:700; font-size:1rem;">
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
                                        <div style="padding:40px; color:#9ca3af;">
                                            <i class="fas fa-inbox" style="font-size:3rem; color:#d1d5db;"></i>
                                            <p style="margin-top:12px;">No hay usuarios registrados</p>
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
        .table tbody tr:hover { background-color: rgba(139,127,216,0.05); }
        .btn { border: none; }
        .filtro {
            border: none;
            color:#fff;
            transition: all 0.3s ease;
        }
        .filtro:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .filtro.active {
            box-shadow: 0 4px 12px rgba(139,127,216,0.3);
        }
        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(139,127,216,0.1);
            border-color: #8b7fd8;
        }
    </style>

    <script>
        // Confirm dialog for toggle estado
        function confirmarCambio(nombre, estadoActual) {
            const accion = estadoActual === 'activo' ? 'INACTIVAR' : 'ACTIVAR';
            const advertencia = estadoActual === 'activo'
                ? 'El usuario NO podrá acceder al sistema.'
                : 'El usuario podrá acceder nuevamente al sistema.';
            return confirm(`¿Estás seguro de ${accion} a "${nombre}"?\n\n${advertencia}`);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Buscador: filtra por nombre / email / dni (si tienes dni en la tabla)
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const term = this.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('#tablaUsuarios tbody tr');
                    rows.forEach(row => {
                        // si fila es "no hay usuarios", la ignoramos
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

            // Filtros (Todos / Activo / Inactivo)
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
