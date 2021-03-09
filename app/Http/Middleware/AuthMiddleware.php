<?php

namespace App\Http\Middleware;

use App\Models\Users\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class AuthMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next, ...$roles)
  {
    $ID_USER = 'id'; # Nombre del campo identificador del usuario
    $ROLE_USER = 'role'; # Nombre del campo idntificador de rol de usuario

    $token = $request->headers->get('token');

    if (!$token) {
      $token = request('token');

      if (!$token) {
        return response([
          'message' => 'Autorización no encontrada.',
          'token' => $token
        ], Response::HTTP_UNAUTHORIZED);
      }
    }

    try {
      $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
    } catch (ExpiredException $e) {
      return response([
        'message' => 'Su autorización ha expirado.',
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

    $routeParameters = Route::current()->parameterNames;
    $existsUserID = in_array('userID', $routeParameters);
    $existsRoles = sizeof($roles) > 0;

    if ($existsUserID) {
      $routeValueID = Route::current()->parameter('userID');
      $isSelf = $routeValueID == $user[$ID_USER];
      $hasRole = false;

      if ($existsRoles) {
        $hasRole = in_array($user[$ROLE_USER], $roles);
      }

      if (!$isSelf && !$hasRole) {
        return response([
          'message' => 'Usted no esta autorizado.',
          'http' => 'no self and not role'
        ], Response::HTTP_UNAUTHORIZED);
      }
    } else {
      if ($existsRoles) {
        $hasRole = in_array($user[$ROLE_USER], $roles);

        if (!$hasRole) {
          return response([
            'message' => 'Usted no esta autorizado.',
            'http' => 'role'
          ], Response::HTTP_UNAUTHORIZED);
        }
      }
    }

    $request->auth = $user;

    return $next($request);
  }
}
