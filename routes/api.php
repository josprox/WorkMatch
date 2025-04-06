<?php

use App\Http\Controllers\EmpresaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/empresas', [EmpresaController::class, 'crearEmpresa']);
Route::get('/empresas/{id}', [EmpresaController::class, 'DetallesEmpresa']);