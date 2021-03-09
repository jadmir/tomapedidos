<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
  Route::group(['prefix' => 'auth'], function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('login/email', [AuthController::class, 'loginEmail']);

    Route::get('test', function () {
      return response([
        'message' => 'Verificación middleware de autenticación',
        'user-auth' => request()->auth
      ], 200);
    })->middleware('auth.jwt');
  });
});
