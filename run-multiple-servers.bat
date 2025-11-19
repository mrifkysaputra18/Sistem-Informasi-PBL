@echo off
echo ========================================
echo  Starting Multiple Laravel Servers
echo ========================================
echo.
echo Port 8000: Admin/Default
echo Port 8001: Dosen
echo Port 8002: Mahasiswa
echo Port 8003: Koordinator
echo.
echo Press Ctrl+C in any window to stop
echo ========================================
echo.

REM Start server on port 8000 (Admin)
start "Laravel Server - Port 8000 (Admin)" cmd /k "php artisan serve --port=8000"
timeout /t 2 /nobreak >nul

REM Start server on port 8001 (Dosen)
start "Laravel Server - Port 8001 (Dosen)" cmd /k "php artisan serve --port=8001"
timeout /t 2 /nobreak >nul

REM Start server on port 8002 (Mahasiswa)
start "Laravel Server - Port 8002 (Mahasiswa)" cmd /k "php artisan serve --port=8002"
timeout /t 2 /nobreak >nul

REM Start server on port 8003 (Koordinator)
start "Laravel Server - Port 8003 (Koordinator)" cmd /k "php artisan serve --port=8003"
timeout /t 2 /nobreak >nul

echo.
echo ========================================
echo All servers started!
echo ========================================
echo.
echo Open these URLs in different browsers/tabs:
echo.
echo   Admin:       http://127.0.0.1:8000
echo   Dosen:       http://127.0.0.1:8001
echo   Mahasiswa:   http://127.0.0.1:8002
echo   Koordinator: http://127.0.0.1:8003
echo.
echo Close this window to keep all servers running
echo Or press any key to exit...
pause >nul
