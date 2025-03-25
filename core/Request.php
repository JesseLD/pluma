<?php

namespace Core;

class Request
{
  public static function all(): array
  {
    return array_merge(self::query(), self::body());
  }

  public static function query(): array
  {
    return $_GET ?? [];
  }

  public static function body(): array
  {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (str_contains($contentType, 'application/json')) {
      $raw = file_get_contents('php://input');
      return json_decode($raw, true) ?? [];
    }

    return $_POST ?? [];
  }

  public static function input(string $key, $default = null)
  {
    $data = self::all();
    return $data[$key] ?? $default;
  }
}
