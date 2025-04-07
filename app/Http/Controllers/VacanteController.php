<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Vacante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class VacanteController
 * @package App\Http\Controllers
 */
class VacanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vacantes = Vacante::paginate(10);

        return view('vacante.index', compact('vacantes'))
            ->with('i', (request()->input('page', 1) - 1) * $vacantes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vacante = new Vacante();
        $empresas = \App\Models\Empresa::orderBy('nombre')->get(); // Obtener todas las empresas
        return view('vacante.create', compact('vacante', 'empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Vacante::$rules);

        $vacante = Vacante::create($request->all());

        return redirect()->route('vacantes.index')
            ->with('success', 'Vacante created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vacante = Vacante::find($id);

        return view('vacante.show', compact('vacante'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vacante = Vacante::find($id);
        $empresas = \App\Models\Empresa::orderBy('nombre')->get(); // Obtener todas las empresas
        return view('vacante.edit', compact('vacante', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Vacante $vacante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vacante $vacante)
    {
        request()->validate(Vacante::$rules);

        $vacante->update($request->all());

        return redirect()->route('vacantes.index')
            ->with('success', 'Vacante updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $vacante = Vacante::find($id)->delete();

        return redirect()->route('vacantes.index')
            ->with('success', 'Vacante deleted successfully');
    }

    public function consultaVacantes(Request $request)
    {
        // Obtener el número de elementos por página (si no se proporciona, usa 10 por defecto)
        $perPage = $request->input('per_page', 10); // 'per_page' en la URL

        // Realizar la consulta con paginación y ordenar por 'updated_at' de mayor a menor
        $vacantes = Vacante::select(
            'vacantes.id',
            'vacantes.titulo',
            'vacantes.descripcion',
            'vacantes.sueldo',
            'vacantes.modalidad',
            'empresas.id as empresa_id',
            'empresas.nombre as empresa_nombre'
        )
            ->join('empresas', 'vacantes.empresa_id', '=', 'empresas.id')
            ->orderBy('vacantes.updated_at', 'desc') // Ordenar por 'updated_at' descendente
            ->paginate($perPage); // Usar el valor de per_page

        // Incluir la paginación en la respuesta
        return response()->json([
            'vacantes' => $vacantes->items(), // Resultados de la página actual
            'current_page' => $vacantes->currentPage(),
            'total_pages' => $vacantes->lastPage(),
            'total_results' => $vacantes->total(),
            'per_page' => $vacantes->perPage()
        ]);
    }
    public function consultaVacantePorId(Request $request, $id)
    {
        // Obtener el número de elementos por página (si no se proporciona, usa 10 por defecto)
        $perPage = $request->input('per_page', 10); // 'per_page' en la URL

        // Buscar la vacante por su ID
        $vacante = Vacante::select(
            'vacantes.titulo',
            'vacantes.descripcion',
            'vacantes.sueldo',
            'vacantes.modalidad',
            'empresas.id as empresa_id',
            'empresas.nombre as empresa_nombre'
        )
            ->join('empresas', 'vacantes.empresa_id', '=', 'empresas.id')
            ->where('vacantes.id', $id) // Filtrar por ID de vacante
            ->orderBy('vacantes.updated_at', 'desc') // Ordenar por 'updated_at' descendente
            ->paginate($perPage); // Usar el valor de per_page

        // Verificar si la vacante existe
        if ($vacante->isEmpty()) {
            return response()->json([
                'message' => 'Vacante no encontrada.'
            ], 404);
        }

        // Incluir la paginación en la respuesta
        return response()->json([
            'vacantes' => $vacante->items(), // Resultados de la página actual (solo debería ser una vacante)
            'current_page' => $vacante->currentPage(),
            'total_pages' => $vacante->lastPage(),
            'total_results' => $vacante->total(),
            'per_page' => $vacante->perPage()
        ]);
    }
    public function crearVacante(Request $request)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'correo' => 'required|email', // Correo de la empresa
            'contra' => 'required|string', // Contraseña de la empresa
            'titulo' => 'required|string|max:255', // Título de la vacante
            'descripcion' => 'required|string', // Descripción de la vacante
            'sueldo' => 'required|numeric', // Sueldo de la vacante
            'modalidad' => 'required|string', // Modalidad de la vacante (presencial, remoto, etc.)
        ]);

        // Buscar la empresa por correo electrónico
        $empresa = Empresa::where('correo', $validated['correo'])->first();

        // Verificar si la empresa existe
        if (!$empresa) {
            return response()->json([
                'message' => 'Empresa no encontrada.'
            ], 404);
        }

        // Verificar si la contraseña proporcionada coincide con la almacenada (usando bcrypt)
        if (!Hash::check($validated['contra'], $empresa->contra)) {
            return response()->json([
                'message' => 'La contraseña es incorrecta.'
            ], 401); // 401 para contraseña incorrecta
        }

        // Crear la vacante asociada a la empresa
        $vacante = new Vacante();
        $vacante->titulo = $validated['titulo'];
        $vacante->descripcion = $validated['descripcion'];
        $vacante->sueldo = $validated['sueldo'];
        $vacante->modalidad = $validated['modalidad'];
        $vacante->empresa_id = $empresa->id; // Asociamos la vacante con la empresa
        $vacante->save(); // Guardamos la vacante

        // Responder con éxito
        return response()->json([
            'message' => 'Vacante creada exitosamente.',
            'vacante' => $vacante
        ], 201); // 201 para recurso creado
    }

    public function actualizarVacante(Request $request, $id)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'correo' => 'required|email', // Correo de la empresa
            'contra' => 'required|string', // Contraseña de la empresa
            'titulo' => 'nullable|string|max:255', // Título de la vacante
            'descripcion' => 'nullable|string', // Descripción de la vacante
            'sueldo' => 'nullable|numeric', // Sueldo de la vacante
            'modalidad' => 'nullable|string', // Modalidad de la vacante (presencial, remoto, etc.)
        ]);

        // Buscar la empresa por correo electrónico
        $empresa = Empresa::where('correo', $validated['correo'])->first();

        // Verificar si la empresa existe
        if (!$empresa) {
            return response()->json([
                'message' => 'Empresa no encontrada.'
            ], 404);
        }

        // Verificar si la contraseña proporcionada coincide con la almacenada (usando bcrypt)
        if (!Hash::check($validated['contra'], $empresa->contra)) {
            return response()->json([
                'message' => 'La contraseña es incorrecta.'
            ], 401); // 401 para contraseña incorrecta
        }

        // Buscar la vacante por ID
        $vacante = Vacante::find($id);

        // Verificar si la vacante existe
        if (!$vacante) {
            return response()->json([
                'message' => 'Vacante no encontrada.'
            ], 404);
        }

        // Verificar si la vacante pertenece a la empresa
        if ($vacante->empresa_id != $empresa->id) {
            return response()->json([
                'message' => 'La vacante no pertenece a esta empresa.'
            ], 403); // 403 para acción no permitida
        }

        // Actualizar los campos de la vacante con los datos proporcionados
        $vacante->titulo = $validated['titulo'] ?? $vacante->titulo; // Mantener el valor anterior si no se proporciona uno nuevo
        $vacante->descripcion = $validated['descripcion'] ?? $vacante->descripcion;
        $vacante->sueldo = $validated['sueldo'] ?? $vacante->sueldo;
        $vacante->modalidad = $validated['modalidad'] ?? $vacante->modalidad;
        $vacante->save(); // Guardar los cambios

        // Responder con éxito
        return response()->json([
            'message' => 'Vacante actualizada exitosamente.',
            'vacante' => $vacante
        ], 200); // 200 para recurso actualizado
    }
    public function eliminarVacante(Request $request, $id)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'correo' => 'required|email', // Correo de la empresa
            'contra' => 'required|string', // Contraseña de la empresa
        ]);

        // Buscar la empresa por correo electrónico
        $empresa = Empresa::where('correo', $validated['correo'])->first();

        // Verificar si la empresa existe
        if (!$empresa) {
            return response()->json([
                'message' => 'Empresa no encontrada.'
            ], 404);
        }

        // Verificar si la contraseña proporcionada coincide con la almacenada (usando bcrypt)
        if (!Hash::check($validated['contra'], $empresa->contra)) {
            return response()->json([
                'message' => 'La contraseña es incorrecta.'
            ], 401); // 401 para contraseña incorrecta
        }

        // Buscar la vacante por ID
        $vacante = Vacante::find($id);

        // Verificar si la vacante existe
        if (!$vacante) {
            return response()->json([
                'message' => 'Vacante no encontrada.'
            ], 404);
        }

        // Verificar si la vacante pertenece a la empresa
        if ($vacante->empresa_id != $empresa->id) {
            return response()->json([
                'message' => 'La vacante no pertenece a esta empresa.'
            ], 403); // 403 para acción no permitida
        }

        // Eliminar la vacante
        $vacante->delete();

        // Responder con éxito
        return response()->json([
            'message' => 'Vacante eliminada exitosamente.'
        ], 200); // 200 para recurso eliminado exitosamente
    }
}
