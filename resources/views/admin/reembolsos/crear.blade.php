@extends('layouts.layoutadmin')

@section('content')
    <style>
        .page-container {
            padding: 30px;
            max-width: 900px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1a56db;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
        }

        .form-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px;
        }

        .form-section-title {
            font-weight: 700;
            color: #1a56db;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef4ff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 13px;
            color: #333;
            margin-bottom: 8px;
        }

        .form-group label i { color: #1a56db; }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1a56db;
        }

        .info-box {
            background: #eef4ff;
            border-left: 4px solid #1a56db;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .info-box p {
            margin: 4px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box i { color: #1a56db; width: 16px; }

        .metodos {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 10px;
        }

        .metodo-item {
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .metodo-item:hover {
            border-color: #1a56db;
            background: #eef4ff;
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

        .metodo-label i { font-size: 17px; width: 20px; text-align: center; }

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
            border-top: 1px solid #e0e0e0;
        }

        .campos-dinamicos.activo { display: block; }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .btn-cancelar {
            padding: 10px 24px;
            background: white;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-procesar {
            padding: 10px 24px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-procesar:hover { background: #1648b8; }
    </style>

    <div class="page-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div class="page-title">
                <i class="fas fa-undo-alt"></i> Nuevo Reembolso
            </div>
            <a href="{{ route('admin.reembolsos') }}" class="btn-cancelar">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="form-card">
            <form action="{{ route('admin.reembolsos.procesar') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-section-title">
                    <i class="fas fa-ticket-alt"></i> Información de la Reserva
                </div>

                <div class="form-group">
                    <label><i class="fas fa-ticket-alt"></i> Seleccionar Reserva *</label>
                    <select name="reserva_id" id="reserva" required onchange="cargarDatos()">
                        <option value="">-- Seleccione una reserva --</option>
                        @foreach($reservas as $reserva)
                            <option value="{{ $reserva->id }}"
                                    data-cliente="{{ $reserva->user->name ?? 'N/A' }}"
                                    data-monto="{{ $reserva->total_a_pagar }}"
                                    data-fecha="{{ $reserva->created_at->format('d/m/Y') }}"
                                    data-codigo="{{ $reserva->codigo_reserva }}"
                                    data-banco="{{ $reserva->reembolsos->first()->banco ?? '' }}"
                                    data-cuenta="{{ $reserva->reembolsos->first()->numero_cuenta ?? '' }}"
                                    data-titular="{{ $reserva->reembolsos->first()->titular_cuenta ?? '' }}">
                                {{ $reserva->user->name ?? 'N/A' }} —
                                {{ $reserva->viaje->origen->nombre ?? '?' }} → {{ $reserva->viaje->destino->nombre ?? '?' }} —
                                {{ \Carbon\Carbon::parse($reserva->viaje->fecha_hora_salida)->format('d/m/Y H:i') }} —
                                {{ $reserva->cantidad_asientos ?? 1 }} {{ ($reserva->cantidad_asientos ?? 1) == 1 ? 'asiento' : 'asientos' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="info-precargada" style="display: none;">
                    <div class="info-box">
                        <p><i class="fas fa-hashtag"></i> <strong>Código:</strong> <span id="codigo">-</span></p>
                        <p><i class="fas fa-user"></i> <strong>Cliente:</strong> <span id="cliente">-</span></p>
                        <p><i class="fas fa-dollar-sign"></i> <strong>Monto Original:</strong> L. <span id="monto">0.00</span></p>
                        <p><i class="fas fa-calendar"></i> <strong>Fecha:</strong> <span id="fecha">-</span></p>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-coins"></i> Monto a Reembolsar *</label>
                    <input type="number" name="monto_reembolso" id="monto_reembolso" placeholder="0.00" step="0.01" required>
                </div>

                <div class="form-section-title" style="margin-top: 10px;">
                    <i class="fas fa-wallet"></i> Método de Pago
                </div>

                <div class="form-group">
                    <label><i class="fas fa-wallet"></i> Selecciona el método *</label>
                    <div class="metodos">
                        <div class="metodo-item">
                            <label class="metodo-label">
                                <input type="radio" name="metodo_pago" value="efectivo" onchange="mostrarCampos('efectivo')">
                                <i class="fas fa-money-bill-wave" style="color: #28a745;"></i> Efectivo
                            </label>
                            <small style="color: #999; margin-left: 48px;">(Máx. L. 3,000)</small>
                        </div>
                        <div class="metodo-item">
                            <label class="metodo-label">
                                <input type="radio" name="metodo_pago" value="transferencia" onchange="mostrarCampos('transferencia')">
                                <i class="fas fa-university" style="color: #1a56db;"></i> Transferencia
                            </label>
                        </div>


                    </div>
                </div>

                <div id="campos-efectivo" class="campos-dinamicos">
                    <div class="info-box">
                        <p><i class="fas fa-clock"></i> <strong>Horario:</strong> 8:00 AM - 5:00 PM</p>
                        <p><i class="fas fa-id-card"></i> <strong>Se requiere:</strong> Firma y foto de identificación</p>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-camera"></i> Foto de Identificación</label>
                        <input type="file" name="foto_id" id="foto-id" accept="image/*">
                    </div>
                </div>

                <div id="campos-transferencia" class="campos-dinamicos">
                    <div class="form-group">
                        <label><i class="fas fa-hashtag"></i> Número de Cuenta (14 dígitos)</label>
                        <input type="text" name="numero_cuenta" placeholder="14 dígitos" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-university"></i> Banco</label>
                        <input type="text" name="banco" placeholder="Nombre del banco">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Titular de Cuenta</label>
                        <input type="text" name="titular_cuenta" placeholder="Nombre del titular">
                    </div>
                </div>



                <div class="form-group" style="margin-top: 10px;">
                    <label><i class="fas fa-sticky-note"></i> Notas Adicionales</label>
                    <textarea name="notas" placeholder="Observaciones..." rows="3"></textarea>
                </div>

                <div class="button-group">
                    <a href="{{ route('admin.reembolsos') }}" class="btn-cancelar">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn-procesar">
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

                // ✅ Precargar datos bancarios si existen
                if (opcion.dataset.banco) {
                    document.querySelector('[name="banco"]').value = opcion.dataset.banco;
                }
                if (opcion.dataset.cuenta) {
                    document.querySelector('[name="numero_cuenta"]').value = opcion.dataset.cuenta;
                }
                if (opcion.dataset.titular) {
                    document.querySelector('[name="titular_cuenta"]').value = opcion.dataset.titular;
                }
            } else {
                document.getElementById('info-precargada').style.display = 'none';
            }
        }

        function mostrarCampos(metodo) {
            document.querySelectorAll('.campos-dinamicos').forEach(el => el.classList.remove('activo'));
            if (metodo === 'efectivo') document.getElementById('campos-efectivo').classList.add('activo');
            else if (metodo === 'transferencia') document.getElementById('campos-transferencia').classList.add('activo');
            else if (metodo === 'cheque') document.getElementById('campos-cheque').classList.add('activo');
        }
    </script>
@endsection
