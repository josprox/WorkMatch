<?php

use App\Http\Controllers\EmpresaController;
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
    Route::resource('/vacantes', App\Http\Controllers\VacanteController::class);
    // Fin de las rutas.
});


Route::get('/register', function () {
    return redirect('/'); // Redirige a la página de login
});

