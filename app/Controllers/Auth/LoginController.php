<?php

namespace App\Controllers\Auth;

use Core\Controller;

class LoginController extends Controller
{
  public function index()
  {
    return $this->view('auth.login');
  }
}
