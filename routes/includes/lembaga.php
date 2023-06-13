<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LembagaController;

Route::post('/tambahLembaga',[LembagaController::class,'store']);
Route::post('/editLembaga/{id}',[LembagaController::class,'update']);
Route::delete('/Lembaga/{id}',[LembagaController::class,'destroy']);
