<?php

// ==========================================
// File: pluma/Support/Str.php
// Description: Utility class for string manipulation
// ==========================================

namespace Pluma\Support;

class Str
{
    /**
     * Convert string to slug format.
     *
     * @param string $string
     * @return string
     */
    public static function slug(string $string): string
    {
        return strtolower(preg_replace('/[^a-z0-9]+/', '-', trim($string)));
    }

    /**
     * Convert string to camelCase.
     *
     * @param string $string
     * @return string
     */
    public static function camel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string))));
    }
}