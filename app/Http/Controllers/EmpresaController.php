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

    public function crearEmpresa(Request $request)
    {
        // Validar los datos que se recibirán para la creación de la empresa
        $validated = $request->validate(Empresa::$rules);

        // Verificar si ya existe una empresa con el mismo nombre o token_empresa (puedes modificar el campo según tus necesidades)
        $empresaExistente = Empresa::where('nombre', $validated['nombre'])
            ->orWhere('correo', $validated['correo'])
            ->first();

        if ($empresaExistente) {
            // Si la empresa ya existe, devolver un mensaje de error
            return response()->json(['message' => 'Ya existe una empresa con ese nombre, correo o token'], 409); // 409 es el código de respuesta para conflicto
        }

        // Crear la nueva empresa con los datos validados
        $empresa = Empresa::create($validated);

        // Devolver una respuesta JSON indicando que la empresa se ha creado correctamente
        return response()->json([
            'message' => 'Empresa creada correctamente',
            'empresa' => $empresa
        ], 201); // 201 es el código de respuesta para 'Creado'
    }


    public function DetallesEmpresa(Request $request)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'correo' => 'required|email',
            'contra' => 'nullable|string',
        ]);
        
        // Buscar la empresa por su correo
        $empresa = Empresa::where('correo', $validated['correo'])->first();

        // Si no se encuentra la empresa, devolver un mensaje de error
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        // Si la contraseña no es proporcionada o no es correcta
        if (!$request->has('contra') || !Hash::check($request->input('contra'), $empresa->contra)) {
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


    public function eliminarEmpresa(Request $request)
    {
        // Validar los datos del request
        $validated = $request->validate([
            'correo' => 'required|email',
            'contra' => 'required|string',
        ]);

        // Buscar la empresa por correo
        $empresa = Empresa::where('correo', $validated['correo'])->first();

        // Si la empresa no existe, devolver un mensaje de error
        if (!$empresa) {
            return response()->json(['message' => 'Empresa no encontrada'], 404);
        }

        // Verificar si la contraseña proporcionada es correcta
        if (!Hash::check($validated['contra'], $empresa->contra)) {
            return response()->json(['message' => 'La contraseña es incorrecta'], 401); // 401 Unauthorized
        }

        // Eliminar la empresa si las credenciales son correctas
        $empresa->delete();

        // Retornar respuesta exitosa
        return response()->json([
            'message' => 'Empresa eliminada correctamente'
        ], 200);
    }
}
