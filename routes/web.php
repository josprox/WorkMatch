<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VacanteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Página de inicio
Route::get('/', function () {
    return view('auth.login'); // Cambia 'welcome' por 'login'
});

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
    // Aquí van tus rutas del CRUD.
    Route::resource('/empresas', EmpresaController::class);
    Route::resource('/vacantes', VacanteController::class);
    Route::resource('/usuarios', UsuarioController::class);
    Route::resource('/candidaturas', App\Http\Controllers\CandidaturaController::class);
    // Fin de las rutas.
});

Route::get('/register', function () {
    return redirect('/'); // Redirige a la página de login
});
Route::resource('/candidatura', App\Http\Controllers\CandidaturaController::class);
