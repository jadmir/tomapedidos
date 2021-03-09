<?php

namespace App\Http\Middleware;

use App\Models\Users\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConfirmarEmail
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    $token = $request->headers->get('token');

    if (!$token) {
      return response([
        'message' => 'Autorización no encontrada.',
        'token' => $token
      ], Response::HTTP_UNAUTHORIZED);
    }

    try {
      $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
    } catch (ExpiredException $e) {
      return response([
        'message' => 'El tiempo de verificación de su correo ha expirado, solicite un link de verificación nuevo',
        'errorDetail' => $e->getMessage()
      ], Response::HTTP_UNAUTHORIZED);
    } catch (Exception $e) {
      return response([
        'message' => 'No se ha podido reconocer su autorizacion.',
      ], Response::HTTP_UNAUTHORIZED);
    }

    $user = User::find($credentials->id);

    if (!$user) {
      return response([
        'message' => 'Usted no esta autorizado a realizar esta acción',
      ], Response::HTTP_UNAUTHORIZED);
    }

    if (!$credentials->type || $credentials->type !== 'confirmar_email') {
      return response([
        'message' => 'Error al confirmar su correo',
      ], Response::HTTP_UNAUTHORIZED);
    }

    $request->auth_email = $user;

    return $next($request);
  }
}
