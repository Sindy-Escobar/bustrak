<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    /**
     * Muestra el formulario de soporte/ayuda
     */
    public function index()
    {
        return view('ayuda.soporte');
    }

    /**
     * Almacena una nueva consulta
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'asunto' => [
            'required',
            'string',
            'max:255',
            'regex:/^[A-Za-z0-9\s]+$/'
        ],
        'mensaje' => [
            'required',
            'string',
            'max:1000',
            'regex:/^[A-Za-z0-9\s]+$/'
        ]
    ], [
        'nombre.required' => 'El nombre es obligatorio',
        'correo.required' => 'El correo es obligatorio',
        'correo.email' => 'El correo debe ser válido',
        'asunto.required' => 'El asunto es obligatorio',
        'asunto.regex' => 'El asunto solo puede contener letras, números y espacios',
        'mensaje.required' => 'El mensaje es obligatorio',
        'mensaje.max' => 'El mensaje no puede exceder 1000 caracteres',
        'mensaje.regex' => 'El mensaje solo puede contener letras, números y espacios'
    ]);

        try {
            Consulta::create([
                'user_id' => auth()->id(),
                'nombre_completo' => $request->nombre,
                'correo' => $request->correo,
                'asunto' => $request->asunto,
                'mensaje' => $request->mensaje
            ]);
            //  Notificar al admin
            $admin = \App\Models\User::where('role', 'Administrador')->first();
            if ($admin) {
                \App\Models\Notificacion::create([
                    'usuario_id' => $admin->id,
                    'titulo' => ' Nueva Consulta de Usuario',
                    'mensaje' => 'El usuario ' . $request->nombre . ' envió una consulta: ' . $request->asunto,
                    'tipo' => 'consulta',
                    'leida' => false,
                ]);
            }

            return redirect()->back()->with('success', ' ¡Consulta enviada exitosamente! Te responderemos pronto.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', ' Hubo un error al enviar tu consulta. Intenta nuevamente.');
        }
    }

    /**
     * Lista todas las consultas (para panel admin)
     */
    public function listar()
    {
        $consultas = Consulta::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.consultas', compact('consultas'));
    }

    public function misConsultas()
    {
        $consultas = Consulta::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ayuda.indexh44', compact('consultas'));
    }
    public function eliminar($id)
    {
        $consulta = \App\Models\Consulta::findOrFail($id);
        $consulta->delete();
        return redirect()->back()->with('success', 'Consulta eliminada correctamente.');
    }

    /**
     * Guarda la respuesta del administrador a una consulta
     * y notifica al usuario que la envió.
     */
    public function responder(Request $request, $id)
    {
        $request->validate([
            'respuesta' => 'required|string|max:2000',
        ], [
            'respuesta.required' => 'La respuesta no puede estar vacía',
            'respuesta.max' => 'La respuesta no puede exceder 2000 caracteres',
        ]);

        $consulta = Consulta::findOrFail($id);

        $consulta->update([
            'respuesta' => $request->respuesta,
            'respondida_en' => now(),
            'respondida_por' => auth()->id(),
        ]);

        // Notificar al usuario que envió la consulta (si tiene cuenta registrada)
        if ($consulta->user_id) {
            \App\Models\Notificacion::create([
                'usuario_id' => $consulta->user_id,
                'titulo' => 'Respondieron tu consulta',
                'mensaje' => 'Soporte respondió tu consulta "' . $consulta->asunto . '": ' . $request->respuesta,
                'tipo' => 'sistema',
                'leida' => false,
            ]);
        }

        return redirect()->back()->with('success', 'Respuesta enviada correctamente.');
    }
}
