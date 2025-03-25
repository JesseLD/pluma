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
   *
   * Example:
   * Response::json('User created', ['user' => $user], 201);
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
   * @param string $message Response message (e.g., "Created successfully")
   * @param string $exception Optional data payload
   * @param int $status HTTP status code (default: 200)
   *
   * Example:
   * Response::jsonException('User created', 'UserAlreadyExists', 409);
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
   *
   * @param string $content The content to be sent.
   * @param int $status The HTTP status code. Default is 200.
   */
  public static function text(string $content, int $status = 200): void
  {
    http_response_code($status);
    header('Content-Type: text/plain');
    echo $content;
    exit;
  }

  /**
   * Redirects the user to a different URL.
   *
   * @param string $url The target URL.
   * @param int $status The HTTP status code. Default is 302 (temporary redirect).
   */
  public static function redirect(string $url, int $status = 302): void
  {
    http_response_code($status);
    header("Location: $url");
    exit;
  }

  /**
   * Sends a file download response.
   *
   * @param string $filePath Full path to the file to be downloaded.
   * @param string|null $fileName Optional custom name for the downloaded file.
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
   * Throws an HTTP exception with a status code and message.
   * Accepts either an exception array (from Exceptions class) or custom values.
   *
   * @param array|int $exception Exception array (e.g. Exceptions::NOT_FOUND), or just a status code.
   * @param string|null $message Optional custom message if passing status code directly.
   *
   * Examples:
   * Response::exception(Exceptions::NOT_FOUND);
   * Response::exception(403, "Access denied.");
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
    header('Content-Type: application/json');
    Self::jsonException($msg, $name, $code);
    exit;
  }
}
