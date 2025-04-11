<?php

// ==========================================
// File: pluma/Support/Arr.php
// Description: Utility class for array access and manipulation
// ==========================================

namespace Pluma\Support;

class Arr
{
    /**
     * Get a value from an array using a key.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        return $array[$key] ?? $default;
    }
}
