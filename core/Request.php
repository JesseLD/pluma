<?php

namespace Core;

/**
 * Provides static helpers for accessing request input.
 * Supports query strings, POST data, and JSON bodies.
 */
class Request
{
    /**
     * Returns all input data from both query and body (POST or JSON).
     *
     * @return array Merged GET and POST/JSON data.
     *
     * Example:
     * $data = Request::all();
     */
    public static function all(): array
    {
        return array_merge(self::query(), self::body());
    }

    /**
     * Returns only the query string (GET) parameters.
     *
     * @return array
     *
     * Example:
     * ?search=books → Request::query() returns ['search' => 'books']
     */
    public static function query(): array
    {
        return $_GET ?? [];
    }

    /**
     * Returns the body data, handling both form and JSON input.
     *
     * If the request's Content-Type is application/json, it parses JSON from the raw input stream.
     * Otherwise, returns $_POST.
     *
     * @return array
     *
     * Example (JSON POST body):
     * { "email": "test@example.com" } → Request::body()['email']
     */
    public static function body(): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            return json_decode($raw, true) ?? [];
        }

        return $_POST ?? [];
    }

    /**
     * Returns a single input value from the request (GET, POST or JSON).
     *
     * @param string $key The input field name.
     * @param mixed $default Default value if the key is not found.
     * @return mixed
     *
     * Example:
     * $email = Request::input('email');
     */
    public static function input(string $key, $default = null)
    {
        $data = self::all();
        return $data[$key] ?? $default;
    }
}
