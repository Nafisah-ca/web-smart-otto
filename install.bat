@echo off
echo ================================================
echo  Smart Otto - Vehicle Inspection System Setup
echo ================================================
echo.

set PHP=D:\laragon\bin\php\php-8.3.12-nts-Win32-vs16-x64\php.exe
set NODE=D:\laragon\bin\nodejs\node-v22.14.0-win-x64
set PATH=%NODE%;%PATH%

echo [1/6] Installing Composer dependencies...
set COMPOSER=D:\laragon\bin\composer\composer.phar
%PHP% %COMPOSER% install --no-interaction
echo.

echo [2/6] Generating Application Key...
%PHP% artisan key:generate --ansi
echo.

echo [3/6] Running Database Migration + Seeder...
%PHP% artisan migrate:fresh --seed --force
echo.

echo [4/6] Creating storage symlink...
%PHP% artisan storage:link
echo.

echo [5/6] Building frontend assets...
%NODE%\node.exe node_modules\vite\bin\vite.js build
echo.

echo [6/6] Clearing cache...
%PHP% artisan config:clear
%PHP% artisan cache:clear
%PHP% artisan view:clear
echo.

echo ================================================
echo  SELESAI! Smart Otto siap digunakan.
echo.
echo  Tambahkan di hosts file:
echo    127.0.0.1  smartotto.test
echo.
echo  Buka: http://smartotto.test
echo.
echo  Login Admin    : admin@smartotto.test / password
echo  Login Inspektor: budi@smartotto.test  / password
echo  Login Customer : andi@gmail.com       / password
echo ================================================
pause
