<?php

namespace Core;

class Response
{
  public static function json(string $message, array $data = [], ?string $exception = null, int $httpCode = 200)
  {
    http_response_code($httpCode);
    header('Content-Type: application/json');

    echo json_encode([
      'message' => $message,
      'data' => $data,
      'exception' => $exception,
    ]);

    exit;
  }

  public static function exception(array $exceptionDef, array $data = [])
  {
    self::json(
      $exceptionDef['message'],
      $data,
      $exceptionDef['name'],
      $exceptionDef['code']
    );
  }
}
