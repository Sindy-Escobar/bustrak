{{--
    Componente: Barra de Progreso para Flujo de Reservas

    Uso:
    @include('components.progress-stepper', ['step' => 1])

    Parámetros:
    - step: 1 (Servicio), 2 (Destino/Seleccionar Viaje), 3 (Asientos), 4 (Confirmación)
--}}

@php
    $step = $step ?? 1; // Por defecto paso 1
@endphp

<div class="row justify-content-center mb-5">
    <div class="col-lg-11">
        <div class="progress-stepper">
            {{-- PASO 1: Servicio --}}
            <div class="step-item {{ $step >= 1 ? 'active' : '' }}">
                <div class="step-circle">
                    <i class="fas fa-bus"></i>
                </div>
                <span class="step-label">Servicio</span>
            </div>

            <div class="step-line {{ $step >= 2 ? '' : 'inactive' }}"></div>

            {{-- PASO 2: Destino/Viaje --}}
            <div class="step-item {{ $step >= 2 ? 'active' : '' }}">
                <div class="step-circle">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <span class="step-label">Destino</span>
            </div>

            <div class="step-line {{ $step >= 3 ? '' : 'inactive' }}"></div>

            {{-- PASO 3: Asientos --}}
            <div class="step-item {{ $step >= 3 ? 'active' : '' }}">
                <div class="step-circle">
                    <i class="fas fa-chair"></i>
                </div>
                <span class="step-label">Asientos</span>
            </div>

            <div class="step-line {{ $step >= 4 ? '' : 'inactive' }}"></div>

            {{-- PASO 4: Confirmación --}}
            <div class="step-item {{ $step >= 4 ? 'active' : '' }}">
                <div class="step-circle">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="step-label">Confirmación</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== PROGRESS STEPPER ===== */
    .progress-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 2rem;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        position: relative;
    }

    .step-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .step-item.active .step-circle {
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
        color: white;
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        transform: scale(1.1);
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
    }

    .step-item.active .step-label {
        color: #3b82f6;
    }

    .step-line {
        flex: 1;
        height: 4px;
        background: linear-gradient(to right, #3b82f6, #e5e7eb);
        margin: 0 1rem;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .step-line.inactive {
        background: #e5e7eb;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .progress-stepper {
            padding: 0 1rem;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .step-label {
            font-size: 0.75rem;
        }

        .step-line {
            margin: 0 0.5rem;
        }
    }

    @media (max-width: 576px) {
        .step-circle {
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }

        .step-label {
            font-size: 0.7rem;
        }

        .step-line {
            margin: 0 0.25rem;
            height: 3px;
        }
    }
</style>
