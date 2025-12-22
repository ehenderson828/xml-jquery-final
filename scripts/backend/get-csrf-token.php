<?php
/**
 * CSRF Token Generation Endpoint
 *
 * Returns a fresh CSRF token for the contact form
 */

// Enable CORS for same-origin requests
header('Content-Type: application/json');

// Load CSRF token helper
require_once __DIR__ . '/csrf-token.php';

// Generate and return token
$token = CSRFToken::get();

echo json_encode([
    'success' => true,
    'token' => $token,
    'token_name' => CSRFToken::getTokenName()
]);
?>
