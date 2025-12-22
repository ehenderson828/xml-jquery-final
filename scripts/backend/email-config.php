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

    // Resend API configuration
    'resend' => [
        'api_key' => 're_JgnV5ZDM_6uCh9GkieGhW9TFFPrgXQEd8',  // Your Resend API key
        'from_email' => 'noreply@erinik.com',  // Your verified domain
        'from_name' => 'Eriník Contact Form'
    ],

    // Email settings - behavioral configuration
    'settings' => [
        'debug' => true,  // Set to true for debugging
        'charset' => 'UTF-8'
    ]
];
?>
