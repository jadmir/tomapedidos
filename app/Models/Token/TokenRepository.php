<?php

namespace App\Models\Token;

use Carbon\Carbon;
use Firebase\JWT\JWT;

class TokenRepository
{
  public static function generarTokenConfirmacioEmail($id)
  {
    $payload = [
      'iss' => env('JWT_ISS'),
      'id' => $id,
      'iat' => time(),
      'exp' => strtotime(Carbon::now()->addDay()),
      'type' => 'confirmar_email'
    ];

    return JWT::encode($payload, env('JWT_SECRET'));
  }
}
