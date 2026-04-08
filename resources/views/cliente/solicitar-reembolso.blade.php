@extends('layouts.layoutuser')

@section('title', 'Solicitar Reembolso')

@section('contenido')
    <style>
        .reembolso-wrapper {
            max-width: 720px;
            margin: 0 auto;
            padding: 10px 0 40px;
        }
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 28px;
        }
        .breadcrumb-nav a { color: #1a56db; text-decoration: none; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .pasos-tracker {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
        }
        .paso {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        .paso:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 16px;
            left: 60%;
            width: 80%;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }
        .paso.completado:not(:last-child)::after { background: #059669; }
        .paso-numero {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            z-index: 1;
            border: 2px solid #e5e7eb;
            background: #fff;
            color: #6b7280;
        }
        .paso.completado .paso-numero { background: #059669; border-color: #059669; color: #fff; }
        .paso.activo .paso-numero { background: #fff; border-color: #059669; color: #059669; }
        .paso-label { font-size: 0.75rem; color: #6b7280; margin-top: 6px; text-align: center; }
        .paso.activo .paso-label { color: #059669; font-weight: 600; }
        .paso.completado .paso-label { color: #047857; }
        .cancelacion-info {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-left: 4px solid #f59e0b;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
        }
        .cancelacion-info i { color: #d97706; font-size: 1.2rem; margin-top: 2px; }
        .cancelacion-info strong { display: block; color: #92400e; margin-bottom: 4px; font-size: 0.9rem; }
        .cancelacion-info p { margin: 0; font-size: 0.85rem; color: #78350f; }
        .card-reembolso {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .card-reembolso .card-header {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            padding: 22px 28px;
            border: none;
        }
        .card-reembolso .card-header h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-reembolso .card-header p {
            color: rgba(255,255,255,0.85);
            margin: 6px 0 0;
            font-size: 0.9rem;
        }
        .card-reembolso .card-body { padding: 28px; }
        .monto-card {
            background: #f0fdf4;
            border: 1.5px solid #a7f3d0;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .monto-icono {
            width: 56px;
            height: 56px;
            background: #d1fae5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .monto-icono i { color: #059669; font-size: 1.5rem; }
        .monto-info label {
            display: block;
            font-size: 0.8rem;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .monto-valor {
            font-size: 2rem;
            font-weight: 800;
            color: #059669;
            line-height: 1;
        }
        .monto-nota { font-size: 0.8rem; color: #059669; margin-top: 4px; }
        .seccion-titulo {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .metodo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        .metodo-option { position: relative; }
        .metodo-option input[type="radio"] { position: absolute; opacity: 0; width: 0; }
        .metodo-label {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }
        .metodo-label:hover { border-color: #a7f3d0; background: #f0fdf4; }
        .metodo-option input[type="radio"]:checked + .metodo-label {
            border-color: #059669;
            background: #f0fdf4;
            box-shadow: 0 0 0 3px rgba(5,150,105,0.1);
        }
        .metodo-icono {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .ic-efectivo { background: #fef3c7; color: #d97706; }
        .ic-transferencia { background: #dbeafe; color: #1d4ed8; }
        .ic-credito { background: #f3e8ff; color: #7c3aed; }
        .ic-cheque { background: #f1f5f9; color: #475569; }
        .metodo-texto strong { display: block; font-size: 0.9rem; color: #111827; margin-bottom: 2px; }
        .metodo-texto small { color: #6b7280; font-size: 0.78rem; }
        .campos-metodo {
            display: none;
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }
        .campos-metodo.activo { display: block; }
        .btn-solicitar {
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px 28px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s;
            width: 100%;
        }
        .btn-solicitar:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(5,150,105,0.4);
            color: #fff;
        }
        .btn-volver {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            color: #6b7280;
            background: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-volver:hover { background: #f9fafb; color: #374151; }
        @media (max-width: 576px) {
            .metodo-grid { grid-template-columns: 1fr; }
        }
    </style>

    <div class="reembolso-wrapper">

        <nav class="breadcrumb-nav">
            <a href="{{ route('cliente.historial') }}"><i class="fas fa-home me-1"></i>Mis Reservas</a>
            <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span>Solicitar Reembolso</span>
        </nav>

        <div class="pasos-tracker">
            <div class="paso completado">
                <div class="paso-numero"><i class="fas fa-check" style="font-size: 0.75rem;"></i></div>
                <div class="paso-label">Cancelación</div>
            </div>
            <div class="paso activo">
                <div class="paso-numero">2</div>
                <div class="paso-label">Reembolso</div>
            </div>
            <div class="paso">
                <div class="paso-numero">3</div>
                <div class="paso-label">Confirmación</div>
            </div>
        </div>

        <div class="cancelacion-info">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Reserva cancelada: #{{ $reembolso->codigo_cancelacion }}</strong>
                <p>Tu boleto ha sido cancelado. Ahora puedes solicitar tu reembolso eligiendo el método de pago de tu preferencia.</p>
            </div>
        </div>

        <div class="card-reembolso card">
            <div class="card-header">
                <h4><i class="fas fa-hand-holding-usd"></i> Solicitar Reembolso</h4>
                <p>Completa los datos para recibir tu reembolso</p>
            </div>
            <div class="card-body">

                <div class="monto-card">
                    <div class="monto-icono">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="monto-info">
                        <label>Monto a reembolsar</label>
                        <div class="monto-valor">L. {{ number_format($reembolso->monto_reembolso, 2) }}</div>
                        <div class="monto-nota">
                            @php
                                $reserva          = $reembolso->reserva;
                                $tarifaBase       = $reserva->tipoServicio->tarifa_base ?? 0;
                                $cantidadAsientos = $reserva->cantidad_asientos ?? 1;
                                $subtotalAsientos = $tarifaBase * $cantidadAsientos;
                                $totalServicios   = $reserva->serviciosAdicionales->sum(function ($s) {
                                    return $s->pivot->precio_unitario * $s->pivot->cantidad;
                                });
                            @endphp
                            Boleto: L. {{ number_format($subtotalAsientos, 2) }}
                            ({{ $cantidadAsientos }} {{ $cantidadAsientos == 1 ? 'asiento' : 'asientos' }} × L. {{ number_format($tarifaBase, 2) }})
                            @if($totalServicios > 0)
                                + Servicios: L. {{ number_format($totalServicios, 2) }}
                            @endif
                            = Total original: L. {{ number_format($reembolso->monto_original, 2) }}
                            &nbsp;·&nbsp; Código: <strong>{{ $reembolso->codigo_reembolso }}</strong>
                        </div>
                    </div>
                </div>

                <form action="{{ route('cliente.reembolso.guardar', $reembolso->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <p class="seccion-titulo"><i class="fas fa-credit-card me-2"></i>Método de Reembolso</p>

                    <div class="metodo-grid">
                        <div class="metodo-option">
                            <input type="radio" name="metodo_pago" id="efectivo" value="efectivo" onchange="mostrarCampos('efectivo')">
                            <label class="metodo-label" for="efectivo">
                                <div class="metodo-icono ic-efectivo"><i class="fas fa-money-bill-alt"></i></div>
                                <div class="metodo-texto">
                                    <strong>Efectivo</strong>
                                    <small>Recojo en ventanilla</small>
                                </div>
                            </label>
                        </div>
                        <div class="metodo-option">
                            <input type="radio" name="metodo_pago" id="transferencia" value="transferencia" onchange="mostrarCampos('transferencia')">
                            <label class="metodo-label" for="transferencia">
                                <div class="metodo-icono ic-transferencia"><i class="fas fa-university"></i></div>
                                <div class="metodo-texto">
                                    <strong>Transferencia</strong>
                                    <small>A tu cuenta bancaria</small>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="campos-metodo" id="campos-efectivo">
                        <div class="alert alert-info border-0 rounded-3 mb-0" style="background: #eff6ff; color: #1e40af;">
                            <i class="fas fa-info-circle me-2"></i>
                            Podrás recoger tu reembolso en cualquiera de nuestras ventanillas de atención. Te notificaremos cuando esté listo.
                        </div>
                    </div>

                    <div class="campos-metodo" id="campos-transferencia">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Banco <span class="text-danger">*</span></label>
                                <select name="banco" class="form-select">
                                    <option value="" disabled selected>Selecciona tu banco</option>
                                    <option>Banco Atlántida</option>
                                    <option>Banco de Occidente</option>
                                    <option>Banpais</option>
                                    <option>BAC Honduras</option>
                                    <option>Ficohsa</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Número de Cuenta <span class="text-danger">*</span></label>
                                <input type="text" name="numero_cuenta" class="form-control"
                                       placeholder="Ej: 12345678901234"
                                       maxlength="14"
                                       inputmode="numeric"
                                       pattern="\d{14}"
                                       oninput="this.value=this.value.replace(/\D/g,'').slice(0,14)"
                                       title="Ingresa exactamente 14 dígitos numéricos">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nombre del Titular <span class="text-danger">*</span></label>
                                <input type="text" name="titular_transferencia" class="form-control" placeholder="Nombre completo del titular de la cuenta">
                            </div>
                        </div>
                    </div>





                    <div class="mb-4 mt-3">
                        <label class="form-label">Observaciones adicionales <span class="text-muted">(opcional)</span></label>
                        <textarea name="notas" class="form-control" rows="2"
                                  placeholder="¿Alguna observación para procesar tu reembolso?"></textarea>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger rounded-3 mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex gap-3">
                        <a href="{{ route('cliente.historial') }}" class="btn-volver">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn-solicitar">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud de Reembolso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function mostrarCampos(metodo) {
            document.querySelectorAll('.campos-metodo').forEach(function(el) {
                el.classList.remove('activo');
            });
            var target = document.getElementById('campos-' + metodo);
            if (target) target.classList.add('activo');
        }
    </script>
@endsection
