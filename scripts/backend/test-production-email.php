<?php
/**
 * Production Email Test Endpoint
 *
 * Access this file via: https://erinik.com/scripts/backend/test-production-email.php
 *
 * This script tests the Resend email configuration in production.
 * DELETE THIS FILE after verifying emails work!
 */

// Enable error display for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// Start output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eriník Email Test - Production</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #534741;
            border-bottom: 3px solid #c7b299;
            padding-bottom: 10px;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #fff8e1;
            border-left: 4px solid #c7b299;
            border-radius: 4px;
        }
        .success {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        .info {
            background: #d1ecf1;
            border-left-color: #17a2b8;
            color: #0c5460;
        }
        .warning {
            background: #fff3cd;
            border-left-color: #ffc107;
            color: #856404;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .check-item {
            padding: 8px 0;
        }
        .check-item::before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-right: 5px;
        }
        .check-item.fail::before {
            content: "✗ ";
            color: #dc3545;
        }
        button {
            background: #534741;
            color: #fff8e1;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: #3e332f;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚂 Eriník Email Test - Railway Production</h1>

        <?php
        // Load configuration
        function getConfig() {
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

            $configFile = __DIR__ . '/email-config.php';
            if (!file_exists($configFile)) {
                return null;
            }
            return require $configFile;
        }

        $config = getConfig();

        // Check if we're testing via form submission
        $sendTest = isset($_POST['send_test']) && $_POST['send_test'] === '1';
        $testEmail = isset($_POST['test_email']) ? trim($_POST['test_email']) : '';

        if (!$sendTest) {
            // Display configuration check
            echo '<div class="section info">';
            echo '<h2>📋 Configuration Check</h2>';

            if (!$config) {
                echo '<div class="check-item fail">Configuration not found</div>';
                echo '<p><strong>Error:</strong> No environment variables or email-config.php found.</p>';
            } else {
                echo '<div class="check-item">Configuration loaded successfully</div>';

                // Check environment variables
                if (getenv('RESEND_API_KEY')) {
                    echo '<div class="check-item">Using environment variables (Production mode)</div>';
                } else {
                    echo '<div class="check-item">Using email-config.php (Local mode)</div>';
                }

                // Display config (masked)
                echo '<h3>Current Configuration:</h3>';
                echo '<ul>';
                echo '<li><strong>API Key:</strong> <code>' . substr($config['resend']['api_key'], 0, 10) . '...</code></li>';
                echo '<li><strong>From Email:</strong> <code>' . htmlspecialchars($config['resend']['from_email']) . '</code></li>';
                echo '<li><strong>From Name:</strong> <code>' . htmlspecialchars($config['resend']['from_name']) . '</code></li>';
                echo '<li><strong>Debug Mode:</strong> <code>' . ($config['settings']['debug'] ? 'true' : 'false') . '</code></li>';
                echo '</ul>';

                echo '<h3>Author Emails:</h3>';
                echo '<ul>';
                foreach ($config['author_emails'] as $author => $email) {
                    echo '<li><strong>' . htmlspecialchars(ucfirst($author)) . ':</strong> <code>' . htmlspecialchars($email) . '</code></li>';
                }
                echo '</ul>';
            }
            echo '</div>';

            if ($config) {
                // Show test form
                echo '<div class="section">';
                echo '<h2>📧 Send Test Email</h2>';
                echo '<p>Send a test email to verify Resend is working correctly.</p>';
                echo '<form method="POST">';
                echo '<input type="hidden" name="send_test" value="1">';
                echo '<label for="test_email"><strong>Send test email to:</strong></label><br>';
                echo '<input type="email" id="test_email" name="test_email" value="' . htmlspecialchars($config['author_emails']['eric']) . '" style="width: 100%; padding: 8px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px;" required>';
                echo '<button type="submit">Send Test Email</button>';
                echo '</form>';
                echo '</div>';
            }

        } else {
            // Send test email
            echo '<div class="section info">';
            echo '<h2>📤 Sending Test Email...</h2>';
            echo '</div>';

            if (!$config) {
                echo '<div class="section error">';
                echo '<h3>❌ Configuration Error</h3>';
                echo '<p>Cannot send email: Configuration not found.</p>';
                echo '</div>';
            } elseif (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
                echo '<div class="section error">';
                echo '<h3>❌ Invalid Email</h3>';
                echo '<p>Please provide a valid email address.</p>';
                echo '</div>';
            } else {
                try {
                    // Initialize Resend
                    $resend = Resend::client($config['resend']['api_key']);

                    // Prepare test email
                    $subject = 'Test Email from Eriník Contact Form';
                    $htmlBody = '
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                            .header { background-color: #534741; color: #fff8e1; padding: 20px; text-align: center; }
                            .content { background-color: #fff; padding: 20px; border: 2px solid #c7b299; }
                            .footer { margin-top: 20px; padding: 10px; text-align: center; font-size: 0.9em; color: #666; }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h2>🎉 Test Email Successful!</h2>
                            </div>
                            <div class="content">
                                <p>Congratulations! Your Resend email configuration is working correctly.</p>
                                <p><strong>Configuration Details:</strong></p>
                                <ul>
                                    <li>From: ' . htmlspecialchars($config['resend']['from_name']) . ' &lt;' . htmlspecialchars($config['resend']['from_email']) . '&gt;</li>
                                    <li>Sent via: Resend API</li>
                                    <li>Domain: erinik.com</li>
                                    <li>Environment: ' . (getenv('RESEND_API_KEY') ? 'Production (Railway)' : 'Local Development') . '</li>
                                </ul>
                                <p>Your contact form is now ready to receive submissions!</p>
                            </div>
                            <div class="footer">
                                This is a test message from the Eriník contact form system.
                            </div>
                        </div>
                    </body>
                    </html>
                    ';

                    $textBody = "Test Email Successful!\n\n";
                    $textBody .= "Congratulations! Your Resend email configuration is working correctly.\n\n";
                    $textBody .= "Configuration Details:\n";
                    $textBody .= "- From: {$config['resend']['from_name']} <{$config['resend']['from_email']}>\n";
                    $textBody .= "- Sent via: Resend API\n";
                    $textBody .= "- Domain: erinik.com\n";
                    $textBody .= "- Environment: " . (getenv('RESEND_API_KEY') ? 'Production (Railway)' : 'Local Development') . "\n\n";
                    $textBody .= "Your contact form is now ready to receive submissions!";

                    // Send email
                    $startTime = microtime(true);
                    $result = $resend->emails->send([
                        'from' => $config['resend']['from_name'] . ' <' . $config['resend']['from_email'] . '>',
                        'to' => [$testEmail],
                        'subject' => $subject,
                        'html' => $htmlBody,
                        'text' => $textBody
                    ]);
                    $endTime = microtime(true);
                    $duration = round(($endTime - $startTime) * 1000, 2);

                    // Success!
                    echo '<div class="section success">';
                    echo '<h3>✅ Email Sent Successfully!</h3>';
                    echo '<p><strong>Email ID:</strong> <code>' . htmlspecialchars($result->id) . '</code></p>';
                    echo '<p><strong>Recipient:</strong> <code>' . htmlspecialchars($testEmail) . '</code></p>';
                    echo '<p><strong>Send Time:</strong> <code>' . $duration . 'ms</code></p>';
                    echo '<p><strong>From:</strong> <code>' . htmlspecialchars($config['resend']['from_name']) . ' &lt;' . htmlspecialchars($config['resend']['from_email']) . '&gt;</code></p>';
                    echo '</div>';

                    echo '<div class="section info">';
                    echo '<h3>📬 Next Steps</h3>';
                    echo '<ol>';
                    echo '<li>Check the inbox of <strong>' . htmlspecialchars($testEmail) . '</strong></li>';
                    echo '<li>Verify the email arrived (check spam/junk folder if needed)</li>';
                    echo '<li>Test the contact form on your live site: <a href="https://erinik.com/pages/contact.html" target="_blank">https://erinik.com/pages/contact.html</a></li>';
                    echo '<li><strong>DELETE THIS TEST FILE</strong> after verification for security</li>';
                    echo '</ol>';
                    echo '</div>';

                    echo '<div class="section warning">';
                    echo '<h3>⚠️ Security Reminder</h3>';
                    echo '<p>Remember to <strong>delete this test file</strong> (<code>test-production-email.php</code>) after verifying your email setup works correctly. Test endpoints should not remain accessible in production.</p>';
                    echo '</div>';

                } catch (Exception $e) {
                    // Error occurred
                    echo '<div class="section error">';
                    echo '<h3>❌ Email Send Failed</h3>';
                    echo '<p><strong>Error Message:</strong></p>';
                    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
                    echo '<h4>Troubleshooting:</h4>';
                    echo '<ul>';
                    echo '<li>Verify your Resend API key is correct</li>';
                    echo '<li>Check that your domain (erinik.com) is verified in Resend</li>';
                    echo '<li>Ensure the "from" email uses your verified domain</li>';
                    echo '<li>Check Railway logs for more details</li>';
                    echo '</ul>';
                    echo '</div>';
                }
            }

            // Back button
            echo '<div style="margin-top: 20px;">';
            echo '<a href="test-production-email.php" style="text-decoration: none;"><button>← Back to Test Page</button></a>';
            echo '</div>';
        }
        ?>

        <div class="section" style="margin-top: 30px; background: #f8f9fa; border-left-color: #6c757d;">
            <h3>ℹ️ About This Test</h3>
            <p>This test script verifies that:</p>
            <ul>
                <li>Railway environment variables are loaded correctly</li>
                <li>Resend API key is valid</li>
                <li>Your verified domain (erinik.com) is configured properly</li>
                <li>Emails can be sent successfully from production</li>
            </ul>
            <p><strong>⚠️ Important:</strong> Delete this file after testing!</p>
        </div>
    </div>
</body>
</html>
