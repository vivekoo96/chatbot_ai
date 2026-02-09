# Cron Jobs for Shared Hosting

## IMPORTANT: Shared Hosting Setup
Since you're on shared hosting, you cannot use Supervisor. Instead, use these cron jobs.

---

## Cron Jobs to Add

Add these cron jobs in your hosting control panel (cPanel, Plesk, etc.):

### 1. Laravel Scheduler (Required)
Runs every minute to check for scheduled tasks (like monthly subscription resets).

```bash
* * * * * cd /var/www/chatboat-ai && php artisan schedule:run >> /dev/null 2>&1
```

**What it does:** Executes Laravel's task scheduler every minute.

---

### 2. Queue Worker (Required for Background Jobs)
Processes queued jobs like website crawling. Run every minute.

```bash
* * * * * cd /var/www/chatboat-ai && php artisan queue:work database --stop-when-empty --max-time=3600 >> /dev/null 2>&1
```

**What it does:** 
- Processes all pending jobs in the queue
- Stops when queue is empty (important for shared hosting)
- Maximum runtime of 1 hour per execution
- Runs every minute to pick up new jobs

**Alternative (if you want more control):**
```bash
* * * * * cd /var/www/chatboat-ai && timeout 3600 php artisan queue:work database --sleep=3 --tries=3 --max-time=3600 >> /dev/null 2>&1
```

---

### 3. Optional: Queue Monitoring
Check queue status every 5 minutes (optional, for monitoring).

```bash
*/5 * * * * cd /var/www/chatboat-ai && php artisan queue:monitor database >> /var/www/chatboat-ai/storage/logs/queue-monitor.log 2>&1
```

---

## How to Add Cron Jobs in cPanel

1. **Login to cPanel**
2. **Find "Cron Jobs"** section
3. **For each cron job above:**
   - Select frequency: `* * * * *` (every minute)
   - Enter the command
   - Click "Add New Cron Job"

---

## Important Notes

> [!IMPORTANT]
> **Replace `/var/www/chatboat-ai`** with your actual server path!

> [!WARNING]
> **Shared Hosting Limitations:**
> - Queue workers cannot run continuously
> - Jobs process every minute instead of instantly
> - Long-running jobs (>60 seconds) may timeout
> - Some hosts limit cron job frequency

> [!TIP]
> **Testing Your Cron Jobs:**
> Run these commands manually via SSH to test:
> ```bash
> cd /var/www/chatboat-ai
> php artisan schedule:run
> php artisan queue:work database --stop-when-empty
> ```

---

## Cron Job Explanation

### Cron Syntax: `* * * * *`
```
* * * * *
│ │ │ │ │
│ │ │ │ └─── Day of week (0-7, Sunday = 0 or 7)
│ │ │ └───── Month (1-12)
│ │ └─────── Day of month (1-31)
│ └───────── Hour (0-23)
└─────────── Minute (0-59)
```

**Examples:**
- `* * * * *` = Every minute
- `*/5 * * * *` = Every 5 minutes
- `0 * * * *` = Every hour
- `0 0 * * *` = Daily at midnight

---

## Queue Worker Options Explained

- `--stop-when-empty` - Exit when no jobs in queue (important for shared hosting)
- `--max-time=3600` - Run for maximum 1 hour (3600 seconds)
- `--sleep=3` - Wait 3 seconds between jobs
- `--tries=3` - Retry failed jobs 3 times
- `--timeout=600` - Individual job timeout (10 minutes)
- `>> /dev/null 2>&1` - Suppress output (optional)

---

## Troubleshooting

### Jobs not processing?
1. Check if cron jobs are running: `grep CRON /var/log/syslog`
2. Check Laravel logs: `tail -f storage/logs/laravel.log`
3. Verify queue has jobs: `php artisan queue:monitor database`

### Cron job errors?
1. Check cron logs in cPanel
2. Test command manually via SSH
3. Verify PHP path: `which php`
4. Some hosts require full PHP path: `/usr/bin/php` or `/usr/local/bin/php`

### Jobs timing out?
1. Reduce `--max-time` value
2. Break large jobs into smaller chunks
3. Increase PHP `max_execution_time` in php.ini
