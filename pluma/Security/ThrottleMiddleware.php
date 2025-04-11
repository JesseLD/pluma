<?php

// ==========================================
// File: pluma/Security/ThrottleMiddleware.php
// Description: Limits repeated requests to prevent brute force attacks
// ==========================================

namespace Pluma\Security;

class ThrottleMiddleware
{
    /**
     * Check and apply throttling limits.
     *
     * @param string $key
     * @param int $maxAttempts
     * @param int $decaySeconds
     * @return bool
     */
    public function attempt(string $key, int $maxAttempts = 5, int $decaySeconds = 60): bool
    {
        session_start();

        $attempts = $_SESSION['throttle'][$key]['count'] ?? 0;
        $expires = $_SESSION['throttle'][$key]['expires'] ?? 0;

        if ($attempts >= $maxAttempts && time() < $expires) {
            http_response_code(429);
            echo 'Too many attempts. Please try again later.';
            return false;
        }

        if (time() > $expires) {
            $attempts = 0;
        }

        $_SESSION['throttle'][$key] = [
            'count' => $attempts + 1,
            'expires' => time() + $decaySeconds
        ];

        return true;
    }
}