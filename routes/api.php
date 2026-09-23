<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DogController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('dogs', DogController::class)->middleware('auth:sanctum');

Route::get('/sandbox', function (Request $request) {
    return "Hello API \n";
});
