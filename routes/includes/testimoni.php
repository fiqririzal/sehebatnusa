<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TestimoniController;

Route::post('/tambahTestimoni',[TestimoniController::class,'store']);
Route::post('/editTestimoni/{id}',[TestimoniController::class,'update']);
Route::delete('/Testimoni/{id}',[TestimoniController::class,'destroy']);
