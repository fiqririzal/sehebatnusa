<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HewanController;

Route::post('/tambahHewan',[HewanController::class,'store']);
// Route::get('/lihatHewan',[HewanController::class,'index']);
// Route::get('/lihatHewan/{id}',[HewanController::class,'show']);
Route::post('/editHewan/{id}',[HewanController::class,'update']);
Route::delete('/Hewan/{id}',[HewanController::class,'destroy']);
