<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\AutorizacionMenor;
use App\Models\User;
use App\Models\Viaje;
use App\Models\Asiento;

class CheckinController extends Controller
{
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

            // ✅ VERIFICAR SI ES CÓDIGO DE AUTORIZACIÓN (empieza con AUT-)
            if (strpos($codigoLimpio, 'AUT-') === 0 || strpos($codigoLimpio, 'AUT') === 0) {
                $autorizacion = AutorizacionMenor::where('codigo_autorizacion', 'LIKE', '%' . $codigoLimpio . '%')
                    ->with('reserva.user', 'reserva.viaje', 'reserva.asiento')
                    ->first();

                if (!$autorizacion) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Código de autorización no encontrado. Asegúrate de escanear el código de RESERVA para el check-in.',
                        'tipo_alerta' => 'error'
                    ]);
                }

                // Usar la reserva asociada a la autorización
                $reserva = $autorizacion->reserva;
            } else {
                // ✅ ES CÓDIGO DE RESERVA NORMAL
                $reserva = Reserva::with(['user', 'viaje', 'asiento', 'autorizacionMenor'])
                    ->where('codigo_reserva', $codigoLimpio)
                    ->first();
            }

            if (!$reserva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reserva no encontrada o código QR inválido.',
                    'tipo_alerta' => 'error'
                ]);
            }

            // ✅ VALIDAR SI YA ABORDÓ
            if ($reserva->estado === 'confirmada' && $reserva->abordado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta reserva ya realizó el check-in anteriormente el ' .
                        ($reserva->fecha_abordaje ? $reserva->fecha_abordaje->format('d/m/Y H:i') : 'fecha no registrada'),
                    'tipo_alerta' => 'warning'
                ]);
            }

            // ✅ VERIFICAR SI ES MENOR Y TIENE AUTORIZACIÓN
            $alertaMenor = null;
            if ($reserva->es_menor) {
                $autorizacion = $reserva->autorizacionMenor;

                if (!$autorizacion) {
                    return response()->json([
                        'success' => false,
                        'message' => '⚠️ PASAJERO MENOR DE EDAD - NO TIENE AUTORIZACIÓN REGISTRADA. Debe completar la autorización antes de abordar.',
                        'tipo_alerta' => 'error',
                        'es_menor' => true,
                        'sin_autorizacion' => true
                    ]);
                }

                if (!$autorizacion->autorizado) {
                    return response()->json([
                        'success' => false,
                        'message' => '⚠️ PASAJERO MENOR DE EDAD - Autorización NO confirmada. Contacte al tutor.',
                        'tipo_alerta' => 'error',
                        'es_menor' => true,
                        'autorizacion_pendiente' => true
                    ]);
                }

                // Autorización válida
                $alertaMenor = [
                    'es_menor' => true,
                    'tutor_nombre' => $autorizacion->tutor_nombre,
                    'tutor_dni' => $autorizacion->tutor_dni,
                    'parentesco' => $autorizacion->parentesco,
                    'codigo_autorizacion' => $autorizacion->codigo_autorizacion,
                    'menor_dni' => $autorizacion->menor_dni,
                    'menor_edad' => \Carbon\Carbon::parse($autorizacion->menor_fecha_nacimiento)->age
                ];
            }

            $viaje = $reserva->viaje;
            $ciudadOrigen = \App\Models\Ciudad::find($viaje->ciudad_origen_id);
            $ciudadDestino = \App\Models\Ciudad::find($viaje->ciudad_destino_id);

            $datos = [
                'reserva_id' => $reserva->id,
                'codigo_qr' => $reserva->codigo_reserva,
                'pasajero' => [
                    'nombre_completo' => $reserva->user->name ?? 'N/A',
                    'dni' => $reserva->user->dni ?? 'N/A',
                    'email' => $reserva->user->email ?? 'N/A',
                    'telefono' => $reserva->user->telefono ?? 'N/A'
                ],
                'viaje' => [
                    'ruta' => ($ciudadOrigen ? $ciudadOrigen->nombre : 'N/A') . ' - ' . ($ciudadDestino ? $ciudadDestino->nombre : 'N/A'),
                    'origen' => $ciudadOrigen ? $ciudadOrigen->nombre : 'N/A',
                    'destino' => $ciudadDestino ? $ciudadDestino->nombre : 'N/A',
                    'fecha_salida' => $viaje->fecha_hora_salida ?? 'N/A',
                    'asiento' => $reserva->asiento->numero_asiento ?? 'N/A'
                ],
                'autorizacion_menor' => $alertaMenor // ✅ DATOS DEL MENOR
            ];

            $mensaje = $alertaMenor
                ? '✅ PASAJERO MENOR DE EDAD - Autorización VÁLIDA. Verificar DNI del tutor presente.'
                : 'Datos de reserva verificados correctamente';

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

            $reserva = Reserva::with('autorizacionMenor')->find($reservaId);

            if (!$reserva) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reserva no encontrada',
                    'tipo_alerta' => 'error'
                ]);
            }

            // Verificar nuevamente si es menor
            if ($reserva->es_menor) {
                $autorizacion = $reserva->autorizacionMenor;
                if (!$autorizacion || !$autorizacion->autorizado) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede confirmar abordaje. El menor no tiene autorización válida.',
                        'tipo_alerta' => 'error'
                    ]);
                }
            }

            // Confirmar abordaje
            $reserva->estado = 'confirmada';
            $reserva->abordado = true;
            $reserva->fecha_abordaje = now();
            $reserva->save();

            return response()->json([
                'success' => true,
                'message' => '¡Abordaje confirmado exitosamente!' .
                    ($reserva->es_menor ? ' (Menor de edad con autorización válida)' : ''),
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
    public function historial()
    {
        $reservas = Reserva::with(['user', 'viaje', 'autorizacionMenor'])
            ->where('abordado', true)
            ->orderBy('fecha_abordaje', 'desc')
            ->get();

        return view('abordajes.historial', compact('reservas'));
    }
}
