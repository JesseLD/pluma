<?php

namespace Core;

class CSRF
{
  public static function generateToken()
  {
    session_start();

    if (empty($_SESSION['_csrf_token'])) {
      $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf_token'];
  }

  public static function getToken()
  {
    session_start();
    return $_SESSION['_csrf_token'] ?? null;
  }

  public static function verifyToken($token)
  {
    session_start();
    return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
  }

  public static function tokenField()
  {
    $token = self::generateToken();
    return '<input type="hidden" name="_csrf" value="' . $token . '">';
  }
}
