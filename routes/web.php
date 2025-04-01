<?php

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

Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/register', function () {
    return redirect('/'); // Redirige a la página de login
});

