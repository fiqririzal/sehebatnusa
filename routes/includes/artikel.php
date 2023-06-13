<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ArtikelController;

Route::post('/tambahArtikel',[ArtikelController::class,'store']);
Route::post('/editArtikel/{id}',[ArtikelController::class,'update']);
Route::delete('/Artikel/{id}',[ArtikelController::class,'destroy']);
