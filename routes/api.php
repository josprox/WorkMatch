<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;

Route::post('/empresas', [EmpresaController::class, 'crearEmpresa']);
Route::get('/empresas/{id}', [EmpresaController::class, 'DetallesEmpresa']);
