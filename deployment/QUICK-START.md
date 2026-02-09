# Quick Reference: Cron Jobs for Shared Hosting

## Add These 2 Cron Jobs in cPanel

### 1. Laravel Scheduler (Every Minute)
```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Queue Worker (Every Minute)
```bash
* * * * * cd /path/to/your/project && php artisan queue:work database --stop-when-empty --max-time=3600 >> /dev/null 2>&1
```

---

## ⚠️ IMPORTANT: Replace `/path/to/your/project`

Replace with your actual server path, for example:
- `/home/username/public_html/chatboat-ai`
- `/var/www/chatboat-ai`
- `/home/yourdomain.com/public_html`

---

## How to Find Your Path

SSH into your server and run:
```bash
pwd
```

Or in cPanel File Manager, check the path at the top.

---

## Testing Commands

Before adding to cron, test manually via SSH:

```bash
cd /path/to/your/project
php artisan schedule:run
php artisan queue:work database --stop-when-empty
```

If these work, your cron jobs will work too!

---

## Full Documentation

See [CRON-JOBS.md](./CRON-JOBS.md) for complete guide with troubleshooting.
