<?php

namespace Core;

class View
{
  protected static $layout = null;
  protected static $sections = [];

  protected static array $shared = [];

  public static function set(string $key, $value)
  {
    self::$shared[$key] = $value;
  }

  public static function get(string $key, $default = null)
  {
    return self::$shared[$key] ?? $default;
  }

  public static function render(string $view, array $data = [])
  {
    $viewPath = dirname(__DIR__) . "/public/views/" . str_replace('.', '/', $view) . ".php";

    if (!file_exists($viewPath)) {
      throw new \Exception("View '$view' não encontrada.");
    }

    extract($data);
    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    if (self::$layout) {
      ob_start();
      include dirname(__DIR__) . "/public/views/layouts/" . self::$layout . ".php";
      return ob_end_flush();
    }

    echo $content;
  }

  public static function extend(string $layout)
  {
    self::$layout = $layout;
  }

  public static function startSection(string $name)
  {
    ob_start();
    self::$sections[$name] = '';
  }

  public static function endSection()
  {
    $content = ob_get_clean();
    $keys = array_keys(self::$sections);
    $lastKey = end($keys);
    self::$sections[$lastKey] = $content;
  }

  public static function section(string $name)
  {
    echo self::$sections[$name] ?? '';
  }
}
