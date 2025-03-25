<?php

namespace App\Requests;

use Core\Validator;

class LoginRequest
{
  public static function validate(array $data)
  {
    Validator::requireFields($data, ['email', 'password']);
  }
}
