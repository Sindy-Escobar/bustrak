@extends('layouts.layoutadmin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 style="margin:0; color:#1e63b8; font-weight:600; font-size:2rem;">
                    <i class="fas fa-headset me-2"></i>Consultas de Usuarios
                </h2>
            </div>
            <div class="card-body">
                <!-- Mensajes de éxito -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-circle-check me-2"></i>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                @endif

                <!-- Tabla de Consultas -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-primary">
                        <tr>
                            <th><i class="fas fa-user me-2"></i>Nombre</th>
                            <th><i class="fas fa-envelope me-2"></i>Correo</th>
                            <th><i class="fas fa-tag me-2"></i>Asunto</th>
                            <th><i class="fas fa-comment me-2"></i>Mensaje</th>
                            <th><i class="fas fa-calendar me-2"></i>Fecha de Envío</th>
                            <th><i class="fas fa-check-double me-2"></i>Estado</th>
                            <th class="text-center"><i class="fas fa-cog me-2"></i>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($consultas as $consulta)
                            <tr>
                                <td>{{ $consulta->nombre_completo }}</td>
                                <td>{{ $consulta->correo }}</td>
                                <td>{{ $consulta->asunto }}</td>
                                <td>{{ $consulta->mensaje }}</td>
                                <td>{{ $consulta->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($consulta->respuesta)
                                        <span class="badge bg-success">Respondida</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#responderModal{{ $consulta->id }}">
                                            <i class="fas fa-reply me-1"></i>{{ $consulta->respuesta ? 'Ver / Editar' : 'Responder' }}
                                        </button>
                                        <form action="{{ route('consulta.eliminar', $consulta->id) }}" method="POST" onsubmit="return confirmarAccionModal(this, '¿Eliminar esta consulta?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Responder -->
                            <div class="modal fade" id="responderModal{{ $consulta->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#1e63b8; color:white;">
                                            <h5 class="modal-title">
                                                <i class="fas fa-reply me-2"></i>Responder consulta de {{ $consulta->nombre_completo }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <form action="{{ route('consulta.responder', $consulta->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p><strong>Asunto:</strong> {{ $consulta->asunto }}</p>
                                                <p><strong>Mensaje del usuario:</strong><br>{{ $consulta->mensaje }}</p>

                                                @if($consulta->respuesta)
                                                    <div class="alert alert-light border">
                                                        <strong>Última respuesta enviada</strong>
                                                        ({{ $consulta->respondida_en?->format('d/m/Y H:i') }}):<br>
                                                        {{ $consulta->respuesta }}
                                                    </div>
                                                @endif

                                                <div class="mb-3">
                                                    <label for="respuesta{{ $consulta->id }}" class="form-label fw-bold">
                                                        {{ $consulta->respuesta ? 'Actualizar respuesta' : 'Escribir respuesta' }}
                                                    </label>
                                                    <textarea name="respuesta" id="respuesta{{ $consulta->id }}" rows="4"
                                                              class="form-control @error('respuesta') is-invalid @enderror"
                                                              maxlength="2000" required>{{ old('respuesta') }}</textarea>
                                                    @error('respuesta')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">El usuario verá esta respuesta como notificación dentro del sistema.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane me-1"></i>Enviar respuesta
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    No hay consultas registradas
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $consultas->links() }}
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
            return false; // evita la acción nativa (submit/navegación) hasta confirmar
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('modalConfirmacionBtnAceptar').addEventListener('click', function () {
                bootstrap.Modal.getInstance(document.getElementById('modalConfirmacionGenerica')).hide();
                if (_confirmacionCallback) _confirmacionCallback();
            });
        });
    </script>
@endsection
