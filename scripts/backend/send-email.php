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
    if (getenv('SMTP_HOST')) {
        return [
            'author_emails' => [
                'eric' => getenv('AUTHOR_EMAIL_ERIC') ?: 'eric.henderson@bhcc.edu',
                'inês' => getenv('AUTHOR_EMAIL_INES') ?: 'ineves@bhcc.edu',
                'níko' => getenv('AUTHOR_EMAIL_NIKO') ?: 'niko.ferdinand@bhcc.edu'
            ],
            'smtp' => [
                'host' => getenv('SMTP_HOST'),
                'port' => getenv('SMTP_PORT') ?: 587,
                'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
                'username' => getenv('SMTP_USERNAME'),
                'password' => getenv('SMTP_PASSWORD'),
                'from_email' => getenv('SMTP_FROM_EMAIL') ?: 'noreply@erinik.com',
                'from_name' => getenv('SMTP_FROM_NAME') ?: 'Eriník Contact Form'
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
        die('Error: Configuration not found. Set environment variables or create email-config.php');
    }
    return require $configFile;
}

$config = getConfig();

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
                $fullName
            </div>

            <div class='field'>
                <span class='label'>Email:</span><br>
                <a href='mailto:$email'>$email</a>
            </div>

            <div class='field'>
                <span class='label'>Phone:</span><br>
                $phone
            </div>

            <div class='field'>
                <span class='label'>Message:</span><br>
                " . nl2br(htmlspecialchars($message)) . "
            </div>
        </div>
        <div class='footer'>
            This message was sent from the Eriník contact form.
        </div>
    </div>
</body>
</html>
";

// Create plain text version if unable to configure html
$emailBodyText = "New Contact Form Submission for $authorName\n\n";
$emailBodyText .= "Details:\n";
$emailBodyText .= "--------\n";
$emailBodyText .= "Name: $fullName\n";
$emailBodyText .= "Email: $email\n";
$emailBodyText .= "Phone: $phone\n";
$emailBodyText .= "Message:\n$message\n";
$emailBodyText .= "--------\n\n";
$emailBodyText .= "This message was sent from the Eriník contact form.";

// Create PHPMailer instance
$mail = new PHPMailer(true);

// Start timing for performance measurement
$startTime = microtime(true);
$timings = [];
$timings['start'] = 0;

try {
    // Server settings
    if ($config['settings']['debug']) {
        $mail->SMTPDebug = 2; // Enable verbose debug output for local testing
    }

    $mail->isSMTP();
    $mail->Host       = $config['smtp']['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['smtp']['username'];
    $mail->Password   = $config['smtp']['password'];
    $mail->SMTPSecure = $config['smtp']['encryption'];
    $mail->Port       = $config['smtp']['port'];
    $mail->CharSet    = $config['settings']['charset'];

    // Timing checkpoint: SMTP configured
    $timings['smtp_configured'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';

    // Recipients
    $mail->setFrom($config['smtp']['from_email'], $config['smtp']['from_name']);
    $mail->addAddress($toEmail, $authorName);
    $mail->addReplyTo($email, $fullName);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $emailBodyHTML;
    $mail->AltBody = $emailBodyText;

    // Send email
    $mail->send();

    // Timing checkpoint: Email sent
    $timings['email_sent'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';
    $timings['total'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';

    // Log the timings for performance analysis
    error_log("Email Send Performance Timings: " . json_encode($timings));

    // Also log to a dedicated file for easier tracking
    $logFile = __DIR__ . '/../../logs/email-timing.log';
    $logDir = dirname($logFile);
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logEntry = date('Y-m-d H:i:s') . " | SUCCESS | " . json_encode($timings) . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    // Success - redirect back with success message
    header('Location: ../../pages/contact.html?success=1');

}
catch (Exception $e) {
    // Timing checkpoint: Error occurred
    $timings['error_occurred'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';
    $timings['total'] = round((microtime(true) - $startTime) * 1000, 2) . 'ms';

    // Log the timings and error
    error_log("Email Send Failed - Timings: " . json_encode($timings) . " | Error: " . $mail->ErrorInfo);

    // Also log to dedicated file
    $logFile = __DIR__ . '/../../logs/email-timing.log';
    $logDir = dirname($logFile);
    if (!file_exists($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $logEntry = date('Y-m-d H:i:s') . " | ERROR | " . json_encode($timings) . " | " . $mail->ErrorInfo . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND);

    // Failed - redirect back with error
    $errorMsg = $config['settings']['debug']
        ? "Failed to send email: {$mail->ErrorInfo}"
        : 'Failed to send email. Please try again later.';

    header('Location: ../../pages/contact.html?error=' . urlencode($errorMsg));
}

exit;
?>
