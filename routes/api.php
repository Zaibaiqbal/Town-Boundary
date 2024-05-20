<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TownController;
use App\Http\Controllers\TownBoundaryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::get('gettownlist',[\App\Http\Controllers\TownController::class,'getTownList']);


Route::get('town_boundaries', [TownController::class, 'getTownBoundaries']);


Route::get('gettownsnamelist',[\App\Http\Controllers\TownController::class,'getTownsNameList']);
