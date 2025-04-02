<?php

namespace Core;

class CSRF
{
    /**
     * Generates and stores a CSRF token in session (if not already present).
     */
    public static function generate(): string
    {
        Session::start();

        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }

    /**
     * Returns a hidden input field to include in forms.
     */
    public static function tokenField(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Validates the CSRF token from POST request.
     */
    public static function validate(): void
    {
        Session::start();

        $expected = $_SESSION['_csrf_token'] ?? null;
        $received = $_POST['_csrf'] ?? null;

        if (!$expected || !$received || !hash_equals($expected, $received)) {
            Response::exception(Exceptions::CSRF_TOKEN_MISMATCH);
        }
    }
}
