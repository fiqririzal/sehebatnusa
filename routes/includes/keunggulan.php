<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\KeunggulanController;

Route::post('/tambahKeunggulan',[KeunggulanController::class,'store']);
Route::post('/editKeunggulan/{id}',[KeunggulanController::class,'update']);
Route::delete('/Keunggulan/{id}',[KeunggulanController::class,'destroy']);
