<?php

use App\Models\Files\FileRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
  return response([
    'message' => 'Funcionando correctamente'
  ], 200);
});

// user ID and roles
Route::get('check/{userID}/roles', function ($userID) {
  return response([
    'userID' => $userID,
    'roles' => request()->roles,
    'u' => request()->auth
  ], 200);
})->middleware('auth.jwt:admin');

// solo user ID
Route::get('check/{userID}', function ($userID) {
  return response([
    'userID' => $userID,
    'roles' => request()->roles,
    'u' => request()->auth
  ], 200);
})->middleware('auth.jwt');

// solo roles
Route::get('check/roles/{idUser}', function ($idUser) {
  return response([
    'roles' => request()->roles,
    'u' => request()->auth,
    'idUser' => $idUser
  ], 200);
})->middleware('auth.jwt:admin');

// ni roles ni user ID
Route::get('check', function () {
  return response([
    'roles' => request()->roles,
    'u' => request()->auth
  ], 200);
})->middleware('auth.jwt');

/**
 * Test upload files
 */
Route::post('file', function () {
  $file = request()->file('file');

  [$nameWithFolder, $name] = FileRepository::saveFile('public', 'avatars', $file);
  $url = FileRepository::getURL('public', $nameWithFolder);

  return response([
    'name' => $name,
    'name_folder' => $nameWithFolder,
    'url' => $url,
  ], Response::HTTP_OK);
});

/**
 * Test upload files S3
 */
Route::post('file-s3', function () {
  $file = request()->file('file');

  [$nameWithFolder, $name] = FileRepository::saveFile('s3', 'avatars/spl', $file);
  $url = FileRepository::getURL('s3', $nameWithFolder);

  return response([
    'name' => $name,
    'name_folder' => $nameWithFolder,
    'url' => $url,
  ], Response::HTTP_OK);
});

/**
 * Test upload files images
 */
Route::post('file-images', function () {
  $file = request()->file('file');

  [$nameWithFolder, $name] = FileRepository::saveFile('images', 'avatars', $file);
  $url = FileRepository::getURL('images', $nameWithFolder);

  return response([
    'name' => $name,
    'name_folder' => $nameWithFolder,
    'url' => $url,
  ], Response::HTTP_OK);
});

/**
 * Eliminar archivo
 */
Route::post('delete/file', function () {
  $name = request('name');

  $isDeleted = FileRepository::deleteFile('public', $name);

  return response([
    'deleted' => $isDeleted
  ], Response::HTTP_OK);
});

Route::post('mail', function () {
  Mail::send('mail.reset_password', [], function ($message) {
    $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
    $message->to('jash.sif@gmail.com', 'Jhojhan');
    $message->subject("Cambio de contraseña");
    $message->priority(1);
  });
});
