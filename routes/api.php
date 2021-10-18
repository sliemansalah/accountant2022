<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\DivisionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('divisions', DivisionController::class);
Route::resource('areas', AreaController::class);
Route::get('areas/{area}/childs', [AreaController::class, 'showWithChildren']);