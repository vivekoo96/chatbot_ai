@echo off
echo ========================================
echo Website Crawling ^& RAG System Setup
echo ========================================
echo.

echo Step 1: Installing Symfony DomCrawler...
composer install
if %errorlevel% neq 0 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)
echo ✓ Packages installed successfully
echo.

echo Step 2: Running database migrations...
php artisan migrate --force
if %errorlevel% neq 0 (
    echo ERROR: Migration failed!
    pause
    exit /b 1
)
echo ✓ Migrations completed successfully
echo.

echo Step 3: Checking queue configuration...
php artisan queue:failed-table
php artisan migrate
echo ✓ Queue tables ready
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Start the queue worker: php artisan queue:work
echo 2. Test the widget at: http://chatboat-ai.test/test-widget.html
echo 3. Check the deployment_guide.md for testing instructions
echo.
pause
