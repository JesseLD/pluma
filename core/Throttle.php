<?php

namespace Core;

/**
 * Simple file-based throttle service.
 * Limits number of actions per key (e.g., IP) in a time window.
 */
class Throttle
{
  protected static string $path = __DIR__ . '/../storage/throttle/';

  public static function check(string $key, string $identifier, int $maxAttempts, int $seconds): bool
  {
    $fullKey = "{$key}:{$identifier}";
    $file = self::$path . md5($fullKey) . '.json';

    $attempts = file_exists($file)
      ? json_decode(file_get_contents($file), true)
      : [];

    $now = time();
    $attempts = array_filter($attempts, fn($timestamp) => $timestamp >= $now - $seconds);

    if (count($attempts) >= $maxAttempts) {
      return false;
    }

    $attempts[] = $now;

    if (!is_dir(self::$path)) {
      mkdir(self::$path, 0755, true);
    }

    file_put_contents($file, json_encode($attempts));

    return true;
  }
}
