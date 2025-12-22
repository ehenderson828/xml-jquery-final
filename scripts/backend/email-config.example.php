<?php
/**
 * Email Configuration Template
 *
 * Copy this file to email-config.php and update with your SMTP settings
 * IMPORTANT: Never commit email-config.php to version control
 */

return [
    // Author email addresses
    'author_emails' => [
        'eric' => 'eric@example.com',
        'inês' => 'ines@example.com',
        'níko' => 'niko@example.com'
    ],

    // Resend API Configuration
    'resend' => [
        'api_key' => 'your-resend-api-key',      // Get from https://resend.com/api-keys
        'from_email' => 'onboarding@resend.dev', // Use Resend's test domain or your verified domain
        'from_name' => 'Eriník Contact Form'     // From name
    ],

    // Email settings
    'settings' => [
        'debug' => false,  // Set to true for debugging
        'charset' => 'UTF-8'
    ]
];
?>
