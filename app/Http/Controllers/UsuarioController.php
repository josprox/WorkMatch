<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

/**
 * Class UsuarioController
 * @package App\Http\Controllers
 */
class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::paginate(10);

        return view('usuario.index', compact('usuarios'))
            ->with('i', (request()->input('page', 1) - 1) * $usuarios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuario = new Usuario();
        return view('usuario.create', compact('usuario'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Usuario::$rules);

        $usuario = Usuario::create($request->all());

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = Usuario::find($id);

        return view('usuario.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = Usuario::find($id);

        return view('usuario.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Usuario $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        request()->validate(Usuario::$rules);

        $usuario->update($request->all());

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $usuario = Usuario::find($id)->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario deleted successfully');
    }

    /**
     * Obtener los detalles del usuario por ID y token_user.
     *
     * @param  int  $id
     * @param  string  $token_user
     * @return \Illuminate\Http\Response
     */
    public function DatosUsuario($id, $token)
    {
        // Validar si el token_user en la cabecera coincide con el del usuario
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        if ($usuario->token_user != $token) {
            return response()->json(['message' => 'Token inválido'], 403);
        }

        return response()->json($usuario);
    }

    /**
     * Actualizar las especialidades y curriculum del usuario.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function actualizarDetalles($id, Request $request)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Verificar el token_user
        if ($usuario->token_user !== $request->header('token_user')) {
            return response()->json(['message' => 'Token inválido'], 403);
        }

        // Validación de los campos permitidos para actualizar
        $request->validate([
            'especialidades' => 'nullable|string',
            'curriculum' => 'nullable|string',
        ]);

        // Solo actualizar los campos permitidos
        $usuario->especialidades = $request->input('especialidades', $usuario->especialidades);
        $usuario->curriculum = $request->input('curriculum', $usuario->curriculum);

        $usuario->save();

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'usuario' => $usuario
        ]);
    }
}
