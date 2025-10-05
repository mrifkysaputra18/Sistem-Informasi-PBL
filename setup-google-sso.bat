@echo off
title Setup Google SSO - Sistem PBL

echo.
echo ========================================
echo  SETUP GOOGLE SSO - SISTEM PBL
echo ========================================
echo.

REM Cek apakah file .env sudah ada
if exist .env (
    echo [INFO] File .env sudah ada
    echo.
) else (
    echo [WARNING] File .env belum ada!
    echo.
    echo Apakah Anda ingin membuat file .env dari .env.example?
    set /p create_env="Ketik Y untuk ya, N untuk tidak: "
    
    if /i "%create_env%"=="Y" (
        if exist .env.example (
            copy .env.example .env
            echo [SUCCESS] File .env berhasil dibuat!
            echo.
            
            echo [INFO] Generate application key...
            php artisan key:generate
            echo.
        ) else (
            echo [ERROR] File .env.example tidak ditemukan!
            echo.
            goto END
        )
    ) else (
        echo [INFO] Setup dibatalkan. Silakan buat file .env terlebih dahulu.
        goto END
    )
)

echo.
echo ========================================
echo  INPUT GOOGLE OAUTH CREDENTIALS
echo ========================================
echo.
echo Silakan masukkan credentials dari Google Cloud Console
echo Panduan lengkap: AKTIFKAN_GOOGLE_SSO.md
echo.

set /p client_id="Google Client ID: "
set /p client_secret="Google Client Secret: "

if "%client_id%"=="" (
    echo [ERROR] Client ID tidak boleh kosong!
    goto END
)

if "%client_secret%"=="" (
    echo [ERROR] Client Secret tidak boleh kosong!
    goto END
)

echo.
echo [INFO] Menambahkan konfigurasi ke .env...

REM Cek apakah sudah ada konfigurasi Google
findstr /C:"GOOGLE_CLIENT_ID" .env >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo [INFO] Konfigurasi Google sudah ada, akan diupdate...
    
    REM Backup .env
    copy .env .env.backup >nul
    
    REM Update konfigurasi
    powershell -Command "(Get-Content .env) -replace 'GOOGLE_CLIENT_ID=.*', 'GOOGLE_CLIENT_ID=%client_id%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace 'GOOGLE_CLIENT_SECRET=.*', 'GOOGLE_CLIENT_SECRET=%client_secret%' | Set-Content .env"
    powershell -Command "(Get-Content .env) -replace 'GOOGLE_REDIRECT_URI=.*', 'GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback' | Set-Content .env"
    
    echo [SUCCESS] Konfigurasi berhasil diupdate!
) else (
    echo [INFO] Menambahkan konfigurasi Google baru...
    
    REM Tambah konfigurasi baru
    (
        echo.
        echo # Google OAuth SSO
        echo GOOGLE_CLIENT_ID=%client_id%
        echo GOOGLE_CLIENT_SECRET=%client_secret%
        echo GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
    ) >> .env
    
    echo [SUCCESS] Konfigurasi berhasil ditambahkan!
)

echo.
echo ========================================
echo  CLEAR CACHE
echo ========================================
echo.

echo [INFO] Clearing Laravel cache...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear

echo.
echo ========================================
echo  SETUP SELESAI!
echo ========================================
echo.
echo [SUCCESS] Google SSO sudah siap digunakan!
echo.
echo Cara menggunakan:
echo 1. Jalankan: php artisan serve
echo 2. Buka: http://localhost:8000/login
echo 3. Klik "Login dengan Google Politala"
echo 4. Login dengan email @politala.ac.id atau @mhs.politala.ac.id
echo.
echo Dokumentasi: AKTIFKAN_GOOGLE_SSO.md
echo.

:END
pause

