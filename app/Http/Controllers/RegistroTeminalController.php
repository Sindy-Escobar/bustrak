<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// ✅ Usamos el nombre correcto del Modelo
use App\Models\RegistroTerminal;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

//  NOMBRE DE CLASE CORREGIDO A 'RegistroTerminalController'
class RegistroTeminalController extends Controller
{
    private $departamentosHonduras = [
        'Atlántida', 'Colón', 'Comayagua', 'Copán', 'Cortés', 'Choluteca',
        'El Paraíso', 'Francisco Morazán', 'Gracias a Dios', 'Intibucá',
        'Islas de la Bahía', 'La Paz', 'Lempira', 'Ocotepeque', 'Olancho',
        'Santa Bárbara', 'Valle', 'Yoro'
    ];

    private $municipiosHonduras = [
        'Atlántida' => ['La Ceiba', 'Tela', 'Arizona', 'Jutiapa', 'La Masica', 'San Francisco', 'El Porvenir', 'Esparta'],
        'Colón' => ['Trujillo', 'Limón', 'Sabaletas', 'Tocoa', 'Balfate', 'Iriona', 'Santa Fe', 'Sonaguera'],
        'Comayagua' => ['Comayagua', 'Siguatepeque', 'Ajuterique', 'Esquías', 'La Libertad', 'La Villa de San Antonio', 'Lamaní', 'Las Lajas'],
        'Copán' => ['Santa Rosa de Copán', 'Corquín', 'Dolores', 'Florida', 'La Unión', 'Nueva Arcadia', 'San Pedro de Copán', 'Veracruz'],
        'Cortés' => ['San Pedro Sula', 'Choloma', 'Puerto Cortés', 'Villanueva', 'La Lima', 'Omoa', 'Potrerillos', 'San Manuel', 'Santa Cruz de Yojoa'],
        'Choluteca' => ['Choluteca', 'San Marcos de Colón', 'Concepción de María', 'El Triunfo', 'Marcovia', 'Pespire', 'San Antonio de Flores'],
        'El Paraíso' => ['Yuscarán', 'Danlí', 'El Paraíso', 'Teupasenti', 'Morocelí', 'Potrerillos', 'Texiguat'],
        'Francisco Morazán' => ['Distrito Central (Tegucigalpa y Comayagüela)', 'Talanga', 'Valle de Ángeles', 'Cantarranas', 'Curarén', 'Marale', 'Ojojona', 'Santa Lucía'],
        'Gracias a Dios' => ['Puerto Lempira', 'Ahuas', 'Juan Francisco Bulnes', 'Villeda Morales', 'Wampusirpi'],
        'Intibucá' => ['La Esperanza', 'Intibucá', 'Jesús de Otoro', 'Camasca', 'San Francisco de Opalaca', 'Yamaranguila'],
        'Islas de la Bahía' => ['Roatán', 'Guanaja', 'Útila', 'Santos Guardiola'],
        'La Paz' => ['La Paz', 'Marcala', 'Aguanqueterique', 'Guajiquiro', 'Mercedes de Oriente', 'San José'],
        'Lempira' => ['Gracias', 'La Campa', 'Belén', 'Erandique', 'La Iguala', 'San Manuel de Colohete', 'Talgua'],
        'Ocotepeque' => ['Ocotepeque', 'Belén Gualcho', 'Fraternidad', 'La Labor', 'San Fernando', 'Sinuapa'],
        'Olancho' => ['Juticalpa', 'Catacamas', 'Campamento', 'Guarizama', 'Manguilile', 'Patuca', 'San Francisco de la Paz'],
        'Santa Bárbara' => ['Santa Bárbara', 'Ilama', 'Azacualpa', 'Chinda', 'Concepción del Norte', 'Trinidad', 'Petoa'],
        'Valle' => ['Nacaome', 'Alianza', 'Amapala', 'San Lorenzo', 'Aramecina', 'Caridad', 'Langue'],
        'Yoro' => ['Yoro', 'El Progreso', 'Olanchito', 'Santa Rita', 'Morazán', 'El Negrito', 'Sulaco'],
    ];


    public function index(Request $request)
    {
        $query = RegistroTerminal::query();

        // Filtros de búsqueda
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('contacto')) {
            $query->where(function ($q) use ($request) {
                $q->where('telefono', 'like', '%' . $request->contacto . '%')
                    ->orWhere('correo', 'like', '%' . $request->contacto . '%');
            });
        }

