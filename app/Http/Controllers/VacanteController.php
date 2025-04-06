<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Vacante;
use Illuminate\Http\Request;

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
}
