<?php

// ==========================================
// File: pluma/Security/CsrfMiddleware.php
// Description: Protects against Cross-Site Request Forgery attacks
// ==========================================

namespace Pluma\Security;

class CsrfMiddleware
{
    /**
     * Verify that the CSRF token is valid.
     *
     * @return bool
     */
    public function verify(): bool
    {
        session_start();

        $token = $_POST['_token'] ?? '';
        $valid = isset($_SESSION['_token']) && hash_equals($_SESSION['_token'], $token);

        if (!$valid) {
            http_response_code(419);
            echo 'CSRF token mismatch.';
        }

        return $valid;
    }

    /**
     * Generate a CSRF token and store it in the session.
     *
     * @return string
     */
    public static function generate(): string
    {
        session_start();
        return $_SESSION['_token'] = bin2hex(random_bytes(32));
    }
}