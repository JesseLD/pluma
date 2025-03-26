<?php

namespace Core;

/**
 * Simple file-based logger.
 * Supports levels: info, warning, error, debug.
 */
class Logger
{
  protected static string $logPath = __DIR__ . '/../storage/logs/app.log';

  public static function info(string $message): void
  {
    self::write('INFO', $message);
  }

  public static function warning(string $message): void
  {
    self::write('WARNING', $message);
  }

  public static function error(string $message): void
  {
    self::write('ERROR', $message);
  }

  public static function debug(string $message): void
  {
    self::write('DEBUG', $message);
  }

  protected static function write(string $level, string $message): void
  {
    $date = date('Y-m-d H:i:s');
    $entry = "[{$date}] {$level}: {$message}" . PHP_EOL;

    $dir = dirname(self::$logPath);
    if (!is_dir($dir)) {
      mkdir($dir, 0755, true);
    }

    file_put_contents(self::$logPath, $entry, FILE_APPEND);
  }
}
