<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TargetController;

Route::post('/tambahTarget',[TargetController::class,'store']);
Route::post('/editTarget/{id}',[TargetController::class,'update']);
Route::delete('/Target/{id}',[TargetController::class,'destroy']);
