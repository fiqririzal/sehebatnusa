<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProgramController;

Route::post('/tambahProgram',[ProgramController::class,'store']);
Route::post('/editProgram/{id}',[ProgramController::class,'update']);
Route::delete('/Program/{id}',[ProgramController::class,'destroy']);
