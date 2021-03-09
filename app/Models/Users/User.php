<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
  use HasFactory;

  protected $table = 'users';

  protected $primaryKey = 'id';

  protected $hidden = ['password'];

  protected $fillable = [
    'username',
    'password',
    'email',
    'role'
  ];

  protected static function boot()
  {
    parent::boot();
    User::observe(UserObserver::class);
  }

  /**
   * Apunta todos los escopes al UserBuilder
   */
  public function newEloquentBuilder($query): UserBuilder
  {
    return new UserBuilder($query);
  }

  /**
   * Verficar que la contraseña del usuario sea válida
   */
  public function validPassword($password)
  {
    return Hash::check($password, $this->password);
  }
}
