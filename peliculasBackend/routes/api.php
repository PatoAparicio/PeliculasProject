<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PeliculaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//CATEGORIAS
Route::get('/categoria', [CategoriaController::class, 'index']);
Route::post('/categoria', [CategoriaController::class, 'store']);
Route::get('/categoria/{id}', [CategoriaController::class, 'show']);
Route::get('/categoria-activas', [CategoriaController::class, 'indexDisponibles']);
Route::put('/categoria/{id}', [CategoriaController::class, 'update']);
Route::delete('/categoria/{id}', [CategoriaController::class, 'destroy']);
//PELICULAS
Route::get('/pelicula', [PeliculaController::class, 'index']);
Route::post('/pelicula', [PeliculaController::class, 'store']);
Route::get('/pelicula/{id}', [PeliculaController::class, 'show']);
Route::put('/pelicula/{id}', [PeliculaController::class, 'update']);
Route::delete('/pelicula/{id}', [PeliculaController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
