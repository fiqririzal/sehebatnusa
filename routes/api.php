<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HewanController;

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

require_once('includes/user.php');
Route::group( ['middleware' => 'auth:api'], function() {
    require_once('includes/hewan.php');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
});
