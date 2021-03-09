<?php

namespace App\Models\Users;

use Illuminate\Support\Facades\Hash;

class UserObserver
{
  public function creating(User $user)
  {
    $user->password = Hash::make($user->password);
    // if (true) {
    //   throw new Exception($user->password);
    //   // Si un hook retorna falso la operacion no se realiza
    //   return false;
    // }
  }
}
