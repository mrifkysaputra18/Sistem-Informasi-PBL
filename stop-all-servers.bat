@echo off
echo Stopping all Laravel servers...
echo.

REM Kill all PHP artisan serve processes
taskkill /F /FI "WINDOWTITLE eq Laravel Server*" /T >nul 2>&1

REM Alternative: Kill all PHP processes (be careful if you have other PHP apps)
REM taskkill /F /IM php.exe /T >nul 2>&1

echo.
echo ========================================
echo All Laravel servers stopped!
echo ========================================
echo.
pause
