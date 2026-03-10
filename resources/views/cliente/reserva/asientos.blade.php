@extends('layouts.layoutuser')

@section('contenido')
    <style>
        .toggle-group { display: flex; gap: 12px; margin-bottom: 20px; }
        .toggle-option {
            flex: 1; display: flex; align-items: center; gap: 12px;
            padding: 13px 16px; border-radius: 10px;
            border: 2px solid #dee2e6; cursor: pointer;
            transition: all 0.2s; background: white;
        }
        .toggle-option:hover { border-color: #5cb3ff; }
        .toggle-option.selected { border-color: #5cb3ff; background: #f0f8ff; }
        .toggle-option input[type="radio"] { display: none; }
        .toggle-icon {
            width: 38px; height: 38px; border-radius: 50%;
            background: #f0f8ff; display: flex;
            align-items: center; justify-content: center; flex-shrink: 0;
        }
        .toggle-option.selected .toggle-icon { background: #5cb3ff; }
        .toggle-icon i { color: #5cb3ff; }
        .toggle-option.selected .toggle-icon i { color: white; }
        .toggle-label strong { display: block; font-size: 0.88rem; margin-bottom: 2px; color: #333; }
        .toggle-label small { font-size: 0.75rem; color: #888; }

        #form-tercero {
            background: #f8f9fa; border-radius: 12px;
            padding: 20px; margin-bottom: 16px;
            border: 1px solid #dee2e6;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        .section-title-hu14 {
            font-size: 0.82rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px; color: #1e63b8; margin-bottom: 14px;
        }
        .info-alert-hu14 {
            display: flex; align-items: flex-start; gap: 9px;
            background: #e3f2fd; border: 1px solid #90caf9;
            border-radius: 8px; padding: 11px 14px;
            font-size: 0.81rem; color: #1565c0; margin-bottom: 16px;
        }
    </style>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-chair me-2"></i>
                Asientos — {{ $viaje->origen->nombre }} → {{ $viaje->destino->nombre }}
            </h4>
            <small>{{ \Carbon\Carbon::parse($viaje->fecha_hora_salida)->format('d/m/Y H:i') }}</small>
        </div>

        <div class="card-body">

            {{-- Mensajes de error/éxito --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form id="formReserva" action="{{ route('cliente.reserva.store') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">
                <input type="hidden" name="fecha_nacimiento_pasajero" value="{{ session('fecha_nacimiento_busqueda') }}">
                <input type="hidden" name="para_tercero" id="para_tercero" value="0">

                {{-- 1. Selección de asiento --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Seleccione Asiento *</label>
                    <select name="asiento_id" class="form-select" required>
                        <option value="" disabled selected>Seleccione un asiento...</option>
                        @foreach($asientos as $asiento)
                            <option value="{{ $asiento->id }}">Asiento #{{ $asiento->numero_asiento }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- 2. HU14: ¿Para quién es el boleto? --}}
                <p class="fw-bold mb-2" style="font-size:0.9rem;">
                    <i class="fas fa-user-friends me-1 text-primary"></i> ¿Para quién es el boleto?
                </p>
                <div class="toggle-group">
                    <label class="toggle-option selected" id="opt-self" onclick="seleccionarPara('self')">
                        <input type="radio" name="_para_quien" value="self" checked>
                        <div class="toggle-icon"><i class="fas fa-user"></i></div>
                        <div class="toggle-label">
                            <strong>Para mí</strong>
                            <small>Mis datos del perfil</small>
                        </div>
                    </label>
                    <label class="toggle-option" id="opt-other" onclick="seleccionarPara('other')">
                        <input type="radio" name="_para_quien" value="other">
                        <div class="toggle-icon"><i class="fas fa-user-plus"></i></div>
                        <div class="toggle-label">
                            <strong>Para otra persona</strong>
                            <small>No necesita cuenta en el sistema</small>
                        </div>
                    </label>
                </div>

                {{-- 3. HU14: Formulario del tercero (oculto por defecto) --}}
                <div id="form-tercero" style="display:none;">
                    <p class="section-title-hu14">
                        <i class="fas fa-id-card me-1"></i> Datos del Pasajero Tercero
                    </p>
                    <div class="info-alert-hu14">
                        <i class="fas fa-info-circle mt-1"></i>
                        <span>El pasajero <strong>no necesita cuenta</strong> en el sistema. El boleto se generará a su nombre.</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nombre(s) *</label>
                            <input type="text" name="tercero_nombre" class="form-control" placeholder="Ej. María José">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Primer Apellido *</label>
                            <input type="text" name="tercero_apellido1" class="form-control" placeholder="Ej. García">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Segundo Apellido</label>
                            <input type="text" name="tercero_apellido2" class="form-control" placeholder="Ej. López">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">País *</label>
                            <select name="tercero_pais" class="form-select" id="tercero_pais" onchange="actualizarDoc()">
                                <option value="Honduras" selected>Honduras</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="México">México</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Estados Unidos">Estados Unidos</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tipo de Documento *</label>
                            <select name="tercero_tipo_doc" class="form-select">
                                <option value="DNI">DNI / Identidad</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="Residente">Carné de Residente</option>
                                <option value="Licencia">Licencia de Conducir</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Número de Documento *</label>
                            <input type="text" name="tercero_num_doc" class="form-control" placeholder="0801-1990-12345">
                            <div class="form-text" id="hint-doc">Formato: 0801-0000-00000</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Teléfono del Pasajero *</label>
                            <input type="tel" name="tercero_telefono" class="form-control" placeholder="+504 9999-9999">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Correo Electrónico <span class="text-muted fw-normal">(Opcional)</span></label>
                            <input type="text" name="tercero_email" class="form-control" placeholder="pasajero@email.com">
                        </div>
                    </div>
                </div>

                {{--  HU2: SERVICIOS ADICIONALES --}}
                <div class="card shadow-sm mb-4 mt-4">
                    <div class="card-header" style="background-color: #1e3a8a; color: white;">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Servicios Adicionales
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Selecciona los servicios que deseas agregar a tu viaje. <strong>Se pagan al momento de abordar.</strong>
                        </p>

                        <div class="row g-3">
                            @foreach($serviciosAdicionales as $servicio)
                                <div class="col-md-4">
                                    <div class="card h-100 border">
                                        <div class="card-body text-center">
                                            <i class="{{ $servicio->icono }} fa-3x mb-3" style="color: #1e3a8a;"></i>
                                            <h6 class="fw-bold">{{ $servicio->nombre }}</h6>
                                            <p class="text-muted small">{{ $servicio->descripcion }}</p>
                                            <p class="fw-bold text-success mb-3">L. {{ number_format($servicio->precio, 2) }}</p>

                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="servicios[]"
                                                       value="{{ $servicio->id }}"
                                                       id="servicio{{ $servicio->id }}">
                                                <label class="form-check-label" for="servicio{{ $servicio->id }}">
                                                    Agregar
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-wallet me-2"></i>
                            <strong>Nota:</strong> Los servicios adicionales se pagan al momento de abordar el bus.
                        </div>
                    </div>
                </div>

                {{-- Alerta menor de edad (igual que antes) --}}
                @php
                    $fnac = session('fecha_nacimiento_busqueda');
                    $edadPasajero = $fnac ? \Carbon\Carbon::parse($fnac)->age : 99;
                @endphp
                @if($edadPasajero < 18)
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atención:</strong> El pasajero es menor de edad ({{ $edadPasajero }} años).
                        Deberá completar la autorización del tutor después de confirmar.
                    </div>
                @endif

                {{-- Botones --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('cliente.reserva.create') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-outline-primary" onclick="return validarForm()">
                        <i class="fas fa-check-circle me-1"></i> Confirmar Reserva
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function seleccionarPara(who) {
            const optSelf    = document.getElementById('opt-self');
            const optOther   = document.getElementById('opt-other');
            const formTercero = document.getElementById('form-tercero');
            const inputPara   = document.getElementById('para_tercero');
            const camposReq   = ['tercero_nombre','tercero_apellido1','tercero_pais','tercero_tipo_doc','tercero_num_doc','tercero_telefono'];

            if (who === 'self') {
                optSelf.classList.add('selected');
                optOther.classList.remove('selected');
                formTercero.style.display = 'none';
                inputPara.value = '0';
                camposReq.forEach(n => { const el = document.querySelector(`[name="${n}"]`); if(el) el.removeAttribute('required'); });
            } else {
                optOther.classList.add('selected');
                optSelf.classList.remove('selected');
                formTercero.style.display = 'block';
                inputPara.value = '1';
                camposReq.forEach(n => { const el = document.querySelector(`[name="${n}"]`); if(el) el.setAttribute('required',''); });
            }
        }

        function actualizarDoc() {
            const hints = {
                'Honduras':'Formato: 0801-0000-00000',
                'Guatemala':'Formato: DPI (13 dígitos)',
                'El Salvador':'Formato: DUI (00000000-0)',
                'Nicaragua':'Formato: 001-010101-0000A',
                'Costa Rica':'Formato: 1-0000-0000',
                'México':'Formato: CURP (18 caracteres)',
                'Colombia':'Formato: CC 1234567890',
                'Estados Unidos':'Formato: Pasaporte o SSN',
            };
            const pais = document.getElementById('tercero_pais').value;
            document.getElementById('hint-doc').textContent = hints[pais] || '';
        }

        function validarForm() {
            if (document.getElementById('para_tercero').value !== '1') return true;
            const campos = {
                'Nombre':    'tercero_nombre',
                'Apellido':  'tercero_apellido1',
                'Documento': 'tercero_num_doc',
                'Teléfono':  'tercero_telefono',
            };
            for (const [label, name] of Object.entries(campos)) {
                const el = document.querySelector(`[name="${name}"]`);
                if (!el || !el.value.trim()) {
                    if(el){ el.focus(); el.classList.add('is-invalid'); setTimeout(()=>el.classList.remove('is-invalid'),2500); }
                    alert(`Por favor completa el campo: ${label}`);
                    return false;
                }
            }
            return true;
        }
    </script>
@endsection
