<?php
/**
 * Email Configuration Template
 *
 * 
 * IMPORTANT: Never commit email-config.php to version control
 */

return [
    // Author email addresses - mapped to an array
    'author_emails' => [
        'eric' => 'eric.henderson@bhcc.edu',
        'inês' => 'ineves@bhcc.edu',
        'níko' => 'niko.ferdinand@bhcc.edu'
    ],

    // SMTP authentication credentials
    'smtp' => [
        'host' => 'smtp.gmail.com',           // SMTP server (e.g., smtp.gmail.com, smtp.office365.com)
        'port' => 587,                         // SMTP port (587 for TLS, 465 for SSL)
        'encryption' => 'tls',                 // Encryption: 'tls' or 'ssl'
        'username' => 'erinik.contact@gmail.com',   // SMTP username
        'password' => 'lylzvlpwegnicpot',    // SMTP password or app password
        // Email headers
        'from_email' => 'noreply@erinik.com', // From email address
        'from_name' => 'Eriník Contact Form'  // From name
    ],

    // Email settings - behaviorial configuration
    'settings' => [
        'debug' => false,  // Set to true for debugging (shows SMTP errors)
        'charset' => 'UTF-8'
    ]
];
?>
