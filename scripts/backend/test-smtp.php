<?php
/**
 * SMTP Connection Test Script
 *
 * This script tests SMTP connectivity without sending an actual email.
 * Use this to diagnose connection issues on Railway.
 *
 * Access via: https://your-railway-url/scripts/backend/test-smtp.php
 */

// Load Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Set to show all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>SMTP Connection Test</h1>";
echo "<pre>";

// Check environment variables
echo "=== ENVIRONMENT VARIABLES ===\n";
echo "SMTP_HOST: " . (getenv('SMTP_HOST') ?: 'NOT SET') . "\n";
echo "SMTP_USERNAME: " . (getenv('SMTP_USERNAME') ?: 'NOT SET') . "\n";
echo "SMTP_PASSWORD: " . (getenv('SMTP_PASSWORD') ? '***SET (length: ' . strlen(getenv('SMTP_PASSWORD')) . ')***' : 'NOT SET') . "\n";
echo "SMTP_PORT: " . (getenv('SMTP_PORT') ?: '587 (default)') . "\n";
echo "SMTP_ENCRYPTION: " . (getenv('SMTP_ENCRYPTION') ?: 'tls (default)') . "\n\n";

// Check if configuration exists
if (!getenv('SMTP_HOST')) {
    echo "❌ ERROR: Environment variables not set!\n";
    echo "Please set SMTP_HOST, SMTP_USERNAME, and SMTP_PASSWORD in Railway dashboard.\n";
    echo "</pre>";
    exit;
}

// Test SMTP connection
echo "=== SMTP CONNECTION TEST ===\n";
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
    $mail->isSMTP();
    $mail->Host       = getenv('SMTP_HOST');
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('SMTP_USERNAME');
    $mail->Password   = getenv('SMTP_PASSWORD');
    $mail->SMTPSecure = getenv('SMTP_ENCRYPTION') ?: 'tls';
    $mail->Port       = getenv('SMTP_PORT') ?: 587;
    $mail->Timeout    = 10; // 10 second timeout

    // Try to connect
    echo "\nAttempting to connect to SMTP server...\n";
    $mail->smtpConnect();

    echo "\n✅ SUCCESS! SMTP connection established.\n";
    echo "Your SMTP credentials are working correctly.\n";

    // Close connection
    $mail->smtpClose();

} catch (Exception $e) {
    echo "\n❌ ERROR: Could not connect to SMTP server.\n";
    echo "Error details: " . $mail->ErrorInfo . "\n\n";

    // Provide troubleshooting suggestions
    echo "=== TROUBLESHOOTING SUGGESTIONS ===\n";

    if (strpos($mail->ErrorInfo, 'timeout') !== false || strpos($mail->ErrorInfo, 'timed out') !== false) {
        echo "⚠️  TIMEOUT ERROR detected\n\n";
        echo "Possible causes:\n";
        echo "1. Railway may be blocking port " . ($mail->Port) . "\n";
        echo "2. Try switching to port 465 with SSL encryption\n";
        echo "   - Set SMTP_PORT=465\n";
        echo "   - Set SMTP_ENCRYPTION=ssl\n\n";
        echo "3. Gmail may be blocking Railway's IP addresses\n";
        echo "   - Check https://myaccount.google.com/security for blocked sign-ins\n";
        echo "   - Consider using a different SMTP provider (SendGrid, Mailgun, etc.)\n\n";
    }

    if (strpos($mail->ErrorInfo, 'authentication') !== false || strpos($mail->ErrorInfo, 'credentials') !== false) {
        echo "⚠️  AUTHENTICATION ERROR detected\n\n";
        echo "Possible causes:\n";
        echo "1. Wrong SMTP username or password\n";
        echo "2. Gmail app password may have been revoked\n";
        echo "3. Generate a new app password at: https://myaccount.google.com/apppasswords\n\n";
    }
}

echo "</pre>";
?>
