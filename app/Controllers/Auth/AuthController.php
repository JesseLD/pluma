<?php

use Core\Request;
use App\Requests\LoginRequest;
use Core\Controller;

class AuthController extends Controller
{
  public function login()
  {
    $data = Request::all(); 
    LoginRequest::validate($data);

  }
}