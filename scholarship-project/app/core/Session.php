<?php
/**
 * Session helper – wraps PHP session management.
 */
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        self::start();
        session_unset();
        session_destroy();
    }

    /** Flash messages – set once, read once */
    public static function flash(string $key, string $message): void
    {
        self::set('flash_' . $key, $message);
    }

    public static function getFlash(string $key): ?string
    {
        $msg = self::get('flash_' . $key);
        self::remove('flash_' . $key);
        return $msg;
    }

    public static function isLoggedIn(): bool
    {
        return self::has('user_id');
    }

    public static function isAdmin(): bool
    {
        return self::get('role') === 'admin';
    }

    public static function isStudent(): bool
    {
        return self::get('role') === 'student';
    }

    public static function userId(): ?int
    {
        $id = self::get('user_id');
        return $id !== null ? (int)$id : null;
    }
}
