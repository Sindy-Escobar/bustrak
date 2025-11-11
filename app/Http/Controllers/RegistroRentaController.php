<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroRenta;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Añadido para transacciones

class RegistroRentaController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        if (!$query) {
            return response()->json([]);
        }

        // Buscar clientes cuyo nombre_completo (o 'name') contenga el texto de la consulta
        // ¡Asegúrate de cambiar 'nombre_completo' por el nombre del campo de tu tabla!
        $clientes = Usuario::where('nombre_completo', 'LIKE', "%{$query}%")
            ->limit(10) // Limita resultados
            ->get(['id', 'nombre_completo']); // Solo necesitamos el ID y el Nombre

        return response()->json($clientes);
    }

    /**
     * Mostrar listado de rentas
     */
    public function index()
    {
        $rentas = RegistroRenta::orderBy('id', 'desc')->paginate(10);
        return view('rentas.index', compact('rentas'));
    }

    /**
     * Mostrar formulario para crear una nueva renta
     */
    public function create()
    {
        $usuarios = Usuario::all();
        return view('rentas.create', compact('usuarios'));
    }


    /**
     * Guardar nueva renta en base de datos.
     * * Implementa la lógica de buscar/crear el cliente (Usuario) con el ID manual de 13 dígitos.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Validar todos los datos (cliente + renta)
        $data = $request->validate([
            // --- VALIDACIÓN DE CLIENTE NUEVO/EXISTENTE ---
            'nombre_cliente' => 'required|string|max:255',
            'email_cliente' => 'required|string|max:255',
            'id_cliente' => 'nullable|string|max:255',

            // CORRECCIÓN 1 (validation.integer): Ahora se valida como una cadena de 13 dígitos numéricos
            'usuario_id' => [
                'required',
                'string',
                'size:13',
                'regex:/^[0-9]+$/', // Solo acepta dígitos
            ],

            // --- VALIDACIÓN DE RENTA ---
            'tipo_evento' => 'required|string|max:50',
            'destino' => 'required|string|max:255',
            'punto_partida' => 'required|string|max:255',

            // CORRECCIÓN 2 (validation.after_or_equal): Se mantiene la lógica de fechas
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',

            // CORRECCIÓN 1 (validation.integer): Usamos 'numeric' para montos monetarios
            'tarifa' => 'required|numeric|min:0.01',
            'descuento_valor' => 'nullable|numeric|min:0|max:100', // Porcentaje
            'anticipo' => 'nullable|numeric|min:0',

            // Pasajeros: Se mantienen como enteros
            'num_pasajeros_confirmados' => 'nullable|integer|min:0|max:500',
            'num_pasajeros_estimados' => 'nullable|integer|min:1|max:500',

            'hora_salida' => 'nullable|date_format:H:i',
            'hora_retorno' => 'nullable|date_format:H:i',
        ]);

        // Usamos una transacción para asegurar la integridad de los datos
        try {
            DB::beginTransaction();

            // 2. BUSCAR o CREAR el cliente (Usuario)
            $usuarioId = $data['usuario_id'];

            // Buscar si el cliente ya existe con ese ID de 13 dígitos
            $cliente = Usuario::find($usuarioId);

            if (!$cliente) {
                // Si el cliente NO existe, lo creamos con el ID manual
                $cliente = Usuario::create([
                    'id' => $usuarioId, // Establece el ID de 13 dígitos (Asumimos que es la PK)
                    'nombre_completo' => $data['nombre_cliente'],
                    // ASUMIDO: tu tabla 'usuarios' tiene el campo 'email_o_contacto'
                    'email_o_contacto' => $data['email_cliente'],
                    // Si tienes más campos obligatorios en Usuario, añádelos aquí
                ]);
            } else {
                // Si el cliente EXISTE, actualizamos sus datos de contacto
                $cliente->update([
                    'nombre_completo' => $data['nombre_cliente'],
                    'email_o_contacto' => $data['email_cliente'],
                ]);
            }

            // 3. Preparar datos financieros
            $tarifa = $data['tarifa'];
            $descuentoPorcentaje = $data['descuento_valor'] ?? 0;
            $descuentoMonto = $tarifa * ($descuentoPorcentaje / 100);
            $totalCalculado = $tarifa - $descuentoMonto;

            // 4. Crear el registro de Renta
            $renta = RegistroRenta::create([
                'usuario_id' => $cliente->id, // Usamos el ID del cliente creado/encontrado

                'nombre_completo' => $cliente->nombre_completo,
                'tipo_evento' => $data['tipo_evento'],
                'destino' => $data['destino'],
                'punto_partida' => $data['punto_partida'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'],

                // Asegurar valores por defecto en caso de ser nulos
                'num_pasajeros_confirmados' => $data['num_pasajeros_confirmados'] ?? 0,
                'num_pasajeros_estimados' => $data['num_pasajeros_estimados'] ?? 0,
                'hora_salida' => $data['hora_salida'] ?? null,
                'hora_retorno' => $data['hora_retorno'] ?? null,

                // Finanzas
                'tarifa' => $tarifa,
                'descuento' => $descuentoMonto,
                'total_tarifa' => $totalCalculado,
                'anticipo' => $data['anticipo'] ?? 0,
            ]);

            DB::commit();

            return redirect()->route('rentas.index')
                ->with('success', 'Renta registrada correctamente. ID de Cliente: ' . $cliente->id);

        } catch (\Exception $e) {
            DB::rollBack();

            // Muestra un error más genérico en caso de fallo de DB
            // En un entorno real, es mejor loguear el error completo.
            return back()->withErrors(['general' => 'Ocurrió un error al guardar la renta y el cliente: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Mostrar formulario para editar una renta existente
     */
    public function edit(RegistroRenta $renta)
    {
        $usuarios = Usuario::all(); // cargar usuarios también al editar
        return view('rentas.edit', compact('renta', 'usuarios'));
    }

    /**
     * Actualizar renta en base de datos
     */
    public function update(Request $request, RegistroRenta $renta)
    {
        $data = $request->validate([
            // Se asume que en Update se selecciona un usuario existente (PK del usuario)
            'usuario_id' => 'required|exists:usuarios,id',
            'nombre_completo' => 'required|string|max:255',
            'tipo_evento' => 'required|string|max:50',
            'destino' => 'required|string|max:255',
            'punto_partida' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',

            'num_pasajeros_confirmados' => 'nullable|integer|min:0|max:500',
            'num_pasajeros_estimados' => 'nullable|integer|min:1|max:500',

            // CORRECCIÓN 1 (validation.integer): Usamos 'numeric'
            'tarifa' => 'required|numeric|min:0.01',
            'descuento_valor' => 'nullable|numeric|min:0', // Si se envía monto
            'total_tarifa' => 'nullable|numeric|min:0', // Si se recalcula

            'estado' => 'required|string|max:50',
            'anticipo' => 'nullable|numeric|min:0',
            'hora_salida' => 'nullable',
            'hora_retorno' => 'nullable',
            'penalizacion' => 'nullable|numeric|min:0',
        ]);

        // Recalcular total y descuento
        $tarifa = $data['tarifa'];
        $descuentoMonto = $data['descuento_valor'] ?? 0;
        $data['total_tarifa'] = $tarifa - $descuentoMonto;
        $data['descuento'] = $descuentoMonto; // Asegurar que el descuento se guarde como monto

        // Eliminamos campos que no existen en la tabla o que son redundantes
        unset($data['descuento_valor']);

        $renta->update($data);

        return redirect()->route('rentas.index')
            ->with('success', 'Renta actualizada correctamente.');
    }

    /**
     * Eliminar una renta
     */
    public function destroy(RegistroRenta $renta)
    {
        $renta->delete();

        return redirect()->route('rentas.index')
            ->with('success', 'Renta eliminada correctamente.');
    }
}
