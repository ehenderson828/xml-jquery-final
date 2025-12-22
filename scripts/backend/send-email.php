<?php
// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/contact.html');
    exit;
}

// Load Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// Load configuration: Try environment variables first (production),
// fall back to email-config.php (local development)
function getConfig() {
    // Check if environment variables are set (Railway production)
    if (getenv('RESEND_API_KEY')) {
        return [
            'author_emails' => [
                'eric' => getenv('AUTHOR_EMAIL_ERIC') ?: 'eric.henderson@bhcc.edu',
                'inês' => getenv('AUTHOR_EMAIL_INES') ?: 'ineves@bhcc.edu',
                'níko' => getenv('AUTHOR_EMAIL_NIKO') ?: 'niko.ferdinand@bhcc.edu'
            ],
            'resend' => [
                'api_key' => getenv('RESEND_API_KEY'),
                'from_email' => getenv('RESEND_FROM_EMAIL') ?: 'noreply@erinik.com',
                'from_name' => getenv('RESEND_FROM_NAME') ?: 'Eriník Contact Form'
            ],
            'settings' => [
                'debug' => getenv('DEBUG_MODE') === 'true',
                'charset' => 'UTF-8'
            ]
        ];
    }

    // Fall back to email-config.php (local development)
    $configFile = __DIR__ . '/email-config.php';
    if (!file_exists($configFile)) {
        die('Error: Configuration not found. Set RESEND_API_KEY environment variable or create email-config.php');
    }
    return require $configFile;
}

$config = getConfig();

// Log configuration source for debugging
error_log("Email config loaded. Using env vars: " . (getenv('RESEND_API_KEY') ? 'YES' : 'NO'));

// Get form data and sanitize
$author = isset($_POST['author']) ? trim($_POST['author']) : '';
$fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validate required fields
$errors = [];

if (empty($author) || !isset($config['author_emails'][$author])) {
    $errors[] = 'Invalid author selection';
}

if (empty($fullName)) {
    $errors[] = 'Full name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} 
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// If there are errors, redirect back with error message
if (!empty($errors)) {
    $errorMessage = urlencode(implode(', ', $errors));
    header("Location: ../../pages/contact.html?error=$errorMessage");
    exit;
}

// Prepare email details
$toEmail = $config['author_emails'][$author];
// Capitalize the author first name
$authorName = ucfirst($author);
$subject = "New Contact Form Submission for $authorName";

// Escape all user inputs to prevent XSS
$fullName_safe = htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8');
$email_safe = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$phone_safe = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$message_safe = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

// Create email body
$emailBodyHTML = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #534741; color: #fff8e1; padding: 20px; text-align: center; }
        .content { background-color: #fff; padding: 20px; border: 2px solid #c7b299; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #534741; }
        .footer { margin-top: 20px; padding: 10px; text-align: center; font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Contact Form Submission</h2>
        </div>
        <div class='content'>
            <p>Hello $authorName,</p>
            <p>You have received a new contact form submission:</p>

            <div class='field'>
                <span class='label'>Name:</span><br>
                $fullName_safe
            </div>

            <div class='field'>
                <span class='label'>Email:</span><br>
                <a href='mailto:$email_safe'>$email_safe</a>
            </div>

            <div class='field'>
                <span class='label'>Phone:</span><br>
                $phone_safe
            </div>

            <div class='field'>
                <span class='label'>Message:</span><br>
                $message_safe
            </div>
        </div>
        <div class='footer'>
            This message was sent from the Eriník contact form.
        </div>
    </div>
</body>
</html>
";

// Create plain text version
$emailBodyText = "New Contact Form Submission for $authorName\n\n";
$emailBodyText .= "Details:\n";
$emailBodyText .= "--------\n";
$emailBodyText .= "Name: $fullName\n";
$emailBodyText .= "Email: $email\n";
$emailBodyText .= "Phone: $phone\n";
$emailBodyText .= "Message:\n$message\n";
$emailBodyText .= "--------\n\n";
$emailBodyText .= "This message was sent from the Eriník contact form.";

// Initialize Resend
$resend = Resend::client($config['resend']['api_key']);

// Start timing for performance measurement
$startTime = microtime(true);
$timings = [];
$timings['start'] = 0;

try {
    // Timing checkpoint: API configured
    $timings['api_configured'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';

    // Send email via Resend
    $result = $resend->emails->send([
        'from' => $config['resend']['from_name'] . ' <' . $config['resend']['from_email'] . '>',
        'to' => [$toEmail],
        'reply_to' => $email,
        'subject' => $subject,
        'html' => $emailBodyHTML,
        'text' => $emailBodyText
    ]);

    // Timing checkpoint: Email sent
    $timings['email_sent'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';
    $timings['total'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';

    // Log the timings for performance analysis
    error_log("Email Send Performance Timings: " . json_encode($timings) . " | Email ID: " . ($result->id ?? 'unknown'));

    // Also log to a dedicated file for easier tracking
    $logFile = __DIR__ . '/../../logs/email-timing.log';
    $logDir = dirname($logFile);
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logEntry = date('Y-m-d H:i:s') . " | SUCCESS | " . json_encode($timings) . " | Email ID: " . ($result->id ?? 'unknown') . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    // Success - redirect back with success message
    header('Location: ../../pages/contact.html?success=1');

}
catch (Exception $e) {
    // Timing checkpoint: Error occurred
    $timings['error_occurred'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';
    $timings['total'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';

    // Log the timings and error
    error_log("Email Send Failed - Timings: " . json_encode($timings) . " | Error: " . $e->getMessage());

    // Also log to dedicated file
    $logFile = __DIR__ . '/../../logs/email-timing.log';
    $logDir = dirname($logFile);
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logEntry = date('Y-m-d H:i:s') . " | ERROR | " . json_encode($timings) . " | " . $e->getMessage() . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    // Failed - redirect back with error
    $errorMsg = $config['settings']['debug']
        ? "Failed to send email: " . $e->getMessage()
        : 'Failed to send email. Please try again later.';

    header('Location: ../../pages/contact.html?error=' . urlencode($errorMsg));
}

exit;
?>
