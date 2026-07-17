@extends('layouts.layoutuser')

@section('title', 'Mis Solicitudes de Empleo')

@section('contenido')
    <div class="d-flex justify-content-center">
        <div style="width: 100%; max-width: 1200px;">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-briefcase me-2"></i>
                        Mis Solicitudes de Empleo
                    </h4>
                    <a href="{{ route('solicitud.empleo.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i> Nueva Solicitud
                    </a>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($solicitudes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Puesto Deseado</th>
                                    <th>Contacto</th>
                                    <th>Estado</th>
                                    <th>Fecha Envío</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($solicitudes as $key => $solicitud)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $solicitud->nombre_completo }}</td>
                                        <td>{{ $solicitud->puesto_deseado }}</td>
                                        <td>{{ $solicitud->contacto }}</td>
                                        <td>
                                            @switch($solicitud->estado)
                                                @case('Pendiente')
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                                    @break
                                                @case('Revisada')
                                                    <span class="badge bg-info">Revisada</span>
                                                    @break
                                                @case('Aceptada')
                                                    <span class="badge bg-success">Aceptada</span>
                                                    @break
                                                @case('Rechazada')
                                                    <span class="badge bg-danger">Rechazada</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($solicitud->cv)
                                                <a href="{{ route('solicitud.empleo.cv', $solicitud->id) }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-download"></i> Ver CV
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                            <form action="{{ route('solicitud.empleo.eliminar', $solicitud->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirmarAccionModal(this, '¿Eliminar esta solicitud?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-3">No has enviado solicitudes de empleo aún.</p>
                            <a href="{{ route('solicitud.empleo.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Enviar Primera Solicitud
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal genérico de confirmación (reemplaza confirm() nativo del navegador) --}}
    <div class="modal fade" id="modalConfirmacionGenerica" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar acción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="modalConfirmacionMensaje" class="mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="modalConfirmacionBtnAceptar">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let _confirmacionCallback = null;

        function confirmarAccionModal(elemento, mensaje) {
            document.getElementById('modalConfirmacionMensaje').textContent = mensaje;
            const modalEl = document.getElementById('modalConfirmacionGenerica');
            const modal = new bootstrap.Modal(modalEl);

            _confirmacionCallback = function () {
                const form = elemento.closest('form');
                if (form) {
                    form.submit();
                } else if (elemento.tagName === 'A') {
                    window.location.href = elemento.href;
                }
            };

            modal.show();
            return false;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('modalConfirmacionBtnAceptar').addEventListener('click', function () {
                bootstrap.Modal.getInstance(document.getElementById('modalConfirmacionGenerica')).hide();
                if (_confirmacionCallback) _confirmacionCallback();
            });
        });
    </script>
@endsection
