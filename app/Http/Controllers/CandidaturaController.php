<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use Illuminate\Http\Request;

/**
 * Class CandidaturaController
 * @package App\Http\Controllers
 */
class CandidaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidaturas = Candidatura::paginate(10);

        return view('candidatura.index', compact('candidaturas'))
            ->with('i', (request()->input('page', 1) - 1) * $candidaturas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $candidatura = new Candidatura();
        return view('candidatura.create', compact('candidatura'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Candidatura::$rules);

        $candidatura = Candidatura::create($request->all());

        return redirect()->route('candidaturas.index')
            ->with('success', 'Candidatura created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidatura = Candidatura::find($id);

        return view('candidatura.show', compact('candidatura'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidatura = Candidatura::find($id);

        return view('candidatura.edit', compact('candidatura'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Candidatura $candidatura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidatura $candidatura)
    {
        request()->validate(Candidatura::$rules);

        $candidatura->update($request->all());

        return redirect()->route('candidaturas.index')
            ->with('success', 'Candidatura updated successfully');
    }

    /**
     * 
     * Se debe modificar antes de destruir la clase.
     * 
     */

    /**
     * Muestra todas las candidaturas del usuario con filtros opcionales
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function candidaturasUsuario(Request $request)
    {
        // Validar que el token_user esté presente
        $request->validate([
            'token_user' => 'required|string',
            'estado' => 'nullable|string|in:aprobada,pendiente,rechazada,otro', // ajusta según tus estados
            'orden' => 'nullable|string|in:asc,desc' // opcional: orden ascendente o descendente
        ]);

        // Obtener el token del usuario desde la solicitud
        $tokenUser = $request->input('token_user');
        
        // Construir la consulta base
        $query = Candidatura::with(['empresa', 'vacante'])
            ->where('token_user', $tokenUser)
            ->orderBy('created_at', $request->input('orden', 'desc')); // por defecto: más reciente primero

        // Aplicar filtro por estado si se especificó
        if ($request->has('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        // Obtener resultados (paginados)
        $candidaturas = $query->paginate(10);

        // Devolver respuesta (ajusta según tu frontend)
        return response()->json([
            'success' => true,
            'data' => $candidaturas
        ]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $candidatura = Candidatura::find($id)->delete();

        return redirect()->route('candidaturas.index')
            ->with('success', 'Candidatura deleted successfully');
    }


}

