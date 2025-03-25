<?php

use Core\View;

function get_env(string $key, $default = null) {
    return $_ENV[$key] ?? $default;
}

function is_debug(): bool {
    return filter_var(get_env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN);
}

function dd(...$vars) {
    // if (!is_debug()) return;

    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    exit;
}

function set_title(string $title) {
    View::set('title', $title);
}

function page_title(): string {
    $app = get_env('APP_NAME', 'Pluma');
    $title = View::get('title', '');
    return $title ? "$title | $app" : $app;
}

// VIEW: get old form data (to repopulate fields)
function old(string $key, $default = '') {
    return $_POST[$key] ?? $default;
}

// CSRF: hidden field
function csrf_field(): string {
    return \Core\CSRF::tokenField();
}

// Asset (public path CSS/JS/Images)
function asset(string $path): string {
    return "/$path";
}

// View: render view extend shortcut
function extend(string $layout) {
    View::extend($layout);
}

// View: start/end sections
function section(string $name) {
    View::startSection($name);
}

function endsection() {
    View::endSection();
}

function render_section(string $name)
{
  View::section($name);
}


function include_partial(string $name, array $data = [])
{
  $path = dirname(__DIR__) . "/public/views/partials/{$name}.php";

  if (!file_exists($path)) {
    throw new Exception("Partial '$name' not found!.");
  }

  extract($data);
  include $path;
}

if (!function_exists('render_component')) {
    function render_component(string $name, array $props = []): void
    {
        $path = __DIR__ . '/../../public/views/partials/components/' . $name . '.php';

        if (!file_exists($path)) {
            throw new Exception("Component '$name' not found $path");
        }

        extract($props);
        include $path;
    }
}



if (!function_exists('redirect')) {
  function redirect(string $url, int $code = 302)
  {
    http_response_code($code);
    header("Location: $url");
    exit;
  }
}

function base_path(string $path = ''): string {
    return dirname(__DIR__, 1) . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
}
