<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\usuarioController;

Route::get('/users', [usuarioController::class, 'index']);


Route::get('/users/{id}', function() {
    return 'Obteniendo un usuario';
});


Route::post('/users', [usuarioController::class, 'store']);


Route::patch('/users/{id}', function() {
    return 'Actualizando usuario';
});


Route::delete('/users/{id}', function() {
    return 'Eliminando usuario';
});