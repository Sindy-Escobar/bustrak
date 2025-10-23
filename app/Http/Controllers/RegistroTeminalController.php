<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroTerminal;
use Illuminate\Validation\Rule;

class RegistroTeminalController extends Controller
{

    private $departamentosHonduras = [
        'Atlántida', 'Colón', 'Comayagua', 'Copán', 'Cortés', 'Choluteca',
        'El Paraíso', 'Francisco Morazán', 'Gracias a Dios', 'Intibucá',
        'Islas de la Bahía', 'La Paz', 'Lempira', 'Ocotepeque', 'Olancho',
        'Santa Bárbara', 'Valle', 'Yoro'
    ];

    /**

     */
    public function index()
    {
        $terminales = RegistroTerminal::all();
        return view('terminales.index', compact('terminales'));
    }

    /**

     */
    public function create()
    {
        $departamentos = $this->departamentosHonduras;
        return view('terminales.create', compact('departamentos'));
    }

    /**

     */
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

    /**

     */
    public function show(RegistroTerminal $terminal)
    {
        return view('terminales.show', compact('terminal'));
    }


    /**
     *
     */
    public function edit(RegistroTerminal $terminal)
    {
        $departamentos = $this->departamentosHonduras;
        return view('terminales.edit', compact('terminal', 'departamentos'));
    }

    /**
     *
     */
    public function update(Request $request, RegistroTerminal $terminal)
    {
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

        $terminal->update($validatedData);
        return redirect()->route('terminales.index')->with('success', 'Terminal actualizada correctamente.');

    }

    /**
     *
     */
    public function destroy()
    {

    }
}
