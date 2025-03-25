<?php

namespace Core;

use App\Models\User;

class Auth
{
    public static function user(): ?User
    {
        $id = Session::get('user_id');
        if (!$id) return null;

        return User::find($id);
    }

    public static function login(int $userId): void
    {
        Session::set('user_id', $userId);
    }

    public static function logout(): void
    {
        Session::remove('user_id');
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }
}
