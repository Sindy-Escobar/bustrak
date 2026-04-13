<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Autorizacion;
use App\Models\User;
use App\Models\Viaje;
use App\Models\Asiento;

class CheckinController extends Controller
{
    public function vistaPublica()
    {
        return view('checkin.publico');
    }
    public function verificarPin(Request $request)
    {
        $dni = $request->input('dni');
        $pin = $request->input('pin');

        $empleado = \App\Models\Empleado::where('dni', $dni)
            ->where('pin', $pin)
            ->where('estado', 'Activo')
            ->first();

        if (!$empleado) {
            return response()->json([
                'success' => false,
                'message' => 'DNI o PIN incorrecto'
            ]);
        }

        return response()->json([
            'success' => true,
            'empleado' => [
                'nombre' => $empleado->nombre . ' ' . $empleado->apellido,
                'cargo' => $empleado->cargo
            ]
        ]);
    }
    /**
     * Validar código QR y verificar autorización de menores
     */
    public function validarCodigo(Request $request)
    {
        try {
            $codigo = trim($request->input('codigo_qr'));

            if (!$codigo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código QR no proporcionado',
                    'tipo_alerta' => 'error'
                ]);
            }

            $codigoLimpio = preg_replace('/[^a-zA-Z0-9_-]/', '', $codigo);

            // ✅ BUSCAR RESERVA (sin autorizacionMenor)
            $reserva = Reserva::with([
                'user',
                'viaje.origen',
                'viaje.destino',
                'tipoServicio',
                'serviciosAdicionales'
            ])
                ->where('codigo_reserva', $codigoLimpio)
                ->first();

