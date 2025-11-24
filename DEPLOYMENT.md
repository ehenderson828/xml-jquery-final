# Deployment Guide

This guide covers deploying the Eriník contact form to Railway or Heroku.

## How the Code Works

The `send-email.php` script uses a **hybrid configuration approach**:

1. **Production (Railway/Heroku):** Uses environment variables
2. **Local Development:** Falls back to `email-config.php`

No code changes needed between environments!

---

## Environment Variables for Production

When deploying to Railway or Heroku, set these environment variables in the platform's dashboard:

### Required Variables

```
SMTP_HOST=smtp.gmail.com
SMTP_USERNAME=erinik.contact@gmail.com
SMTP_PASSWORD=lylzvlpwegnicpot
```

### Optional Variables (with defaults)

```
SMTP_PORT=587
SMTP_ENCRYPTION=tls
SMTP_FROM_EMAIL=noreply@erinik.com
SMTP_FROM_NAME=Eriník Contact Form
DEBUG_MODE=false
```

### Author Email Addresses (optional, defaults provided)

```
AUTHOR_EMAIL_ERIC=eric.henderson@bhcc.edu
AUTHOR_EMAIL_INES=ineves@bhcc.edu
AUTHOR_EMAIL_NIKO=niko.ferdinand@bhcc.edu
```

---

## Railway Deployment Steps

### 1. Create Railway Account
- Go to [railway.app](https://railway.app)
- Sign up with GitHub

### 2. Create New Project
- Click "New Project"
- Select "Deploy from GitHub repo"
- Choose your repository

### 3. Set Environment Variables
- Go to your project dashboard
- Click "Variables" tab
- Add all the environment variables listed above

### 4. Deploy
- Railway automatically deploys when you push to GitHub
- Your site will be available at a Railway-provided URL

---

## Heroku Deployment Steps

### 1. Create Heroku Account
- Go to [heroku.com](https://heroku.com)
- Sign up for free

### 2. Install Heroku CLI (optional)
```bash
# Or use the Heroku dashboard web interface
curl https://cli-assets.heroku.com/install.sh | sh
```

### 3. Create New App
- Dashboard → New → Create new app
- Choose app name and region

### 4. Connect GitHub
- Deploy tab → GitHub → Connect repository

### 5. Set Environment Variables
- Settings tab → Config Vars → Reveal Config Vars
- Add all the environment variables listed above

### 6. Deploy
- Deploy tab → Manual deploy → Deploy Branch
- Or enable automatic deploys

---

## Testing Locally (No Changes Needed!)

Your local setup continues to work as-is:

```bash
# Start PHP server
php -S localhost:8080

# Visit
http://localhost:8080/pages/contact.html
```

The code automatically uses `email-config.php` when environment variables aren't set.

---

## Verification Checklist

**Before deploying:**
- [ ] All environment variables set in platform dashboard
- [ ] `composer.json` is in your repository
- [ ] `.gitignore` excludes `email-config.php` and `vendor/`

**After deploying:**
- [ ] Visit your deployed contact page
- [ ] Submit a test form
- [ ] Check recipient email inbox
- [ ] Verify no errors in platform logs

---

## Troubleshooting

### "Configuration not found" error
- Check that environment variables are set in the platform dashboard
- Verify `SMTP_HOST` is set (this triggers env var mode)

### Emails not sending
- Check platform logs for SMTP errors
- Verify SMTP credentials are correct
- Try setting `DEBUG_MODE=true` temporarily to see detailed errors

### Composer dependencies not installing
- Ensure `composer.json` exists in repository root
- Check platform build logs for Composer errors

---

## Security Notes

✅ **Do commit to git:**
- `send-email.php`
- `composer.json`
- `email-config.example.php`
- This DEPLOYMENT.md file

❌ **Never commit to git:**
- `email-config.php` (local credentials)
- `.env` (if you create one)
- `vendor/` (Composer dependencies)

---

## Support Resources

- **Railway Docs:** [docs.railway.app](https://docs.railway.app)
- **Heroku PHP Docs:** [devcenter.heroku.com/categories/php-support](https://devcenter.heroku.com/categories/php-support)
- **PHPMailer:** [github.com/PHPMailer/PHPMailer](https://github.com/PHPMailer/PHPMailer)
