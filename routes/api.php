<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;

Route::post('/pokemons', [PokemonController::class, 'store']);
Route::post('/pokemons/{id}', [PokemonController::class, 'update']);
Route::delete('/pokemons/{id}', [PokemonController::class, 'destroy']);
Route::get('/pokemons/list', [PokemonController::class, 'index']);
