<?php

// ==========================================
// File: pluma/Http/Response.php
// Description: Sends HTTP responses in various formats
// ==========================================

namespace Pluma\Http;

class Response
{
    /**
     * Return a JSON response.
     *
     * @param mixed $data
     */
    public static function json(mixed $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Return a plain text response.
     *
     * @param string $text
     */
    public static function text(string $text): void
    {
        header('Content-Type: text/plain');
        echo $text;
    }
}