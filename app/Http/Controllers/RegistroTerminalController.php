<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroTerminal;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Models\Servicio;
use App\Models\Bus;

class RegistroTerminalController extends Controller
{
    private $departamentosHonduras = [
        'Atlántida', 'Colón', 'Comayagua', 'Copán', 'Cortés', 'Choluteca',
        'El Paraíso', 'Francisco Morazán', 'Gracias a Dios', 'Intibucá',
        'Islas de la Bahía', 'La Paz', 'Lempira', 'Ocotepeque', 'Olancho',
        'Santa Bárbara', 'Valle', 'Yoro'
    ];

    private $municipiosHonduras = [
        'Atlántida' => ['La Ceiba', 'El Porvenir', 'Esparta', 'Jutiapa', 'La Másica', 'San Francisco', 'Tela', 'Arizona'],
        'Colón' => ['Trujillo', 'Balfate', 'Iriona', 'Limón', 'Sabá', 'Santa Fe', 'Santa Rosa de Aguán', 'Sonaguera', 'Tocoa', 'Bonito Oriental'],
        // … resto igual …
    ];

    public function index(Request $request)
    {
        $query = RegistroTerminal::query()
            ->with(['buses' => fn ($q) => $q->orderBy('numero_bus')])
            ->withCount('buses')
            ->withSum('buses as capacidad_total', 'capacidad_asientos');

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

        $terminales = $query->paginate(5)->withQueryString();

        return view('terminales.index', compact('terminales'))
            ->with('nombre', $request->nombre)
            ->with('contacto', $request->contacto)
            ->with('ubicacion', $request->ubicacion);
    }

    public function create()
    {
        $departamentos = $this->departamentosHonduras;
        $municipiosHonduras = $this->municipiosHonduras;
        $buses = Bus::whereNull('terminal_id')->orderBy('numero_bus')->get();

        return view('terminales.create', compact('departamentos', 'municipiosHonduras', 'buses'));
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
            'telefono' => 'required|string|max:8|regex:/^[983]\d{7}$/|unique:registro_terminal,telefono',
            'correo' => 'required|email:rfc,dns|max:50|unique:registro_terminal,correo|regex:/^\S.*$/',
            'horario_apertura' => 'required|date_format:H:i',
            'horario_cierre' => 'required|date_format:H:i|after:horario_apertura',
            'descripcion' => 'required|string',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'bus_ids' => 'nullable|array',
            'bus_ids.*' => 'integer|distinct|exists:buses,id',
        ]);

        try {
            $terminal = RegistroTerminal::create($validatedData);

            if ($request->has('servicios')) {
                foreach ($request->servicios as $servicio) {
                    if (!empty($servicio['nombre'])) {
                        $terminal->servicios()->create([
                            'nombre'      => $servicio['nombre'],
                            'descripcion' => $servicio['descripcion'] ?? null,
                            'icono'       => 'fas fa-concierge-bell',
                            'activo'      => true,
                            'terminal_id' => $terminal->id,
                        ]);
                    }
                }
            }

            if (! empty($validatedData['bus_ids'])) {
                Bus::whereIn('id', $validatedData['bus_ids'])
                    ->whereNull('terminal_id')
                    ->update(['terminal_id' => $terminal->id]);
            }

            return redirect()->route('terminales.index')->with('success', 'Terminal creada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error FATAL al crear la terminal: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error del sistema al guardar: ' . $e->getMessage());
        }
    }

    public function edit(RegistroTerminal $terminal)
    {
        $departamentos = $this->departamentosHonduras;
        $municipiosHonduras = $this->municipiosHonduras;
        $terminal->load('buses');
        $buses = Bus::where(function ($query) use ($terminal) {
            $query->whereNull('terminal_id')
                ->orWhere('terminal_id', $terminal->id);
        })->orderBy('numero_bus')->get();

        return view('terminales.edit', compact('terminal', 'departamentos', 'municipiosHonduras', 'buses'));
    }

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
                'telefono' => 'required|string|max:8|regex:/^[983]\d{7}$/|unique:registro_terminal,telefono,' . $terminal->id,
                'correo' => 'required|email:rfc,dns|max:50|unique:registro_terminal,correo,' . $terminal->id . '|regex:/^\S.*$/',
                'horario_apertura' => 'required|date_format:H:i',
                'horario_cierre' => 'required|date_format:H:i|after:horario_apertura',
                'descripcion' => 'required|string',
                'latitud' => 'nullable|numeric|between:-90,90',
                'longitud' => 'nullable|numeric|between:-180,180',
                'bus_ids' => 'nullable|array',
                'bus_ids.*' => 'integer|distinct|exists:buses,id',
            ]);

            $terminal->update($validatedData);

            Bus::where('terminal_id', $terminal->id)->update(['terminal_id' => null]);
            if (! empty($validatedData['bus_ids'])) {
                Bus::whereIn('id', $validatedData['bus_ids'])
                    ->whereNull('terminal_id')
                    ->update(['terminal_id' => $terminal->id]);
            }

            return redirect()->route('terminales.index')->with('success', 'Terminal actualizada correctamente.');

        } catch (\Exception $e) {
            Log::error('Error FATAL al actualizar la terminal: ' . $e->getMessage());
            return back()->with('error', 'Error crítico del sistema: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy()
    {
        // Lógica de eliminación...
    }

    public function servicios(RegistroTerminal $terminal)
    {
        $servicios = $terminal->servicios()->activos()->ordenadosPorNombre()->paginate(10);
        return view('terminales.servicios.index', compact('terminal', 'servicios'));
    }

    public function storeServicio(Request $request, RegistroTerminal $terminal)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'icono'       => 'nullable|string|max:50',
        ]);

        $terminal->servicios()->create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'icono'       => $request->icono ?? 'fas fa-concierge-bell',
            'activo'      => true,
            'terminal_id' => $terminal->id,
        ]);

        return redirect()
            ->route('terminales.servicios', $terminal)
            ->with('success', 'Servicio eliminado correctamente.');
    }

    public function exportarPDF()
    {
        $terminales = RegistroTerminal::with('buses')
            ->withCount('buses')
            ->withSum('buses as capacidad_total', 'capacidad_asientos')
            ->orderBy('nombre')
            ->get();

        $pdf = \PDF::loadView('terminales.pdf', compact('terminales'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Listado_Terminales_' . now()->format('Y-m-d') . '.pdf');
    }
}
