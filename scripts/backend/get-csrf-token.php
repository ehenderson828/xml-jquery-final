<?php
/**
 * CSRF Token Generation Endpoint
 *
 * Returns a fresh CSRF token for the contact form
 */

// Configure session for Railway/production environments
ini_set('session.cookie_lifetime', '3600'); // 1 hour
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', ''); // Empty = current domain
ini_set('session.cookie_secure', '0'); // Allow non-HTTPS for now
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');

// Use /tmp for sessions on Railway (writable directory)
$sessionPath = sys_get_temp_dir();
if (is_writable($sessionPath)) {
    session_save_path($sessionPath);
}

// Enable CORS for same-origin requests
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Load CSRF token helper
require_once __DIR__ . '/csrf-token.php';

try {
    // Generate and return token
    $token = CSRFToken::get();

    echo json_encode([
        'success' => true,
        'token' => $token,
        'token_name' => CSRFToken::getTokenName()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to generate CSRF token',
        'message' => $e->getMessage()
    ]);
}
?>
