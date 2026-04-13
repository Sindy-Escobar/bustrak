<?php

namespace App\Http\Controllers;

use App\Models\SolicitudEmpleo;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class SolicitudEmpleoController extends Controller
{
    public function create()
    {
        return view('solicitudes.empleo');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|min:3|max:255',
            'contacto' => 'required|email',
            'puesto_deseado' => 'required|string|max:255',
            'experiencia_laboral' => 'required|string|min:10',
            'cv' => 'required|mimes:pdf,doc,docx|max:2048',
        ], [
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'contacto.required' => 'El correo es obligatorio.',
            'contacto.email' => 'Debe ser un correo válido.',
            'puesto_deseado.required' => 'El puesto deseado es obligatorio.',
            'experiencia_laboral.required' => 'La experiencia laboral es obligatoria.',
            'cv.required' => 'Debe adjuntar un CV.',
            'cv.mimes' => 'El CV debe ser PDF, DOC o DOCX.',
        ]);

        $cvUrl = null;
        if ($request->hasFile('cv')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_KEY'),
                    'api_secret' => env('CLOUDINARY_SECRET'),
                ],
                'url' => [
                    'secure' => true
                ]
            ]);

            $result = $cloudinary->uploadApi()->upload(
                $request->file('cv')->getRealPath(),
                [
                    'folder'        => 'solicitudes-empleo',
                    'resource_type' => 'raw',
                    'public_id'     => 'cv_' . auth()->id() . '_' . time(),
                ]
            );

            $cvUrl = $result['secure_url'];
        }

        SolicitudEmpleo::create([
            'user_id' => auth()->id(),
            'nombre_completo' => $request->nombre_completo,
            'contacto' => $request->contacto,
            'puesto_deseado' => $request->puesto_deseado,
            'experiencia_laboral' => $request->experiencia_laboral,
            'cv' => $cvUrl,
        ]);
        //  Notificar al admin
        $admin = \App\Models\User::where('role', 'Administrador')->first();
        if ($admin) {
            \App\Models\Notificacion::create([
                'usuario_id' => $admin->id,
                'titulo' => ' Nueva Solicitud de Empleo',
                'mensaje' => $request->nombre_completo . ' aplicó para el puesto de ' . $request->puesto_deseado,
                'tipo' => 'empleo',
                'leida' => false,
            ]);
        }
        return redirect()->route('solicitud.empleo.mis-solicitudes')
            ->with('success', ' ¡Solicitud de empleo enviada correctamente! Pronto nos pondremos en contacto contigo.');
    }

    public function misSolicitudes()
    {
        $solicitudes = SolicitudEmpleo::where('user_id', auth()->id())->latest()->get();
        return view('solicitudes.index-empleo', compact('solicitudes'));
    }

    public function descargarCV($id)
    {
        $solicitud = SolicitudEmpleo::findOrFail($id);

        if (!$solicitud->cv) {
            abort(404, 'Archivo no encontrado');
        }

        if (str_starts_with($solicitud->cv, 'http')) {
            return redirect($solicitud->cv);
        }

        $path = storage_path('app/public/' . $solicitud->cv);
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'El archivo no está disponible. Por favor sube una nueva solicitud.');
        }

        return response()->download($path);
    }

    public function aceptar($id)
    {
        $solicitud = SolicitudEmpleo::findOrFail($id);
        $solicitud->update(['estado' => 'Aceptada']);

        //  Notificación al usuario
        \App\Models\Notificacion::create([
            'usuario_id' => $solicitud->user_id,
            'titulo' => ' Solicitud de Empleo Aceptada',
            'mensaje' => 'Tu solicitud de empleo para el puesto de ' . $solicitud->puesto_deseado . ' ha sido aceptada.',
            'tipo' => 'empleo',
            'leida' => false,
        ]);

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud de empleo aceptada.');
    }

    public function rechazar($id)
    {
        $solicitud = SolicitudEmpleo::findOrFail($id);
        $solicitud->update(['estado' => 'Rechazada']);

        //  Notificación al usuario
        \App\Models\Notificacion::create([
            'usuario_id' => $solicitud->user_id,
            'titulo' => ' Solicitud de Empleo Rechazada',
            'mensaje' => 'Tu solicitud de empleo para el puesto de ' . $solicitud->puesto_deseado . ' ha sido rechazada.',
            'tipo' => 'empleo',
            'leida' => false,
        ]);

        return redirect()->route('solicitudes.index')
            ->with('success', 'Solicitud de empleo rechazada.');
    }
    public function eliminar($id)
    {
        $solicitud = SolicitudEmpleo::findOrFail($id);
        $solicitud->delete();
        return redirect()->back()->with('success', 'Solicitud eliminada correctamente.');
    }
}
