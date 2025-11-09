<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroTerminal;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // <-- ¡NUEVA IMPORTACIÓN CLAVE!

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


    public function index()
    {
        $terminales = RegistroTerminal::paginate(5);
        return view('terminales.index', compact('terminales'));
    }

    public function create()
    {
        $departamentos = $this->departamentosHonduras;
        $municipios = $this->municipiosHonduras;
        return view('terminales.create', compact('departamentos', 'municipios'));
    }

    public function store(Request $request)
    {
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

            'telefono' => 'required|string|max:8|regex:/^[983]\d{7}$/',
            'correo' => 'required|email:rfc,dns|max:50|unique:registro_terminal,correo|regex:/^\S.*$/',

            'horario_apertura' => 'required|date_format:H:i',
            'horario_cierre' => 'required|date_format:H:i|after:horario_apertura',

            'descripcion' => 'required|string',
        ]);

        RegistroTerminal::create($validatedData);

        return redirect()->route('terminales.index')->with('success', 'Terminal creada correctamente.');
    }

    public function show(RegistroTerminal $terminal)
    {
        return view('terminales.show', compact('terminal'));
    }

    public function edit($id)
    {
        $terminal = RegistroTerminal::findOrFail($id);
        $departamentos = $this->departamentosHonduras;
        $municipios = $this->municipiosHonduras;

        return view('terminales.edit', compact('terminal', 'departamentos', 'municipios'));
    }


    /**
     * Actualiza la terminal en la base de datos con manejo de errores forzado.
     */
    public function update(Request $request, RegistroTerminal $terminal)
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

                'telefono' => 'required|string|max:8|regex:/^[983]\d{7}$/',

                'correo' => 'required|email:rfc,dns|max:50|unique:registro_terminal,correo,' . $terminal->id . '|regex:/^\S.*$/',

                'horario_apertura' => 'required|date_format:H:i',
                'horario_cierre' => 'required|date_format:H:i|after:horario_apertura',

                'descripcion' => 'required|string',
            ]);

            // Si llegamos aquí, la validación pasó.
            $terminal->update($validatedData);
            return redirect()->route('terminales.index')->with('success', 'Terminal actualizada correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Error de validación: Laravel lo maneja automáticamente.
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Error CRÍTICO (por ejemplo, error de base de datos o columna):
            Log::error('Error FATAL al actualizar la terminal (ID ' . $terminal->id . '): ' . $e->getMessage());
            return back()->with('error', 'Error crítico del sistema al actualizar. Detalles registrados en el log de Laravel.')->withInput();
        }
    }

    public function destroy()
    {
        // Lógica de eliminación...
    }

    public function ver_terminales(Request $request)
    {
        $search = $request->get('search');
        $estado = $request->get('estado');
        // ... (rest of the method)
        $query = RegistroTerminal::query();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('municipio', 'like', "%{$search}%");
            });
        }
        if ($estado) {
            $query->where('estado', $estado);
        }
        $terminales = $query->orderBy('nombre')->paginate(10)->withQueryString();
        return view('terminales.ver_terminales', compact('terminales', 'search', 'estado'));
    }
}
