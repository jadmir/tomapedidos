<?php

namespace App\Http\Controllers;

use App\Models\Users\UserRepository;
use Carbon\Carbon;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
  protected $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  protected function generateToken(Int $id)
  {
    $payload = [
      'iss' => env('JWT_ISS'),
      'id' => $id,
      'iat' => time(),
      'exp' => strtotime(Carbon::now()->addDay()),
    ];

    return JWT::encode($payload, env('JWT_SECRET'));
  }

  /**
   * Login usuario
   */
  public function login()
  {
    $dataLogin = request()->input();
    [$rules, $messages] = $this->userRepository->validateLogin($dataLogin);
    CheckValidate($dataLogin, $rules, $messages);

    $username = $dataLogin['username'];
    $password = $dataLogin['password'];

    $user = $this->userRepository->findByUsername($username);

    CheckModel($user, 'Nombre de usuario incorrecto');

    $passwordValid = $user->validPassword($password);

    if (!$passwordValid) {
      ThrowBadRequest('La contraseña es incorrecta');
    }

    return response([
      'user' => $user,
      'token' => $this->generateToken($user->id)
    ], 200);
  }

  /**
   * Login usuario con email
   */
  public function loginEmail()
  {
    $dataLogin = request()->input();

    $email = $dataLogin['email'];
    $password = $dataLogin['password'];

    $user = $this->userRepository->findByEmail($email);

    CheckModel($user, 'Email incorrecto');

    if (!$user->esta_verificado) {
      ThrowBadRequest('Debe verificar su correo para poder ingresar');
    }

    $passwordValid = $user->validPassword($password);

    if (!$passwordValid) {
      ThrowBadRequest('La contraseña es incorrecta');
    }

    return response([
      'user' => $user,
      'token' => $this->generateToken($user->id)
    ], 200);
  }
}
