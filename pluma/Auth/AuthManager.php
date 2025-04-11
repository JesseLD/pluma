<?php

// ==========================================
// File: pluma/Auth/AuthManager.php
// Description: Provides access to guards for authentication
// ==========================================

namespace Pluma\Auth;

class AuthManager
{
    /**
     * Get the current authenticated user from session.
     *
     * @return mixed|null
     */
    public function user(): mixed
    {
        session_start();
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check if user is authenticated.
     *
     * @return bool
     */
    public function check(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Log out the current user.
     */
    public function logout(): void
    {
        session_start();
        unset($_SESSION['user']);
    }
}
