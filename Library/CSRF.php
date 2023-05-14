<?php

namespace Project\Library;

/**
 * Cross-site request forgery protection
 */
class CSRF
{
    /**
     * Generate CSRF input field
     */
    public static function getInputField(): string
    {
        $token = self::generateToken();
        $_SESSION['csrf_token'] = $token;
        return '<input name="token" value="' . $token . '" hidden>';
    }

    /**
     * Check if CSRF token is valid
     * 
     * @param string $token
     * 
     * @return bool
     */
    public static function isTokenValid(string $token): bool
    {
        if ($_SESSION['csrf_token'] === $token)
            return true;

        return false;
    }

    /**
     * Generate CSRF token
     * 
     * @return string
     */
    private static function generateToken(): string
    {
        return md5(uniqid());
    }
}