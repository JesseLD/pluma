<?php

namespace Core;

class Response
{
  /**
   * Sends a structured JSON response with message, data, and status code.
   *
   * @param string $message Response message (e.g., "Created successfully")
   * @param array $data Optional data payload
   * @param int $status HTTP status code (default: 200)
   */
  public static function json(string $message, array $data = [], int $status = 200): void
  {
    http_response_code($status);
    header('Content-Type: application/json');

    echo json_encode([
      'message' => $message,
      'data' => $data
    ]);

    exit;
  }

  /**
   * Sends a structured JSON exception with message, data, and status code.
   *
   * @param string $message Error message
   * @param string $exception Exception name or type
   * @param int $status HTTP status code (default: 200)
   */
  protected static function jsonException(string $message, string $exception, int $status = 200): void
  {
    http_response_code($status);
    header('Content-Type: application/json');

    echo json_encode([
      'message' => $message,
      'exception' => $exception
    ]);

    exit;
  }

  /**
   * Sends plain text response.
   */
  public static function text(string $content, int $status = 200): void
  {
    http_response_code($status);
    header('Content-Type: text/plain');
    echo $content;
    exit;
  }

  /**
   * Redirects to another URL.
   */
  public static function redirect(string $url, int $status = 302): void
  {
    http_response_code($status);
    header("Location: $url");
    exit;
  }

  /**
   * Sends a file for download.
   */
  public static function download(string $filePath, ?string $fileName = null): void
  {
    if (!file_exists($filePath)) {
      http_response_code(404);
      echo "File not found.";
      exit;
    }

    $fileName = $fileName ?? basename($filePath);

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

    readfile($filePath);
    exit;
  }

  /**
   * Throws an HTTP exception, returning either JSON or HTML depending on the request.
   *
   * @param array|int $exception Exception array or status code
   * @param string|null $message Optional custom message
   */
  public static function exception(array|int $exception, ?string $message = null): void
  {
    if (is_array($exception)) {
      $code = $exception['code'] ?? 500;
      $msg = $exception['message'] ?? 'An error occurred';
      $name = $exception['name'] ?? 'HttpException';
    } else {
      $code = $exception;
      $msg = $message ?? 'An error occurred';
      $name = 'HttpException';
    }

    http_response_code($code);

    if (self::isApiRequest()) {
      self::jsonException($msg, $name, $code);
    } else {
      $viewPath = "errors.{$code}";
      if (View::exists($viewPath)) {
        echo View::render($viewPath, ['message' => $msg]);
      } else {
        echo View::render('errors.default', ['message' => $msg, 'code' => $code]);
      }
      exit;
    }
  }

  /**
   * Determines if the current request expects a JSON response.
   *
   * @return bool
   */
  protected static function isApiRequest(): bool
  {
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    return str_starts_with($uri, '/api') ||
      str_contains($accept, 'application/json') ||
      str_contains($contentType, 'application/json');
  }
}
