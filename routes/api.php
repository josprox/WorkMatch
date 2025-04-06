<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/empresas', [EmpresaController::class, 'crearEmpresa']);
Route::get('/empresas/{id}', [EmpresaController::class, 'DetallesEmpresa']);
Route::get('usuarios/{id}/{token}', [UsuarioController::class, 'DatosUsuario'])->name('api.usuarios.DatosUsuario');
Route::put('usuarios/{id}', [UsuarioController::class, 'actualizarDetalles'])->name('api.usuarios.actualizarDetalles');