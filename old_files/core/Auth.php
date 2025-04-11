<?php

namespace Core;

use App\Models\User;

class Auth
{
    /**
     * Tries to authenticate a user using email/username and password.
     *
     * @param string $field Field name to search by (e.g. "email" or "username")
     * @param string $value The field value (e.g. the user's email)
     * @param string $password The plain-text password to verify
     *
     * @return bool True if authentication succeeded, false otherwise
     *
     * Example:
     * if (Auth::attempt('email', $request['email'], $request['password'])) {
     *     redirect('/dashboard');
     * }
     */
    public static function attempt(string $field, string $value, string $password): bool
    {
        // You must have a User model with a static "where" method
        $user = User::where($field, $value)->first();

        // If user not found or password doesn't match, return false
        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        // Authentication successful: store user ID in session
        Session::set('user_id', $user->id);
        return true;
    }

    /**
     * Returns the currently authenticated user (or null).
     */
    public static function user(): ?User
    {
        $id = Session::get('user_id');
        if (!$id) return null;

        return User::find($id);
    }

    /**
     * Checks if a user is authenticated.
     */
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    /**
     * Logs the user out.
     */
    public static function logout(): void
    {
        Session::remove('user_id');
    }

    /**
     * Returns the current user's ID.
     */
    public static function id(): ?int
    {
        return Session::get('user_id');
    }
}
