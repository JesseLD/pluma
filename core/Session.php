<?php

namespace Core;

/**
 * Session management utility for handling session state, values, and flash messages.
 */
class Session
{
    /**
     * Starts the session if it's not already active.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Retrieves a session value by key.
     *
     * @param string $key The session key to retrieve.
     * @param mixed $default Optional default value if key is not found.
     * @return mixed The value from the session or the default.
     *
     * Example:
     * $userId = Session::get('user_id');
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Sets a session value.
     *
     * @param string $key The key to store the value under.
     * @param mixed $value The value to store.
     *
     * Example:
     * Session::set('user_id', 123);
     */
    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Checks if a session key exists.
     *
     * @param string $key The key to check.
     * @return bool True if key exists in session.
     *
     * Example:
     * if (Session::has('user_id')) { ... }
     */
    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Removes a session key.
     *
     * @param string $key The key to remove.
     *
     * Example:
     * Session::remove('user_id');
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Destroys the entire session.
     *
     * Example:
     * Session::destroy();
     */
    public static function destroy(): void
    {
        self::start();
        session_destroy();
    }

    /**
     * Sets or retrieves a flash message. Flash data is only available for one request.
     *
     * @param string $key The flash key.
     * @param string|null $value Optional value to set.
     * @return mixed The flash value if getting, or null if setting.
     *
     * Example (set):
     * Session::flash('success', 'User saved successfully.');
     *
     * Example (get):
     * $message = Session::flash('success');
     */
    public static function flash(string $key, ?string $value = null): mixed
    {
        self::start();

        // Set flash value
        if ($value !== null) {
            $_SESSION['_flash'][$key] = $value;
            return null;
        }

        // Get and delete flash value
        $val = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $val;
    }
}
