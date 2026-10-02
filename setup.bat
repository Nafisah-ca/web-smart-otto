@echo off
chcp 65001 >nul
echo.
echo  ╔══════════════════════════════════════════╗
echo  ║    Smart Otto — Setup Setelah Clone      ║
echo  ╚══════════════════════════════════════════╝
echo.

:: ── Cari PHP ──────────────────────────────────────────────────
set PHP=
for %%P in (
    "D:\laragon\bin\php\php-8.3.12-nts-Win32-vs16-x64\php.exe"
    "C:\laragon\bin\php\php-8.3.12-nts-Win32-vs16-x64\php.exe"
    "D:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe"
    "C:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe"
    "D:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe"
) do (
    if exist %%P set PHP=%%P
)

:: Fallback: cari php di PATH
if "%PHP%"=="" (
    where php >nul 2>&1
    if not errorlevel 1 set PHP=php
)

if "%PHP%"=="" (
    echo [ERROR] PHP tidak ditemukan!
    echo Pastikan Laragon/XAMPP sudah terinstall dan PHP ada di PATH.
    pause
    exit /b 1
)
echo [OK] PHP ditemukan: %PHP%

:: ── Cari Node ─────────────────────────────────────────────────
set NODE=
for %%N in (
    "D:\laragon\bin\nodejs\node-v22.14.0-win-x64\node.exe"
    "C:\laragon\bin\nodejs\node-v22.14.0-win-x64\node.exe"
    "D:\laragon\bin\nodejs\node-v18\node.exe"
    "C:\laragon\bin\nodejs\node-v18\node.exe"
) do (
    if exist %%N set NODE=%%N
)

if "%NODE%"=="" (
    where node >nul 2>&1
    if not errorlevel 1 set NODE=node
)

if "%NODE%"=="" (
    echo [WARN] Node.js tidak ditemukan, npm run build akan dilewati.
)

:: ── Cari Composer ─────────────────────────────────────────────
set COMPOSER=
for %%C in (
    "D:\laragon\bin\composer\composer.phar"
    "C:\laragon\bin\composer\composer.phar"
) do (
    if exist %%C set COMPOSER=%%C
)

echo.
echo [1/6] Menyalin .env.example ke .env ...
if not exist ".env" (
    copy .env.example .env
    echo      .env berhasil dibuat.
) else (
    echo      .env sudah ada, dilewati.
)

echo.
echo [2/6] Generate APP_KEY ...
if "%COMPOSER%"=="" (
    %PHP% artisan key:generate --ansi
) else (
    %PHP% artisan key:generate --ansi
)

echo.
echo [3/6] Install Composer dependencies ...
if "%COMPOSER%"=="" (
    if exist "vendor\autoload.php" (
        echo      vendor sudah ada, dilewati.
    ) else (
        echo [ERROR] Composer tidak ditemukan dan vendor belum ada!
        echo Jalankan: composer install
        pause
        exit /b 1
    )
) else (
    %PHP% %COMPOSER% install --no-interaction
)

echo.
echo [4/6] Jalankan migrasi database ...
echo      Pastikan database 'smartotto' sudah dibuat di MySQL!
%PHP% artisan migrate --force
if errorlevel 1 (
    echo.
    echo [ERROR] Migrasi gagal!
    echo Cek koneksi database di file .env ^(DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD^)
    pause
    exit /b 1
)

echo.
echo [5/6] Jalankan seeder ...
%PHP% artisan db:seed --force

echo.
echo [6/6] Buat storage symlink ...
%PHP% artisan storage:link

:: ── Build assets jika Node tersedia ──────────────────────────
if not "%NODE%"=="" (
    echo.
    echo [7/7] Build frontend assets ^(npm run build^) ...
    if exist "node_modules\vite\bin\vite.js" (
        %NODE% node_modules\vite\bin\vite.js build
    ) else (
        echo      node_modules belum ada, jalankan: npm install
    )
)

echo.
echo  ╔══════════════════════════════════════════╗
echo  ║   SETUP SELESAI!                         ║
echo  ║                                          ║
echo  ║   Jalankan: php artisan serve            ║
echo  ║   Buka    : http://127.0.0.1:8000        ║
echo  ║                                          ║
echo  ║   Admin   : admin@smartotto.test         ║
echo  ║   Password: Admin@2026                   ║
echo  ╚══════════════════════════════════════════╝
echo.
pause
