<?php

namespace App\Models\Users;

use App\BaseBuilder;

class UserBuilder extends BaseBuilder
{
  /**
   * Scope que filtra los usuarios según su dni
   */
  public function usernameScope($username)
  {
    return $this->where('username', $username);
  }
}