        if ($request->filled('ubicacion')) {
            $query->where(function ($q) use ($request) {
                $q->where('departamento', 'like', '%' . $request->ubicacion . '%')
                    ->orWhere('municipio', 'like', '%' . $request->ubicacion . '%')
                    ->orWhere('direccion', 'like', '%' . $request->ubicacion . '%');
            });
        }

        // Paginación
        $terminales = $query->paginate(5);

        return view('terminales.index', compact('terminales'))
            ->with('nombre', $request->nombre)
            ->with('contacto', $request->contacto)
            ->with('ubicacion', $request->ubicacion);
    }


    public function create()
    {
        $departamentos = $this->departamentosHonduras;
        $municipiosHonduras = $this->municipiosHonduras; // Nombre de variable para JS
        return view('terminales.create', compact('departamentos', 'municipiosHonduras'));
    }

    public function store(Request $request)
    {
        // ... (Validación y Store logic - NO CAMBIA) ...
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100|regex:/^\S.*$/',
            'codigo' => 'required|string|max:10|unique:registro_terminal,codigo|regex:/^\S.*$/',
            'direccion' => 'required|string|max:150|regex:/^\S.*$/',

            'departamento' => [
                'required',
                'string',
                Rule::in($this->departamentosHonduras),
            ],
            'municipio' => 'required|string|max:50|regex:/^\S.*$/',

            'telefono' => 'required|string|max:8|regex:/^[983]\d{7}$/|unique:registro_terminal,telefono',

            'correo' => 'required|email:rfc,dns|max:50|unique:registro_terminal,correo|regex:/^\S.*$/',

            'horario_apertura' => 'required|date_format:H:i',
            'horario_cierre' => 'required|date_format:H:i|after:horario_apertura',
            'descripcion' => 'required|string',
        ]);

        try {
            RegistroTerminal::create($validatedData);

            return redirect()->route('terminales.index')->with('success', 'Terminal creada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error FATAL al crear la terminal: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Error del sistema al guardar. Detalle técnico: ' . $e->getMessage());
        }
    }

    public function show(RegistroTerminal $terminal)
    {
        return view('terminales.show', compact('terminal'));
    }

    /**
     * Muestra el formulario de edición de la terminal.
     */
    public function edit(RegistroTerminal $terminal) // ✅ Inyección de modelo directamente (Route Model Binding)
    {
        $departamentos = $this->departamentosHonduras;
        $municipiosHonduras = $this->municipiosHonduras;

        return view('terminales.edit', compact('terminal', 'departamentos', 'municipiosHonduras'));
    }


    /**
     * Actualiza la terminal en la base de datos.
     */
    public function update(Request $request, RegistroTerminal $terminal) // ✅ Inyección de modelo (Route Model Binding)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:100|regex:/^\S.*$/',
                'codigo' => 'required|string|max:10|unique:registro_terminal,codigo,' . $terminal->id . '|regex:/^\S.*$/',
                'direccion' => 'required|string|max:150|regex:/^\S.*$/',

                'departamento' => [
                    'required',
                    'string',
                    Rule::in($this->departamentosHonduras),
                ],
                'municipio' => 'required|string|max:50|regex:/^\S.*$/',

                'telefono' => 'required|string|max:8|regex:/^[983]\d{7}$/|unique:registro_terminal,telefono,' . $terminal->id,

                'correo' => 'required|email:rfc,dns|max:50|unique:registro_terminal,correo,' . $terminal->id . '|regex:/^\S.*$/',

                'horario_apertura' => 'required|date_format:H:i',
                'horario_cierre' => 'required|date_format:H:i|after:horario_apertura',

                'descripcion' => 'required|string',
            ]);

            $terminal->update($validatedData);
            return redirect()->route('terminales.index')->with('success', 'Terminal actualizada correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error FATAL al actualizar la terminal (ID ' . $terminal->id . '): ' . $e->getMessage());
            return back()->with('error', 'Error crítico del sistema al actualizar. Detalles registrados en el log de Laravel.')->withInput();
        }
    }

    public function destroy()
    {
        // Lógica de eliminación...
    }


}
