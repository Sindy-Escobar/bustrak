@extends('layouts.layoutadmin')

@section('content')
    <style>
        .crear-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header-card {
            background: linear-gradient(135deg, #5cb3ff 0%, #1e63b8 100%);
            border-radius: 16px;
            padding: 40px;
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .header-card h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #5cb3ff;
        }

        .metodos {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .metodo-item {
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .metodo-item:hover {
            border-color: #5cb3ff;
            background: #f0f7ff;
        }

        .metodo-label {
            display: flex !important;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            margin: 0 !important;
        }

        .metodo-label i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .metodo-item input[type="radio"] {
            width: auto !important;
            padding: 0 !important;
            border: none !important;
            cursor: pointer;
            margin: 0 !important;
        }

        .campos-dinamicos {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }

        .campos-dinamicos.activo {
            display: block;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #5cb3ff;
            color: white;
        }

        .btn-secondary {
            background: #ccc;
            color: #333;
        }

        .info-box {
            background: #f0f7ff;
            border-left: 4px solid #5cb3ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>

    <div class="crear-container">
        <div class="header-card">
            <h1>
                <i class="fas fa-undo-alt"></i>
                Procesar Nuevo Reembolso
            </h1>
        </div>

        <div class="card">
            <form action="{{ route('admin.reembolsos.procesar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Seleccionar Reserva -->
                <div class="form-group">
                    <label for="reserva">
                        <i class="fas fa-ticket-alt" style="color: #5cb3ff;"></i> Seleccionar Reserva *
                    </label>
                    <select name="reserva_id" id="reserva" required onchange="cargarDatos()">
                        <option value="">-- Seleccione una reserva --</option>
                        @foreach($reservas as $reserva)
                            <option value="{{ $reserva->id }}"
                                    data-cliente="{{ $reserva->user->name ?? 'N/A' }}"
                                    data-monto="{{ $reserva->precio_total }}"
                                    data-fecha="{{ $reserva->created_at->format('d/m/Y') }}"
                                    data-codigo="{{ $reserva->codigo_reserva }}">
                                {{ $reserva->codigo_reserva }} - {{ $reserva->user->name ?? 'N/A' }} (L. {{ number_format($reserva->precio_total, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Información Precargada -->
                <div id="info-precargada" style="display: none;">
                    <div class="info-box">
                        <p><i class="fas fa-hashtag" style="color:#5cb3ff;"></i> <strong>Código:</strong> <span id="codigo">-</span></p>
                        <p><i class="fas fa-user" style="color:#5cb3ff;"></i> <strong>Cliente:</strong> <span id="cliente">-</span></p>
                        <p><i class="fas fa-dollar-sign" style="color:#5cb3ff;"></i> <strong>Monto Original:</strong> L. <span id="monto">0.00</span></p>
                        <p><i class="fas fa-calendar" style="color:#5cb3ff;"></i> <strong>Fecha:</strong> <span id="fecha">-</span></p>
                    </div>
                </div>

                <!-- Monto a Reembolsar -->
                <div class="form-group">
                    <label for="monto_reembolso">
                        <i class="fas fa-coins" style="color: #5cb3ff;"></i> Monto a Reembolsar *
                    </label>
                    <input type="number" name="monto_reembolso" id="monto_reembolso" placeholder="0.00" step="0.01" required>
                </div>

                <!-- Método de Pago -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-wallet" style="color: #5cb3ff;"></i> Método de Pago *
                    </label>
                    <div class="metodos">
                        <div class="metodo-item">
                            <label class="metodo-label">
                                <input type="radio" name="metodo_pago" value="efectivo" onchange="mostrarCampos('efectivo')">
                                <i class="fas fa-money-bill-wave" style="color: #28a745;"></i> Efectivo
                            </label>
                            <small style="color: #999; margin-left: 52px;">(Máx. L. 3,000)</small>
                        </div>
                        <div class="metodo-item">
                            <label class="metodo-label">
                                <input type="radio" name="metodo_pago" value="transferencia" onchange="mostrarCampos('transferencia')">
                                <i class="fas fa-university" style="color: #1e63b8;"></i> Transferencia
                            </label>
                        </div>
                        <div class="metodo-item">
                            <label class="metodo-label">
                                <input type="radio" name="metodo_pago" value="credito" onchange="mostrarCampos('credito')">
                                <i class="fas fa-credit-card" style="color: #6f42c1;"></i> Crédito
                            </label>
                        </div>
                        <div class="metodo-item">
                            <label class="metodo-label">
                                <input type="radio" name="metodo_pago" value="cheque" onchange="mostrarCampos('cheque')">
                                <i class="fas fa-file-invoice" style="color: #fd7e14;"></i> Cheque
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Campos Dinámicos (Efectivo) -->
                <div id="campos-efectivo" class="campos-dinamicos">
                    <div class="info-box">
                        <i class="fas fa-clock" style="color: #5cb3ff;"></i> <strong>Horario:</strong> 8:00 AM - 5:00 PM<br><br>
                        <i class="fas fa-id-card" style="color: #5cb3ff;"></i> <strong>Se requiere:</strong> Firma y foto de identificación
                    </div>
                    <div class="form-group">
                        <label for="foto-id">
                            <i class="fas fa-camera" style="color: #5cb3ff;"></i> Foto de Identificación
                        </label>
                        <input type="file" name="foto_id" id="foto-id" accept="image/*">
                    </div>
                </div>

                <!-- Campos Dinámicos (Transferencia) -->
                <div id="campos-transferencia" class="campos-dinamicos">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-hashtag" style="color: #5cb3ff;"></i> Número de Cuenta (20 dígitos)
                        </label>
                        <input type="text" name="numero_cuenta" placeholder="20 dígitos" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label>
                            <i class="fas fa-university" style="color: #5cb3ff;"></i> Banco
                        </label>
                        <input type="text" name="banco" placeholder="Nombre del banco">
                    </div>
                    <div class="form-group">
                        <label>
                            <i class="fas fa-user" style="color: #5cb3ff;"></i> Titular de Cuenta
                        </label>
                        <input type="text" name="titular_cuenta" placeholder="Nombre del titular">
                    </div>
                </div>

                <!-- Campos Dinámicos (Cheque) -->
                <div id="campos-cheque" class="campos-dinamicos">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-file-invoice" style="color: #5cb3ff;"></i> Número de Cheque
                        </label>
                        <input type="text" name="numero_cheque" placeholder="Número del cheque">
                    </div>
                </div>

                <!-- Notas -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-sticky-note" style="color: #5cb3ff;"></i> Notas Adicionales
                    </label>
                    <textarea name="notas" placeholder="Observaciones..."></textarea>
                </div>

                <!-- Botones -->
                <div class="button-group">
                    <a href="{{ route('admin.reembolsos') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Procesar Reembolso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function cargarDatos() {
            const select = document.getElementById('reserva');
            const opcion = select.options[select.selectedIndex];

            if (opcion.value) {
                document.getElementById('info-precargada').style.display = 'block';
                document.getElementById('codigo').textContent = opcion.dataset.codigo;
                document.getElementById('cliente').textContent = opcion.dataset.cliente;
                document.getElementById('monto').textContent = parseFloat(opcion.dataset.monto).toFixed(2);
                document.getElementById('fecha').textContent = opcion.dataset.fecha;
                document.getElementById('monto_reembolso').value = opcion.dataset.monto;
            } else {
                document.getElementById('info-precargada').style.display = 'none';
            }
        }

        function mostrarCampos(metodo) {
            document.querySelectorAll('.campos-dinamicos').forEach(el => {
                el.classList.remove('activo');
            });

            if (metodo === 'efectivo') {
                document.getElementById('campos-efectivo').classList.add('activo');
            } else if (metodo === 'transferencia') {
                document.getElementById('campos-transferencia').classList.add('activo');
            } else if (metodo === 'cheque') {
                document.getElementById('campos-cheque').classList.add('activo');
            }
        }
    </script>
@endsection