            if (!$reserva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reserva no encontrada o código QR inválido.',
                    'tipo_alerta' => 'error'
                ]);
            }

            // ✅ VALIDAR SI LA RESERVA ESTÁ CANCELADA
            if ($reserva->estado === 'cancelada') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta reserva ha sido cancelada y no puede abordar.',
                    'tipo_alerta' => 'error'
                ]);
            }

            // ✅ VALIDAR SI YA ABORDÓ
            if ($reserva->abordado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta reserva ya realizó el check-in anteriormente el ' .
                        ($reserva->fecha_abordaje ? \Carbon\Carbon::parse($reserva->fecha_abordaje)->format('d/m/Y H:i') : 'fecha no registrada'),
                    'tipo_alerta' => 'warning'
                ]);
            }
            // ══════════════════════════════════════════════════════════
            // OBTENER NÚMEROS DE ASIENTOS
            // ══════════════════════════════════════════════════════════
            $asientosReservados = Asiento::where('reserva_id', $reserva->id)
                ->orderBy('numero_asiento')
                ->pluck('numero_asiento')
                ->toArray();

            // ══════════════════════════════════════════════════════════
            // VERIFICAR SI ES MENOR Y TIENE AUTORIZACIÓN
            // ══════════════════════════════════════════════════════════
            $alertaMenor = null;

            if ($reserva->es_menor) {
                // Determinar el país del pasajero
                $paisPasajero = $reserva->para_tercero
                    ? $reserva->tercero_pais
                    : ($reserva->user->pais ?? 'Honduras');

                $esHondureno = strtolower(trim($paisPasajero)) === 'honduras';
                $necesitaAutorizacion = !$esHondureno;

                // Calcular edad
                $edad = \Carbon\Carbon::parse($reserva->fecha_nacimiento_pasajero)->age;

                if ($necesitaAutorizacion) {
                    // MENOR EXTRANJERO - Necesita autorización
                    $autorizacion = Autorizacion::where('reserva_id', $reserva->id)
                        ->where('estado', 'aprobada')
                        ->first();

                    if (!$autorizacion) {
                        return response()->json([
                            'success' => false,
                            'message' => '⚠️ PASAJERO MENOR EXTRANJERO - NO TIENE AUTORIZACIÓN APROBADA. Debe completar la autorización antes de abordar.',
                            'tipo_alerta' => 'error',
                            'es_menor' => true,
                            'sin_autorizacion' => true
                        ]);
                    }

                    // Autorización válida para menor extranjero
                    $alertaMenor = [
                        'es_menor' => true,
                        'es_extranjero' => true,
                        'menor_dni' => $reserva->para_tercero ? $reserva->tercero_num_doc : ($reserva->user->dni ?? 'N/A'),
                        'menor_edad' => $edad,
                        'tutor_nombre' => 'Ver documento de autorización',
                        'tutor_dni' => 'Ver documento de autorización',
                        'parentesco' => 'Ver documento de autorización',
                        'codigo_autorizacion' => 'AUT-' . $autorizacion->id
                    ];
                } else {
                    // MENOR HONDUREÑO - No necesita autorización
                    $alertaMenor = [
                        'es_menor' => true,
                        'es_extranjero' => false,
                        'menor_dni' => $reserva->para_tercero ? $reserva->tercero_num_doc : ($reserva->user->dni ?? 'N/A'),
                        'menor_edad' => $edad
                    ];
                }
            }

            // ══════════════════════════════════════════════════════════
            // PREPARAR SERVICIOS ADICIONALES
            // ══════════════════════════════════════════════════════════
            $serviciosAdicionales = $reserva->serviciosAdicionales->map(function($servicio) {
                return [
                    'nombre' => $servicio->nombre,
                    'cantidad' => $servicio->pivot->cantidad
                ];
            })->toArray();

            // ══════════════════════════════════════════════════════════
            // PREPARAR INFORMACIÓN DEL PASAJERO
            // ══════════════════════════════════════════════════════════
            $pasajeroInfo = [
                'para_tercero' => $reserva->para_tercero,
                'nombre_completo' => $reserva->user->nombre_completo ?? $reserva->user->name,
                'dni' => $reserva->user->dni ?? 'N/A',
                'email' => $reserva->user->email,
                'telefono' => $reserva->user->telefono ?? 'N/A',
            ];

            if ($reserva->para_tercero) {
                $pasajeroInfo['usuario_nombre'] = $reserva->user->nombre_completo ?? $reserva->user->name;
                $pasajeroInfo['usuario_email'] = $reserva->user->email;
                $pasajeroInfo['tercero_nombre'] = $reserva->tercero_nombre;
                $pasajeroInfo['tercero_tipo_doc'] = $reserva->tercero_tipo_doc;
                $pasajeroInfo['tercero_num_doc'] = $reserva->tercero_num_doc;
                $pasajeroInfo['tercero_pais'] = $reserva->tercero_pais;
                $pasajeroInfo['tercero_telefono'] = $reserva->tercero_telefono;
                $pasajeroInfo['tercero_email'] = $reserva->tercero_email;
            }

            // ══════════════════════════════════════════════════════════
            // PREPARAR INFORMACIÓN DEL VIAJE
            // ══════════════════════════════════════════════════════════
            $viaje = $reserva->viaje;

            $viajeInfo = [
                'ruta' => $viaje->origen->nombre . ' - ' . $viaje->destino->nombre,
                'origen' => $viaje->origen->nombre,
                'destino' => $viaje->destino->nombre,
                'fecha_salida' => \Carbon\Carbon::parse($viaje->fecha_hora_salida)->format('d/m/Y H:i'),
                'tipo_servicio' => $reserva->tipoServicio->nombre ?? 'N/A',
                'asientos' => $asientosReservados,
                'asiento' => $asientosReservados[0] ?? 'N/A'
            ];

            // ══════════════════════════════════════════════════════════
            // PREPARAR RESPUESTA
            // ══════════════════════════════════════════════════════════
            $datos = [
                'reserva_id' => $reserva->id,
                'codigo_qr' => $reserva->codigo_reserva,
                'pasajero' => $pasajeroInfo,
                'viaje' => $viajeInfo,
                'servicios_adicionales' => $serviciosAdicionales,
                'autorizacion_menor' => $alertaMenor
            ];

            $mensaje = 'Datos de reserva verificados correctamente';
            if ($alertaMenor) {
                if ($alertaMenor['es_extranjero']) {
                    $mensaje = '✅ PASAJERO MENOR EXTRANJERO - Autorización VÁLIDA. Verificar DNI del tutor presente.';
                } else {
                    $mensaje = '✅ PASAJERO MENOR HONDUREÑO - No requiere autorización adicional.';
                }
            }

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'tipo_alerta' => $alertaMenor ? 'warning' : 'success',
                'datos' => $datos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'tipo_alerta' => 'error'
            ]);
        }
    }

    /**
     * Confirmar abordaje
     */
    public function confirmarAbordaje(Request $request)
    {
        try {
            $reservaId = $request->input('reserva_id');
            $observaciones = $request->input('observaciones');

            $reserva = Reserva::find($reservaId);

            if (!$reserva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reserva no encontrada',
                    'tipo_alerta' => 'error'
                ]);
            }

            if ($reserva->estado === 'cancelada') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede confirmar abordaje. La reserva está cancelada.',
                    'tipo_alerta' => 'error'
                ]);
            }

            if ($reserva->abordado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta reserva ya confirmó su abordaje anteriormente.',
                    'tipo_alerta' => 'warning'
                ]);
            }

            // Verificar menor extranjero
            if ($reserva->es_menor) {
                $paisPasajero = $reserva->para_tercero
                    ? $reserva->tercero_pais
                    : ($reserva->user->pais ?? 'Honduras');

                $esHondureno = strtolower(trim($paisPasajero)) === 'honduras';

                if (!$esHondureno) {
                    $autorizacion = Autorizacion::where('reserva_id', $reserva->id)
                        ->where('estado', 'aprobada')
                        ->first();

                    if (!$autorizacion) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No se puede confirmar abordaje. El menor extranjero no tiene autorización aprobada.',
                            'tipo_alerta' => 'error'
                        ]);
                    }
                }
            }

            $reserva->estado = 'confirmada';
            $reserva->abordado = true;
            $reserva->fecha_abordaje = now();

            if ($observaciones) {
                $reserva->observaciones_abordaje = $observaciones;
            }

            $reserva->save();

            $mensajeExtra = '';
            if ($reserva->es_menor) {
                $paisPasajero = $reserva->para_tercero
                    ? $reserva->tercero_pais
                    : ($reserva->user->pais ?? 'Honduras');
                $esHondureno = strtolower(trim($paisPasajero)) === 'honduras';

                $mensajeExtra = $esHondureno
                    ? ' (Menor hondureño)'
                    : ' (Menor extranjero con autorización válida)';
            }

            return response()->json([
                'success' => true,
                'message' => '¡Abordaje confirmado exitosamente!' . $mensajeExtra,
                'tipo_alerta' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'tipo_alerta' => 'error'
            ]);
        }
    }

    /**
     * Historial de abordajes
     */
    public function index()
    {
        return view('abordajes.checkin');
    }

    public function historial()
    {
        $reservas = Reserva::with(['user', 'viaje.origen', 'viaje.destino', 'tipoServicio'])
            ->where('abordado', true)
            ->orderBy('fecha_abordaje', 'desc')
            ->paginate(20);

        return view('abordajes.historial', compact('reservas'));
    }
}
