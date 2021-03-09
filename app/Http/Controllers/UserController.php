<?php

namespace App\Http\Controllers;

use App\Models\Mails\MailRepository;
use App\Models\Token\TokenRepository;
use App\Models\Users\User;
use App\Models\Users\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  protected $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = $this->userRepository->allUsers();

    return response([
      'users' => $users
    ], Response::HTTP_OK);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store()
  {
    $dataUser = request()->input();

    [$rules, $messages] = $this->userRepository->validateNewUsuario($dataUser);
    CheckValidate($dataUser, $rules, $messages);
    $newUser = new User($dataUser);
    $user = $this->userRepository->newUser($newUser);

    return response([
      'user' => $user
    ], Response::HTTP_CREATED);
  }

  /**
   * Registrarse dentro del sistema
   */
  public function registrar()
  {
    $dataUser = request()->input();

    [$rules, $messages] = $this->userRepository->validateNewUsuario($dataUser);
    CheckValidate($dataUser, $rules, $messages);

    $newUser = new User($dataUser);
    $user = $this->userRepository->saveUser($newUser);

    $token = TokenRepository::generarTokenConfirmacioEmail($user->id);

    MailRepository::verificarEmail([
      'email' => $user->email
    ], $user->email, $user->username, $token);

    return response([
      'user' => $user
    ], Response::HTTP_CREATED);
  }

  /**
   * Verificar el correo de un usuario recientemente registrado
   */
  public function verificarCorreo()
  {
    $user = request()->auth_email;

    if ($user->esta_verificado) {
      return response([
        'message' => 'Email verifado'
      ], Response::HTTP_OK);
    }

    $user->esta_verificado = true;
    $user->d_verificacion_email = Carbon::now();
    $user->save();

    $this->userRepository->saveUser($user);

    return response([
      'message' => 'Email verifado'
    ], Response::HTTP_OK);
  }

  /**
   * Reenviar correo de verifacion de email
   */
  public function reenviarCorreoVerificacion()
  {
    $email = request()->post('email');

    $user = $this->userRepository->findByEmail($email);

    if (!$user) {
      return response([
        'message' => 'El correo ingresado no corresponde a ninguno registrado'
      ], Response::HTTP_OK);
    }

    $token = TokenRepository::generarTokenConfirmacioEmail($user->id);

    MailRepository::verificarEmail([
      'email' => $user->email
    ], $user->email, $user->username, $token);

    return response([
      'message' => 'El correo de verificación fue enviado'
    ], Response::HTTP_OK);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = $this->userRepository->findById($id);

    CheckModel($user, 'El usuario no existe');

    return response([
      'user' => $user
    ], Response::HTTP_OK);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $dni
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
