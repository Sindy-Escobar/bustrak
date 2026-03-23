@extends('layouts.layoutuser')

@section('contenido')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="header-card">
                    <div class="header-content">
                        <div class="header-icon-wrapper">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <h2 class="header-title">Procesar Pago</h2>
                            <p class="header-subtitle">Selecciona tu método de pago preferido</p>
                        </div>
                    </div>
                    <div class="header-decoration"></div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-4">

                    {{-- Columna izquierda: Métodos de pago --}}
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="text-primary mb-4">
                                    <i class="fas fa-wallet me-2"></i>Selecciona Método de Pago
                                </h5>

                                <form action="{{ route('cliente.pago.store', $reserva->id) }}" method="POST" enctype="multipart/form-data" id="pagoForm">
                                    @csrf

                                    @php
                                        $tipoServicioNombre = strtolower($reserva->tipoServicio->nombre ?? '');
                                        $esRegularOInterurbano = str_contains($tipoServicioNombre, 'regular') ||
                                                                str_contains($tipoServicioNombre, 'interurbano');
                                    @endphp

                                    {{-- Métodos de pago --}}
                                    <div class="payment-methods mb-4">

                                        @if($esRegularOInterurbano)
                                            {{-- Solo Efectivo para Regular/Interurbano --}}
                                            <div class="alert alert-info mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Servicio {{ $reserva->tipoServicio->nombre }}:</strong>
                                                Solo se acepta pago en efectivo al abordar el bus.
                                            </div>

                                            <div class="payment-option">
                                                <input type="radio" name="metodo_pago" value="efectivo" id="efectivo" checked required>
                                                <label for="efectivo">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                    <span>Efectivo</span>
                                                    <small>Pagar al abordar</small>
                                                </label>
                                            </div>
                                        @else
                                            {{-- Todos los métodos para otros tipos de servicio --}}

                                            {{-- Tarjeta de Crédito --}}
                                            <div class="payment-option">
                                                <input type="radio" name="metodo_pago" value="tarjeta_credito" id="tarjeta_credito" required>
                                                <label for="tarjeta_credito">
                                                    <i class="fas fa-credit-card"></i>
                                                    <span>Tarjeta de Crédito</span>
                                                    <small>Pago inmediato</small>
                                                </label>
                                            </div>

                                            {{-- Tarjeta de Débito --}}
                                            <div class="payment-option">
                                                <input type="radio" name="metodo_pago" value="tarjeta_debito" id="tarjeta_debito">
                                                <label for="tarjeta_debito">
                                                    <i class="fas fa-credit-card"></i>
                                                    <span>Tarjeta de Débito</span>
                                                    <small>Pago inmediato</small>
                                                </label>
                                            </div>

                                            {{-- Transferencia Bancaria --}}
                                            <div class="payment-option">
                                                <input type="radio" name="metodo_pago" value="transferencia" id="transferencia">
                                                <label for="transferencia">
                                                    <i class="fas fa-university"></i>
                                                    <span>Transferencia Bancaria</span>
                                                    <small>Requiere aprobación</small>
                                                </label>
                                            </div>

                                            {{-- Pago en Terminal --}}
                                            <div class="payment-option">
                                                <input type="radio" name="metodo_pago" value="terminal" id="terminal">
                                                <label for="terminal">
                                                    <i class="fas fa-cash-register"></i>
                                                    <span>Pago en Terminal</span>
                                                    <small>Pagar en la terminal</small>
                                                </label>
                                            </div>

                                            {{-- Efectivo --}}
                                            <div class="payment-option">
                                                <input type="radio" name="metodo_pago" value="efectivo" id="efectivo">
                                                <label for="efectivo">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                    <span>Efectivo</span>
                                                    <small>Pagar al abordar</small>
                                                </label>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Formulario de Tarjeta --}}
                                    <div id="form-tarjeta" class="payment-form" style="display: none;">
                                        <h6 class="text-primary mb-3">Datos de la Tarjeta</h6>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Número de Tarjeta</label>
                                                <input type="text" class="form-control" name="numero_tarjeta" placeholder="1234 5678 9012 3456" maxlength="19">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Fecha de Expiración</label>
                                                <input type="text" class="form-control" name="fecha_expiracion" placeholder="MM/AA">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">CVV</label>
                                                <input type="text" class="form-control" name="cvv" placeholder="123" maxlength="3">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Formulario de Transferencia --}}
                                    <div id="form-transferencia" class="payment-form" style="display: none;">
                                        <h6 class="text-primary mb-3">Datos de la Transferencia</h6>
                                        <div class="alert alert-info">
                                            <strong>Datos Bancarios:</strong><br>
                                            Banco: Banco Atlántida<br>
                                            Cuenta: 123456789<br>
                                            Titular: BusTrak S.A.
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Referencia Bancaria</label>
                                                <input type="text" class="form-control" name="referencia_bancaria" placeholder="Ej: 123456">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Banco</label>
                                                <input type="text" class="form-control" name="banco" placeholder="Nombre del banco">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Comprobante de Pago</label>
                                                <input type="file" class="form-control" name="comprobante" accept="image/*,application/pdf">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Información de Terminal/Efectivo --}}
                                    <div id="info-terminal" class="payment-form alert alert-info" style="display: none;">
                                        <strong>Pago en Terminal:</strong><br>
                                        Dirígete a nuestra terminal en Tegucigalpa y presenta tu código de reserva: <strong>{{ $reserva->codigo_reserva }}</strong>
                                    </div>

                                    <div id="info-efectivo" class="payment-form alert alert-warning" style="display: none;">
                                        <strong>Pago en Efectivo:</strong><br>
                                        Deberás pagar el monto total al abordar el bus. Asegúrate de llevar el monto exacto.
                                    </div>

                                    {{-- Botón de pago --}}
                                    <div class="d-grid gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Confirmar Pago
                                        </button>
                                        <a href="{{ route('cliente.historial') }}" class="btn btn-outline-secondary">
                                            Cancelar
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Columna derecha: Resumen --}}
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                            <div class="card-body p-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-receipt me-2"></i>Resumen
                                </h5>

                                <div class="mb-3">
                                    <small class="text-muted">Código de Reserva</small>
                                    <h6 class="text-primary">{{ $reserva->codigo_reserva }}</h6>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Ruta</small>
                                    <p class="mb-0">{{ $reserva->viaje->origen->nombre }} → {{ $reserva->viaje->destino->nombre }}</p>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Asientos</small>
                                    <p class="mb-0">{{ $reserva->cantidad_asientos }} {{ $reserva->cantidad_asientos == 1 ? 'asiento' : 'asientos' }}</p>
                                </div>

                                <hr>

                                @php
                                    $tarifaBase = $reserva->tipoServicio->tarifa_base ?? 0;
                                    $subtotal = $tarifaBase * $reserva->cantidad_asientos;
                                    $servicios = $reserva->serviciosAdicionales->sum(function($s) {
                                        return $s->pivot->precio_unitario * $s->pivot->cantidad;
                                    });
                                @endphp

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <strong>L. {{ number_format($subtotal, 2) }}</strong>
                                </div>

                                @if($servicios > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Servicios:</span>
                                        <strong>L. {{ number_format($servicios, 2) }}</strong>
                                    </div>
                                @endif

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Total:</h5>
                                    <h4 class="mb-0 text-success">L. {{ number_format($total, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const metodosPago = document.querySelectorAll('input[name="metodo_pago"]');
            const formTarjeta = document.getElementById('form-tarjeta');
            const formTransferencia = document.getElementById('form-transferencia');
            const infoTerminal = document.getElementById('info-terminal');
            const infoEfectivo = document.getElementById('info-efectivo');

            // Verificar si hay métodos de pago para escuchar
            if (metodosPago.length === 0) return;

            metodosPago.forEach(metodo => {
                metodo.addEventListener('change', function() {
                    // Ocultar todos
                    if (formTarjeta) formTarjeta.style.display = 'none';
                    if (formTransferencia) formTransferencia.style.display = 'none';
                    if (infoTerminal) infoTerminal.style.display = 'none';
                    if (infoEfectivo) infoEfectivo.style.display = 'none';

                    // Mostrar el correspondiente
                    if (this.value === 'tarjeta_credito' || this.value === 'tarjeta_debito') {
                        if (formTarjeta) formTarjeta.style.display = 'block';
                    } else if (this.value === 'transferencia') {
                        if (formTransferencia) formTransferencia.style.display = 'block';
                    } else if (this.value === 'terminal') {
                        if (infoTerminal) infoTerminal.style.display = 'block';
                    } else if (this.value === 'efectivo') {
                        if (infoEfectivo) infoEfectivo.style.display = 'block';
                    }
                });
            });

            // Si solo hay efectivo, mostrarlo automáticamente
            const soloEfectivo = metodosPago.length === 1 && metodosPago[0].value === 'efectivo';
            if (soloEfectivo && infoEfectivo) {
                infoEfectivo.style.display = 'block';
            }
        });
    </script>

    <style>
        .header-card {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
            position: relative;
            overflow: hidden;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .header-icon-wrapper {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .header-title {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.75rem;
        }

        .header-subtitle {
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
        }

        .header-decoration {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .payment-option {
            margin-bottom: 15px;
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-option label {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-option label:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .payment-option input[type="radio"]:checked + label {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .payment-option label i {
            font-size: 24px;
            color: #3b82f6;
            margin-right: 15px;
        }

        .payment-option label span {
            flex: 1;
            font-weight: 600;
        }

        .payment-option label small {
            color: #6b7280;
        }

        .payment-form {
            background: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
@endsection
