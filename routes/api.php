<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//CRUD Empresa
Route::post('/empresas', [EmpresaController::class, 'crearEmpresa']);
Route::get('/empresas', [EmpresaController::class, 'DetallesEmpresa']);
Route::put('/empresas', [EmpresaController::class, 'actualizarEmpresa']);
Route::put('/empresas/newpassword', [EmpresaController::class, 'cambiarContraEmpresa']);
Route::delete('empresas/eliminar', [EmpresaController::class, 'eliminarEmpresa']);

// CRUD Usuarios
Route::get('usuarios/{token}', [UsuarioController::class, 'DatosUsuario'])->name('api.usuarios.DatosUsuario');
Route::post('usuarios', [UsuarioController::class, 'crearUsuario'])->name('api.usuarios.crearUsuario');
Route::put('usuarios/{token}', [UsuarioController::class, 'actualizarDetalles'])->name('api.usuarios.actualizarDetalles');
Route::delete('usuarios/{token_user}', [UsuarioController::class, 'eliminarUsuarioPorToken']);