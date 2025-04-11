<?php

// ==========================================
// File: pluma/View/View.php
// Description: Simple view renderer with variable injection
// ==========================================

namespace Pluma\View;

class View
{
    /**
     * Render a view file with data.
     *
     * @param string $path
     * @param array $data
     */
    public static function render(string $path, array $data = []): void
    {
        extract($data);
        include base_path("views/{$path}.php");
    }
}
