<?php

namespace App\Controllers\Api;

use Core\Response;

class UserController
{
  public function index()
  {

    return Response::json(
      "Users",
      [
        ['id' => 1, 'name' => 'João'],
        ['id' => 2, 'name' => 'Maria'],
      ]
    );
  }
}
