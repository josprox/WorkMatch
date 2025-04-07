<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use App\Models\Empresa;
use App\Models\Vacante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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
        // Obtener todas las empresas
        $empresas = \App\Models\Empresa::all();  // Cambia 'Empresa' por el nombre de tu modelo de empresas
        $candidatura = new Candidatura();
        
        // Pasar la variable $empresas a la vista
        return view('candidatura.create', compact('candidatura', 'empresas'));
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
    $candidatura = Candidatura::findOrFail($id);
    $empresas = \App\Models\Empresa::all(); // Cargar todas las empresas
    $vacantes = []; // Inicializar vacantes
    
    // Si la candidatura ya tiene empresa asociada, cargar sus vacantes
    if ($candidatura->empresa_id) {
        $vacantes = Vacante::where('empresa_id', $candidatura->empresa_id)->get();
    }
    
    return view('candidatura.edit', compact('candidatura', 'empresas', 'vacantes'));
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
    public function getVacantesPorEmpresa($empresa_id)
{
    // Verificar que la empresa existe primero
    $empresa = \App\Models\Empresa::find($empresa_id);
    
    if (!$empresa) {
        return response()->json(['error' => 'Empresa no encontrada'], 404);
    }

    // Obtener las vacantes activas de la empresa
    $vacantes = Vacante::where('empresa_id', $empresa_id)
                      ->get(['id', 'titulo']); // Solo los campos necesarios

    return response()->json([
        'success' => true,
        'vacantes' => $vacantes
    ]);
}

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
     * Método que verifica las credenciales y retorna la información deseada.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerCandidatura(Request $request)
    {
        // Validamos que se envíen correo y contraseña
        $request->validate([
            'correo' => 'required|email',
            'contra' => 'required|string',
        ]);

        // Obtenemos la empresa asociada al correo
        $empresa = Empresa::where('correo', $request->correo)->first();

        if (!$empresa) {
            return response()->json(['error' => 'Empresa no encontrada'], 404);
        }

        // Verificamos que la contraseña sea correcta
        if (!Hash::check($request->contra, $empresa->contra)) {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }

        // Si las credenciales son correctas, obtenemos la candidatura
        $candidaturas = Candidatura::select('candidaturas.id', 'candidaturas.token_user', 'vacantes.titulo', 'candidaturas.estado')
            ->join('vacantes', 'candidaturas.vacante_id', '=', 'vacantes.id')
            ->join('empresas', 'vacantes.empresa_id', '=', 'empresas.id')
            ->where('empresas.id', $empresa->id)
            ->get();

        // Preparamos una variable para almacenar las candidaturas con la información extra
        $candidaturasConInfo = [];

        // Recorrer las candidaturas para hacer la solicitud a la API externa
        foreach ($candidaturas as $candidatura) {
            // Obtener el token del usuario
            $tokenUser = $candidatura->token_user;

            // Hacer la solicitud a la API externa para obtener el first_name y email del usuario
            (string)$jossred = env("JOSSRED","https://jossred.josprox.com/api/");
            $response = Http::get("{$jossred}jossred/info", [
                'user_token' => $tokenUser
            ]);

            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                $userInfo = $response->json();

                // Verificar si la respuesta contiene los datos necesarios
                if (isset($userInfo['user']['first_name']) && isset($userInfo['user']['email'])) {
                    $firstName = $userInfo['user']['first_name'];
                    $email = $userInfo['user']['email'];
                } else {
                    // Si no se obtienen los datos, establecer valores por defecto
                    $firstName = 'Desconocido';
                    $email = 'Desconocido';
                }

                // Combinar los datos de la candidatura con los del usuario
                $candidaturasConInfo[] = [
                    'id' => $candidatura->id,
                    'first_name' => $firstName,
                    'email' => $email,
                    'titulo' => $candidatura->titulo,
                    'estado' => $candidatura->estado,
                ];
            } else {
                // Si la solicitud a la API externa falla
                $candidaturasConInfo[] = [
                    'first_name' => 'Error',
                    'email' => 'Error',
                    'titulo' => $candidatura->titulo,
                    'estado' => $candidatura->estado,
                ];
            }
        }

        // Devolver la respuesta con las candidaturas y los datos del usuario
        return response()->json([
            'success' => true,
            'data' => $candidaturasConInfo,
        ]);
    }

        /**
     * Actualiza el estado de una candidatura
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function actualizarEstadoCandidatura(Request $request)
    {
        // Validar credenciales de la empresa y datos requeridos
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
            'candidatura_id' => 'required|integer',
            'nuevo_estado' => 'required|string' // Ajusta según tus estados
        ]);

        // Autenticar empresa
        $empresa = Empresa::where('correo', $request->correo)->first();

        if (!$empresa || !Hash::check($request->contrasena, $empresa->contra)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Obtener la candidatura
        $candidatura = Candidatura::find($request->candidatura_id);

        // Verificar que la vacante asociada pertenece a la empresa
        $vacante = Vacante::where('id', $candidatura->vacante_id)
            ->where('empresa_id', $empresa->id)
            ->first();

        if (!$vacante) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para modificar esta candidatura'
            ], 403);
        }

        // Actualizar el estado
        $candidatura->estado = $request->nuevo_estado;
        $candidatura->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado de candidatura actualizado correctamente',
            'data' => [
                'candidatura_id' => $candidatura->id,
                'nuevo_estado' => $candidatura->estado,
                'vacante_id' => $candidatura->vacante_id,
                'vacante_titulo' => $vacante->titulo,
                'token_user' => $candidatura->token_user
            ]
        ]);
    }

        /**
     * Elimina una candidatura validando que la empresa es dueña de la vacante
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function eliminarCandidaturaSegura(Request $request)
    {
        // Validar datos de entrada
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
            'candidatura_id' => 'required|integer'
        ]);

        // Autenticar empresa
        $empresa = Empresa::where('correo', $request->correo)->first();

        if (!$empresa || !Hash::check($request->contrasena, $empresa->contra)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        // Buscar la candidatura y verificar relación con la empresa
        $candidatura = Candidatura::with(['vacante' => function($query) use ($empresa) {
                $query->where('empresa_id', $empresa->id);
            }])
            ->find($request->candidatura_id);

        if (!$candidatura || !$candidatura->vacante) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar esta candidatura o no existe'
            ], 403);
        }

        // Eliminar la candidatura
        $candidatura->delete();

        return response()->json([
            'success' => true,
            'message' => 'Candidatura eliminada correctamente',
            'data' => [
                'id_eliminado' => $request->candidatura_id,
                'empresa' => $empresa->nombre,
                'vacante' => $candidatura->vacante->titulo
            ]
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
