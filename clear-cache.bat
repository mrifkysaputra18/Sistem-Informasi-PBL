@echo off
echo Clearing Laravel cache...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo Cache cleared successfully!
echo.
echo IMPORTANT: Now do the following in your browser:
echo 1. Press Ctrl+Shift+Delete
echo 2. Clear "Cached images and files"
echo 3. Or simply press Ctrl+F5 (hard refresh) on the page
echo.
pause
