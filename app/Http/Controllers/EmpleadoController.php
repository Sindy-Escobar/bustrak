<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    /** =====================================================
     * DASHBOARD DE EMPLEADO
     * ===================================================== */
    public function dashboard()
    {
        $user = auth()->user();
        if ($user->role !== 'Empleado') {
            abort(403, 'Acceso denegado: solo empleados pueden acceder.');
        }

        return view('empleados.dashboard');
    }

    /** =====================================================
     * LISTADO DE EMPLEADOS (Administrativo)
     * ===================================================== */
    public function index(Request $request)
    {
        $query = Empleado::query();

        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%$buscar%")
                    ->orWhere('apellido', 'like', "%$buscar%")
                    ->orWhere('cargo', 'like', "%$buscar%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('rol')) {
            $query->where('rol', $request->input('rol'));
        }

        if ($request->filled('fecha_registro')) {
            $query->whereDate('fecha_ingreso', $request->input('fecha_registro'));
        }

        $empleados = $query->orderBy('nombre')->paginate(10);

        return view('empleados.index_hu5', compact('empleados'));
    }

    /** =====================================================
     * CREAR EMPLEADO
     * ===================================================== */
    public function create()
    {
        return view('empleados.create');
    }

    /** =====================================================
     * GUARDAR NUEVO EMPLEADO
     * ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'apellido' => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'dni' => 'required|digits:13|unique:empleados,dni',
            'cargo' => 'required|string|max:50|regex:/^[\pL\s]+$/u',
            'fecha_ingreso' => 'required|date',
            'rol' => 'required|in:Empleado,Administrador',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.max' => 'El apellido no puede exceder los 100 caracteres.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.digits' => 'El DNI debe tener exactamente 13 dígitos.',
            'dni.unique' => 'Este DNI ya está registrado con otro empleado.',
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.max' => 'El cargo no puede exceder los 50 caracteres.',
            'cargo.regex' => 'El cargo solo puede contener letras y espacios.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'foto.required' => 'La foto es obligatoria.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser de tipo jpg, jpeg o png.',
            'foto.max' => 'La imagen no puede exceder los 2MB.',
        ]);

        // Guardar foto
        $fotoPath = $request->file('foto')->store('empleados', 'public');

        // Generar email único
        $baseEmail = strtolower(str_replace(' ', '', $request->nombre . '.' . $request->apellido));
        $email = $baseEmail . '@bustrak.com';
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . '@bustrak.com';
            $counter++;
        }

        // Contraseña inicial
        $password_initial = Str::random(8);

        // Crear empleado
        $empleado = Empleado::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'dni' => $request->dni,
            'cargo' => $request->cargo,
            'fecha_ingreso' => $request->fecha_ingreso,
            'rol' => ucfirst(strtolower($request->rol)),
            'estado' => 'Activo',
            'foto' => $fotoPath,
            'email' => $email,
            'password_initial' => $password_initial,
        ]);

        // Crear usuario asociado
        User::create([
            'name' => "{$empleado->nombre} {$empleado->apellido}",
            'email' => $email,
            'password' => Hash::make($password_initial),
            'role' => $empleado->rol,
            'estado' => 'activo',
        ]);

        // Redirigir a index_hu5 con mensaje de éxito
        return redirect()->route('empleados.hu5')
            ->with('success', "¡Registro exitoso! Correo: $email | Contraseña: $password_initial");
    }

    /** =====================================================
     * ACTUALIZAR EMPLEADO
     * ===================================================== */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'apellido' => 'required|string|max:100|regex:/^[\pL\s]+$/u',
            'dni' => "required|digits:13|unique:empleados,dni,{$id}",
            'cargo' => 'required|string|max:50|regex:/^[\pL\s]+$/u',
            'fecha_ingreso' => 'required|date',
            'rol' => 'required|in:Empleado,Administrador',
            'estado' => 'required|in:Activo,Inactivo',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellido.required' => 'El apellido es obligatorio.',
            'apellido.max' => 'El apellido no puede exceder los 100 caracteres.',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.digits' => 'El DNI debe tener exactamente 13 dígitos.',
            'dni.unique' => 'Este DNI ya está registrado con otro empleado.',
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.max' => 'El cargo no puede exceder los 50 caracteres.',
            'cargo.regex' => 'El cargo solo puede contener letras y espacios.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date' => 'La fecha de ingreso debe ser una fecha válida.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser de tipo jpg, jpeg o png.',
            'foto.max' => 'La imagen no puede exceder los 2MB.',
        ]);

        $data = $request->only(['nombre', 'apellido', 'dni', 'cargo', 'fecha_ingreso', 'rol', 'estado']);

        if ($request->hasFile('foto')) {
            if ($empleado->foto && Storage::disk('public')->exists($empleado->foto)) {
                Storage::disk('public')->delete($empleado->foto);
            }
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        }

        $empleado->update($data);

        // Actualizar usuario relacionado
        $user = User::where('email', $empleado->email)->first();
        if ($user) {
            $user->update([
                'name' => "{$empleado->nombre} {$empleado->apellido}",
                'role' => $empleado->rol,
                'estado' => strtolower($empleado->estado),
            ]);
        }

        return redirect()->route('empleados.hu5')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    /** =====================================================
     * DESACTIVAR / ACTIVAR EMPLEADO
     * ===================================================== */
    public function formDesactivar($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('empleados.desactivar', compact('empleado'));
    }

    public function guardarDesactivacion(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate(['motivo_baja' => 'required|string|max:255']);

        $empleado->update([
            'estado' => 'Inactivo',
            'motivo_baja' => $request->motivo_baja,
            'fecha_desactivacion' => Carbon::now(),
        ]);

        $user = User::where('email', $empleado->email)->first();
        if ($user) $user->update(['estado' => 'inactivo']);

        return redirect()->route('empleados.hu5')
            ->with('success', 'Empleado desactivado correctamente.');
    }

    public function activar($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update([
            'estado' => 'Activo',
            'motivo_baja' => null,
            'fecha_desactivacion' => null,
        ]);

        $user = User::where('email', $empleado->email)->first();
        if ($user) $user->update(['estado' => 'activo']);

        return redirect()->route('empleados.hu5')
            ->with('success', 'Empleado activado correctamente.');
    }

    /** =====================================================
     * FUNCIONES ADICIONALES PARA EMPLEADO
     * ===================================================== */
    public function viajes()
    {
        if (auth()->user()->role !== 'Empleado') abort(403);
        return view('empleado.viajes');
    }

    public function pasajeros()
    {
        if (auth()->user()->role !== 'Empleado') abort(403);
        return view('empleado.pasajeros');
    }

    public function confirmar()
    {
        if (auth()->user()->role !== 'Empleado') abort(403);
        return view('empleado.confirmar');
    }

    public function qr()
    {
        if (auth()->user()->role !== 'Empleado') abort(403);
        return view('empleado.qr');
    }
}
