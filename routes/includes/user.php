<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

Route::group([
    'prefix' => 'auth'
], function () {
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', [UserController::class, 'logout']);
});
});
