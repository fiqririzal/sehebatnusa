<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HewanController;
use App\Http\Controllers\API\ArtikelController;
use App\Http\Controllers\API\LembagaController;
use App\Http\Controllers\API\KeunggulanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/lihatHewan',[HewanController::class,'index']);
Route::get('/lihatHewan/{id}',[HewanController::class,'show']);
Route::get('/lihatArtikel',[ArtikelController::class,'index']);
Route::get('/lihatArtikel/{id}',[ArtikelController::class,'show']);
Route::get('/lihatKeunggulan',[KeunggulanController::class,'index']);
Route::get('/lihatKeunggulan/{id}',[KeunggulanController::class,'show']);
Route::get('/lihatLembaga',[LembagaController::class,'index']);
Route::get('/lihatLembaga/{id}',[LembagaController::class,'show']);
Route::get('/lihatProgram',[ProgramController::class,'index']);
Route::get('/lihatProgram/{id}',[ProgramController::class,'show']);
Route::get('/lihatTarget',[TargetController::class,'index']);
Route::get('/lihatTarget/{id}',[TargetController::class,'show']);
Route::get('/lihatTestimoni',[TestimoniController::class,'index']);
Route::get('/lihatTestimoni/{id}',[TestimoniController::class,'show']);

require_once('includes/user.php');
Route::group( ['middleware' => 'auth:api'], function() {
    require_once('includes/hewan.php');
    require_once('includes/artikel.php');
    require_once('includes/keunggulan.php');
    require_once('includes/lembaga.php');
    require_once('includes/program.php');
    require_once('includes/target.php');
    require_once('includes/testimoni.php');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
});
