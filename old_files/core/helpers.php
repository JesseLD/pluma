<?php

use Core\View;
use Core\Response;
use Core\Session;

/**
 * ========== Response Helpers ==========
 */

if (!function_exists('json')) {
    /**
     * Sends a JSON response.
     */
    function json(string $message, array $data, int $status = 200): void
    {
        Response::json($message, $data, $status);
    }
}

if (!function_exists('text')) {
    /**
     * Sends plain text response.
     */
    function text(string $content, int $status = 200): void
    {
        Response::text($content, $status);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirects to a given URL.
     */
    function redirect(string $url, int $status = 302): void
    {
        Response::redirect($url, $status);
    }
}

if (!function_exists('download')) {
    /**
     * Sends a file download response.
     */
    function download(string $filePath, ?string $fileName = null): void
    {
        Response::download($filePath, $fileName);
    }
}

/**
 * ========== Environment Helpers ==========
 */

function get_env(string $key, $default = null) {
    return $_ENV[$key] ?? $default;
}

function is_debug(): bool {
    return filter_var(get_env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN);
}

/**
 * Debug helper that dumps variables and exits the script.
 */
function dd(...$vars): void {
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    exit;
}

/**
 * ========== View / Layout Helpers ==========
 */

function set_title(string $title): void {
    View::set('title', $title);
}

function page_title(): string {
    $app = get_env('APP_NAME', 'Pluma');
    $title = View::get('title', '');
    return $title ? "$title | $app" : $app;
}

function view_get(string $key, $default = null) {
    return View::get($key, $default);
}

/**
 * Define the layout file to use.
 */
function extend(string $layout): void {
    View::extend($layout);
}

/**
 * Starts a content section.
 */
function section(string $name): void {
    View::startSection($name);
}

/**
 * Ends the current section.
 */
function endsection(): void {
    View::endSection();
}

/**
 * Renders the given section (used in layout).
 */
function render_section(string $name): void {
    View::section($name);
}

/**
 * Escapes output for safe HTML rendering (XSS protection).
 */
if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

/**
 * ========== Form Helpers ==========
 */

/**
 * Returns previously submitted POST value.
 */
function old(string $key, $default = '') {
    return $_POST[$key] ?? $default;
}

/**
 * Returns the hidden CSRF input field.
 */
function csrf_field(): string {
    return \Core\CSRF::tokenField();
}

/**
 * ========== View Assets & Partials ==========
 */

/**
 * Returns a public URL path for assets (images, CSS, JS).
 */
function asset(string $path): string {
    return "/$path";
}

/**
 * Includes a partial view with optional data.
 */
function include_partial(string $name, array $data = []): void
{
    $path = dirname(__DIR__) . "/public/views/partials/{$name}.php";

    if (!file_exists($path)) {
        throw new Exception("Partial '{$name}' not found!");
    }

    extract($data);
    include $path;
}

/**
 * Includes a reusable component from /partials/components
 */
if (!function_exists('render_component')) {
    function render_component(string $name, array $props = []): void
    {
        $componentPath = base_path("public/views/partials/components/{$name}.php");

        if (!file_exists($componentPath)) {
            throw new Exception("Component '{$name}' not found at: $componentPath");
        }

        extract($props);
        include $componentPath;
    }
}


/**
 * ========== Session Helpers ==========
 */

/**
 * Retrieves a session value by key.
 */
function flash(string $key, ?string $value = null): void
{
  Session::flash($key, $value);
}


/**
 * ========== Path Utilities ==========
 */

/**
 * Returns the base path of the project with optional subpath.
 */
function base_path(string $path = ''): string {
    return dirname(__DIR__, 1) . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
}


/**
 * ========== Config Helpers ==========
 */


/**
 * Retrieves a configuration value from config/config.php using dot notation.
 *
 * Example: config('db.host') will return the database host.
 *
 * @param string $key     Dot-notated config key (e.g., 'db.host')
 * @param mixed  $default Value returned if key is not found
 * @return mixed
 */
function config(string $key, $default = null)
{
    static $config;

    if (!$config) {
        $config = require __DIR__ . '/../config/config.php';
    }

    $keys = explode('.', $key);
    $value = $config;

    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return $default;
        }
    }

    return $value;
}



/**
 * ========== Logger Helpers ==========
 */


/**
 * Logs a message to the application log file.
 */
function app_log(string $message, string $level = 'info'): void
{
    Core\Logger::$level($message);
}