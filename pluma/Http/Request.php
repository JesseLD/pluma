<?php

// ==========================================
// File: pluma/Http/Request.php
// Description: Handles HTTP request data and helper methods
// ==========================================

namespace Pluma\Http;

class Request
{
    /**
     * Get all input data from the request.
     *
     * @return array
     */
    public function all(): array
    {
        return $_REQUEST;
    }

    /**
     * Get a single input item from the request.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function input(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }
}