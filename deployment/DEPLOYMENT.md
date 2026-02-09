# Deployment Guide - Shared Hosting

## Prerequisites

- Shared hosting with SSH access
- PHP 8.2 or higher
- MySQL database
- Composer installed
- cPanel or similar control panel

---

## Deployment Steps

### 1. Upload Files

Upload your Laravel application to your server:

```bash
# Via Git (recommended)
cd /path/to/your/project
git clone https://github.com/yourusername/chatboat-ai.git .

# Or upload via FTP/cPanel File Manager
```

### 2. Install Dependencies

```bash
cd /path/to/your/project
composer install --optimize-autoloader --no-dev
```

### 3. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit .env with your production settings
nano .env
```

**Important .env settings:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

QUEUE_CONNECTION=database

OPENAI_API_KEY=your_openai_api_key
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate --force
```

### 6. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Setup Cron Jobs

**Add these 2 cron jobs in cPanel:**

See [QUICK-START.md](./QUICK-START.md) for the exact commands.

1. **Laravel Scheduler** - Every minute
2. **Queue Worker** - Every minute

### 9. Configure Web Server

**For cPanel/Shared Hosting:**

1. Point your domain to the `public` folder
2. Or create a `.htaccess` in root to redirect to `public`

**Example .htaccess (if needed):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 10. Test Your Application

1. Visit your domain
2. Test chatbot functionality
3. Check logs: `tail -f storage/logs/laravel.log`

---

## Updating Your Application

```bash
cd /path/to/your/project

# Pull latest changes
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Troubleshooting

### 500 Internal Server Error
- Check `storage/logs/laravel.log`
- Verify file permissions (755 for directories, 644 for files)
- Clear cache: `php artisan cache:clear`

### Chatbot Not Working
- Verify OpenAI API key in `.env`
- Check queue jobs: `php artisan queue:monitor database`
- Verify cron jobs are running

### Database Connection Error
- Verify database credentials in `.env`
- Check if database exists
- Test connection: `php artisan db:show`

### Queue Jobs Not Processing
- Verify cron jobs are added in cPanel
- Test manually: `php artisan queue:work database --stop-when-empty`
- Check cron logs in cPanel

---

## Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong `APP_KEY`
- [ ] Secure database credentials
- [ ] Set proper file permissions
- [ ] Enable HTTPS
- [ ] Keep `.env` file secure (not in git)
- [ ] Regularly update dependencies

---

## Monitoring

### Check Queue Status
```bash
php artisan queue:monitor database
```

### View Logs
```bash
tail -f storage/logs/laravel.log
tail -f storage/logs/worker.log
```

### Check Scheduled Tasks
```bash
php artisan schedule:list
```

---

## Support Files

- [QUICK-START.md](./QUICK-START.md) - Quick cron job reference
- [CRON-JOBS.md](./CRON-JOBS.md) - Detailed cron job documentation
- [supervisor-chatboat.conf](./supervisor-chatboat.conf) - For VPS (not shared hosting)
