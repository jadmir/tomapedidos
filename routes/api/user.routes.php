<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
  Route::group(['middleware' => 'auth.jwt'], function () {
    Route::apiResource('user', UserController::class);
  });

  Route::group(['prefix' => 'users'], function () {
    Route::post('register', [UserController::class, 'registrar']);
    Route::post('verificar/email', [UserController::class, 'verificarCorreo'])
      ->middleware('email.confirmar');
    Route::post('reenviar/verificar/email', [UserController::class, 'reenviarCorreoVerificacion']);
  });
});
