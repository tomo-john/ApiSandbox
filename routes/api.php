<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;
use App\Http\Controllers\WalkController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('dogs', DogController::class)
    ->middleware('auth:sanctum');

Route::apiResource('dogs.walks', WalkController::class)
    ->middleware('auth:sanctum')
    ->scoped();

Route::get('/sandbox', function (Request $request) {
    return response("私はティーポットです🐶\n", 418);
});
