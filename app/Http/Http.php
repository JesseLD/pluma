<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Http
{
    protected static ?Client $client = null;
    protected static array $defaultHeaders = [];

    protected static function getClient(): Client
    {
        if (!static::$client) {
            static::$client = new Client();
        }

        return static::$client;
    }

    public static function withHeaders(array $headers): static
    {
        static::$defaultHeaders = $headers;
        return new static;
    }

    public static function get(string $url, array $query = []): mixed
    {
        return static::request('GET', $url, ['query' => $query]);
    }

    public static function post(string $url, array $data = []): mixed
    {
        return static::request('POST', $url, ['json' => $data]);
    }

    public static function put(string $url, array $data = []): mixed
    {
        return static::request('PUT', $url, ['json' => $data]);
    }

    public static function delete(string $url, array $data = []): mixed
    {
        return static::request('DELETE', $url, ['json' => $data]);
    }

    protected static function request(string $method, string $url, array $options = []): mixed
    {
        $client = static::getClient();
        $options['headers'] = array_merge($options['headers'] ?? [], static::$defaultHeaders);

        try {
            $response = $client->request($method, $url, $options);
            $body = (string) $response->getBody();
            return json_decode($body, true) ?? $body;
        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? (string) $e->getResponse()->getBody() : null,
            ];
        } finally {
            static::$defaultHeaders = []; // limpa os headers pra próxima requisição
        }
    }
}
