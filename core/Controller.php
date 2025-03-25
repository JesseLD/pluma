<?php

namespace Core;

abstract class Controller
{
  public function view(string $view, array $data = [])
  {
    return View::render($view, $data);
  }

  public function redirect(string $url)
  {
    header("Location: $url");
    exit;
  }

  public function input(string $key, $default = null)
  {
    return $_POST[$key] ?? $_GET[$key] ?? $default;
  }

  public function session(?string $key = null)
  {
    session_start();

    if ($key !== null) {
      return $_SESSION[$key] ?? null;
    }

    return $_SESSION;
  }
}
