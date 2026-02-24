<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController; 
use App\Http\Controllers\Api\OrderController; 
use App\Http\Controllers\Api\MejaController; 
use App\Http\Controllers\Api\KategoriController; 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/menus', [MenuController::class, 'index']);
Route::post('/checkout', [OrderController::class, 'store']);
Route::get('/mejas', [MejaController::class, 'index']);
Route::get('/kategori', [KategoriController::class, 'index']);
