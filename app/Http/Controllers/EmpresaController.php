<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class EmpresaController
 * @package App\Http\Controllers
 */
class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = Empresa::paginate(10);

        return view('empresa.index', compact('empresas'))
            ->with('i', (request()->input('page', 1) - 1) * $empresas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresa = new Empresa();
        return view('empresa.create', compact('empresa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Empresa::$rules);

        $empresa = Empresa::create($request->all());

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Empresa::find($id);

        return view('empresa.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empresa = Empresa::find($id);

        return view('empresa.edit', compact('empresa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Empresa $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empresa $empresa)
    {
        request()->validate(Empresa::$rules);

        $empresa->update($request->all());

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $empresa = Empresa::find($id)->delete();

        return redirect()->route('empresas.index')
            ->with('success', 'Empresa deleted successfully');
    }

    // Crear nueva empresa
    public function crearEmpresa(Request $request)
    {
        $validated = $request->validate(Empresa::$rules);

        $empresa = Empresa::create($validated);

        return response()->json([
            'message' => 'Empresa creada correctamente',
            'empresa' => $empresa
        ], 201);
    }

    public function DetallesEmpresa($id, Request $request)
{
    // Buscar la empresa por su ID
    $empresa = Empresa::find($id);

    // Si no se encuentra la empresa, devolver un mensaje de error
    if (!$empresa) {
        return response()->json(['message' => 'Empresa no encontrada'], 404);
    }

    // Si la contraseña no es proporcionada o no es correcta
    if (!$request->has('password') || !Hash::check($request->input('password'), $empresa->contra)) {
        // Si no se pasa la contraseña o es incorrecta, devolver solo nombre, correo y ubicación
        $empresaDatos = [
            'nombre' => $empresa->nombre,
            'correo' => $empresa->correo,
            'ubicacion' => $empresa->ubicacion,
        ];
    } else {
        // Si la contraseña es correcta, devolver toda la información de la empresa
        $empresaDatos = $empresa;
    }

    // Retornar los datos de la empresa en formato JSON
    return response()->json($empresaDatos);
}

}
