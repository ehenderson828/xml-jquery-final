<?php
/**
 * CSRF Token Generator and Validator
 *
 * Provides protection against Cross-Site Request Forgery attacks
 */

class CSRFToken {
    private static $tokenName = 'csrf_token';
    private static $tokenTime = 'csrf_token_time';
    private static $tokenLifetime = 3600; // 1 hour in seconds

    /**
     * Generate a new CSRF token
     *
     * @return string The generated token
     */
    public static function generate() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Generate a cryptographically secure random token
        $token = bin2hex(random_bytes(32));

        // Store token and timestamp in session
        $_SESSION[self::$tokenName] = $token;
        $_SESSION[self::$tokenTime] = time();

        return $token;
    }

    /**
     * Get the current CSRF token (generate if doesn't exist)
     *
     * @return string The current token
     */
    public static function get() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if token exists and is still valid
        if (!isset($_SESSION[self::$tokenName]) || !isset($_SESSION[self::$tokenTime])) {
            return self::generate();
        }

        // Check if token has expired
        $tokenAge = time() - $_SESSION[self::$tokenTime];
        if ($tokenAge > self::$tokenLifetime) {
            return self::generate();
        }

        return $_SESSION[self::$tokenName];
    }

    /**
     * Validate a CSRF token
     *
     * @param string $token The token to validate
     * @return bool True if valid, false otherwise
     */
    public static function validate($token) {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if token exists in session
        if (!isset($_SESSION[self::$tokenName]) || !isset($_SESSION[self::$tokenTime])) {
            return false;
        }

        // Check if token has expired
        $tokenAge = time() - $_SESSION[self::$tokenTime];
        if ($tokenAge > self::$tokenLifetime) {
            return false;
        }

        // Use hash_equals to prevent timing attacks
        return hash_equals($_SESSION[self::$tokenName], $token);
    }

    /**
     * Get HTML hidden input field for CSRF token
     *
     * @return string HTML input field
     */
    public static function getHiddenField() {
        $token = self::get();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Get token name for use in AJAX requests
     *
     * @return string Token name
     */
    public static function getTokenName() {
        return self::$tokenName;
    }

    /**
     * Destroy the current token
     */
    public static function destroy() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION[self::$tokenName]);
        unset($_SESSION[self::$tokenTime]);
    }
}
?>
