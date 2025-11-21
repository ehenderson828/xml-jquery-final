# Email Setup Guide

This guide will help you configure the PHPMailer-based contact form for the Eriník website.

## Prerequisites

- PHP 7.4 or higher
- Composer (PHP package manager)
- An email account with SMTP access (Gmail, Outlook, etc.)

## Installation Steps

### 1. Install Composer (if not already installed)

**Linux/macOS:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**Windows:**
Download and install from [getcomposer.org](https://getcomposer.org/)

### 2. Install PHPMailer

Navigate to the project directory and run:

```bash
cd /home/ehenderson828/bhcc/fall2025/xml/xml-jquery-final
composer install
```

This will install PHPMailer and create a `vendor/` directory.

### 3. Configure Email Settings

1. Copy the example config file:
   ```bash
   cp scripts/email-config.example.php scripts/email-config.php
   ```

2. Edit `scripts/email-config.php` with your settings:
   ```bash
   nano scripts/email-config.php
   # or use your preferred editor
   ```

3. Update the configuration:

#### Author Email Addresses
Replace the example emails with real addresses:
```php
'author_emails' => [
    'eric' => 'eric@actual-domain.com',
    'inês' => 'ines@actual-domain.com',
    'níko' => 'niko@actual-domain.com'
],
```

#### SMTP Configuration

**For Gmail:**
```php
'smtp' => [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'encryption' => 'tls',
    'username' => 'your-email@gmail.com',
    'password' => 'your-app-password',  // See below for App Password setup
    'from_email' => 'noreply@yourdomain.com',
    'from_name' => 'Eriník Contact Form'
],
```

**For Outlook/Office365:**
```php
'smtp' => [
    'host' => 'smtp.office365.com',
    'port' => 587,
    'encryption' => 'tls',
    'username' => 'your-email@outlook.com',
    'password' => 'your-password',
    'from_email' => 'noreply@yourdomain.com',
    'from_name' => 'Eriník Contact Form'
],
```

### 4. Setting Up Gmail App Passwords (if using Gmail)

Gmail requires App Passwords for security when using SMTP:

1. Go to your Google Account: [myaccount.google.com](https://myaccount.google.com)
2. Select **Security**
3. Under "Signing in to Google," select **2-Step Verification** (enable if not already enabled)
4. At the bottom, select **App passwords**
5. Select **Mail** and **Other (Custom name)**
6. Enter "Eriník Contact Form"
7. Click **Generate**
8. Copy the 16-character password and use it in your `email-config.php`

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
- Check that it's in the `scripts/` directory

### "SMTP Error: Could not authenticate"
- Double-check your username and password
- For Gmail, make sure you're using an App Password, not your regular password
- Verify 2-Step Verification is enabled for Gmail

### "SMTP connect() failed"
- Check your SMTP host and port settings
- Verify your firewall isn't blocking the connection
- Try changing the port (587 for TLS, 465 for SSL)

### Emails not arriving
- Check your spam/junk folder
- Verify the recipient email addresses are correct
- Enable debug mode to see detailed error messages

## Security Notes

- **NEVER** commit `email-config.php` to version control (it's in `.gitignore`)
- Keep your SMTP credentials secure
- Use environment variables for production deployments
- Regularly update PHPMailer: `composer update phpmailer/phpmailer`

## Email Features

The contact form includes:
- ✅ HTML and plain text email versions
- ✅ Professional email template with Eriník branding
- ✅ Proper Reply-To headers (emails go to the submitter)
- ✅ Input validation and sanitization
- ✅ SMTP authentication
- ✅ Error handling and user feedback

## Support

For PHPMailer documentation, visit: [github.com/PHPMailer/PHPMailer](https://github.com/PHPMailer/PHPMailer)
