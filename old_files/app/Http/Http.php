<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Http service wrapper around GuzzleHttp\Client.
 *
 * Allows simple GET, POST, PUT, DELETE requests with optional headers.
 * Automatically handles JSON parsing and error catching.
 */
class Http
{
    /**
     * Shared Guzzle client instance.
     */
    protected static ?Client $client = null;

    /**
     * Default headers for the next request.
     */
    protected static array $defaultHeaders = [];

    /**
     * Returns the singleton Guzzle client.
     */
    protected static function getClient(): Client
    {
        if (!static::$client) {
            static::$client = new Client();
        }

        return static::$client;
    }

    /**
     * Sets headers for the next request.
     *
     * @param array $headers
     * @return static
     *
     * Example:
     * Http::withHeaders(['Authorization' => 'Bearer TOKEN'])->get($url);
     */
    public static function withHeaders(array $headers): static
    {
        static::$defaultHeaders = $headers;
        return new static;
    }

    /**
     * Sends a GET request with optional query parameters.
     *
     * @param string $url
     * @param array $query
     * @return mixed JSON-decoded response or string
     *
     * Example:
     * $res = Http::get('https://api.example.com/users', ['page' => 1]);
     */
    public static function get(string $url, array $query = []): mixed
    {
        return static::request('GET', $url, ['query' => $query]);
    }

    /**
     * Sends a POST request with JSON body.
     *
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public static function post(string $url, array $data = []): mixed
    {
        return static::request('POST', $url, ['json' => $data]);
    }

    /**
     * Sends a PUT request with JSON body.
     */
    public static function put(string $url, array $data = []): mixed
    {
        return static::request('PUT', $url, ['json' => $data]);
    }

    /**
     * Sends a DELETE request with JSON body.
     */
    public static function delete(string $url, array $data = []): mixed
    {
        return static::request('DELETE', $url, ['json' => $data]);
    }

    /**
     * Core request handler.
     *
     * Applies default headers, handles JSON decoding, and catches request exceptions.
     *
     * @param string $method HTTP method (GET, POST, etc)
     * @param string $url
     * @param array $options Guzzle options (query, json, headers, etc)
     * @return mixed
     *
     * Example:
     * $res = Http::request('GET', 'https://api.example.com/data');
     */
    protected static function request(string $method, string $url, array $options = []): mixed
    {
        $client = static::getClient();

        // Merge default headers with request-specific headers
        $options['headers'] = array_merge($options['headers'] ?? [], static::$defaultHeaders);

        try {
            $response = $client->request($method, $url, $options);

            $body = (string) $response->getBody();

            // Try to decode JSON if possible
            return json_decode($body, true) ?? $body;

        } catch (RequestException $e) {
            // Return structured error array
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? (string) $e->getResponse()->getBody() : null,
            ];
        } finally {
            // Reset headers after request
            static::$defaultHeaders = [];
        }
    }
}
