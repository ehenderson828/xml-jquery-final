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

    // SMTP Configuration
    'smtp' => [
        'host' => 'smtp.gmail.com',           // SMTP server (e.g., smtp.gmail.com, smtp.office365.com)
        'port' => 587,                         // SMTP port (587 for TLS, 465 for SSL)
        'encryption' => 'tls',                 // Encryption: 'tls' or 'ssl'
        'username' => 'your-email@gmail.com', // SMTP username
        'password' => 'your-app-password',    // SMTP password or app password
        'from_email' => 'noreply@erinik.com', // From email address
        'from_name' => 'Eriník Contact Form'  // From name
    ],

    // Email settings
    'settings' => [
        'debug' => false,  // Set to true for debugging (shows SMTP errors)
        'charset' => 'UTF-8'
    ]
];
?>
