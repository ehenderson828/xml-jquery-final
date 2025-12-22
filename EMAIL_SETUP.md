# Email Setup Guide

This guide will help you configure the Resend-based contact form for the Eriník website.

## Prerequisites

- PHP 7.4 or higher
- Composer (PHP package manager)
- A Resend account with verified domain (for production) or test domain (for development)

## Installation Steps

### 1. Install Composer (if not already installed)

**Linux/macOS:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**Windows:**
Download and install from [getcomposer.org](https://getcomposer.org/)

### 2. Install Dependencies

Navigate to the project directory and run:

```bash
cd /home/ehenderson828/bhcc/fall2025/xml/xml-jquery-final
composer install
```

This will install the Resend PHP SDK and create a `vendor/` directory.

### 3. Get a Resend API Key

1. Sign up for a free account at [resend.com](https://resend.com)
2. Navigate to **API Keys** in the dashboard
3. Click **Create API Key**
4. Give it a name (e.g., "Eriník Development")
5. Copy the generated API key (starts with `re_`)

### 4. Configure Email Settings

1. Copy the example config file:
   ```bash
   cp scripts/backend/email-config.example.php scripts/backend/email-config.php
   ```

2. Edit `scripts/backend/email-config.php` with your settings:
   ```bash
   nano scripts/backend/email-config.php
   # or use your preferred editor
   ```

3. Update the configuration:

#### Author Email Addresses
Replace the example emails with real addresses:
```php
'author_emails' => [
    'eric' => 'eric.henderson@bhcc.edu',
    'inês' => 'ineves@bhcc.edu',
    'níko' => 'niko.ferdinand@bhcc.edu'
],
```

#### Resend API Configuration

**For Development (Test Domain):**
```php
'resend' => [
    'api_key' => 're_your_api_key_here',
    'from_email' => 'onboarding@resend.dev',  // Resend's test domain
    'from_name' => 'Eriník Contact Form'
],
```

**For Production (Verified Domain):**
```php
'resend' => [
    'api_key' => 're_your_api_key_here',
    'from_email' => 'noreply@erinik.com',  // Your verified domain
    'from_name' => 'Eriník Contact Form'
],
```

### 4. Domain Verification (For Production)

To use your own domain for sending emails:

1. Go to **Domains** in your Resend dashboard
2. Click **Add Domain**
3. Enter your domain (e.g., `erinik.com`)
4. Add the DNS records Resend provides to your domain registrar
5. Wait for verification (usually a few minutes)
6. Update `from_email` to use your verified domain

### 5. Testing the Setup

1. Set debug mode to `true` in `email-config.php`:
   ```php
   'settings' => [
       'debug' => true,
   ]
   ```

2. Open the contact page in your browser
3. Click on an author and fill out the form
4. Submit and check for any error messages

5. Once working, set debug back to `false`:
   ```php
   'settings' => [
       'debug' => false,
   ]
   ```

## Troubleshooting

### "email-config.php not found"
- Make sure you copied `email-config.example.php` to `email-config.php`
- Check that it's in the `scripts/backend/` directory

### "API Error" or emails not sending
- Verify your Resend API key is correct (starts with `re_`)
- Check that your API key hasn't been revoked in the Resend dashboard
- Ensure your domain is verified if using a custom domain
- For production, make sure `from_email` uses your verified domain

### "Domain not verified" error
- Complete domain verification in Resend dashboard
- Verify DNS records are correctly configured
- Wait a few minutes for DNS propagation
- Try using `onboarding@resend.dev` for testing

### Emails not arriving
- Check your spam/junk folder
- Verify the recipient email addresses are correct
- Enable debug mode to see detailed error messages
- Check Resend dashboard logs for delivery status

## Security Notes

- **NEVER** commit `email-config.php` to version control (it's in `.gitignore`)
- Keep your Resend API key secure and never expose it publicly
- Regenerate your API key immediately if it's compromised
- Use environment variables for production deployments (see DEPLOYMENT.md)
- Regularly update dependencies: `composer update`

## Email Features

The contact form includes:
- ✅ HTML and plain text email versions
- ✅ Professional email template with Eriník branding
- ✅ Proper Reply-To headers (emails go to the submitter)
- ✅ Input validation and XSS sanitization
- ✅ Resend API integration with performance tracking
- ✅ Error handling and user feedback
- ✅ Email delivery logging and timing metrics

## Support

- **Resend Documentation:** [resend.com/docs](https://resend.com/docs)
- **Resend PHP SDK:** [github.com/resendlabs/resend-php](https://github.com/resendlabs/resend-php)
- **Deployment Guide:** See `DEPLOYMENT.md` for production setup
